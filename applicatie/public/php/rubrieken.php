<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Verkennen</title>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        include '../includes/card_inc.php';
        include '../functions/pop_check.php'; 
        require '../requirers/dbh_inc.php';       

    if(!empty($_GET["c"])){
        // get uit de url
        $categories = $_GET["c"];
        // haalt de eerst mogelijke rubriek op
        $getTopQuery = $dbh->prepare("SELECT * FROM Rubriek 
            WHERE rubriek.rubrieknummer = (SELECT rubriek.rubriek FROM Rubriek WHERE rubrieknummer = ? )");
        $getTopQuery->execute(array($categories));
        $fetchCat = $getTopQuery->fetchAll(PDO::FETCH_ASSOC);
        // haalt de 2e mogelijke rubriek op
        $getFirstQuery = $dbh->prepare("SELECT * FROM rubriek WHERE rubrieknummer = ?");
        $getFirstQuery->execute(array($categories));
        $fetchFirst = $getFirstQuery->fetch(PDO::FETCH_ASSOC);
        // first id & string voor broodkruimels
        $first = $categories;
        $stringFirst = $fetchFirst["rubrieknaam"];
        // checkt of de 1e query resultaten heeft.
        if(sizeof($fetchCat) > 0){
            $second = $fetchCat[0]['rubrieknummer'];
            $stringSecond = $fetchCat[0]['rubrieknaam'];
            // haalt de sub-rubriek van de vorige query op.
            $getSubQuery = $dbh->prepare("SELECT * FROM Rubriek 
                WHERE rubriek.rubrieknummer = (SELECT rubriek.rubriek FROM Rubriek WHERE rubrieknummer = ? )");
            $getSubQuery->execute(array($second));
            $fetchSubCat = $getSubQuery->fetchAll(PDO::FETCH_ASSOC);
            // checkt of de sub query resultaten heeft
            if(sizeof($fetchSubCat) > 0 ){
                $third = $fetchSubCat[0]['rubrieknummer'];
                $stringThird = $fetchSubCat[0]['rubrieknaam'];
                breadcrumbCat($stringThird,$third,$stringSecond, $second,$stringFirst, $first,"");
            }
             if(sizeof($fetchSubCat) == 0){
                breadcrumbCat($stringSecond,$second,$stringFirst, $first, "", "", "");
            }
        }
        if(sizeof($fetchCat) == 0){
            breadcrumbCat($stringFirst,$first,"","", "", "", "");
        }
    }       

    if(empty($_GET["c"]) || $_GET["c"] == "" || !is_numeric($categories)){ 
       showBreadcrumbs("index","rubrieken.php","");
    //    echo the container for all the categories
        echo'    
        <div class="container">
            <div class="row">
                <div class="col s12  center">
                    <h2 class="index margin-top-0">Rubrieken</h2>
                </div>
            </div>

            <div class="row center">';
            // get the categories where there is no upper category
                $getCategories = $dbh->prepare("SELECT * FROM rubriek 
                LEFT JOIN BestandRubriek ON rubriek.rubrieknummer = bestandRubriek.rubriek
                WHERE rubriek.rubriek  IN (SELECT rubrieknummer FROM rubriek WHERE rubriek IS NULL)");
                $getCategories->execute();
                $fetchCategories = $getCategories->fetchAll(PDO::FETCH_ASSOC);
                    foreach($fetchCategories as $category){
                        $title = $category["rubrieknaam"];
                        $id = $category["rubrieknummer"];
                        $getTopItemImage = $dbh->prepare("SELECT * FROM BestandRubriek WHERE rubriek = ?");
                        $getTopItemImage->execute(array($id));
                        if($getTopItemImage->rowCount() == 0){
                            $file = '../media/default-image.jpg';
                        }else{
                            $file = $category['filenaam'];
                        }
                        echo '
                        <div class="col s12 m6 l4">
                            <div class="card hoverable">
                                <div class="card-image">
                                <a href="?c='.$id.'" >                                
                                    <img src="'.$file.'">
                                </a>
                                </div>
                                <a href="?c='.$id.'" class="card-content">
                                    <h5 class="link truncate">'.$title.'</h5>
                                </a>
                            </div>
                        </div>';                        
                    }               
                echo'
            </div>
        </div>  ';
    }
    
    else if(is_numeric($categories)){
        //categorieen > voorwerpen
        $queryCat = $dbh->prepare("SELECT DISTINCT voorwerp.*, rubriek.*, (SELECT MAX(bodBedrag) FROM bod WHERE voorwerp.voorwerpnummer = bod.voorwerp) as hoogsteBod
        FROM voorwerp INNER JOIN VoorwerpInRubriek ON Voorwerp.voorwerpnummer = VoorwerpInRubriek.voorwerp
        INNER JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek
        LEFT JOIN bod ON bod.voorwerp = voorwerp.voorwerpnummer 
        WHERE rubriek.rubriek =  (SELECT rubrieknummer FROM Rubriek WHERE rubriek.rubrieknummer= ?) 
        OR rubriek.rubrieknummer = (SELECT rubrieknummer FROM Rubriek WHERE rubriek.rubrieknummer = ?)
        AND voorwerp.isActief = 1 AND voorwerp.veilingGesloten = 0;
            ");
        $queryCat->execute(array($categories, $categories));
        $fetchCatInfo = $queryCat->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="row">        
        <?php
            $queryTopCat = $dbh->prepare("SELECT * FROM rubriek
            LEFT JOIN bestandrubriek ON rubriek.rubrieknummer = bestandRubriek.rubriek
            WHERE rubriek.rubriek =  (SELECT rubrieknummer FROM rubriek WHERE rubriek.rubrieknummer = ?)
            ");
            $queryTopCat->execute(array($categories));
            $fetchTopCat = $queryTopCat->fetchAll(PDO::FETCH_ASSOC);
        if( sizeof($fetchTopCat) > 0){            
            echo ' 
            <div class="container">
            <div class="row center"> 
            <h2 class="index">'.$fetchFirst["rubrieknaam"].'</h2>
            ';
            // fetches the image from the database if there is one, else it gets a default image.
            foreach($fetchTopCat as $topItem){
                $getTopItemImage = $dbh->prepare("SELECT * FROM BestandRubriek WHERE rubriek = ?");
                $getTopItemImage->execute(array($topItem["rubrieknummer"]));
                if($getTopItemImage->rowCount() == 0){
                    $image = '../media/default-image.jpg';
                }else{
                    $image = $topItem['filenaam'];
                }
                echo '
            <!-- '.$topItem['rubrieknaam'].' -->
                <div class="col s12 m6 l4 xl3">
                    <div class="card hoverable category-card">
                        <div class="card-image category-image">
                        <a href="../php/rubrieken.php?c='.$topItem['rubrieknummer'].'"><img src="'.$image.'"></a>
                        </div>
                        <div  class="card-content padding-populair category-title-block">
                            <a href="../php/rubrieken.php?c='.$topItem['rubrieknummer'].'"><h5 class="link category-title truncate">'.$topItem['rubrieknaam'].'</h5></a>
                        </div>
                    </div>
                </div>
            ';
            }
            echo '</div></div>';         

        }
        // checks the size of previous queries to declare if it needs to run.
        if(sizeof($fetchCatInfo) > 0 && sizeof($fetchTopCat) == 0){
            echo '<h2 class="index center">'.$fetchFirst["rubrieknaam"].'</h2>';
            foreach($fetchCatInfo as $item){
                $getItemImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
                $getItemImage->execute(array($item["voorwerpnummer"]));
                $fetchItemImage = $getItemImage->fetch(PDO::FETCH_ASSOC);

                // show only items with an image
                if(!$getItemImage->rowCount() == 0){                  
                    $src = $fetchItemImage["filenaam"];
                    $title = $item["titel"];
                    $bid = $item["hoogsteBod"];
                    $date = $item["looptijdEindeDag"];
                    $id = $item["voorwerpnummer"];
                    $genre = $item["rubrieknaam"];
                    echo '<div class="col s12 m6 l4 xl3">';
                    echo card('default',$title, $bid, $date, $id, $src, $genre);
                    echo '</div>';
                }
                
            }
            // shows no results on the screen if the size of queries is 0
        }else if(sizeof($fetchCatInfo) == 0 && sizeof($fetchTopCat) == 0){
            echo '
            <p class="col s12 center">Er zijn geen veilingen gevonden voor deze categorie</p>
            <p class="col s12 center">Klik <u> <a href="../index.php">Hier</a></u> om terug te gaan naar de homepagina</p>
            ';
        }
    }?>
        </div>
    </main>
    <!--footer php -->
<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>