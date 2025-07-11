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
        $vehicles = [];

        try {
            // Chargement des infos utilisateur
            $stmt = $db->prepare("SELECT firstname, lastname, email, credits, is_driver FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            // Chargement des véhicules du user connecté
            $stmt = $db->prepare("SELECT * FROM vehicles WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Charger les trajets créés par l'utilisateur connecté (chauffeur)
            $stmt = $db->prepare("SELECT t.*, v.brand, v.model, v.plate_number
                                  FROM trips t
                                  JOIN vehicles v ON t.vehicle_id = v.id
                                  WHERE t.user_id = ?
                                  ORDER BY t.departure_datetime DESC");
            $stmt->execute([$_SESSION['user_id']]);
            $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erreur de chargement du profil : " . $e->getMessage();
        }

        require 'views/profile.php';

    }

    public function editProfile() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $db = connectDB();
        $error = '';
        $success = '';

        // Charger les infos actuelles de l'utilisateur
        $stmt = $db->prepare("SELECT firstname, lastname, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $email = trim($_POST['email']);
            $password = $_POST['password'] ?? '';

            try {
                // préparer la requête SQL
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->execute([$firstname, $lastname, $email, $hashed, $_SESSION['user_id']]);
                } else {
                    $stmt = $db->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
                    $stmt->execute([$firstname, $lastname, $email, $_SESSION['user_id']]);
                }


                $success = "Profil mis à jour avec succès";

                // Mettre à jour la session si prénom changé
                $_SESSION['firstname'] = $firstname;

                // Ajouter le message flash et rediriger
                $_SESSION['flash_success'] = " Profil mis à jour avec succès.";
                header('Location: index.php?page=profile');
                exit;

            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour : " . $e->getMessage();
            }
        }
            
        require 'views/edit_profile.php';   

    }

    public function selectRole() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $db = connectDB();

        // Charger les valeurs actuelles de l'utilisateur
        $stmt = $db->prepare("SELECT is_driver, is_passenger FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Vérifie si les cases sont cochées
            $is_driver = isset($_POST['is_driver']) ? 1 : 0;
            $is_passenger = isset($_POST['is_passenger']) ? 1 : 0;

            // Mise à jour dans la BDD
            $stmt = $db->prepare("UPDATE users SET is_driver = ?, is_passenger = ? WHERE id = ?");
            $stmt->execute([$is_driver, $is_passenger, $_SESSION['user_id']]);

            $_SESSION['flash_success'] = "Votre rôle a bien été enregistré";
            header('Location: index.php?page=profile');
            exit;
        }

        require 'views/select_role.php';
    }

    public function addVehicle() {
        // Vérifie si l'utilisateur est connecté en consultant la session
        if (empty($_SESSION['user_id'])) {
            // Si non connecté, redirige vers la page de connexion
            header('Location: index.php?page=login');
            exit;
        }

        // Connexion à la base de données
        $db = connectDB();


        // Si le formulaire a été soumis en méthode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prépare une requête SQL pour insérer un nouveau véhicule dans la base 
            $stmt = $db->prepare("INSERT INTO vehicles
            (user_id, brand, model, color, energy, plate_number, registration_date, seats, preferences)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Exécute la requête avec les données du formulaire et l'identifiant de l'utilisateur
            $stmt->execute([
                $_SESSION['user_id'],
                $_POST['brand'],
                $_POST['model'],
                $_POST['color'],
                $_POST['energy'],
                $_POST['plate_number'],
                $_POST['registration_date'],
                $_POST['seats'],
                $_POST['preferences']
            ]);

            // Message flash pour confirmer l'ajout du véhicule
            $_SESSION['flash_success'] = " Véhicule ajouté avec succès.";
           
            // Redirige vers la page de profil après insertion
            header('Location: index.php?page=profile');
            exit;
        }

    // Si le formulaire n'a pas été soumis, on affiche la vue du formulaire
    require 'views/add_vehicle.php';

    }

    public function addTrip() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $db = connectDB();

        // Charger les véhicules du chauffeur
        $stmt = $db->prepare("SELECT * FROM vehicles WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupération des données du formulaire
            $departure_city = $_POST['departure_city'] ?? '';
            $arrival_city = $_POST['arrival_city'] ?? '';
            $departure_datetime = $_POST['departure_datetime'] ?? '';
            $arrival_datetime = $_POST['arrival_datetime'] ?? '';
            $vehicle_id = $_POST['vehicle_id'] ?? '';
            $seats_available = $_POST['seats_available'] ?? '';
            $price = $_POST['price'] ?? '';

            // Conversion au bon format DATETIME pour MySQL
            $departure_datetime = date('Y-m-d H:i:s', strtotime($departure_datetime));
            $arrival_datetime = date('Y-m-d H:i:s', strtotime($arrival_datetime));

            // Préparation et exécution de la requête
            $stmt = $db->prepare("INSERT INTO trips
                (user_id, vehicle_id, departure_city, arrival_city, departure_datetime, arrival_datetime, seats_available, price)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $_SESSION['user_id'],
                $_POST['vehicle_id'],
                $_POST['departure_city'],
                $_POST['arrival_city'],
                $_POST['departure_datetime'],
                $_POST['arrival_datetime'],
                $_POST['seats_available'],
                $_POST['price'],
            ]);

            $_SESSION['flash_success'] = " Trajet créé avec succès.";
            header('Location: index.php?page=profile');
            exit;
            
        }

        require 'views/add_trip.php';
    }

    public function deleteTrip() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trip_id'])) {
            $trip_id = $_POST['trip_id'];

            $db = connectDB();

            // Supprimer uniquement si l'utilisateur en est le propriétaire
            $stmt = $db->prepare("DELETE FROM trips WHERE id = ? AND user_id = ?");
            $stmt->execute([$trip_id, $_SESSION['user_id']]);

            $_SESSION['flash_success'] = " Trajet supprimé avec succès.";            
        }

        header('Location: index.php?page=profile');
        exit;
    }

    public function editTrip() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $db = connectDB();
        $trip_id = $_GET['id'] ?? null;

        // Charger le trajet actuel
        $stmt = $db->prepare("SELECT * FROM trips WHERE id = ? AND user_id = ?");
        $stmt->execute([$trip_id, $_SESSION['user_id']]);
        $trip = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$trip) {
            $_SESSION['flash_success'] = "Trajet introuvable.";
            header('Location: index.php?page=profile');
            exit;
        }

        // Si soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $departure_datetime = date('Y-m-d H:i:s', strtotime($_POST['departure_datetime']));
            $arrival_datetime = date('Y-m-d H:i:s', strtotime($_POST['arrival_datetime']));

            $stmt = $db->prepare("UPDATE trips SET departure_city = ?, arrival_city = ?, departure_datetime = ?, arrival_datetime = ?, seats_available = ?, price = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([
                $_POST['departure_city'],
                $_POST['arrival_city'],
                $departure_datetime,
                $arrival_datetime,
                $_POST['seats_available'],
                $_POST['price'],
                $trip_id,
                $_SESSION['user_id']                
            ]);

            $_SESSION['flash_success'] = "Trajet modifié avec succès.";
            header('Location: index.php?page=profile');
            exit;
        }

        // Afficher le formulaire
        require 'views/edit_trip.php';
    }

}

    