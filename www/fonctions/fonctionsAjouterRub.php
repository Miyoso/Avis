<?php
	function ajouterProduit(){
		include("Parametres.php");
		include("Fonctions.inc.php");
		include("Donnees.inc.php");

		$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
		$result = query($mysqli,"select distinct LIBELLE_RUB from RUBRIQUE");
		
		echo "<h2>Ajouter</h2><br/>";
		echo "<hr>";
		echo "<form>";
		echo "<table>";
		echo "<tr><td>Libelle </td><td><input id='libelle'></input></td></tr>";
		echo "<tr><td>Rubrique mère</td><td><select id='option' style='width:145px'>";
		
		while($row = mysqli_fetch_assoc($result)){
			echo "<option>".$row["LIBELLE_RUB"]."</option>";
			}
			
		echo "</select></td></tr>";
		echo "<tr><td colspan='2'><br/><input type='submit' id='valider' value='Valider'></input></td></tr>";
		echo "</table>";
		echo "</form>";
		mysqli_close($mysqli);
		
	}
	
?>