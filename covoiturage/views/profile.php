<h2> Mon espace personnel </h2>

<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<?php
if (!empty($_SESSION['flash_success'])):
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['flash_success']) . '</p>';
    unset($_SESSION['flash_success']); // on l'efface après l'affichage
endif;
?>
<?php if (!empty($user)): ?>
    <p><strong>Prénom : </strong> <?= htmlspecialchars($user['firstname']) ?></p>
    <p><strong>Nom : </strong> <?= htmlspecialchars($user['lastname']) ?></p>
    <p><strong>Email : </strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Crédits : </strong> <?= htmlspecialchars($user['credits']) ?> </p>


<style>
.star {
    color: #ccc;
    font-size: 10px;
}
.star.filled {
    color: gold;
}
</style>

<?php if (!empty($avgDriverRating)): ?>
    <p><strong>Note moyenne en tant que conducteur :</strong> <?= number_format($avgDriverRating, 1) ?>/5</p>
    <p>
        <?php
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= round($avgDriverRating) ? '⭐' : '☆';
        }
        ?>
    </p>
<?php endif; ?>

<?php if (!empty($avgPassengerRating)): ?>
    <p><strong>Note moyenne en tant que passager :</strong> <?= number_format($avgPassengerRating, 1) ?>/5</p>
    <p>
        <?php
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= round($avgPassengerRating) ? '⭐' : '☆';
        }
        ?>
    </p>
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


                    <td><?= htmlspecialchars($trip['brand']) ?></td>
                    <td><?= htmlspecialchars($trip['seats_available']) ?></td>
                    <td><?= htmlspecialchars($trip['price']); ?> euros</td>
                    <td>

                        <!-- Bouton voir -->
                        <a href="index.php?page=trip-details&id=<?= $trip['id'] ?>">
                            <button>Voir</button>
                        </a>

                        <!-- Formulaire pour modifier un trajet -->
                        <a href="index.php?page=edit-trip&id=<?= $trip['id'] ?>">
                            <button> Modifier</button>
                        </a>

                        <!-- Formulaire pour supprimer un trajet -->
                        <form method="POST" action="index.php?page=delete-trip" onsubmit="return confirm('Supprimer ce trajet ?');">
                            <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                            <button type="submit"> Supprimer</button>
                        </form>

                        <!-- Ajouter ici les boutons Démarrer / Terminer dynamiquement -->
                        <?php if ($trip['is_completed']): ?>
                            <span style="color: green;">Trajet terminé</span>

                        <?php elseif ($trip['is_started']): ?>
                           <form method="POST" action="index.php?page=complete-trip" onsubmit="return confirm('Marquer ce covoiturage comme terminé ?');">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <button type="submit">✅ Arrivée à destination</button>
                            </form>

                        <?php else: ?>
                            <form method="POST" action="index.php?page=start-trip" onsubmit="return confirm('Démarrer ce covoiturage ?');">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <button type="submit">🟢 Démarrer</button>
                            </form>

                        <?php endif; ?>

                        
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Si aucun trajet n'est enregistré -->
     <p> Aucun trajet enregistré. </p>
<?php endif; ?>

<h3> Mes trajets en tant que passager </h3>

<?php if (!empty($joinedTrips)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Conducteur</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Date/Heure</th>
                <th>Véhicule</th>
                <th>Prix</th>
                <th>Confirmation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($joinedTrips as $trip): ?>
                <tr>
                    <td><?= htmlspecialchars($trip['driver_firstname']) ?></td>
                    <td><?= htmlspecialchars($trip['departure_city']) ?></td>
                    <td><?= htmlspecialchars($trip['arrival_city']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($trip['departure_datetime'])) ?></td>
                    <td><?= htmlspecialchars($trip['brand'] . ' ' . $trip['model']) ?></td>
                    <td><?= htmlspecialchars($trip['price']) ?></td>

                    <td>
                        <?php if ($trip['is_completed'] && !$trip['is_confirmed']): ?>
                            <!-- Formulaire de confirmation positive -->
                             <form method="POST" action="index.php?page=confirm-trip" style="display:inline;">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <input type="hidden" name="status" value="ok">
                                <button type="submit">✅ Tout s'est bien passé </button> 
                            </form>

                            <!-- Formulaire de signalement -->
                            <form method="POST" action="index.php?page=confirm-trip" style="display:inline;">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <input type="hidden" name="status" value="problem">
                                <button type="submit">❌ Signaler un problème</button>
                            </form>
                        <?php elseif ($trip['is_confirmed'] == 1): ?>
                            <span style="color:green;">Validé</span>
                        <?php elseif ($trip['is_confirmed'] == -1): ?>
                            <span style="color:red;">Problème signalé</span>
                        <?php else: ?>
                            <span>En attente de validation</span>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <form method="POST" action="index.php?page=cancel-participation" onsubmit="return confirm('Annuler votre participation à ce trajet ?');">
                            <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                            <button type="submit">Annuler ma participation</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p> Vous n'avez pas encore participé à de trajets.</p>
<?php endif; ?>

<!-- Avis reçus en tant que conducteur -->
<?php if (!empty($reviews_received)): ?>
    <h3>Avis reçus en tant que conducteur</h3>   
    <ul>
        <?php foreach ($reviews_received as $r): ?>
            <li><strong><?= htmlspecialchars($r['reviewer_name'])?></strong> : <?= $r['rating'] ?>/5 - <?= htmlspecialchars($r['content'])?></li>
        <?php endforeach; ?>
    </ul> 
<?php endif; ?>

<!-- Avis donnés en tant que passager -->
<?php if (!empty($reviews_given)): ?>
    <h3>Avis donnés en tant que passager</h3>
    <ul>
        <?php foreach ($reviews_given as $r): ?>
            <li><strong><?= htmlspecialchars($r['driver_name']) ?></strong> : <?= $r['rating'] ?>/5 - <?= htmlspecialchars($r['content']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- Ajouter un bouton "ajouter un trajet dans le profil chauffeur" -->
<?php if (!empty($user['is_driver']) && $user['is_driver'] == 1): ?>
    <p>
        <a href="index.php?page=add-trip">
            <button>Ajouter un trajet</button>
        </a>
    </p>
<?php require 'views/partials/footer.php'; ?>

<?php endif; ?>

<?php endif;

