<?php include __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/admin-dashboard.css">

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

    <form action="/SRnails/public/article/<?= $article['id'] ?>/update" method="POST" enctype="multipart/form-data">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>

        <label for="content">Contenu :</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($article['content']) ?></textarea>

        <label for="category">Catégorie :</label>
        <select id="category" name="category" required>
            <option value="">-- Choisir une catégorie --</option>
            <option value="faux ongles" <?= $article['category'] === 'faux ongles' ? 'selected' : '' ?>>💅 Faux ongles</option>
            <option value="accessoires" <?= $article['category'] === 'accessoires' ? 'selected' : '' ?>>🌸 Accessoires</option>
            <option value="soins" <?= $article['category'] === 'soins' ? 'selected' : '' ?>>🌿 Soins des ongles</option>
            <option value="coffret" <?= $article['category'] === 'coffret' ? 'selected' : '' ?>>🎁 Coffrets</option>
        </select>

        <?php if (!empty($article['image'])): ?>
            <div>
                <p>Image actuelle :</p>
                <img src="<?= $baseUrl ?>/assets/img/articles/<?= htmlspecialchars($article['image']) ?>" alt="image actuelle" width="150">
            </div>
        <?php endif; ?>

        <label for="image">Changer l’image :</label>
        <input type="file" id="image" name="image">

        <label for="price">Prix (€) :</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($article['price']) ?>" required>

        <button type="submit">Mettre à jour l'article</button>
    </form>

    <p><a href="/SRnails/public/admin/dashboard">Retourner au tableau de bord</a></p>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
