<?php
	session_start();
	if(isset($_POST["item"])){
		include("../Parametres.php");
		include("../Fonctions.inc.php");
		include("../Donnees.inc.php");
			$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
			mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
			
			query($mysqli,'delete from produits where id_prod = '.$_POST["item"]);
			mysqli_close($mysqli);
	}
	else{
		echo "Erreur";
	}
?>