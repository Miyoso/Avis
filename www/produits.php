<?php
session_start();
include 'fonctions/fonctionsLayout.php';
include 'fonctions/fonctionsProduits.php';
include("Parametres.php");
include_once 'Fonctions.inc.php';
include_once 'Donnees.inc.php';
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$mysqli = mysqli_connect($host, $user, $pass, $base) or die("Erreur de connexion : " . mysqli_connect_error());
mysqli_select_db($mysqli, $base) or die("Une erreur est survenue.");

$rubrique = $_GET['rubrique'] ?? '';
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
					   data: {id : e,
                              csrf_token: '<?= $csrf_token ?>'
                       },
					   success: function(data)
							{
								alert(data);
								location.reload();
							}
						});
		}
		</script>
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
                    data: {item : e,
                           csrf_token: '<?= $csrf_token ?>'
                    },
                    success: function(data){
                        alert(data);
                    },
                });
            };

            function addFav(e){
                $.ajax({
                    type: 'POST',
                    url: 'fonctions/fonctionsFav.php',
                    data: {item : e,
                           csrf_token: '<?= $csrf_token ?>'
                    },
                    success: function(data){
                        alert(data);
                    },
                });
            };
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
                                <?php
                                // Ancien code (vulnérable) :
                                // $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
                                // mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base")
                                // Exemple : récupérer toutes les rubriques existantes
                                if ($rubrique) {
                                    // Récupère uniquement les produits de la rubrique sélectionnée
                                    $stmt = $mysqli->prepare("
                                        SELECT 
                                            p.id_prod AS id,
                                            p.Libelle AS lib,
                                            p.Photo AS photo,
                                            p.Prix AS prix,
                                            p.Descriptif AS descr
                                        FROM PRODUITS p
                                        JOIN APPARTIENT a ON a.id_prod = p.id_prod
                                        JOIN RUBRIQUES r ON a.id_rub = r.id_rub
                                        WHERE r.Libelle_rub = ?
                                    ");
                                    $stmt->bind_param("s", $rubrique);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    echo '<h2>' . htmlspecialchars($rubrique, ENT_QUOTES, 'UTF-8') . '</h2>';
                                    echo '<div class="wrapper style5"><section id="team" class="container"><div class="row">';

                                    $temp = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        // Nettoyage des chemins (remplacement des "\" par "/")
                                        $photoPath = str_replace('\\', '/', $row["photo"]);

                                        echo '<div class="3u">';
                                        echo '<a href="#" onclick="addPanier(\'' . $row["id"] . '\')"><img src="images/13336.gif" style="height:30px;"/></a> ';
                                        echo '<a href="#" onclick="addFav(\'' . $row["id"] . '\')"><img src="images/favorite_add.png" style="height:40px;"/></a><br/>';
                                        echo '<img src="' . htmlspecialchars($photoPath, ENT_QUOTES, 'UTF-8') . '" class="Image"/>';
                                        echo '<h3 style="color:grey">' . htmlspecialchars($row["lib"], ENT_QUOTES, 'UTF-8') . '</h3>';
                                        echo '<p style="color:grey">' . htmlspecialchars($row["descr"], ENT_QUOTES, 'UTF-8') . '</p>';
                                        echo '<p style="color:black">Prix :'.$row["prix"].'€</p>';
                                        echo '</div>';

                                        $temp++;
                                        if ($temp == 4) {
                                            echo '</div><div class="row">';
                                            $temp = 0;
                                        }
                                    }

                                    echo '</div></section></div>';
                                } else {
                                    // Pas de rubrique sélectionnée : afficher tous les produits
                                    afficherProduits($mysqli);
                                }

                                mysqli_close($mysqli);
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