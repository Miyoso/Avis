<?php
	session_start();
	include 'fonctions/fonctionsLayout.php';
	if(isset($_SESSION["login"])){
		  include("Parametres.php");
		  include("Fonctions.inc.php");
		  include("Donnees.inc.php");
		  $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		  mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
				$str = "SELECT LOGIN,EMAIL,PASS,NOM,PRENOM,DATE,SEXE,ADRESSE,CODEP,VILLE,TELEPHONE FROM USERS WHERE LOGIN = '".$_SESSION["login"]."'";
				$result = query($mysqli,$str) or die("Impossible de se connecter");
				$row = mysqli_fetch_assoc($result);
				if(is_null($row["LOGIN"])){$login = "";}else{$login = $row["LOGIN"];}
				if(is_null($row["EMAIL"])){$email = "";}else{$email = $row["EMAIL"];}
				if(is_null($row["NOM"])){$nom = "";}else{$nom = $row["NOM"];}
				if(is_null($row["PRENOM"])){$prenom = "";}else{$prenom = $row["PRENOM"];}
				if(is_null($row["DATE"])){$date = "";}else{$date = $row["DATE"];}
				if(is_null($row["TELEPHONE"])){$telephone = "";}else if((int)$row["TELEPHONE"] == 0){ $telephone = NULL;}else{$telephone = $row["TELEPHONE"];}
				if(is_null($row["ADRESSE"])){$ADRESSEe = "";}else{$ADRESSEe = $row["ADRESSE"];}
				if(is_null($row["CODEP"])){$codepostal = "";}else{$codepostal = $row["CODEP"];}
				if(is_null($row["VILLE"])){$ville = "";}else{$ville = $row["VILLE"];}
				if(is_null($row["SEXE"])){$sexe = "";}else{$sexe = $row["SEXE"];}
		  mysqli_close($mysqli);
	}
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
									<h2>Vos Informations</h2>
								</header>
									<?php
								echo '<form action="update.php" method="post">';
									if(isset($row["LOGIN"])){
										echo "					
										<table wnameth='30%'>
										<tr>
											<th><hr></th>
										</tr>
										<tr>
											<td><p><strong>Email</strong></p></td><td><input name='emailbdd' type=\"text\" placeholder=\"".$email."\"/></td>
										</tr>
										<tr>
											<td><p><strong>Nom</strong></p></td><td><input name='nombdd' type=\"text\" placeholder=\"".$nom."\"/></td>
										</tr>
										
										<tr>
											<td><p><strong>Prénom</strong></p></td><td><input name='prenombdd' type=\"text\" placeholder=\"".$prenom."\"/></td>
										</tr>
										<tr>
											<td><p><strong>Date de Naissance</strong></p></td><td><input name='datebdd' type=\"date\" placeholder=\"".$date."\"/></td>
										</tr>
										<tr>
											<td><p><strong>Telephone</strong></p></td><td><input name='telephonebdd' type=\"text\" placeholder=\"".$telephone."\"/></td>
										</tr>
										
										<tr>
											<td><p><strong>ADRESSE</strong></p></td><td><textarea name='ADRESSEebdd' rows=\"4\" placeholder=\"".$ADRESSEe."\"></textarea></td>
										</tr>
										<tr>
											<td><p><strong>Ville</strong></p></td><td><input name='villebdd' type=\"text\" placeholder=\"".$ville."\"/></td>
										</tr>
										<tr>
											<td><p><strong>Code Postal</strong></p></td><td><input name='postalbdd' type=\"text\" placeholder=\"".$codepostal."\"/></td>
										</tr>
										<tr>
										<tr>
											<td><p><strong>Sexe</strong></p></td><td><input type='radio' name='optradio' value='Femme'/>Femme     <input type='radio' name='optradio' checked='' value='Homme'/>Homme</td>
										</tr>
										<tr>
											<td colspan='2'><hr></td>
										</tr>
											<td><input type='submit' value='Valider'/></td>
										</tr>
										</table>";
									echo '</form>';
									}
									else{
										echo "<font color='grey'>Connectez vous pour afficher cette page</font>";
									}
								?>
							</section>
						</div>						

						<!-- Sidebar -->
						<div id="sidebar" class="4u">
							<section>
								<header class="major">
									<h2>Profil</h2>
								</header>									
								<ul class="default">
								<?php afficherCadreCompte(); ?>
								</ul>
							</section>
						</div>

					</div>
				</div>
			</div>
		
		<!-- Team -->
			<div class="wrapper style5">
				<section id="team" class="container">
					<header class="major">
						<h2>Cras vitae metus aliNuam</h2>
						<span class="byline">pulvinar mollis. Vestibulum sem magna, elementum vestibulum arcu</span>
					</header>
					<div class="row">
						<div class="3u">
							<a href="#" class="image"><img src="images/placeholder.png" alt=""></a>
							<h3>Molly Millions</h3>
							<p>In posuere eleifend odio quisque semper augue wisi ligula.</p>
						</div>
						<div class="3u">
							<a href="#" class="image"><img src="images/placeholder.png" alt=""></a>
							<h3>Henry Dorsett Case</h3>
							<p>In posuere eleifend odio quisque semper augue wisi ligula.</p>
						</div>
						<div class="3u">
							<a href="#" class="image"><img src="images/placeholder.png" alt=""></a>
							<h3>Willis Corto</h3>
							<p>In posuere eleifend odio quisque semper augue wisi ligula.</p>
						</div>
						<div class="3u">
							<a href="#" class="image"><img src="images/placeholder.png" alt=""></a>
							<h3>Linda Lee</h3>
							<p>In posuere eleifend odio quisque semper augue wisi ligula.</p>
						</div>
					</div>
				</section>
			</div>

	<!-- Footer -->
		<div id="footer">
			<section class="container">
				<header class="major">
					<h2>Contactez nous</h2>
				</header>
				<ul class="icons">
					<li class="active"><a href="#" class="fa fa-facebook"><span>Facebook</span></a></li>
					<li><a href="#" class="fa fa-twitter"><span>Twitter</span></a></li>
					<li><a href="#" class="fa fa-dribbble"><span>Pinterest</span></a></li>
					<li><a href="#" class="fa fa-google-plus"><span>Google+</span></a></li>
				</ul>
			</section>		
		</div>

	</body>
</html>