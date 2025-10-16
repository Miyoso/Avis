<?php

$target = $_SERVER['PHP_SELF'] ?? '/';
$target = str_replace(["\r", "\n", "\0"], '', $target); // éviter header injection
header('Location: ' . $target . '?result=0', true, 302);
exit;

?>