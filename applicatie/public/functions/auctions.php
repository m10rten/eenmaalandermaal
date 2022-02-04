<?php

function getAuctions($dbh, $searchQuery = null){

    if($searchQuery) {
      $query = $dbh->prepare("SELECT TOP 20 COUNT(bod.voorwerp) as AantalBiedingen, voorwerp.voorwerpnummer, voorwerp.isActief, (SELECT MAX(bod.bodBedrag)) as hoogsteBod
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

      $query->execute(array($searchQuery));
    } else {
      $query = $dbh->prepare("SELECT TOP 20 COUNT(bod.voorwerp) as AantalBiedingen, voorwerp.voorwerpnummer, voorwerp.isActief, (SELECT MAX(bod.bodBedrag)) as hoogsteBod
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
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  function blockAuction() {

  }

  function deBlockAuction() {

  }

?>

