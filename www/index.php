<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	include_once 'fonctions/fonctionsLayout.php';
	include_once 'afficherProduits.php';
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
		<!--[if lte IE 8]>
        <script src="css/ie/html5shiv.js"></script><![endif]-->
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
		<!--[if lte IE 8]>
        <link rel="stylesheet" href="css/ie/v8.css"/><![endif]-->
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
									<h2>Nouveautés</h2>
								</header>
								<?php afficherProduits(); ?>
							</section>					

					</div>
				</div>
			</div>
		
		<!-- Team -->
			

	<!-- Footer -->
<?php include_once 'footer.php';?>

	</body>
</html>