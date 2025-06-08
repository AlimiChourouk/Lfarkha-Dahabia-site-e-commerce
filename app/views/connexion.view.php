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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
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

 <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
               <div id="logoCustom-container">
            <img id="logoimg" src="../public/img/logo.png" alt="logo" class="responsive-image" loading="lazy">
            <div class="custom-container">
                <span class="custom-text-1">Lfarkha</span>
                <span class="custom-text-2">Dahabia</span>
            </div>
        </div>
                <p>Votre partenaire de confiance pour des produits avicoles 100% naturels et de qualité supérieure.</p>
                <div class="mt-4">
                    <span class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full">
                        <i class="fas fa-certificate mr-1"></i>Certifié Bio
                    </span>
                </div>
            </div>
            
            <div class="footer-section">
                <h3><i class="fas fa-link mr-2"></i>Liens rapides</h3>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home mr-2"></i>Accueil</a></li>
                    <li><a href="?rout=produits"><i class="fas fa-leaf mr-2"></i>Nos Produits</a></li>
                    <li><a href="?rout=panier"><i class="fas fa-shopping-cart mr-2"></i>Panier</a></li>
                    <li><a href="?rout=favoris"><i class="fas fa-heart mr-2"></i>Favoris</a></li>
                    <li><a href="?rout=connexion"><i class="fas fa-sign-in-alt mr-2"></i>Connexion</a></li>
                    <li><a href="?rout=inscription"><i class="fas fa-user-plus mr-2"></i>Inscription</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3><i class="fas fa-phone mr-2"></i>Contactez-nous</h3>
                <p><i class="fas fa-envelope mr-2"></i>Email : <a href="mailto:contact@lfarkhadahabia.com">contact@lfarkhadahabia.com</a></p>
                <p><i class="fas fa-phone mr-2"></i>Téléphone : +212 123 456 789</p>
                <p><i class="fas fa-clock mr-2"></i>Lun-Ven : 9h-18h</p>
            </div>
            
            <div class="footer-section">
                <h3><i class="fas fa-share-alt mr-2"></i>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© <?= date('Y') ?> Lfarkha Dahabia. Tous droits réservés. </p>
        </div>
    </footer>
</body>
</html>