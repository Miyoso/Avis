<?php
session_start();

// Supprimer toutes les variables de session
$_SESSION = [];

// Réinitialiser le token CSRF
unset($_SESSION['csrf_token']);

// Supprimer le cookie de session côté client
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Supprimer le cookie panier
setcookie('panier', '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Redirection
header('Location: index.php');
exit;
?>
