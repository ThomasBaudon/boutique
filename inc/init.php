<?php

$pdo = new PDO(
    "mysql:host=localhost; port=8080; dbname=boutiquephp", "root", "root",
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // emet un avertissement sur les erreurs sql
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' //Utiliser l'encodage utf8 lors des échanges avec la BDD
    )
);
// TEST CONNEXION BDD
// var_dump($pdo);

// Déclarer une variable qui va contenir les différents messages
$content ='';

/* Déclarer une variable  qui va afficher les messages d'erreur */
$errorMessage = '';

// Je lance la session
session_start();

// appel le fichier de fonctions
require_once('function.php');

// Définir une constante où sera stockée l'URL RELATIVE
define('URL', 'localhost:8080/boutique/');

define("BASE", $_SERVER['DOCUMENT_ROOT'] . '/boutique/');// Je prépare le chemin de l'image dans le dossier qui est sur le serveur

?>