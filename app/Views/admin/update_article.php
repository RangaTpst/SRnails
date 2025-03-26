<?php include __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/SRnails/public/assets/css/admin-dashboard.css">

<main>
    <h2>Mise à jour de l'article</h2>

    <!-- Affichage des erreurs ou succès -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Formulaire de mise à jour de l'article -->
    <form action="/SRnails/public/article/<?= $article['id'] ?>/update" method="POST">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>

        <label for="content">Contenu :</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($article['content']) ?></textarea>

        <label for="image">Image (URL) :</label>
        <input type="text" id="image" name="image" value="<?= htmlspecialchars($article['image']) ?>">

        <label for="price">Prix (€) :</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($article['price']) ?>" required>

        <button type="submit">Mettre à jour l'article</button>
    </form>

    <p><a href="/SRnails/public/admin/dashboard">Retourner au tableau de bord</a></p>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
