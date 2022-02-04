<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Veiling</title>
    <link href="../css/veiling.css" rel="stylesheet" />

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php
        require '../requirers/dbh_inc.php';
        include '../includes/functies_inc.php';
        if(!isset($_GET["v"]) || $_GET["v"] == ""|| !is_numeric($_GET["v"])){ 
            showBreadcrumbs("index","rubrieken.php","veiling.php");
            showEmptyAuction();
            include '../functions/pop_check.php';
        } 
         
        if(isset($_GET["v"]) && is_numeric($_GET["v"])){
             
            $auctionInfo = $_GET["v"];           
                        
            $query = $dbh->prepare("SELECT * FROM voorwerp 
            LEFT JOIN GebruikersTelefoon ON voorwerp.verkoper = GebruikersTelefoon.gebruiker
            INNER JOIN Bestand ON bestand.voorwerp = voorwerp.voorwerpnummer
            INNER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.voorwerp = Voorwerp.voorwerpnummer
            INNER JOIN Rubriek ON rubriek.rubrieknummer = VoorwerpInRubriek.rubriek
            WHERE voorwerp.voorwerpnummer = ? AND voorwerp.isActief = 1");
            $query->execute(array($auctionInfo));
            $fetch_auction = $query->fetch(PDO::FETCH_ASSOC);
            if($query->rowCount() == 1){
                
            }           
            
            if((!$query->rowCount() == 1) || 
            // checks if the seller or buyer is the logged in user when the auction is closed
            // else the user sees an empty screen.
                ($fetch_auction["veilingGesloten"] == 1 && 
                    (isset($_SESSION["User"]) && ($fetch_auction["verkoper"] !== $_SESSION["User"]["gebruikersnaam"] && $fetch_auction["koper"] !== $_SESSION["User"]["gebruikersnaam"])))
                    || ($fetch_auction["veilingGesloten"] == 1 && (!isset($_SESSION["User"])))){
                showBreadcrumbs("index","rubrieken.php","veiling.php");
                showEmptyAuction();
                include '../functions/pop_check.php';
            }
            
            else {
                $catName = $fetch_auction["rubrieknaam"];               

                $queryImage = $dbh->prepare("SELECT * from bestand WHERE voorwerp = ?");
                $queryImage->execute(array($auctionInfo));
                $fetchImage = $queryImage->fetchAll(PDO::FETCH_ASSOC);

                $phoneNumber = $fetch_auction['telefoon'];
                $seller = $fetch_auction['verkoper'];    
                $title = $fetch_auction['titel'];
                $description = $fetch_auction['beschrijving'];
                $startPrice = $fetch_auction['startprijs'];
                $paymentMethod = $fetch_auction['betalingswijze'];
                $paymentInstruction = $fetch_auction['betalingsinstructie'];
                $city = $fetch_auction['plaatsnaam'];
                $country = $fetch_auction['land'];
                $id = $fetch_auction['voorwerpnummer'];
                $idCat = $fetch_auction['rubrieknummer'];
                $condition = $fetch_auction['conditie'];

                $buyer = $fetch_auction['koper'];

                $shippingCosts = $fetch_auction['verzendkosten'];
                $shipmentMethod = $fetch_auction['verzendinstructies'];

                $fetchedDate = $fetch_auction['looptijdEindeDag'];
                $fetchedTime = $fetch_auction['looptijdEindeTijdstip'];
                $date = new DateTime($fetchedDate);
                list($d, $m, $y) = explode('-', $date->format('d-M-Y'));   
                $t = $fetchedTime;

                breadcrumbAuction($catName, $idCat, $title, $auctionInfo);
                include '../functions/pop_check.php';
                
                // shows the information on the seller and item
                echo ' 
            <div class="container">
                <div class="row">
                    <div class="row">                    
                        <!-- fotos -->
                        <div class="col s12 m7">
                            <!-- main foto -->
                            <div class="col s12 l8 main-image">
                                <img src="'.$fetchImage[0]["filenaam"].'" class="materialboxed " style="max-width: 100%" alt="'.$fetchImage[0]["filenaam"].'">
                            </div>                        

                            <!-- rest fotos -->
                            <div class="col s12 l4 ">
                                <!-- foto 1 -->';
                                foreach(array_slice($fetchImage, 1) as $img){
                                    echo'
                                    <div class="col s4 l12" style="padding: 5px 5px 5px 0;">
                                        <img src="'.$img["filenaam"].'" class="materialboxed" style="max-width: 100%" alt="'.$img["filenaam"].'">
                                    </div>';
                                }                           
                            echo '    
                            </div>
                        </div>
                        <!-- informatie aanbieder -->
                        <div class="col s12 m5 " >
                            <div class="col s12 rounded z-depth-3 info-box p-bold">
                                <div class="col s12">
                                <h6 class="text-bold"> <i class="material-icons left">collections_bookmark</i> <a class="black-text" href="../php/rubrieken.php?c='.$idCat.'"> '.$catName.'</a></h6>
                                    <p><i class="material-icons left">person</i><a class="black-text" href="../php/verkoper.php?p='.$seller.'">'.$seller.'</a></p>
                                    <p><i class="material-icons left">place</i>'.$city.', '.$country.'</p>';
                                        if(isset($phoneNumber)){
                                            echo '<p><i class="material-icons left">local_phone</i>'.$phoneNumber.'</p>';
                                        }                                    
                                    echo '
                                    <p><i class="material-icons left">schedule</i>
                                        <span id="day-c">days</span>d en <span id="hours-c">hours</span>h <span id="min-c"></span>
                                        <span id="condition">conditie</span>'; 
                                        if((strtotime(date('d-n-Y')) > strtotime($fetchedDate)) || ((strtotime(date('d-n-Y')) == strtotime($fetchedDate)) && (strtotime(date('H:i')) >= strtotime($fetchedTime)))){
                                            echo '<p><b><i class="material-icons left">alarm_off</i></b><span class="red-text text-bold"> Gesloten</span></p>';
                                        }else {
                                            echo '<p><b><i class="material-icons left">alarm_on</i></b><span class="green-text text-bold"> Geopend</span></p>';
                                        }                                        
                                        echo'
                                    </p>
                                
                                    <p><b><i class="material-icons left">date_range</i>
                                    <span id="day-t">'.$d.'</span> <span id="month-t">'.$m.'</span> <span id="year-t">'.$y.'</span> <span id="time-t">'.$t.'</span> 
                                    </b></p>
                                    <p><b><i class="material-icons left">payment</i>'.$paymentMethod.', '.$paymentInstruction.'</b></p>
                                    <p><b><i class="material-icons left">local_shipping</i>'.$shipmentMethod.'</b></p>';
                                    if(isset($condition)){
                                        echo '<p><i class="material-icons left">loyalty</i>'.$condition.'</p>';
                                    }
                                    echo'
                                    <div class="col s12 center review-box star-box">
                                        ';
                                        $getReviewQuery = $dbh->prepare("SELECT AVG(beoordeling) as reviewWaarde
                                         FROM feedback WHERE verkoper = ?
                                        ");
                                        $getReviewQuery->execute(array($seller));
                                        $review = $getReviewQuery->fetch(PDO::FETCH_ASSOC);
                                        getStarsReview($review['reviewWaarde']);
                                        echo '
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>     
                    
                    <!-- beschrijving product -->  
                                
                    <div class="col s12 m7 description">
                    <h5 class="text-bold">'.$title.'</h5>';                    

                    ?>
                        <?php if($description != strip_tags($description)) { ?>
                        <iframe id="iframe-description" frameBorder="0" srcdoc='<?php echo $description; ?>'>
                            <p>Your browser does not support iframes.</p>
                        </iframe>
                        <?php } else { ?>
                            <p><?php echo $description ?></p>
                        <?php } ?>
               
                    </div>

                    <!-- Bieden -->        
                    <div class="col s12 m5 ">
                    <?php 
                        $queryBid = $dbh->prepare("SELECT TOP 3 * FROM Bod 
                        INNER JOIN voorwerp on bod.voorwerp = voorwerp.voorwerpnummer 
                        WHERE voorwerp.voorwerpnummer = ? ORDER BY bodBedrag DESC");
                        $queryBid->execute(array($id));
                        $fetchBids = $queryBid->fetchAll(PDO::FETCH_ASSOC);
                    echo '                        
                        <div class="col s12 info-box rounded z-depth-3">
                            <div class="col s12">';
                            // check for buyer
                            if(isset($buyer) && $buyer !== ""){
                                echo'<p><i class="material-icons left">person_pin</i>Koper: '.$buyer.'</p>';
                                // check if the logged in user is the seller so the seller can contact the buyer
                                if(isset($_SESSION["User"]) && $_SESSION["User"]["gebruikersnaam"] == $seller && $seller !== $buyer){
                                    echo '<p><i class="material-icons left">forum</i>Bericht <a href="../php/chat.php?id='.$buyer.'" class="black-text"><u class="text-bold"> '.$buyer.'</u></a></p>';
                                }
                                // check if the logged in user is the buyer so the buyer can contact the seller
                                if(isset($_SESSION["User"]) && $_SESSION["User"]["gebruikersnaam"] == $buyer && $seller !== $buyer){
                                    echo '<p><i class="material-icons left">forum</i>Bericht <a href="../php/chat.php?id='.$seller.'" class="black-text"><u class="text-bold"> '.$seller.'</u></a></p>';
                                }
                            }
                            // if no buyer and de date is higher or same, time is higher.
                            if(!isset($buyer)){
                                if((strtotime(date('d-n-Y')) > strtotime($fetchedDate)) || ((strtotime(date('d-n-Y')) == strtotime($fetchedDate)) && (strtotime(date('H:i')) >= strtotime($fetchedTime)))){
                                    $checkBids = $dbh->prepare("SELECT TOP 1 * FROM Bod WHERE bod.voorwerp = ? ORDER BY bod.bodBedrag DESC");
                                    $checkBids->execute(array($auctionInfo));
                                    $fetchHighestBid = $checkBids->fetchAll(PDO::FETCH_ASSOC);                                    
                                    if($checkBids->rowCount() == 1){
                                        $newBuyer = $fetchHighestBid[0]["gebruiker"];
                                        $updateItem = $dbh->prepare("UPDATE voorwerp SET koper = ?, veilingGesloten = 1 WHERE voorwerpnummer = ?");
                                        $updateItem->execute(array($newBuyer, $auctionInfo));
                                        echo'<script>
                                        page = window.location.href;
                                        window.replace(page);
                                        </script>';
                                    }else{
                                        echo '<p>geen boden geplaatst</p>';
                                    }                                    
                                }
                            }
                    echo'
                            <h6><b>bieden vanaf &euro;'.round($startPrice).',-</b></h6>';
                                if(sizeof($fetchBids) > 0){
                                    foreach($fetchBids as $bid){
                                        echo '<p>';
                                        echo  $bid['gebruiker']." <span class='date'>".$bid['bodDag']." ".$bid['bodTijdstip'].'</span><b> €'.$bid['bodBedrag'].'</b>';
                                        echo '</p>';
                                    }
                                }else{
                                    echo'<p>Er is nog niet geboden op deze veiling</p>';
                                }
                                
                    echo '  </div>
                                            
                            <div class="col s12 center bid-box">
                            ';
                            if(!isset($buyer) || !(strtotime(date('d-n-Y')) > strtotime($fetchedDate)) || !((strtotime(date('d-n-Y')) == strtotime($fetchedDate)) && (strtotime(date('H:i')) >= strtotime($fetchedTime)))){                            
                                if(isset($_SESSION["User"])){                                
                                    $username = $_SESSION["User"]["gebruikersnaam"];
                                    if($seller == $username){
                                        echo '<p class="yellow black-text rounded bid-login" >Kan niet bieden op je eigen veiling</p>';
                                    }else if($_SESSION["User"]["geverifieerd"] !== '1' && $seller !== $username){
                                        echo '<p class="yellow black-text rounded bid-login">Verifieer eerst uw email om te bieden</p>';
                                    }
                                    else if($_SESSION["User"]["geverifieerd"] == '1' && $seller !== $username){                                    
                                        if(sizeof($fetchBids) > 0) {
                                        $highestOffer = $fetchBids[0]['bodBedrag'];
                                        $startBidPrice = $highestOffer + getIncrease($highestOffer);
                                        $quickBid1 = $highestOffer + (getIncrease($highestOffer)*2 );
                                        $quickBid2 = $highestOffer + (getIncrease($highestOffer)*3 );
                                        $quickBid3 = $highestOffer + (getIncrease($highestOffer)*4 );
                                        }
                                        else if(sizeof($fetchBids) == 0){
                                            $startBidPrice = $startPrice + getIncrease($startPrice);
                                            $quickBid1 = $startPrice + (getIncrease($startPrice)*2);
                                            $quickBid2 = $startPrice + (getIncrease($startPrice)*3);
                                            $quickBid3 = $startPrice + (getIncrease($startPrice)*4);
                                        }
                                    echo '
                                        <form action="../functions/place_bod.php?nr='.$auctionInfo.'" method="post" autocomplete="off">
                                            <input class="borderless-input eigen-bod-input z-depth-3" type="number" step="0.1" value="'.$startBidPrice.'" id="input_veiling" name="bod-veiling"> 
                                            <button class="btn waves-effect yellow rounded place-bid-btn" type="submit" name="submit-bod-veiling" style="margin-top: 5px">
                                                Plaats bod
                                            </button>
                                        </form>
                                        <div class="col s12 ">
                                    <h6><b>Snel bieden</b></h6>
                                    <form action="../functions/place_bod.php?nr='.$auctionInfo.'" method="post" autocomplete="off">
                                        <input type="number" steps="0.1" name="bod-veiling" class="no-show-input" value="'.$quickBid1.'">                       
                                        <button class="btn-small waves-effect yellow rounded black" name="submit-bod-veiling"  type="submit" style="margin: 5px 0px 5px 0px">
                                            €'.$quickBid1.'
                                        </button>
                                        <input type="number" steps="0.1" name="bod-veiling" class="no-show-input" value="'.$quickBid2.'">
                                        <button class="btn-small waves-effect yellow rounded black" value="" name="submit-bod-veiling" value="'.$quickBid2.'" type="submit" style="margin: 5px 0px 5px 0px">
                                            €'.$quickBid2.'
                                        </button>
                                        <input type="number" steps="0.1" name="bod-veiling" class="no-show-input" value="'.$quickBid3.'">
                                        <button class="btn-small waves-effect yellow rounded black" name="submit-bod-veiling" value="'.$quickBid3.'" type="submit" style="margin: 5px 0px 5px 0px">
                                            €'.$quickBid3.'
                                        </button>
                                    </form> 
                                </div> 
                                    ';}                                      
                                }else{
                                    echo '<a href="../php/login.php"><button class="btn yellow black-text rounded bid-login" >Log in om te bieden</button></a>';
                                }
                            }else{
                                echo '<p>je kan niet meer bieden op dit item</p>';
                            }
                                echo '
                            </div>
                        </div>                    
                    </div> 
                </div>
            </div>
            ';
            echo'<script src="../script/countdown.js"> </script>';     
            }
        }
        ?>
    </main>
    <!--footer php -->
    
<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>
