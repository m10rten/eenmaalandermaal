<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
    date_default_timezone_set('Europe/Amsterdam');
$realPath = $_SERVER['DOCUMENT_ROOT'];
require $realPath . '/requirers/dbh_inc.php';

require "$realPath/functions/test_categories.php";

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

    
