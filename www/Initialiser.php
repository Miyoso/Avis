<html>
<head>
    <title>Initialisation de la base de données</title>
    <meta charset="utf-8"/>
</head>

<body>
<?php

include("Parametres.php");
include("Fonctions.inc.php");
include("Donnees.inc.php");

// Connexion au serveur MySQL

//MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());

//Le script d'initialisation peut etre lancé avant le démarrage du conteneur mysql
//On ajoute donc une boucle d'attente de la disponibilité du serveur mysql
$maxRetries = 10;
$retry = 0;
$connected = false;
while (!$connected && $retry < $maxRetries) {
    $mysqli = @mysqli_connect($host, $user, $pass);
    if ($mysqli) {
        $connected = true;
    } else {
        $retry++;
        sleep(3); // attendre 3s avant le prochain essai
    }
}
if (!$connected) die("Impossible de se connecter à MySQL après plusieurs essais");

// Suppression / Création / Sélection de la base de données : $base
query($mysqli, 'DROP DATABASE IF EXISTS ' . $base);
query($mysqli, 'CREATE DATABASE ' . $base);
mysqli_select_db($mysqli, $base) or die("Impossible de sélectionner la base : $base");


query($mysqli, "CREATE TABLE IF NOT EXISTS USERS (
					  LOGIN varchar(100)  PRIMARY KEY,
					  IS_ADMIN boolean DEFAULT false,
					  EMAIL varchar(200),
					  PASS varchar(100),
					  NOM varchar(50),
					  PRENOM varchar(50),
					  DATE varchar(10),
					  SEXE varchar(10),
					  ADRESSE varchar(500),
					  CODEP int(20),
					  VILLE varchar(50),
					  TELEPHONE int(50)					  
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				");

query($mysqli, "CREATE TABLE IF NOT EXISTS PRODUITS (
					  ID_PROD int(10) NOT NULL AUTO_INCREMENT,
					  LIBELLE VARCHAR(100) NOT NULL,
					  PRIX float,
					  DESCRIPTIF VARCHAR(500),
					  PHOTO varchar(80),
					  PRIMARY KEY(ID_PROD)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=14;
				");

query($mysqli, "CREATE TABLE IF NOT EXISTS FAVS (
					  LOGIN varchar(200),
					  ID_PROD int(10),
					  FOREIGN KEY (LOGIN) REFERENCES USERS(LOGIN),
					  PRIMARY KEY(LOGIN,ID_PROD)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				");

query($mysqli, 'CREATE TABLE IF NOT EXISTS COMMANDES (
  `ID_COM` bigint(20) NOT NULL AUTO_INCREMENT,
  `ID_PROD` int(11) NOT NULL,
  `ETAT` int(1), # NOT NULL ne nous sert pas
  `ID_CLIENT` varchar(200) NOT NULL,
  `DATE` varchar(40) NOT NULL,
  `CIVILITE` varchar(4), # NOT NULL ne nous sert pas
  `NOM` varchar(40) NOT NULL,
  `PRENOM` varchar(40) NOT NULL,
  `ADRESSE` varchar(160) NOT NULL,
  `CP` int(11) NOT NULL,
  `VILLE` varchar(80) NOT NULL,
  `TELEPHONE` varchar(10) NOT NULL,
  PRIMARY KEY (`ID_COM`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;');


query($mysqli, 'CREATE TABLE IF NOT EXISTS HIERARCHIE (
  `ID_PARENT` int(11) NOT NULL,
  `ID_ENFANT` int(11) NOT NULL,
  PRIMARY KEY (`id_parent`,`id_enfant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;');


query($mysqli, 'CREATE TABLE IF NOT EXISTS RUBRIQUES (
  `ID_RUB` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_RUB` varchar(80) NOT NULL,
  PRIMARY KEY (`ID_RUB`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;');

query($mysqli, 'CREATE TABLE IF NOT EXISTS APPARTIENT (
  `id_prod` int(11) NOT NULL,
  `id_rub` int(11) NOT NULL,
  PRIMARY KEY (`id_prod`,`id_rub`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;');

$rub = 13;
$num = 13;

// Insertion
query($mysqli, "INSERT INTO USERS VALUES ('admin', true, 'admin@admin.com','" . password_hash('pass', PASSWORD_DEFAULT) . "','ADMIN','admin','01/01/1999','Homme',NULL,'57000',NULL,918633099);");
query($mysqli, 'insert into RUBRIQUES (LIBELLE_RUB)values (\'Lit\')');

query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Lit mezzanine\',249,\'Lit mezzanine en pin massif couchage 140x200cm THEO Couleur: Blanc , Dimensions: 140x200cm\',\'images\\\meubles\\\033fb78dae72240e5826c74b0932798c.jpg\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Lit en Teck\',1005,\'Aspect authentique garanti grâce à une très belle finition de Teck massif vieilli.
Des trous et des nœuds soigneusement préservés pour révéler la beauté naturelle du bois et créer un aspect sauvage et rustique\',\'images\\\meubles\\\L001-MLI6013001-Z.jpg\')');
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub++ . ")");
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub . ")");

query($mysqli, 'insert into RUBRIQUES(LIBELLE_RUB)values (\'Cuisine\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Meuble bas de cuisine\',125,\'Excellent rapport qualité prix
Fabriqué en France
Coloris jeunes et tendances
A associer avec l ensemble de la collection SPRING pour un aménagement réussi\',\'images\\\meubles\\\MCU1254891-Z.jpg\')');
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub++ . ")");

query($mysqli, 'insert into RUBRIQUES(LIBELLE_RUB)values (\'Table\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Table a manger\',469,\'Table à manger rectangulaire bois avec allonge. Panneau de particules revêtu de mélamine Couleur : Chêne brut et béton Dimensions : Longueur : 180/240 cm Largeur : 90 cm Hauteur : 79 cm Table à manger ...\',\'images\\\meubles\\\L001-MSM1254818-Z.jpg\')');
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub++ . ")");


query($mysqli, 'insert into RUBRIQUES(LIBELLE_RUB)values (\'Chaise\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Chaise design simili\',99,\'Pin massif. Assise multiplis mousse 50kg/m3. Dossier mousse 50kg/m3. Revêtement : polyuréthane\',\'MSM6014266-Z.jpg\')');
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub++ . ")");

query($mysqli, 'insert into RUBRIQUES(LIBELLE_RUB)values (\'Buffet\')');
$rub++;

query($mysqli, 'insert into RUBRIQUES(LIBELLE_RUB)values (\'Armoire\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Armoire de chambre en bois\',405,\'2 portes miroirs coulissantes. 1/3 partie lingère : 4 étagères. 2/3 partie penderie : 1 tringle et une tablette. A monter soi-même avec la notice. Garantie 2 ans\',\'images\\\meubles\\\L001-MCH6085585-Z.jpg\')');
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub++ . ")");


query($mysqli, 'insert into RUBRIQUES(LIBELLE_RUB)values (\'Bureau\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Bureau verre et métal\',129,\'Bureau de dessin ultra moderne
Rangements pratiques
Espace de travail conséquent et aéré\',\'images\\\meubles\\\MBU6060008-Z.jpg\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'Bureau informatique \',220,\'Très bon rapport Qualité/Prix
Fonctionnel et design
Méthode de fabrication respectueuse de l environnement
Constitué de matériaux fiables et haut de gamme\',\'images\\\meubles\\\L001-MBU0016172-Z.jpg\')');
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub++ . ")");
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub . ")");


query($mysqli, 'insert into RUBRIQUES(LIBELLE_RUB)values (\'SalleDeBain\')');
query($mysqli, 'INSERT INTO PRODUITS (LIBELLE,PRIX,DESCRIPTIF,PHOTO) VALUES(\'meuble vasque + miroir + colonne\',675,\'Ensemble de salle de bain complet
Style contemporain ambiance zen
Rangement fonctionnel avec tiroirs à compartiments
Fermeture silencieuse des tiroirs et des portes\',\'images\\\meubles\\\B001-MBA1254590-2-Z.jpg\')');
query($mysqli, "insert into APPARTIENT values(" . $num++ . "," . $rub++ . ")");


mysqli_close($mysqli);
?>

Initialisation réussie
</body>
</html>