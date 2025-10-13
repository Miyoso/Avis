<?php
	include 'fonctions/fonctionsLayout.php';
	include 'fonctions/fonctionsFavoris.php';
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
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
				<script>
			function addPanier(e){
				$.ajax({
					type: 'POST',
					url: 'fonctions/fonctionsPanier.php',
					data: {item : e},
					success: function(data){
								alert(data);
								location.reload();
					},
				});
			};	
				
			function addFav(e){
				$.ajax({
					type: 'POST',
					url: 'fonctions/fonctionsFav.php',
					data: {item : e, x : '1'},
					success: function(data){
								alert(data);					
					},
				});
			};	
		</script>
	</head>
	<body>
<?php include("./navbar.php");?>	
		<!-- Main -->
			<div id="main" class="wrapper style4">
				<div class="container">
					<div class="row">
							<section>
								<header class="major">
									<h3>Vos Produits Favoris</h3>
								</header>
								<?php afficherFavoris(); ?>				
							</section>			
								
									
					</div>
				</div>
			</div>
		
		<!-- Team -->
		

	<!-- Footer --><?php include_once 'footer.php';?>

	</body>
</html>