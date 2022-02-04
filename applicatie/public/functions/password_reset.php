<?php

session_start();

if(isset($_POST['submit-nieuwwachtwoord'])) {
    require '../requirers/dbh_inc.php';
    include '../includes/functies_inc.php';

    $password = $_POST['password'];
    $passwordRepeat = $_POST['repeat-password'];
    $token = $_POST['token'];

    $username = useToken($dbh, $token, 'wachtwoord vergeten');

    if(!passwordCheckOut($password, $passwordRepeat)) {
        $http_ref = $_SERVER['HTTP_REFERER'];
        //$address = str_replace("&pop=passwords not matching","", $http_ref);
        if(strpos($http_ref, "?pop")){
            $address2 = cleanUrl($http_ref);
        }else{
            $address2 = $http_ref;
        }

        header('Location: '. $address2 .'&pop=passwords dont match');
        exit();
    }

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    $query = $dbh->prepare('UPDATE Gebruiker
        SET wachtwoord = ?
        OUTPUT inserted.*
        WHERE gebruikersnaam = ?');
    $query->execute(array($passwordHashed,$username));
    $user = $query->fetch(PDO::FETCH_ASSOC);

    loginUser($dbh, $user['mailbox'], $password);


    header('location: ../index.php');
    exit();
}else{
    header("Location: ../php/wachtwoordvergeten.php?pop=nice try");
    exit();
}
