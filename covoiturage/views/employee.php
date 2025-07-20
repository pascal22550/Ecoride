<h2> Espace Employé - Trajets signalés </h2>
<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<?php if (!empty($problemTrips)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID trajet</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Date</th>
                <th>Conducteur</th>
                <th>Email conducteur</th>
                <th>Passager</th>
                <th>Email passager</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($problemTrips as $trip): ?>
                <tr>
                    <td><?= htmlspecialchars($trip['trip_id']) ?></td>
                    <td><?= htmlspecialchars($trip['departure_city']) ?></td>
                    <td><?= htmlspecialchars($trip['arrival_city']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($trip['departure_datetime'])) ?></td>
                    <td><?= htmlspecialchars($trip['driver_firstname']) ?></td>
                    <td><?= htmlspecialchars($trip['passenger_firstname']) ?></td>
                    <td><?= htmlspecialchars($trip['passenger_email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun trajet problématique signalé pour l'instant.</p>
<?php endif; ?>

<?php require 'views/partials/footer.php'; ?>

            






        </tbody>

        </thead>
    
    
    
    
    


    </table>