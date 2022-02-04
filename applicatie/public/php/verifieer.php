<?php
require '../requirers/dbh_inc.php';
require '../includes/functies_inc.php';

$message = '';

if(!isset($_GET['token'])) {
    $message = 'geen token';
} else {
    $token = $_GET['token'];
    $message = '';

    if(checkToken($dbh, $token, 'verificatie')) {
        $user = useToken($dbh, $token, 'verificatie');
        verifyUser($dbh, $user);

        $message = 'Uw heeft uw mail succesvol geverifieerd';

    } else {
        $message = 'token is niet geldig';
    }
}

?>


<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>EenmaalAndermaal | Verifiëren </title>
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
      <!--header php-->
      <?php 
         include '../includes/header_inc.php';
         ?>
      <main>
          <?php 
          showBreadcrumbs("index","verifieer.php","");
          ?>
         <div class="container">
            <h1 class="center">Status verificatie</h1>
            <p class="center"><?php echo $message ?></p>
        </div>
    </main>
      <!--footer php -->
      <?php 
         include '../includes/footer_inc.php';
         ?>
      </body>
</html>
