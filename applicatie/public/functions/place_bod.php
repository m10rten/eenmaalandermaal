<?php

require '../requirers/dbh_inc.php';
date_default_timezone_set('Europe/Amsterdam');
if(isset($_POST["submit-bod-veiling"])){
    session_start();

    $price = htmlspecialchars($_POST['bod-veiling']);
    $username = $_SESSION["User"]["gebruikersnaam"];
    
    $time = date('H:i');
    $date = date('d-n-Y');
    if(!isset($_GET["nr"])){
        header('Location: ../php/veiling.php?v='.$_GET["nr"].'&pop=id error');
        exit();
    }
    else if(isset($_GET["nr"])){
        $id = htmlspecialchars($_GET["nr"]);
    }
    if(!is_numeric($id)){
        header('Location: ../php/veiling.php?v='.$id.'&pop=id error');
        exit();
    }
    $query = $dbh->prepare("SELECT MAX(Bod.bodBedrag) AS huidigbod
     FROM Bod WHERE Bod.voorwerp = ?");
    $query->execute(array($id));
    $fetchHighest = $query->fetch(PDO::FETCH_ASSOC);
    // check of er al een bod is of niet
        $fetchItem = $dbh->prepare("SELECT * FROM voorwerp WHERE voorwerpnummer = ?");
        $fetchItem->execute(array($id));
        $fetchBid = $fetchItem->fetch(PDO::FETCH_ASSOC);
        $fetchedDate = $fetchBid["looptijdEindeDag"];
        $fetchedTime = $fetchBid["looptijdEindeTijdstip"];
    if((strtotime(date('d-n-Y')) > strtotime($fetchedDate)) || ((strtotime(date('d-n-Y')) == strtotime($fetchedDate)) && (strtotime(date('H:i')) >= strtotime($fetchedTime)))){
        header('Location: ../php/veiling.php?v='.$id.'&pop=auction closed');
        exit();
    }
    if(!sizeof($fetchHighest) == 1 || $fetchHighest['huidigbod'] == NULL){        
        $currentBid = $fetchBid["startprijs"];
    }
    // er is een bod en dan worden andere gegevens opgehaald
    else if(sizeof($fetchHighest) == 1 && $fetchHighest['huidigbod'] !== NULL){
        $currentBid =  $fetchHighest['huidigbod'];
        $queryBod = $dbh->prepare("SELECT * FROM bod
        INNER JOIN voorwerp ON voorwerp.voorwerpnummer = bod.voorwerp 
        WHERE bod.voorwerp = ? AND bod.bodBedrag = ?");
        $queryBod->execute(array($id, $currentBid));
        $fetchBid = $queryBod->fetch(PDO::FETCH_ASSOC);
        // check datum en tijd aan minuut van vorig bod
        if($fetchBid['bodDag'] == $date && $fetchBid['bodTijdstip'] == $time){            
            header('Location: ../php/veiling.php?v='.$id.'&pop=wait a minute');
            exit();            
        }
        // checken van gebruiker of deze verkoper is of vorige bieder        
        if($fetchBid['gebruiker'] == $username){
            header('Location: ../php/veiling.php?v='.$id.'&pop=same user');
            exit();
        }
    }
        if($fetchBid['verkoper'] == $username){
            header('Location: ../php/veiling.php?v='.$id.'&pop=same seller');
            exit();
        }else if($fetchBid['verkoper'] !== $username){   
            if($price <= $currentBid){
                header('Location: ../php/veiling.php?v='.$id.'&pop=invalid bid');
                exit();
            }
            else if($price > $currentBid){
                if($currentBid > 1.00 && $currentBid < 49.99){
                    if(($currentBid + 0.50) > $price){
                        header('Location: ../php/veiling.php?v='.$id.'&pop=increase bid');
                        exit();                        
                    }
                }
                if($currentBid > 49.99 && $currentBid < 499.99){
                    if(($currentBid + 1.00) > $price){
                        header('Location: ../php/veiling.php?v='.$id.'&pop=increase bid');
                        exit();
                    }
                }
                if($currentBid > 500.00 && $currentBid < 999.99){
                    if(($currentBid + 5.00) > $price){
                        header('Location: ../php/veiling.php?v='.$id.'&pop=increase bid');
                        exit();
                    }
                }
                if($currentBid > 1000.00 && $currentBid < 4999.99){
                    if(($currentBid + 10.00) > $price){
                        header('Location: ../php/veiling.php?v='.$id.'&pop=increase bid');
                        exit();
                    }
                }
                if($currentBid > 5000.00){
                    if(($currentBid + 50.00) > $price){
                        header('Location: ../php/veiling.php?v='.$id.'&pop=increase bid');
                        exit();
                    }
                }
                if($currentBid < 1.00){
                    header('Location: ../php/veiling.php?v='.$id.'&pop=increase bid');
                    exit();                
                }else{
                    $insertQuery = $dbh->prepare("INSERT INTO bod 
                    (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) 
                    VALUES (?,?,?,?,?);
                    ");                    
                    $insertQuery->execute(array($id, $price, $username, $date, $time));

                    header('Location: ../php/veiling.php?v='.$id.'&pop=bid placed');
                    exit();
                }  
            }
        }    
    else{
        header('Location: ../php/veiling.php?v='.$id.'&pop=server error bid');
        exit();
    }
}else{
    header('Location: ../php/veiling.php?v='.$id.'&pop=nice try');
    exit();
}