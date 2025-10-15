<?php
	if(isset($_POST["item"])){
		if(isset($_COOKIE["panier"])){
			$arr = json_decode($_COOKIE["panier"],true);
			// MODIF $arr[] = $_POST["item"];
			// MODIF setcookie('panier',json_encode($arr),time() + (60*30),"/");
            // MODIF echo "Produit ajouté au panier";
            if (!is_array($arr)) {
                $arr = [];
            }
		}
		else{
            // MODIF $arr[] = $_POST["item"];
            // MODIF setcookie('panier',json_encode($arr),time() + (60*30),"/");
			// MODIF echo "Produit ajouté au panier";
            $arr = [];
        }

        $arr[] = $_POST["item"];

        setcookie(
            'panier',
            json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            [
                'expires' => time() + (60 * 30),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
        echo "Produit ajouté au panier";
	}
	else{
		echo "Erreur";
	}
?>