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
					
									if(isset($row["LOGIN"])){
										echo "					
										<table width='30%'>
										<tr>
											<th><hr></th>
										</tr>
										<tr>
											<td><p><strong>Email</strong></p></td><td>".$email."</td>
										</tr>
										<tr>
											<td><p><strong>Nom</strong></p></td><td>".$nom."</td>
										</tr>
										
										<tr>
											<td><p><strong>Prénom</strong></p></td><td>".$prenom."</td>
										</tr>
										<tr>
											<td><p><strong>Date de Naissance</strong></p></td><td>".$date."</td>
										</tr>
										<tr>
											<td><p><strong>Telephone</strong></p></td><td>".$telephone."</td>
										</tr>
										
										<tr>
											<td><p><strong>ADRESSEe</strong></p></td><td>".$ADRESSEe."</td>
										</tr>
										<tr>
											<td><p><strong>Ville</strong></p></td><td>".$ville."</td>
										</tr>
										<tr>
											<td><p><strong>Code Postal</strong></p></td><td>".$codepostal."</td>
										</tr>
										<tr>
										<tr>
											<td><p><strong>Sexe</strong></p></td><td>".$sexe."</td>
										</tr>
											<td><a href='editer.php'  data-toggle='modal' class='list-group-item'>Éditer</a></td><td></td>
										</tr>
										</table>";
									}
									else{
										echo "<font color='grey'>Connectez vous pour afficher cette page</font>";
									}
								?>
							</section>
						</div>		

					</div>
				</div>
			</div>
	<!-- Footer -->
<?php include_once 'footer.php';?>

	</body>
</html>