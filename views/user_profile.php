<!-- ESPACE UTILISATEUR -->
<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<?php
session_start();
if (empty($_SESSION['firstname'])) {
    header('Location: index.php?page=login');
    exit;
}
?>

<h2>Espace personnel</h2>
<p> Bienvenue <?= htmlspecialchars($_SESSION['firstname']) ?></p>
<p> Email : <?= htmlspecialchars($_SESSION['email']) ?></p>
<p> Crédits disponibles : <?= htmlspecialchars($_SESSION['credits'])?></p>

<?php require 'views/partials/footer.php'; ?>
