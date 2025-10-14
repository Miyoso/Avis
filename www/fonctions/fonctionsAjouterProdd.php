<?php
	$file_result = '';
	if($_FILES['file']['error']>0 && (!preg_match("/.jpg$/",$_FILES['file']['name']) || !preg_match("/.png$/",$_FILES['file']['name']) || !preg_match("/.bmp$/",$_FILES['file']['name']) || !preg_match("/.jpeg$/",$_FILES['file']['name']))){
		
		$file_result = 'Error';
		
	}else{
        //ligne inco
//		$file_result = 'images/'.$_FILES['file']['name'];
//		move_uploaded_file($_FILES['file']['tmp_name'],'../'.$file_result);

        //propal correc
        //Path Traversal
        $allowed = ['jpg','jpeg','png','bmp'];
        $baseName = basename($_FILES['file']['name']);
        $ext = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed, true) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $file_result = 'images/' . $baseName;
            move_uploaded_file($_FILES['file']['tmp_name'], '../' . $file_result);
        } else {
            $file_result = 'Error';
        }

		include("../Parametres.php");
		include("../Fonctions.inc.php");
		include("../Donnees.inc.php");

		//MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
		$mysqli=mysqli_connect($host,$user,$pass) or die("Une erreur est survenue.");
        //MODIF mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
		mysqli_select_db($mysqli,$base) or die("Une erreur est survenue.");

		if(isset($_POST["libelle"]) && isset($_POST["prix"]) && isset($_POST["descriptif"])){
			
			$ok = true;
			if(!preg_match('/^([A-Za-z]{0,80}$)/', $_POST["libelle"])){
				$ok = false;
			}
		
			if(!preg_match('/^([0-9]+$)/', $_POST["prix"])){
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

                    $stmt = $mysqli->prepare("REPLACE INTO `PRODUITS` (`Libelle`,`Prix`,`descriptif`,`photo`) VALUES (?, ?, ?, ?)");
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

					query($mysqli,'insert into appartient (id_prod,id_rub) values ((select max(id_prod) from produits),(select id_rub from rubrique where libelle_rub = \''.$rubrique.'\'))');
					echo "Engretrement reussi";
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