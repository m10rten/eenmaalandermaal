<?php 
function getReviews($dbh){
    $getReviews = $dbh->prepare("SELECT * FROM Feedback");
    $getReviews->execute();
    $fetchReviews = $getReviews->fetchAll(PDO::FETCH_ASSOC);
    return $fetchReviews;
}
?>