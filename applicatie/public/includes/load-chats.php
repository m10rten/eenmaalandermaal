<?php 
require '../requirers/dbh_inc.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if(isset($_POST["otherUser"]) && isset($_POST["loggedInUser"])){
    $user = $_POST["loggedInUser"];
    $chatUser = $_POST["otherUser"];
    $getMsg = $dbh->prepare("SELECT * FROM Berichten
    WHERE chatId IN (SELECT chatId FROM Chats 
    WHERE (gebruiker1 = ? OR gebruiker2 = ?) 
        AND (gebruiker1 = ? OR gebruiker2 = ?))ORDER BY berichtNummer ASC");
    $getMsg->execute(array($user, $user, $chatUser, $chatUser));
    $fetchMsg = $getMsg->fetchAll(PDO::FETCH_ASSOC);
    if(sizeof($fetchMsg) > 0){
    foreach($fetchMsg as $msg){
        echo '
        <div class="col s12">
            <div class="chat-message">
                <p class=" ';
                    if($msg["verzender"] == $user){
                        echo 'send-chat';
                    }else{
                        echo 'recieved-chat';
                    }
                echo ' ">';
                if($msg["isVerwijderd"] == 1){
                    echo 'dit bericht is verwijderd';
                }else{ echo $msg["bericht"]; }
            echo '<span class="right grey-text"> '; 
                if($msg["dag"] == date("d-m-Y")){echo $msg["tijdstip"];}else{echo $msg["dag"];}                                                
                echo '</span></p>
            </div>
        </div>';
    }
    echo '<div class="col s12 extra-margin-chat">
    </div>';
    }else{
    echo 'er zijn geen berichten hier, start een chat door een bericht te sturen.';
    }
}
?>