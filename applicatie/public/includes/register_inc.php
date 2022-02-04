<?php
require '../requirers/dbh_inc.php';
require '../requirers/mail_inc.php';
include '../includes/functies_inc.php';
if(isset($_POST["submit-register"])){
    if(isset($_POST['g-recaptcha-response'])){
        $captcha=$_POST['g-recaptcha-response'];
      if(!$captcha){
        header("Location: ../php/registeren.php?pop=recaptcha unchecked");
        exit();
      }
    }

    $firstname = htmlspecialchars($_POST["firstname-register"]);
    $lastname = htmlspecialchars($_POST["lastname-register"]);
    $username = htmlspecialchars($_POST["username-register"]);
    $email = htmlspecialchars($_POST["email-register"]);
    $password = htmlspecialchars($_POST["password-register"]);
    $passwordRepeat = htmlspecialchars($_POST["repeat-password-register"]);
    
    $birthday_post = htmlspecialchars($_POST["birthday-register"]);
    $birthday = date("Y-m-d", strtotime($birthday_post));

    $state = htmlspecialchars($_POST["state-register"]);
    $streetname = htmlspecialchars($_POST["streetname-register"]);
    $housenumber = htmlspecialchars($_POST["housenumber-register"]);
    $zipcode = htmlspecialchars($_POST["zipcode-register"]);
    $country = htmlspecialchars($_POST["country-register"]);
    $secretQuestion = htmlspecialchars($_POST["secret-question-register"]);
    $answerQuestion = htmlspecialchars($_POST["answer-secretquestion-register"]);
    $get = '?fnr='.$firstname.'&lnr='.$lastname.'&unr='.$username.'&emr='.$email.
            '&str='.$state.'&snr='.$streetname.'&hnr='.$housenumber.'&zcr='.$zipcode;

    if(empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($passwordRepeat) || empty($birthday) || empty($state) || empty($streetname) || empty($housenumber) || empty($zipcode) || empty($country) || empty($secretQuestion) || empty($answerQuestion)){
        header("Location: ../php/registreren.php".$get."&pop=empty input");
        exit();
    }

    if (userExists($dbh, $email, $email ) !== false){
        header("location: ../php/registreren.php".$get."&pop=mail in use ");
        exit();
    }
    if (userExists($dbh, $username, $username ) !== false){
        header("location: ../php/registreren.php".$get."&pop=username in use ");
        exit();
    }
    if(passwordCheckOut($password, $passwordRepeat) === false){
        header("location: ../php/registreren.php".$get."&pop=passwords dont match");
        exit();
    }
    if(!is_numeric($housenumber)){
        header("location: ../php/registreren.php".$get."&pop=not valid housenumber");
        exit();
    }
    if(!emailCheck($email)){
        header("location: ../php/registreren.php".$get."&pop=not valid email");
        exit();
    }

    createUser($dbh, $firstname, $lastname, $username, $email, $password, $birthday, $state, $streetname, $housenumber, $zipcode, $country, $secretQuestion, $answerQuestion);
    sendVerifyMail($dbh, $mail, $email, $username);

    header('Location: ../php/login.php?pop=register succes');
    exit();

} else{
    header('Location: ../php/registreren.php?pop=nice try');
    exit();
}
