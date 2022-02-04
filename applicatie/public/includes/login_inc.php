<?php
include '../includes/functies_inc.php';
require '../requirers/dbh_inc.php';
if(isset($_POST["submit-login"])){
  $_SESSION['post-username-login'] = $username;
      
    session_start();

    $username = htmlspecialchars($_POST["username-login"]);
    
    $password = htmlspecialchars($_POST["password-login"]);

    $get = '?unl='.$username;

    if(isset($_POST['g-recaptcha-response'])){
        $captcha=$_POST['g-recaptcha-response'];
      if(!$captcha){
        header("Location: ../php/login.php".$get."&pop=recaptcha unchecked");
        exit();
      }
    }
     
    if(empty($username) || empty($password)){
      header("Location: ../php/login.php".$get."&pop=empty input");
      exit();
    }

    loginUser($dbh, $username, $password, $get);    
}
else{
    header("location: ../php/login.php?pop=nice try");
    exit();
}