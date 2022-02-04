<?php

require '../requirers/dbh_inc.php';


$message = '';

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    $message = '';

    if(!checkToken($dbh, $token, 'wachtwoord vergeten')) {
        $message = 'token is niet geldig';
    } 
} 
