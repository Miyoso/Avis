<?php
$file_result = '';
session_start();
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Erreur CSRF : formulaire non autorisé.");
}

// Vérification de l'erreur d'upload système
if($_FILES['file']['error'] > 0){

    $file_result = 'Error'; // Ou 'Error_upload' pour distinguer

} else {

    $file_info = $_FILES['file'];

    // Si une erreur est survenue pendant l'upload, affichez-la
    if ($file_info['error'] !== UPLOAD_ERR_OK) {
        $error_message = match ($file_info['error']) {
            UPLOAD_ERR_INI_SIZE => 'Erreur: Le fichier est trop grand (ini_size).',
            UPLOAD_ERR_FORM_SIZE => 'Erreur: Le fichier est trop grand (form_size).',
            UPLOAD_ERR_PARTIAL => 'Erreur: Fichier partiellement uploadé.',
            UPLOAD_ERR_NO_FILE => 'Erreur: Aucun fichier n\'a été envoyé.',
            default => 'Erreur: Erreur d\'upload inconnue (' . $file_info['error'] . ')',
        };
        // Arrêtez le script et affichez l'erreur pour diagnostiquer
        die("Erreur d'Upload : " . $error_message);
    }

    // Vérifiez la taille du fichier temporaire (il devrait être > 0)
    if (!is_uploaded_file($file_info['tmp_name']) || filesize($file_info['tmp_name']) === 0) {
        die("Erreur d'Upload: Le fichier temporaire est vide ou invalide.");
    }
    // --- LOGIQUE D'UPLOAD SÉCURISÉ (Propale Correc) ---
    $allowed = ['jpg','jpeg','png','bmp'];
    $baseName = basename($_FILES['file']['name']);
    $ext = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));

    // Vérifie si l'extension est autorisée ET si c'est bien un fichier uploadé
    if (in_array($ext, $allowed, true) && is_uploaded_file($_FILES['file']['tmp_name'])) {

        // Chemin vers le dossier 'images' (monte d'un niveau, puis va dans 'images')
        $targetDir = '../images/';

        // Crée le dossier si besoin
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        $targetPath = $targetDir . $baseName;

        // Déplacement du fichier
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $file_result = 'images\\' . $baseName; // Succès, chemin pour la BD
        } else {
            $file_result = 'Error'; // Échec du déplacement
        }
    } else {
        $file_result = 'Error'; // Fichier non autorisé
    }
		include("../Parametres.php");
		include("../Fonctions.inc.php");
		include("../Donnees.inc.php");

		//MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		$mysqli=mysqli_connect($host,$user,$pass) or die("Une erreur est survenue.");
        //MODIF mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
		mysqli_select_db($mysqli,$base) or die("Une erreur est survenue.");
        // ESSAI
        $mysqli->set_charset('utf8mb4');
        $mysqli->query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci'");

        if(isset($_POST["libelle"]) && isset($_POST["prix"]) && isset($_POST["descriptif"])){

			$ok = true;
            // MODIF
			/* if(!preg_match('/^([A-Za-z]{0,80}$)/', $_POST["libelle"])){
				$ok = false;
			}

			if(!preg_match('/^([0-9]+$)/', $_POST["prix"])){
					$ok = false;
			}*/
            if (!preg_match('/^[\p{L}0-9\s.,\'"-]{1,80}$/u', $_POST["libelle"])) {
                $ok = false;
            }

            if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $_POST["prix"])) {
                $ok = false;
            }
			
				if($ok){
					$libelle = mysqli_real_escape_string($mysqli,$_POST["libelle"]);
					$prix = mysqli_real_escape_string($mysqli,$_POST["prix"]);
					$descriptif = mysqli_real_escape_string($mysqli,$_POST["descriptif"]);
					$rubrique = mysqli_real_escape_string($mysqli,$_POST["rubrique"]);

                    //Cross-site Scripting (XSS)
                    //ligne a corriger
					//query($mysqli,"replace into `produits` (`Libelle`,`Prix`,`descriptif`,`photo`) values ('".$libelle."','".$prix."','".$descriptif."','".$file_result."')");

                    //Propal corr

                    $stmt = $mysqli->prepare("REPLACE INTO `PRODUITS` (`LIBELLE`,`PRIX`,`DESCRIPTIF`,`PHOTO`) VALUES (?, ?, ?, ?)");
                    if ($stmt === false) {
                        error_log("Erreur préparation REPLACE produits: " . $mysqli->error);
                        die("Une erreur est survenue. Merci de réessayer plus tard.");
                    }

                    $prix_val = floatval($prix);
                    $photo_safe = basename($file_result);

                    if (!$stmt->bind_param('sdss', $libelle, $prix_val, $descriptif, $photo_safe)) {
                        error_log("Erreur bind_param REPLACE produits: " . $stmt->error);
                        $stmt->close();
                        die("Une erreur est survenue. Merci de réessayer plus tard.");
                    }

                    if (!$stmt->execute()) {
                        error_log("Erreur execution REPLACE produits: " . $stmt->error . " -- libelle: " . $libelle);
                        $stmt->close();
                        die("Une erreur est survenue lors de l'enregistrement. Merci de réessayer.");
                    }

                    $stmt->close();

					query($mysqli,'insert into APPARTIENT (id_prod,id_rub) values ((select max(id_prod) from PRODUITS),(select id_rub from RUBRIQUES where libelle_rub = \''.$rubrique.'\'))');
                    echo "Enregistrement reussi";
				}
				else
				{
					echo "Erreur1";
				}
						
				
		
		}else{
			echo "Erreur2";
		}
		
		mysqli_close($mysqli);
	}
	
	//libelle, prix, descriptif, image
?>