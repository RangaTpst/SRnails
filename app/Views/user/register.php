<?php include __DIR__ . '/../layouts/header.php'; ?>

<main>
    <h2>Inscription</h2>
    <form action="/SRnails/public/user/register/process" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        
        <label for="password_confirm">Confirmer le mot de passe :</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
        
        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="/SRnails/public/user/login">Connectez-vous ici</a>.</p>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
