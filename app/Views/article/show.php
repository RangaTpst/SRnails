<?php include __DIR__ . '/../layouts/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['title']) ?></title>
    <link rel="stylesheet" href="/SRnails/public/assets/css/show.css">
</head>
<body>
<div class="wrapper">

<div class="article-container">
    <div class="article-image">
        <?php if (!empty($article['image'])): ?>
            <img src="/SRnails/public/assets/img/articles/<?= htmlspecialchars($article['image']) ?>" alt="Image de l'article">
        <?php else: ?>
            <p>Pas d'image disponible</p>
        <?php endif; ?>
    </div>

    <div class="article-details">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($article['category']) ?></p>
        <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($article['content'])) ?></p>
        <p><strong>Prix :</strong> <?= number_format((float)$article['price'], 2, ',', ' ') ?> €</p>
    </div>
</div>
</div>
</body>
</html>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
