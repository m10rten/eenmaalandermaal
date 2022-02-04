<?php 
    session_start();
    if(empty($_SESSION['User']) || $_SESSION["User"]["is_beheerder"] == 0){
        header("Location: ../php/login.php?pop=nice try");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Beheer</title>
    <link href="../css/verkoper.css" rel="stylesheet" />
    <link href="../css/beheren.css" rel="stylesheet" />

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!--header php-->
<?php
      
    include '../includes/header_inc.php';
          
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","beheren.php","");
        include '../requirers/dbh_inc.php';
        include '../includes/user_inc.php';
        include '../functions/pop_check.php';
        include '../functions/auctions.php';
        include '../includes/card_inc.php';
        include '../functions/reviews.php';

        ?>
        <div class="container">
            <h1 class="center">Beheren</h1>  
            <div class="row">
                <div class="col s12">
                    <div class="container">
                        <ul class="tabs yellow-text">
                            <li class="tab col s4"><a <?php if(isset($_GET['activeTab']) && $_GET['activeTab'] == 'veilingen') {echo 'class="active"';}?> href="#veilingen">Veilingen</a></li>
                            <li class="tab col s4"><a <?php if(isset($_GET['activeTab']) && $_GET['activeTab'] == 'gebruikers') {echo 'class="active"';}?> href="#gebruikers">Gebruikers</a></li>
                            <li class="tab col s4"><a <?php if(isset($_GET['activeTab']) && $_GET['activeTab'] == 'reviews') {echo 'class="active"';}?>href="#reviews">reviews</a></li>
                        </ul>
                    </div>
                </div>

                <!-- zoekresultaten -->
                <div id="veilingen" class="col s12">
                    <div class="row">
                                <!-- zoekbalk -->
                                <div class="col s8 offset-s2">
                    <form action="" method="get" autocomplete="off">
                        <div class="input-field">
                                <input class="center" id="search-auction" type="search" name="zoek-query-auction" placeholder="zoeken.." value="<?php echo (isset($_GET['zoek-query-auction']) ? $_GET['zoek-query-auction'] : null) ?>">
                                <i class="material-icons">close</i>
                        </div>
                    </form>
                </div>
                    </div>

                    <div class="row" id="admin-auctions">
                        <?php foreach(getAuctions($dbh, isset($_GET['zoek-query-auction']) ? $_GET['zoek-query-auction'] : null) as $auction)  {
                        
                             
                            $id = $auction["voorwerpnummer"];
                            $getImage = $dbh->prepare("SELECT * FROM Bestand WHERE voorwerp = ?");
                            $getImage->execute(array($id));
                            $image = $getImage->fetch(PDO::FETCH_ASSOC);
                            if($getImage->rowCount() == 0){
                                $src = '../media/default-image.jpg';
                            }
                            else{
                                $src = $image["filenaam"];
                            }
                            $title = $auction["titel"];
                            $bid = $auction['hoogsteBod'];
                            $date = $auction["looptijdEindeDag"];
                            
                            $genre = $auction['rubrieknaam'];
                            $auctionnumber = $auction['voorwerpnummer'];
                            $active = $auction['isActief'];
                            echo '<div class="col s12 m6 l4" >';
                            echo card('admin',$title, $bid, $date, $id, $src, $genre, $auctionnumber, $active);                                
                            echo '</div> ';                        
                        }                       
                        ?> 
                    </div>
                    <div class="col s12 load-more">
                        <button class="btn rounded yellow text-bold admin-margin" id="loadMoreSearch-auctions">
                            laad meer
                        </button>
                    </div>
                </div>
                <div id="gebruikers" class="col s12">
                    <div class="row">
                        <!-- zoekbalk -->
                        <div class="col s8 offset-s2">
                            <form action="" method="get" autocomplete="off">
                                <input type="hidden" name="activeTab" value="gebruikers" />
                                <div class="input-field">
                                    <input class="center" id="search-user" type="search" name="zoek-query-user" placeholder="zoeken.." value="<?php echo (isset($_GET['zoek-query-user']) ? $_GET['zoek-query-user'] : null) ?>">
                                    <i class="material-icons">close</i>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" id="admin-users">
                        <?php 
                            foreach(getUsers($dbh, isset($_GET['zoek-query-user']) ? $_GET['zoek-query-user'] : null) as $user) {
                                userCard($user['gebruikersnaam'], $user['mailbox'], $user['is_geblokkeerd']);
                            }                            
                        ?>
                    </div>
                    <div class="col s12 load-more">
                        <button class="btn rounded yellow text-bold admin-margin" id="loadMoreSearc-users">
                            laad meer
                        </button>
                    </div>           
                </div>
                <div class="col s12" id="reviews">
                    <div class="row">
                        <?php
                            foreach(getReviews($dbh) as $review){
                                $reviewer = $review["reviewer"];
                                $rating = $review["beoordeling"];
                                $description = $review["beschrijving"];
                                $date = $review["dag"];
                                $time = $review["tijdstip"];
                                $itemId = $review["voorwerp"];
                                $reviewId = $review["reviewnummer"];
                                $getItemName = $dbh->prepare("SELECT * FROM voorwerp WHERE voorwerpnummer = ?");
                                $getItemName->execute(array($itemId));
                                $fetchName = $getItemName->fetch(PDO::FETCH_ASSOC);
                                echo '                                                    
                                    <div class="col s1">
                                    <i class="material-icons small left rating-icon">rate_review</i>
                                    </div>
                                    <div class="col ';
                                        if(isset($_SESSION["User"]) && $_SESSION["User"]["is_beheerder"] == 1){echo 's10';
                                        }else{echo 's11';} 
                                        echo '">
                                        <div class="col s12 center">
                                            <h6 class="text-bold margin-top-0">'.$fetchName['titel'].'</h6>
                                        </div>
                                        <div class="col s12 l6">
                                            <i class="material-icons left">person_pin</i><span class="text-bold">'.$reviewer.'</span>
                                        </div>
                                        <div class="col s12 l6">
                                            <div class="text-bold margin-top-0 review-box star-box">'; echo getStarsReview($rating); echo '</div>
                                        </div>
                                        <div class="col s12">
                                            <i class="material-icons left">textsms</i>'.$description.'
                                        </div>
                                        <div class="col s12">
                                            '.$date.' '.$time.'
                                        </div>
                                    </div>
                                    ';
                                    // laat een X zien als de ingelogde gebruiker een beheerder is
                                    if(isset($_SESSION["User"]) && $_SESSION["User"]["is_beheerder"] == 1){
                                        echo '
                                        <form action="../includes/del-review_inc.php?r='.$reviewId.'" method="post">
                                            <div class="col s1 ">
                                                <input type="text" class="d-none" name="hidden-review-number" value="'.$reviewId.'">
                                                '; 
                                                if($review["isGeblokkeerd"] == 1){
                                                    echo '<input hidden value="'.$review["isGeblokkeerd"].'" name="review-blocked-status">
                                                    <button class="green rounded close-button z-depth-1 text-bold white-text" type="submit" name="submit-remove-review"><i class="material-icons" >check</i></button>
                                                        ';
                                                }else{
                                                    echo '<input hidden value="'.$review["isGeblokkeerd"].'" name="review-blocked-status">
                                                    <button class="red rounded close-button z-depth-1 text-bold white-text" type="submit" name="submit-remove-review"><i class="material-icons" >close</i></button>
                                                    ';
                                                }
                                                echo'
                                            </div>
                                        </form>
                                        ';
                                    }
                                    echo '
                                    <hr class="col s12 card center">
                                ';
                            }
                            ?>
                    </div>
                </div>
            </div>  
        </div>                    
    </main>

    

    <!--footer php -->
<?php 
    include '../includes/footer_inc.php';
?>
    <script>
            document.getElementById('search-user').onkeydown = function(e){
                if(e.keyCode == 13){
                    $(this).submit();
                }
            };
        $(document).ready(function(){
            var items = 20;
            var users = 20;
            var searchUser = '<?php                          
                if(isset($_GET['zoek-query-user']) && !empty($_GET['zoek-query-user']) && $_GET['zoek-query-user'] !== ""){
                    echo $_GET["zoek-query-user"];
                }                                  
                ?>';  
            var searchAuction = '<?php                          
                if(isset($_GET['zoek-query-auction']) && !empty($_GET['zoek-query-auction']) && $_GET['zoek-query-auction'] !== ""){
                    echo $_GET["zoek-query-auction"];
                }                                  
            ?>';          
            $("#loadMoreSearch-auctions").click(function(){
                items = items + 20;
                $("#admin-auctions").load("../includes/load-items-admin_inc.php", {
                    newitemCount: items,
                    search_query: searchAuction,
                    method: 'auctions'
                });                
            });            
            $("#loadMoreSearc-users").click(function(){
                users = users + 20;
                $("#admin-users").load("../includes/load-items-admin_inc.php", {
                    newitemCount: users,
                    search_query: searchUser,
                    method: 'users'
                });                
            });
        });
    </script>
</body>
</html>
