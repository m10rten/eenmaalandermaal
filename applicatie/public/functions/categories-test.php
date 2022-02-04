<?php
$realPath = $_SERVER['DOCUMENT_ROOT'];;
require "$realPath/requirers/dbh_inc.php";
$categories;
$formattedCategories = array();

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
        $formattedCategories[] = $category;
    }
}



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
    //Insert rubriekpad like 1,2,3,4, to prevent to search for '2' and also get results with a rubriekpad including 12
    //TODO: op biedingen selecteren
    $query = $dbh->prepare("SELECT TOP 10 Rubriek.*, COUNT(Bod.voorwerp) AS biedingen
        FROM Rubriek LEFT JOIN Rubriek AS SubRubriek ON SubRubriek.rubriekpad 
        LIKE '%' + CAST(Rubriek.rubrieknummer AS varchar) + '%'
        FULL OUTER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.rubriek = SubRubriek.rubrieknummer
        LEFT JOIN Bod ON Bod.voorwerp = VoorwerpInRubriek.voorwerp
        WHERE Rubriek.rubriek IN (SELECT rubrieknummer FROM rubriek WHERE rubriek IS NULL)
        GROUP BY Rubriek.rubrieknummer, Rubriek.rubrieknaam, Rubriek.rubriek, Rubriek.rubriekpad
        ORDER BY biedingen DESC
    ");

    $query->execute();
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
    return $categories;

}

function categoryCard($categoryNr, $categoryName) {
    echo '<div class="col s6 m6 l3 padding-populair category-populair-card">
    <a href="./php/rubrieken.php?c='.$categoryNr.'" class=" z-depth-1 rounded category">' . $categoryName . '</a>
    </div>';
}
