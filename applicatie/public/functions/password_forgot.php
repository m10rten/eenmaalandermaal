<?php

if(isset($_POST['submit-wachtwoordvergeten'])) {

    require '../requirers/mail_inc.php';
    require '../requirers/dbh_inc.php';
    include '../includes/functies_inc.php';

    $email = htmlspecialchars($_POST['email-forgot-password']);
    
    $http_ref = $_SERVER['HTTP_REFERER'];

        if(strpos($http_ref, "?pop")){
            $address2 = cleanUrl($http_ref);
        }else if(!strpos($http_ref, "?pop")){
            $address2 = $http_ref;
        }

    if(userExists($dbh, $email, $email)) {

        $user = userFind($dbh, $email, $email);

        sendPasswordResetMail($dbh, $mail, $user['gebruikersnaam'], $user['mailbox']);
        header('Location: ' . $address2 . '?pop=mail%20sent');        
        exit();
    } else {
        header('Location: ' . $address2 .'?pop=unknown%20user');
        exit();
    }

} else {
    header('Location: ../php/wachtwoordvergeten.php?pop=nice%20try');
    exit();
}
