<?php
//MODIF header('location: '.$_GET["p"]); rendait possible une faille XSS via un header Location

// Si le paramètre 'p' n'est pas défini, redirige vers la racine
$p = isset($_GET['p']) ? $_GET['p'] : '/';

// Supprime les retours à la ligne pour éviter l'injection de headers
$p = str_replace(["\r", "\n"], '', $p);

// Refuse les URLs absolues ou protocole relatif sinon redirection vers la racine
if (preg_match('#^\s*[a-z][a-z0-9+\-.]*:#i', $p) || preg_match('#^\s*//#', $p)) {
    $p = '/';
}

// Empêche de remonter dans l'arborescence
$p = '/' . ltrim($p, "/ \t");
if (strpos($p, '..') !== false) {
    $p = '/';
}

header('Location: ' . $p, true, 302);
exit;
?>