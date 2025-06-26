<?php

require_once(__DIR__ . '/../config/database.php');


/* Vérification si le formulaire a été envoyé */
class UserController {

public function adminUsers() {
    // Connexion à la base de données via la fonction connectDB()
    $db = connectDB();

    // Vérification si la connexion à échoué
    if (!$db) {
        echo "Connexion à la base de données échouée.";
        return;
    }

    try {

        // Execution de la requête SQL pour récupérer tous les utilisateurs
        $stmt = $db->query("SELECT id, firstname, lastname, email, credits, created_at FROM users");

        // On récupère les résultats dans un tableau associatif
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // Chargement de la vue qui affichera le tableau HTML
        require 'views/admin_users.php';
    } catch (PDOException $e) {
        // Affichage d'un message en cas d'erreur SQL
        echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
        $users = []; // Pour éviter une erreur si la variable est utilisée dans la vue
        require 'views/admin_users.php';
    }
}

public function register() {
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname  = trim($_POST['lastname'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';

        $db = connectDB();
        if (!$db) {
            $error = "Connexion à la base de données échouée.";
        } else {
            try {
                // Vérifier si l'email existe déjà
                $check = $db->prepare("SELECT id FROM users WHERE email = ?");
                $check->execute([$email]);

                if ($check->rowCount() > 0) {
                    $error = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
                } else {
                    // Hasher le mot de passe
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    // Insérer le nouvel utilisateur
                    $stmt = $db->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$firstname, $lastname, $email, $hash]);

                    $success = "Inscription réussie ! Bienvenue, $firstname 🎉";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
    }

    require __DIR__ . '/../views/register.php';
    }

    public function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $db = connectDB();
            if (!$db) {
                $error = "Connexion à la base de données échouée.";
            } else {
                try {
                    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user && password_verify($password, $user['password'])) {
                        // Démarrer la session
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['firstname'] = $user['firstname'];
                        $_SESSION['email'] = $user['email'];

                        // Rediriger vers la page d'accueil ou tableau de bord
                        header('Location: index.php?page=home');
                        exit;
                    } else {
                        $error = "Identifiants inccorects.";
                    }
                }   catch (PDOException $e) {
                    $error = "Erreur los de la connexion : " . $e->getMessage(); 
                }

            }
        }

        require 'views/login.php';
    }
    
    public function profile() {
        if (empty($_SESSION['user_id'])){
            header('Location: index.php?page=login');
            exit;
        }

        $db = connectDB();
        $user = null; // garantit que $user existe même en cas d'erreur

        try {
            $stmt = $db->prepare("SELECT firstname, lastname, email, credits FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erreur de chargement du profil : " . $e->getMessage();
        }

        require 'views/profile.php';

    }
}
    