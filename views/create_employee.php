<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<h2>Créer un compte employé</h2>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<form method="POST" action="index.php?page=create-employee">
    <label>Prénom:</label><br>
    <input type="text" name="firstname" required><br><br>

    <label>Nom:</label><br>
    <input type="text" name="lastname" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Créer le compte employé</button>
</form>

<?php require 'views/partials/footer.php'; ?>
