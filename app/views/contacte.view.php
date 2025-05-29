<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/contact.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Contact - Beldi Avicole</title>
</head>
<body>
    
  <div class="hero-banner">
    <img src="../public/img/heroContact.jpg" alt="Image de contact" class="hero-image">
    
    <div class="fog-layer"></div>

    <div class="hero-content">
        <h1>Contactez-Nous</h1>
        <p>Nous sommes là pour répondre à toutes vos questions</p>
    </div>
</div>

    <div class="container">
        <div class="contact-section">
            <div class="contact-info">
                <h2>Informations de Contact</h2>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                    <div class="info-content">
                        <h3>Téléphone</h3>
                        <p>+212 634644220</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-content">
                        <h3>Email</h3>
                        <p>contact@lfarkhadahabia.com</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-content">
                        <h3>Adresse</h3>
                        <p>Maroc<br>Meknes<br></p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                    <div class="info-content">
                        <h3>Heures d'Ouverture</h3>
                        <p>24/7</p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h3>Envoyez-Nous un Message</h3>
                <?php if (!empty($this->errorMessage)) : ?>
                    <p class="error"><?= htmlspecialchars($this->errorMessage) ?></p>
                <?php endif; ?>
                <?php if (!empty($this->successMessage)) : ?>
                    <p class="success"><?= htmlspecialchars($this->successMessage) ?></p>
                <?php endif; ?>
                <form action="index.php?rout=contact/envoyer" method="post" class="form-group">
                    <div class="form-group">
                        <label for="contenu">Votre message :</label>
                        <textarea name="contenu" id="contenu" required></textarea>
                    </div>
                    <button type="submit" class="btn">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>