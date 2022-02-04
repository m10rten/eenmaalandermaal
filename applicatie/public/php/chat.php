<?php 
session_start();
    if(!isset($_SESSION["User"])){
        header("Location: ../php/login.php?pop=nice try");
        exit();
    }
    if(!isset($_GET["id"]) || $_GET["id"] == ""){
        header("Location: ../php/berichten.php");
        exit();
    }
    if(isset($_GET["id"]) && $_GET["id"] !== ""){
        $chatUser = $_GET["id"];
        $user = $_SESSION["User"]["gebruikersnaam"];
        include '../includes/functies_inc.php';
        require '../requirers/dbh_inc.php';
        if(!userExists($dbh,$chatUser, $chatUser)){
            header("Location: ../php/berichten.php?pop=user not found");
            exit();
        }
        if($user == $chatUser){
            header("Location: ../php/berichten.php?pop=nice try");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    <link href="../css/berichten.css" rel="stylesheet" />

    <title>EenmaalAndermaal | Berichten</title>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
     showBreadcrumbs("index","berichten.php","chat.php?id=".$chatUser."");
        include '../functions/pop_check.php';
        ?>
        <div class="container">
            <div class="row rounded z-depth-1">
                <div class="col s12">
                    <h4 class="center margin-top-0 margin-bottom-0">
                        Chat met: <?php echo $chatUser ?>
                    </h4>  
                </div>
                <div class="col s12">
                   <hr class="devide center" id="chat"> 
                </div>   
                <!-- chat box -->
                <div class="row">
                    <div class="col s12 chat-box" id="chat-box">
                        <div class="chat-block" id="chat-block">
                            <?php 
                            // get all messages from the chat
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
                            ?>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 center ">
                        <div class="col s12 input-box rounded z-depth-1 ">
                        <?php echo '  <form action="../includes/chat_inc.php?id='.$chatUser.'" method="post" autocomplete="off">'; ?>
                                <div class="col s9 m10 input-field ">
                                    <input id="message-chat" class="borderless-input" type="text" placeholder="typ een bericht..." name="message-chat" required>
                                </div>
                                <button class="col s3 m2 send-chat-button rounded grey yellow-text right" type="submit" name="submit-chat">
                                    <i class="material-icons">send</i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </main>

    <!--footer php -->
<?php 
    include '../includes/footer_inc.php';
?>
<script>
    $(document).ready(function(){
        scrolltoBottom();
        focusChat();
        var chatUser = '<?php echo $_GET["id"];?>';
        var user = '<?php echo $_SESSION["User"]["gebruikersnaam"]?>';

        function scrolltoBottom(){
            $("#chat-box").scrollTop($(document).height());
        }
        function focusChat(){
            $("#message-chat").focus();
        }
        function reloadMessages(){
            $("#chat-block").load("../includes/load-chats.php",{
                otherUser: chatUser,
                loggedInUser: user
            });
        }
        setInterval(() => {
            reloadMessages();
        }, 2000);
    })
</script>

</body>
</html>