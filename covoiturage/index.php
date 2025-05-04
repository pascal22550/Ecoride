<?php
// Liste de covoiturages stimulés

$covoiturages = [
    [
        'chauffeur' => 'John Doe', 
        'photo' => 'john.jpg',
        'note' => 4.5, 
        'places' => 2,
        'prix' => 20,
        'date' => '2025-05-10',
        'heure_depart' => '08:00',
        'heure_arrivee' => '10:00',
        'ecologique' => true,

    ],
    [
        'chauffeur' => 'Jane Smith',
        'photo' => 'jane.jpg',
        'note' => 4.0,
        'places' => 1,
        'prix' => 15,
        'date' => '2025-05-10',
        'heure_depart' => '09:00',
        'heure_arrivee' => '11:00',
        'ecologique' => false,

    ],
];

// Initialisation
$results = [];
$message  ='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depart = $_POST['depart'] ?? '';
    $arrivee = $_POST['arrivee'] ?? '';
    $date = $_POST['date'] ?? '';

    // Recherche des covoiturages disponibles
    foreach ($covoiturages as $covoiturage) {
        if ($covoiturage['places'] > 0 && $covoiturage['date'] === $date) {
            $results[] = $covoiturage;
        }
    }

    if (empty($results)) {
        // Si aucun résultat trouvé, on propose la date du prochain covoiturage 
        $prochainTrajet = null;
        foreach ($covoiturages as $covoiturage) {
            if ($covoiturage['places'] > 0 && $covoiturage['date'] > $date) {
                if (!$prochainTrajet || $covoiturage['date'] < $prochainTrajet['date']) {
                    $prochainTrajet = $covoiturage;
                }
            }
        }

        if ($prochainTrajet) {
            $message = "Aucun trajet trouvé à cette date. Voulez-vous partir le " . htmlspecialchars($prochainTrajet['date']) . " ?";
        } else {
            $message = "Aucun covoiturage disponible.";   
        }
    }
}
?>

<h1> Rechercher un covoiturage </h1>

<form method="post" action="">
    <label for="depart"> Ville de départ : </label>
    <input type="text" id="depart" name="depart" require><br><br>

    <label for="arrivee"> Ville d'arrivée : </label>
    <input type="text" id="arrivee" name="arrivee" required><br><br>

    <label for="date"> Date du voyage : </label>
    <input type="date" id="date" name="date" required><br><br>

    <button type="submit"> Rechercher </button>
</form>

<hr>

