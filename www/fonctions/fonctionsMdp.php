<?php
// AJOUT pour remplacer MD5 plus d'actualité
function generer_mdp(int $longueur = 8): string {
    $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($alphabet) - 1;
    $motdepasse = '';
    for ($i = 0; $i < $longueur; $i++) {
        $motdepasse .= $alphabet[random_int(0, $max)];
    }
    return $motdepasse;
}
	//verification de l'email inseree
	if(isset($_POST["email"]) && preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$#', $_POST["email"])){
		include("../Parametres.php");
		include("../Fonctions.inc.php");

        // Ancien (vulnérable) :
        // $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
        // mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");

        // Début Correction
        $mysqli = @mysqli_connect($host, $user, $pass);
        if (!$mysqli) {
            // ne pas l'afficher à l'utilisateur
            error_log('DB connection failed: ' . mysqli_connect_error());
            // message simple pour l'utilisateur
            echo "Erreur serveur, veuillez réessayer plus tard.";
            exit;
        }

        if (!@mysqli_select_db($mysqli, $base)) {
            error_log('DB select failed: ' . mysqli_error($mysqli));
            echo "Erreur serveur, veuillez réessayer plus tard.";
            mysqli_close($mysqli);
            exit;
        }


		$result = query($mysqli,'select login,prenom,nom,email,adresse,ville,telephone from USERS where login = \'admin\'');
		//creation d'un nouveau mot de pass
        $newpass = generer_motdepasse(8);
		$message = "Monsieur, Madame, \n\n\n Votre mot de pass pour le site TaupeAchat est maintenant: ".$newpass.".\n\n\nBien cordialement, l'equipe Taupe Achat.";
		$val = mail($_POST["email"],"TaupeAchat - Nouveau mot de pass",$message,"From: noreply@taupeachat.com");
		
		//verification si l'email a été envoyé
		if($val) {
            //Vuln ligne naze SQL Injection
            //$result = mysql_query("insert into users (PASS) values ('".password_hash(($newpass), PASSWORD_DEFAULT)."') where email='".$_POST["email"]."'");

            //correc

            $stmt = $mysqli->prepare("UPDATE USERS SET PASS = ? WHERE email = ?");
            $stmt->bind_param("ss", password_hash($newpass, PASSWORD_DEFAULT), $_POST["email"]);
            $stmt->execute();
            $stmt->close();

            echo "Nouveau mot de pass envoyé";
		}else{
			
			//MODIF echo "Erreur : Email invalide"; Donne des indications aux attaquants
			echo "Si l'adresse email est enregistrée, un nouveau mot de passe a été envoyé.";

		}
		mysqli_close($mysqli);
	}else{
		
		echo "Erreur";
		
	}

?>