<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Voorwaarden</title>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","rubrieken.php","");
        include '../functions/pop_check.php';
        ?>
        <div class="container">
            <h1 class="center">Algemene Voorwaarden</h1>
        <div class="center">
           <p class="center">Benieuwd naar de algemene voorwaarden?</p>
           <a class="center" href="/files/AlgemeneVoorwaardenEenmaalAndermaal.pdf" target="_blank">Klik op hier om de algemene voorwaarden te openen</a>
        </div>
        </div>
    </main>
    <!--footer php -->

<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>