<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/styles.css">

<main class="contact-page">
    <section class="contact-intro">
        <h2>Contactez-nous 📩</h2>
        <p>Une question ? Une demande particulière ? Nous sommes là pour vous répondre !</p>
    </section>

    <section class="contact-container">
        <!-- Bloc de gauche : Formulaire -->
        <div class="contact-form">
            <form action="#" method="POST">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </div>
        <!-- Bloc de droite : localisation -->
        <div class="contact-location">
        <iframe 
    src="https://www.google.com/maps?q=Port+de+Vannes,+Vannes,+France&hl=fr&z=14&output=embed" 
    width="100%" 
    height="100%" 
    style="border:0; border-radius: 12px;" 
    allowfullscreen="" 
    loading="lazy">
</iframe>

        </div>

        
        
    </section>

    <section class="contact-info">
        <h3>📬 Autres moyens de nous joindre</h3>
        <p>Email : <a href="mailto:contact@srnails.fr">contact@srnails.fr</a></p>
        <p>Instagram : <a href="https://instagram.com" target="_blank">@srnails</a></p>
    </section>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
