<h1>Liste des articles</h1>
<ul>
<?php foreach ($articles as $article): ?>
    <li>
        <strong><?= htmlspecialchars($article['title']) ?></strong>
        <p><?= htmlspecialchars($article['content']) ?></p>
    </li>
<?php endforeach; ?>
</ul>
