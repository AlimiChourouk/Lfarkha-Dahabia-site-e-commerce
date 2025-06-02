<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/contact.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <title>Contact - Beldi Avicole</title>
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
            <?php if (isset($_SESSION['idUtilisateur']) && !empty($_SESSION['idUtilisateur'])): ?>
                <button onclick="toggleUserMenu()" class="text-gray-600 hover:text-black focus:outline-none" style="background: none; border: none;">
                    <i class="fas fa-user text-2xl"></i>
                </button>
                <div id="userMenu" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
    <div class="px-4 py-3 text-sm text-gray-700 space-y-2">
        <p><strong>Nom :</strong> <?= htmlspecialchars($_SESSION['nomUtil'] ?? 'Inconnu') ?></p>
        <a href="?rout=dashboard" class="block text-blue-600 hover:underline"> Mon Profil</a>
            </div>
    <div class="border-t px-4 py-2">
        <a href="deconnexion.php" class="block text-red-600 hover:text-red-800">Déconnexion</a>
    </div>
</div>

            <?php else: ?>
                <a href="?rout=connexion" class="btn-connexion">Connexion</a>
            <?php endif; ?>
        </div>
    </header>
<div class="hero-banner">
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
                        <p>+212 522 123 456</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-content">
                        <h3>Email</h3>
                        <p>contact@beldi-poulet.ma</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-content">
                        <h3>Adresse</h3>
                        <p>123 Avenue Mohammed V<br>Casablanca, 20250<br>Maroc</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                    <div class="info-content">
                        <h3>Heures d'Ouverture</h3>
                        <p>Lun-Ven: 9h-18h | Sam: 9h-13h</p>
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
    <script src="/Lfarkha-Dahabia-site-e-commerce/public/javaScripte.js"></script>
</body>
</html>