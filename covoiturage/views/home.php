<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $entreprise['nom'] ?></title>
    <link rel="stylesheet" href="/public/style.css">

</head>
<body>
    <h1>Bienvenue sur EcoRide !</h1>
      <?php require 'views/partials/header.php'; ?>

</body>

<main>
    <img src="public/Nature1.png"<?= $entreprise['image'] ?>" alt="Image entreprise" width="300">
    <p><?= $entreprise['description'] ?></p>

    <section>
      <h2>Bienvenue sur notre plateforme</h2>
      <p>
        Notre objectif : réduire les émissions de CO₂ grâce au covoiturage.
        Trouvez ou proposez des trajets partagés, gagnez des crédits, et aidez la planète.
      </p>
    </section>

      <h2> Trouvez votre covoiturage</h2>

      <form action="index.php" method="GET">
          <input type="hidden" name="page" value="search-results">

          <label>Ville de départ :
              <input type="text" name="departure_city" required>
          </label><br>

          <label>Ville d’arrivée :
              <input type="text" name="arrival_city" required>
          </label><br>

          <label>Date de départ :
              <input type="date" name="date" required>
          </label><br>

          <button type="submit">Rechercher</button>
      </form>


</main>

</body>
</html>

</html>

<?php
/* Vérification du bon fonctionnement de la connexion de la session */
if (!empty($_SESSION['firstname'])) {
  echo "<p>Bonjour, " . htmlspecialchars($_SESSION['firstname']) . " ! Merci pour votre connexion et ce geste pour la planète.</p>";
}
?>

<?php require 'views/partials/footer.php'; ?>


