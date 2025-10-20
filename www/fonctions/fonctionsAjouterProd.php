<?php
	function ajouterProduit($mysqli, $csrf_token){
		include("Parametres.php");
		include("Fonctions.inc.php");
		include("Donnees.inc.php");

		//MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		//MODIF mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
        $str = "SELECT DISTINCT libelle_rub FROM RUBRIQUES";

        $result = query($mysqli, $str);

        echo '</select>';
        echo "<h2>Ajouter produit</h2><br/>";
        // Ajout du formulaire avec l'attribut onsubmit pour l'appel AJAX
		echo "<form  enctype='multipart/form-data' onsubmit='envoyerFormulaireAjax(event)' action='fonctions/fonctionsAjouterProdd.php' method='post' class='putImages'>";
        echo '<input type="hidden" name="csrf_token" value="'.htmlspecialchars($csrf_token).'"/>';

        echo "<table>";
		echo "<tr><td width='180px'>Libelle</td><td><input type='text' name='libelle'></input></td></tr>";
		echo "<tr><td>Prix</td><td><input type='text' name='prix'></input></td></tr>";
		echo "<tr><td>Descriptif</td><td><input type='text' name='descriptif'></input></td></tr>";

		echo "<tr><td>Rubrique</td><td><select name='rubrique' style='wnameth:145px'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . htmlspecialchars($row['libelle_rub']) . '">'
                . htmlspecialchars($row['libelle_rub'])
                . '</option>';
        }
		
		echo "</select></td></tr>";
		
		echo "<tr><td>Image</td><td><input id='file' name='file' type='file' multiple/></td></tr>";
		echo "<tr><td><br/><input name='valider' type='submit' value='Valider'/></td></tr>";
		echo "</table>";
		echo "</form>";
	}
?>