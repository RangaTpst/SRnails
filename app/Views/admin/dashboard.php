<?php include __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/SRnails/public/assets/css/admin-dashboard.css">

<main>
    <h2>👑 Tableau de bord administrateur</h2>

    <div class="container">
        <!-- Gestion des utilisateurs -->
        <section>
            <h3>📋 Liste des utilisateurs</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['username'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                <td><?= $user['is_admin'] ? '✅ Oui' : '❌ Non' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">Aucun utilisateur trouvé.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Gestion des articles -->
        <section>
            <h3>📚 Gestion des articles</h3>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?= htmlspecialchars($article['title']) ?></td>
                            <td><?= htmlspecialchars($article['description']) ?></td>
                            <td><img src="<?= htmlspecialchars($article['img']) ?>" alt="Image de l'article" style="width: 50px; height: 50px;"></td>
                            <td><?= htmlspecialchars($article['price']) ?> €</td>
                            <td>
                                <!-- Lien vers la page de modification de l'article -->
                                <a href="/SRnails/public/article/<?= $article['id'] ?>/update">Modifier</a> |
                                <a href="/SRnails/public/article/<?= $article['id'] ?>/delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- Création d'un article -->
    <section>
        <h3>📝 Créer un article</h3>
        <form method="post" action="/SRnails/public/article/create">
            <label for="title">Titre :</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Description :</label>
            <textarea id="content" name="description" required></textarea>

            <label for="image">Image (URL) :</label>
            <input type="text" id="image" name="img">

            <label for="price">Prix (€) :</label>
            <input type="number" step="0.01" id="price" name="price" required>

            <button type="submit">Créer l'article</button>
        </form>
    </section>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
