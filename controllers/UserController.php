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

                        // Reconnaître un employé
                        $_SESSION['is_employee'] = $user['is_employee'] ?? 0;
                        $_SESSION['is_admin'] = $user['is_admin'] ?? 0;

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
        $joinedTrips = [];

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

            // Charger des trajets où l'utilisateur est passager
            $stmt = $db->prepare("
                SELECT t.*, tp.is_confirmed, u.firstname AS driver_firstname, v.brand, v.model
                FROM trip_participants tp
                JOIN trips t ON tp.trip_id = t.id
                JOIN users u ON t.user_id = u.id
                JOIN vehicles v ON t.vehicle_id = v.id
                WHERE tp.user_id = ?
                ORDER BY t.departure_datetime DESC
            ");

            $stmt->execute([$_SESSION['user_id']]);
            $joinedTrips = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
        } catch (PDOException $e) {
            echo "Erreur de chargement du profil : " . $e->getMessage();
        }

            // Avis reçus (en tant que conducteur)
            $stmt = $db->prepare("SELECT r.rating, r.content, u.firstname AS reviewer_name
                                  FROM reviews r
                                  JOIN users u ON r.reviewer_id = u.id
                                  WHERE r.driver_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $reviews_received = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calcul de la note moyenne du conducteur
            $stmt = $db->prepare("SELECT AVG(rating) FROM reviews WHERE driver_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $avgDriverRating = $stmt->fetchColumn();

            // Avis donnés (en tant que passager)
            $stmt = $db->prepare("SELECT r.rating, r.content, u.firstname AS driver_name
                                  FROM reviews r
                                  JOIN users u ON r.driver_id = u.id
                                  WHERE r.reviewer_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $reviews_given = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calcul de la note moyenne en tant que passager
            $stmt = $db->prepare("SELECT AVG(rating) FROM reviews WHERE passenger_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $avgPassengerRating = $stmt->fetchColumn();


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
    public function participateTrip() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trip_id'])) {
            $trip_id = intval($_POST['trip_id']);
            $user_id = $_SESSION['user_id'];

            $db = connectDB();

            try {
                // Vérifie s'il reste des places
                $stmt = $db->prepare("SELECT seats_available FROM trips WHERE id = ?");
                $stmt->execute([$trip_id]);
                $trip = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$trip || $trip['seats_available'] <= 0) {
                    $_SESSION['flash_error'] = "Désolé, ce trajet est complet.";
                    header('Location: index.php?page=trip-details&id=' .$trip_id);
                    exit;
                }

                // Vérifie si l'utilisateur est déjà inscrit
                $stmt = $db->prepare("SELECT * FROM trip_participants WHERE trip_id = ? AND user_id = ?");
                $stmt->execute([$trip_id, $user_id]);
                if ($stmt->fetch()) {
                    $_SESSION['flash_error'] = "Vous êtes déjà inscrit à ce trajet.";
                    header('Location: index.php?page=trip-details&id=' . $trip_id);
                    exit;
                }

                // Vérifie si l'utilisateur a au moins 1 crédit
                $stmt = $db->prepare("SELECT credits FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user['credits'] <= 0) {
                    $_SESSION['flash_error'] = "Vous n'avez pas assez de crédits pour participer à ce trajet.";
                    header('Location: index.php?page=search');
                    exit;
                }

                // Inscription à un trajet
                $stmt = $db->prepare("INSERT INTO trip_participants (trip_id, user_id) VALUES (?, ?)");
                $stmt->execute([$trip_id, $user_id]);

                // Mise à jour du nombre de places
                $stmt = $db->prepare("UPDATE trips SET seats_available = seats_available - 1 WHERE id = ?");
                $stmt->execute([$trip_id]);

                // Décrémenter 1 crédit au passager
                $stmt = $db->prepare("UPDATE users SET credits = credits - 1 WHERE id = ?");
                $stmt->execute([$user_id]);

                $_SESSION['flash_success'] = "Vous êtes inscrit à ce trajet";
                header('Location: index.php?page=trip-details&id=' . $trip_id);
                exit;
                
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = "Erreur de base de données : " . $e->getMessage();
                header('Location: index.php?page=trip-details&id=' . $trip_id);
                exit;
            }
        } else {
            $_SESSION['flash_error'] = "Requête invalide.";
            header('Location: index.php?page=search');
            exit;
        }
    }
    
    public function rateDriver() {
        // Vérifie si l'utilisateur est connecté
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        // Vérifie que le formulaire est bien en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trip_id = $_POST['trip_id'] ?? null;
            $driver_id = $_POST['driver_id'] ?? null;
            $rating = $_POST['rating'] ?? null;
            $content = trim($_POST['content'] ?? '');
            $reviewer_id = $_SESSION['user_id'];

        // Validation basique : tous les champs sont obligatoires
        if (!$trip_id || !$driver_id || !$rating || empty($content)) {
            $_SESSION['flash_error'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?page=trip-details&id=' . $trip_id);
            exit;
        }

        $db = connectDB();

        try {
            // Insérer la note
            $passenger_id = $reviewer_id;
            $stmt = $db->prepare("INSERT INTO reviews (trip_id, reviewer_id, driver_id, passenger_id, rating, content, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$trip_id, $reviewer_id, $driver_id, $passenger_id, $rating, $content]);


            // Ajouter 10 crédits au passager qui a noté
            $stmt = $db->prepare("UPDATE users SET credits = credits + 10 WHERE id = ?");
            $stmt->execute([$reviewer_id]);

            // Succès
            $_SESSION['flash_success'] = "Merci pour votre avis ! Vous avez gagné 10 crédits.";
            header('Location: index.php?page=profile');
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur : " . $e->getMessage();
            header('Location: index.php?page=trip-details&id=' . $trip_id);
            exit;
        }

        }  
 
    }

    public function ratePassenger() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $trip_id = $_POST['trip_id'] ?? null;
                $passenger_id = $_POST['passenger_id'] ?? null;
                $rating = $_POST['rating'] ?? null;
                $content = trim($_POST['content'] ?? '');
                $reviewer_id = $_SESSION['user_id'];

                // Validation
                if (!$trip_id || !$passenger_id || !$rating || empty($content)) {
                    $_SESSION['flash_error'] = "Tous les champs sont obligatoires";
                    header('Location: index.php?page=trip-details&id=' . $trip_id);
                    exit;
                }

                // Vérifie que le passager a bien participé

                try {

                $db = connectDB();

                $stmt = $db->prepare("SELECT * FROM trip_participants WHERE trip_id = ? AND user_id = ?");
                $stmt->execute([$trip_id, $passenger_id]);
                if (!$stmt->fetch()) {
                    $_SESSION['flash_error'] = "Ce passager n'a pas participé à ce trajet.";
                    header('Location: index.php?page=trip-details&id=' . $trip_id);
                    exit;
                }

                // Insertion de l'avis
                $stmt = $db->prepare("INSERT INTO reviews (trip_id, reviewer_id, passenger_id, rating, content, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$trip_id, $reviewer_id, $passenger_id, $rating, $content]);

                // Récompense : le conducteur gagne 5 crédits 
                $stmt = $db->prepare("UPDATE users SET credits = credits + 5 WHERE id = ?");
                $stmt->execute([$reviewer_id]);

                $_SESSION['flash_success'] = "Avis enregistré avec succès.";
                header('Location: index.php?page=trip-details&id=' . $trip_id);
                exit;
                
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = "Erreur : " . $e->getMessage();
                header('Location: index.php?page=profile');
                exit;
            }
        }
    }

    public function cancelParticipation()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['trip_id'])) {
            $trip_id = $_POST['trip_id'];
            $user_id = $_SESSION['user_id'];

            $db = connectDB();

            // Supprimer la ligne de participation
            $stmt = $db->prepare("DELETE FROM trip_participants WHERE user_id = ? AND trip_id = ?");
            $success = $stmt->execute([$user_id, $trip_id]);

            if ($success) {
                $_SESSION['flash_success'] = " Vous avez annulé votre participation au trajet.";
            } else {
                $_SESSION['flash_error'] = "Erreur lors de l'annulation.";
            }
        }

        header('Location: index.php?page=profile');
        exit;
        }

    public function startTrip() {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['flash_error'] = "Vous devez être connecté pour démarer un trajet.";
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trip_id = $_POST['trip_id'] ?? null;

            if ($trip_id) {
                $db = connectDB();

                // Vérifier que l'utilisateur est bien le conducteur
                $stmt = $db->prepare("SELECT * FROM trips WHERE id = ? AND user_id = ?");
                $stmt->execute([$trip_id, $_SESSION['user_id']]);
                $trip = $stmt->fetch();

                if ($trip) {
                    // Mettre à jour le champ is_started
                    $stmt = $db->prepare("UPDATE trips SET is_started = 1 WHERE id = ?");
                    $stmt->execute([$trip_id]);

                    $_SESSION['flash_success'] = "Trajet démarré avec succès.";
                    header("Location: index.php?page=profile");
                    exit;
                } else {
                    $_SESSION['flash_error'] = "Vous n'êtes pas autorisé à démarrer ce trajet.";
                }
            } else {
                $_SESSION['flash_error'] = "Trajet introuvable.";
            }

            header("Location: index.php?page=profile");
            exit;
                }
            }

    public function completeTRip() {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['flash_error'] = "Connexion requise.";
            header("Location: index.php?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trip_id = $_POST['trip_id'] ?? null;

            if ($trip_id) {
                $db = connectDB();

                // Vérifie que l'utilisateur est bien le conducteur
                $stmt = $db->prepare("SELECT * FROM trips WHERE id = ? AND user_id = ?");
                $stmt->execute([$trip_id, $_SESSION['user_id']]);
                $trip = $stmt->fetch();

                if ($trip) {
                    // Mettre à jour le trajet comme terminé
                    $stmt = $db->prepare("UPDATE trips SET is_completed = 1 WHERE id = ?");
                    $stmt->execute([$trip_id]);

                    $_SESSION['flash-success'] = "Trajet terminé avec succès.";
                } else {
                    $_SESSION['flash_error'] = "Action non autorisée pour ce trajet.";
                }
            } else {
                $_SESSION['flash_error'] = "Identifiant du trajet manquant.";
            }

                // Après avoir mis à jour le statut du trajet :
                $stmt = $db->prepare("UPDATE trips SET is_completed = 1 WHERE id = ?");
                $stmt->execute([$trip_id]);

                // Récupération des passagers
                $stmt = $db->prepare("
                    SELECT u.email, u.firstname
                    FROM trip_participants tp
                    JOIN users u ON tp.user_id = u.id
                    WHERE tp.trip_id = ?
                ");
                $stmt->execute([$trip_id]);
                $passengers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Envoi de mail à chaque passager
                foreach ($passengers as $p) {
                    $to = $p['email'];
                    $subject = "Confirmation du trajet terminé";
                    $message = "Bonjour " . htmlspecialchars($p['firstname']) . ",\n\nLe covoiturage auquel vous avez participé est terminé.\n\nMerci de vous rendre dans votre espace personnel pour confirmer que tout s'est bien passé, laisser une note et un avis.\n\n Merci pour votre confiance.\n\nL'équipe EcoRide";
                    
                    // Pour tester en local avec MAMP, écrire dans un fichier plutôt qu'envoyer vraiment
                    // file_put_contents('emails/debut_mail_' .uniqid() . '.txt', $message);

                    mail($to, $subject, $message); // A activer uniquement si le serveur est prêt à envoyer des mails
                }

                $_SESSION['flash_success'] = "Trajet marqué comme temriné. Des emails ont été envoyés aux passagers.";
                header('Location: index.php?page=profile');
                exit;



        }

        header("Location: index.php?page=profile");
        exit;
        }

    public function confirmTrip() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trip_id = $_POST['trip_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $user_id = $_SESSION['user_id'];

            if (!$trip_id || !$status) {
                $_SESSION['flash_error'] = "Informations manquantes.";
                header('Location: index.php?page=profile');
                exit;
            }

            $db = connectDB();

            // Mise à jour de la confirmation dans la table trip_participants
            $stmt = $db->prepare("UPDATE trip_participants SET is_confirmed = ? WHERE trip_id = ? AND user_id = ?");
            $is_confirmed = ($status === 'ok') ? 1 : -1;
            $stmt->execute([$is_confirmed, $trip_id, $user_id]);

            // Si c'était une validation positive, on crédite le conducteur
            if ($is_confirmed === 1) {
                // On récupère l'ID du conducteur et le prix du trajet
                $stmt = $db->prepare("SELECT t.user_id AS driver_id, t.price FROM trips t WHERE t.id = ?");
                $stmt->execute([$trip_id]);
                $trip = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($trip) {
                    $stmt = $db->prepare("UPDATE users SET credits = credits + ? WHERE id = ?");
                    $stmt->execute([$trip['price'], $trip['driver_id']]);
                }
            }

            $_SESSION['flash_success'] = "Merci pour votre retour sur le trajet.";
            header('Location: index.php?page=profile');
            exit;
        }

        http_response_code(405);
        echo "Methode non autorisée.";
    }

    public function employeeDashboard() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;   
        }

        $db = connectDB();

        // Vérifie que c'est bien un employé
        $stmt = $db->prepare("SELECT is_employee FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['is_employee'] != 1) {
            $_SESSION['flash_error'] = "Accès Interdit.";
            header('Location: index.php?page=profile');
            exit;
        }

        // Avis à valider
        $stmt = $db->query("
            SELECT r.*, u.firstname AS reviewer_name, t.id AS trip_id
            FROM reviews r
            JOIN users u ON r.reviewer_id = u.id
            JOIN trips t ON r.trip_id = t.id
            WHERE r.status = 'pending'
        ");
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Trajets problématiques
        $stmt = $db->query("
            SELECT t.*, d.firstname AS driver_firstname, p.firstname AS passenger_firstname, p.email AS passenger_email
            FROM trips t
            JOIN users d ON t.user_id = d.id
            JOIN trip_participants tp ON tp.trip_id = t.id
            JOIN users p ON tp.user_id = p.id
            WHERE tp.is_confirmed = -1
        ");
        $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Historique des avis modérés (validés ou rejetés)
        $stmt = $db->query("
            SELECT r.*, u.firstname AS reviewer_name, t.id AS trip_id
            FROM reviews r
            JOIN users u ON r.reviewer_id = u.id
            JOIN trips t ON r.trip_id = t.id
            WHERE r.status IN ('approved', 'rejected')
            ORDER BY r.created_at DESC
        ");
        $moderated_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obligatoire pour pouvoir afficher la vue
        require 'views/employee_dashboard.php';
    }

    public function validateReview() {
        if (!empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $db = connectDB();

        // Vérifie que l'utilisateur est bien un employé
        $stmt = $db->prepare("SELECT is_employee FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || $user['is_employee'] != 1) {
            $_SESSION['flash_error'] = "Acccès Interdit.";
            header('Location: index.php?page=profile');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $review_id = $_POST['review_id'] ?? null;
            $action = $_POST['action'] ?? null;

            if (!$review_id || !in_array($action, ['approve', 'reject'])) {
                $_SESSION['flash_error'] = "Données invalides.";
                header('Location: index.php?page=employee-dashboard');
                exit;
            }

            $status = ($action === 'approve') ? 'approved' : 'rejected';

            $stmt = $db->prepare("UPDATE reviews SET status = ? WHERE id = ?");
            $stmt->execute([$status, $review_id]);

            $_SESSION['flash_success'] = "Avis mis à jour avec succès.";
            header('Location: index.php?page=employee-dashboard');
            exit;
        }

        http_response_code(485);
        echo "Methode non autorisée";
        }

    public function moderateReview() {
    if (empty($_SESSION['user_id'])) {
        header('Location: index.php?page=moderate-review');
        exit;
    }

    $db = connectDB();

    // Vérifie que c’est bien un employé
    $stmt = $db->prepare("SELECT is_employee FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['is_employee'] != 1) {
        $_SESSION['flash_error'] = "Accès interdit.";
        header('Location: index.php?page=profile');
        exit;
    }

    // Vérifie les données du formulaire
    $review_id = $_POST['review_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$review_id || !in_array($action, ['approve', 'reject'])) {
        $_SESSION['flash_error'] = "Données invalides.";
        header('Location: index.php?page=employee-dashboard');
        exit;
    }

    // Met à jour le statut de l'avis
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    $stmt = $db->prepare("UPDATE reviews SET status = ? WHERE id = ?");
    $stmt->execute([$status, $review_id]);

    $_SESSION['flash_success'] = "Avis mis à jour.";
    header('Location: index.php?page=employee-dashboard');
    exit;

}

    public function adminDashboard() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=profile');
            exit;
        }

        $db = connectDB();

        // Vérifie que l'utilisateur est admin
        $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['is_admin'] != 1) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: index.php?page=admin-dashboard');
            exit;
        }

        // Liste des employés
        $stmt = $db->prepare("SELECT firstname, lastname, email FROM users WHERE is_employee = 1");
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Liste des utilisateurs (pour suspension)
        $stmt = $db->query("SELECT * FROM users WHERE is_admin = 0");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Statistiques : trajets par jour
        $stmt = $db->query("
            SELECT DATE(departure_datetime) AS date, COUNT(*) AS count
            FROM trips
            GROUP BY DATE(departure_datetime)
            ORDER BY DATE(departure_datetime) DESC
        ");
        $tripsPerDay = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Statistiques : total des crédits gagnés
        $stmt = $db->query("SELECT SUM(credits) AS total_credits FROM users");
        $creditsData = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalCredits = $creditsData['total_credits'] ?? 0;

        require 'views/admin_dashboard.php';
    }
    
    public function createEmployee() {
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$firstname || !$lastname || !$email || !$password) {
                $_SESSION['flash_error'] = "Tous les champs sont obligatoires.";
                header('Location: index.php?page=create-employee');
                exit;
            }

            $db = connectDB();

            // Vérifie que l'email n'est pas déjà utilisée
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {

                // Met à jour le rôle en employé
                $stmt = $db->prepare("UPDATE users SET is_employee = 1 WHERE id = ?");
                $stmt->execute([$existingUser['id']]);
                $_SESSION['flash_success'] = "Utilisateur existant promu au rôle employé.";
                header('Location: index.php?page=admin-dashboard');
                exit;
            }

            // Sinon, créer un nouvel employé
            $stmt = $db->prepare("INSERT INTO users (firstname, lastname, email, password, is_employee) VALUES (?, ?, ?, ?, 1)");
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([$firstname, $lastname, $email, $hashedPassword]);

            $_SESSION['flash_success'] = "Compte employé créé avec succès.";
            header('Location: index.php?page=admin-dashboard');
            exit;
        }

        require 'views/create_employee.php';
    }

    public function suspendUser() {
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;

            if ($userId) {
                $db = connectDB();
                $stmt = $db->prepare("UPDATE users SET is_suspended = 1 WHERE id = ?");
                $stmt->execute([$userId]);
                $_SESSION['flash_success'] = "Utilisateur suspendu.";
            }
        }

        header('Location: index.php?page=admin-dashboard');
        exit;
    }

    public function unsuspendUser() {
        if (empty($_SESSION['is_admin'])) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: index.php?page=login');
            exit;
        }

        $user_id = $_POST['user_id'] ?? null;
        if (!$user_id) {
            $_SESSION['flash_error'] = "Utilisateur invalide.";
            header('Location: index.php?page=admin-dashboard');
            exit;
        }

        $db = connectDB();
        $stmt = $db->prepare("UPDATE users SET is_suspended = 0 WHERE id = ?");
        $stmt->execute([$user_id]);

        $_SESSION['flash_success'] = "Utilisateur réactivé.";
        header('Location: index.php?page=admin-dashboard');
        exit;
}


}


    



