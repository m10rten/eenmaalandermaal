<?php
require '../requirers/dbh_inc.php';
include '../includes/functies_inc.php';
if(isset($_POST["submit-seller-account"])){   
    session_start();

    $bank = htmlspecialchars($_POST['bank-activate']);
    $accountNumber = htmlspecialchars($_POST['account-number-seller-account']);
    $creditcard = htmlspecialchars($_POST['creditcard-number-seller-account']);
    $phone = htmlspecialchars($_POST["phonenumber-activate"]);

    if(empty($bank) || $bank = "" || empty($accountNumber) || empty($creditcard)){
      header("Location: ../php/activeren.php?pop=empty input");
      exit();
    }
    if(!is_numeric($phone)){
      header("Location: ../php/activeren.php?pop=incorrect phonenumber");
      exit();
    }

    // checken op inputs zoals creditcard validatie
    if($bank == "mastercard") {
      if(strlen($creditcard) != 16) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    } else if($bank == "visa") {
      if(strlen($creditcard) < 13 || strlen($creditcard) > 16) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    } else if($bank == "amex") {
      if(strlen($creditcard) != 14) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    } else if($bank == "postbank") {
      if(strlen($creditcard) != 19) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    }

    // checken op inputs zoals creditcard validatie

    if($bank == "mastercard") {
      if(strlen($creditcard) != 16) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    } else if($bank == "visa") {
      if(strlen($creditcard) < 13 || strlen($creditcard) > 16) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    } else if($bank == "amex") {
      if(strlen($creditcard) != 14) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    } else if($bank == "postbank") {
      if(strlen($creditcard) != 19) {
        header("Location: ../php/activeren.php?pop=wrong creditcard number");
        exit();
      }
    }

    $username = $_SESSION["User"]["gebruikersnaam"];

    if(!checkVerifiedEmail($dbh, $username)){
      header("Location: ../activeren.php?pop=not verified email");
      exit();
    }

    verifySeller($dbh, $username);

    createSeller($dbh, $username, $bank, $accountNumber, $creditcard);
    insertPhone($dbh, $username, $phone);
    
    header("location: ../index.php?pop=activation succes");
    exit();
}
else{
    header("location: ../php/activeren.php?pop=nice try");
    exit();
}