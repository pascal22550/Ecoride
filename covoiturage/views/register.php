<!DOCTYPE html>
<html>
<head>
    <h1>Inscription</h1>
</head>
<body>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?> </p>
    <?php endif; ?>

    <?php if (!empty($sucess)): ?>
        <p style="color:green;"><?= $sucess ?></p>
    <?php endif; ?>
    
    <form action="index.php?page=register" method="POST">
        <label for="firstname">Prenom : </label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="lastname">Nom :</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Mot de passe : </label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">S'inscire</button>


    </form>

