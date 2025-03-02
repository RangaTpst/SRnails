<?php include __DIR__ . '/../layouts/header.php'; ?> 

<link rel="stylesheet" href="/SRnails/public/assets/css/login.css">

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
// Vérification côté client du formulaire d'inscription
document.getElementById('register-form').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;

    // Vérification que tous les champs sont remplis
    if (username === '' || email === '' || password === '' || passwordConfirm === '') {
        alert("Tous les champs doivent être remplis.");
        e.preventDefault(); // Annule la soumission du formulaire
        return;
    }

    // Vérification de la validité de l'email
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!emailPattern.test(email)) {
        alert("L'email n'est pas valide.");
        e.preventDefault();
        return;
    }

    // Vérification que les mots de passe correspondent
    if (password !== passwordConfirm) {
        alert("Les mots de passe ne correspondent pas.");
        e.preventDefault();
        return;
    }

    // Vérification de la sécurité du mot de passe
    if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
        alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.");
        e.preventDefault();
        return;
    }
});
</script>
