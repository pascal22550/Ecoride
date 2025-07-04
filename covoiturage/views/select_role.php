<h2> Déclarer mon rôle </h2>


<!-- Formulaire pour sélectionner son rôle (chauffeur ou passager) -->
<form method="POST" action="index.php?page=select-role">

    <!-- Case à cocher pour indiquer que l'utilisateur est chauffeur -->
    <label>
        <input type="checkbox" name="is_driver" value="1"
        <?= (!empty($user['is_driver']) && $user['is_driver']) ? 'checked' : '' ?>>
        Je suis chauffeur
    </label><br>

    <!-- Case à cochjer pour indiquer que l'utilisateur est passager -->
    <label>
        <input type="checkbox" name="is_passenger" value="1"
        <?= (!empty($user['is_passenger']) && $user['is_passenger']) ? 'checked' : '' ?>>
        Je suis passager 
    </label><br><br>

    <!-- Bouton pour envoyer le formulaire -->
    <button type="submit">Enregistrer mon rôle</button>
</form>

<!-- Lien pour retourner sur la page du profil de l'utilisateur -->
<p><a href="index.php?page=profile"><= Retour au profil </a></p>