<?php
session_start();
include("Parametres.php");
include("Fonctions.inc.php");
include("Donnees.inc.php");
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];


if (isset($_SESSION["login"]) && $_SESSION["login"] = 'admin' && isset($_POST["id"])) {
    //MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
    $mysqli = mysqli_connect($host, $user, $pass) or die("Une erreur est survenue.");
    //MODIF mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
    mysqli_select_db($mysqli, $base) or die("Une erreur est survenue.");

    /*MODIF $str = "delete from favs where id_prod =".$_POST["id"];
    MODIF $str2 = "delete from produits where id_prod =".$_POST["id"];
    MODIF query($mysqli,$str);
    MODIF	query($mysqli,$str2);*/
    //Utilisation de requêtes préparées pour éviter les injections SQL et conversion de l'ID en entier pour eviter les injections XSS

    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

    $stmt1 = mysqli_prepare($mysqli, "DELETE FROM FAVS WHERE id_prod = ?");
    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    $stmt2 = mysqli_prepare($mysqli, "DELETE FROM PRODUITS WHERE id_prod = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    mysqli_close($mysqli);
    echo "produit effacé avec succès";
}


?>