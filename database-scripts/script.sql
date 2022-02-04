/* Create database EenmaalAndermaal */


/* Drop all tables for database reset */
DROP TABLE IF EXISTS VoorwerpInRubriek;
DROP TABLE IF EXISTS Rubriek;
DROP TABLE IF EXISTS GebruikersTelefoon;
DROP TABLE IF EXISTS Feedback;
DROP TABLE IF EXISTS Bod;
DROP TABLE IF EXISTS Bestand;
DROP TABLE IF EXISTS BestandRubriek
DROP TABLE IF EXISTS Voorwerp;
DROP TABLE IF EXISTS Verkoper;
DROP TABLE IF EXISTS Gebruiker_Tokens;
DROP TABLE IF EXISTS Gebruiker;
DROP TABLE IF EXISTS Vraag;
DROP TABLE IF EXISTS verkoperReview;
DROP TABLE IF EXISTS berichten
DROP TABLE IF EXISTS chats


/*Creates 'Vraag' table */
CREATE TABLE Vraag (
	vraagnummer integer IDENTITY(1,1),
	vraag varchar(256),
	CONSTRAINT PK_Vraag PRIMARY KEY (vraagnummer)
);

/*Creates 'Gebruiker' table */
CREATE TABLE Gebruiker (
	gebruikersnaam varchar(20),
	voornaam varchar(15),
	achternaam varchar(15),
	adresregel1 varchar(25),
	adresregel2 varchar(25) NULL,
	postcode varchar(10),
	plaatsnaam varchar(17),
	land varchar(64),
	geboortedag date,
	mailbox varchar(38),
	wachtwoord varchar(max),
	vraag integer,
	antwoordtekst varchar(26),
	verkoper bit,
	geverifieerd bit,
	is_beheerder bit DEFAULT 0,
	is_geblokkeerd bit,
	CONSTRAINT PK_Gebruiker PRIMARY KEY (gebruikersnaam),
	FOREIGN KEY (vraag) REFERENCES Vraag(vraagnummer),
);


/*Creates 'Gebruiker_Tokens' table */
CREATE TABLE Gebruiker_Tokens (
	gebruiker varchar(20),
	token varchar(80),
	token_type varchar(30), /* Email verificatie of wachtwoord vergeten */
	verloopt_op date,
	gebruikt bit,
	CONSTRAINT PK_Gebruiker_Token PRIMARY KEY (gebruiker, token),
	FOREIGN KEY (gebruiker) REFERENCES Gebruiker(gebruikersnaam),
);


/*Creates 'Verkoper' table */
CREATE TABLE Verkoper (
	gebruiker varchar(20),
	bank varchar(8) NULL,
	bankrekening varchar(20) NULL,
	controleoptienaam varchar(10),
	creditcardnummer varchar(19) NULL,
	isActief bit DEFAULT 1,
	CONSTRAINT PK_Verkoper PRIMARY KEY (gebruiker),
	FOREIGN KEY (gebruiker) REFERENCES Gebruiker(gebruikersnaam),
	CONSTRAINT CHK_Verkoper CHECK (dbo.isVerkoper(gebruiker) = 1),
	CONSTRAINT CHK_Creditcard CHECK ((controleoptienaam = 'creditcard' AND creditcardnummer IS NOT NULL) OR (controleoptienaam != 'creditcard' AND creditcardnummer IS NULL)),
	CONSTRAINT CHK_PaymentInfo CHECK (bankrekening IS NOT NULL OR creditcardnummer IS NOT NULL),
);

/*Creates 'Voorwerp' table */
CREATE TABLE Voorwerp (
	voorwerpnummer numeric(10) IDENTITY(1,1),
	titel varchar(128),
	beschrijving varchar(max),
	startprijs float(5),
	betalingswijze varchar(23),
	betalingsinstructie varchar(23) NULL,
	plaatsnaam varchar(12),
	land varchar(64),
	looptijd integer,
	looptijdBeginDag varchar(10),
	looptijdBeginTijdstip varchar(8),
	verzendkosten float(5) NULL,
	verzendinstructies varchar(23) NULL,
	verkoper varchar(20),
	koper varchar(20) NULL,
	looptijdEindeDag varchar(10),
	looptijdEindeTijdstip varchar(8),
	veilingGesloten bit,
	isActief bit DEFAULT 1,
	verkoopprijs float(5) NULL,
	CONSTRAINT PK_Voorwerp PRIMARY KEY (voorwerpnummer),
	FOREIGN KEY (verkoper) REFERENCES Verkoper(gebruiker) ON DELETE SET NULL,
	FOREIGN KEY (koper) REFERENCES Gebruiker(gebruikersnaam) ON DELETE SET NULL,
);


/*Creates 'Bestand' table */
CREATE TABLE Bestand (
    filenaam varchar(256),
    voorwerp numeric(10),
	CONSTRAINT PK_Bestand PRIMARY KEY (filenaam),
	FOREIGN KEY (voorwerp) REFERENCES Voorwerp(voorwerpnummer) ON DELETE CASCADE,
	CONSTRAINT CHK_Afbeeldingen CHECK (dbo.aantalAfbeeldingen(voorwerp) <= 4),
);

CREATE TABLE bestandRubriek(
	filenaam varchar(256),
	rubriek numeric(10)
);

CREATE TABLE verkoperReview(
	reviewnummer numeric(10) IDENTITY(1,1),
	verkoper varchar(20),
	reviewer varchar(20),
	beoordeling float (10),
	beschrijving varchar(max)
	CONSTRAINT PK_verkoperReview PRIMARY KEY (reviewnummer)
);


/*Creates 'Bod' table */
CREATE TABLE Bod (
	voorwerp numeric(10),
	bodBedrag float(5),
	gebruiker varchar(20),
	bodDag varchar(10),
	bodTijdstip varchar(8),
	CONSTRAINT PK_Bod PRIMARY KEY (voorwerp,bodBedrag),
	CONSTRAINT UC_Bod1 UNIQUE (voorwerp, bodDag, bodTijdstip),
	CONSTRAINT UC_Bod2 UNIQUE (gebruiker, bodDag, bodTijdstip),
	FOREIGN KEY (voorwerp) REFERENCES Voorwerp(voorwerpnummer) ON DELETE CASCADE,
	FOREIGN KEY (gebruiker) REFERENCES Gebruiker(gebruikersnaam) ON DELETE SET NULL,
	CONSTRAINT CHK_Gebruiker CHECK (dbo.isEigenaarVoorwerp(voorwerp, gebruiker) = 0),
	CONSTRAINT CHK_Bod CHECK (dbo.isHogerBod(voorwerp) = null OR dbo.isHogerBod(voorwerp) < bodBedrag),
);

/*Creates 'Feedback' table */
CREATE TABLE Feedback (
	reviewnummer numeric(10) IDENTITY(1,1),
	reviewer varchar(20),
	voorwerp numeric(10),	
	beoordeling float (10),	
	verkoper varchar(20),
	soortGebruiker varchar(8),
	feedbackSoort varchar(8),
	dag varchar(10),
	tijdstip varchar(8),
	beschrijving varchar(MAX) NULL,
	isGeblokkeerd bit NULL,
	CONSTRAINT PK_Feedback PRIMARY KEY (voorwerp,soortGebruiker),
	FOREIGN KEY (voorwerp) REFERENCES Voorwerp(voorwerpnummer),
);

/*Creates 'GebruikersTelefoon' table */
CREATE TABLE GebruikersTelefoon (
	volgnr integer IDENTITY(1,1),
	gebruiker varchar(20),
	telefoon varchar(11),
	CONSTRAINT PK_GebruikersTelefoon PRIMARY KEY (volgnr,gebruiker),
	FOREIGN KEY (gebruiker) REFERENCES Gebruiker(gebruikersnaam) ON DELETE CASCADE,
);

/*Creates 'Rubriek' table */
CREATE TABLE Rubriek (
	rubrieknummer integer IDENTITY(1,1),
	rubrieknaam varchar(24),
	rubriek integer NULL,
	rubriekpad varchar(20),
	CONSTRAINT PK_Rubriek PRIMARY KEY (rubrieknummer),
	FOREIGN KEY (rubriek) REFERENCES Rubriek(rubrieknummer),
);

/*Creates 'VoorwerpInRubriek' table */
CREATE TABLE VoorwerpInRubriek (
	voorwerp numeric(10),
	rubriek integer,
	CONSTRAINT PK_VoorwerpInRubriek PRIMARY KEY (voorwerp, rubriek),
	FOREIGN KEY (voorwerp) REFERENCES Voorwerp(voorwerpnummer) ON DELETE CASCADE,
	FOREIGN KEY (rubriek) REFERENCES Rubriek(rubrieknummer) ON DELETE CASCADE,
);

CREATE TABLE Chats (
	chatId numeric IDENTITY(1,1),
	gebruiker1 varchar(20) NOT NULL,
	gebruiker2 varchar(20) NOT NULL,
	CONSTRAINT PK_Chat PRIMARY KEY (chatId)
);
CREATE TABLE Berichten (
	berichtNummer numeric IDENTITY(1,1),
	chatId numeric NOT NULL,
	verzender varchar(20) NOT NULL,
	ontvanger varchar(20) NOT NULL,
	dag varchar(10),
	tijdstip varchar(8),
	bericht varchar(MAX),
	isVerwijderd bit NULL,
	FOREIGN KEY (chatid) REFERENCES Chats(chatId),
	CONSTRAINT PK_BerichtNr PRIMARY KEY (berichtNummer)
);


/* Testdata */

/* Testdata: Rubriek */

/* niveau 1 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Voertuigen', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Kunst', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Speelgoed', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Boeken', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Elektronica', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Kleding', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Meubels', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Muziek', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Tuin en Terras', null, '');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Sport', null, '');



/* Voertuigen - niveau 2 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Auto''s', 1, '1');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Motoren', 1, '1');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Boten', 1, '1');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Fietsen', 1, '1');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Brommobielen', 1, '1');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Vliegtuigen', 1, '1');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Speelgoed autos', 1, '1');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Overig', 1, '1');

/* Kunst - niveau 2 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Schilderijen', 2, '2');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Beeldhouwkunst', 2, '2');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Fotografie', 2, '2');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Filmkunst', 2, '2');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Grafiek', 2, '2');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Tekenkunst', 2, '2');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Moderne media', 2, '2');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Overig', 2, '2');

/* Electronica - niveau 2 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Telefoon', 5, '5');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Tv', 5, '5');

/* Speelgoed - niveau 2 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Playmobil', 3, '3');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Lego', 3, '3');


/* Kunst -> Schilderijen niveau 3 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Landschap', 19, '2 19');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Portret', 19, '2 19');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Stilleven', 19, '2 19');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Stadsgezicht', 19, '2 19');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Abstract', 19, '2 19');

/* Kunst -> Beeldhouwkunst niveau 3 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Plastiek', 20, '2 20');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Ruimtelijk', 20, '2 20');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Sculptuur', 20, '2 20');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Standbeeld', 20, '2 20');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Beton', 20, '2 20');

/* Voertuigen -> Auto's - niveau 3 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Pickup', 11, '1 11');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Stationwagen', 11, '1 11');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Elektrische auto', 11, '1 11');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Sportauto', 11, '1 11');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Oldtimer', 11, '1 11');

/* Voertuigen -> TV - Motoren */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Chopper', 12, '1 12');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Racemotor', 12, '1 12');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Tourmotor', 12, '1 12');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Wegmotor', 12, '1 12');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Crossmotor', 12, '1 12');

/* Voertuigen -> TV - Boten */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Roeiboot', 13, '1 13');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Jacht', 13, '1 13');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Speedboot', 13, '1 13');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Waterfiets', 13, '1 13');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Kano', 13, '1 13');

/* Voertuigen -> TV - Fietsen */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Ligfiets', 14, '1 14');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Fiets', 14, '1 14');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Elektrische fiets', 14, '1 14');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Bakfiets', 14, '1 14');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Eenwieler', 14, '1 14');

/* Voertuigen -> TV - Brommobielen */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Scooters', 15, '1 15');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Brommers', 15, '1 15');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Scootmobielen', 15, '1 15');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Brommobiel', 15, '1 15');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Snorfiets', 15, '1 15');

/* Voertuigen -> TV - Vliegtuigen */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Zweefvliegtuig', 16, '1 16');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Proppellervliegtuig', 16, '1 16');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Vliegtuig', 16, '1 16');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Airbus', 16, '1 16');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Helikopter', 16, '1 16');

/* Voertuigen -> TV - Speelgoed */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Step', 17, '1 17');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Skateboard', 17, '1 17');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Skelter', 17, '1 17');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Elektrische apparaten', 17, '1 17');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Rolschaatsen', 17, '1 17');

/* Voertuigen -> TV - Overig */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Bus', 18, '1 18');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Vrachtwagen', 18, '1 18');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Trekker', 18, '1 18');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Landbouw voertuigen', 18, '1 18');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Driewielers', 18, '1 18');

/* Speelgoed -> Playmobil niveau 3 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Playmobil Camping', 29, '3 29');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Playmobil Villa', 29, '3 29');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Playmobil In het zwembad', 29, '3 29');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Playmobil Haarsalon', 29, '3 29');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('PlayMobil Dierenasiel', 29, '3 29');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Playmobil Manege', 29, '3 29');

/* Speelgoed -> Lego niveau 3 */
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Lego Camping', 30, '3 30');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Lego Villa', 30, '3 30');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Lego In het zwembad', 30, '3 30');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Lego Haarsalon', 30, '3 30');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Lego Dierenasiel', 30, '3 30');
insert into Rubriek (rubrieknaam, rubriek, rubriekpad) values ('Lego Manege', 30, '3 30');


/* Testdata: Vragen */
insert into Vraag (vraag) values('In welke stad/dorp ben je geboren?');
insert into Vraag (vraag) values('Wat is de meisjesnaam van je moeder?');
insert into Vraag (vraag) values('Wat is je lievelingsgerecht?');
insert into Vraag (vraag) values('Naar welke basisschool ging je?');
insert into Vraag (vraag) values('Hoe heette je huisdier?');


/* Testdata: Gebruiker */
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('ggrute0', 'Galina', 'Grute', '8687 Londonderry Plaza', 'Non-binary', null, 'Bluefields', 'Nicaragua', '7/16/2020', 'ggrute0@vk.com', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Western pygmy possum', 0);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('jfireman1', 'Johnnie', 'Fireman', '5190 Mallory Point', 'Bigender', null, 'Runsonglaozhai', 'China', '10/25/2020', 'jfireman1@plala.or.jp', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Ring-necked pheasant', 1);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('kmaplestone2', 'Karita', 'Maplestone', '767 Gulseth Crossing', 'Male', '90005', 'Los Angeles', 'United States', '4/6/2021', 'kmaplestone2@umich.edu', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Toucan, red-billed', 1);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('dgoves3', 'Daphna', 'Goves', '318 Nancy Lane', 'Polygender', null, 'Nigríta', 'Greece', '10/8/2020', 'dgoves3@tinypic.com', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'African skink', 1);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('peskriet4', 'Pasquale', 'Eskriet', '833 Canary Center', 'Genderfluid', null, 'Munsan', 'South Korea', '9/16/2020', 'peskriet4@so-net.ne.jp', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Goliath heron', 1);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('jgerckens5', 'Joly', 'Gerckens', '3 David Way', 'Polygender', '2713', 'Balangonan', 'Philippines', '10/8/2020', 'jgerckens5@merriam-webster.com', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'California sea lion', 1);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('syeabsley6', 'Sal', 'Yeabsley', '269 Almo Junction', 'Agender', '88807', 'Kota Kinabalu', 'Malaysia', '1/21/2021', 'syeabsley6@simplemachines.org', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Antechinus, brown', 1);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('mtomkinson7', 'Madonna', 'Tomkinson', '614 Sloan Road', 'Non-binary', null, 'Ba?ki Petrovac', 'Serbia', '5/29/2020', 'mtomkinson7@engadget.com', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Vine snake (unidentified)', 1);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper) values ('cbastow8', 'Celisse', 'Bastow', '61 Ramsey Circle', 'Male', '2710-106', 'Várzea de Sintra', 'Portugal', '3/30/2021', 'cbastow8@tinyurl.com', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Rhea, gray', 0);
insert into Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, verkoper, is_beheerder) values ('kmonier9', 'Kipp', 'Monier', '20262 Oak Valley Parkway', 'Bigender', null, 'Vareiá', 'Greece', '12/12/2020', 'kmonier9@cornell.edu', '$2y$10$Lc5tvf22PAPdcj4ZKo.IKuB5gVGjmrz.YP975XHlbjfnhp3.dnAs6', 1, 'Sloth, two-toed', 0, 1);

/* Testdata: telefoonnummers */
insert into GebruikersTelefoon (gebruiker, telefoon) values ('ggrute0', '0623958546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('jfireman1', '0625658546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('kmaplestone2', '0623923546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('dgoves3', '0623958896');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('peskriet4', '0622958546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('jgerckens5', '0625950546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('syeabsley6', '0623543546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('mtomkinson7', '0684658546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('cbastow8', '0622758546');
insert into GebruikersTelefoon (gebruiker, telefoon) values ('kmonier9', '0623958846');


/* Testdata: Verkopers */
insert into Verkoper (gebruiker, bank, bankrekening, controleoptienaam, creditcardnummer) values ('cbastow8', 'Rabobank', 'RABO23422111', 'creditcard', '453543211');


/* Testdata: Voorwerpen en koppelingen aan rubrieken */
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Playmobil camper', 'Mooie camperset.' , 99.00 , 'contant', 'direct betalen', 'Amsterdam', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Playmobil luxe leven', 'Playmobil set met vila en luxe auto.' , 12.00 , 'contant', 'direct betalen', 'Arnhem', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Playmobil duikplank set', 'Playmobil set met verschillende duikplanken' , 45.00 , 'contant', 'direct betalen', 'Tilburg', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Playmobil kappersstoel set', 'Playmobil kapperstoel voor de kapsalon.' , 19.00 , 'contant', 'direct betalen', 'Rotterdam', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Playmobil honden set', 'Playmobil hondenset voor het asiel.' , 71.00 , 'contant', 'direct betalen', 'Hengelo', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Playmobil paardenstallen', 'Playmobil paardenstallen voor je manege.' , 112.00 , 'contant', 'direct betalen', 'Doetinchem', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);

insert into VoorwerpInRubriek (voorwerp, rubriek) values (1, 81);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (2, 82);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (3, 83);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (4, 84);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (5, 85);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (6, 86);

insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Lego camper', 'Mooie camperset.' , 55.00 , 'contant', 'direct betalen', 'Zutphen', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Lego luxe leven', 'Lego set met vila en luxe auto.' , 34.00 , 'contant', 'direct betalen', 'Zevenaar', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Lego duikplank set', 'Lego set met verschillende duikplanken' , 67.00 , 'contant', 'direct betalen', 'Duiven', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Lego kappersstoel set', 'Lego kapperstoel voor de kapsalon.' , 88.00 , 'contant', 'direct betalen', 'Westervoort', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Lego honden set', 'Lego hondenset voor het asiel.' , 21.00 , 'contant', 'direct betalen', 'Ede', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Lego paardenstallen', 'Lego paardenstallen voor je manege.' , 212.00 , 'contant', 'direct betalen', 'Wageningen', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);


insert into VoorwerpInRubriek (voorwerp, rubriek) values (7, 87);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (8, 88);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (9, 89);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (10, 90);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (11, 91);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (12, 92);



insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Iphone 7S', 'Mooie camperset.' , 654.00 , 'contant', 'direct betalen', 'Groningen', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Samsung Galaxy S4', 'Lego set met vila en luxe auto.' , 799.00 , 'contant', 'direct betalen', 'Zwolle', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('LG G4', 'Lego set met verschillende duikplanken' , 245.00 , 'contant', 'direct betalen', 'Zeewolde', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Huawai S4', 'Lego kapperstoel voor de kapsalon.' , 378.00 , 'contant', 'direct betalen', 'Amstelveen', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Sony Xperia', 'Lego hondenset voor het asiel.' , 489.00 , 'contant', 'direct betalen', 'Utrecht', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Nokia K400', 'Lego paardenstallen voor je manege.' , 90.00 , 'contant', 'direct betalen', 'Maastricht', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);


insert into VoorwerpInRubriek (voorwerp, rubriek) values (13, 19);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (14, 20);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (15, 21);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (16, 22);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (17, 23);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (18, 24);



insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Apple TV', 'Mooie camperset.' , 467.00 , 'contant', 'direct betalen', 'Venlo', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Samsung 4K OLED TV', 'Lego set met vila en luxe auto.' , 1112.00 , 'contant', 'direct betalen', 'Arnhem', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('LG 4ED', 'Lego set met verschillende duikplanken' , 562.00 , 'contant', 'direct betalen', 'Nijmegen', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Huawai 65 inch TV', 'Lego kapperstoel voor de kapsalon.' , 212.00 , 'contant', 'direct betalen', 'Didam', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Sony Bright OLED TV', 'Lego hondenset voor het asiel.' , 699.00 , 'contant', 'direct betalen', 'Enschede', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);
insert into Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, looptijd, looptijdBeginDag, looptijdBeginTijdstip, verzendkosten, verzendinstructies, verkoper, looptijdEindeDag, looptijdEindeTijdstip, veilingGesloten) values ('Nokia Nostalgia TV', 'Lego paardenstallen voor je manege.' , 2445.00 , 'contant', 'direct betalen', 'Arnhem', 'Nederland', 3, '14-5-2021', '14:00', 2.99, 'ophalen', 'cbastow8', '14-5-2021', '14:00', 0);


insert into VoorwerpInRubriek (voorwerp, rubriek) values (19, 25);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (20, 26);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (21, 27);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (22, 28);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (23, 29);
insert into VoorwerpInRubriek (voorwerp, rubriek) values (24, 30);

/*Testdata: Biedingen */
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (1, 120, 'cbastow8', '22-5-2021', '15:00');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (1, 125, 'cbastow8', '22-5-2021', '15:15');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (1, 135, 'cbastow8', '22-5-2021', '15:30');

INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (2, 120, 'cbastow8', '22-5-2021', '15:45');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (2, 125, 'cbastow8', '22-5-2021', '15:23');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (3, 135, 'cbastow8', '22-5-2021', '15:54');

INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (4, 149, 'cbastow8', '22-5-2021', '15:01');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (5, 128, 'cbastow8', '22-5-2021', '15:13');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (5, 137, 'cbastow8', '22-5-2021', '15:12');


INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (6, 143, 'cbastow8', '22-5-2021', '16:01');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (7, 137, 'cbastow8', '22-5-2021', '17:16');
INSERT INTO bod (voorwerp, bodBedrag, gebruiker, bodDag, bodTijdstip) VALUES (7, 255, 'cbastow8', '22-5-2021', '16:13');

/*Testdata: Bestanden */
INSERT INTO Bestand(voorwerp, filenaam) VALUES(1, '../media/veilingen/voorwerp1/preview-1.png')
INSERT INTO Bestand(voorwerp, filenaam) VALUES(2, '../media/veilingen/voorwerp2/preview-2.png')
INSERT INTO Bestand(voorwerp, filenaam) VALUES(3, '../media/veilingen/voorwerp3/preview-3.png')
INSERT INTO Bestand(voorwerp, filenaam) VALUES(4, '../media/veilingen/voorwerp4/preview-4.png')
INSERT INTO Bestand(voorwerp, filenaam) VALUES(5, '../media/veilingen/voorwerp5/preview-5.png')
INSERT INTO Bestand(voorwerp, filenaam) VALUES(6, '../media/veilingen/voorwerp6/preview-6.png')
INSERT INTO Bestand(voorwerp, filenaam) VALUES(7, '../media/veilingen/voorwerp7/preview-7.png')

--voor rubrieken
INSERT INTO BestandRubriek (rubriek, filenaam) VALUES(29, '../media/rubrieken/playmobil.jpg');
INSERT INTO BestandRubriek (rubriek, filenaam) VALUES(30, '../media/rubrieken/lego.jpg');
INSERT INTO BestandRubriek (rubriek, filenaam) VALUES(27, '../media/rubrieken/telefoon.jpg');
INSERT INTO BestandRubriek (rubriek, filenaam) VALUES(28, '../media/rubrieken/tv.jpg');

--chats en berichten
INSERT INTO Chats (gebruiker1, gebruiker2) VALUES ('asdf','cbastow8');
INSERT INTO Chats (gebruiker1, gebruiker2) VALUES ('asdf', 'asdff');
INSERT INTO Berichten (chatId, verzender, ontvanger, dag, tijdstip, bericht) VALUES(1, 'asdf','cbastow8','01-06-2021','12:00','welkom op de site, leuk dat je een bericht stuurt.');
INSERT INTO Berichten (chatId, verzender, ontvanger, dag, tijdstip, bericht) VALUES(1, 'cbastow8','asdf','01-06-2021','12:01','welkom op de site, leuk dat je een bericht stuurt!');
INSERT INTO Berichten (chatId, verzender, ontvanger, dag, tijdstip, bericht) VALUES(2, 'asdf','asdff','01-06-2021','12:02','welkom op de site, leuk dat je een bericht stuurt?');
INSERT INTO Berichten (chatId, verzender, ontvanger, dag, tijdstip, bericht) VALUES(2, 'asdff','asdf','01-06-2021','12:02','welkom op de site, leuk dat je een bericht stuurt...');

--feedback: review: verkoper
INSERT INTO Feedback (reviewer, voorwerp, beoordeling, verkoper, soortGebruiker, feedbackSoort, dag, tijdstip, beschrijving) VALUES('asdf', 1, 4,'cbastow8','koper', 'review', '31-5-2021', '12:00' ,'een hele fijne verkoper');
INSERT INTO Feedback (reviewer, voorwerp, beoordeling, verkoper, soortGebruiker, feedbackSoort, dag, tijdstip, beschrijving) VALUES('asdf', 1, 2,'cbastow8','koper', 'review', '31-5-2021', '12:00' ,'waardeloze verkoper, geen reacties, wel ontvangen');
