<?php
// controllers/HomeController.php

class HomeController {
    public function index() {
        // Variables à envoyer à la vue
        $entreprise = [
            'nom' => 'EcoRide',
            'description' => 'Une solution de covoiturage écoresponsable pour les trajets du quotidien.',
            'image' => 'public/image.jpg'
        ];

        // Rendre la vue avec les données
        include 'views/home.php';
    }
}
