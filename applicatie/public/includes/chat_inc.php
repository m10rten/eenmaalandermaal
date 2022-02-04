<?php
$http_ref = $_SERVER['HTTP_REFERER'];
if(strpos($http_ref, "?pop")){
    $address = cleanUrl($http_ref);
}else if(strpos($http_ref, "&pop")){
    $address = cleanUrl($http_ref, "&pop");
}else if(!strpos($http_ref, "?pop")){
    $address = $http_ref;
}

if(isset($_POST["submit-chat"])){
    session_start();
    date_default_timezone_set('Europe/Amsterdam');
    if(empty($_POST["message-chat"])){
        header("Location: ".$address."&pop=empty input");
        exit();
    }
    if(isset($_GET["id"])  && $_GET["id"] !== ""){
        include '../includes/functies_inc.php';
        require '../requirers/dbh_inc.php';
        $reciever = $_GET["id"];
        $sender = $_SESSION["User"]["gebruikersnaam"];
        if(userExists($dbh, $reciever, $reciever)){
            $message = htmlspecialchars($_POST["message-chat"]);
            $date = date("d-m-Y");
            $time = date("H:i");
            echo $sender."<br> ".$reciever."<br> ".$date."<br> ".$time."<br> ".$message."<br>";
            $checkChat = $dbh->prepare("SELECT * FROM Chats 
                WHERE (gebruiker1 = ? OR gebruiker2 = ?) 
                    AND (gebruiker1 = ? OR gebruiker2 = ?)");
            $checkChat->execute(array($sender, $sender, $reciever, $reciever));
            $fetchCheckChat = $checkChat->fetchAll(PDO::FETCH_ASSOC);
            if(sizeof($fetchCheckChat) == 1){
                $chatId = $fetchCheckChat[0]["chatId"];
                $insertMsg = $dbh->prepare("INSERT INTO Berichten 
                (chatId, verzender, ontvanger, dag, tijdstip, bericht)
                VALUES (?,?,?,?,?,?)");
                $insertMsg->execute(array((int)$chatId, $sender, $reciever, $date, $time, $message));
                
            }else{
                $insertChat = $dbh->prepare("INSERT INTO Chats 
                (gebruiker1, gebruiker2) 
                OUTPUT inserted.chatId
                VALUES (?,?);");
                $insertChat->execute(array($sender, $reciever));
                $chatIdOutput = $insertChat->fetch(PDO::FETCH_ASSOC);
                $insertMsg = $dbh->prepare("INSERT INTO Berichten 
                (chatId, verzender, ontvanger, dag, tijdstip, bericht)
                VALUES (?,?,?,?,?,?)");
                $insertMsg->execute(array((int)$chatIdOutput["chatId"], $sender, $reciever, $date, $time, $message));
                
            }
            header("Location: ".$address."#chat-block");
            exit();
            
        }else{
            header("Location: ../php/berichten.php?pop=user not found");
            exit();
        }
    }else{
        header("Location: ../php/berichten.php?pop=user not found");
        exit();
    }

}else{
    header("Location: ../php/berichten.php?pop=nice try");
    exit();
}