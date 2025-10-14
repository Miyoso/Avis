<?php
session_start();
include("Parametres.php");
include("Fonctions.inc.php");
include("Donnees.inc.php");

//MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
$mysqli = mysqli_connect($host, $user, $pass) or die("Une erreur est survenue.");
//MODIF mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
mysqli_select_db($mysqli, $base) or die("Une erreur est survenue.");


$ok = true;
$result["msg"] = "invalide";


if ((isset($_POST["loginbdd"])) && (isset($_POST["passwordbdd"]))) {
    if (empty($_POST["loginbdd"]) || empty($_POST["passwordbdd"])) {
        $return["pass"] = "le mot de pass est très court";
        $return["loginVal"] = "le login n'est pas valid";
        $ok = false;
    } else {
        $pass = mysqli_real_escape_string($mysqli, $_POST["passwordbdd"]);
        $login = mysqli_real_escape_string($mysqli, $_POST["loginbdd"]);
        $matches[] = NULL;
        if (!preg_match("/^[a-zA-Z'\-\_0-9 ]+$/", $_POST["loginbdd"])) {
            $return["loginVal"] = "le login n'est pas valid";
            $login = NULL;

        }


        //MODIF if (sizeof($login) > 100) {
        if (strlen($login) > 100) {
            $return["loginLong"] = "le login est trop long";
            $ok = false;
        }

        //MODIF if (sizeof($pass) > 100) {
        if (strlen ($pass) > 100) {
            $return["passLong"] = "le mot de pass est trop long";
            $ok = false;
        }

    }

} else {
    $return["loginVal"] = "l'login n'est pas valid";;
    $return["passVal"] = "le mot de pass n'est valid";
    $ok = false;
}

if (isset($_POST["emailbdd"])) {
    if (!filter_var($_POST["emailbdd"], FILTER_VALIDATE_EMAIL)) {
        $return["emailVal"] = "l'email n'est pas valid";
        $email = NULL;
    } else {
        $email = $_POST["emailbdd"];
    }
} else {
    $email = NULL;
}

if (isset($_POST["nombdd"])) {
    if (empty($_POST["nombdd"])) {
        $return["Nom"] = "le Nom n'est pas valid";
        $nom = NULL;
    } else {
        $nom = mysqli_real_escape_string($mysqli, $_POST["nombdd"]);
        if (!preg_match("/^[a-zA-Z'\- ]+$/", $_POST["nombdd"])) {
            $return["Nom"] = "le Nom n'est pas valid";
            $nom = NULL;
        //MODIF } else if (sizeof($nom) > 50) {
        } else if (strlen($nom) > 50) {
            $return["Nom"] = "le Nom est trop long";
            $ok = false;
        }
    }
} else {
    $nom = NULL;
}

if (isset($_POST["prenombdd"])) {
    if (empty($_POST["prenombdd"])) {
        $prenom = NULL;
    } else {
        $prenom = mysqli_real_escape_string($mysqli, $_POST["prenombdd"]);
        if (!preg_match("/^[a-zA-Z'\- ]+$/", $_POST["prenombdd"])) {
            $return["Prenom"] = "le Prénom n'est pas valid";
            $prenom = NULL;
        //MODIF } else if (sizeof($prenom) > 50) {
        } else if (strlen($prenom) > 50) {
            $return["Prenom"] = "le Prénom est trop long";
            $ok = false;
        }
    }
} else {
    $prenom = NULL;
}

if (isset($_POST["adressebdd"])) {
    if (empty($_POST["adressebdd"])) {
        $adresse = NULL;
    } else {
        $adresse = mysqli_real_escape_string($mysqli, $_POST["adressebdd"]);
        //MODIF if (sizeof($adresse) > 500) {
        if (strlen($adresse) > 500) {
            $return["Adresse"] = "L'adresse n'est pas valide";
            $ok = false;
        }
    }
} else {
    $adresse = NULL;
}


if (isset($_POST["villebdd"])) {
    if (empty($_POST["villebdd"])) {
        $ville = NULL;
    } else {
        $ville = mysqli_real_escape_string($mysqli, $_POST["villebdd"]);
        //MODIF if (sizeof($ville) > 50) {
        if (strlen($ville) > 50) {
            $return["ville"] = "La ville n'est pas valide";
            $ok = false;
        }
    }
} else {
    $ville = NULL;
}

if (isset($_POST["codepostalbdd"])) {
    if (empty($_POST["codepostalbdd"])) {
        $codepostal = NULL;
    } else {
        $codepostal = mysqli_real_escape_string($mysqli, $_POST["codepostalbdd"]);
        //MODIF if (sizeof($codepostal) > 50) {
        if (strlen($codepostal) > 50) {
            $return["codepostal"] = "le code postal n'est pas valid";
            $ok = false;
        }
    }
} else {
    $codepostal = NULL;
}

if (isset($_POST["datebdd"])) {
    if (empty($_POST["datebdd"])) {
        $date = NULL;
    } else {
        $date = mysqli_real_escape_string($mysqli, $_POST["datebdd"]);
        //MODIF (sizeof($date) > 50) {
        if (strlen($date) > 50) {
            $return["date"] = "la date n'est pas valid";
            $ok = false;
        }
    }
} else {
    $date = NULL;
}

if (isset($_POST["telephonebdd"])) {
    if (!preg_match("/^[0-9]{9,15}$/", $_POST["telephonebdd"])) {
        $return["telephoneVal"] = "le telephone n'est pas valid";
        $telephone = NULL;
    } else {
        $telephone = mysqli_real_escape_string($mysqli, $_POST["telephonebdd"]);
    }
} else {
    $telephone = NULL;
}


if (isset($_POST["optradio"])) {
    $sexe = $_POST["optradio"];
} else {
    $sexe = NULL;
}

if (isset($login)) {
    /*MODIF $str = "SELECT EMAIL FROM USERS WHERE login = '" . $login . "'";
    $result = query($mysqli, $str) or die("Impossible de creer une compte dans ce moment<br>");
    if (mysqli_num_rows($result) > 0) {
        $ok = false;
        $return["dejaEmail"] = "l'email saisi est déjà enregistré";
    }


    $str = "SELECT LOGIN FROM USERS WHERE LOGIN = '" . $login . "'";
    $result = query($mysqli, $str) or die("Impossible de creer une compte dans ce moment<br>");
    if (mysqli_num_rows($result) > 0) {
        $ok = false;
        $return["dejaLogin"] = "le login saisi est déjà enregistré";
    }*/

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $stmt = mysqli_prepare($mysqli, "SELECT EMAIL FROM USERS WHERE LOGIN = ?");
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $ok = false;
        $return["dejaEmail"] = htmlspecialchars("l'email saisi est déjà enregistré", ENT_QUOTES, 'UTF-8');
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($mysqli, "SELECT LOGIN FROM USERS WHERE LOGIN = ?");
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $ok = false;
        $return["dejaLogin"] = htmlspecialchars("le login saisi est déjà enregistré", ENT_QUOTES, 'UTF-8');
    }
    mysqli_stmt_close($stmt);
} else {
    $ok = false;
}


if ($ok === true) {
    //MODIF $str = "INSERT INTO USERS VALUES ('".$login."','".$email."','".password_hash($pass, PASSWORD_DEFAULT)."','".$nom."','".$prenom."','".$date."','".$sexe."','".$adresse."','".$codepostal."','".$ville."','".$telephone."');";
    //MODIF query($mysqli,$str) or die("Impossible de creer une compte dans ce moment<br>");

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $stmt = mysqli_prepare($mysqli, "INSERT INTO USERS (LOGIN, EMAIL, PASS, NOM, PRENOM, DATE, SEXE, ADRESSE, CODEP, VILLE, TELEPHONE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Impossible de creer un compte pour le moment<br>");
    }
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Liaison des paramètres
    $mysqli_bind_param = mysqli_stmt_bind_param($stmt, "sssssssssss", $login, $email, $hashed_pass, $nom, $prenom, $date, $sexe, $adresse, $codepostal, $ville, $telephone);

    // Exécution de la requête
    mysqli_stmt_execute($stmt) or die("Impossible de creer un compte pour le moment<br>");
    mysqli_stmt_close($stmt);

    $_SESSION["login"] = $login;
    $_SESSION["NOM"] = $nom;
    $_SESSION["PRENOM"] = $prenom;
    $_SESSION["ADRESSE"] = $adresse;
    $_SESSION["CP"] = $codepostal;
    $_SESSION["VILLE"] = $ville;
    $_SESSION["TELEPHONE"] = $telephone;
    unset($return);
    mysqli_close($mysqli);
    header('location: index.php');
} else {

    mysqli_close($mysqli);
    $_SESSION["inscription"] = $return;
    header('location: inscription.php');
}


?>
