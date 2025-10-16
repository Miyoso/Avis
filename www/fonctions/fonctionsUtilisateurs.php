<?php
	function afficherUtilisateurs(){
		include("Parametres.php");
		include("Fonctions.inc.php");
		include("Donnees.inc.php");

        // Ancien code vulnérable :
        // $mysqli = mysqli_connect($host, $user, $pass) or die("Problème de création de la base :".mysqli_error());

        // Début Correction
        $mysqli = @mysqli_connect($host, $user, $pass);
        if (!$mysqli) {
            error_log('Erreur connexion DB : ' . mysqli_connect_error());
            echo '<p>Erreur interne du serveur. Veuillez réessayer plus tard.</p>';
            exit;
        }

        if (!@mysqli_select_db($mysqli, $base)) {
            error_log('Erreur sélection DB : ' . mysqli_error($mysqli));
            echo '<p>Erreur interne du serveur. Veuillez réessayer plus tard.</p>';
            mysqli_close($mysqli);
            exit;
        }
        // Fin Correction
        
		echo "<hr>";
		$result = query($mysqli,'select login,prenom,nom,email,adresse,ville,telephone from USERS where IS_ADMIN = true');
		$result2 = query($mysqli,'select login,prenom,nom,email,adresse,ville,telephone from USERS where IS_ADMIN = false');
		if((mysqli_num_rows($result)>0) || (mysqli_num_rows($result2)>0)){
			
			echo "<table>";
			if($result){
					echo "<tr><td><h2>Administrateur</h2></td></tr>";
					while ($row = mysqli_fetch_assoc($result)){
						echo "<tr>";
						echo "<td>".$row["prenom"]." ".$row["nom"]."</td>";
						echo "</tr>";
					}
			}
			
			echo "</table><br/><br/>";
			echo '<hr>';
			echo "<table>";
			if($result2){
				echo "<tr><td><h2>Clients</h2></td></tr>";
				while ($row = mysqli_fetch_assoc($result2)){
						echo "<tr>";
						echo "<td>".$row["login"]." ".$row["prenom"]." ".$row["nom"]."</td>";
						echo "<td><a href='details.php?login=".$row["login"]."'> détails </a></td>";
						echo "</tr>";
					}
			}
			
					
			echo "</table>";
		}
		else{
			echo "Aucun enregistrement dans la base de données";
		}
		mysqli_close($mysqli);
	}
	
?>