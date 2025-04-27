<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title> EcoRide - Covoiturage écologique </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <!-- En-tête -->
        <header>
            <h1> Bienvenue sur EcoRide </h1>
            <p> Le covoiturage pour le service de la planète </p>
        </header>

        <br>

        <!-- Menu de l'application -->

        <nav>
            <ul>
                <li><a href="#home" class="menu-item"> Retour à l'accueil </a></li>
                <li><a href="#covoiturages" class="menu-item"> Covoiturages </a></li>
                <li><a href="#connexion" class="menu-item"> Connexion </a></li>
                <li><a href="#contact" class="menu-item"> Contact </a></li>
            </ul>
        </nav>

        <!-- Formulaire de recherche -->

        <section id="search-form">
            <h2> Rechercher un covoiturage </h2>
            <form action="rechercher_covoiturage.php" method="GET">
                <label for="depart">Ville de départ :</label>
                <input type="text" id="depart" name="depart" placeholder="Ville de départ" required>

                <label for="arrivee"> Ville d'arrivée :</label>
                <input type="text" id="arrivee" name="arrivee" placeholder="Ville d'arrivée" required>

                <label for="date"> Date du voyage </label>
                <input type="date" id="date" name="date" required>

                <button type="submit">Rechercher</button>
            </form>
        </section>



        <!-- Présentation avec image -->
        <section class="presentation">
            <h2> Notre mission ? </h2>
            <p> EcoRide est une plateforme de covoiturage dédiée aux trajets en voiture pour réduire l'impact environnemental. Rejoignez une communauté de voyageurs engagés. </p>
            <img src="images/Nature1.png" alt="Image écologique" />
            <img src="images/Nature2.png" alt="Image écologique" />
            <img src="images/Nature3.png" alt="Image écologique" />

        </section>

        <!-- Barre de recherche -->
        <section class="recherche">
            <h2> Rechercher un itinéraire </h2>
            <form>
                <input type="text" placeholder="Départ" required>
                <input type="text" placeholder="Destination" required>
                <button type="submit"> Rechercher</button>
            </form>
        </section>

        <!-- Pied de page -->
        <footer>
            <p>Contact : contact@ecoride.com </p>
            <a href="#"> Mentions légales </a>
        </footer>


    </body>

</html>