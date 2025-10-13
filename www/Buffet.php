<?php
	session_start();
	include_once 'fonctions/fonctionsLayout.php';
?>
<!DOCTYPE HTML>
<!--
	Solarize by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>Taupe Meubles</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
		</noscript>
		<script>
			$().ready(function() {
				$('a img').click(function (e){
				e.preventDefault();				
				});
			});
			
			function addPanier(e){
				$.ajax({
					type: 'POST',
					url: 'fonctions/fonctionsPanier.php',
					data: {item : e},
					success: function(data){
								alert(data);					
					},
				});
			};

			function addFav(e){
				$.ajax({
					type: 'POST',
					url: 'fonctions/fonctionsFav.php',
					data: {item : e},
					success: function(data){
								alert(data);					
					},
				});
			};	
		</script>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>
	<?php include_once("./navbar.php");?>	
	
		<!-- Main -->
			<div id="main" class="wrapper style4">

				<div class="container">
					<div class="row">
						<!-- Content -->
							<section>
								<header class="major">
									<h2>Buffet</h2>
								</header>
								
								<?php	
									include_once 'fonctions/fonctionsLayout.php';
									include("Parametres.php");
									include("Fonctions.inc.php");
									include("Donnees.inc.php");

										$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
										mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
										//echo "<a href='ajouterProd.php'>Ajouter une rubrique</a><br/>";
										$result = query($mysqli,'select produits.id_prod as id,produits.Libelle as lib,produits.Photo as photo, produits.Descriptif as descr from produits,rubrique,appartient where appartient.id_prod = produits.id_prod and appartient.id_rub = rubrique.id_rub and libelle_rub = \'Buffet\'');
										
										echo '<div class="wrapper style5"><section id="team" class="container"><div class="row">';
										$temp = 0;
										while($row = mysqli_fetch_assoc($result)){
											echo '<div class="3u">';
											echo '<a href="#" onclick="addPanier(\''.$row["id"].'\')"><img src="images/13336.gif" style="height:30px;"/></a>  <a href="#" onclick="addFav(\''.$row["id"].'\')"><img src="images/favorite_add.png" style="height:40px;"/></a><br/>';
											echo '<img src="'.$row["photo"].'" class="Image"/>';
											echo '<h3 style="color:grey">'.$row["lib"].'</h3>';
											echo '<p style="color:grey">'.$row["descr"].'</p>';
											echo '</div>';
											$temp++;
											if($temp == 4){
												echo '</div><div class="row">';
											}
										}
										echo '</div></section></div>';
										mysqli_close($mysqli);
																
								?>
							</section>					

					</div>
				</div>

					</div>
				</div>
			</div>
		
		<!-- Team -->
			

	<!-- Footer -->
<?php include_once 'footer.php';?>

	</body>
</html>