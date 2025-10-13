<?php
	session_start();
	function afficherFavoris(){
		include("Parametres.php");
		include("Fonctions.inc.php");
		
		if(isset($_SESSION["login"])){
			
		
					$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
					mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
					$result = query($mysqli,'select * from produits where id_prod in (select id_prod from favs where login = \''.$_SESSION["login"].'\')');
					$num = mysqli_num_rows($result);
					echo '<div class="wrapper style5"><section id="team" class="container"><div class="row">';
						if($num > 0){
								$temp = 0;
							while($row = mysqli_fetch_assoc($result)){
								echo '<div class="3u">';
								echo '<h3><a href="#" onclick="addPanier(\''.$row["ID_PROD"].'\')"><img src="images/13336.gif" style="height:30px;"/></a> '.$row["LIBELLE"].'<a href="#" onclick="addFav(\''.$row["ID_PROD"].'\')"><img src="images/No-entry.png" style="height:25px;"/></a><br/></h3>';
								echo '<img src="'.$row["PHOTO"].'" class="Image"/>';
								echo '<h3 style="color:grey">'.$row["LIBELLE"].'</h3>';
								echo '<p style="color:black">'.$row["DESCRIPTIF"].'</p>';
								echo '</div>';
								$temp++;
								if($temp == 3){
									echo '</div><div class="row">';
								}
							}
						}else{
							echo '<h4 style="color:black">Pas de Produits dans vos favoris</h4>';
						}
					echo '</div></section></div>';
					mysqli_close($mysqli);
			}
			else{
				echo "<h4 style='color:black'>Connectez vous por afficher vos favoris</h4>";
			}
	}
?>