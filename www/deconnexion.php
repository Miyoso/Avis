<?php
	session_start();
	session_destroy();
	setcookie('panier','',-1);
	header('location: index.php');
?>