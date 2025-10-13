<?php
	session_start();
	if(isset($_POST["item"]) && isset($_SESSION["login"])){
		include("../Parametres.php");
		include("../Fonctions.inc.php");
		include("../Donnees.inc.php");

		//Cross-site Scripting (XSS)

			$mysqli=mysqli_connect($host,$user,$pass) //or die("Problème de création de la base :".mysqli_error());

			if (!$mysqli) {
        		die("Problème de création de la base."); // message générique
    		}
    		mysqli_select_db($mysqli, $base) or die("Impossible de sélectionner la base.");

    		//Fonction query corrigée contre XSS
    		function query($link, $query) {
        		$resultat = mysqli_query($link, $query);
        		if (!$resultat) {
            		// Log cote serveur pour debug
            		error_log("MySQL Error: " . mysqli_error($link));

            	// Message générique cote utilisateur
            	die("Impossible de traiter la requête pour le moment.");
        		}
        	return $resultat;
    		}
    		$item = intval($_POST["item"]);
			
			$str0 = 'select * from favs where id_prod = '.$_POST["item"];
			$str = "INSERT INTO FAVS VALUES('".$_SESSION["login"]."','".$_POST["item"]."')";
			$result = query($mysqli,$str0) or die("Impossible de ajouter produit<br>");
			
			if(mysqli_num_rows($result)>0 && isset($_POST["x"])){
						query($mysqli,'delete from favs where id_prod = '.$_POST["item"].' and LOGIN = \''.$_SESSION["login"].'\'');
						echo 'delete set';
			}else{
						query($mysqli,$str);
						echo 'set';
			}
			
			mysqli_close($mysqli);
	}
?>