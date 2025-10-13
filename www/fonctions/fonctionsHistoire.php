<?php
	
	function afficherCommandes(){
		include("Parametres.php");
		include("Fonctions.inc.php");
		include("Donnees.inc.php");

		$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
		$result = query($mysqli,'select id_com, id_client, (select prenom from users where users.login = commande.id_client limit 1) as prenom,(select nom from users where users.login = commande.id_client limit 1) as nom,id_prod,date,ADRESSE,cp,ville from commande where  id_client = \''.$_SESSION["login"].'\'');
		$num = mysqli_num_rows($result);
			if($num > 0){
			echo "<table>";
					echo "<tr><td width='50px'>ID</td><td width='80px'>Date</td><td width='80px'>Produit</td><td width='80px'>Client</td></tr>"; 
					echo "<tr><td colspan='5'><hr></td></tr>";
					while ($row = mysqli_fetch_assoc($result)){
						echo "<tr>";
						echo "<td id='item'>".$row["id_com"]."</td><td> ".$row["date"]."</td><td><a href='details.php?prod=".$row["id_prod"]."'> ".$row["id_prod"]."<a></td><td><a href='details.php?login=".$row["id_client"]."'>".$row["nom"]." ".$row["prenom"]."</a></td>";
						echo "</tr>";
						echo "<tr><td colspan='5'><hr></td></tr>";
					}
			echo "</table>";
			}else{
				echo '<h4>Pas d\'histoire d\'achats</h4>';
			}
		mysqli_close($mysqli);
	}
?>