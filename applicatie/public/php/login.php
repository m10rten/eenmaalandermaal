<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>EenmaalAndermaal | Login </title>
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
      <!--header php-->
      <?php 
         include '../includes/header_inc.php';
         ?>
      <main>
         <?php 
            include '../includes/functies_inc.php';
            showBreadcrumbs("index","login.php","");
            include '../functions/pop_check.php';
            ?>
         <div class="container">
            <h1 class="center">Inloggen</h1>
            <div class="row center">
                <form id="login-form" action="../includes/login_inc.php" method="post" autocomplete="off">
                    <!-- username -->                    
                    <div class="input-field col s12 m6 offset-m3 center z-depth-2 rounded">
                        <input class="borderless-input" type="text" placeholder="Gebruikersnaam" id="input_username" name="username-login"
                         value="<?php if(isset($_GET['unl']) && $_GET["unl"] !== ""){echo $_GET["unl"];}?>" required> 
                        <label class="yellow rounded label" for="input_username">Gebruikersnaam</label>
                    </div>                                

                    <!-- password !-->                    
                    <div class="input-field col s12 m6 offset-m3 center z-depth-2 rounded">
                        <input class="borderless-input col s10"  type="password"  placeholder="Wachtwoord" id="input_password" name="password-login" required>
                        <label class="yellow rounded label"  for="input_password">Wachtwoord</label>
                        <div id="toggle_password" class="col s2 borderless-input left" onclick="togglePassword()"><li id="visibility-password" class="material-icons align-center-show-hide">visibility</li></div>
                    </div>

                    <!-- nieuw wachtwoord en account -->
                    <div class="register-field col s12 center">
                        <p>Wachtwoord vergeten? Reset <a class="black-text " href="../php/wachtwoordvergeten.php"><u>hier.</u> </a></p>
                        <p>Nog geen account? Registreer<a class="black-text " href="../php/registeren.php"> <u>hier</u>.</a></p>
                    </div>

                    <!-- recaptcha --> 
                    <!-- voor site -->
                    <!-- <div class="col s12 center">
                        <div class="g-recaptcha input-field" data-sitekey="6LfTsb4aAAAAABaEiYwkYde50MhAP5wgyrvC9qjB"></div>
                    </div> -->
                    <!--  localhost -->
                    <div class="col s12 center">                    
                        <div class="g-recaptcha input-field" data-sitekey="6Ldqy7QaAAAAABhyHV6SZl6G1VzQ6X79-3vWLy86"></div>
                    </div> 
                    <!-- log in knop -->                    
                    <div class="input-field col s12 center">
                        <button class=" btn waves-effect yellow rounded" type="submit" name="submit-login">
                            login
                        <i class="material-icons left ">login</i>
                        </button>                        
                    </div>                    
                </form>
            </div>
        </div>
    </main>
      <!--footer php -->
      <?php 
         include '../includes/footer_inc.php';
         ?>
      </body>
</html>
