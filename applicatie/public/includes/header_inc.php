<?php 
// starts a session if not started already
if (session_status() !== PHP_SESSION_ACTIVE) {
session_start();
}
// sets the default time zone so time can be used to calculate stuff.
date_default_timezone_set('Europe/Amsterdam');
$realPath = $_SERVER['DOCUMENT_ROOT'];
    require $realPath . '/requirers/dbh_inc.php';
    require $realPath . "/functions/categories.php";
?>
<!-- Compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"/>

<!--Import Google Icon Font-->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

<!--own css-->
<link href="../css/stylesheet.css" rel="stylesheet" />
<link href="../css/stylesheetIndex.css" rel="stylesheet" />
<link href="../css/mediaQueries.css" rel="stylesheet" />

<!--favicon-->
<link rel="icon" href="../media/favicon.ico" type="image/x-icon" />

<!-- font awesome icons -->
<script src="https://kit.fontawesome.com/7581d5f274.js" crossorigin="anonymous"></script>

</head>
<body>

<?php 
// function to list all categories
    function echoListItems($cat) {
        if(isset($_SESSION["categories"])){
            foreach($cat as $category) {
                if(count($category['sub']) > 0) {
                    echo '<li><a class="black-text dropdown-trigger-top2" id="dropdown-category-' . $category['rubrieknummer']. '"  data-target="categorieen-dropdown-top' . $category['rubrieknummer'] .'" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                    echo '<ul id="categorieen-dropdown-top' . $category['rubrieknummer'] .'" caller="dropdown-category-' . $category['rubrieknummer']. '" class="dropdown-content">';
                    echoListItems($category['sub']);
                    echo '</ul>';
                } else {
                    echo '<li><a class="black-text" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                }
            }
        }else{
            foreach($cat as $category) {
                if(count($category['sub']) > 0) {
                    echo '<li><a class="black-text dropdown-trigger-top2" id="dropdown-category-' . $category['rubrieknummer']. '"  data-target="categorieen-dropdown-top' . $category['rubrieknummer'] .'" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                    echo '<ul id="categorieen-dropdown-top' . $category['rubrieknummer'] .'" caller="dropdown-category-' . $category['rubrieknummer']. '" class="dropdown-content">';
                    echoListItems($category['sub']);
                    echo '</ul>';
                } else {
                    echo '<li><a class="black-text" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                }
            }
        }        
    }
?>
    <!--header box-->
    <ul id="categorieen-dropdown-top" class="dropdown-content" caller="category-dropdown-head">
        <?php 
        // checks if there are session categories set.
        if(isset($_SESSION["categories"])){
            foreach($_SESSION["categories"] as $index => $category) { 
                if(count($category['sub']) > 0) {
                    echo '<li><a class="black-text dropdown-trigger-top2" id="dropdown-category-' . $category['rubrieknummer']. '"  data-target="categorieen-dropdown-top' . $category['rubrieknummer'] .'" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                    echo '<ul id="categorieen-dropdown-top' . $category['rubrieknummer'] .'" caller="dropdown-category-' . $category['rubrieknummer']. '" class="dropdown-content">';
                    echoListItems($category['sub']);
                    echo '</ul>';
                } else {
                    echo '<li><a class="black-text" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                }
            }
        }else{
            foreach($formattedCategories as $index => $category) { 
                if(count($category['sub']) > 0) {
                    echo '<li><a class="black-text dropdown-trigger-top2" id="dropdown-category-' . $category['rubrieknummer']. '"  data-target="categorieen-dropdown-top' . $category['rubrieknummer'] .'" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                    echo '<ul id="categorieen-dropdown-top' . $category['rubrieknummer'] .'" caller="dropdown-category-' . $category['rubrieknummer']. '" class="dropdown-content">';
                    echoListItems($category['sub']);
                    echo '</ul>';
                } else {
                    array_push($_SESSION["categories"],$category);
                    echo '<li><a class="black-text" href="/php/rubrieken.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li>';
                }
            }
        }
        ?>
    </ul>
    <header>
        <nav class="nav-wraper yellow sticky-nav">
            <div class="row">    
                <div class="col s12">       
                    <a href="../index.php" class="brand-logo"> <img src="../media/logo-groot-nbg.png" alt=""> </a> 
                    <a href="#" class="sidenav-trigger right" data-target="mobile-links">
                        <i class="material-icons black-text">menu</i>
                    </a>      
                    <ul class="right hide-on-med-and-down">    
                    <?php if(isset($_SESSION["User"]) && $_SESSION["User"]["is_beheerder"] == 1) { ?>
                        <li><a class="black-text" href="../php/beheren.php">Beheren</a></li>
                    <?php } ?>
   
                        <!-- <li><a class="black-text" href="../php/rubrieken.php">rubrieken</a></li>    -->
                        <li><a style="min-width:150px;" class="dropdown-trigger-top black-text center" id="category-dropdown-head" data-target="categorieen-dropdown-top">rubrieken<i class="material-icons right">arrow_drop_down</i> </a></li>
                                     
                        <?php
                            if(isset($_SESSION["User"])){
                                echo'<li><a style="min-width:150px;" class="dropdown-trigger-top black-text center" data-target="instellingen-dropdown-top">'.$_SESSION["User"]["gebruikersnaam"].'<i class="material-icons right">arrow_drop_down</i> </a></li>
                                <ul id="instellingen-dropdown-top" class="dropdown-content">
                                    <li><a class="black-text" href="../php/mijnaccount.php">account<i class="material-icons">home</i></a></li>
                                            <li class="divider"></li>
                                            <li><a class="black-text" href="../php/mijnaccount.php?activeTab=mijnveilingen">Veilingen<i class="material-icons">library_books</i></a></li>
                                            <li><a class="black-text" href="../php/mijnaccount.php?activeTab=favorieten">Wenslijst<i class="material-icons prefix">favorite</i></a></li>
                                            <li><a class="black-text" href="../php/mijnaccount.php?activeTab=mijnbiedingen">Boden <i class="material-icons prefix">gavel</i></a></li>
                                            <li><a class="black-text" href="../php/mijnaccount.php?activeTab=mijnreviews">Reviews <i class="material-icons prefix">rate_review</i></a></li>
                                            <li><a class="black-text" href="../php/gebruiker.php">Profiel <i class="material-icons prefix">person</i></a></li>
                                            <li class="divider"></li>
                                        <li><a class="black-text" href="../php/berichten.php">berichten<i class="material-icons black-text prefix">message</i></a></li>
                                            <li class="divider"></li>
                                    <li><a class="black-text" href="../includes/logout_inc.php">uitloggen<i class="material-icons prefix">swap_horiz</i></a></li>
                                </ul>
                                <li><a class="black-text grey btn" href="../php/plaatsen.php">plaatsen<i class="material-icons right yellow-text">add</i></a></li>';
                            } else {
                                echo '<li><a class="black-text" href="../php/login.php">login</a></li>
                                    <li><a class="black-text" href="../php/registreren.php">registreren</a></li>';
                            }
                        ?>                    
                        <li class="search" >
                            <div class="search-wrapper blue-text">
                                <form action="../php/zoeken.php" autocomplete="off" method="get">
                                    <div class="input-field">
                                        <input class="center" id="search" type="search" name="q" placeholder="zoeken.." required>
                                        <i class="material-icons">close</i>
                                    </div>
                                </form>
                            </div>
                        </li>                 
                    </ul>
                </div>
            </div>
        </nav>  
        
        <ul class="sidenav yellow" id="mobile-links">
        <li><a href="../index.php" class="brand-logo"> <img id="navbar-logo" src="../media/logo-groot-nbg.png" alt=""> </a> </li>
        <li>     
            <a class="black-text" href="../php/zoeken.php">Zoeken</a>     
        </li> 
        <li><a class="black-text" href="../php/rubrieken.php">Rubrieken</a></li>              
            
            <?php
                if(isset($_SESSION["User"])){
                    echo'<li><a style="min-width:150px;" class="dropdown-trigger-side black-text" data-target="instellingen-dropdown-side">'.$_SESSION["User"]["gebruikersnaam"].' <i class="material-icons right">arrow_drop_down</i> </a></li>
                    <ul id="instellingen-dropdown-side" class="dropdown-content">
                        <li><a class="black-text" href="../php/mijnaccount.php">Account<i class="material-icons">home</i></a></li>
                        <li class="divider"></li>
                        <li><a class="black-text" href="../php/mijnaccount.php?activeTab=mijnveilingen">Veilingen<i class="material-icons">library_books</i></a></li>
                        <li><a class="black-text" href="../php/mijnaccount.php?activeTab=favorieten">Wenslijst<i class="material-icons prefix">favorite</i></a></li>
                        <li><a class="black-text" href="../php/mijnaccount.php?activeTab=mijnbiedingen">Boden <i class="material-icons prefix">gavel</i></a></li>
                        <li><a class="black-text" href="../php/mijnaccount.php?activeTab=mijnreviews">Reviews <i class="material-icons prefix">rate_review</i></a></li>
                        <li><a class="black-text" href="../php/gebruiker.php">Profiel <i class="material-icons prefix">person</i></a></li>

                        <li class="divider"></li>
                        <li><a class="black-text" href="../includes/logout_inc.php">Uitloggen<i class="material-icons prefix">swap_horiz</i></a></li>
                    </ul>
                    <li><a href="../php/berichten.php">Berichten</a></li>
                    ';
                    if(isset($_SESSION["User"]) && $_SESSION["User"]["is_beheerder"] == 1) { ?>
                    <li><a class="black-text" href="../php/beheren.php">Beheren</a></li>
                <?php } 
                    echo '<li><a class="black-text grey btn z-depth-3" href="../php/plaatsen.php">Plaatsen<i class="material-icons right yellow-text accent-3 darken-4">add</i></a></li>';
                }
                else{
                    echo '<li><a class="black-text" href="../php/login.php">login</a></li>
                        <li><a class="black-text" href="../php/registreren.php">registreren</a></li>';
                }
                 
            ?>          
        </ul>
    </header>