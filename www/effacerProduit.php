<?php
	session_start();
	include("Parametres.php");
	include("Fonctions.inc.php");
	include("Donnees.inc.php");

		
	if(isset($_SESSION["login"]) && $_SESSION["login"] = 'admin' && isset($_POST["id"])){
		$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
		$str = "delete from favs where id_prod =".$_POST["id"];
		$str2 = "delete from produits where id_prod =".$_POST["id"];
		query($mysqli,$str);
		query($mysqli,$str2);
		mysqli_close($mysqli);
		echo "produit effacé avec succès";
	}
	

?>