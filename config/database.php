<?php
function connectDB() {
    $host = 'localhost';
    $dbname = 'ecoride';
    $username = 'root';
    $password = 'root';

    try {
        $db = new PDO("mysql:host=$host;port=8889;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        echo "Erreur connexion BDD : " . $e->getMessage();
        return null;
    }
}