<h2> Liste des utilisateurs </h2>

<?php if (!empty($error)): ?>
    <!-- Affiche un message d'erreur s'il y a eu un souci avec la base de données -->
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<!-- Tableau HTML pour afficher les données -->
<table style="border: 1px solid black; border-collapse: collapse;">
    <thead>
        <tr>
            <!-- En-têtes du tableau -->
             <th>ID</th>
             <th>Prénom</th>
             <th>Nom</th>
             <th>Email</th>
             <th>Crédits</th>
             <th>Date d'inscription</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($users as $user): ?>
        <!-- On boucle sur chaque utilisateur pour afficher ses données -->
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['firstname']) ?></td>
            <td><?= htmlspecialchars($user['lastname']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['credits']) ?></td>
            <td><?= htmlspecialchars($user['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>









</table>