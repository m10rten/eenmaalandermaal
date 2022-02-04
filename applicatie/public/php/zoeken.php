<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    <link rel="stylesheet" href="../css/zoeken.css">
    <title>EenmaalAndermaal | Zoeken</title>

    <!--header php-->
<?php 
    include '../includes/header_inc.php';
?>
    <main>
    <?php 
        include '../includes/functies_inc.php';
        showBreadcrumbs("index","zoeken.php","");
        include '../functions/pop_check.php';
        ?>
        <div class="container">
            <div class="row">
                <!-- titel -->
                <div class="col s12">
                    <h2 class="center margin-top-0">
                        Zoeken
                    </h2>
                </div>
                <!-- zoekveld -->
                <div class="col s8 offset-s2">
                    <form action="./zoeken.php" method="get" autocomplete="off">
                        <div class="input-field">
                            <input class="center" id="search" type="search" name="q" placeholder="zoeken.." required>
                            <i class="material-icons">close</i>
                        </div>
                    </form>
                </div>
                
            </div>
            <!-- zoekresultaten -->
            <div class="row">
                 <div class="col s12">
                    <h5 class="center">zoekresultaten voor: 
                        <b>
                            <?php if(!empty($_GET["q"])){echo htmlspecialchars($_GET["q"]);}  ?>
                        </b> </h5>
                </div>
                <div class="row" id="searchResults">               
                <?php
                    include '../includes/zoeken_inc.php';
                ?>
                 </div>
                 <?php    
                // shows a: 'laad meer' button when there has been searched on a string not empty    
                    if(isset($_GET['q']) && !empty($_GET['q']) && $_GET['q'] !== ""){
                            echo '
                        <div class="col s12 load-more">
                            <button class="btn rounded yellow text-bold admin-margin" id="loadMoreSearch">
                                laad meer
                            </button>
                        </div>
                        ';
                    }                                  
                ?>              
            </div>
        </div>
    </main>

    <!--footer php -->    
<?php 
    include '../includes/footer_inc.php';
?>
<script>
        $(document).ready(function(){
            var items = 25;
            var search = '<?php                          
                if(isset($_GET['q']) && !empty($_GET['q']) && $_GET['q'] !== ""){
                    echo $_GET["q"];
                }                                  
                ?>';
            $("#loadMoreSearch").click(function(){
                items = items + 20;
                $("#searchResults").load("../includes/load-items_inc.php", {
                    newitemCount: items,
                    search_query: search
                });
            });
        });
    </script>
</body>
</html>