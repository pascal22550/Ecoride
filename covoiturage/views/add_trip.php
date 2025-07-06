<h2> Ajouter un trajet </h2>

<form method="POST" action="index.php?page=add-trip">
    <label> Ville de départ:  /form>
        <input type="text" name="departure_city" required>
    </label><br>

    <label> Ville d'arrivée : 
        <input type="text" name="arrival_city" required>
    </label>

    <label> Date et heure de départ : </label>
        <input type="datetime-local" name="departure_datetime" required>
    </label><br>

    <label>Nombre de places disponibles : 
        <input type="number" name="seats_available" min="1" required>
    </label><br>

    <label>Prix par passager (euro) : </label>
        <input type="number" name="price" step="0.01" required>
</label><br>

<label>Véhicules :
    <select name="vehicle_id" required>
        <?php foreach ($vehicles as $vehicle): ?>
            <option value="<?= $vehicle['id'] ?>">
                <?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model'] . ' (' . $vehicle['plate_number'] . ')') ?>
            </option>
        <?php endforeach; ?>
    </select>
</label><br><br>

<button type="submit">Créer le trajet</button>

</form>