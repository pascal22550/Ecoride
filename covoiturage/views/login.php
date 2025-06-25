<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Connexion</title>
    </head>
    <body>
        <h1> Connexion </h1>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="index.php?page=login" method="POST">
            <label for="email">Email : </label>
            <input type="email" name="email" id="email" required><br>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required><br>

            <button type="submit">Se connecter</button>
        </form>

    </body>
</html>