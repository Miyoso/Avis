<?php
	session_start();
	include 'fonctions/fonctionsLayout.php';
	include 'fonctions/fonctionsInscription.php';
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
									<h2>Inscription</h2>
								</header>
								<div id="container" class="clear">
    <!-- main content -->
    <div id="homepage" style="min-height:400px">
	<div class="modal-dialog">
			<div class = "modal-content">
				<div class="modal-header">
				<h4>Créer un compte</h4>
				</div>
				<div class="modal-body">
					<form method="post" action="enregistrer.php" autocomplete="off">
						<div>
							Login: <input type="text" maxlength="100" name="loginbdd" /><br/>
						</div>
						<div>
							Password<input type="password" maxlength="100" name="passwordbdd"/><br/>
						</div>
						<div>
							Email: <input type="email" maxlength="200" name="emailbdd"/><br/>
						</div>

						<div>
							Nom: <input type='text' placeholder='Nom' maxlength='200' name='nombdd'/><br/>
						</div>
						<div>
							Prénom: <input type='text' placeholder='Prénom' maxlength='100' name='prenombdd' /><br/>
						</div>
						<div>
							Date de Naissance: <input type='date' name='datebdd' placeholder='Date de Naissance'/>
						</div>
						<div>
							<br/>Telephone: <input type='text' placeholder='Telephone' maxlength='15' name='telephonebdd'/><br/>
						</div>
						<div>
							Adresse: <input type='textarea' placeholder='Adresse' maxlength='500' name='adressebdd'/><br/>
						</div>
						<div>
							Ville: <input type='textarea' placeholder='Ville' maxlength='100' name='villebdd'/><br/>
						</div>
						<div>
							Code Postal: <input type='textarea' placeholder='Code Postal' maxlength='50' name='codepostalbdd'/><br/>
						</div>
						<div>
							<label class='radio-inline active'><input type='radio' name='optradio' checked='' value='Homme'/>Homme</label>
							<label class='radio-inline'><input type='radio' name='optradio' value='Femme'/>Femme</label>
						</div>
						<div>
							<br/><input type="submit" value="valider">
						</div>
						<div>
						<?php
							echo '<ul>';
							if(isset($_SESSION["inscription"])){
								$arr = $_SESSION["inscription"];
								foreach($arr as $item){
									echo '<li>'.$item.'</li>';
								}
							}
							echo '</ul>';
							unset($_SESSION["inscription"]);
						?>
						</div>
					</div>					
				</div>
				</form>
			</div>
    <!-- / content body -->
  </div>
							</section>
						</div>						
					</div>
				</div>
			</div>
	<!-- Footer -->
	<?php include_once 'footer.php';?>

	</body>
</html>