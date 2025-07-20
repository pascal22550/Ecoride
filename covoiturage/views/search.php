<h2> Rechercher un covoiturage </h2>
<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<form method="GET" action="index.php">
    <input type="hidden" name="page" value="search-results">

    <label>Départ : </label>
        <input type="text" name="departure_city" required>
    </label><br>

    <label>Arrivée : 
        <input type="text" name="arrival_city" required>
    </label><br>

    <label>Date : 
        <input type="date" name="travel_date" required>
    </label><br><br>

    <hr>

    <h3>Filtres avancés </h3>

    <label>
        <input type="checkbox" name="eco_only" value="1">
        Trajets écologiques (voiture électrique uniquemnet)
    </label><br>

    <label> Prix maximum (euro) : 
        <input type="number" name="max_price" step="0.01" min="0">
    </label>

    <label>Durée maximale (heures) : 
        <input type="number" name="max_duration" min="1">
    </label><br>

    <label>Not minimum du chauffeur : 
        <input type="number" name="min_rating" min="1" max="5">
    </label><br><br>

    <button type="submit"> Rechercher </button>

</form>

<?php require 'views/partials/footer.php'; ?>
