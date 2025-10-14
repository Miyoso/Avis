<?php
	function afficherPanier(){

			if(isset($_SESSION["paiement"])){
				echo '<p style="color:'.$_SESSION["color"].';">'.$_SESSION["paiement"].'</p>';
				unset($_SESSION["color"]);
				unset($_SESSION["paiement"]);
			}
			else{
				if(isset($_COOKIE["panier"])){
						include("Parametres.php");
						include("Fonctions.inc.php");
						include("Donnees.inc.php");

						$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
						mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
						
						$arr = json_decode($_COOKIE["panier"]);
						echo '<table>';
						$item = 0;

                        //SQL Injection
                        //Ligne pas bon
						//foreach($arr as $item){
								//$str = 'select * from produits where id_prod = '.$item;
								//$result = query($mysqli,$str);
                            //propal de rep
                            $stmt = $mysqli->prepare("SELECT * FROM produits WHERE id_prod = ?");
                            foreach($arr as $item){
                                $id = intval($item); // sécurisation de la valeur du cookie
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();

								$row = mysqli_fetch_assoc($result);
											echo "<tr><td width='50px'>ID</td><td width='80px'>Libelle</td><td width='80px'>Prix</td></tr>"; 
											echo "<tr><td colspan='3'><hr></td></tr>";
												echo "<tr>";
												echo "<td id='item'>".$row["ID_PROD"]."</td><td> ".$row["LIBELLE"]."</td><td> ".$row["PRIX"]."</td>";
												echo '<td><button onclick="removePanier('.$row["ID_PROD"].','.$item.')">effacer</button></td>';
												echo "</tr>";
												echo "<tr><td colspan='3'><hr></td></tr>";
									
						}
						echo '</table>';
						mysqli_close($mysqli);
					}
					else{
						echo '<p>Pas de produits dans votre panier</p>';
					}
				}
			}
	
?>