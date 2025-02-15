<?php
session_start();
$user = null;
if (isset($_SESSION['user_id'])) {
    $user = \App\Controllers\UserController::getLoggedInUser();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SR Nails'; ?></title>
    <link rel="stylesheet" href="/SRnails/public/assets/css/styles.css">
</head>

<header>
    <div class="header-container">
        <h1>SRnails</h1>
        <nav>
            <ul>
                <li><a href="/SRnails/public">Accueil</a></li>
                <?php if ($user): ?>
                    <li>Bonjour, <?= htmlspecialchars($user['username']) ?>!</li>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li><a href="/SRnails/public/admin/dashboard">Tableau de bord</a></li>
                    <?php endif; ?>
                    <li><a href="/SRnails/public/user/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/SRnails/public/user/login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
