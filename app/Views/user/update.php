<?php include __DIR__ . '/../layouts/header.php'; ?>

<main>
    <h2>Modifier mes informations</h2>

<form action="/SRnails/public/user/update" method="POST">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <button type="submit">Mettre à jour</button>
</form>

</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
