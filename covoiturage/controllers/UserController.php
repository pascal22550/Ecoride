<?php

require_once(__DIR__ . '/../config/database.php');


/* Vérification si le formulaire a été envoyé */
class UserController {

public function adminUsers() {
    $db = connectDB();
    if (!$db) {
        echo "Connexion à la base de données échouée.";
        return;
    }

    try {
        $users = $db->query("SELECT * FROM users")->fetchAll();
        require 'views/admin_users.php';
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
    }
}

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once './config/database.php'; // inclure la connexion PDO
            // Traitement du formulaire
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            try {
                global $pdo;

                // Vérification si l'email existe déjà

                $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $checkStmt->execute([$email]);

                if ($checkStmt->rowCount() > 0) {
                    $error = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
                } else {
                    // Insertion si l'email est libre
                    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES(?, ?, ?, ?)");
                    $stmt->execute([$firstname, $lastname, $email, $password]);

                    $success = "Inscription réussie ! Bienvenue $firstname 🎉";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }

        // Affichage de la vue avec message
        require 'views/register.php';
    }
}