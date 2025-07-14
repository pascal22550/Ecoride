<?php

session_start(); 

require_once './controllers/HomeController.php';
require_once './controllers/UserController.php';
require_once './controllers/PublicController.php';

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
        session_destroy();
        header('Location: index.php?page=home');
        exit;
        break;

    /* Ajout nouvelle route pour le profil utilisateur */
    case 'profile':
        $controller = new UserController();
        $controller->profile(); 
        break;

    /* Ajouter nouvelle pour route pour modifier le profil utilisateur */
    case 'edit-profile':
        $controller = new UserController();
        $controller->editProfile();
        break;

    /* Ajouter la selection du role de l'utilisateur */
    case 'select-role':
        $controller = new UserController();
        $controller->selectRole();
        break;

    /* Ajouter la possibilité d'ajouter un véhicule */
    case 'add-vehicle':
        $controller = new UserController();
        $controller->addVehicle();
        break;

    /* Ajouter la possibilité d'ajouter un trajet */
    case 'add-trip':
        $controller = new UserController();
        $controller->addTrip();
        break;

    /* Ajouter la possibilité de supprimer un trajet */
    case 'delete-trip':
        $controller = new UserController();
        $controller->deleteTrip();
        break;

    /* Ajouter la possibilité de modifier un trajet */
    case 'edit-trip' :
        $controller = new UserController();
        $controller->editTrip();
        break;

    /* Ajouter la possibilite pour un visiteur de rechercher un trajet */
    case 'search':
        require 'views/search.php';
        break;

    /* Voir les résultats des recherches du visiteur */
    case 'search-results':
        $controller = new PublicController();
        $controller->searchResults();
        break;

    /* fonctionnement trip-details et tripDetails */
    case 'trip-details':
    case 'trip-Details': // supporte aussi cette écriture
        $controller = new PublicController();
        $controller->tripDetails();
        break;

    /* Fonction du bouton participer */
    case 'participate':
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        $controller->participateTrip();
        break;

    /* Fonctionnement de la notation du conducteur */
    case 'rate-driver':
        $controller = new UserController();
        $controller->rateDriver();
        break;

    /* Permettre à un conducteur de noter un passager */
    case 'rate-passenger':
        $controller = new UserController();
        $controller->ratePassenger();
        break;

    /* Page qui s'affiche en cas d'erreur */
    default:
        http_response_code(404);
        echo "Page non trouvée.";
        break;
}

