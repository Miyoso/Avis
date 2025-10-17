<?php

  // Parametres de configuration de la connexion
  // -> permet de porter l'application en ne modifiant qu'une seule fois
  //	les param�tres de connexions � un serveur MySQL 

//MODIF
// $host="127.0.0.1";
//  $host="db";
//  $user="root";
//  $pass="password";
//  $base="TaupeMeubles";
//  $id_user="0000";


    $host=getenv("DB_HOST");
    $user=getenv("DB_USER");
    $pass=getenv("DB_PASSWORD");
    $base=getenv("DB_NAME");
    $id_user=getenv("DB_ID_USER");
  
?>