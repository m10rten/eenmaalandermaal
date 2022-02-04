# EenmaalAndermaal

ICT-propedeuse project groep 12 (20/21)


## Installatie

* Haal het project binnen vanaf de master.
* Start Docker op.
* Voer in SQL Management Studio het database script uit, die onder de readme staat.
* Open met de editor of choice de docker containter van eenmaalandermaal
* Op localhost:8000 is EenmaalAndermaal te zien.


## Hoe werkt EenmaalAndermaal

##### Homepagina

* De top veilingen worden uit de database gehaald (op basis van de hoeveelheid boden op de advertentie).
* Onder de veilingen staan de populaire rubrieken (op basis van de totale hoeveelheid boden in een rubriek).

##### Rubriekenpagina

###### /php/rubrieken.php?c=1

* Er kan door de rubrieken heen geklikt worden totdat de gebruiker bij de laatste rubriek is en worden er advertenties laten zien.
* Onder de veilingen staan de populaire rubrieken (op basis van de totale hoeveelheid boden in een rubriek)

##### Loginpagina

###### /php/login.php

* De gebruiker kan hier inloggen en om inlogpogingen van bots te voorkomen moet de recaptcha ingevuld worden.
* Het Wachtwoord kan zichtbaar worden gemaakt door op het oogje te klikken.

##### Registratiepagina

###### /php/registreren.php

* De gebruiker kan hier registreren en om registratiepogingen van bots te voorkomen moet de recaptcha wederom ingevuld worden.
* Alle velden zijn verplicht bij het registreren, aangegeven met een sterretje bij het label.

##### Veilingpagina

###### /php/veiling.php?v=1

* Op de pagina worden de details van de veiling weergegeven en afbeeldingen van het geveilde object.
* Op de veiling kan worden geboden met een bedrag hoger dan het huidige hoogste bod.
* Er is ook een optie om snel te bieden, gebasseerd op het huidige hoogste bod.

##### Plaatsenpagina

###### /php/plaatsen.php

**Je moet ingelogd en verkoper zijn om een veiling te kunnen plaatsen/maken**

* Alle velden zijn verplicht bij het plaatsen, aangegeven met een sterretje bij het label.
* Bij het plaatsen kan een afbeelding worden geupload door op het input veld te klikken
* Door op het plusje te klikken kan er nog een afbeelding worden toegevoegd.
* Bij de locatie wordt verwacht dat de Gebruiker eerst de stad neerzet met daarachter een komma met het land erachter (bijvoorbeeld "Ruitenberglaan 26 Arnhem, Nederland")

##### Mijnaccountpagina

###### /php/mijnaccount.php

**Je moet ingelogd zijn om een je account te kunnen bekijken**

* Op de pagina zijn drie tabjes met de onderwerpen: mijn veilingen, mijn favorieten en mijn biedingen.
* Op het desbetreffende tabje worden de gegevens van de persoon die is ingelogd weergegeven.
* De zoekfunctionaliteit kan gebruikt worden om snel een veiling te vinden, voor het geselecteerde tabje.

##### Beherenpagina

###### /php/beheren.php

**Je moet ingelogd zijn als beheerder om veilingen of gebruikers te (de)blokkeren**

* Op de pagina zijn twee tabjes met de onderwerpen: veilingen en gebruikers.
* Op het desbetreffende tabje worden de gegevens weergegeven.
* De zoekfunctionaliteit kan gebruikt worden om snel een veiling of gebruiker te vinden.


##### Op elke pagina aanwezig

###### Header

* De header is dynamisch, bijvoorbeeld wanneer er ingelogd is door een gebruiker, verschijnen er extra opties voor de gebruiker.
* Het onderdeel rubrieken is een dropdown met alle rubrieken, met weer een dropdown als een rubriek subrubrieken bevat.
* De subrubrieken worden weergegeven als de de muis op een rubriek staat met subrubrieken.
* Door op een rubriek te klikken ga je naar de rubrieken pagina. Daar worden dan de subrubrieken weergegeven of de veilingen binnen dat rubriek.

###### Footer

* De footer heeft een sectie waar er ingeschreven kan worden op de nieuwsbrief van EenmaalAndermaal.
* Daarnaast staan er wat basisinformatie over de website en handige linkjes vermeld.

###### Breadcrumbs

* Onder de header staan de breadcrumbs om snel te navigeren door de website.
* Hierdoor krijgt de gebruiker een overzicht waar hij zich bevind op de website.
* De breadcrumbs zijn klikbaar en brengt je naar de pagina waar op geklikt is.

## Overnemen EenmaalAndermaal

* Haal de laatste versie op van de master branch.
* Haal de laatste versie van het database script op, onder de readme.
* Regel een domeinnaam voor de website (Het adres van de website).
* Regel een hosting service of een eigen webserver (om de site online te houden).
* Upload de master versie en database naar de webserver of hosting service.







