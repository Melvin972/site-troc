<?php
// connexion à la base de données
$pdo = new PDO('mysql:host=db5015750433.hosting-data.io;dbname=dbs12850253', 'dbu98077', 'trocifocop', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

session_start();


define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/eval_troc/');


define('URL', 'http://www.troc.melvinhans.com/');


$erreur = '';
$erreurIndex = '';
$validate = '';
$validateIndex = '';
$content = '';



foreach ($_POST as $key => $value) {

    $_POST[$key] = htmlspecialchars(trim($value));
}

foreach ($_GET as $key => $value) {
    $_GET[$key] = htmlspecialchars(trim($value));
}
require_once('fonctions.php');
