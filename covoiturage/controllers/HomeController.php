<?php

class HomeController {
    public function index() {

    $entreprise = [
        'nom' => 'EcoRide',
        'description' => 'Une solution de covoiturage écoresponsable pour les trajets du quotidien. ',
        'image' => 'public/image.jpg'
    ];

    // Rendre la vue avec les données
    include 'views/home.php';
    }
}