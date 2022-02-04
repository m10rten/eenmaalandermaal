<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Vergeten</title>
 
    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        include '../functions/password_reset_token_check.php';
        showBreadcrumbs("index","wachtwoordvergeten.php","");
        include '../functions/pop_check.php';
    ?>
        <div class="container center">
            <h1>Wachtwoord vergeten</h1> <br>
 
    <?php if(isset($message)) { 
        echo "<p>$message</p>"; } ?>
 
    <?php if(!isset($_GET['token'])) {
        echo '
        <p class="center">Weet je het wachtwoord niet meer? Vul hieronder je e-mailadres in. </p>
        <p class="center">We sturen dan binnen enkele minuten een e-mail waarmee een nieuw wachtwoord kan worden aangemaakt.</p>
        <div class="row">
            <form action="../functions/password_forgot.php" method="post" autocomplete="off">
                <div class="input-field col s12 m6 offset-m3 center z-depth-2 rounded">
                    <input class="borderless-input" type="email" placeholder="email@example.com" id="input_email" name="email-forgot-password" required> 
                    <label class="yellow rounded label" for="input_email">Emailadres</label>
                </div>                  
                <div class="input-field col s12 center">
                    <button class="btn waves-effect yellow black-text rounded" type="submit" name="submit-wachtwoordvergeten">
                        Verzenden
                        <i class="material-icons left">mail</i>
                    </button>
                </div>
            </form>
        </div>
    ';
 
    

    } else if(isset($_GET['token']) && checkToken($dbh, $token, 'wachtwoord vergeten')) {
        echo '<div class="row">
            <form action="../functions/password_reset.php" method="post" autocomplete="off">
            <input hidden name="token" value="' . $_GET['token'] . '" />
            <div class="input-field col s12 m8 offset-m2 center z-depth-2 rounded">
            <input class="borderless-input col s10" type="password" placeholder="Wachtwoord" id="input_password" name="password" required>
            <label class="yellow rounded label" for="input_password">Wachtwoord</label>
            <div id="toggle_password" class="col s2 borderless-input " ><li onclick="togglePassword()" id="visibility-password" class="material-icons align-center-show-hide">visibility</li></div>
        </div>
 
        <div class="input-field col s12 m8 offset-m2 center z-depth-2 rounded">
            <input class="borderless-input col s10" type="password" placeholder="Herhaal wachtwoord" id="input-repeat_password" name="repeat-password" required>
            <label class="yellow rounded label" for="input-repeat_password">Herhaal wachtwoord</label>
            <div id="toggle_repeat-password" class="col s2 borderless-input " ><li onclick="togglePasswordRepeat()" id="visibility-password-repeat" class="material-icons align-center-show-hide">visibility</li></div>
        </div>                
                <div class="input-field col s12 center">
                    <button class="btn waves-effect yellow black-text rounded" type="submit" name="submit-nieuwwachtwoord">
                        Verzenden
                        <i class="material-icons left">mail</i>
                    </button>
                </div>
            </form>
        </div>';
    }

         ?>
        </div>

</div>
    </main>
    <!--footer php -->
 
<?php 
    include '../includes/footer_inc.php';
?>
 
</body>
</html>