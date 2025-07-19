<h2>Tableau de bord Administrateur</h2>

<?php if (!empty($_SESSION['flash_success'])); ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['flash_success']) ?></p>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <p style="color: red:"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
    <?php unset($_SESSION['flash_error']; ?>
<?php endif; ?>

<!-- SECTION 1 : Employés -->
<h3>Comptes Employés</h3>
<?php if (!empty($employees)): ?>
    <ul>
        <?php foreach ($employees as $e): ?>
            <li><?= htmlspecialchars($e['firstname'] . ' ' . $e['lastname'] . ' (' . $e['email'] . ')') ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun employé enregistré.</p>
<?php endif; ?>

<p>
    <a href="index.php?page=create-employee">
        <buton>Créer un compte employé</button>
    </a>
</p>

<!-- SECTION 2 : Utilisateurs -->
<h3>Utilisateurs</h3>
<?php if (!emtpy($users)): ?>
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
            <?php foreach ($uers as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['credit']) ?></td>
                    <td>
                        <?php if (empty($u['is_suspended'])): ?>
                            <form method="POST" action="index.php?page=suspend-user" onsubmit="return confirm('Suspendre cet utilisateur ?');">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <button type=submit">🚫 Suspendre</button>
                            </form>
                        <?php else: ?>
                            <span style="color: red;">Suspendu</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>
    