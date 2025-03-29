<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = null;
if (isset($_SESSION['user_id'])) {
    $user = \App\Controllers\UserController::getLoggedInUser();
}

// Détection de l'environnement pour gérer les chemins
$baseUrl = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') 
    ? '/SRnails/public' 
    : '';
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
