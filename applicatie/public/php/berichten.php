<?php 
session_start();
    if(!isset($_SESSION["User"])){
        header("Location: ../php/login.php?pop=nice try");
        exit();
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
        include '../includes/functies_inc.php';

        showBreadcrumbs("index","berichten.php","");
        
        include '../functions/pop_check.php';
        ?>
        <div class="container">
            <div class="row">
                <div class="col s12">
                   <h2 class="center margin-top-0">
                        berichten:
                    </h2> 
                </div>
            </div>    
            <div class="row z-depth-1 overview-msg-box">
                <div class="col s12">
                    <div class="col s4 left ">
                     <span class="left"> gebruiker </span><i class="material-icons right">arrow_drop_down</i>
                    </div>
                    <div class="col s5 center">
                    <span class="">laatste bericht</span><i class="material-icons right">arrow_drop_down</i>
                    </div>
                    <div class="col s3 right">
                        <p class="right margin-top-0 margin-bottom-0">tijdstip</p>
                    </div>
                </div>
            <hr class="devide center">
            <!-- alle chats laden -->
            <?php 
                require '../requirers/dbh_inc.php';
                $user = $_SESSION["User"]["gebruikersnaam"];
                // get all chats from the user
                $getChats = $dbh->prepare("SELECT * FROM Chats WHERE chatId IN 
                    (SELECT chatId FROM Chats WHERE (gebruiker1 = ? or gebruiker2 = ?))
                        ORDER BY(SELECT TOP 1 tijdstip FROM Berichten WHERE chatId = chats.chatId 
                            AND (verzender = ? OR ontvanger = ?)ORDER BY dag DESC, tijdstip DESC, berichtNummer DESC) DESC;");
                    $getChats->execute(array($user, $user, $user, $user));
                    $fetchChats = $getChats->fetchAll(PDO::FETCH_ASSOC);
                    if(sizeof($fetchChats) > 0){
                        foreach($fetchChats as $chat){
                            $getLastMsg = $dbh->prepare("SELECT TOP 1 bericht as laatsteBericht, dag, tijdstip 
                            FROM Berichten WHERE chatId = ? ORDER BY dag DESC, tijdstip DESC, berichtNummer DESC");
                            $getLastMsg->execute(array($chat["chatId"]));
                            $fetchLastMsg = $getLastMsg->fetch(PDO::FETCH_ASSOC);
                            if($chat["gebruiker1"] !== $user){
                                $chatUser = $chat["gebruiker1"];
                            }else{
                                $chatUser = $chat["gebruiker2"];
                            }echo '
                            <div class="col s12 msg-msg-overview-box z-depth-1">
                                <div class="col s8 m4 left">
                                    <i class="material-icons left">person_outline</i> <a class="black-text" href="../php/gebruiker.php?g=">'.$chatUser.'</a>
                                </div>
                                
                                <div class="col s4 m2 right">
                                    <p class="right margin-top-0 margin-bottom-0">';
                                     if($fetchLastMsg["dag"] == date("d-m-Y")){echo $fetchLastMsg["tijdstip"];}else{echo $fetchLastMsg["dag"];}
                                     echo'
                                     </p>
                                </div>
                                <div class="col s12 m6 center">
                                    <a class="grey-text truncate " href="../php/chat.php?id='.$chatUser.'">'.$fetchLastMsg["laatsteBericht"].'</a>
                                </div>
                            </div>';
                        }
                    }
                    
            ?>
                
            </div>           
        </div>
    </main>
    <!--footer php -->

<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>