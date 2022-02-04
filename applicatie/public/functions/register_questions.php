<?php
    require '../requirers/dbh_inc.php';

    $query = $dbh->prepare("SELECT * FROM Vraag");
    $query->execute();
    $questions = $query->fetchAll(PDO::FETCH_ASSOC);
?>
