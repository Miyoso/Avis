<?php
session_start();
if (isset($_POST["item"])) {
    include("../Parametres.php");
    include("../Fonctions.inc.php");
    include("../Donnees.inc.php");
    //MODIF $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
    $mysqli = mysqli_connect($host, $user, $pass) or die("Une erreur est survenue.");
    //MODIF mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");
    mysqli_select_db($mysqli, $base) or die("Une erreur est survenue.");

    //MODIF query($mysqli,'delete from produits where id_prod = '.$_POST["item"]);
    $item = isset($_POST["item"]) ? intval($_POST["item"]) : 0;

    $stmt = mysqli_prepare($mysqli, "DELETE FROM produits WHERE id_prod = ?");
    mysqli_stmt_bind_param($stmt, "i", $item);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($mysqli);
} else {
    echo "Erreur";
}
?>