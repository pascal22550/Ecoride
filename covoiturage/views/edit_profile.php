<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil /html></title>
</head>
<body>
<h2> Modifier mes informations </h2>
<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)) : ?>
    <p style="color:green"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="index.php?page=edit-profile">
    <label>Prénom : </label>
        <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
    </label><br>

    <label>Nom : </label>
        <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
    </label>

    <label>Email : </label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </label>

    <label>Nouveau mot de passe : 
        <input type="password" name="password" placeholder="Laisser vide pour ne pas changer">
    </label>

    <button type="submit">Mettre à jour </button>
</form>

<p><a href="index.php?page=profile"><- Retour au profil</a></p>

</body>
</html>

<?php require 'views/partials/footer.php'; ?>
