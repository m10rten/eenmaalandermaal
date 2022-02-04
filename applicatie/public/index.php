<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Home</title>

    <!--header php-->
<?php 
    include './includes/header_inc.php';

    // require './requirers/mail_inc.php';

?>
    <main>          
        <div class="row">
            <div class="col s12" id="header">
                <div class="container">
                    <h1>Plaatsen, Veilen, Cashen!</h1>
                    <div class="col s12">
                        <?php 
                            if(isset($_SESSION["User"])){
                                $username = $_SESSION["User"]["gebruikersnaam"];
                                $queryUser = $dbh->prepare("SELECT gebruikersnaam, verkoper  FROM gebruiker WHERE gebruikersnaam = ?");
                                $queryUser->execute(array($username));
                                $fetchUserCheck = $queryUser->fetch(PDO::FETCH_ASSOC);
                                if($fetchUserCheck['verkoper'] == 1){
                                    echo '<a href="./php/plaatsen.php"><div class="btn yellow center black-text"> veil nu! </div></a>'; 
                                }
                                else{                                    
                                    echo '<a href="./php/activeren.php"><div class="btn yellow center black-text"> Word verkoper! </div></a>';                                    
                                }
                            }else{
                                    echo '<a href="./php/login.php"><div class="btn yellow center black-text"> meld je aan! </div></a>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include './includes/functies_inc.php';
            showBreadcrumbs("index","","");
            include './functions/pop_check.php';
            require './requirers/dbh_inc.php';   
        ?> 
        <!-- 1e blok -->
        <div class="container">
            <div class="row">         
                <!-- veiling van de dag -->
                <div class="col s12">
                    <h2 class="index margin-top-0">Trending</h2>
                </div> 
                    <?php                            
                        // vvdd = veiling van de dag, fetches 6 items with either the most bids, or just 6 items.
                        $queryVvdD = $dbh->prepare("SELECT TOP 6 COUNT(bod.voorwerp) as AantalBiedingen, (SELECT MAX(bod.bodBedrag)) as hoogsteBod, 
                        rubriek.rubrieknaam
                        ,voorwerp.* FROM voorwerp LEFT JOIN bod ON bod.voorwerp = voorwerp.voorwerpnummer 
                        INNER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.voorwerp = voorwerp.voorwerpnummer
                        INNER JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek 
                            WHERE voorwerp.veilingGesloten = 0 
                            AND voorwerp.isActief = 1
                        GROUP BY voorwerp.voorwerpnummer, bod.voorwerp, voorwerp.titel, voorwerp.looptijd, voorwerp.beschrijving, voorwerp.betalingsinstructie, voorwerp.betalingswijze,
                        voorwerp.looptijdBeginDag, voorwerp.looptijdBeginTijdstip, voorwerp.startprijs, voorwerp.veilingGesloten, voorwerp.plaatsnaam, voorwerp.land, voorwerp.verzendkosten,
                        voorwerp.verzendinstructies, voorwerp.verkoopprijs, voorwerp.verkoper, voorwerp.koper, voorwerp.looptijdEindeDag, voorwerp.looptijdEindeTijdstip,
                        rubriek.rubrieknaam, voorwerp.isActief, voorwerp.conditie
                        ORDER BY aantalbiedingen DESC, hoogsteBod DESC");
                        $queryVvdD->execute();
                        $fetchVvdD = $queryVvdD->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php include './includes/card_inc.php';
                       
                        foreach($fetchVvdD as $auction){                            
                            $id = $auction["voorwerpnummer"];
                            $getImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
                            $getImage->execute(array($id));
                            $image = $getImage->fetch(PDO::FETCH_ASSOC);
                            $title = $auction["titel"];
                            $bid = $auction['hoogsteBod'];
                            $date = $auction["looptijdEindeDag"];                        
                            $src = $image["filenaam"];
                            $genre = $auction['rubrieknaam'];
                            echo '<div class="col s12 m6 l4">';
                            echo card('default',$title, $bid, $date, $id, $src, $genre, $id, false);
                            echo '</div>';
                        }  
                    ?>                                              
                <!-- populaire rubrieken -->
                <!-- populaire rubrieken titel -->
                <div class="col s12">
                    <h2 class="index">Populaire Rubrieken</h2>
                </div>
                <!-- rubrieken blokjes -->
                <div class="col s12 center" >
                    <?php 
                    if(!isset($_SESSION["topCategories"])){
                        foreach(getPopularMainCategories($dbh) as $category){
                            categoryCard($category['rubrieknummer'], $category['rubrieknaam']);
                        }
                    }else{
                        foreach($_SESSION["topCategories"] as $category){
                            categoryCard($category['rubrieknummer'], $category['rubrieknaam']);
                        }
                    }                        
                    ?>        
                </div>         
            </div>
        </div>

    </main>

    <!--footer php -->
<?php 
    include './includes/footer_inc.php';
?>

</body>
</html>