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
      <title>EenmaalAndermaal | Activeren </title>
      <!--header php-->
        <?php 
         include '../includes/header_inc.php';
        ?>
      <main>
         <?php 
            include '../includes/functies_inc.php';
            showBreadcrumbs("index","activeren.php","");
            include '../functions/pop_check.php';
            require '../requirers/dbh_inc.php';
            ?>
         <div class="container">
            <h1 class="center">Verkoopaccount activeren</h1>
            <p class="center"><span class="required">*</span>Door verkoper te worden ga je ermee akkoord dat wij sommige gegevens laten zien aan andere gebruikers.</p>
            <p class="center">onder andere: <span class="text-bold">email, opgegeven locatie, telefoon nummer en gebruikersnaam.</span></p>
            <p class="center">Het is momenteel alleen mogelijk om in Nederland verkoper te worden.</p>
            <?php
            $username = $_SESSION["User"]["gebruikersnaam"];
                if(checkVerifiedEmail($dbh, $username)){
                    if(checkVerifiedSeller($dbh, $username)){
                        echo '
                            <p class="col s12 center">je bent al verkoper!</p>
                            <p class="col s12 center">Klik <u> <a class="black-text" href="../index.php">hier</a></u> om terug te gaan naar de homapagina</p>
                        ';
                    }else{                    
                    echo '
                    <div class="row center">
                    <form id="login-form" action="../functions/activate.php" method="post" autocomplete="off">
                    
                        <!-- Bank -->
                        <div class="col s12 m6 offset-m3 center  input-field rounded z-depth-2">
                        <select name="bank-activate" id="bank" class="borderless-input">
                            <option value="">Kies je betaalwijze...</option>
                            <option value="mastercard">Mastercard</option>
                            <option value="visa">Visa</option>
                            <option value="amex">Amex</option>
                            <option value="postbank">Postbank</option>
                        </select>    
                        <label class="rounded yellow black-text label active" for="bank" style="top: 2px;"><span class="required">*</span> Bank</label>
                        </div>
                        
                        <!-- Rekeningnummer -->                    
                        <div class="input-field col s12 m6 offset-m3 center z-depth-2 rounded">
                            <input class="borderless-input" type="text" placeholder="Rekeningnummer" id="input_rekeningnummer" name="account-number-seller-account" required> 
                            <label class="yellow rounded label" for="input_rekeningnummer"><span class="required">*</span> Rekeningnummer</label>
                        </div>
    
                        <!-- Creditcardnummer -->                    
                        <div class="input-field col s12 m6 offset-m3 center z-depth-2 rounded">
                            <input class="borderless-input" type="text" placeholder="Creditcardnummer" id="input_creditcardnummer" name="creditcard-number-seller-account" required> 
                            <label class="yellow rounded label" for="input_creditcardnummer"><span class="required">*</span> Creditcardnummer</label>
                        </div>

                        <!--telefoon nummer --> 
                        <div class="input-field col s12 m6 offset-m3 center z-depth-2 rounded">
                            <input class="borderless-input" pattern="06[0-9]{8}" type="tel" placeholder="06-12345678" id="input_phonenumber" name="phonenumber-activate" required> 
                            <label class="yellow rounded label" for="input_phonenumber"><span class="required">*</span>Tel.Nr</label>
                        </div>                        
    
                        <!-- Activatie knop verkoopaccount -->                    
                        <div class="input-field col s12 center">
                            <button class=" btn waves-effect yellow rounded s8 black-text text-bold" type="submit" name="submit-seller-account">
                                Verkoopaccount Activeren
                            <i class="material-icons left ">login</i>
                            </button>                        
                        </div>
    
                        <!-- Annuleer knop verkoopaccount -->                    
                        <div class="input-field col s12 center">
                            <a class=" btn waves-effect yellow rounded s8 black-text text-bold" href="mijnaccount.php" name="cancel">
                                Annuleren
                            </a>                        
                        </div>
                       
                    </form>
                </div>
            	';}
                }else{
                    echo '                    
                    <p class="col s12 center">het lijkt er op dat je email adres nog niet bevestigd is</p>
                    <p class="col s12 center">klik <u><a class="black-text" href="../php/verifieer.php">hier</a></u> om je email te bevestigen </p>
                    ';
                    //TODO: resend verificatie mail register
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
