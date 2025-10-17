<?php
include("Parametres.php");

// MODIF il n'y avait rien avant
// Comme on utilise mysqli on le reprends dans paramètres et puis on instaure la connexion
$mysqli = mysqli_connect($host, $user, $pass, $base) or die("Erreur de connexion : " . mysqli_connect_error());
mysqli_select_db($mysqli, $base) or die("Une erreur est survenue.");


// INUTILE
function afficherAdmin(){
			if(isset($_SESSION["login"])){
				echo '<a href="produits.php"><h4>Gestion de produits</h4></a><br/>';
				echo '<a href="utilisateurs.php"><h4>Gestion d\'utilisateurs</h4></a><br/>';
				echo '<a href="commandes.php"><h4>Visualiser les commandes<h4></a>';
			}	
					
}


function uploadFichier()
{
	if(isset($_FILES['xml']))
	{

		$fichier = 'upload/'.basename($_FILES['xml']['name']);

		if(move_uploaded_file($_FILES['xml']['tmp_name'], $fichier))
		{
		  construireBdd();
		}
		else
		{
			$_SESSION['message'] = '<div style="text-align:center"><h1>Echec de l\'envoi du fichier xml.</h1><br /><br /><a href="administration.php">Cliquez ici pour retourner à l\'administration.</a></div>';
			header('Location: message.php');
		}
		
		unlink($fichier);
	}
}

//on s'assure que chaque produit possède les propriétés Libelle, Prix et UniteDeVente
function xmlValide($dom)
{
	$produitList = $dom->getElementsByTagName('Produit');
	foreach($produitList as $produit)
	{	
		$estValide['Libelle'] = false;
		$estValide['Prix'] = false;
		$estValide['UniteDeVente'] = false;
	 
		$proprieteList = $produit->getElementsByTagName('Propriete');
		foreach($proprieteList as $propriete)
		{
			if($propriete->getAttribute('nom') == 'Libelle') $estValide['Libelle'] = true;
			
			if($propriete->getAttribute('nom') == 'Prix') $estValide['Prix'] = true;
			
			if($propriete->getAttribute('nom') == 'UniteDeVente') $estValide['UniteDeVente'] = true;
		}
		
		if(!$estValide['Libelle'])
		{
			$_SESSION['message'] = '<div style="text-align:center"><h1>Erreur ligne '.$propriete->getLineNo().'. Le produit n\'a pas de propriété Libelle.</h1><br /><br /><a href="administration.php">Cliquez ici pour retourner à l\'administration.</a></div>';
			header('Location: message.php');
			exit;
		}
		
		if(!$estValide['Prix'])
		{
			$_SESSION['message'] = '<div style="text-align:center"><h1>Erreur ligne '.$propriete->getLineNo().'. Le produit n\'a pas de propriété Prix.</h1><br /><br /><a href="administration.php">Cliquez ici pour retourner à l\'administration.</a></div>';
			header('Location: message.php');
			exit;
		}
		
		if(!$estValide['UniteDeVente'])
		{
			$_SESSION['message'] = '<div style="text-align:center"><h1>Erreur ligne '.$propriete->getLineNo().'. Le produit n\'a pas de propriété UniteDeVente.</h1><br /><br /><a href="administration.php">Cliquez ici pour retourner à l\'administration.</a></div>';
			header('Location: message.php');
			exit;
		}
	}
}

//on insère une nouvelle rubrique dans la base de données (si la rubrique est déjà présente alors on insert que les rubriques supérieures de façon récursive)
// Le but de la modification est d'utiliser mysqli car l'ancienne version n'est pas sécurisée contre les injections SQL
function insererRub($rub)
{
    // AJOUT
    global $mysqli;

    // MODIF $result = mysql_query('select id_rub from rubrique where Libelle_rub ="'.$rub['Nom'].'"');
    // on regarde si la rubrique existe
    $result = mysqli_prepare($mysqli, "SELECT ID_RUB FROM RUBRIQUES WHERE LIBELLE_RUB = ?");
    mysqli_stmt_bind_param($result, "s", $rub['Nom']);
    mysqli_stmt_execute($result);
    mysqli_stmt_bind_result($result, $id_rub);
    mysqli_stmt_fetch($result);
    mysqli_stmt_close($result);

    // MODIF if(mysql_num_rows($result) == 0)
    if (!($id_rub)) {
        // MODIF mysql_query('insert into rubrique (Libelle_rub) values("'.$rub['Nom'].'")');
        // MODIF if(isset($rub['RubriquesSuperieures'])) $result = mysql_query('select id_rub from rubrique where Libelle_rub ="'.$rub['Nom'].'"');
        // sinon on la crée
        $result = mysqli_prepare($mysqli, "INSERT INTO RUBRIQUES (LIBELLE_RUB) VALUES (?)");
        mysqli_stmt_bind_param($result, "s", $rub['Nom']);
        mysqli_stmt_execute($result);
        $id_rub = mysqli_insert_id($mysqli);
        mysqli_stmt_close($result);
    }

    // on regarde les parents
    if (isset($rub['RubriquesSuperieures'])) {
        // MODIF $id_rub = mysql_fetch_row($result);
        foreach ($rub['RubriquesSuperieures'] as $libelle) {
            // recursivité
            insererRub(array('Nom' => $libelle)); //on insère récursivement les rubriques supérieures dans la base de données

            // MODIF $result = mysql_query('select id_rub from rubrique where Libelle_rub ="'.$libelle.'"');
            // MODIF $id_rub_sup = mysql_fetch_row($result);

            $result = mysqli_prepare($mysqli, "SELECT ID_RUB FROM RUBRIQUES WHERE LIBELLE_RUB = ?");
            mysqli_stmt_bind_param($result, "s", $libelle);
            mysqli_stmt_execute($result);
            mysqli_stmt_bind_result($result, $id_rub_sup);
            mysqli_stmt_fetch($result);
            mysqli_stmt_close($result);

            // est ce que la relation est dans hierarchies
            $result = mysqli_prepare($mysqli, "SELECT 1 FROM HIERARCHIE WHERE ID_PARENT = ? AND ID_ENFANT = ?");
            mysqli_stmt_bind_param($result, "ii", $id_rub_sup, $id_rub);
            mysqli_stmt_execute($result);
            mysqli_stmt_store_result($result);
            $relation_existe = mysqli_stmt_num_rows($result) > 0;
            mysqli_stmt_close($result);

            // MODIF mysql_query('insert into hierarchie (id_parent, id_enfant) values("'.$id_rub_sup[0].'","'.$id_rub[0].'")');
            // sinon on la crée
            if (!$relation_existe) {
                $result = mysqli_prepare($mysqli, "INSERT INTO HIERARCHIE (ID_PARENT, ID_ENFANT) VALUES (?, ?)");
                mysqli_stmt_bind_param($result, "ii", $id_rub_sup, $id_rub);
                mysqli_stmt_execute($result);
                mysqli_stmt_close($result);
            }
        }
    }
}

function parserRub($dom)
{
	$ListeRubriques = $dom->getElementsByTagName('ListeRubriques');
	foreach($ListeRubriques as $LR)
	{
		$rubriqueList = $LR->getElementsByTagName('Rubrique');
		foreach($rubriqueList as $rubrique)
		{
			unset($rub);

			foreach($rubrique->getElementsByTagName('Nom') as $nom) $rub['Nom'] = utf8_decode($nom->nodeValue);

			$rubSupList = $rubrique->getElementsByTagName('RubriquesSuperieures');
			foreach($rubSupList as $rubSup)
			{
				$rubriqueList2 = $rubSup->getElementsByTagName('Rubrique');
				foreach($rubriqueList2 as $rubrique2) $rub['RubriquesSuperieures'][] = utf8_decode($rubrique2->nodeValue);
			}

			if(isset($rub)) insererRub($rub);
		}
	}
}


function insererProd($prod)
{
    // AJOUT
    global $mysqli;

    if(!isset($prod['Photo'])) $prod['Photo'] = 'img_encours.jpg';
	if(!isset($prod['Descriptif'])) $prod['Descriptif'] = '';
	if(!isset($prod['Rubriques']))
	{
		$prod['Rubriques'][] = 'Divers';
		// MODIF $result = mysql_query('select id_rub from rubrique where Libelle_rub="Divers"');
        $result = mysqli_prepare($mysqli, "SELECT ID_RUB FROM RUBRIQUES WHERE LIBELLE_RUB=?");
        mysqli_stmt_bind_param($result, "s", $prod['Rubriques'][0]);
        mysqli_stmt_execute($result);
        mysqli_stmt_store_result($result);

        // MODIF if(mysql_num_rows($result)==0) mysql_query('insert into rubrique (Libelle_rub) values("Divers")');
        if(mysqli_stmt_num_rows($result) == 0)
        {
            $insert = mysqli_prepare($mysqli, "INSERT INTO RUBRIQUES (LIBELLE_RUB) VALUES (?)");
            mysqli_stmt_bind_param($insert, "s", $prod['Rubriques'][0]);
            mysqli_stmt_execute($insert);
            mysqli_stmt_close($insert);
        }
        mysqli_stmt_close($result);
    }

	// MODIF mysql_query('insert into produit (Libelle, Prix, UniteDeVente, Descriptif, Photo) values("'.$prod['Libelle'].'","'.$prod['Prix'].'","'.$prod['UniteDeVente'].'","'.$prod['Descriptif'].'","'.$prod['Photo'].'")');
    //on récupère l'id du produit que l'on vient d'insérer
    // MODIF $result = mysql_query('select id_prod from produit where Libelle="'.$prod['Libelle'].'" order by id_prod DESC');
    // MODIF $id_prod = mysql_fetch_row($result);
    $result = mysqli_prepare($mysqli, "INSERT INTO PRODUITS (LIBELLE, PRIX, UNITEDEVENTE, DESCRIPTIF, PHOTO) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($result, "sdsss", $prod['Libelle'], $prod['Prix'], $prod['UniteDeVente'], $prod['Descriptif'], $prod['Photo']);
    mysqli_stmt_execute($result);
    $id_prod = mysqli_insert_id($mysqli);
    mysqli_stmt_close($result);

	foreach($prod['Rubriques'] as $libelle)
	{
		//On récupère l'id de la rubrique.
		// MODIF $result = mysql_query('select id_rub from rubrique where Libelle_rub="'.$libelle.'"');
        // MODIF $id_rub = mysql_fetch_row($result);
        // MODIF mysql_query('insert into appartient (id_prod, id_rub) values("'.$id_prod[0].'","'.$id_rub[0].'")');

        $result = mysqli_prepare($mysqli, "SELECT ID_RUB FROM RUBRIQUES WHERE LIBELLE_RUB=?");
        mysqli_stmt_bind_param($result, "s", $libelle);
        mysqli_stmt_execute($result);
        mysqli_stmt_bind_result($result, $id_rub);
        mysqli_stmt_fetch($result);
        mysqli_stmt_close($result);

        $result = mysqli_prepare($mysqli, "INSERT INTO APPARTIENT (ID_PROD, ID_RUB) VALUES (?, ?)");
        mysqli_stmt_bind_param($result, "ii", $id_prod, $id_rub);
        mysqli_stmt_execute($result);
        mysqli_stmt_close($result);
    }

	//on supprime des élements de $prod pour se retrouver avec les propriétés que nous n'avons pas encore traitées
	unset($prod['Libelle']);
	unset($prod['Prix']);
	unset($prod['UniteDeVente']);
	unset($prod['Descriptif']);
	unset($prod['Photo']);
	unset($prod['Rubriques']);

	foreach($prod as $propriete=>$valeur)
	{
		//On récupère l'id de la propriété. Si la propriété n'est pas dans la base de données alors on l'insert.
		/* MODIF
		$result = mysql_query('select id_prop from propriete where libelle_prop="'.$propriete.'"');
		if(mysql_num_rows($result) == 0)
		{
			mysql_query('insert into propriete (libelle_prop) values("'.$propriete.'")');
			$result = mysql_query('select id_prop from propriete where libelle_prop="'.$propriete.'"');
		}
		$id_prop = mysql_fetch_row($result);

		mysql_query('insert into appartient2 (id_prod, id_prop, valeur_prop) values("'.$id_prod[0].'","'.$id_prop[0].'","'.$valeur.'")');
		*/
        $result = mysqli_prepare($mysqli, "SELECT ID_PROP FROM PROPRIETE WHERE LIBELLE_PROP=?");
        mysqli_stmt_bind_param($result, "s", $propriete);
        mysqli_stmt_execute($result);
        mysqli_stmt_store_result($result);

        if(mysqli_stmt_num_rows($result) == 0)
        {
            $insert = mysqli_prepare($mysqli, "INSERT INTO PROPRIETE (LIBELLE_PROP) VALUES (?)");
            mysqli_stmt_bind_param($insert, "s", $propriete);
            mysqli_stmt_execute($insert);
            mysqli_stmt_close($insert);
        }
        mysqli_stmt_close($result);

        $result = mysqli_prepare($mysqli, "SELECT ID_PROP FROM PROPRIETE WHERE LIBELLE_PROP=?");
        mysqli_stmt_bind_param($result, "s", $propriete);
        mysqli_stmt_execute($result);
        mysqli_stmt_bind_result($result, $id_prop);
        mysqli_stmt_fetch($result);
        mysqli_stmt_close($result);

        $result = mysqli_prepare($mysqli, "INSERT INTO APPARTIENT2 (ID_PROD, ID_PROP, VALEUR_PROP) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($result, "iis", $id_prod, $id_prop, $valeur);
        mysqli_stmt_execute($result);
        mysqli_stmt_close($result);
	}
}

function parserProd($dom)
{
	$ListeProduits = $dom->getElementsByTagName('ListeProduits');
	foreach($ListeProduits as $LP)
	{
		$produitList = $LP->getElementsByTagName('Produit');
		foreach($produitList as $produit)
		{
			unset($prod);

			$proprieteList = $produit->getElementsByTagName('Propriete');
			foreach($proprieteList as $propriete) $prod[utf8_decode($propriete->getAttribute('nom'))] = utf8_decode($propriete->nodeValue);

			foreach($produit->getElementsByTagName('Descriptif') as $descriptif) $prod['Descriptif'] = utf8_decode($descriptif->nodeValue);

			foreach($produit->getElementsByTagName('Rubriques') as $rubriques)
			{
				foreach($rubriques->getElementsByTagName('Rubrique') as $rubrique) $prod['Rubriques'][] =  utf8_decode($rubrique->nodeValue);
			}

			if(isset($prod)) insererProd($prod);
		}
	}
}

function construireBdd()
{
	$fichier = 'upload/'.basename($_FILES['xml']['name']);
	$dom = new DOMDocument();

	if(!$dom->load($fichier)) die('Impossible de charger le fichier XML');

	xmlValide($dom);

	parserRub($dom);
	parserProd($dom);
}

function afficherAdministration()
{
    // MODIF FONCTION : enormes vulnérabilités niveau SQL injection et XSS
    // AJOUT
    global $mysqli;

    // MODIF HYPER DANGEREUX extract($_GET);
    // MODIF if(isset($_POST['envoyer'])) uploadFichier();
    if (isset($_POST['envoyer'])) {
        uploadFichier();
    }

    $action = isset($_GET['action']) ? $_GET['action'] : '';

	//si on a cliqué sur le bouton envoyer
	echo '<a href="administration.php?action=bdd">Editer la base de données.</a><br /><br />';

	//si on a cliqué sur "Editer la base de données." alors on affiche ce qui suit
    // MODIF on a enleve la condition : isset($action)
	if($action=='bdd')
	{
		echo '<div style="margin-left:30px">';
			echo '<form method="post" action="administration.php" enctype="multipart/form-data">';
				echo '<div>';
					echo '<label>Fichier: </label><input type="file" name="xml"/>
						 <input type="submit" name="envoyer" value="Envoyer"/>';
				echo '</div>';
			echo '</form>';
		echo '</div><br /><br />';
	}

	echo '<a href="produits.php">Gestion de produits</a><br/><br/>';
	echo '<a href="utilisateurs.php">Gestion d\'utilisateurs</a><br/><br/>';

	echo '<a href="administration.php?action=commande">Visualiser les commandes.</a><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

	//si on a cliqué sur "Visualiser les commandes." alors on affiche ce qui suit
	// MODIF on a enleve la condition isset($action)
    if($action=='commande')
	{
        // MODIF $result = mysql_query('select * from commande order by id_com DESC');
        $sql = "SELECT ID_COM FROM COMMANDES ORDER BY ID_COM DESC";
        $res_cmds = mysqli_query($mysqli, "SELECT ID_COM FROM COMMANDES ORDER BY ID_COM DESC");
//Ancien code vulnerable
        //        if (!$res_cmds) {
//            die('Erreur SQL : ' . mysqli_error($mysqli));
//        }

// Ancien (vulnérable) :
// if (!$res_cmds) {
//     die('Erreur SQL : ' . mysqli_error($mysqli));
// }

// Début Correction
        if (!$res_cmds) {
            // logger l'erreur pour l'admin
            error_log('DB query failed (SELECT ID_COM): ' . mysqli_error($mysqli));
            // message générique côté client
            echo '<div>Erreur lors de la récupération des commandes. Veuillez réessayer plus tard.</div>';

            return;
        }
// Fin Correction


        if(mysqli_num_rows($res_cmds) === 0) {
            echo '<div>Aucune commande enregistrée.</div>';
            return;
        }
        if (!$res_cmds) {
            echo '<div>Erreur lors de la récupération des commandes.</div>';
            return;
        }

        echo '<form action="administration.php?action=commande" method="post"><div class="floatRight">';

		echo '<label>N° commande: </label><select name="id_com">';


        // MODIF
        /* while($commande = mysql_fetch_assoc($result))
		{
			if(isset($_POST['id_com']) && $_POST['id_com']==$commande['id_com']) echo '<option selected="selected">'.$commande['id_com'].'</option>';
			else echo '<option>'.$commande['id_com'].'</option>';
		}*/

        $selected_id_com = isset($_POST['id_com']) ? intval($_POST['id_com']) : null;
        while ($row = mysqli_fetch_assoc($res_cmds)) {
            $id = intval($row['ID_COM']);
            $sel = ($selected_id_com !== null && $selected_id_com === $id) ? ' selected="selected"' : '';
            echo '<option value="' . $id . '"' . $sel . '>' . $id . '</option>';
        }

		echo '</select>';

		echo '<input id="submit" name="voir" type="submit" value="Voir" style="margin:0px"/>';
		echo '</div></form>';

		//si on a cliqué sur voir
        // AJOUT condition && isset($_POST['id_com'])
		if(isset($_POST['voir']) && isset($_POST['id_com']))
		{
            // MODIF $result = mysql_query('select * from commande where id_com="'.$_POST['id_com'].'"');
            // MODIF $commande = mysql_fetch_assoc($result);

            // AJOUT :
            $id_com = intval($_POST['id_com']);

            //on récupère la commande
            $id_com = isset($_POST['id_com']) ? intval($_POST['id_com']) : 0;
            if ($id_com <= 0) {
                echo '<div>Commande introuvable.</div>';
                return;
            }

            $stmt = mysqli_prepare($mysqli, "SELECT * FROM COMMANDES WHERE ID_COM = ?");
            mysqli_stmt_bind_param($stmt, "i", $id_com);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $commande = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stmt);

            if (!$commande) {
                echo '<div>Commande introuvable.</div>';
                return;
            }

			// MODIF : $dateVal = getdate($commande['DATE']);
            $dateStr = $commande['DATE'];
            $timestamp = strtotime($dateStr);
            if ($timestamp === false) {
                $dateAffichee = htmlspecialchars($dateStr, ENT_QUOTES, 'UTF-8');
            } else {
                $dateAffichee = date('d/m/Y H:i:s', $timestamp);
            }

            // AJOUT : on affiche les infos clients
            $civilite = isset($commande['civilite']) ? htmlspecialchars($commande['civilite'], ENT_QUOTES, 'UTF-8') : '';
            $prenom = isset($commande['prenom']) ? htmlspecialchars(ucfirst(strtolower($commande['prenom'])), ENT_QUOTES, 'UTF-8') : '';
            $nom = isset($commande['nom']) ? htmlspecialchars(strtoupper($commande['nom']), ENT_QUOTES, 'UTF-8') : '';
            $adresse = isset($commande['adresse']) ? nl2br(htmlspecialchars($commande['adresse'], ENT_QUOTES, 'UTF-8')) : '';
            $cp = isset($commande['cp']) ? htmlspecialchars($commande['cp'], ENT_QUOTES, 'UTF-8') : '';
            $ville = isset($commande['ville']) ? htmlspecialchars($commande['ville'], ENT_QUOTES, 'UTF-8') : '';
            $telephone = isset($commande['telephone']) ? htmlspecialchars($commande['telephone'], ENT_QUOTES, 'UTF-8') : '';

            // MODIF : #'.$_POST['id_com'].'<br />
            //				Date: '.$date['mday'].'/'.$date['mon'].'/'.$date['year'].'/'.$date['hours'].':'.$date['minutes'].':'.$date['seconds'].'<br /><br />'.
            //				$commande['civilite'].'. '.ucfirst(strtolower($commande['prenom'])).' '.strtoupper($commande['nom']).'<br />'.
            //				$commande['adresse'].'<br />'.
            //				$commande['cp'].'<br />'.
            //				$commande['ville'].'<br />'.
            //				$commande['telephone'].'

            echo '<br /><br /><div class="article">'
                . '#' . $id_com . '<br />'
                . 'Date: ' . $dateAffichee . '<br /><br />'
                . $civilite . '. ' . $prenom . ' ' . $nom . '<br />'
                . $adresse . '<br />'
                . $cp . '<br />'
                . $ville . '<br />'
                . $telephone
                . '</div><br /><br />';

            // MODIF : $result = mysql_query('select * from detail where id_com="'.$_POST['id_com'].'"');

            // MODIF : $nbArticle = mysql_num_rows($result);

            // MODIF : du truc plus haut
            // while($article = mysql_fetch_assoc($result))
            //			{
            //                $result2 = mysql_query('select Libelle, Prix from PRODUITS where id_prod="'.$article['id_prod'].'"');
            //
            //                $info = mysql_fetch_assoc($result2);
            //
            //				$detail[$article['id_prod']]['Quantite'] = $article['quantite'];
            //				$detail[$article['id_prod']]['Libelle'] = $info['Libelle'];
            //				$detail[$article['id_prod']]['Prix'] = $info['Prix'];
            //			}


                // MODIF : echo '<div class="panier"';
                //				if($i != $nbArticle) echo ' style="border-bottom:none"';
                //
                //				echo '>
                //						<div style="float: left; padding: 5px; width:421px">'.$article['Libelle'].'</div>
                //						<div class="colonnePanier" style="width:94px; padding-top:17px;">'.$article['Prix'].' �</div>
                //						<div class="colonnePanier" style="width:70px; padding-top:17px;">'.$article['Quantite'].'</div>
                //						<div class="colonnePanier" style="width:90px; padding-top:17px;">'.$article['Prix'] * $article['Quantite'].'</div>
                //					</div>';
                //
                //				$i++;


            $stmt = mysqli_prepare($mysqli, "SELECT ID_PROD, QUANTITE FROM DETAIL WHERE ID_COM = ?");
            mysqli_stmt_bind_param($stmt, "i", $id_com);
            mysqli_stmt_execute($stmt);
            $res_details = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);

            if (!$res_details || mysqli_num_rows($res_details) === 0) {
                echo '<div>Aucun détail pour cette commande.</div>';
                return;
            }


            $detail = [];
            while ($d = mysqli_fetch_assoc($res_details)) {
                $id_prod = intval($d['ID_PROD']);
                $quantite = intval($d['QUANTITE']);


                $stmt2 = mysqli_prepare($mysqli, "SELECT LIBELLE, PRIX FROM PRODUITS WHERE ID_PROD = ?");
                mysqli_stmt_bind_param($stmt2, "i", $id_prod);
                mysqli_stmt_execute($stmt2);
                $res_prod = mysqli_stmt_get_result($stmt2);
                $info = $res_prod ? mysqli_fetch_assoc($res_prod) : null;
                mysqli_stmt_close($stmt2);

                if ($info) {
                    $detail[$id_prod] = [
                        'id_prod'  => $id_prod,
                        'quantite' => $quantite,
                        'libelle'  => $info['LIBELLE'],
                        'prix'     => $info['PRIX']
                    ];
                }
            }


            echo '<div class="entetePanier" style="width:90px">Prix</div>
                <div class="entetePanier" style="width:70px; border-right:none">Quantité</div>
                <div class="entetePanier" style="width:94px; border-right:none">Prix unitaire</div>';

            $i = 1;
            $nbArticle = count($detail);

            foreach($detail as $article) {
                echo '<div class="panier"' . (($i != $nbArticle) ? ' style="border-bottom:none"' : '') . '>';
                echo '<div style="float: left; padding: 5px; width:421px">' . htmlspecialchars($article['libelle'], ENT_QUOTES, 'UTF-8') . '</div>';
                echo '<div class="colonnePanier" style="width:94px; padding-top:17px;">' . htmlspecialchars((string)$article['prix'], ENT_QUOTES, 'UTF-8') . ' €</div>';
                echo '<div class="colonnePanier" style="width:70px; padding-top:17px;">' . intval($article['quantite']) . '</div>';
                echo '<div class="colonnePanier" style="width:90px; padding-top:17px;">' . htmlspecialchars((string)($article['prix'] * $article['quantite']), ENT_QUOTES, 'UTF-8') . '</div>';
                echo '</div>';
                $i++;
            }

            // total
            $total = 0;
            foreach($detail as $article) {
                $total += $article['prix'] * $article['quantite'];
            }

            echo '<div class="piedPanier">' . htmlspecialchars((string)$total, ENT_QUOTES, 'UTF-8') . '</div>';
        }
	}
}
?>