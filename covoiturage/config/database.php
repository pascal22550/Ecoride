<?php

$host = 'localhost';
$dbname = 'ecoride';
$user = 'root'; // ou nom d'utilisateur MySQL
$pass = ''; // ou mot de passe


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur connexion BDD : " . $e->getMessage());
}



