
<a href="index.php?page=login">Se connecter</a>
<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>


<!-- Un bouton de déconnexion -->
<?php if (!empty($_SESSION['firstname'])): ?>
    <a href="index.php?page=logout">Déconnexion</a>
<?php endif; ?>

