<h2> Mon espace personnel </h2>

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
<?php else:  ?>
    <p> Impossible de charger vos informations. </p>
<?php endif; ?>

<p>
    <a href="index.php?page=edit-profile">
        <button> Modifier mes informations </button>
    </a>
</p>


