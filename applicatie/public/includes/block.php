<?php

require '../requirers/dbh_inc.php';
require '../requirers/mail_inc.php';
require '../includes/functies_inc.php';

if(isset($_POST["submit-user-block"])){
  $_SESSION['post-username-block'] = $username;
       
    session_start();

    $mailaddress = htmlspecialchars($_POST["user-block-mail"]);
    $blocked = htmlspecialchars($_POST["user-blocked"]);
    if($_SESSION["User"]["is_beheerder"] != 1){
        header("location: ../index.php?pop=nice try");
        exit();
    }
    if($blocked == 1) {
        activateUser($mailaddress, $dbh);
        header("location: ../php/beheren.php?activeTab=gebruikers&pop=user active");
        sendBlockedMail($dbh, $mail, $mailaddress, 'gedeblokkeerd'); 
        exit(); 
    } else {
        blockUser($mailaddress, $dbh);
        sendBlockedMail($dbh, $mail, $mailaddress, 'geblokkeerd', 'gebruiker'); 
        header("location: ../php/beheren.php?activeTab=gebruikers&pop=user nonactive");
        exit();
    }   
}

if(isset($_POST["submit-auction-block"])) {
    $auctionnumber = htmlspecialchars($_POST["auction-block-number"]);
    $isActive = htmlspecialchars($_POST["auction-block-status"]);

    session_start();

    if($_SESSION["User"]["is_beheerder"] != 1){
        header("location: ../index.php?pop=nice try");
        exit();
    }
    handleActiveStatusAuction($dbh, $auctionnumber, $isActive);
    
    if($isActive) {
        $newStatus = 'geblokkeerd';
    } else {
        $newStatus = 'gedeblokkeerd';
    }

    $query = $dbh->prepare('SELECT * FROM Voorwerp WHERE voorwerpnummer = ?');
    $query->execute(array($auctionnumber));
    $auction = $query->fetch(PDO::FETCH_ASSOC);

    $user = userFind($dbh,$auction['verkoper']);
    sendBlockedMail($dbh, $mail, $user['mailbox'], $newStatus, 'veiling', $auction['titel']); 
    header("location: ../php/beheren.php?activeTab=veilingen&pop=auction status changed");
}
function handleActiveStatusAuction($dbh, $auctionnumber, $isActive) {
    $query = $dbh->prepare("
    UPDATE Voorwerp
    SET isActief = ?
    WHERE voorwerpnummer = ?
    ");
    $query->execute(array($isActive ? 0 : 1, $auctionnumber));
}

function blockUser($mail, $dbh) {
    $query = $dbh->prepare("
    UPDATE Gebruiker
    SET is_geblokkeerd = 1
    WHERE mailbox = ?
    ");
    $query->execute(array($mail));
}

function activateUser($mail, $dbh) {
    $query = $dbh->prepare("
    UPDATE Gebruiker
    SET is_geblokkeerd = NULL
    WHERE mailbox = ?
    ");
    $query->execute(array($mail));
}
?>
