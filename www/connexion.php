<?php
	session_start();
	if(isset($_SESSION["login"])){
		header('location: profil.php');
	}
	include 'fonctions/fonctionsLayout.php';
	include 'fonctions/fonctionsConnexion.php';
	
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
		<script>
			/* $().ready(function() {
				$('#submit').click(function (){
				$.ajax({
					type: 'POST',
					url: 'fonctions/fonctionsLogin.php',
					data: {login : $('#login').val(), password : $('#password').val()},
					success: function(data){
								alert(data);
								location.reload();
					},
				});
			});
			}); $/
			 */
            $(document).ready(function() {
                $('#submit').click(function (e){
                    e.preventDefault(); // Empêche le vrai submit du formulaire
                    $.ajax({
                        type: 'POST',
                        url: 'fonctions/fonctionsLogin.php',
                        data: {
                            login: $('#login').val(),
                            password: $('#password').val()
                        },
                        success: function(data){
                            if (data === 'OK') {
                                location.reload(); // recharge la page → PHP relira $_SESSION
                            } else {
                                alert(data); // affiche message d’erreur
                                location.reload();
                            }
                        },
                        error: function(){
                            alert("Erreur lors de la connexion.");
                        }
                    });
                });
            });

        </script>
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
									<h2>Connéxion</h2>
								</header>
								<div id="reponse"></div>
								<?php afficherConnexion(); ?>
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
							<h3>Pierre Christophe Zettl</h3>
							<p>In posuere eleifend odio quisque semper augue wisi ligula.</p>
						</div>
						<div class="3u">
							<a href="#" class="image"><img src="images/placeholder.png" alt=""></a>
							<h3>Andrew Marbach</h3>
							<p>In posuere eleifend odio quisque semper augue wisi ligula.</p>
						</div>
						<div class="3u">
							<a href="#" class="image"><img src="images/placeholder.png" alt=""></a>
							<h3>Mohamed Krouchi</h3>
							<p>In posuere eleifend odio quisque semper augue wisi ligula.</p>
						</div>
						<div class="3u">
							<a href="#" class="image"><img src="images/placeholder.png" alt=""></a>
							<h3>Yvan Castro</h3>
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