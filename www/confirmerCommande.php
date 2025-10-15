<?php
session_start();
if(isset($_COOKIE["panier"]) && isset($_SESSION["login"]) && isset($_POST["num"]) && isset($_POST["code"])){
    if(!empty($_POST["num"]) || !empty($_POST["code"])){
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

        mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");

        /* -----------------------------
           ligne vulnérable (commentée)
           (injection SQL possible)
        */
        // query($mysqli,"replace into commande (ID_PROD,ID_CLIENT,DATE,NOM,PRENOM,ADRESSE,CP,VILLE,TELEPHONE) values ('".$item."','".$_SESSION["login"]."','".date('d/m/Y')."','".$_SESSION["NOM"]."','".$_SESSION["PRENOM"]."','".$_SESSION["ADRESSE"]."','".$_SESSION["CP"]."','".$_SESSION["VILLE"]."','".$_SESSION["TELEPHONE"]."')");

        // Proposition corr

        $sql = "REPLACE INTO COMMANDES (ID_PROD, ID_CLIENT, DATE, NOM, PRENOM, ADRESSE, CP, VILLE, TELEPHONE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            error_log("Erreur préparation REPLACE commande: " . $mysqli->error);

            die("Oups — impossible de finaliser la commande pour le moment. Merci de réessayer.");
        }


        $login = isset($_SESSION["login"]) ? $_SESSION["login"] : '';
        $dateNow = date('d/m/Y');
        $nom = isset($_SESSION["NOM"]) ? $_SESSION["NOM"] : '';
        $prenom = isset($_SESSION["PRENOM"]) ? $_SESSION["PRENOM"] : '';
        $adresse = isset($_SESSION["ADRESSE"]) ? $_SESSION["ADRESSE"] : '';
        $cp = isset($_SESSION["CP"]) ? $_SESSION["CP"] : '';
        $ville = isset($_SESSION["VILLE"]) ? $_SESSION["VILLE"] : '';
        $telephone = isset($_SESSION["TELEPHONE"]) ? $_SESSION["TELEPHONE"] : '';


        $idProd = 0;
        if (!$stmt->bind_param('isssssiss', $idProd, $login, $dateNow, $nom, $prenom, $adresse, $cp, $ville, $telephone)) {
            error_log("Erreur bind_param REPLACE commande: " . $stmt->error);
            $stmt->close();
            die("Oups — impossible de traiter le panier pour le moment.");
        }

        foreach($panier as $item){

            $idProd = intval($item);


            if (!$stmt->execute()) {

                error_log("Erreur execution REPLACE commande: " . $stmt->error . " -- ID_PROD: " . $idProd . " -- Login: " . $login);

                $stmt->close();
                die("Oups — une erreur est survenue lors de l'enregistrement de la commande. Merci de réessayer.");
            }
        }


        $stmt->close();

        setcookie("panier", "", time()-3600,"/");
        mysqli_close($mysqli);
        $_SESSION["paiement"] = "opération réussie";
        $_SESSION["color"] = "green";
    }else{
        $_SESSION["paiement"] = "donnees incorrectes <br/> Veuillez essayer de nouveau";
        $_SESSION["color"] = "red";
    }

}else{
    $_SESSION["paiement"] = "donnees incorrectes <br/> Veuillez essayer de nouveau";
    $_SESSION["color"] = "red";
}

header('location: panier.php');
?>
