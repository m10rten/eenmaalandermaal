<?php 
if(isset($_POST["submit-review"])){
    // start de sessie en zet de tijdzone op EU amsterdam (evt uit database halen voor gebruiker)
    session_start();
    date_default_timezone_set('Europe/Amsterdam');
    require '../requirers/dbh_inc.php';
    $user = $_SESSION["User"]["gebruikersnaam"];
    $item = $_POST["voorwerp-select"];
    $description = $_POST["description-review"];
    // 0-5 voor de meegegeven 0-50 om zo 0.0 tot 5.0 te maken.
    $rating = $_POST["rating-review"] / 10;
    // check op lege input
    if(empty($user) || empty($item) || empty($description) || empty($rating)){
            header("Location: ../php/review.php?pop=empty input");
            exit();
    }
    if(isset($_GET["p"]) && $_GET["p"] !== ""){        
        $seller = $_GET["p"]; 
        // het verkrijgen van de huidige tijd en dag
        $date = date("d-m-y");
        $time = date("H:i");
        $userSort = "koper";
        $feedbackSort = "review";      

        // check of de verkoper bestaat of dat deze in de html is aangepast
        $checkSellerQuery = $dbh->prepare("SELECT * FROM verkoper WHERE gebruiker = ?");
        $checkSellerQuery->execute(array($seller));
        $fetchSellerCheck = $checkSellerQuery->fetchAll(PDO::FETCH_ASSOC);
        if(sizeof($fetchSellerCheck) == 1){
            echo '1 verkoper gevonden <br>';
            if($seller !== $user){
                echo 'de gebruiker is niet de verkoper en kan dus reviewen <br>';
                $checkForReview = $dbh->prepare("SELECT * FROM voorwerp 
                WHERE voorwerpnummer NOT IN (SELECT voorwerp FROM Feedback) 
                AND verkoper = ? AND koper = ?");
                $checkForReview->execute(array($seller,$user));
                $fetchCheckReview = $checkForReview->fetchAll(PDO::FETCH_ASSOC);
                if(sizeof($fetchCheckReview) >= 1){
                    // items to be reviewed have been found
                    $insertReview = $dbh->prepare("INSERT INTO feedback 
                    (reviewer, voorwerp, beoordeling, verkoper, soortGebruiker, feedbackSoort, dag, tijdstip, beschrijving)
                    VALUES(?,?,?,?,?,?,?,?,?)");
                    $insertReview->execute(array($user, $item, $rating, $seller, $userSort, $feedbackSort, $date, $time, $description));
                    header("Location: ../php/review.php?p=".$seller."&pop=review placed");
                    exit();
                }else{
                    header("Location: ../php/review.php?p=".$seller."&pop=already reviewed");
                    exit();
                }
            }else{
                header("Location: ../php/review.php?p=".$seller."&pop=same seller");
                exit();
            }
        }else{
            header("Location: ../php/review.php?p=".$seller."&pop=nice try");
            exit();
        }        
    } else{
        header("Location: ../php/review.php?pop=nice try");
        exit();
    }
}else{
    header("Location: ../index.php?pop=nice try");
    exit();
}