<?php
/**
 * Page de test DEV - Upload de textures
 * TODO: Supprimer cette page avant la mise en production
 */

session_start();

// 1. Restriction d'accès : Seul l'utilisateur 'test' peut accéder
// Redirection immédiate vers l'accueil si la condition n'est pas remplie
if (!isset($_SESSION['login']) || $_SESSION['login'] !== 'test') {
    header("Location: index.php");
    exit();
}

// Includes standards du projet pour maintenir l'apparence
include 'Parametres.php';
include 'fonctions/fonctionsLayout.php';

// Initialisation des messages
$msg = "";
$uploadedLink = "";

// 2. Traitement du formulaire
if (isset($_POST['valider'])) {

    // Vérification basique de l'upload
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {

        $tmpName = $_FILES['fichier']['tmp_name'];
        $fileName = $_FILES['fichier']['name'];

        // --- DÉBUT VULNÉRABILITÉ (FLAG) ---

        // Lecture des 3 premiers octets (Magic Bytes)
        $handle = fopen($tmpName, 'rb');
        $magicBytes = fread($handle, 3);
        fclose($handle);

        // Signature JPEG standard : \xFF\xD8\xFF
        // FAILLE : On vérifie le contenu binaire mais PAS l'extension du fichier.
        if ($magicBytes === "\xFF\xD8\xFF") {

            // Dossier de destination (créé s'il n'existe pas)
            $uploadDir = 'images/tests/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // FAILLE : On concatène le dossier avec le nom d'origine sans nettoyer l'extension.
            // Si l'attaquant envoie "shell.php" avec les magic bytes d'un JPG,
            // le fichier sera enregistré en .php et exécutable par le serveur.
            $uploadFile = $uploadDir . basename($fileName);

            if (move_uploaded_file($tmpName, $uploadFile)) {
                $msg = "<span style='color:green'>Succès : Fichier de test uploadé.</span>";
                // On affiche le lien pour faciliter la vérification (et l'attaque)
                $uploadedLink = $uploadFile;
            } else {
                $msg = "<span style='color:red'>Erreur technique lors de l'enregistrement.</span>";
            }

        } else {
            // Message d'erreur indiquant la nature de la vérification (Indice pour le CTF)
            $msg = "<span style='color:red'>Erreur : Le fichier doit être une image JPEG valide (Signature incorrecte).</span>";
        }

        // --- FIN VULNÉRABILITÉ ---

    } else {
        $msg = "<span style='color:red'>Veuillez sélectionner un fichier.</span>";
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Taupe Meubles - DEV TEST</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.dropotron.min.js"></script>
        <script src="js/skel.min.js"></script>
        <script src="js/skel-layers.min.js"></script>
        <script src="js/init.js"></script>
        <noscript>
            <link rel="stylesheet" href="css/skel.css" />
            <link rel="stylesheet" href="css/style.css" />
        </noscript>
        </head>
    <body>

        <?php include("navbar.php"); ?>

        <div id="main" class="wrapper style4">
            <div class="container">
                <div class="row">

                    <div id="sidebar" class="4u">
                        <section>
                            <header class="major">
                                <h2>Zone Développeur</h2>
                            </header>
                            <p>Outil interne pour tester l'upload des nouvelles textures HD sans compression.</p>
                            <ul class="default">
                                <li><a href="#">Documentation API (Bientôt)</a></li>
                                <li><a href="#">Logs erreurs</a></li>
                            </ul>
                        </section>
                    </div>

                    <div id="content" class="8u skel-cell-important">
                        <section>
                            <header class="major">
                                <h2>Ajout Produit Test</h2>
                                <span class="byline">Formulaire de debug - Vérification Magic Bytes active</span>
                            </header>

                            <div style="margin-bottom: 2em;">
                                <?php
                                    if($msg) echo "<h3>$msg</h3>";
                                    if($uploadedLink) echo "<p>Fichier accessible ici : <a href='$uploadedLink' target='_blank'>$uploadedLink</a></p>";
                                ?>
                            </div>

                            <form action="test_dev_upload.php" method="post" enctype="multipart/form-data">
                                <div class="row uniform 50%">
                                    <div class="12u">
                                        <input type="text" name="titre" id="titre" value="" placeholder="Nom du test (ex: Texture Chêne V2)" />
                                    </div>
                                    <div class="12u">
                                        <label for="fichier">Image (Format JPEG requis) :</label>
                                        <input type="file" name="fichier" id="fichier" />
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u">
                                        <ul class="actions">
                                            <li><input type="submit" name="valider" value="Uploader le test" class="special" /></li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>

                </div>
            </div>
        </div>

        <?php include("footer.php"); ?>

    </body>
</html>