<?php

function showBreadcrumbs($nr1,$nr2,$nr3){
    echo " 
<div class='container center'>    
    <div class='col s12'>
        <ul>
            <a class='black-text' href='../$nr1.php'> Home </a>
            ";
            if($nr2 !== ""){
                $n2 =  substr($nr2, 0, strpos($nr2, ".php"));
            echo"<a class='black-text' href='../php/$nr2'> $n2 </a>";
            }
            if($nr3 !== ""){
                $n3 =  substr($nr3, 0, strpos($nr3, ".php"));    
            echo"<a class='black-text' href='../php/$nr3'> $n3 </a>";
            }
    echo"</ul>
    </div>
</div>";   
}

// broodkruimel functie voor de rubrieken.php
function breadcrumbCat($nr1,$n1, $nr2,$n2, $nr3,$n3, $nr4){
    echo '
    <div class="container center">
        <div class="col s12">
            <ul>
                <a href="../index.php" class="black-text">Home</a>
                <a href="../php/rubrieken.php" class="black-text">Rubrieken</a>
            ';
            if($nr1 !== ""){
                echo '<a href="../php/rubrieken.php?c='.$n1.'" class="black-text">'.$nr1.'</a>';                
            }
            if($nr2 !== ""){
                echo '<a href="../php/rubrieken.php?c='.$n2.'" class="black-text">'.$nr2.'</a>';                
            }
            if($nr3 !== ""){
                echo '<a href="../php/rubrieken.php?c='.$n3.'" class="black-text">'.$nr3.'</a>';                
            }
            if($nr4 !== ""){
                echo '<a href="../php/rubrieken.php?c='.$nr4.'" class="black-text">'.$nr4.'</a>';                
            }
            echo '
            </ul>
        </div>
    </div>';
}

function breadcrumbAuction($nr1,$n1, $nr2, $id){
    echo '
    <div class="container center">
        <div class="col s12">
            <ul>
                <a href="../index.php" class="black-text">Home</a>
            ';
            if($nr1 !== ""){
                echo '<a href="../php/rubrieken.php?c='.$n1.'" class="black-text">'.$nr1.'</a>';                
            }
            if($nr2 !== ""){
                echo '<a href="../php/veiling.php?v='.$id.'" class="black-text">'.$nr2.'</a>';                
            }
    echo '
    </ul>
</div>
</div>';
}

function userExists($dbh,$name){
    $result;
    $query = $dbh->prepare('SELECT * FROM gebruiker WHERE gebruikersnaam = ? OR mailbox = ?');
    $query->execute(array($name, $name));

    if($query->rowCount() == 0){
        $result = false;
    } 
    else{
        $result = true;
    }
    return $result;
}

function userFind($dbh,$name = null, $mail = null){
    $result;
    $query = $dbh->prepare('SELECT * FROM gebruiker WHERE gebruikersnaam = ?  OR mailbox = ?');
    $query->execute(array($name,$mail));

    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}


function emailCheck($mail){
    $result;
    if(filter_var($mail,FILTER_VALIDATE_EMAIL)){
        $result = true;
    } else{
        $result = false;
    }
    return $result;
}


function passwordCheckOut($pwd, $fetch){
    $result;    
    if($pwd !== $fetch){
        $result = false;
    }
    else {
        $result = true;
    }
    return $result;
}

function loginUser($dbh, $name, $pwd, $get){
    $userCheck = userExists($dbh, $name);

    $query = $dbh->prepare("SELECT * FROM Gebruiker WHERE gebruikersnaam = ?  OR mailbox = ?");
    $query->execute(array($name,$name));
    $fetchedArray = $query->fetch(PDO::FETCH_ASSOC);

    $count = $query->rowCount();
    $geblokkeerd = $fetchedArray['is_geblokkeerd'];

    if($geblokkeerd == 1) {
        header("location: ../php/login.php".$get."&pop=account blocked");
        exit();
    }
    if($userCheck === false){
        echo "false usercheck ";
        echo $name;
        header("location: ../php/login.php".$get."&pop=wrong login");
        exit();
    }
    $fetchedPassword = $fetchedArray['wachtwoord'];
    $pwdverify = password_verify($pwd, $fetchedPassword);

    if($pwdverify !== true){
            echo "false password ";
            echo $name;
            header("location: ../php/login.php".$get."&pop=wrong login");
            exit();
    }
    else if($pwdverify){
        $_SESSION["User"] = $fetchedArray;

        header("location: ../index.php?pop=login succes");
        exit();
    }  
}


function createUser($dbh, $firstname, $lastname, $username, $email, $pwd, $birthday, $state,
    $streetname, $housenumber, $zipcode, $country, $secretQuestion, $answerQuestion){
    
    $hashedPassword = password_hash($pwd, PASSWORD_DEFAULT);

    $query = $dbh->prepare('INSERT INTO Gebruiker (gebruikersnaam, voornaam, achternaam,
    adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox,
    wachtwoord, vraag, antwoordtekst)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $query->execute(array($username, $firstname, $lastname, $streetname, $housenumber,
    $zipcode, $state, $country, $birthday, $email, $hashedPassword, $secretQuestion, $answerQuestion));
}

function checkToken($dbh, $token, $token_type) {

    $query = $dbh->prepare("
        SELECT *
        FROM Gebruiker_Tokens
        WHERE token = ?
        AND token_type = ?
        AND verloopt_op >=  GETDATE() 
        AND gebruikt = 0
    ");

    $query->execute(array($token, $token_type));
    $gebruiker = $query->fetch(PDO::FETCH_ASSOC);

    $token_exists = $query->rowCount();
 
    if($token_exists) {
        return true;
    } else {
        return false;
    }
}

function useToken($dbh, $token, $token_type) {
    $query = $dbh->prepare("
    UPDATE Gebruiker_Tokens
    SET gebruikt = 1
    OUTPUT inserted.gebruiker
    WHERE token = ?
    ");
    $query->execute(array($token));
    $user = $query->fetch(PDO::FETCH_ASSOC);
  

    return $user['gebruiker'];
}


function cleanUrl($firstString, $filterWord = null){
    if($filterWord == null){
        $filterWord = '?pop';
    }
    return substr($firstString, 0, strpos($firstString, $filterWord));    
}

function showEmptyAuction(){
    echo '
            <div class="container">
                <div class="row">
                    <div class="col s12">
                        <h2 class="center">
                            Het is erg leeg hier...
                        </h2>
                    </div>
                    <div class="col s12">
                        <p class="center">klik <a href="../index.php">hier</a> om terug te gaan naar de homepagina</p>
                    </div>
                </div>
            </div>
            ';
}


function sendVerifyMail($dbh, $mail, $mailAddress, $username) {

    $token = bin2hex(random_bytes(16));
    $tomorrow = date( "Ymd", strtotime( "+1 days" ) );

    $query = $dbh->prepare('INSERT INTO Gebruiker_Tokens (gebruiker, token_type, token, verloopt_op, gebruikt)
    VALUES(?,?,?,?,?)');
    $query->execute(array($username, 'verificatie', $token, $tomorrow, 0));

    $mail->addAddress($mailAddress, '');

    $mail->isHTML(true);
    $mail->Subject = 'Nieuw account | EenmaalAndermaal';
    $mail->Body = "<p>Welkom bij EenmaalAndermaal. Verifieer jouw emailadres om te starten met bieden!</p><p><a href='https://iproject12.ip.aimsites.nl/php/verifieer.php?token=$token'>Verifiëren</a></p>";
    $mail->AltBody = 'Welkom, je bent vanaf nu ingeschreven voor de nieuwsbrief';

    $mail->send();
    $http_ref = $_SERVER['HTTP_REFERER'];

    if(strpos($http_ref, "?")){
        $address = cleanUrl($http_ref, "?");
    }else if(strpos($http_ref, "&")){
        $address = cleanUrl($http_ref, "&");
    }else if(!strpos($http_ref, "?")){
        $address = $http_ref;
    }
    
    header('Location: ' . $address . '?pop=mail sent');
    exit();

    if(!$mail->send()) {
        echo "Mesasage could not be sent.";
        echo 'Mailer error: ' . $mail->ErrorInfo;
    } else {
        echo "Message has been sent";
    }
    exit();
}

function verifyUser($dbh, $user) {
    $query = $dbh->prepare("
    UPDATE Gebruiker
    SET geverifieerd = 1
    WHERE gebruikersnaam = ?
    ");
    $query->execute(array($user));
}

function verifySeller($dbh, $user) {
    $query = $dbh->prepare("
    UPDATE Gebruiker
    SET verkoper = 1
    WHERE gebruikersnaam = ?
    ");
    $query->execute(array($user));
}

function sendPasswordResetMail($dbh, $mail, $username, $mailAddress) {
    $token = bin2hex(random_bytes(16));
    $tomorrow = date( "Ymd", strtotime( "+1 days" ) );

    $query = $dbh->prepare('INSERT INTO Gebruiker_Tokens (gebruiker, token_type, token, verloopt_op, gebruikt)
    VALUES(?,?,?,?,?)');
    $query->execute(array($username, 'wachtwoord vergeten', $token, $tomorrow, 0));

    $mail->addAddress($mailAddress, '');

    $mail->isHTML(true);
    $mail->Subject = 'Wachtwoord Resetten | EenmaalAndermaal';
    $mail->Body = "<p>U heeft een wachtwoord reset aangevraagd. Gebruik de link om een nieuw wachtwoord in te stellen.</p><p><a href='https://iproject12.ip.aimsites.nl/php/wachtwoordvergeten.php?token=$token'>Wachtwoord resetten</a></p>";
    $mail->AltBody = "U heeft een wachtwoord reset aangevraagd. Gebruik de link om een nieuw wachtwoord in te stellen. /n https://iproject12.ip.aimsites.nl/php/wachtwoordvergeten.php?token=$token";

    $mail->send();
    $http_ref = $_SERVER['HTTP_REFERER'];

    if(strpos($http_ref, "?pop")){
        $address2 = cleanUrl($http_ref);
    }else{
        $address2 = $http_ref;
    }
    
    header('Location: ' . $address2 . '?pop=mail sent');
    exit();

    if(!$mail->send()) {
        echo "Mesasage could not be sent.";
        echo 'Mailer error: ' . $mail->ErrorInfo;
    } else {
        echo "Message has been sent";
    }
    exit();
}

function checkVerifiedEmail($dbh, $user){
    $query = $dbh->prepare("SELECT geverifieerd FROM gebruiker WHERE gebruikersnaam = ?");
    $query->execute(array($user));
    $fetch = $query->fetch(PDO::FETCH_ASSOC);

    if($fetch['geverifieerd'] == 1){
        return true;
    }else{
        return false;
    }
}

function checkVerifiedSeller($dbh, $user){
    $query = $dbh->prepare("SELECT verkoper FROM gebruiker WHERE gebruikersnaam = ?");
    $query->execute(array($user));
    $fetch = $query->fetch(PDO::FETCH_ASSOC);

    if($fetch['verkoper'] == 1){
        return true;
    }else{
        return false;
    }
}

function createSeller($dbh, $username, $bank, $accountNumber, $creditcard){
    $query = $dbh->prepare("INSERT INTO Verkoper 
    (gebruiker, bank, bankrekening, controleoptienaam, creditcardnummer)
     VALUES (?,?,?,?,?);
    ");
    $query->execute(array($username, $bank, $accountNumber, 'creditcard', $creditcard));
}
function insertPhone($dbh, $username, $phone){
    $query = $dbh->prepare("INSERT INTO gebruikersTelefoon 
    (gebruiker, telefoon)
    VALUES (?,?)");
    $query->execute(array($username, $phone));
}

function getIncrease($value){
    if($value > 1.00 && $value < 49.99){
        return 0.50;
    }

    if($value >= 49.99 && $value < 499.99){
        return 1.00;
    }

    if($value >= 500.00 && $value < 999.99){
        return 5.00;
    }

    if($value >= 1000.00 && $value < 4999.99){
        return 10.00;
    }

    if($value >= 5000.00){
        return 50.00;    
    }
}

function sendBlockedMail($dbh, $mail, $mailAddress, $state, $type, $extraInfo = null) {

    $mail->addAddress($mailAddress, '');

    $mail->isHTML(true);

    if($type == 'veiling') {
        $mail->Subject = "Uw veiling is $state | EenmaalAndermaal";
        $mail->Body = "<p>Eenmaal Andermaal heeft besloten om de veiling met titel '<strong>$extraInfo</strong>' op de status '<strong>$state</strong>' te zetten.</p>";
        $mail->AltBody = "<p>Eenmaal Andermaal heeft besloten om  de veiling met titel'$extraInfo' op de status '$state' te zetten.</p>";
    } else if($type == 'gebruiker') {
        $mail->Subject = "Uw account is $state | EenmaalAndermaal";
        $mail->Body = "<p>Eenmaal Andermaal heeft besloten om '<strong>$mailAddress</strong>' op de status '<strong>$state</strong>' te zetten.</p>";
        $mail->AltBody = "<p>Eenmaal Andermaal heeft besloten om '$mailAddress' op de status '$state' te zetten.</p>";
    } else if ($type == 'review') {
        $mail->Subject = "Uw review is $state | EenmaalAndermaal";
        $mail->Body = "<p>Eenmaal Andermaal heeft besloten om de review van '<strong>$mailAddress</strong>' met de beschrijving '<strong>$extraInfo</strong>' op de status '<strong>$state</strong>' te zetten.</p>";
        $mail->AltBody = "<p>Eenmaal Andermaal heeft besloten om de review van '$mailAddress' met de beschrijving '$extraInfo' op de status '$state' te zetten.</p>";
    }

    $mail->send();
}

// return aantal sterren voor veiling pagina
function getStarsReview($input = null){
    if($input == 0 || $input < 0.3 ){
        showStars(0);
    }
    if( $input == 0.5 || ($input > 0.3 && $input < 0.7)){
        showStars(0.5);        
    }
    if( $input == 1 || ($input > 0.7 && $input < 1.3)){
        showStars(1);        
    }
    if( $input == 1.5 || ($input > 1.3 && $input < 1.7)){
        showStars(1.5);        
    }
    if( $input == 2 || ($input > 1.7 && $input < 2.3)){
        showStars(2);        
    }
    if( $input == 2.5 || ($input > 2.3 && $input < 2.7)){
        showStars(2.5);        
    }
    if( $input == 3 || ($input > 2.7 && $input < 3.3)){
        showStars(3);        
    }
    if( $input == 3.5 || ($input > 3.3 && $input < 3.7)){
        showStars(3.5);        
    }
    if( $input == 4 || ($input > 3.7 && $input < 4.3)){
        showStars(4);
    }
    if( $input == 4.5 || ($input > 4.3 && $input < 4.7)){
        showStars(4.5);                
    }
    if( $input == 5 || $input > 4.7){
        showStars(5);        
    }
}
// returned echo statements voor het tonen van sterren gebruikt in de vorige functie
function showStars($stars){
    echo'  <p> <b> <i class="material-icons left">stars</i> </b> </p>';
    if($stars == 0){
        echo '
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 0.5){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star_half</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 1){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 1.5){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star_half</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 2){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 2.5){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star_half</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 3){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 3.5){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star_half</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    
    if($stars == 4){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left">star_border</i> </b> </p>
        ';
    }
    if($stars == 4.5){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star_half</i> </b> </p>
        ';
    }
    if($stars == 5){
        echo '
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
            <p> <b> <i class="material-icons left yellow-text">star</i> </b> </p>
        ';
    }
}

