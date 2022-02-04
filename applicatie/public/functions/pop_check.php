<?php
// $popup='';
if(isset($_GET["pop"]) && $_GET["pop"] !== ''){
    $getpop = $_GET["pop"];

    $popups = [
        'mail sent' => 'Er is een email verzonden naar jouw emailadres.',
        'wrong login' => 'De inlog gegevens zijn onjuist, probeer opnieuw.',
        'nice try' => 'Goed geprobeerd! laat het ons weten als je wel iets vind!',
        'login succes' => 'Je bent nu ingelogd!',
        'logout' => 'Je bent nu uitgelogd.',
        'passwords dont match' => 'De gegevens komen niet overeen.',
        'mail not sent' => 'Oops... de mail is niet verzonden, probeer het later opnieuw',
        'not valid housenumber'=> 'Dit huisnummer is niet geldig, *alleen nummers*',
        'not valid email' => 'Dit email adres is niet geldig, voer een geldig adres in.',
        'mail in use' => 'Dit email adres word al gebruikt.',
        'username in use' => 'Deze gebruikersnaam is al gekozen.',
        'register succes' => 'Je staat nu geregistreerd, log in:',
        'recaptcha unchecked' => 'Selecteer de recaptcha om verder te gaan',
        'oversized files' => 'De gekozen bestanden zijn samen meer dan 50MB!',
        'upload error' => 'Er was een error tijdens het uploaden van de bestanden, probeer het later opnieuw.',
        'select image' => 'Selecteer een geschikt formaat: toegestaan: jpg, png en jpeg.',
        'upload succes' => 'De bestanden zijn succesvol geupload naar de server.',
        'empty input' => 'Vul alle verplichte velden in met een sterretje *.',
        'empty input special' => 'Vul het veld in.',
        'activation succes' => 'Het activeren van het verkoop account is gelukt.',
        'not verified mail' => 'Bevestig eerst uw email voordat je dit kan doen.',
        'wrong creditcard number' => 'Het opgegeven creditcardnummer is onjuist.',
        'invalid bid' => 'Je bod is te laag, het bod moet hoger zijn dan de hoogste bieder',
        'increase bid' => 'Je bod is te laag, zorg dat je bod nog hoger is met de vereiste verhoging ',
        'same seller' => 'Het lijkt er op dat jij deze verkoper bent.',
        'same user' => 'Jij hebt al als laatste geboden, wacht totdat iemand je overbied.',
        'bid placed' => 'Je bod is geplaatst',
        'wait a minute' => 'Je moet even wachten voordat je een volgend bod kan plaatsen.',
        'server error bid' => 'Er was een error op bij de server, probeer het later opnieuw.',
        'user nonactive' => 'De gebruiker staat op non actief.',
        'user active' => 'De gebruiker staat op actief.',
        'nonactive failed' => 'De status van de gebruiker aanpassen is mislukt.',
        'account blocked' => 'Uw account is geblokkeerd.',
        'auction status changed' => 'De status van de veiling is aangepast.',
        'review placed' => 'Je review is met succes geplaatst.',
        'already reviewed' => 'Het lijkt er op dat je al een review voor dit voorwerp hebt gemaakt.',
        'no review found' => 'Er is geen review gevonden voor dit nummer.',
        'review unblocked' => 'De review is niet meer geblokkeerd',
        'review blocked' => 'Deze review is succesvol geblokt.',
        'user not found' => 'Er is geen gebruiker gevonden.',
        'incorrect phonenumber' => 'Zorg dat het telefoon nummer geen letters bevat.',
        'auction closed' => 'De veiling waar je op wilde bieden is helaas al gesloten',
        'none numeric found' => 'Het lijkt er op dat je startprijs of verzendkosten niet een getal zijn, wil je iets gratis aanbieden of laten verzenden, zet dan 0 neer.'
    ];


    foreach($popups as $index => $value){
         if($getpop == $index){
            $key = array_search($value, $popups); //geeft index nummer uit de popups array
            echo '
            <div class="row" id="popup">
                <div class="col s10 offset-s1 m8 offset-m2 grey lighten-3 rounded padding-populair" >
                    <div class="center popup-box ">            
                        <h6>
                            <u class="col s10 text-bold ">'.$value.'</u>
                        </h6>
                        <div class="col s2">
                            <i onclick="closePopup()" class="material-icons center close-popup">close</i>
                        </div>
                    </div>
                </div>
            </div>
            ';
            //$popup = $popupValue[$key]; 
         }
    }    
}