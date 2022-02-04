<?php
if(isset($_POST['submit-letter'])) {
    require '../requirers/mail_inc.php';
    include '../includes/functies_inc.php';

    $email = $_POST['input-letter_email'];

    if(empty($email)){
        exit();
    }

    $mail->addAddress($email, '');

    $mail->isHTML(true);
    $mail->Subject = 'Nieuwsbrief';
    $mail->Body = 'Welkom, je bent vanaf nu ingeschreven voor de nieuwsbrief';
    $mail->AltBody = 'Welkom, je bent vanaf nu ingeschreven voor de nieuwsbrief';


    $http_ref = $_SERVER['HTTP_REFERER'];
    if(strpos($http_ref, "?")){
        $address = cleanUrl($http_ref, "?");
    }else if(strpos($http_ref, "&")){
        $address = cleanUrl($http_ref, "&");
    }else if(!strpos($http_ref, "?")){
        $address = $http_ref;
    }
    // exit();

    if(!$mail->send()) {
        echo "Mesasage could not be sent.";
        echo 'Mailer error: ' . $mail->ErrorInfo;
        header('Location: ' . $address . '?pop=mail%20not%20sent');
        exit();
    } else {
        echo "Message has been sent";    
    
        header('Location: ' . $address . '?pop=mail%20sent');
        exit();
    }   
}else{
    header('location: ../index.php?pop=nice try');
    exit();
}