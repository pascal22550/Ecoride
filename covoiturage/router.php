<?php

require_once './controllers/HomeController.php';

$uri = $_SERVER['REQUEST_URI'];

switch ($uri) {
    case '/':
    case '/index.php';
        $controller = new HomeController();
        $controller-> index();
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée.";
        break;
}