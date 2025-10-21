<?php
	session_start();
	include 'fonctions/fonctionsLayout.php';
	include 'fonctions/fonctionsAcheter.php';
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE HTML>
<!--
	Solarize by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html lang="fr">
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
					function removePanier(e,i){
						$.ajax({
							type: 'POST',
							url: 'fonctions/fonctionsRemove.php',
							data: {item : e,pos : i,
                                csrf_token: '<?= $csrf_token ?>'},
							success: function(data){
										alert(data);
										location.reload();
							},
						});
					};
		</script>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>
	<?php include("./navbar.php");?>		
		<!-- Main -->
			<div id="main" class="wrapper style4" >
				<div class="container">
					<div class="row">
				
						<!-- Content -->
						<div id="content" class="8u skel-cell-important">
							<section>
								<header class="major">
									<h2>Panier</h2>
								</header>
								<?php afficherPanier(); ?>
							</section>
						</div>		

					</div>
					<div>
					<?php
						if(isset($_SESSION["login"]) && isset($_COOKIE["panier"])){
									echo '<a href="paiement.php">ACHETER</a>';
						}else if(isset($_COOKIE["panier"])){
										echo '<p>Connectez vous pour pouvoir acheter</p>';
									}
					?>
					</div>
				</div>

			</div>

		<!-- Team -->

	<!-- Footer -->
		<?php include 'footer.php';?>

	</body>
</html>