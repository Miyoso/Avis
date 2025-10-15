<?php
include("Parametres.php");
include("Fonctions.inc.php");
include("Donnees.inc.php");

//MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
$mysqli=mysqli_connect($host,$user,$pass) or die("Une erreur est survenue.");
//MODIF mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
mysqli_select_db($mysqli,$base) or die("Une erreur est survenue.");


$ok = true;
$result["msg"] = "invalide";


		  if((isset($_POST["loginbdd"])) && (isset($_POST["passwordbdd"]))){			  
			  if(empty($_POST["loginbdd"]) || empty($_POST["passwordbdd"])){
				$return["pass"] = "le mot de pass est très court";
			    $return["loginVal"] = "le login n'est pas valid";
				$ok = false;
			  }
			  else{
				 $pass = mysqli_real_escape_string($mysqli,$_POST["passwordbdd"]);
				 $login = mysqli_real_escape_string($mysqli,$_POST["loginbdd"]);
				 $matches[] = NULL;
				 if(!preg_match("/^[a-zA-Z'\-\_0-9 ]+$/",$_POST["loginbdd"])){
							  $return["loginVal"] = "le login n'est pas valid";
							  $login = NULL;
							
					  }
					  
				  
				  if(strlen($login)>100){
					  $return["loginLong"] = "l'login est trop long";
					  $ok = false;
				  }
				  
				  if(strlen($pass)>100){
					  $return["passLong"] = "le mot de pass est trop long";
					  $ok = false;
				  }
				  
			  }
			  
		  }
		  else{
			   $return["loginVal"] = "l'login n'est pas valid";;
			   $return["passVal"] = "le mot de pass n'est valid";
			   $ok = false;
		  }
			
		  	if(isset($_POST["emailbdd"])){
				if(!filter_var($_POST["emailbdd"], FILTER_VALIDATE_EMAIL)){
					  $return["emailVal"] = "l'email n'est pas valid";
					  $email = NULL;
				}
				else{
					$email = $_POST["emailbdd"];
				}
			}else{
				$email = NULL;
			}
		  
		 if(isset($_POST["nombdd"])){
			  if(empty($_POST["nombdd"])){
				  $return["Nom"] = "le Nom n'est pas valid";
				  $nom = NULL;
			  }
			  else{
				  $nom = mysqli_real_escape_string($mysqli,$_POST["nombdd"]);
				  if(!preg_match("/^[a-zA-Z'\- ]+$/",$_POST["nombdd"])){
					  $return["Nom"] = "le Nom n'est pas valid";
					  $nom = NULL;
				  }else if(strlen($nom)>50){
					  $return["Nom"] = "le Nom est trop long";
					   $ok = false;
				  }
			  } 
		  }else{
			  $nom = NULL;
		  }
		  
		  if(isset($_POST["prenombdd"])){
			  if(empty($_POST["prenombdd"])){
				  $prenom = NULL;
			  }
			  else{
				  $prenom = mysqli_real_escape_string($mysqli,$_POST["prenombdd"]);
				  if(!preg_match("/^[a-zA-Z'\- ]+$/",$_POST["prenombdd"])){
					  $return["Prenom"] = "le Prénom n'est pas valid";
					  $prenom = NULL;
				  }else if(strlen($prenom)>50){
					  $return["Prenom"] = "le Prénom est trop long";
					   $ok = false;
				  }
			  } 
		  }
		  else{
			  $prenom = NULL;
		  }
		  
		  if(isset($_POST["adressebdd"])){
			  if(empty($_POST["adressebdd"])){
			  $adresse = NULL;
			}else{
				$adresse = mysqli_real_escape_string($mysqli,$_POST["adressebdd"]);
				if(strlen($adresse)>500){
				$return["Adresse"] = "L'adresse n'est pas valide";
				$ok = false;
				}
			}
		  }else{
			  $adresse = NULL;
		  }
		
		
		if(isset($_POST["villebdd"])){
			  if(empty($_POST["villebdd"])){
			  $ville = NULL;
			}else{
				$ville = mysqli_real_escape_string($mysqli,$_POST["villebdd"]);
				if(strlen($ville)>50){
				$return["ville"] = "La ville n'est pas valide";
				$ok = false;
				}
			}
		  }
		  else{
			  $ville = NULL;
		  }
		  
		if(isset($_POST["codepostalbdd"])){
			  if(empty($_POST["codepostalbdd"])){
			  $codepostal = NULL;
			}else{
				$codepostal = mysqli_real_escape_string($mysqli,$_POST["codepostalbdd"]);
				if(strlen($codepostal)>50){
				$return["codepostal"] = "le code postal n'est pas valid";
				$ok = false;
				}
			}
		  }else{
			  $codepostal = NULL;
		  }
		  
		if(isset($_POST["datebdd"])){
			 if(empty($_POST["datebdd"])){
			  $date = NULL;
			}else{
				$date = mysqli_real_escape_string($mysqli,$_POST["datebdd"]);
				if(strlen($date)>50){
				$return["date"] = "le code postal n'est pas valid";
				$ok = false;
				}
			}
		  }
		  else{
			  $date = NULL;
		  }
		  
		 if(isset($_POST["telephonebdd"])){
			  if(!preg_match("/^[0-9]{9,15}$/",$_POST["telephonebdd"],$matches)){
					  $return["telephoneVal"] = "le telephone n'est pas valid";
					  $telephone = NULL;
			}
			else{
				$telephone = $_POST["telephonebdd"];
			}
		  }else{
			  $telephone = NULL;
		  }
		  	 
		  
		  
		  if(isset($_POST["optradio"])){
			  $sexe = $_POST["optradio"];
		  }else{
			  $sexe = NULL;
		  }
		//-------------------------------------------------------------------------------------  
		 if(isset($_POST["datebdd"]) && preg_match("/^[0-9\-\/ ]+$/",$_POST["datebdd"])){
			  $date = $_POST["datebdd"];
		 }else 
			 if(isset($_POST["datebdd"])){
					$data = explode("/",$_POST["datebdd"]);
					if(isset($data[1]) && isset($data[0]) && isset($data[2])){
							if(checkdate((int)$data[1],(int)$data[0],(int)$data[2])){
							  $date = trim(mysqli_real_escape_string($mysqli,$_POST["datebdd"])); 
						}
					}
					
				$date = NULL;
		}
		  if(isset($login)){
				  $str = "SELECT EMAIL FROM USERS WHERE login = '".$login."'";
			  $result = query($mysqli,$str) or die("Impossible de creer un compte pour le moment<br>");
			  if(mysqli_num_rows($result)>0){
				  $ok = false;
				  $return["dejaEmail"] = "l'email saisi est déjà enregistré";
			  }
			  
			  
			  $str = "SELECT LOGIN FROM USERS WHERE LOGIN = '".$login."'";
			  $result = query($mysqli,$str) or die("Impossible de creer un compte pour le moment<br>");
			  if(mysqli_num_rows($result)>0){
				  $ok = false;
				  $return["dejaLogin"] = "le login saisi est déjà enregistré";
			  }
		  }else{
			  $ok = false;
		  }



	if($ok == true){
				  $str = "INSERT INTO USERS VALUES ('".$login."','".$email."','".password_hash($pass, PASSWORD_DEFAULT)."','".$nom."','".$prenom."','".$date."','".$sexe."','".$adresse."','".$codepostal."','".$ville."','".$telephone."');";

				  //Cross-site Scripting (XSS)
				  // query($mysqli,$str) or die("Impossible de creer une compte dans ce moment<br>");
				  function query($link, $query){
    					$resultat = mysqli_query($link, $query);
    					if (!$resultat) {
        				// Log de l'erreur technique côté serveur
        					error_log("MySQL Error: " . mysqli_error($link) . " | Query: " . $query);

        				// Affichage à l'utilisateur d'un message générique, sécurisé contre XSS
        				die("Une erreur est survenue. Veuillez réessayer plus tard.");
    }
    return $resultat; }
                    setcookie(
                        "user",
                        $login,
                        [
                            "expires"  => 0,
                            "path"     => "/",
                            "secure"   => false,
                            "httponly" => true,
                            "samesite" => "Lax"
                        ]
                    );
				  unset($return);
				  $return["msg"] = "Opération réussie";
	}




$return["ok"] = false;
mysqli_close($mysqli);	
echo json_encode($return);
exit();

?>
