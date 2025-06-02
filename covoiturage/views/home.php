<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $entreprise['nom'] ?></title>
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
    <h1>Bienvenue sur EcoRide !</h1>
    <nav>
        <h1><?= $entreprise['nom'] ?></h1>

        <a href="/">Accueil</a>
        <a href="/trajets">Trajets</a>
        <a href="/connexion">Connexion</a>
    </nav>
    <p><a href="index.php?page=register">S'inscrire</a></p>
</body>

<main>
    <img src="public/Nature1.png"<?= $entreprise['image'] ?>" alt="Image entreprise" width="300">
    <p><?= $entreprise['description'] ?></p>

    <form action="index.php" method="GET">
        <input type="hidden" name="route" value="search">
        <input type="text" name="query" placeholder="Rechercher un trajet...">
        <button type="submit">Rechercher</button>
    </form>
    <section>
      <h2>Bienvenue sur notre plateforme</h2>
      <p>
        Notre objectif : réduire les émissions de CO₂ grâce au covoiturage.
        Trouvez ou proposez des trajets partagés, gagnez des crédits, et aidez la planète.
      </p>
    </section>
  </main>

  <footer>
    <p>© 2025 Covoiturage Écologique - Tous droits réservés</p>
  </footer>
</body>
</html>

</html>
