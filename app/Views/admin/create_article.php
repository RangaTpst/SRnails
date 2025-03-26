<?php include __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/SRnails/public/assets/css/admin-dashboard.css">

<main>
    <h2>📝 Créer un nouvel article</h2>

    <!-- Affichage des messages -->
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

    <form action="/SRnails/public/admin/article/create" method="POST" enctype="multipart/form-data">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description :</label>
        <textarea id="description" name="content" required></textarea>

        <label for="category">Catégorie :</label>
        <select id="category" name="category" required>
            <option value="">-- Choisir une catégorie --</option>
            <option value="faux ongles">💅 Faux ongles</option>
            <option value="accessoires">🌸 Accessoires</option>
            <option value="soins">🌿 Soins des ongles</option>
            <option value="coffret">🎁 Coffrets</option>
        </select>

        <label for="image">Image :</label>
        <input type="file" id="image" name="image" required>

        <label for="price">Prix (€) :</label>
        <input type="number" step="0.01" id="price" name="price" required>

        <button type="submit">Créer l'article</button>
    </form>

    <p><a href="/SRnails/public/admin/dashboard">← Retour au tableau de bord</a></p>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
