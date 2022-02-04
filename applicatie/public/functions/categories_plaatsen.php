<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$realPath = $_SERVER['DOCUMENT_ROOT'];;
require "$realPath/requirers/dbh_inc.php";
$categories;
// $formattedCategories;

if(!isset($_SESSION["categories"])){
    $_SESSION["NrOfRows"] = 0;
    $_SESSION["categories"] = array();
}
if(sizeof($_SESSION["categories"]) == 0){
    $time = 0;
    if(!$time >= 1){
        $query = $dbh->prepare("
            SELECT *
            FROM Rubriek
        ");

        $query->execute();
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($categories as $index => $category) {
            if($category['rubriek'] == null) {
                $category['sub'] = getChildCategoriesCat($categories, $category);
                $formattedCategories[] = $category;
            }
        }
        $time ++;
    }    
}else{
    if(sizeof($_SESSION["categories"]) < $_SESSION["NrOfRows"]){
        foreach($_SESSION["categories"] as $index => $category) {
            $level = 1;    
            if($category['rubriek'] == null || $category['rubriek'] == -1) {
                $category['sub'] = getChildCategories($_SESSION["categories"], $category, $level);
                // $formattedCategories[] = $category;
                $_SESSION["categories"][] = $category;
            }
        }
    }
}

function getChildCategoriesCat($categories, $selectedCategory) {
    $subcategories = [];
    $i = 0;
    foreach($categories as $index => $category) {
        if(in_array($selectedCategory['rubrieknummer'], $category) && $category['rubrieknummer'] != $selectedCategory['rubrieknummer']) {
            $category['sub'] = getChildCategoriesCat($categories, $category);
            $subcategories[$i] = $category;
            $i++;
        }
    }
    return $subcategories;
}
// creates select with every category and subs, loads from DB if not set.
echo '  
    <select class="borderless-input" id="categorie_auction"  name="categorie-auction" required>
        <option disabled selected> Kies je categorie:</option>
';
if(isset($_SESSION["categories"])){

    foreach($_SESSION["categories"] as $index => $category) { 
        if(count($category['sub']) > 0) {
            echo '<optgroup label="' . $category['rubrieknaam'] . '" value="" ><ul id="categorieen-dropdown-top' . $category['rubrieknummer'] .'" caller="dropdown-category-' . $category['rubrieknummer']. '" class="dropdown-content">';
            echoListItemsCat($category['sub']);
            echo '</ul></optgroup>';
        } else {
            echo '<option value="'.$category['rubrieknummer'].'"><li><a class="black-text" href="/php/categorieen.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li></option>';
        }
    }
}
 echo '</select>';

 function echoListItemsCat($formattedCategories) {
    foreach($formattedCategories as $category) {
        if(count($category['sub']) > 0) {
            echo '<optgroup label="' . $category['rubrieknaam'] . '"><ul id="categorieen-dropdown-top' . $category['rubrieknummer'] .'" caller="dropdown-category-' . $category['rubrieknummer']. '" class="dropdown-content">';
            echoListItemsCat($category['sub']);
            echo '</ul></optgroup>';
        } else {
            echo '<option value="'.$category['rubrieknummer'].'"><li><a class="black-text" href="/php/categorieen.php?c=' . $category['rubrieknummer'] .'">' . $category['rubrieknaam'] . '</a></li></option>';
        }
    }
}
?>