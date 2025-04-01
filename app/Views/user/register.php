<?php include __DIR__ . '/../layouts/header.php'; ?> 

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/login.css">
<div id="toast" class="toast" style="display:none;">
    ✅ Votre compte a été créé avec succès ! Redirection...
</div>
<main>


    <!-- Affichage des erreurs côté serveur -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form id="register-form" action="/SRnails/public/user/register/process" method="POST">
    <h2>Inscription</h2>
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        
        <label for="password_confirm">Confirmer le mot de passe :</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
        
        <button type="submit">S'inscrire</button>
        <p>Déjà un compte ? <a href="/SRnails/public/user/login">Connectez-vous ici</a>.</p>
    </form>


</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<script>
document.getElementById('register-form').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;

    if (!username || !email || !password || !passwordConfirm) {
        alert("Tous les champs doivent être remplis.");
        e.preventDefault();
        return;
    }

    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email)) {
        alert("L'email n'est pas valide.");
        e.preventDefault();
        return;
    }

    if (password !== passwordConfirm) {
        alert("Les mots de passe ne correspondent pas.");
        e.preventDefault();
        return;
    }

    if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
        alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.");
        e.preventDefault();
        return;
    }

    // ✅ Affiche la pop-up
    const toast = document.getElementById('toast');
    toast.style.display = 'block';

    // ⏳ Redirige le formulaire 1s plus tard (laisse le toast s'afficher)
    setTimeout(() => {
        document.getElementById('register-form').submit(); // continue l'envoi POST
    }, 1000);

    e.preventDefault(); // stop temporairement la soumission
});
</script>
