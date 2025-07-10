<h2> Résultats de recherche </h2>

<?php if (empty($trips)): ?>
    <p> Aucun covoiturage trouvé pour cette recherche. </p>
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Chauffeur</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Date/Heure</th>
                <th>Prix</th>
                <th>Places</th>
                <th>Ecologique</th>
                <th>Details</th>
                <th>Durée (h)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trips as $trip): ?>
                <tr>
                    <td><?= htmlspecialchars($trip['firstname']) ?></td>
                    <td><?= htmlspecialchars($trip['departure_city']) ?></td>
                    <td><?= htmlspecialchars($trip['arrival_city']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($trip['departure_datetime'])) ?></td>
                    <td><?= htmlspecialchars($trip['price']) ?></td>
                    <td><?= htmlspecialchars($trip['seats_available']) ?></td>
                    <td><?= $trip['energy'] === 'électrique' ? 'Oui' : 'Non' ?></td>
                    <td><a href="index.php?page=trip-details&id=<?= $trip['id'] ?>">
                    <td><?= $trip['duration'] ?> h</td>
                        Voir
                    </a>
                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

