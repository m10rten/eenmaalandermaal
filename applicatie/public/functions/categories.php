<?php
// start the session so every category and sub can be saved in an array.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$realPath = $_SERVER['DOCUMENT_ROOT'];;
require "$realPath/requirers/dbh_inc.php";
$categories;

if(!isset($_SESSION["categories"])){
    $_SESSION["NrOfRows"] = 0;
    $_SESSION["categories"] = array();
}

if(sizeof($_SESSION["categories"]) == 0){
    // prevents from doing it more then 1 time
   $time = 0;
    if(!$time >= 1){
        $query = $dbh->prepare("
            SELECT *
            FROM Rubriek
            WHERE rubrieknaam != 'Root'
        ");

        $query->execute();
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($categories as $index => $category) {
            $level = 1;    
            if($category['rubriek'] == null || $category['rubriek'] == -1) {
                $category['sub'] = getChildCategories($categories, $category, $level);
                $_SESSION["categories"][] = $category;
                $_SESSION["NrOfRows"]++;
            }
        }
        $time ++;
    }
    
}else {
// checks if all the rows are retrieved in the last fetch, if the session is bigger(22 - 0) it fetches all again.
    if(sizeof($_SESSION["categories"]) < $_SESSION["NrOfRows"]){
        foreach($_SESSION["categories"] as $index => $category) {
            $level = 1;    
            if($category['rubriek'] == null || $category['rubriek'] == -1) {
                $category['sub'] = getChildCategories($_SESSION["categories"], $category, $level);
                $_SESSION["categories"][] = $category;
            }
        }
    }    
}
// get the child categories from the previous category
function getChildCategories($categories, $selectedCategory, $level) {
    $subcategories = [];
    $i = 0;
    $level++;

    if($level <= 3) {
        foreach($categories as $index => $category) {
            if(in_array($selectedCategory['rubrieknummer'], $category) && $category['rubrieknummer'] != $selectedCategory['rubrieknummer']) {
                $selectedCategory['subrubrieken'][] = $category['rubrieknummer'];
                $category['sub'] = getChildCategories($categories, $category, $level);
                $subcategories[$i] = $category;
                $i++;
            }
        }
    }

    return $subcategories;

}


function getPopularMainCategories($dbh) {
    // save every top category in a session array, to save time and load times, refreshes when the user logges out
    $_SESSION["topCategories"] = array();
    $query = $dbh->prepare("SELECT TOP 10 Rubriek.*, COUNT(Bod.voorwerp) AS biedingen
        FROM Rubriek
        FULL OUTER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.rubriek = rubriek.rubrieknummer
        LEFT JOIN Bod ON Bod.voorwerp = VoorwerpInRubriek.voorwerp
        WHERE Rubriek.rubriek IN (SELECT rubrieknummer FROM rubriek WHERE rubriek IS NULL)
        GROUP BY Rubriek.rubrieknummer, Rubriek.rubrieknaam, Rubriek.rubriek, Rubriek.rubriekpad
        ORDER BY biedingen DESC
    ");

    $query->execute();
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
    // fills the session array with the top so it takes less time to reload.
    $_SESSION["topCategories"] = $categories;
    return $categories;

}
// function to draw the card you see on the index.php
function categoryCard($categoryNr, $categoryName) {
    echo '<div class="col s6 l4 xl3 padding-populair category-populair-card">
    <a href="./php/rubrieken.php?c='.$categoryNr.'" class=" z-depth-1 rounded category truncate">' . $categoryName . '</a>
    </div>';
}
