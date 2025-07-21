<?php

require_once('config/database.php');

function insertUser($firstname, $lastname, $email, $password) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$firstname, $lastname, $email, $password]);
    } catch (PDOException $e) {
        return false;
    }

}

