<?php
	function afficherProduits(){
		include("Parametres.php");
		include("Fonctions.inc.php");
		include("Donnees.inc.php");

		//$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		$mysqli=mysqli_connect($host,$user,$pass) or die("Une erreur est survenue.");
		//mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
		mysqli_select_db($mysqli,$base) or die("Une erreur est survenue.");
		$result = query($mysqli,'select * from PRODUITS limit 4');
		
		echo '<div class="wrapper style5"><section id="team" class="container"><div class="row">';
		$temp = 0;
		while($row = mysqli_fetch_assoc($result)){
			echo '<div class="3u">';
			echo '<a href="#" onclick="addPanier(\''.$row["ID_PROD"].'\')"><img src="images/13336.gif" style="height:30px;"/></a>  <a href="#" onclick="addFav(\''.$row["ID_PROD"].'\')"><img src="images/favorite_add.png" style="height:40px;"/></a><br/>';
			echo '<img src="'.$row["PHOTO"].'" class="Image"/>';
			echo '<h3 style="color:grey">'.$row["LIBELLE"].'</h3>';
			echo '<p style="color:black">'.$row["DESCRIPTIF"].'</p>';
			echo '</div>';
			$temp++;
			if($temp == 4){
				echo '</div><div class="row">';
			}
		}
		echo '</div></section></div>';
		mysqli_close($mysqli);
	}
?>