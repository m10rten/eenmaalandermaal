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
    
    <title>EenmaalAndermaal | Profiel</title>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","mijnaccount.php","");
        include '../functions/pop_check.php';
        ?>

    <div class="row">
        <div class="container">
            <div class="col s12">                
                <ul class="tabs yellow-text">
                    <li class="tab col s3"><a <?php if(isset($_GET['activeTab']) && $_GET['activeTab'] == 'mijnveilingen') {echo 'class="active"';}?> href="#mijnveilingen">Mijn Veilingen</a></li>
                    <li class="tab col s3"><a <?php if(isset($_GET['activeTab']) && $_GET['activeTab'] == 'favorieten') {echo 'class="active"';}?> href="#favorieten">Mijn Favorieten</a></li>
                    <li class="tab col s3"><a <?php if(isset($_GET['activeTab']) && $_GET['activeTab'] == 'mijnbiedingen') {echo 'class="active"';}?> href="#mijnbiedingen">Mijn Biedingen</a></li>
                    <li class="tab col s3"><a <?php if(isset($_GET['activeTab']) && $_GET['activeTab'] == 'mijnreviews') {echo 'class="active"';}?> href="#mijnreviews">Mijn Reviews</a></li>
                </ul>
            </div>       
        </div>
    </div>        

        <!-- Mijn veilingen -->
        <div id="mijnveilingen" class="container">
            <div class="row">
                <div class="col s12">
                    <h1 class="center">Mijn Veilingen</h1>
                </div>
                <?php
                    include '../includes/card_inc.php';
                    require '../requirers/dbh_inc.php';
                    $username = $_SESSION["User"]["gebruikersnaam"];
                    $queryUserAuctions = $dbh->prepare("SELECT DISTINCT voorwerp.*, rubriek.*, (SELECT MAX(bodBedrag) FROM bod WHERE voorwerp.voorwerpnummer = bod.voorwerp) as hoogsteBod FROM voorwerp 
                    INNER JOIN VoorwerpInRubriek ON Voorwerp.voorwerpnummer = VoorwerpInRubriek.voorwerp
                    INNER JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek
                    LEFT JOIN bod on bod.voorwerp = voorwerp.voorwerpnummer
                    WHERE voorwerp.verkoper = ?
                    ORDER BY voorwerp.looptijdBeginDag ASC, looptijdBeginTijdstip DESC");
                    $queryUserAuctions->execute(array($username));
                    $fetchUserAuctions = $queryUserAuctions->fetchAll(PDO::FETCH_ASSOC);
                
                    if(sizeof($fetchUserAuctions) > 0){
                        foreach($fetchUserAuctions as $bidAuction){
                            $id = $bidAuction["voorwerpnummer"];
                            $getItemImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
                            $getItemImage->execute(array($id));
                            $image = $getItemImage->fetch(PDO::FETCH_ASSOC);
                            if($getItemImage->rowCount() == 0){
                                $src = '../media/default-image.jpg';
                            }else{
                                $src = $image["filenaam"];
                                $title = $bidAuction["titel"];
                            $bid = $bidAuction['hoogsteBod'];
                            $date = $bidAuction["looptijdEindeDag"];                            
                            $genre = $bidAuction['rubrieknaam'];
                            echo '<div class="col s12 m6 l4">';
                            echo card('default',$title, $bid, $date, $id, $src, $genre);
                            echo '</div>';
                            }
                            
                            
                        }
                    }else{
                        echo '
                        <p class="center">Er valt hier niks te zien.</p>
                        ';
                    }      
                    
                ?>

            </div>
        </div>
        <!-- Mijn Favorieten -->
        <div id="favorieten" class="container">
            <div class="row">
                <div class="col s12">
                    <h1 class="center">Mijn Favorieten</h1>
                </div>
            </div>
        </div>
        <div id="mijnbiedingen" class="container">
            <div class="row">
                <div class="col s12">
                    <h1 class="center">Mijn Biedingen</h1>
                </div> 
                <?php
                $querybod = $dbh->prepare("SELECT DISTINCT
                (SELECT MAX(bodBedrag) FROM bod WHERE voorwerp.voorwerpnummer = bod.voorwerp) as bodBedrag, 
                (SELECT MAX(bodBedrag) FROM bod WHERE voorwerp.voorwerpnummer = bod.voorwerp AND bod.gebruiker = ?) as mijnBod,
                voorwerp.*, Rubriek.*  
                FROM voorwerp INNER JOIN Bod ON bod.voorwerp = voorwerp.voorwerpnummer
                INNER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.voorwerp = voorwerp.voorwerpnummer
                INNER JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek
                WHERE bod.gebruiker = ? 
                AND NOT voorwerp.verkoper = ? 
                ORDER BY  voorwerpnummer
                ");
                $querybod->execute(array($username, $username, $username));
                $fetchbod = $querybod->fetchAll(PDO::FETCH_ASSOC);
            
                if(sizeof($fetchbod) > 0){
                    foreach($fetchbod as $bodAuction){
                        $id = $bodAuction["voorwerpnummer"];
                        $getItemImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
                            $getItemImage->execute(array($id));
                            $image = $getItemImage->fetch(PDO::FETCH_ASSOC);
                        if(!$getItemImage->rowCount() == 0){
                        $title = $bodAuction["titel"];
                        $bod = $bodAuction['bodBedrag'];
                        $date = $bodAuction["looptijdEindeDag"];                        
                        $src = $image["filenaam"];
                        $genre = $bodAuction['rubrieknaam'];
                        $myBid = $bodAuction['mijnBod'];
                        echo '<div class="col s12 m6 l4">';
                        echo card('default',$title, $bod, $date, $id, $src, $genre, $id, false, $myBid);
                        echo '</div>';
                        }
                    }
                }else{
                    echo '
                    <p class="center">Er valt hier niks te zien.</p>
                    ';
                }
                ?>                   
            </div>
        </div>
        <div id="mijnreviews" class="container">
            <div class="row">
                <div class="col s12">
                    <h1 class="center">
                        Mijn Reviews 
                    </h1>
                </div>
                <?php
                $getReviewsQuery = $dbh->prepare("SELECT TOP 6 * FROM Feedback
                WHERE reviewer = ? AND (isGeblokkeerd = 0 OR isGeblokkeerd IS NULL)
                ");
                $getReviewsQuery->execute(array($username));
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
                            <div class="col s11">
                                <div class="col s12 center">
                                    <h6 class="text-bold margin-top-0">'.$fetchName['titel'].'</h6>
                                </div>
                                <div class="col s12 l6">
                                    <i class="material-icons left">person_pin</i><span class="text-bold">'.$reviewer.'</span>
                                </div>
                                <div class="col s12 l6">
                                    <div class="text-bold margin-top-0">'; echo getStarsReview($rating); echo '</div>
                                </div>
                                <div class="col s12">
                                    <i class="material-icons left">textsms</i>'.$description.'
                                </div>
                                <div class="col s12">
                                    '.$date.' '.$time.'
                                </div>
                            </div>
                            ';                          
                            echo '
                            <hr class="col s12 card center">
                        ';                                                
                    }
                }else{
                    echo '<p class="center">er zijn nog geen reviews geplaatst voor deze verkoper</p>';
                } ?>
            </div>
        </div>
    </main>
    <!--footer php -->

<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>