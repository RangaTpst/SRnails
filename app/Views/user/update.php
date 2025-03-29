<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/login.css">
<body>
    <div class="wrapper">
        <?php include __DIR__ . '/../layouts/header.php'; ?> 

        <main>
            <form action="/SRnails/public/user/update" method="POST">
                <h2>Modifier mes informations</h2>

                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <button type="submit">Mettre à jour</button>
            </form>
        </main>

        <?php include __DIR__ . '/../layouts/footer.php'; ?>
    </div>
</body>
