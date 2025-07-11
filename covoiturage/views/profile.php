<h2> Mon espace personnel </h2>

<?php
if (!empty($_SESSION['flash_success'])):
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['flash_success']) . '</p>';
    unset($_SESSION['flash_success']); // on l'efface après l'affichage
endif;
?>
<pre><?php print_r($user); ?></pre>
<?php if (!empty($user)): ?>
    <p><strong>Prénom : </strong> <?= htmlspecialchars($user['firstname']) ?></p>
    <p><strong>Nom : </strong> <?= htmlspecialchars($user['lastname']) ?></p>
    <p><strong>Email : </strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Crédits : </strong> <?= htmlspecialchars($user['credits']) ?> </p>
<?php else:  ?>
    <p> Impossible de charger vos informations. </p>
<?php endif; ?>

<p>
    <a href="index.php?page=edit-profile">
        <button> Modifier mes informations </button>
    </a>
</p>

<p>
    <a href="index.php?page=select-role">
        <button> Declarer mon rôle (chauffeur / passager) </button>
    </a>
</p>

<?php 
// Vérifie si l'utilisateur est défini comme chauffeur 
// Cela signifie que la clé "is_driver" existe et qu'elle a une valeur "vraie"
if (!empty($user['is_driver']) && $user['is_driver'] == 1): ?>
    <!-- Si l'utilisateur est chauffeur, on affiche un bouton pour ajouter un véhicule -->
    <p>
        <a href="index.php?page=add-vehicle">
            <button>Ajouter un véhicule</button>
        </a>
    </p>
<?php endif; ?> <!-- Fin de la condition -->

<?php if (!empty($vehicles)): ?>

    <h3>Mes Véhicules</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Couleur</th>
                <th>Energie</th>
                <th>Immatriculation</th>
                <th>Places</th>
                <th>Préférences</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $v) : ?>
                <tr>
                    <td><?= htmlspecialchars($v['brand']) ?></td>
                    <td><?= htmlspecialchars($v['model']) ?></td>
                    <td><?= htmlspecialchars($v['color']) ?></td>
                    <td><?= htmlspecialchars($v['energy']) ?></td>
                    <td><?= htmlspecialchars($v['plate_number']) ?></td>
                    <td><?= htmlspecialchars($v['seats']) ?></td>
                    <td><?= htmlspecialchars($v['preferences']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun véhicule enregistré.</p>
<?php endif; ?>

<?php if (!empty($trips)): ?>
    <h3> Mes trajets </h3>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Départ</th> <!-- Ville de départ -->
                <th>Arrivée</th> <!-- Ville d'arrivée -->
                <th>Date & Heure </th> <!-- Date et heure du trajet -->
                <th>Véhicules</th> <!-- Infos sur le véhicule utilisé -->
                <th>Places</th> <!-- Nombre de places disponibles -->
                <th>Prix (euro)</th> <!-- Prix par passager -->
                <th>Actions</th> <!-- Colonne pour modifier/supprimer -->
            </tr>
        </thead>
        <tbody>
            <!-- Boucle sur tous les trajets -->
             <?php foreach ($trips as $trip): ?>
                <tr>
                    <td><?= htmlspecialchars($trip['departure_city']) ?> </td>
                    <td><?= htmlspecialchars($trip['arrival_city']) ?> </td>
                    <td>
                        <?php 
                        if (!empty($trip['departure_datetime']) && strtotime($trip['departure_datetime']) !== false) {
                        echo date('d/m/Y H:i', strtotime($trip['departure_datetime']));
                        } else {
                        echo '<span class="text-muted">Date non définie</span>';
                        }
                        ?>
                    </td>


                    <td><?= htmlspecialchars($trip['brand']) ?>
                    <td><?= htmlspecialchars($trip['seats_available']) ?>
                    <td><?= htmlspecialchars($trip['price']); ?> euros
                    <td>
                        <!-- Formulaire pour supprimer un trajet -->
                        <form method="POST" action="index.php?page=delete-trip" onsubmit="return confirm('Supprimer ce trajet ?');">
                            <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                            <button type="submit"> Supprimer</button>
                        </form>

                        <!-- Formulaire pour modifier un trajet -->
                        <a href="index.php?page=edit-trip&id=<?= $trip['id'] ?>">
                            <button> Modifier</button>
                        </a>
                    </td>
                </td>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Si aucun trajet n'est enregistré -->
     <p> Aucun trajet enregistré. </p>
<?php endif; ?>

<!-- Ajouter un bouton "ajouter un trajet dans le profil chauffeur" -->
<?php if (!empty($user['is_driver']) && $user['is_driver'] == 1): ?>
    <p>
        <a href="index.php?page=add-trip">
            <button>Ajouter un trajet</button>
        </a>
    </p>
<?php endif; ?>