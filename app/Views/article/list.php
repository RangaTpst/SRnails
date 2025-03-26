<?php include __DIR__ . '/../layouts/header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nos Articles - SR Nails</title>
    <meta name="description" content="Découvrez tous les articles proposés par SR Nails. Ongles, accessoires et plus encore.">
    <link rel="icon" href="/SRnails/public/assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/SRnails/public/assets/css/list.css">
</head>

<div class="page-layout">
    <aside class="filters">
        <form method="get" action="/SRnails/public/articles">
            <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />

            <h4>Filtrer par prix :</h4>
            <?php
            $priceOptions = [
                '0-10' => 'Moins de 10 €',
                '10-20' => '10 à 20 €',
                '20-999' => 'Plus de 20 €'
            ];
            foreach ($priceOptions as $range => $label):
                $checked = in_array($range, $_GET['price'] ?? []) ? 'checked' : '';
            ?>
                <label>
                    <input type="checkbox" name="price[]" value="<?= $range ?>" <?= $checked ?>> <?= $label ?>
                </label><br>
            <?php endforeach; ?>
            
            <h4>Filtrer par catégorie :</h4>
                <label><input type="checkbox" name="category[]" value="faux ongles"> 💅 Faux ongles</label><br>
                <label><input type="checkbox" name="category[]" value="accessoires"> 🌸 Accessoires</label><br>
                <label><input type="checkbox" name="category[]" value="soins"> 🌿 Soins des ongles</label><br>
                <label><input type="checkbox" name="category[]" value="coffret"> 🎁 Coffrets</label><br>

            <h4>Trier par :</h4>
            <select name="order">
                <option value="">-- Aucun tri --</option>
                <option value="price_asc" <?= ($_GET['order'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
                <option value="price_desc" <?= ($_GET['order'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                <option value="latest" <?= ($_GET['order'] ?? '') === 'latest' ? 'selected' : '' ?>>Nouveautés</option>
            </select>

            <button type="submit">Appliquer</button>
        </form>
    </aside>

    <main class="content">
        <h2>Nos articles</h2>
        <div class="article-grid">
            <?php foreach ($articles as $article): ?>
                <a href="/SRnails/public/article/<?= $article['id'] ?>" class="tile">
                    <img src="/SRnails/public/assets/img/articles/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                    <div class="overlay">
                        <h3><?= htmlspecialchars($article['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
