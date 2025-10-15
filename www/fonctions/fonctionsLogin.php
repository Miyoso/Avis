<?php
session_start ();
include("../Parametres.php");
include("../Fonctions.inc.php");
include("../Donnees.inc.php");

// Ancien code vulnérable :
// $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
// mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");

// Début Correction

$mysqli = @mysqli_connect($host, $user, $pass);
if (!$mysqli) {
    error_log('DB connect failed: ' . mysqli_connect_error());
    echo "Erreur serveur, réessayez plus tard.";
    exit;
}

if (!@mysqli_select_db($mysqli, $base)) {
    error_log('DB select failed: ' . mysqli_error($mysqli));
    echo "Erreur serveur, réessayez plus tard.";
    mysqli_close($mysqli);
    exit;
}
// Fin Correction


$return["msg"] = "L'utilisateur n'a été pas trouvé";

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = trim (mysqli_real_escape_string ($mysqli, $_POST["login"]));
    $pass = $_POST["password"];
    $str = "SELECT * FROM USERS WHERE LOGIN = '" . $login . "'";
    $result = query ($mysqli, $str) or die ("Impossible de se connection à la base de données<br>");
    if (mysqli_num_rows ($result) > 0) {
        $row = mysqli_fetch_assoc ($result);
        if (password_verify ($pass, $row["PASS"])) {
            $_SESSION["login"] = $row["LOGIN"];
            $_SESSION["isAdmin"] = $row["IS_ADMIN"];
            $_SESSION["NOM"] = $row["NOM"];
            $_SESSION["PRENOM"] = $row["PRENOM"];
            $_SESSION["ADRESSE"] = $row["ADRESSE"];
            $_SESSION["CP"] = $row["CODEP"];
            $_SESSION["VILLE"] = $row["VILLE"];
            $_SESSION["TELEPHONE"] = $row["TELEPHONE"];

            unset($return);
            $return["msg"] = "Connéxion réussi";
            mysqli_close ($mysqli);
            echo $return["msg"];
            exit();
        }

    }
}
mysqli_close ($mysqli);
echo $return["msg"];
?>