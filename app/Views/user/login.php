<?php 
include __DIR__ . '/../layouts/header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="/SRnails/public/assets/css/login.css">

<main>
    <div class="container">
        <h2>Connexion</h2>

        <?php if (isset($_SESSION['login_error'])): ?>
            <p class="error-message"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
        <?php endif; ?>

        <form action="/SRnails/public/user/login/process" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Se connecter</button>
            <p>Pas encore de compte ? <a href="/SRnails/public/user/register">Inscrivez-vous ici</a>.</p>
            
        </form>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
