<?php
	function afficherProduits(){
		include("Parametres.php");
		include("Fonctions.inc.php");
		include("Donnees.inc.php");

        // Ancien code (vulnérable) :
        // $mysqli = mysqli_connect($host, $user, $pass) or die("Problème de création de la base :" . mysqli_error());

        // Début Correction
        $mysqli = @mysqli_connect($host, $user, $pass);
        if (!$mysqli) {
            error_log('Erreur connexion DB : ' . mysqli_connect_error());
            echo 'Erreur interne du serveur. Veuillez réessayer plus tard.';
            exit;
        }
        if (!@mysqli_select_db($mysqli, $base)) {
            error_log('Erreur sélection base : ' . mysqli_error($mysqli));
            echo 'Erreur interne du serveur. Veuillez réessayer plus tard.';
            mysqli_close($mysqli);
            exit;
        }
        // Fin Correction
		
		echo "<a href='ajouterProd.php'>Ajouter un produit</a><br/>";
		//echo "<a href='ajouterProd.php'>Ajouter une rubrique</a><br/>";
		echo "<hr>";
		echo "<h2>Produits</h2><br/>";
		$result = query($mysqli,'select id_prod,Libelle,Prix from PRODUITS');
		
		if(mysqli_num_rows($result)<=0){
			echo "Aucun enregistrement dans la base de données";
		}
		else if(mysqli_num_rows($result)>0){
			echo "<table>";
					echo "<tr><td width='50px'>ID</td><td width='80px'>Libelle</td><td>Rubrique</td><td width='80px'>Prix</td></tr>"; 
					echo "<tr><td colspan='3'><hr></td></tr>";
					while ($row = mysqli_fetch_assoc($result)){
						$result2 = query($mysqli,'select LIBELLE_RUB from RUBRIQUES,APPARTIENT where RUBRIQUES.id_rub = APPARTIENT.id_rub and APPARTIENT.id_prod = '.$row["id_prod"].' limit 1');
						$rub = mysqli_fetch_assoc($result2);
						echo "<tr>";
						echo "<td id='item'><a href='details.php?prod=".$row["id_prod"]."'>".$row["id_prod"]."</a></td><td> ".$row["Libelle"]."</td><td>".$rub["LIBELLE_RUB"]."</td><td> ".$row["Prix"]."</td>";
						echo "<td><button onclick='effacerProd(".$row["id_prod"].")'>effacer</button></td>";
						echo "</tr>";
						echo "<tr><td colspan='3'><hr></td></tr>";
					}
			echo "</table>";
		}
		mysqli_close($mysqli);
	}
	
?>