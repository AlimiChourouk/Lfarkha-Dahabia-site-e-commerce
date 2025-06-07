<?php
require_once __DIR__ . '/../../config/db.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/connexion.css?v=<?= time(); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>Connexion - Lfarkha Dahabia</title>
</head>
<body>
<header>
    <nav class="menu">
        <a href="index.php">Accueil</a>
        <a href="?rout=produits">Produit</a>
        <a href="?rout=conseils">Conseils</a>
    </nav>
    
   
    <div id="logoCustom-container">
        <img id="logoimg" src="img/logo.png" alt="logo">
        <div class="custom-container">
            <span class="custom-text-1">Lfarkha</span>
            <span class="custom-text-2">Dahabia</span>
        </div>
    </div>
    <div class="menu">
           <a href="index.php?rout=about">À propos</a>
           <a href="?rout=contact">contact</a>
        </div>
    <div class="relative inline-block text-left" style="display: flex; align-items: center; gap: 15px;">
        <a href="?rout=panier" class="cart-icon" title="Voir le panier" style="color: #333; font-size: 22px;">
            <i class="fas fa-shopping-cart"></i>
            <span class="panier-quantite"><?= htmlspecialchars($totalQte) ?></span>
        </a>
        <a href="?rout=favoris" class="heart-outline-btn" title="Mes favoris" style="color: #333; font-size: 22px;">
            <i class="far fa-heart"></i>
        </a>
        <a href="?rout=connexion" class="btn-connexion">Connexion</a>
    </div>
</header>

    <!-- Main content -->
    <section class="login-section">
        <h2 class="login-title">Connexion</h2>

        <!-- Display error message if set -->
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" action="?rout=connexion/login" class="login-form">
            <div class="form-group">
                <label for="email" class="form-label">Email :</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="motPasse" class="form-label">Mot de passe :</label>
                <input type="password" id="motPasse" name="motPasse" class="form-input" required>
            </div>
            <div class="form-submit">
                <button type="submit" class="submit-button">Se connecter</button>
            </div>
        </form>

     
        <div class="auth-links">
            <p>Pas encore inscrit ? <a href="?rout=inscription" class="register-link">Créer un compte</a></p>
            </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Lfarkha Dahabia</h3>
                <p>Votre partenaire de confiance pour des produits avicoles 100% naturels.</p>
            </div>
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="?rout=panier">Panier</a></li>
                    <li><a href="?rout=connexion">Connexion</a></li>
                    <li><a href="?rout=inscription">Inscription</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contactez-nous</h3>
                <p>Email : <a href="mailto:contact@lfarkhadahabia.com">contact@lfarkhadahabia.com</a></p>
                <p>Téléphone : +212 634644220</p>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="img/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> Lfarkha Dahabia. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>