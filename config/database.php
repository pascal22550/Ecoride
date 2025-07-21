<?php
function connectDB() {
    $host = 'localhost';
    $dbname = 'u645697248_ThrkF';
    $username = 'u645697248_fKeCu';
    $password = 'Ecoride123';

    try {
        $db = new PDO("mysql:host=$host;port=8889;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        echo "Erreur connexion BDD : " . $e->getMessage();
        return null;
    }
}