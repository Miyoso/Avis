<?php

function afficherCommandes()
{
    include("Parametres.php");
    include("Fonctions.inc.php");
    include("Donnees.inc.php");

    // Ancien code vulnérable :
    // $mysqli=mysqli_connect($host,$user,$pass) or die("Problème de création de la base :".mysqli_error());
    // mysqli_select_db($mysqli,$base) or die("Impossible de sélectionner la base : $base");

    // Début Correction
    $mysqli = @mysqli_connect($host, $user, $pass);
    if (!$mysqli) {
        // log technique pour l'admin/dev
        error_log('DB connection failed: ' . mysqli_connect_error());
        // message générique côté client
        echo 'Serveur temporairement indisponible. Veuillez réessayer plus tard.';
        return;
    }

    if (!@mysqli_select_db($mysqli, $base)) {
        error_log('DB select failed: ' . mysqli_error($mysqli));
        echo 'Serveur temporairement indisponible. Veuillez réessayer plus tard.';
        mysqli_close($mysqli);
        return;
    }
    // Fin Correction

    //MODIF $result = query($mysqli,'select id_com, id_client, (select prenom from USERS where USERS.login = COMMANDES.id_client limit 1) as prenom,(select nom from USERS where USERS.login = COMMANDES.id_client limit 1) as nom,id_prod,date,ADRESSE,cp,ville from COMMANDES where  id_client = \''.$_SESSION["login"].'\'');
    $result = query($mysqli, '
        SELECT 
            c.id_com, 
            c.id_client, 
            d.id_prod,
            c.date,
            c.adresse,
            c.cp,
            c.ville,
            (SELECT prenom FROM USERS WHERE USERS.login = c.id_client LIMIT 1) as prenom,
            (SELECT nom FROM USERS WHERE USERS.login = c.id_client LIMIT 1) as nom
        FROM 
            COMMANDES c
        JOIN 
            DETAIL d ON c.id_com = d.id_com
        WHERE 
            c.id_client = \'' . $_SESSION["login"] . '\'
    ');

    $num = mysqli_num_rows($result);
    if ($num > 0) {
        echo "<table>";
        echo "<tr><td width='50px'>ID</td><td width='80px'>Date</td><td width='80px'>Produit</td><td width='80px'>Client</td></tr>";
        echo "<tr><td colspan='5'><hr></td></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td id='item'>" . $row["id_com"] . "</td><td> " . $row["date"] . "</td><td><a href='details.php?prod=" . $row["id_prod"] . "'> " . $row["id_prod"] . "<a></td><td><a href='details.php?login=" . $row["id_client"] . "'>" . $row["nom"] . " " . $row["prenom"] . "</a></td>";
            echo "</tr>";
            echo "<tr><td colspan='5'><hr></td></tr>";
        }
        echo "</table>";
    } else {
        echo '<h4>Pas d\'histoire d\'achats</h4>';
    }
    mysqli_close($mysqli);
}

?>