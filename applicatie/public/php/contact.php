<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | Contact</title>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","contact.php","");
        include '../functions/pop_check.php';
        ?>
        <div class="container">
            <div class="row z-depth-1 rounded padding-populair">
                <div class="col s12">
                    <h2 class="center margin-top-0">
                        Contact
                    </h2>
                </div>
                <div class="col s12 l4">
                    <div class="col s12 padding-contact">
                    <hr class="col s12 card center hide-on-large-only">
                        <h3>Bel ons</h3>
                        <p>Technische ondersteuning is beschikbaar van 9 tot 5.</p>
                        <p>Bel ons gerust!</p>
                        <i class="material-icons left ">phone</i><u><a class="black-text" href="tel:0612345678">0612345678</u></a>
                    </div>
                </div>
 
                <div class="col s12 l4 ">
                    <div class="col s12 padding-contact">
                    <hr class="col s12 card center hide-on-large-only">
                        <h3>E-mail ons</h3>
                        <p>Voor algemene vragen kunt u ons via de mail bereiken.</p>
                        <i class="material-icons left ">mail</i><u><a class="black-text" href="mailto:iproject12@han.nl">iproject12@han.nl</a></u>
                    </div>
                </div>
 
                <div class="col s12 l4 ">
                    <div class="col s12 padding-contact">
                    <hr class="col s12 card center hide-on-large-only">
                        <h3>Bezoek ons</h3>
                        <p>Adres:</p>
                        <i class="material-icons left ">place</i>Maps: <u><a class="black-text" target="_blank" href="https://www.google.nl/maps/search/Ruitenberglaan-26">Ruitenberglaan 26</a></u>
                    </div>
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