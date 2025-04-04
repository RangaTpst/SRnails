<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = null;
if (isset($_SESSION['user_id'])) {
    $user = \App\Controllers\UserController::getLoggedInUser();
}

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SR Nails'; ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/header.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/footer.css">
</head>

<header>
    <div class="header-container">
        <h1>SRnails</h1>
        <button class="menu-toggle">☰</button>
        <nav>
            <ul>
                <li><a href="/SRnails/public">Accueil</a></li>
                <li><a href="/SRnails/public/articles">Nos articles</a></li>
                <?php if ($user): ?>
                    <li><a href="/SRnails/public/user/dashboard">Dashboard</a></li>
                    <?php if ($user['is_admin'] === 1): ?>
                        <li><a href="/SRnails/public/admin/dashboard">Admin</a></li>
                        <li><a href="http://90.121.52.205:50294/d/eehbtohm9getcf/fail2ban?orgId=1&from=2025-03-29T08:24:41.162Z&to=2025-03-29T20:24:41.162Z&timezone=browser&refresh=5s" target="_blank">Grafana</a></li>

                    <?php endif; ?>
                    <li><a href="/SRnails/public/user/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/SRnails/public/user/login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<script src="<?= $baseUrl ?>/assets/js/scripts.js"></script>
