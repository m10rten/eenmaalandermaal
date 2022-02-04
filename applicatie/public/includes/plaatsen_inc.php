<?php
if(isset($_POST["submit-auction"])){
    require '../requirers/dbh_inc.php';

    session_start();
    date_default_timezone_set('Europe/Amsterdam');
    
    $title = htmlspecialchars($_POST['title-auction']);
    $category = htmlspecialchars($_POST['categorie-auction']);
    $startprice = htmlspecialchars($_POST['startprice-auction']);
    $city = htmlspecialchars($_POST['location-auction']);
    $country = htmlspecialchars($_POST['country-register']);
    $transfer = htmlspecialchars($_POST['transfer-auction']);
    $description = htmlspecialchars($_POST['description-auction']);
    $paymentI = htmlspecialchars($_POST['paymentinstruction-auction']);
    $paymentM = htmlspecialchars($_POST['paymentmethod-auction']);

    $shippingCosts = htmlspecialchars($_POST['shippingcosts-auction']);
    $endtime = htmlspecialchars($_POST['endingtime-auction']);
    $enddate = htmlspecialchars($_POST['lastday-auction']);
    $enddate_date = date("Y-m-d", strtotime($enddate));

    $get = '?tip='.$title.'&stp='.$startprice.'&ctp='.$city.'&shp='.$shippingCosts;

    $inputDate = $enddate_date." ".$endtime;
    
    $endDate = strtotime($inputDate);
    $time = time();  
    $diff = $endDate - $time;
    $diffDate = round($diff / (60 * 60 * 24));
    $startDate = date("Y-m-d");
    $startTime = date("H:i");       

    $notClosed = 0;
    $seller = $_SESSION["User"]["gebruikersnaam"];

    $imgs = ["extraImage0",
            "extraImage1",
            "extraImage2",
            "extraImage3",
            "extraImage4"
        ];
    $filePaths = [];

    $allowedExt = array('jpg','jpeg','png');
    $uploadDir = "../media/veilingen/";
    $newDirName = $title.rand(0,9999)."/";

    if(empty($title) || empty($category) || empty($startprice) || empty($city) || empty($country) || empty($transfer) || empty($description) || empty($paymentI) || empty($paymentM) || empty($endtime) || empty($enddate) || empty($seller) || empty($imgs)){
        header("Location: ../php/plaatsen.php".$get."&pop=empty input");
        exit();
    }
    if(!is_numeric($shippingCosts) || !is_numeric($startprice)){
        header("Location: ../php/plaatsen.php".$get."&pop=none numeric found");
        exit();
    }

    if(!is_dir("../media/veilingen/".$newDirName)){
        $newFolder = mkdir($uploadDir.$newDirName, 0777, true);
        $uploadDestination = $uploadDir.$newDirName;
        for($i = 0; $i < sizeof($imgs); $i+=1){
            if(!empty($_FILES[$imgs[$i]])){
                $image_files[$i] = $_FILES[$imgs[$i]];
                
                $fileName[$i] = $image_files[$i]['name'];
                $fileTmpName[$i] = $image_files[$i]['tmp_name'];
                $fileSize[$i] = $image_files[$i]['size'];
                $fileError[$i] = $image_files[$i]['error'];
                $fileType[$i] = $image_files[$i]['type'];

                $fileExt[$i] = explode('.', $fileName[$i]);
                $fileActExt[$i] = strtolower(end($fileExt[$i]));

                if(in_array($fileActExt[$i], $allowedExt)){
                    if($fileError[$i] === 0){
                        if($fileSize[$i] < 5000000){
                            $fileNameNew[$i] = uniqid('',true).".".$fileActExt[$i];
                            
                            $filePath = $uploadDestination.$fileNameNew[$i];
                            move_uploaded_file($fileTmpName[$i], $filePath);
                            $filePaths[$i] = $filePath;       
                        }                        
                        else{
                            deleteDir($uploadDestination);
                            header("Location: ../php/plaatsen.php".$get."&pop=oversized files");
                            exit();
                        }
                    }
                    else{                        
                        deleteDir($uploadDestination);
                        header("Location: ../php/plaatsen.php".$get."&pop=upload error");
                        exit();
                    }                    
                }
                else if(!in_array($fileActExt[$i], $allowedExt)){                                        
                    deleteDir($uploadDestination);
                    header("Location: ../php/plaatsen.php".$get."&pop=select image");
                    exit();
                }                
            } 
        }
            $queryObject = $dbh->prepare('INSERT INTO voorwerp (
                titel, beschrijving, startprijs, betalingswijze,
                betalingsinstructie, plaatsnaam, land,
                looptijd, looptijdBeginDag, looptijdBeginTijdstip,
                verzendkosten, verzendinstructies, verkoper,
                looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) 
                OUTPUT inserted.voorwerpnummer
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                ');
                $queryObject->execute(array($title, $description, 
                    $startprice, $paymentM, $paymentI, $city, $country, $diffDate,
                    $startDate, $startprice, $shippingCosts,$transfer, $seller, $enddate_date,
                    $endtime, $notClosed));

                $queryItemFetch = $queryObject->fetch(PDO::FETCH_ASSOC);
                $itemNr = $queryItemFetch["voorwerpnummer"];
                // insert item in category so it can be found
                $queryVoorwerpRubriek = $dbh->prepare("INSERT INTO VoorwerpInRubriek (voorwerp, rubriek)
                VALUES (?,?)");
                $queryVoorwerpRubriek->execute(array($itemNr, $category));

                for($f = 0; $f < sizeof($filePaths); $f++){
                    $queryFile = $dbh->prepare('INSERT INTO bestand (
                        filenaam, voorwerp
                    ) VALUES(?,?)
                    ');
                    $queryFile->execute(array($filePaths[$f],$itemNr));
                }                
            header("Location: ../php/plaatsen.php?pop=upload succes");
            exit();
    }   
}
else{
    header("Location: ../php/plaatsen.php?pop=nice try");
    exit();
}
function deleteDir($uploadDestination){
    if (! is_dir($uploadDestination)) {
        exit();
    }
    rmdir($uploadDestination);
}