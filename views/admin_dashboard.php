<link rel="stylesheet" href="assets/css/style.css">
<?php require 'views/partials/header.php'; ?>

<h2>Tableau de bord Administrateur</h2>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['flash_success']) ?></p>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <p style="color: red:"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<!-- SECTION 1 : Employés -->
<h3>Employés enregistrés</h3>
<?php if (!empty($employees)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($employees as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['firstname']) ?></td>
                <td><?= htmlspecialchars($e['lastname']) ?></td>
                <td><?= htmlspecialchars($e['email']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun employé enregistré.</p>
<?php endif; ?>

<p>
    <a href="index.php?page=create-employee">
        <button>➕ Créer un compte employé</button>
    </a>
</p>


<!-- SECTION 2 : Utilisateurs -->
<h3>Utilisateurs</h3>
<?php if (!empty($users)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Crédits</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['credits']) ?></td>
                    <td>
                    <?php if (empty($u['is_suspended'])): ?>
                        <form method="POST" action="index.php?page=suspend-user" onsubmit="return confirm('Suspendre cet utilisateur ?');">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button type="submit">🚫 Suspendre</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="index.php?page=unsuspend-user" onsubmit="return confirm('Réactiver cet utilisateur ?');">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button type="submit">✅ Réactiver</button>
                        </form>
                    <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>

    <!-- SECTION 3 : Statistiques -->
    <h3>Statistiques</h3>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <p><strong>Total des crédits attribués : </strong> <?= number_format($totalCredits, 2) ?>

    <h4>Trajets par jour : </h4>
    <?php if (!empty($tripsPerDay)): ?>
        <table border="1" cellpadding="5" cellspacing="0"> 
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nombre de trajets</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tripsPerDay as $stat): ?>
                    <tr>
                        <td><?= htmlspecialchars($stat['date']) ?></td>
                        <td><?= htmlspecialchars($stat['count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun trajet enregistré.</p>
    <?php endif; ?>

    <?php require 'views/partials/footer.php'; ?>

    <h4>Graphique des trajets par jour:</h4>
    <canvas id="tripsChart" width="400" height="200"></canvas>

    <script>
    const ctx = document.getElementById('tripsChart').getContext('2d');
    const tripsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($tripsPerDay, 'date')) ?>,
            datasets: [{
                label: 'Nombre de trajets',
                data: <?= json_encode(array_column($tripsPerDay, 'count')) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
