<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mysql', 'root', 'root');
    echo "✅ Connexion réussie à la base de données.";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
