
		<!-- Header Wrapper -->
			<div class="wrapper style1">
			
			<!-- Header -->
				<div id="header">
					<div class="container">
							
						<!-- Logo -->
							<h1><a href="#" id="logo">Taupe Meubles</a></h1>
						
						<!-- Nav -->
							<nav id="nav">
								<ul>
									<li class="active"><a href="index.php">Accueil</a></li>
									<li><a href="">Produits</a>
										<ul>
											<!-- <li><a href="" onclick="menu('lit.php')">Lit</a></li>
											<li><a href="" onclick="menu('table.php')">Table</a></li>
											<li><a href="" onclick="menu('cuisine.php')">Cuisine</a></li>
											<li><a href="" onclick="menu('buffet.php')">Buffet</a></li>
											<li><a href="" onclick="menu('armoire.php')">Armoire</a></li>
											<li><a href="" onclick="menu('chaise.php')">Chaise</a></li>
											<li><a href="" onclick="menu('bureau.php')">Bureau</a></li>
											<li><a href="" onclick="menu('SalleDeBain.php')">SalleDeBain</a></li> -->
                                            <?php
                                            include_once 'Parametres.php';
                                            $mysqli = mysqli_connect($host, $user, $pass, $base) or die("Erreur de connexion : " . mysqli_connect_error());
                                            mysqli_select_db($mysqli, $base) or die("Une erreur est survenue.");

                                            $result = mysqli_query($mysqli, "SELECT LIBELLE_RUB FROM RUBRIQUES ORDER BY LIBELLE_RUB ASC");
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $libelle = htmlspecialchars($row['LIBELLE_RUB']);
                                                $url = 'produits.php?rubrique=' . urlencode($libelle);
                                                echo '<li><a href="' . $url . '">' . $libelle . '</a></li>';
                                            }
                                            ?>
										</ul>
									</li>
									<li>
										<a href="">Profil</a>
										<?php afficherCadreCompte(); ?>
									</li>
									<li><a href="favoris.php">Favoris</a></li>
									<li><a href="panier.php">Panier</a></li>
								</ul>
							</nav>
	
					</div>
				</div>
			</div>