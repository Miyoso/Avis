<?php
	include 'fonctions/fonctionsLayout.php';
	include 'fonctions/fonctionsProduits.php';
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
		<!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> -->
        <script src="https://code.jquery.com/jquery-1.10.2.js"
                integrity="sha256-DW2s8VFiMxoMka0rKLa9qXyytNi9F6Nq3QkLpwWg4g8="
                crossorigin="anonymous"></script>
		<script>
		function effacerProd(e){
			$.ajax({
					   url: "effacerProduit.php",
					   method: "POST",
					   data: {id : e},
					   success: function(data)
							{
								alert(data);
								location.reload();
							}
						});
		}
		</script>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>
<?php include("./navbar.php");?>
		<!-- Main -->
			<div id="main" class="wrapper style4">
				<div class="container">
					<div class="row">
				
						<!-- Content -->
						<div id="content" class="8u skel-cell-important">
							<section>
								<header class="major">
									<h2>Produits</h2>
								</header>
								<?php afficherProduits(); ?>
							</section>
						</div>					
					</div>
				</div>
			</div>
	<!-- Footer -->
<?php include_once 'footer.php';?>

	</body>
</html>