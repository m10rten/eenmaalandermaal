<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
    date_default_timezone_set('Europe/Amsterdam');
$realPath = $_SERVER['DOCUMENT_ROOT'];
require $realPath . '/requirers/dbh_inc.php';
$search_query = $_POST["search_query"];
$newCount = $_POST["newitemCount"];

$query = $dbh->prepare("SELECT TOP $newCount rubriek.rubrieknaam, (SELECT MAX(bod.bodBedrag)) as hoogsteBod,
voorwerp.* 
FROM voorwerp LEFT JOIN Bestand ON
 voorwerp.voorwerpnummer = bestand.voorwerp
 LEFT JOIN bod ON bod.voorwerp = voorwerp.voorwerpnummer 
 LEFT JOIN VoorwerpInRubriek ON VoorwerpInRubriek.voorwerp = voorwerp.voorwerpnummer
 LEFT JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek 
WHERE voorwerp.titel like  '%'+?+'%'
AND voorwerp.veilingGesloten = 0
GROUP BY voorwerp.voorwerpnummer, bod.voorwerp, voorwerp.titel, voorwerp.looptijd, voorwerp.beschrijving, voorwerp.betalingsinstructie, voorwerp.betalingswijze,
voorwerp.looptijdBeginDag, voorwerp.looptijdBeginTijdstip, voorwerp.startprijs, voorwerp.veilingGesloten, voorwerp.plaatsnaam, voorwerp.land, voorwerp.verzendkosten,
voorwerp.verzendinstructies, voorwerp.verkoopprijs, voorwerp.verkoper, voorwerp.koper, voorwerp.looptijdEindeDag, voorwerp.looptijdEindeTijdstip,
rubriek.rubrieknaam, voorwerp.isActief, voorwerp.conditie");
$query->execute(array($search_query));
if(!$query->rowCount() == 0){
    include '../includes/card_inc.php';
    while($auction = $query->fetch(PDO::FETCH_ASSOC)){
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
} 
?>