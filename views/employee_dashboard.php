<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['flash_success']) ?></p>
    <?php unset($_SESSION['flash_sucess']); ?>
<?php endif; ?>

<?php if(!empty($_SESSION['flash_error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<!-- Section 1 : Avis à valider -->
<h3> Avis en attente de validation</h3>

<?php if (!empty($reviews)): ?>
    <table border="1" cellpading="5" cellspacing="0">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['reviewer_name']) ?></td>
                    <td><?= htmlspecialchars($r['rating']) ?>/5</td>
                    <td><?= htmlspecialchars($r['content']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                    <td>
                        <form method="POST" action="index.php?page=moderate-review" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit"> ✅ Valider</button>
                        </form>
                        <form method="POST" action="index.php?page=moderate-review" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                            <input type="hidden" name="action" value="reject">
                            <button type="submit"> ❌ Rejeter </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun avis à valider pour le moment.</p>
<?php endif; ?>

<!-- Section 2 : trajets problématique signalés --> 
<h3>Trajets problématiques signalés</h3>

<?php if (!empty($problems)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Conducteur</th>
                <th>Passager</th>
                <th>Email du passager </th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Date et heure</th>
                <th>Prix</th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($problems as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['driver_firstname']) ?></td>
                <td><?= htmlspecialchars($p['passenger_firstname']) ?></td>
                <td><?= htmlspecialchars($p['passenger_email']) ?></td>
                <td><?= htmlspecialchars($p['departure_city']) ?></td>
                <td><?= htmlspecialchars($p['arrival_city']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($p['departure_datetime'])) ?></td>
                <td><?= htmlspecialchars($p['price']) ?> euros </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>Aucun trajet signalé pour le moment.</p>
<?php endif; ?>

<!-- Historique des avis modérés -->

<h3>Historique des avis modérés</h3>

<?php if (!empty($moderated_reviews)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($moderated_reviews as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['reviewer_name']) ?></td>
                    <td><?= htmlspecialchars($r['rating']) ?>/5</td>
                    <td><?= htmlspecialchars($r['content']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                    <td>
                        <?php if ($r['status'] === 'approved'): ?>
                            <pan style="color: green;">Validé</pan>
                        <?php else: ?>
                            <span style="color: red;">Rejeté</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p> Aucun avis validé ou rejeté pour le moment.</p>
<?php endif; ?>






    </table>

    <?php require 'views/partials/footer.php'; ?>

