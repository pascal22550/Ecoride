<?php

$route = $_GET['route'] ?? 'home'; 

if ($route === 'home') {
    require_once 'controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
} else {
    echo "Page non trouvée";
}


