<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="/SRnails/public/assets/css/admin-dashboard.css">

<div class="wrapper">
    <main>
        <h2><?= $title; ?></h2>
        <p><?= $welcome_message; ?></p>
        <section>
            <h3>Nos produits phares</h3>
            <ul>
                <li>Produit 1</li>
                <li>Produit 2</li>
                <li>Produit 3</li>
            </ul>
        </section>
    </main>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</div>