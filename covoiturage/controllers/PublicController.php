<?php 

require_once 'config/database.php';

class PublicController {
    public function searchResults() {
        $db = connectDB();

        $departure_city = trim($_GET['departure_city'] ?? '');
        $arrival_city   = trim($_GET['arrival_city'] ?? '');
        $date      = trim($_GET['date'] ?? '');

        // Filtres facultatifs
        $eco_only  = isset($_GET['eco_only']);
        $max_price = $_GET['max_price'] ?? null;
        $max_duration = $_GET['max_duration'] ?? null;
        $min_rating = $_GET['min_rating'] ?? null;


        if (empty($departure_city) || empty($arrival_city) || empty($date)) {
            echo "<p style='color:red;'>Merci de remplir tous les champs du formulaire.</p>";
            return;
        }
        // Base de la requête
        $sql = "
            SELECT t.*,
                   u.firstname,
                   v.energy,
                   TIMESTAMPDIFF(HOUR, t.departure_datetime, t.arrival_datetime) AS duration
            FROM trips t
            JOIN users u ON t.user_id = u.id
            JOIN vehicles v ON t.vehicle_id = v.id
            WHERE t.departure_city = ?
                AND t.arrival_city = ?
                AND DATE(t.departure_datetime) = ?
                AND t.seats_available > 0
        ";

        $params = [$departure_city, $arrival_city, $date];

        // Ajout des filtres dynamiquement
        if ($eco_only) {
            $sql .= "AND v.energy = 'électrique'";
        }

        if ($max_price !== null && is_numeric($max_price)) {
            $sql .= " AND t.price <= ?";
            $params[] = $max_price;
        }

        if ($max_duration !== null && is_numeric($max_duration)) {
            $sql .= " AND TIMESTAMPDIFF(HOUR, t.departure_datetime, t.arrival_datetime) <= ?";
            $params[] = $min_rating;
            $params[] = $max_duration;
        }

        $sql .= " ORDER BY t.departure_datetime";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/search_results.php';
    }

    public function tripDetails() {
        $db = connectDB();
        $trip_id = $_GET['id'] ?? null;

        if (!$trip_id) {
            echo "Trajet introuvable.";
            return;
        }

        // Trajet + chauffeur + vehicule
        $stmt = $db->prepare("
            SELECT 
                t.id AS trip_id,
                t.user_id,
                t.departure_city,
                t.arrival_city,
                t.departure_datetime,
                t.arrival_datetime,
                t.seats_available,
                t.price,
                u.firstname,
                v.brand,
                v.model,
                v.energy,
                v.preferences
            FROM trips t
            JOIN users u ON t.user_id = u.id
            JOIN vehicles v ON t.vehicle_id = v.id
            WHERE t.id = ?
            ");

        $stmt->execute([$trip_id]);
        $trip = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$trip) {
            echo "Trajet non trouvé.";
            return;
        }

        // Débogage temporaire à supprimer après test
        echo "<pre>";
        var_dump($trip['departure_datetime']);
        echo "</pre>";

        
        // Récuperer les avis
        $avisStmt = $db->prepare("SELECT content, rating FROM reviews WHERE driver_id = ?");
        $avisStmt->execute([$trip['user_id']]);
        $reviews = $avisStmt->fetchAll(PDO::FETCH_ASSOC);

        // Avis laissés sur les passagers (par le conducteur)
        $stmt = $db->prepare("SELECT r.*, u.firstname AS passenger_name
                              FROM reviews r
                              JOIN users u ON r.passenger_id = u.id
                              WHERE r.trip_id = ? AND r.passenger_id IS NOT NULL");
        $stmt->execute([$trip['trip_id']]);
        $reviews_for_passengers = $stmt->fetchAll();

        require 'views/trip_details.php';
    }
}