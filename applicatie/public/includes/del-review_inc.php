<?php
include '../includes/functies_inc.php';
include '../requirers/mail_inc.php';

$http_ref = $_SERVER['HTTP_REFERER'];
if(strpos($http_ref, "?pop")){
    $address = cleanUrl($http_ref, "?pop");
}else if(strpos($http_ref, "&pop")){
    $address = cleanUrl($http_ref, "&pop");
}else if(!strpos($http_ref, "?pop")){
    $address = $http_ref;
}

if(isset($_POST["submit-remove-review"])){
    require '../requirers/dbh_inc.php';
    
    session_start();
    $reviewId = $_POST["hidden-review-number"];
    $getId = $_GET["r"];
    $isBlocked = htmlspecialchars($_POST["review-blocked-status"]);
    // check op lege input
    if(empty($reviewId) || empty($getId)){
        header("Location: ../php/verkoper.php?pop=empty input");
        exit();
    }
    // check of de GET en POST gelijk is zodat er niet iets is aangepast
    if($reviewId == $getId){
        $id = $getId;
        $checkBlocked = $dbh->prepare("SELECT * FROM Feedback 
        INNER JOIN Voorwerp ON Feedback.voorwerp = Voorwerp.voorwerpnummer
        INNER JOIN Gebruiker ON Voorwerp.verkoper = Gebruiker.gebruikersnaam
        WHERE reviewnummer = ?");
        $checkBlocked->execute(array($id));
        $fetchCheckBlocked = $checkBlocked->fetchAll(PDO::FETCH_ASSOC);
        if(sizeof($fetchCheckBlocked) == 1){
            $updateBlocked = $dbh->prepare("UPDATE feedback SET isGeblokkeerd = ? WHERE reviewnummer = ?");
            $updateBlocked->execute(array($isBlocked ? 0 : 1,$id));
            if($isBlocked) {
                $newStatus = 'geblokkeerd';
            } else {
                $newStatus = 'gedeblokkeerd';
            }
            sendBlockedMail($dbh, $mail, $fetchCheckBlocked[0]['mailbox'], $newStatus, 'review', $fetchCheckBlocked[0]['beschrijving']);
            header("Location: ".$address."?pop=review blocked");
            exit();
        }else{
            header("Location: ../php/beheren.php?activeTab=reviews&pop=review unblocked");
            exit(); 
        }
    }else{
        header("Location: ".$address."?pop=no review found");
        exit(); 
    }
}else{
    header("Location: ".$address."?pop=nice try");
    exit(); 
}
