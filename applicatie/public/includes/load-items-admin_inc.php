<?php 
if(isset($_POST["method"])){
    $realPath = $_SERVER['DOCUMENT_ROOT'];
    require $realPath . '/requirers/dbh_inc.php';
    include '../includes/card_inc.php';
    $newCount = $_POST["newitemCount"];
    $search_query = $_POST["search_query"];
    $method = $_POST["method"];
    if($method == 'auctions'){
        if($search_query !== "") {
            $query = $dbh->prepare("SELECT TOP $newCount COUNT(bod.voorwerp) as AantalBiedingen, voorwerp.voorwerpnummer, voorwerp.isActief, (SELECT MAX(bod.bodBedrag)) as hoogsteBod
            , rubriek.rubrieknaam
            ,voorwerp.* FROM voorwerp LEFT JOIN bod ON bod.voorwerp = voorwerp.voorwerpnummer 
            LEFT JOIN VoorwerpInRubriek ON VoorwerpInRubriek.voorwerp = voorwerp.voorwerpnummer
            LEFT JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek 
            WHERE voorwerp.veilingGesloten = 0 
            AND voorwerp.titel LIKE '%'+?+'%'
            GROUP BY voorwerp.voorwerpnummer, bod.voorwerp, voorwerp.titel, voorwerp.looptijd, voorwerp.beschrijving, voorwerp.betalingsinstructie, voorwerp.betalingswijze,
            voorwerp.looptijdBeginDag, voorwerp.looptijdBeginTijdstip, voorwerp.startprijs, voorwerp.veilingGesloten, voorwerp.plaatsnaam, voorwerp.land, voorwerp.verzendkosten,
            voorwerp.verzendinstructies, voorwerp.verkoopprijs, voorwerp.verkoper, voorwerp.koper, voorwerp.looptijdEindeDag, voorwerp.looptijdEindeTijdstip,
            rubriek.rubrieknaam, voorwerp.isActief, voorwerp.conditie
            ORDER BY aantalbiedingen DESC, hoogsteBod DESC");
    
            $query->execute(array($search_query  ));
        }else {
            $query = $dbh->prepare("SELECT TOP $newCount COUNT(bod.voorwerp) as AantalBiedingen, voorwerp.voorwerpnummer, voorwerp.isActief, (SELECT MAX(bod.bodBedrag)) as hoogsteBod
            , rubriek.rubrieknaam
            ,voorwerp.* FROM voorwerp LEFT JOIN bod ON bod.voorwerp = voorwerp.voorwerpnummer 
            LEFT JOIN VoorwerpInRubriek ON VoorwerpInRubriek.voorwerp = voorwerp.voorwerpnummer
            LEFT JOIN Rubriek ON Rubriek.rubrieknummer = VoorwerpInRubriek.rubriek 
            WHERE voorwerp.veilingGesloten = 0 
            GROUP BY voorwerp.voorwerpnummer, bod.voorwerp, voorwerp.titel, voorwerp.looptijd, voorwerp.beschrijving, voorwerp.betalingsinstructie, voorwerp.betalingswijze,
            voorwerp.looptijdBeginDag, voorwerp.looptijdBeginTijdstip, voorwerp.startprijs, voorwerp.veilingGesloten, voorwerp.plaatsnaam, voorwerp.land, voorwerp.verzendkosten,
            voorwerp.verzendinstructies, voorwerp.verkoopprijs, voorwerp.verkoper, voorwerp.koper, voorwerp.looptijdEindeDag, voorwerp.looptijdEindeTijdstip,
            rubriek.rubrieknaam, voorwerp.isActief, voorwerp.conditie
            ORDER BY aantalbiedingen DESC, hoogsteBod DESC");
    
            $query->execute();
        }
        $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($fetch as $auction){
            $id = $auction["voorwerpnummer"];
            $getImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
            $getImage->execute(array($id));
            $image = $getImage->fetch(PDO::FETCH_ASSOC);
            if($getImage->rowCount() == 0){
                $src = '../media/default-image.jpg';
            }
            else{
                $src = $image["filenaam"];
            }
            $title = $auction["titel"];
            $bid = $auction['hoogsteBod'];
            $date = $auction["looptijdEindeDag"];

            $genre = $auction['rubrieknaam'];
            $auctionnumber = $auction['voorwerpnummer'];
            $active = $auction['isActief'];
            echo '<div class="col s12 m6 l4" id="admin-auctions">';
            echo card('admin',$title, $bid, $date, $id, $src, $genre, $auctionnumber, $active);                                
            echo '</div> ';
        }       
    }
    if($method == 'users'){
        include '../includes/user_inc.php';
        if($search_query !== "") {
            $query = $dbh->prepare("SELECT TOP $newCount G.gebruikersnaam,
            G.mailbox, G.is_geblokkeerd
            FROM Gebruiker AS G
            WHERE is_beheerder IS NULL OR is_beheerder = 0
            AND G.gebruikersnaam LIKE '%'+?+'%'
            ");
            $query->execute(array($search_query));
        }else{
            $query = $dbh->prepare("SELECT TOP $newCount G.gebruikersnaam,
            G.mailbox, G.is_geblokkeerd
            FROM Gebruiker AS G
            WHERE is_beheerder IS NULL OR is_beheerder = 0
            ");
            $query->execute();
        }
        $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($fetch as $user){
            echo userCard($user['gebruikersnaam'], $user['mailbox'], $user['is_geblokkeerd']);
        }

    }
}else{
    echo'<script>alert("goed geprobeerd");window.location = "../index.php";</script>';
}
?>