<?php
// checks if the GET variable is set
if(!empty($_GET["q"]) && !$_GET["q"] == ""){
    $search_query = htmlspecialchars($_GET["q"]);
    require '../requirers/dbh_inc.php';
    $query = $dbh->prepare("SELECT TOP 20 rubriek.rubrieknaam, (SELECT MAX(bod.bodBedrag)) as hoogsteBod,
    voorwerp.* 
    FROM voorwerp LEFT JOIN Bestand ON
     voorwerp.voorwerpnummer = bestand.voorwerp
     LEFT JOIN bod ON bod.voorwerp = voorwerp.voorwerpnummer 
     LEFT JOIN VoorwerpInRubriek ON VoorwerpInRubriek.voorwerp = voorwerp.voorwerpnummer
     LEFT JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek 
    WHERE voorwerp.titel like  '%'+?+'%'
    AND voorwerp.veilingGesloten = 0 AND voorwerp.isActief = 1
    GROUP BY voorwerp.voorwerpnummer, bod.voorwerp, voorwerp.titel, voorwerp.looptijd, voorwerp.beschrijving, voorwerp.betalingsinstructie, voorwerp.betalingswijze,
    voorwerp.looptijdBeginDag, voorwerp.looptijdBeginTijdstip, voorwerp.startprijs, voorwerp.veilingGesloten, voorwerp.plaatsnaam, voorwerp.land, voorwerp.verzendkosten,
    voorwerp.verzendinstructies, voorwerp.verkoopprijs, voorwerp.verkoper, voorwerp.koper, voorwerp.looptijdEindeDag, voorwerp.looptijdEindeTijdstip,
    rubriek.rubrieknaam, voorwerp.isActief, voorwerp.conditie");
    $query->execute(array($search_query));
    $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
    include '../includes/card_inc.php';
    if(sizeof($fetch) > 0){        
            foreach($fetch as $auction){            
            $id = $auction["voorwerpnummer"];
            $getImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
            $getImage->execute(array($id));
            $image = $getImage->fetch(PDO::FETCH_ASSOC);
            if(!$getImage->rowCount() == 0){
                $src = $image["filenaam"];
                $title = $auction["titel"];
                $bid = $auction['hoogsteBod'];
                $date = $auction["looptijdEindeDag"];
                $genre = $auction['rubrieknaam'];
                echo '<div class="col s12 m6 l4 xl3">';    
                echo card('default',$title, $bid, $date, $id, $src, $genre);
                echo '</div>';
            }                       
        }        
    }else{
        echo'<div class="row">
            <div class="col s12">
                <h2 class="center">
                    oops er is niks gevonden voor: '.htmlspecialchars($_GET["q"]).'
                </h2>
            </div>
        </div>';        
    }
}else{
    echo '<div class="row">
        <div class="col s12">
            <h2 class="center">probeer te zoeken in de zoekbalk</h2>
        </div>
    </div>';    
}