<?php
session_start();

if(isset($_POST["item"]) && isset($_SESSION["login"])){

    include("../Parametres.php");
    include("../Fonctions.inc.php");
    include("../Donnees.inc.php");

    // Connexion sec a BDD
    $mysqli = mysqli_connect($host, $user, $pass, $base);
    if (!$mysqli) {
        die("Problème de connexion à la base."); // message générique
    }

    //Ancienne fonction vuln
    /*
    function query($link, $query) {
        $resultat = mysqli_query($link, $query);
        if (!$resultat) {
            error_log("MySQL Error: " . mysqli_error($link));
            die("Impossible de traiter la requête pour le moment.");
        }
        return $resultat;
    }
    */
    // Code corr
    function safe_query($mysqli, $sql, $types = '', $params = []) {
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            error_log("Erreur préparation requête : " . $mysqli->error);
            die("Impossible de traiter la requête pour le moment.");
        }

        if ($types !== '' && count($params) > 0) {
            $refs = [];
            foreach ($params as $k => $v) $refs[$k] = &$params[$k];
            array_unshift($refs, $types);
            if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
                $stmt->close();
                die("Impossible de traiter la requête pour le moment.");
            }
        }

        if (!$stmt->execute()) {
            $stmt->close();
            die("Impossible de traiter la requête pour le moment.");
        }

        $result = $stmt->get_result();
        if ($result !== false) {
            $stmt->close();
            return $result; // SELECT
        }

        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected; // INSERT / DELETE / UPDATE
    }

   
    $item = intval($_POST["item"]);
    $login = $_SESSION["login"];

    //Ancien code vul
    /*
    $str0 = 'select * from favs where id_prod = '.$_POST["item"];
    $str = "INSERT INTO FAVS VALUES('".$_SESSION["login"]."','".$_POST["item"]."')";
    $result = query($mysqli,$str0) or die("Impossible de ajouter produit<br>");
    */

    // nouveau code sec
    $sqlCheck = "SELECT * FROM FAVS WHERE id_prod = ? AND login = ?";
    $result = safe_query($mysqli, $sqlCheck, 'is', [$item, $login]);

    if ($result && $result->num_rows > 0 && isset($_POST["x"])) {
        //Ancien code vul
        // query($mysqli,'delete from favs where id_prod = '.$_POST["item"].' and LOGIN = \''.$_SESSION["login"].'\'');
        //Nouveau code sec
        safe_query($mysqli, "DELETE FROM FAVS WHERE id_prod = ? AND login = ?", 'is', [$item, $login]);
        echo 'delete set';
    } else {
        //Ancien code vulne
        // query($mysqli,$str);
        //Nouveau code sec
        safe_query($mysqli, "INSERT IGNORE INTO FAVS (login, id_prod) VALUES (?, ?)", 'si', [$login, $item]);
        echo 'set';
    }

    mysqli_close($mysqli);
}
?>
