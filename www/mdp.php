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
    <script type="text/javascript">
		$(document).ready(function() {
			$("#valider").click(function(){
				$.ajax({
					   url: "fonctions/fonctionsMdp.php",
					   method: "POST",
					   data: {email : $("#email").val()},
					   success: function(data)
							{
								alert(data);
								history.back();
							}
						});
			}); 
		});
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
					<h2>Mot de passe oublié</h2><br/>
					<label>Entrez l'adresse email avec laquelle vous vous êtes inscrit pour réinitialiser votre mot de passe.</label><br/><br/>
					<input type="text" size="55" id="email" placeholder="votre email"></input><br/><br/>
					<button id="valider">Valider</button>
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