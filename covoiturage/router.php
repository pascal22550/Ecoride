<?php

session_start(); 

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

    case 'admin-users':
        // si l'URL contient $page=admin-users, on appelle la méthode adminUsers() du contrôleur
        $controller = new UserController();
        $controller->adminusers();
        break;

    /* Ajout nouvelle route login dans router */
    case 'login':
        $controller = new UserController();
        $controller->login();
        break;

    /* Ajout nouvelle route pour se deconnecter */
    case 'logout':
        session_start();
        session_destroy();
        header('Location: index.php?page=home');
        exit;
        break;

    /* Ajout nouvelle route pour le profil utilisateur */
    case 'profile':
        $controller = new UserController();
        $controller->profile(); 
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée.";
        break;
}

