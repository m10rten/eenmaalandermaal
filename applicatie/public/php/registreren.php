<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Registreren</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","registreren.php","");
        include '../functions/pop_check.php';
        include '../functions/register_questions.php';
        ?>
        <div class="container">
            <h1 class="center">Registreren</h1>
            <div class="row center">
                <form action="../includes/register_inc.php" method="post" autocomplete="off" class="center-align-register">
                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Voornaam" id="input_voornaam" name="firstname-register" 
                            required <?php if(isset($_GET["fnr"]) && $_GET["fnr"] !== ""){ echo 'value="'.$_GET["fnr"].'"';}?>> 
                        <label class="yellow rounded label" for="input_voornaam"><span class="required">*</span> Voornaam</label>
                    </div>  
                    <div class="col s0 m2 l2"></div>
                    <div class="input-field col s12 m5 l5  center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Achternaam" id="input_achternaam" name="lastname-register" 
                            required <?php if(isset($_GET["lnr"]) && $_GET["lnr"] !== ""){ echo 'value="'.$_GET["lnr"].'"';}?>> 
                        <label class="yellow rounded label" for="input_achternaam"><span class="required">*</span> Achternaam</label>
                    </div>

                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Gebruikersnaam" id="input_username" name="username-register" 
                            required <?php if(isset($_GET["unr"]) && $_GET["unr"] !== ""){ echo 'value="'.$_GET["unr"].'"';}?>> 
                        <label class="yellow rounded label" for="input_username"><span class="required">*</span> Gebruikersnaam</label>
                    </div>
                    <div class="col s0 m2 l2"></div>
                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input" type="email" placeholder="email@example.com" id="input_email" name="email-register" 
                            required <?php if(isset($_GET["emr"]) && $_GET["emr"] !== ""){ echo 'value="'.$_GET["emr"].'"';}?>> 
                        <label class="yellow rounded label" for="input_email"><span class="required">*</span> Emailadres</label>
                    </div>

                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input col s10" type="password" placeholder="Wachtwoord" id="input_password" name="password-register" required>
                        <label class="yellow rounded label" for="input_password"><span class="required">*</span> Wachtwoord</label>
                        <div id="toggle_password" class="col s2 borderless-input " ><span onclick="togglePassword()" id="visibility-password" class="material-icons align-center-show-hide">visibility</span></div>
                    </div>
                    <div class="col s0 m2 l2"></div>
                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input col s10" type="password" placeholder="Herhaal wachtwoord" id="input-repeat_password" name="repeat-password-register" required>
                        <label class="yellow rounded label" for="input-repeat_password"><span class="required">*</span> Herhaal wachtwoord</label>
                        <div id="toggle_repeat-password" class="col s2 borderless-input " ><span onclick="togglePasswordRepeat()" id="visibility-password-repeat" class="material-icons align-center-show-hide">visibility</span></div>
                    </div>

                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input datepicker" type="text" placeholder="Geboortedatum" id="input_geboortedatum" name="birthday-register" required>
                        <label class="yellow rounded label" for="input_geboortedatum"><span class="required">*</span> Geboortedatum</label>
                    </div>
                    <div class="col s0 m2 l2"></div>
                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Plaats" id="input_plaats" name="state-register" 
                            required  <?php if(isset($_GET["str"]) && $_GET["str"] !== ""){ echo 'value="'.$_GET["str"].'"';}?>>
                        <label class="yellow rounded label" for="input_plaats"><span class="required">*</span> Plaats</label>
                    </div>

                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Straatnaam" id="input_straatnaam" name="streetname-register" 
                            required  <?php if(isset($_GET["snr"]) && $_GET["snr"] !== ""){ echo 'value="'.$_GET["snr"].'"';}?>>
                        <label class="yellow rounded label" for="input_straatnaam"><span class="required">*</span> Straatnaam</label>
                    </div>
                    <div class="col s0 m2 l2"></div>
                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Huisnummer" id="input_huisnummer" name="housenumber-register"
                            required  <?php if(isset($_GET["hnr"]) && $_GET["hnr"] !== ""){ echo 'value="'.$_GET["hnr"].'"';}?>>
                        <label class="yellow rounded label" for="input_password"><span class="required">*</span> Huisnummer</label>
                    </div>

                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Postcode" id="input_postcode" name="zipcode-register" 
                            required <?php if(isset($_GET["zcr"]) && $_GET["zcr"] !== ""){ echo 'value="'.$_GET["zcr"].'"';}?>>
                        <label class="yellow rounded label" for="input_postcode"><span class="required">*</span> Postcode</label>
                    </div>
                    <div class="col s0 m2 l2"></div>
                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded" >
                        <?php 
                            include '../includes/country_inc.php';
                        ?>
                        <label class="yellow rounded label active"  for="input_country" style="top: 2px;" ><span class="required">*</span> Land</label> 
                    </div>

                    <div class="input-field col s12 m5 l5  center z-depth-2 rounded" required>
                        <select name="secret-question-register" id="input_geheime-vraag" class="borderless-input">
                            <option value="" disabled selected>kies je geheime vraag</option>

                        <?php 
                            foreach($questions as $question) { 
                                echo "<option value='" . $question['vraagnummer'] . "'>" . $question['vraag'] . "</option>";
                             } 
                        ?>
                        </select>
                        <!-- <input class="borderless-input"  type="text"  placeholder="Kies een geheime vraag..." id="input_geheime-vraag" name="secret-question-register"> -->
                        <label class="yellow rounded label active"  for="input_geheime-vraag" style="top: 2px;"><span class="required">*</span> Geheime vraag</label>
                    </div>
                    <div class="col s0 m2 l2"></div>
                    <div class="input-field col s12 m5 l5 center z-depth-2 rounded">
                        <input class="borderless-input"  type="text"  placeholder="Antwoord geheime vraag..." id="input_antwoord-geheime-vraag" name="answer-secretquestion-register" required>
                        <label class="yellow rounded label"  for="input_antwoord-geheime-vraag"><span class="required">*</span> Antwoord Geheime vraag</label>
                    </div>
            <!-- recaptcha -->                    
            <!-- voor site -->
            <!-- <div class="col s12 center">
                <div class="g-recaptcha input-field" data-sitekey="6LfTsb4aAAAAABaEiYwkYde50MhAP5wgyrvC9qjB"></div>
            </div> -->
            <!-- localhost -->
            <div class="col s12 center">
                <div class="g-recaptcha input-field" data-sitekey="6Ldqy7QaAAAAABhyHV6SZl6G1VzQ6X79-3vWLy86"></div>
            </div>
               
                <div class="row">                
                    <div class="input-field col s12 center">
                        <button class="btn waves-effect yellow black-text rounded" type="submit" name="submit-register">
                            register   <i class="material-icons left">login</i>
                        </button>
                    </div>                 
                </div>
            </form>    

    </main>
    <!--footer php -->

<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>