<!-- Card -->
<?php
function card($type = 'default', $title, $bid, $date, $id, $src, $genre, $auctionnumber = null, $isActive = false, $myBid = null) {
    echo'
            <div class="card card-radius hoverable">
                <div class="card-image">
                    <a href="../php/veiling.php?v='.$id.'" class="">
                        <img src="'.$src.'" class="card-image-radius">
                    </a>
                </div>
                <div class="card-content">
                    <div class="row margin-bottom-0 ">  
                        <p class="col s10 text-bold title-preview-auction truncate" title="'.$title.'"><a class="black-text" href="../php/veiling.php?v='.$id.'">'.$title.'</a> </p> 
                        <a href="#favoriet" ><h6 class="col s2 material-icons grey-text hoverable-heart">favorite</h6></a>
                    </div>
                    <h7 class="grey-text genre-preview-auction" title="'.$genre.'">'.$genre.'</h7>
                    <div class="row margin-bottom-0">
                    '; if($bid !== null){
                        echo '<h7 class="col s12 center">Huidige bod: €'.$bid.'</h7>
                    ';
                    }else {
                        echo '<h7 class="col s12 center">nog geen bod</h7>';
                    }                        
                        // if myBid isset then display my bid
                        if($myBid !== null){
                        echo '<h7 class="col s12 center">Mijn bod: €'.$myBid.'</h7>';
                        }
                        echo '
                        <hr class="col s12 center">
                        <h7 class="col s12 center grey-text">tot: '.$date.'</h7>
                    </div>
    ';
            if($type == 'admin') {
                $buttonClass = $isActive ? "red" : "green";
                    echo '
                    <form action="../includes/block.php" method="post" autocomplete="off">
                        <div class="row margin-bottom-0 center">
                            <input hidden type="text" name="auction-block-number" value="' . $auctionnumber . '">
                            <input hidden type="text" name="auction-block-status" value="' . $isActive . '">
                            <button role="button" class="admin-margin col btn waves-effect ' . $buttonClass . ' rounded s10 offset-s1 add-margin-bottom" type="submit" name="submit-auction-block">';
                                if($isActive) {
                                    echo 'blokkeren <i class="material-icons left">block</i>';
                                } else {
                                    echo 'deblokkeren <i class="material-icons left">check</i>';
                                }
                echo '      
                            </button>
                        </div>
                    </form>
                    ';
            }
                    echo '
                </div>
            </div>
                    ';
             
        }
// NOG TYPE AANMAKEN VOOR EIGEN VEILING DAT JE DIE KAN AANPASSEN //
?>
