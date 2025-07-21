<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<h2> Ajouter un véhicule </h2>

<!-- Formulaire d'ajout de véhicule -->
 <form method="POST" ACTION="index.php?page=add-vehicle">

    <!-- SAisir le texte pour la marque du véhicule -->
    <label>Marque : <input type="text" name="brand" required></label><br>

    <!-- Saisir le texte pour le modèle du véhicule -->
    <label>Modèle:  <input type="text" name="model" required></label><br>

    <!-- Saisir le texte pour la couleur du véhicule -->
    <label>Couleur: <input type="text" name="color"></label><br>

    <!-- Menu déroulant pour sélectionner le type d'énergie -->
    <label> Energie :
        <select name="energy" required>
            <!-- Option pour moteur essence -->
            <option value="essence">Essence</option>
            <!-- Option pour moteur diesel -->
            <option value="diesel">Diesel</option>
            <!-- Option pour moteur électrique -->
            <option value="électrique">Electrique</option>
            <!-- Option pour moteur hybride -->
            <option value="hybride">Hybride</option>
        </select>
    </label><br>

    <!-- Saisir la plaque d'immatriculation du véhicule -->
    <label>Immatriculation : <input type="text" name="plate_number" required></label><br>

    <!-- Saisir la date de première immatriculation -->
    <label>Date de première immatriculation : <input type="date" name="registration_date" required></label><br>

    <!-- Saisir le nombre de places du véhicule -->
    <label>Nombre de places : <input type="number" name="seats" min="1" required></label><br>

    <!-- Saisir les préférences (ex: fumeur, non fumeur, animaux...) -->
    <label>Préférences (fumeur, animaux...) : <textarea name="preferences"></textarea></label><br>

    <!-- Bouton pour soumettre le formulaire et enregistrer les informations du véhicules-->
    <button type="submit">Enregistrer le véhicule</button>
 </form>

<!-- Lien pour revenir à la page de profil -->
<p><a href="index.php?page=profile"><-Retour au profil</a></p>

<?php require 'views/partials/footer.php'; ?>

