<?php
require_once __DIR__ . '/../../config/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/confermation.css?v=<?= time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>Confirmation de commande - Lfarkha Dahabia</title>
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
        <a href="?rout=Deconnexion" class="block text-red-600 hover:text-red-800">Déconnexion</a>
    </div>
</div>

            <?php else: ?>
                <a href="?rout=connexion" class="btn-connexion">Connexion</a>
            <?php endif; ?>
        </div>
    </header>
    <!-- Main Content -->
    <main class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                Confirmation de commande
            </h1>
            <div class="breadcrumb">
                <span>Accueil</span>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span>Panier</span>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span class="font-semibold">Confirmation</span>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($_SESSION['success_message']) ?></span>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($_SESSION['error_message']) ?></span>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Main Content Grid -->
        <?php if (empty($produits)): ?>
            <div class="empty-state">
                <i class="empty-icon fas fa-shopping-cart"></i>
                <p class="empty-text">Votre panier est vide.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Products Column -->
                <div class="lg:col-span-2">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Articles dans votre commande (<?= count($produits) ?> articles)
                        </div>
                        <div class="card-content p-0">
                            <?php foreach ($produits as $produit): ?>
                                <div class="product-item">
                                    <img src="<?= htmlspecialchars($produit['imgProduit']) ?>" alt="<?= htmlspecialchars($produit['nomProduit']) ?>" class="product-image">
                                    <div class="product-info">
                                        <div class="product-name"><?= htmlspecialchars($produit['nomProduit']) ?></div>
                                        <div class="product-details">
                                            <span><i class="fas fa-tag mr-1"></i>Prix unitaire: <?= htmlspecialchars($produit['prix']) ?> DH</span>
                                            <span><i class="fas fa-boxes mr-1"></i>Quantité: <?= $produit['QTE'] ?></span>
                                        </div>
                                    </div>
                                    <div class="product-price"><?= ($produit['QTE'] * $produit['prix']) ?> DH</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Delivery Details Column -->
                <div class="lg:col-span-1">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-truck mr-2"></i>
                            Détails de livraison
                        </div>
                        <div class="card-content">
                            <form action="?rout=confirmation" method="post">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user mr-2"></i>Prénom
                                    </label>
                                    <input type="text" value="<?= htmlspecialchars($user['prenomUtil']) ?>" class="form-input" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user mr-2"></i>Nom
                                    </label>
                                    <input type="text" value="<?= htmlspecialchars($user['nomUtil']) ?>" class="form-input" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope mr-2"></i>Email
                                    </label>
                                    <input type="email" value="<?= htmlspecialchars($user['emailUtil']) ?>" class="form-input" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone mr-2"></i>Téléphone
                                    </label>
                                    <input type="text" value="<?= htmlspecialchars($user['telUtil']) ?>" class="form-input" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Adresse de livraison *
                                    </label>
                                    <textarea name="adresse" class="form-input textarea" placeholder="Veuillez entrer votre adresse complète de livraison..." required></textarea>
                                    <?php if (isset($_SESSION['error_message']) && $_SESSION['error_message'] === "L'adresse de livraison est requise."): ?>
                                        <p class="text-red-600 text-sm mt-1">Veuillez entrer une adresse de livraison.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Total Summary -->
                                <div class="total-summary">
                                    <div class="total-row">
                                        <span><i class="fas fa-calculator mr-2"></i>Total</span>
                                        <span><?= number_format($totalCommande, 2) ?> DH</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-full">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Confirmer la commande
                                </button>
                            </form>

                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                    En procédant au paiement, vous acceptez nos 
                                    <a href="#" class="text-blue-600 hover:underline font-medium">conditions générales</a> et notre 
                                    <a href="#" class="text-blue-600 hover:underline font-medium">politique de confidentialité</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Continue Shopping -->
        <div class="text-center mt-8">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Continuer mes achats
            </a>
        </div>
    </main>

    <!-- Footer -->
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
    <script src="/Lfarkha-Dahabia-site-e-commerce/public/confermation.js"></script>

</body>
</html>