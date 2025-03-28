<?php include __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/SRnails/public/assets/css/dashboard.css">

<body>
    <div class="wrapper">
        <?php include __DIR__ . '/../layouts/header.php'; ?>

        <main>
            <h2>Bienvenue dans votre Dashboard, <?= htmlspecialchars($user['username']) ?></h2>
            <p>Depuis cet espace, vous pouvez consulter vos informations personnelles.</p>

            <p>Nom d'utilisateur : <?= htmlspecialchars($user['username']) ?></p>
            <p>Email : <?= htmlspecialchars($user['email']) ?></p>

            <a href="/SRnails/public/user/update">Modifier mes informations</a>
        </main>

        <?php include __DIR__ . '/../layouts/footer.php'; ?>
    </div>
</body>