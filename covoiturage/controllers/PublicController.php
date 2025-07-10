<?php 

require_once 'config/database.php';

class PublicController {
    public function searchResults() {
        $db = connectDB();

        $departure = $_GET['departure_city'] ?? '';
        $arrival   = $_GET['arrival_city'] ?? '';
        $date      = $_GET['travel_date'] ?? '';

        // Filtres facultatifs
        $eco_only  = isset($_GET['eco_only']);
        $max_price = $_GET['max_price'] ?? null;
        $max_duration = $_GET['max_duration'] ?? null;
        $min_rating = $_GET['min_rating'] ?? null;

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

        $params = [$departure, $arrival, $date];

        // Ajout des filtres dynamiquement
        if ($eco_only) {
            $sql .= "AND v.energy = 'électrique'";
        }

        if ($max_price !== null && is_numeric($max_price)) {
            $sql .= "AND t.price <= ?";
            $params[] = $max_price;
        }

        if ($max_duration !== null && is_numeric($max_duration)) {
            $sql .= " AND TIMESTAMPDIFF(HOUR, t.departure_datetime, t.arrival_datetime) <= ?";
            $params[] = $min_rating;
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
            SELECT t.*, u.firstname, v.brand, v.model, v.energy, v.preferences
            FROM trips t
            JOIN users u ON t.user_id = u.id
            JOIN vehicules v ON t.vehicle_id = v.id
            WHERE t.id = ?
        ");
        $stmt->execute([$trip_id]);
        $trip = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$trip) {
            echo "Trajet non trouvé.";
            return;
        }

        // Récuperer les avis
        $avisStmt = $db->prepare("SELECT content, rating FROM reviews WHERE driver_id = ?");
        $avisStmt->execute([$trip['user_id']]);
        $reviews = $avisStmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/trip_details.php';
    }
}