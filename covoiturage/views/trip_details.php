<h2> Detail du trajet </h2>

<p><strong>Conducteur : </strong></p> <?=htmlspecialchars(string: $trip['firstname']) ?></p>
<p><strong>Départ : </strong> <?=htmlspecialchars(string: $trip['departure_city']) ?></p>
<p><strong>Arrivée : </strong> <?= htmlspecialchars($trip['arrival_city']) ?></p>
<p><strong>Date: </strong> <?= date('d/m/Y H:i', strtotime($trip['departure_datetim'])) ?></p>
<p><strong>Prix: </strong> <?= htmlspecialchars($trip['price']) ?> euros</p>
<p><strong>Places restantes: </strong> <?= htmlspecialchars($trip['seats_available']) ?></p>

<h3>Véhicule</h3>
<p><strong>Marque:</strong> <?= htmlspecialchars($trip['brand']) ?></p>
<p><strong>Modèle:</strong> <?= htmlspecialchars($trip['model']) ?></p>
<p><strong>Energie:</strong> <?= htmlspecialchars($trip['energy']) ?></p>
<p><strong>Ecologique :</strong> <?= $trip['energy'] === 'électrique' ? 'Oui' : 'Non' ?></p>

<h3>Préférences</h3>
<p><?= htmlspecialchars($trip['preferences'] ?? 'Non spécifiées') ?></p>

<h3>Avis sur le conducteur</h3>

<?php if (!empty($reviews)): ?>
    <ul>
        <?php foreach ($reviews as $r): ?>
            <li> <?= $r['rating'] ?>/5 - <?= htmlspecialchars($r['content']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun avis pour ce conducteur.</p>
<?php endif; ?>