<?php
function query($link, $query)
{
    // Ancien (vulnérable) :
    // $resultat = mysqli_query($link, $query) or die("$query : " . mysqli_error($link));

    // Début Correction
    $resultat = mysqli_query($link, $query);
    if ($resultat === false) {
        // Logger l'erreur complète pour l'admin
        error_log('DB query failed: ' . mysqli_error($link) . ' -- Query: ' . $query);


        return false;
    }
    // Fin Correction

    return $resultat;
}
?>
