<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= $entreprise['nom'] ?></title>
        <link rel="stylesheet" href="/public/style.css">
    </head>
    <body>
        <header>        
            <h1><?= $entreprise['nom'] ?> </h1>
        </header>
        
        <main>
            <img src="public/Nature1.png"<?= $entreprise['image'] ?>" alt="Image entreprise" width="300">
            <p><?= $entreprise['description'] ?></p>

            <form action="index.php" method="GET">
                <input type="hidden" name="route" value="search">
                <input type="text" name="query" placeholder="Rechercher un trajet...">
                <button type="submit">Rechercher</button>
            </form>
        </main>

        <footer>
            <p>&copy; 2025 EcoRide - Tous droits réservés.</p>
        </footer>
    </body>
    </html>