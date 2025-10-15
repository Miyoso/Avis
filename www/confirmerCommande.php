<?php
session_start();

if(isset($_COOKIE["panier"]) && isset($_SESSION["login"]) && isset($_POST["num"]) && isset($_POST["code"])) {
    if(!empty($_POST["num"]) || !empty($_POST["code"])) {

        $panier = json_decode($_COOKIE["panier"]);
        include("Parametres.php");
        include("Fonctions.inc.php");
        include("Donnees.inc.php");

        //Ligne Vuln : Information Exposure - Server Error Message
        //$mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());

        // Corr Propal

        $mysqli = @mysqli_connect($host, $user, $pass, $base);
        if (!$mysqli) {
            error_log("MySQL connect error: " . mysqli_connect_error() . " (host: {$host}, db: {$base})");
            die("Oups — problème de connexion à la base. Merci de réessayer plus tard.");
        }
        mysqli_select_db($mysqli, $base) or die("Impossible de sélectionner la base : $base");

        /* -----------------------------
           ligne vulnérable (commentée)
           (injection SQL possible)
        */
        // query($mysqli,"replace into commande (ID_PROD,ID_CLIENT,DATE,NOM,PRENOM,ADRESSE,CP,VILLE,TELEPHONE) values ('".$item."','".$_SESSION["login"]."','".date('d/m/Y')."','".$_SESSION["NOM"]."','".$_SESSION["PRENOM"]."','".$_SESSION["ADRESSE"]."','".$_SESSION["CP"]."','".$_SESSION["VILLE"]."','".$_SESSION["TELEPHONE"]."')");

        // Proposition corr

        /* TEST
        $sql = "REPLACE INTO COMMANDES (ID_PROD, ID_CLIENT, DATE, NOM, PRENOM, ADRESSE, CP, VILLE, TELEPHONE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            error_log("Erreur préparation REPLACE commande: " . $mysqli->error);

            die("Oups — impossible de finaliser la commande pour le moment. Merci de réessayer.");
        }*/

        $login = isset($_SESSION["login"]) ? $_SESSION["login"] : '';
        $dateNow = date('d/m/Y');
        $nom = isset($_SESSION["NOM"]) ? $_SESSION["NOM"] : '';
        $prenom = isset($_SESSION["PRENOM"]) ? $_SESSION["PRENOM"] : '';
        $adresse = isset($_SESSION["ADRESSE"]) ? $_SESSION["ADRESSE"] : '';
        $cp = isset($_SESSION["CP"]) ? $_SESSION["CP"] : '';
        $ville = isset($_SESSION["VILLE"]) ? $_SESSION["VILLE"] : '';
        $telephone = isset($_SESSION["TELEPHONE"]) ? $_SESSION["TELEPHONE"] : '';


        $sqlCommande = "INSERT INTO COMMANDES 
            (ID_CLIENT, DATE, NOM, PRENOM, ADRESSE, CP, VILLE, TELEPHONE) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtCommande = $mysqli->prepare($sqlCommande);
        if(!$stmtCommande) {
            error_log("Erreur préparation INSERT commande: " . $mysqli->error);
            die("Oups — impossible de finaliser la commande pour le moment.");
        }

        $stmtCommande->bind_param('ssssssss', $login, $dateNow, $nom, $prenom, $adresse, $cp, $ville, $telephone);
        if(!$stmtCommande->execute()) {
            error_log("Erreur execution INSERT commande: " . $stmtCommande->error);
            $stmtCommande->close();
            die("Oups — une erreur est survenue lors de l'enregistrement de la commande.");
        }

        $idCom = $mysqli->insert_id;
        if ($idCom <= 0) {
            die('Erreur : impossible de créer la commande.');
        }
        $stmtCommande->close();

        // on met dans detail aussi
        foreach($panier as $item) {
            $idProd = intval($item);
            $quantite = 1;

            $stmtCheckProd = mysqli_prepare($mysqli, "SELECT ID_PROD FROM PRODUITS WHERE ID_PROD = ?");
            mysqli_stmt_bind_param($stmtCheckProd, "i", $idProd);
            mysqli_stmt_execute($stmtCheckProd);
            $resCheckProd = mysqli_stmt_get_result($stmtCheckProd);
            mysqli_stmt_close($stmtCheckProd);

            if (!$resCheckProd || mysqli_num_rows($resCheckProd) === 0) {
                continue; // produit introuvable
            }

            $stmtDetail = mysqli_prepare($mysqli, "INSERT INTO DETAIL (ID_COM, ID_PROD, QUANTITE) VALUES (?, ?, ?) 
                                        ON DUPLICATE KEY UPDATE QUANTITE = QUANTITE + ?");
            mysqli_stmt_bind_param($stmtDetail, "iiii", $idCom, $idProd, $quantite, $quantite);
            if(!$stmtDetail->execute()) {
                error_log("Erreur execution DETAIL: " . $stmtDetail->error . " -- ID_PROD: $idProd -- ID_COM: $idCom");
                mysqli_stmt_close($stmtDetail);
                die("Oups — une erreur est survenue lors de l'enregistrement du détail de la commande.");
            }
            mysqli_stmt_close($stmtDetail);
        }

        // ici on affiche
        $detail = [];
        $stmt = mysqli_prepare($mysqli, "SELECT d.ID_PROD, d.QUANTITE, p.LIBELLE, p.PRIX 
                                        FROM DETAIL d 
                                        JOIN PRODUITS p ON d.ID_PROD = p.ID_PROD 
                                        WHERE d.ID_COM = ?");
        mysqli_stmt_bind_param($stmt, "i", $idCom);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        while($row = mysqli_fetch_assoc($res)) {
            $detail[] = [
                'id_prod'  => intval($row['ID_PROD']),
                'quantite' => intval($row['QUANTITE']),
                'libelle'  => $row['LIBELLE'],
                'prix'     => floatval($row['PRIX'])
            ];
        }
        mysqli_stmt_close($stmt);

        // total
        $total = 0;
        foreach($detail as $article) {
            $total += $article['prix'] * $article['quantite'];
        }

        $_SESSION['dernier_detail_commande'] = [
            'articles' => $detail,
            'total'    => $total
        ];

        setcookie("panier", "", time()-3600, "/");
        mysqli_close($mysqli);

        $_SESSION["paiement"] = "Opération réussie !";
        $_SESSION["color"] = "green";

    } else {
        $_SESSION["paiement"] = "Données incorrectes. Veuillez réessayer.";
        $_SESSION["color"] = "red";
    }
} else {
    $_SESSION["paiement"] = "Données incorrectes. Veuillez réessayer.";
    $_SESSION["color"] = "red";
}

header('Location: panier.php');
exit;
?>
