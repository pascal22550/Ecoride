<h2> Modifier un trajet</h2>

<form method="POST" action="index.php?page=edit-trip&id=<?= $trip['id'] ?>">
    <label>Ville de départ :</label>
        <input type="text" name="departure_city" value="<?= htmlspecialchars($trip['departure_city']) ?>" required>
    </label><br>

    <label>Ville d'arrivée :
        <input type="text" name="arrival_city" value="<?= htmlspecialchars($trip['arrival_city']) ?>" required>
    </label><br>

    <label>Date et heure de départ : ??
        <input type="datetime-local" name="departure_datetime" value="<?= date('Y-m-d\TH:i', strtotime($trip['departure_datetime'])) ?>" required> 
    </label><br>

    <label>Date et heure d'arrivée : ?
        <input type="datetime-local" name="arrival_datetime" value="<?= date('Y-m-d\TH:i', strtotime($trip['arrival_datetime'])) ?>" required>
    </label>

    <label>Place disponibles : /form>
        <input type="number" name="seats_available" value="<?= htmlspecialchars($trip['seats_available']) ?>" min="1" required>
    </label><br>

    <label>Prix (Euro) :
        <input type="number" name="price" value="<?= htmlspecialchars($trip['price']) ?>" step="0,01" required>
    </label><br>

    <button type="submit"> Enregistrer</button>
</form>