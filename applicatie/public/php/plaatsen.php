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
    
    <title>EenmaalAndermaal | Plaatsen</title>

    <!--header php-->
<?php    
    include '../includes/header_inc.php';    
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","plaatsen.php","");
        
        include '../functions/pop_check.php';
        require '../requirers/dbh_inc.php';

        ?>
        <div class="row center">
            <!-- plaatsen titel -->
            <div class="container">
                <h1 class="center margin-top-0">
                    Plaatsen
                </h1>
            </div>
            <?php
                $username = $_SESSION["User"]["gebruikersnaam"];
                if(checkVerifiedSeller($dbh, $username)){
                    echo '
                    <div class="container">
                <form action="../includes/plaatsen_inc.php" method="post" autocomplete="off" enctype="multipart/form-data">
                    <!-- afbeeldingen invoegen -->
                    <section class="row">                                                                 
                        <div class="col s12">
                            <div class="col s10 file-field input-field">
                                <img id="auctionImage0" src="" alt="" class="image-preview">                             
                                <span id="auctionImageText0" class="rounded yellow black-text text-img-preview"></span>
                                <input type="file" name="extraImage0" onchange="readImg(this,0)" accept="image/*" required>                                    
                                <div class="file-path-wrapper rounded z-depth-1">
                                    <input class="file-path validate borderless-input text-path-image" type="text" placeholder="selecteer foto...">
                                </div>                                    
                            </div>                                                
                            <div class="col s2 add-image">
                                <li id="add-image" class="btn col s12 material-icons rounded center green accent-4">add</li>
                            </div>
                        </div>
                    </section>
                    <!-- input velden -->                    
                    <!-- titel -->
                    <div class="col s12 m5 input-field rounded z-depth-2">
                        <input class="borderless-input" type="text" id="title_auction" name="title-auction" placeholder="titel..." 
                        required '; if(isset($_GET["tip"]) && $_GET["tip"] !== ""){ echo 'value="'.$_GET["tip"].'"';} echo'>
                        <label class="rounded yellow black-text label" for="title_auction"><span class="required">*</span> Titel</label>
                    </div>

                    <!-- categorie -->
                    <div class="col s12 m5 offset-m1 input-field rounded z-depth-2">
                    '; 
                    require '../functions/categories_plaatsen.php';
                    echo '
                        <label class="rounded yellow black-text label" for="categorie_auction"><span class="required">*</span> Categorie</label>
                    </div>
                    
                    <!-- startprijs/bieden vanaf -->
                    <div class="col s12 m5 l3 input-field rounded z-depth-2">
                        <input class="borderless-input" type="number" id="offerFrom_auction" name="startprice-auction" placeholder="bieden vanaf..." 
                        required '; if(isset($_GET["stp"]) && $_GET["stp"] !== ""){ echo 'value="'.$_GET["stp"].'"';} echo'>
                        <label class="rounded yellow black-text label" for="offerFrom_auction"><span class="required">*</span> Bieden vanaf</label>
                    </div>

                    <!-- veiling laatste dag -->
                    <div class="col s12 m5 l3 offset-m1 offset-l1 input-field rounded z-depth-2">
                        <input class="borderless-input datepicker" type="text" id="endingDay_auction" name="lastday-auction"
                        value="" required>
                        <label class="rounded yellow black-text label" for="endingDay_auction"><span class="required">*</span> Veiling laatste dag</label>
                    </div>

                    <!-- veiling eindtijd-->
                    <div class="col s12 m5 l3 offset-l1 input-field rounded z-depth-2">
                        <input class="borderless-input timepicker" type="text" id="endingDay_auction" name="endingtime-auction" placeholder="Eindtijd veiling..."
                        value="" required>
                        <label class="rounded yellow black-text label" for="endingDay_auction"><span class="required">*</span> Veiling eindtijd</label>
                    </div>

                    <!-- betaalwijze -->
                    <div class="col s12 m5 l3 offset-m1  input-field rounded z-depth-2">
                        <select name="paymentmethod-auction" id="paymentMethod_auction" class="borderless-input" required>
                        <option value="" disabled selected>Kies je betaalwijze...</option>
                        <option value="contant">Contant</option>
                        <option value="IDeal">IDeal</option>
                        <option value="creditcard">Creditcard</option>
                        <option value="bankoverschrift">Bankoverschrift</option>
                    </select>    
                        <label class="rounded yellow black-text label active" for="paymentMethod_auction" style="top: 2px;"><span class="required">*</span> Betaalwijze</label>
                    </div>

                    <!-- betaalinstructie -->
                    <div class="col s12 m5 l3 offset-l1 input-field rounded z-depth-2">
                        <select name="paymentinstruction-auction" id="paymentInstruction_auction" class="borderless-input" required>
                        <option value="" disabled selected> Kies je betaalinstructie...</option>
                        <option value="direct betalen">Direct betalen</option>
                        <option value="achteraf betalen">Betaal achteraf</option>
                        <option value="vooraf betalen">Betaal vooraf</option>
                    </select>    
                        <label class="rounded yellow black-text label active" for="paymentInstruction_auction" style="top: 2px;"><span class="required">*</span> Betaalinstructie</label>
                    </div>

                    <!-- stad -->
                    <div class="col s12 m5 l3 offset-m1 offset-l1 input-field rounded z-depth-2">
                        <input class="borderless-input" type="text" id="location_auction" name="location-auction" placeholder="Stad..." 
                        required '; if(isset($_GET["ctp"]) && $_GET["ctp"] !== ""){ echo 'value="'.$_GET["ctp"].'"';} echo'>
                        <label class="rounded yellow black-text label" for="location_auction"><span class="required">*</span>Stad</label>
                    </div>
                    
                    <!-- land -->
                    <div class="col s12 m5 l3  input-field rounded z-depth-2">
                        ';
                        include '../includes/country_inc.php';
                        echo '
                        <label class="yellow rounded label active"  for="input_country" style="top: 2px;" ><span class="required">*</span> Land</label> 
                    </div>

                    <!-- overdracht -->
                    <div class="col s12 m5 offset-m1 offset-l1 l3 input-field rounded z-depth-2" >
                        <select name="transfer-auction" id="transfer_auction" class="borderless-input" required>
                        <option value="" disabled selected> Kies een overdracht...</option>
                        <option value="ophalen">Komen ophalen</option>
                        <option value="bezorgen">Laten bezorgen</option>
                    </select>    
                        <label class="rounded yellow black-text label active" for="transfer_auction" style="top: 2px;"><span class="required">*</span> Overdracht</label>
                    </div>
                    
                    <!-- shipping cost -->
                    <div class="col s12 m5 l3 offset-l1 input-field rounded z-depth-2">
                        <input class="borderless-input" type="text" placeholder="2,99,-" id="shipping-costs" name="shippingcosts-auction" 
                        required '; if(isset($_GET["shp"]) && $_GET["shp"] !== ""){ echo 'value="'.$_GET["shp"].'"';} echo'>
                        <label class="rounded yellow black-text label" for="shipping-costs"><span class="required">*</span>verzendkosten</label>
                    </div>
                    <!-- beschrijving -->
                    <div class="col s12 m11 input-field rounded z-depth-2 "> 
                        <textarea id="description_auction" class="materialize-textarea borderless-input" placeholder="beschrijving" name="description-auction" 
                        required></textarea>
                        <label class="rounded yellow black-text label" for="description_auction"><span class="required">*</span> Beschrijving</label>
                    </div>

                    <!-- buttons -->
                    <!-- submit plaatsen -->
                    <div class="col s12">
                        <button class="btn rounded yellow black-text admin-margin" type="submit" name="submit-auction">plaatsen</button>
                    </div>
                    <!-- annuleren plaatsen -->
                    <div class="col s12">
                        <a href="#"> <button class="btn rounded yellow black-text admin-margin">annuleren</button></a>
                    </div>

                </form>
            </div>
                    '; 
                }
                else{
                    echo '<p class="col s12 center">het lijkt er op dat je nog geen verkoper bent.</p>

                    <p class="col s12 center">klik <u> <a class="black-text" href="../php/activeren.php">hier</a></u> om verkoper te worden.</p>';
                    
                }
            ?>
                     
        </div>
    </main>

    <!--footer php -->
    <script src="../script/auction.js"></script>
<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>