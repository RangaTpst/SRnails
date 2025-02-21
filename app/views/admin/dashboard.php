<?php include __DIR__ . '/../layouts/header.php'; ?>

<main>
    <h2>👑 Tableau de bord administrateur</h2>

    <section>
        <h3>📋 Liste des utilisateurs</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['is_admin'] ? '✅ Oui' : '❌ Non' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
