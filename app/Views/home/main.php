<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/styles.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/carousel.css">

<div class="wrapper">

    <!-- Carrousel -->
    <section class="carousel">
        <div class="carousel-track">
            <div class="carousel-item" style="background-image: url('<?= $baseUrl ?>/assets/img/slide_nos_produits.png');">
                <a href="/SRnails/public/articles">
                    <h3>Découvrez nos produits</h3>
                </a>
            </div>
            <div class="carousel-item" style="background-image: url('<?= $baseUrl ?>/assets/img/slide_a_propos.png');">
                <a href="/SRnails/public/about">
                    <h3>À propos de SR Nails</h3>
                </a>
            </div>
            <div class="carousel-item" style="background-image: url('<?= $baseUrl ?>/assets/img/slide_contact.png');">
                <a href="/SRnails/public/contact">
                    <h3>Contactez-nous 📩</h3>
                </a>
            </div>
        </div>
        <button class="carousel-button prev">❮</button>
        <button class="carousel-button next">❯</button>
    </section>

    <main>

        <section class="intro">
            <h2>Bienvenue chez SR Nails 🌿</h2>
            <p>Inspirées par la nature, nos créations d’onglerie vous offrent élégance et bien-être avec des produits soigneusement sélectionnés.</p>
        </section>

        <section class="categories">
    <h3>✨ Nos univers</h3>
    <div class="category-list">
        <a href="/SRnails/public/articles?category[]=faux%20ongles" class="category-item faux-ongles">💅 Faux-ongles personnalisés</a>
        <a href="/SRnails/public/articles?category[]=accessoires" class="category-item accessoires">🌸 Accessoires</a>
        <a href="/SRnails/public/articles?category[]=soins" class="category-item soins">🌿 Soins des ongles</a>
        <a href="/SRnails/public/articles?category[]=coffret" class="category-item coffrets">🎁 Coffrets cadeaux</a>
    </div>
</section>


        <section class="featured-products">
        <h3>Les nouveautés</h3>
        <div class="article-grid">
            <?php foreach (array_slice($articles, 0, 4) as $article): ?>
                <a href="/SRnails/public/article/<?= $article['id'] ?>" class="tile">
                    <img src="<?= $baseUrl ?>/assets/img/articles/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                    <div class="overlay">
                        <h3><?= htmlspecialchars($article['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        </section>


        <section class="testimonials">
            <h3>Ce qu'en pensent nos clients 💬</h3>
            <blockquote>"Magnifique qualité et livraison rapide, je recommande à 100% !" – Laura</blockquote>
            <blockquote>"Les designs sont sublimes et tiennent super bien !" – Sarah</blockquote>
        </section>

        <section class="instagram">
            <h3>Rejoignez-nous sur Instagram 📸</h3>
            <p>Découvrez nos dernières créations et inspirations directement sur nos réseaux sociaux.</p>
            <a href="https://instagram.com" target="_blank">Voir notre Instagram</a>
        </section>

        <section class="why-us">
            <h3>Pourquoi choisir SR Nails ?</h3>
            <ul>
                <li>🌿 Produits inspirés par la nature</li>
                <li>🚚 Livraison rapide et soignée</li>
                <li>❤️ Créations artisanales uniques</li>
                <li>🛡️ Paiement 100% sécurisé</li>
            </ul>
        </section>

        <section class="newsletter">
            <h3>Inscrivez-vous à notre newsletter ✉️</h3>
            <form action="<?= $baseUrl ?>/newsletter/subscribe" method="POST">
                <input type="email" name="email" placeholder="Votre email" required>
                <button type="submit">Je m’inscris</button>
            </form>
        </section>

    </main>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</div>

<script src="<?= $baseUrl ?>/assets/js/carousel.js"></script>
