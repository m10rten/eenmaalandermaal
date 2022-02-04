<?php 
session_start();
    // if(!isset($_SESSION["User"])){
    //     header("Location: ../php/login.php?pop=nice try");
    //     exit();
    // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Verkoper</title>
    <link href="../css/verkoper.css" rel="stylesheet" />

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","verkoper.php","");
        include '../functions/pop_check.php';
        require '../requirers/dbh_inc.php';
        ?>
        <?php
            if(isset($_GET["p"])){
                $seller = $_GET["p"];
                $getSellerQuery = $dbh->prepare("SELECT * FROM verkoper 
                INNER JOIN Gebruiker ON gebruiker.gebruikersnaam = verkoper.gebruiker
                LEFT JOIN GebruikersTelefoon ON gebruiker.gebruikersnaam = GebruikersTelefoon.gebruiker
                WHERE verkoper.gebruiker = ?");
                $getSellerQuery->execute(array($seller));
                $fetchSellerInfo = $getSellerQuery->fetchAll(PDO::FETCH_ASSOC);
                if(sizeof($fetchSellerInfo) > 0){
                    $username = $fetchSellerInfo[0]["gebruikersnaam"]; 
                    $email = $fetchSellerInfo[0]["mailbox"];
                    $location = $fetchSellerInfo[0]["plaatsnaam"];
                    $phone = $fetchSellerInfo[0]["telefoon"];
                    echo '            
                    <div class="container">        
                        <div class="row">
                            <div class="col s12  margin-top-0">
                                <img class="default-image-seller left" src="../media/default-person.png" alt="default-person"> 
                                <h1 class=" margin-top-0">'.$seller.'</h1>
                            </div>
                        </div>
                        <div class="row rounded z-depth-1 padding-populair">
                            <div class="col s12 center">                  
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">person</i> '.$seller.'</p>
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">mail</i><u><a class="black-text" href="mailto:'.$email.'">'.$email.'</a></u></p>
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">place</i>Maps: <u><a class="black-text" target="_blank" href="https://www.google.nl/maps/search/'.$location.'">'.$location.'</a></u></p>
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">phone</i><u><a class="black-text" href="tel:'.$phone.'"> '.$phone.'</a></u></p>
                                <p class="col s12 l6 text-bold info-review-box"><i class="material-icons left ">sms</i><u><a class="black-text" ';
                                if(isset($_SESSION["User"])){
                                    echo 'href="../php/chat.php?id='.$seller.'"> stuur een bericht';
                                }else{
                                   echo ' href="../php/login.php"> log in om een bericht te sturen ';
                                }
                                
                                echo'</a></u></p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col s12">
                                <h2 class="col s12 margin-top-0">
                                    Reviews van '.$seller.'
                                </h2>
                            </div>
                            <div class="col s12 rounded z-depth-1">                    
                                <div class="col s12 padding-populair">
                                    <div class="row">
                                        '; 
                                        if(isset($_SESSION["User"]) && $_SESSION["User"]["gebruikersnaam"] !== $seller){
                                            echo '<a href="../php/review.php?p='.$seller.'"><p class="center yellow text-bold black-text rounded z-depth-1" >review deze verkoper</p></a>';
                                        }else if(isset($_SESSION["User"]) && $_SESSION["User"]["gebruikersnaam"] == $seller){
                                            echo '<p class="center yellow black-text rounded z-depth-1" >je kan jezelf niet reviewen</p>';
                                        }else{
                                            echo '<a href="../php/login.php"><p class="center yellow black-text rounded z-depth-1" >Log in om een review te geven</p></a>';
                                        }
                                        echo'
                                    </div>
                                    ';
                                    $getReviewsQuery = $dbh->prepare("SELECT TOP 6 * FROM Feedback
                                        WHERE verkoper = ? AND (isGeblokkeerd = 0 OR isGeblokkeerd IS NULL)
                                        ");
                                        $getReviewsQuery->execute(array($seller));
                                        $fetchReviews = $getReviewsQuery->fetchAll(PDO::FETCH_ASSOC);
                                        if(sizeof($fetchReviews) > 0){
                                            echo '<hr class="col s12 card center">';
                                            foreach($fetchReviews as $review){
                                                $reviewer = $review["reviewer"];
                                                $rating = $review["beoordeling"];
                                                $description = $review["beschrijving"];
                                                $date = $review["dag"];
                                                $time = $review["tijdstip"];
                                                $itemId = $review["voorwerp"];
                                                $reviewId = $review["reviewnummer"];
                                                $getItemName = $dbh->prepare("SELECT * FROM voorwerp WHERE voorwerpnummer = ?");
                                                $getItemName->execute(array($itemId));
                                                $fetchName = $getItemName->fetch(PDO::FETCH_ASSOC);
                                                echo '                                                    
                                                    <div class="col s1">
                                                    <i class="material-icons small left rating-icon">rate_review</i>
                                                    </div>
                                                    <div class="col ';
                                                        if(isset($_SESSION["User"]) && $_SESSION["User"]["is_beheerder"] == 1){echo 's10';
                                                        }else{echo 's11';} 
                                                        echo '">
                                                        <div class="col s12 center">
                                                            <h6 class="text-bold margin-top-0">'.$fetchName['titel'].'</h6>
                                                        </div>
                                                        <div class="col s12 l6">
                                                            <i class="material-icons left">person_pin</i><span class="text-bold">'.$reviewer.'</span>
                                                        </div>
                                                        <div class="col s12 l6 ">
                                                            <div class="text-bold margin-top-0 review-box star-box">'; echo getStarsReview($rating); echo '</div>
                                                        </div>
                                                        <div class="col s12">
                                                            <i class="material-icons left">textsms</i>'.$description.'
                                                        </div>
                                                        <div class="col s12">
                                                            '.$date.' '.$time.'
                                                        </div>
                                                    </div>
                                                    ';
                                                    // laat een X zien als de ingelogde gebruiker een beheerder is
                                                    if(isset($_SESSION["User"]) && $_SESSION["User"]["is_beheerder"] == 1){
                                                        echo '
                                                        <form action="../includes/del-review_inc.php?r='.$reviewId.'" method="post">
                                                            <div class="col s1 ">
                                                                <input type="text" class="d-none" name="hidden-review-number" value="'.$reviewId.'">
                                                                <button class="yellow rounded close-button z-depth-1 text-bold black-text" type="submit" name="submit-remove-review"><i class="material-icons" >close</i></button>
                                                            </div>
                                                        </form>
                                                        ';
                                                    }
                                                    
                                                    echo '
                                                    <hr class="col s12 card center">
                                                ';                                                
                                            }
                                        }else{
                                            echo '<p>er zijn nog geen reviews geplaatst voor deze verkoper</p>';
                                        }
                                    echo'                                                         
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <h2 class="margin-top-0">
                                    Items van '.$seller.'
                                </h2>
                            </div>
                            <div class="col s12 rounded z-depth-1">
                                <div class="col s12 padding-populair">
                                    ';
                                    include '../includes/card_inc.php';
                                    require '../requirers/dbh_inc.php';
                                    $queryUserAuctions = $dbh->prepare("SELECT DISTINCT voorwerp.*, rubriek.*, (SELECT MAX(bodBedrag) FROM bod WHERE voorwerp.voorwerpnummer = bod.voorwerp) as hoogsteBod FROM voorwerp 
                                    INNER JOIN VoorwerpInRubriek ON Voorwerp.voorwerpnummer = VoorwerpInRubriek.voorwerp
                                    INNER JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek
                                    LEFT JOIN bod on bod.voorwerp = voorwerp.voorwerpnummer
                                    WHERE voorwerp.verkoper = ?
                                    ORDER BY voorwerp.looptijdBeginDag ASC, looptijdBeginTijdstip DESC");
                                    $queryUserAuctions->execute(array($seller));
                                    $fetchUserAuctions = $queryUserAuctions->fetchAll(PDO::FETCH_ASSOC);
                                
                                    if(sizeof($fetchUserAuctions) > 0){
                                        foreach($fetchUserAuctions as $auction){
                                            $id = $auction["voorwerpnummer"];
                                            $getImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
                                            $getImage->execute(array($id));
                                            $image = $getImage->fetch(PDO::FETCH_ASSOC);
                                            if(!$getImage->rowCount() == 0){
                                            $title = $auction["titel"];
                                            $bod = $auction['hoogsteBod'];
                                            $date = $auction["looptijdEindeDag"];                                            
                                            $src = $image["filenaam"];
                                            $genre = $auction['rubrieknaam'];
                                            echo '<div class="col s12 m6 l4">';
                                            echo card('default',$title, $bod, $date, $id, $src, $genre);
                                            echo '</div>';
                                            }
                                        }
                                    }else{
                                        echo '
                                        <p>Er valt hier niks te zien.</p>
                                        ';
                                    }
                                    echo '
                                </div>
                            </div>
                        </div>     
                    </div>
                    ';
                }
                else{
                    echo '<p class="center">deze verkoper is niet gevonden</p>';                    
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