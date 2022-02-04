<?php 
session_start();
if(empty($_SESSION['User'])){
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
    
    <title>EenmaalAndermaal | gebruiker</title>
    <link href="../css/verkoper.css" rel="stylesheet" />

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","gebruiker.php","");
        include '../functions/pop_check.php';
        require '../requirers/dbh_inc.php';
        ?> 
        <div class="row">
            <div class="col s12">
                <h2 class="center index margin-top-0 margin-bottom-0">
                    jouw account
                </h2>
            </div>
        </div>
        <?php

            if(isset($_SESSION["User"])){
                $username = $_SESSION["User"]["gebruikersnaam"];
                $getUserQuery = $dbh->prepare("SELECT * FROM  Gebruiker
                LEFT JOIN verkoper ON gebruiker.gebruikersnaam = verkoper.gebruiker
                LEFT JOIN GebruikersTelefoon ON gebruiker.gebruikersnaam = GebruikersTelefoon.gebruiker
                WHERE gebruiker.gebruikersnaam = ?");
                $getUserQuery->execute(array($username));
                $fetchUserInfo = $getUserQuery->fetchAll(PDO::FETCH_ASSOC);
                if(sizeof($fetchUserInfo) > 0){
                    $username = $fetchUserInfo[0]["gebruikersnaam"]; 
                    $email = $fetchUserInfo[0]["mailbox"];
                    $location = $fetchUserInfo[0]["plaatsnaam"];
                    $phone = $fetchUserInfo[0]["telefoon"];
                    echo '            
                    <div class="container">        
                        <div class="row">
                            <div class="col s12  margin-top-0">
                                <img class="default-image-seller left" src="../media/default-person.png" alt="default-person"> 
                                <h3 class="left">'.$username.'</h3>
                            </div>
                        </div>
                        <div class="row rounded z-depth-1 padding-populair">
                            <div class="col s12 center">                  
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">person</i> '.$username.'</p>
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">mail</i><u><a class="black-text" href="mailto:'.$email.'">'.$email.'</a></u></p>
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">place</i>Maps: <u><a class="black-text" target="_blank" href="https://www.google.nl/maps/search/'.$location.'">'.$location.'</a></u></p>
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">phone</i><u><a class="black-text" href="tel:'.$phone.'"> '.$phone.'</a></u></p>
                            </div>
                        </div>     
                    </div>
                    ';
                }
                else{
                    echo '<p class="center">deze gebruiker is niet gevonden</p>';                    
                }
            }
        ?>
    </main>
    <!--footer php -->

<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>