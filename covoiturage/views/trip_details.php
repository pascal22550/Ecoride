<?php if (!empty($_SESSION['flash_success'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['flash_success']) ?></p>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>


<h2> Detail du trajet </h2>

<p><strong>Conducteur : </strong></p> <?=htmlspecialchars(string: $trip['firstname']) ?></p>
<p><strong>Départ : </strong> <?=htmlspecialchars(string: $trip['departure_city']) ?></p>
<p><strong>Arrivée : </strong> <?= htmlspecialchars($trip['arrival_city']) ?></p>
<?php if (!empty($trip['departure_datetime']) && strtotime($trip['departure_datetime']) !== false): ?>
    <?= date('d/m/Y H:i', strtotime($trip['departure_datetime'])) ?>
<?php else: ?>
    <span class="text-muted">Date non définie</span>
<?php endif; ?>
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

<?php if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] !== $trip['user_id'] && $trip['seats_available'] > 0): ?>
    <form method="POST" action="index.php?page=participate">
        <input type="hidden" name="trip_id" value="<?= $trip['trip_id'] ?>">
        <button type="submit">Participer à ce trajet</button>
    </form>
<?php elseif ($trip['seats_available'] <= 0): ?>
    <p style="color:red;">Ce trajet est complet.</p>
<?php endif; ?>


<?php if (!empty($reviews)): ?>
    <ul>
        <?php foreach ($reviews as $r): ?>
            <li> <?= $r['rating'] ?>/5 - <?= htmlspecialchars($r['content']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun avis pour ce conducteur.</p>
<?php endif; ?>