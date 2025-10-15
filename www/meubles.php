<?php
// Ancien code vulnérable :
// header('location: '.$_GET["p"]);

// correction
$redirect = $_GET["p"] ?? 'index.php'; // Valeur par défaut

// Vérif que c'est un chemin relatif et pas une URL externe

if (strpos($redirect, '/') === 0 && !str_contains($redirect, '://')) {
    header('Location: ' . $redirect);
} else {
    // Redir par défaut sécurisée
    header('Location: /index.php');
}
exit();
// fin de correction
?>