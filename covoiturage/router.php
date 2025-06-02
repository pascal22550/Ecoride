<?php

require_once './controllers/HomeController.php';
require_once './controllers/UserController.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'register':
        $controller = new UserController();
        $controller->register();
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée.";
        break;
}

