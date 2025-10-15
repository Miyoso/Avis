<?php
if (isset($_POST["item"]) && isset($_POST["pos"])) {
    // MODIF $arr1 = array();
    $arr1 = [];
    $trouve = false;
    if (isset($_COOKIE["panier"])) {
        $arr = json_decode($_COOKIE["panier"], true);
        // MODIF $x = 0;
        // MODIF foreach ($arr as $item) {

        if (is_array($arr)) {
            $x = 0;
            foreach ($arr as $item) {
                if (($_POST["item"] == $item) && ($x == $_POST["pos"]) && $trouve == false) {
                    $trouve = true;
                } else {
                    $arr1[] = $item;
                    // MODIF $x++;
                }
                $x++;
            }
        }
    }

    /* MODIF
    if ($arr1) {
        setcookie('panier', json_encode($arr1), time() + (60 * 30), "/");
    } else {
        setcookie("panier", "", time() - 3600, "/");
    }
    */

    if (!empty($arr1)) {
        setcookie(
            'panier',
            json_encode($arr1, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            [
                'expires' => time() + (60 * 30),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    } else {
        setcookie(
            'panier',
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }
    echo "Produit retiré au panier";
}
?>