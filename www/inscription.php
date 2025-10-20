<?php
	session_start();
	include 'fonctions/fonctionsLayout.php';
	include 'fonctions/fonctionsInscription.php';

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
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
    <!-- Le Formulaire d'inscription à été modifié pour vérifier les saisies côté client -->
    <div id="homepage" style="min-height:400px">
	<div class="modal-dialog">
			<div class = "modal-content">
				<div class="modal-header">
				<h4>Créer un compte</h4>
				</div>
				<div class="modal-body">
					<form id="formInscription" method="post" action="enregistrer.php" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div>
							<label for="login">Login:</label>
							<input id="login" name="loginbdd" type="text" maxlength="100" required pattern="[A-Za-z0-9._\- ]{2,100}" title="3 à 100 caractères : lettres, chiffres, . _ - et espaces autorisés" />
							<br/>
						</div>
						<div>
							<label for="password">Password:</label>
							<input id="password" name="passwordbdd" type="password" minlength="8" maxlength="100" required title="Mot de passe (min 8 caractères)" />
							<br/>
						</div>
						<div>
							<label for="email">Email:</label>
							<input id="email" name="emailbdd" type="email" maxlength="200" required />
							<br/>
						</div>

						<div>
							<label for="nombdd">Nom:</label>
							<input id="nombdd" name="nombdd" type="text" placeholder="Nom" maxlength="50" pattern="[A-Za-z'\- ]{1,50}" required title="Lettres et - ' uniquement (max 50)" />
							<br/>
						</div>
						<div>
							<label for="prenombdd">Prénom:</label>
							<input id="prenombdd" name="prenombdd" type="text" placeholder="Prénom" maxlength="50" pattern="[A-Za-z'\- ]{0,50}" title="Lettres et - ' uniquement" />
							<br/>
						</div>
						<div>
							<label for="datebdd">Date de Naissance:</label>
							<input id="datebdd" name="datebdd" type="date" placeholder="Date de Naissance" />
						</div>
						<div>
							<br/>
							<label for="telephonebdd">Telephone:</label>
							<input id="telephonebdd" name="telephonebdd" type="tel" maxlength="20" pattern="\+?[0-9\s\-]{9,20}" title="Numéro de téléphone (9 à 20 chiffres, espaces, - et + autorisés)" />
							<br/>
						</div>
						<div>
							<label for="adressebdd">Adresse:</label>
							<textarea id="adressebdd" name="adressebdd" placeholder="Adresse" maxlength="500" rows="3"></textarea>
							<br/>
						</div>
						<div>
							<label for="villebdd">Ville:</label>
							<input id="villebdd" name="villebdd" type="text" placeholder="Ville" maxlength="100" />
							<br/>
						</div>
						<div>
							<label for="codepostalbdd">Code Postal:</label>
							<input id="codepostalbdd" name="codepostalbdd" type="text" placeholder="Code Postal" maxlength="20" pattern="[0-9A-Za-z \-]{2,20}" title="Code postal valide" />
							<br/>
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
									echo '<li>'.htmlspecialchars($item, ENT_QUOTES, 'UTF-8').'</li>';
								}
							}
							echo '</ul>';
							unset($_SESSION["inscription"]);
						?>
						</div>
					</form>
				</div>
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