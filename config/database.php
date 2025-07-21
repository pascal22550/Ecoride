<?php
function connectDB(): PDO|null {
    $host = 'localhost';
    $dbname = 'u645697248_Thrkf';
    $username = 'u645697248_fKeCu';
    $password = 'EcoRide2025!'; 

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        echo "❌ Erreur connexion BDD : " . $e->getMessage();
        return null;
    }
}
