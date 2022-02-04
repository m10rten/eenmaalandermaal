<?php
function developerCard($name, $text, $imgSource) {
    echo <<<EOL
    <div class="col s12 l6">
        <div class="row">
            <div class="col s12 container-1">
                <img class="card-image developer-card-image margin-right-20" src="$imgSource" alt="$imgSource">
                <div class="container-2">
                    <h5 class="text-bold left-align">$name</h5>
                    <p class= "left-align">$text</p>
                </div>
            </div>
        </div>
    </div>
    EOL;
}
?>