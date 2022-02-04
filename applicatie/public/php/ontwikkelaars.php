<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    
    <title>EenmaalAndermaal | ontwikkelaars</title>
    <link href="../css/ontwikkelaars.css" rel="stylesheet" />
    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        include '../includes/ontwikkelaar_inc.php';
        showBreadcrumbs("index","ontwikkelaars.php","");
        include '../functions/pop_check.php';
        ?>
        <div class="row center">
            <!-- Ontwikkelaars titel -->
            <div class="container">
                <h1 class="center margin-top-0 text-bold categories-section">
                    Ontwikkelaars
                </h1>
                <div class="col s12">
                    <div class="row">
                        <?php
                            developerCard('Ties Peters', 'Ik ontwikkel graag applicaties die een verschil maakt voor de gebruiker!', 'https://static.nieuwsblad.be/Assets/Images_Upload/2020/02/10/ba18ffdc-4bff-11ea-99ec-ff66d24792fb_web_scale_0.6349207_0.6349207__.jpg?maxheight=460&maxwidth=638&scale=both');
                            developerCard('Martijn Staal', 'Ik ontwikkel graag applicaties die een verschil maakt voor de gebruiker!', 'https://static.nieuwsblad.be/Assets/Images_Upload/2020/02/10/ba18ffdc-4bff-11ea-99ec-ff66d24792fb_web_scale_0.6349207_0.6349207__.jpg?maxheight=460&maxwidth=638&scale=both');
                            developerCard('Maarten van der Lei', 'Ik ontwikkel graag applicaties die een verschil maakt voor de gebruiker!', 'https://static.nieuwsblad.be/Assets/Images_Upload/2020/02/10/ba18ffdc-4bff-11ea-99ec-ff66d24792fb_web_scale_0.6349207_0.6349207__.jpg?maxheight=460&maxwidth=638&scale=both');
                            developerCard('Martijn de Vries', 'Ik ontwikkel graag applicaties die een verschil maakt voor de gebruiker!', 'https://static.nieuwsblad.be/Assets/Images_Upload/2020/02/10/ba18ffdc-4bff-11ea-99ec-ff66d24792fb_web_scale_0.6349207_0.6349207__.jpg?maxheight=460&maxwidth=638&scale=both');
                            developerCard('Pieter Bikkel', 'Ik ontwikkel graag applicaties die een verschil maakt voor de gebruiker!', 'https://static.nieuwsblad.be/Assets/Images_Upload/2020/02/10/ba18ffdc-4bff-11ea-99ec-ff66d24792fb_web_scale_0.6349207_0.6349207__.jpg?maxheight=460&maxwidth=638&scale=both');

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

</body>
</html>