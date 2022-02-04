<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | over-ons</title>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","over-ons.php","");
        include '../functions/pop_check.php';
        ?>
        <div class="container">
            <div class="row">
                <div class="col s12 center">
                    <h1>over ons</h1>
                </div>
                <div class="col s12">
                    <img class="col s12 materialboxed" width="650" src="../media/E-bay.jpg" alt="EenmaalAndermaal">
                    <p class="col s12 center">EenmaalAndermaal is ooit opgericht door een aantal studenten die een projectopdracht kregen van de school om een veilingswebsite te gaan maken die beter denkt aan het milieu. Dit bleek uiteindelijk zo’n groot succes te zijn dat het uitgebreid is tot een van de grootste veilingsites in Nederland. Dit doel willen wij gaan bereiken door onze servers op groene energie te laten draaien. Bij het bezorgen van een veilingartikel kan alleen een bezorgservice worden gekozen die verantwoord met het milieu omgaat.</p>
                </div>
            </div>
        </div>


    </main>
    <!--footer php -->

<?php 
    include '../includes/footer_inc.php';
?>

</body>
</html>