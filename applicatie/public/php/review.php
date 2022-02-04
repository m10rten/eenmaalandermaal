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
    
    <title>EenmaalAndermaal | Review</title>
    <link href="../css/verkoper.css" rel="stylesheet" />

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        require '../requirers/dbh_inc.php';
    ?>

    <div class="container">
        
        <?php 
        if(isset($_GET["p"]) && $_GET["p"] !== ""){
            $seller = $_GET["p"];
            showBreadcrumbs("index","verkoper.php?p=".$seller."","review.php");
            include '../functions/pop_check.php';
            echo '
                <div class="row">
                    <div class="col s12">
                        <h2 class="center margin-top-0">Review</h2>
                    </div>
                </div>
            ';
            $getVerkoperQuery = $dbh->prepare("SELECT * FROM Verkoper WHERE Gebruiker = ?");
            $getVerkoperQuery->execute(array($seller));
            $fetchSeller = $getVerkoperQuery->fetchAll(PDO::FETCH_ASSOC);
            if(sizeof($fetchSeller) > 0 && sizeof($fetchSeller) < 2){
                if($seller !== $_SESSION["User"]["gebruikersnaam"]){                
                    $user = $_SESSION["User"]["gebruikersnaam"];
                    $getSellersItems = $dbh->prepare("SELECT * FROM voorwerp 
                    WHERE voorwerpnummer NOT IN (SELECT voorwerp FROM Feedback) 
                    AND verkoper = ? AND koper = ?
                    ");
                    $getSellersItems->execute(array($seller, $user));
                    $fetchItems = $getSellersItems->fetchAll(PDO::FETCH_ASSOC);
                    if(sizeof($fetchItems) > 0){
                        echo '
                        <div class="container">
                            <form action="../includes/review_inc.php?p='.$seller.'" method="post" class="input-field">
                                <select name="voorwerp-select" id="select-voorwerp" required>
                                    <option disabled selected>kies een gekocht product om te reviewen</option>
                                ';
                                    foreach($fetchItems as $item){
                                        $title = $item["titel"];
                                        $id = $item["voorwerpnummer"];
                                        echo ' <option value="'.$id.'">'.$title.'</option>';
                                    }
                                    echo '
                                </select>
                                <!-- slider stars 0-50 voor 0.0 tot 5.0 -->
                                <p class="center">Op schaal van 0 tot 5 sterren</p>
                                <input type="range" min="0.0" max="50" name="rating-review" required>
                                <!--beschrijving-->
                                <div class="col s12 m11 l7 offset-l1 input-field rounded z-depth-2 "> 
                                    <textarea id="description_review" class="materialize-textarea borderless-input" placeholder="beschrijving" name="description-review" required></textarea>
                                    <label class="rounded yellow black-text label" for="description_review"><span class="required">*</span> Beschrijving</label>
                                </div>
                                <div class="col s12 center">
                                    <button type="submit" class="btn yellow rounded" name="submit-review">Review</button>
                                </div>
                            </form>
                        </div>';
                    }
                    else{
                        echo '<p class="center">koop eerst een item van '.$seller.'</p>'; 
                    }
                }else{
                    echo '<p class="center">je kan jezelf niet reviewen</p>'; 
                }
            }else{
                echo '<p class="center">er is geen verkoper gevonden</p>'; 
            }
        }else{
            showBreadcrumbs("index","verkoper.php","review.php");
            include '../functions/pop_check.php';

            echo '
                <div class="row">
                    <div class="col s12">
                        <h2 class="center margin-top-0">Review</h2>
                    </div>
                </div>
            ';
            echo '<p class="center">er is geen verkoper gevonden</p>'; 
        }
        ?>
    </div>

        
    </main>
    
    <!--footer php -->
<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>