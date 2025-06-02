<?php
/* Vérification si le formulaire a été envoyé */
class UserController {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once './config/database.php'; // inclure la connexion PDO
            // Traitement du formulaire
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            try {

                $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$firstname, $lastname, $email, $password]);

                echo "<p> Inscriptioni réussie ! Bienvenue $firstname ! </p>";
            } catch (PDOException $e) {
                echo "Erreur lors de l'inscription : " . $e->getMessage();

            }
        }
        
        require 'views/register.php';
    }
}

