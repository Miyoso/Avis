<?php
// Ancien code (vulnérable) :
// header('location: '.$_GET["p"]);

// correction
$target = $_GET['p'] ?? '';

// empêcher l'injection d'en-têtes
$target = str_replace(["\r", "\n", "\0"], '', $target);

// décoder les encodages
$target = rawurldecode($target);

// refuser les URLs compl
$parts = parse_url($target);

$is_internal = $target !== ''
    && strpos($target, '/') === 0
    && strpos($target, '//') !== 0
    && !isset($parts['scheme'])
    && !isset($parts['host']);

if ($is_internal) {
    header('Location: ' . $target, true, 302);
    exit;
}


header('Location: /', true, 302);
exit;
// fin de correction
