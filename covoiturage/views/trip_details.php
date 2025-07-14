<pre><?php print_r($trip); ?></pre>

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
    <h3>Avis sur le conducteur</h3>
    <ul>
        <?php foreach ($reviews as $r): ?>
            <li style="margin-bottom:10px;">
                <strong>Note : </strong> <?= str_repeat("⭐", $r['rating']) ?> (<?= $r['rating'] ?>/5)<br>
                <strong>Commentaire : </strong> <?= htmlspecialchars($r['content']) ?><br>
                <em style="color:gray;"> 
                    Posté le 
                    <?php
                        if (!empty($r['created_at'])){
                            echo date('d/m/Y à H:i', strtotime($r['created_at']));
                        } else {
                            echo "Date inconnue";
                        }
                    ?>
                </em>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun avis pour ce conducteur.</p>
<?php endif; ?>

<?php
    // Conditions : connecté, passager, inscrit au trajet, pas encore noté
    if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] !== $trip['user_id']) {
        $db = connectDB();

        try {
        // Vérifie s'il a participé
        $stmt = $db->prepare("SELECT * FROM trip_participants WHERE trip_id = ? AND user_id = ?");
        $stmt->execute([$trip['trip_id'], $_SESSION['user_id']]);
        $isParticipant = $stmt->fetch();

        // Vérifie s'il a déjà laissé une note
        $stmt = $db->prepare("SELECT * FROM reviews WHERE trip_id = ? AND reviewer_id = ?");
        $stmt->execute([$trip['trip_id'], $_SESSION['user_id']]);
        $alreadyRated = $stmt->fetch();


        if ($isParticipant && !$alreadyRated):
    ?>

        <h3> Laisser un avis sur le conducteur </h3>
        <form method="POST" action="index.php?page=rate-driver">
            <input type="hidden" name="trip_id" value="<?= $trip['trip_id'] ?>">
            <input type="hidden" name="driver_id" value="<?= $trip['user_id'] ?>">

            <label>Note (1 à 5) : </label>
            <input type="number" name="rating" min="1" max="5" required><br>

            <label>Commentaire :</label><br>
            <textarea name="content" rows="4" cols="40" required></textarea><br>

            <button type="submit">Envoyer mon avis</button>
        </form>
<?php
        endif;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur SQL : " . $e->getMessage() . "</p>";
    }
        
    }
?>

<!-- Notation des passagers (visible uniquement pour le conducteur) -->

<?php
if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] == $trip['user_id']) {
    $db = connectDB();

    // Récupérer les passagers inscrits à ce trajet
    $stmt = $db->prepare("
        SELECT u.id, u.firstname
        FROM trip_participants tp
        JOIN users u ON tp.user_id = u.id
        WHERE tp.trip_id = ?
    ");
    $stmt->execute([$trip['trip_id']]);
    $passengers = $stmt->fetchAll();

    // Pour chaque passager, afficher un formulaire si non encore noté
    foreach ($passengers as $passenger) {
        // Vérifie si une note a été laissée par le conducteur à ce passager
        $stmt = $db->prepare("
            SELECT * FROM reviews
            WHERE trip_id = ? AND passenger_id = ? AND reviewer_id = ?
        ");
        $stmt->execute([$trip['trip_id'], $passenger['id'], $_SESSION['user_id']]);
        $alreadyRated = $stmt->fetch();

        if (!$alreadyRated):
?>

    <h3>Noter <?= htmlspecialchars($passenger['firstname']) ?> (passager) </h3>
    <form method="POST" action="index.php?page=rate-passenger">
        <input type="hidden" name="trip_id" value="<?= $trip['trip_id'] ?>">
        <input type="hidden" name="passenger_id" value="<?= $passenger['id'] ?>">

        <label>Note (1 à 5) : </label>
        <input type="number" name="rating" min="1" max="5" required><br>

        <label>Commentaire : </label><br>
        <textarea name="content" rows="4" cols="40" required></textarea><br>

        <button type="submit">Noter ce passager</button>
    </form>
<?php
        endif;
    }
}
?>

<?php if (!empty($reviews_for_passengers)): ?>
    <h3>Avis laissés sur les passagers</h3>
    <ul>
        <?php foreach ($reviews_for_passengers as $r): ?>
            <li style="margin-bottom:10px;"> 
                <strong>Passager :</strong> <?= htmlspecialchars($r['passenger_name']) ?><br>
                <strong>Note :</strong> <?= str_repeat("⭐", $r['rating']) ?> (<?= $r['rating'] ?>/5)<br>
                <strong>Commentaire : </strong> <?= htmlspecialchars($r['content']) ?><br>
                <em style="color:gray;">Posté le <?= date('d/m/Y à H:i', strtotime($r['created_at'])) ?></em>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>