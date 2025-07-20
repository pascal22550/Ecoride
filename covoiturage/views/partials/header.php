<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoRide</title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<header class="site-header">
    <h1 class="site-title">EcoRide</h1>
    <nav class="navbar">
        <ul>
            <li><a href="index.php?page=home">Accueil</a></li>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <li><a href="index.php?page=profile">Mon profil</a></li>
                <?php if (!empty($_SESSION['is_employee'])): ?>
                    <li><a href="index.php?page=employee-dashboard">Espace Employé</a></li>
                <?php endif; ?>
                <?php if (!empty($_SESSION['is_admin'])): ?>
                    <li><a href="index.php?page=admin-dashboard">Admin</a></li>
                <?php endif; ?>
                <li><a href="index.php?page=logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="index.php?page=login">Connexion</a></li>
                <li><a href="index.php?page=register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
