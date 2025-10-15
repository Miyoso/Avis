<?php
// Ancien code (vulnérable) :
// header('location: '.$_GET["p"]);

// correction
$target = $_GET['p'] ?? '';


$target = str_replace(["\r", "\n", "\0"], '', $target);

// décoder pour éviter %2f%2f etc.
$target = rawurldecode($target);

// rapide vérif : que c'est pas un truc bizarre
$parts = parse_url($target);
if ($target !== '' && strpos($target, '/') === 0 && strpos($target, '//') !== 0 && !isset($parts['scheme']) && !isset($parts['host'])) {
    header('Location: ' . $target, true, 302);
    exit;
}


header('Location: /', true, 302);
exit;
// fin de correction
