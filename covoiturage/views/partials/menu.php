
<a href="index.php?page=login">Se connecter</a>

<!-- Un bouton de déconnexion -->
<?php if (!empty($_SESSION['firstname'])): ?>
    <a href="index.php?page=logout">Déconnexion</a>
<?php endif; ?>

