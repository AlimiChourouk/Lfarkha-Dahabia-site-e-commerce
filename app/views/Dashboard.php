<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <title>Tableau de Bord - Dashboard</title>
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/dashboard.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/nav-style.css?v=<?= time(); ?>">
    
    
</head>
<body>
<div class="container">
        <!-- Navigation avec effet de verre et arrière-plan branches -->
        <nav class="glassmorphism-nav text-white shadow-lg fixed w-full top-0 z-50">
            <!-- Particules flottantes -->
            <div class="floating-particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo avec effet de verre -->
                    <div id="logoCustom-container">
            <img id="logoimg" src="../public/img/logo.png" alt="logo" class="responsive-image" loading="lazy">
            <div class="custom-container">
                <span class="custom-text-1">Lfarkha</span>
                <span class="custom-text-2">Dahabia</span>
            </div>
        </div>

                    <!-- Menu principal avec éléments en verre -->
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="index.php" class="nav-link glass-element"><i class="fas fa-home mr-1"></i>Accueil</a>
                        <a href="?rout=produits" class="nav-link glass-element"><i class="fas fa-leaf mr-1"></i>Produit</a>
                        <a href="?rout=conseils" class="nav-link glass-element"><i class="fas fa-lightbulb mr-1"></i>Conseils</a>
                        <a href="index.php?rout=about" class="nav-link glass-element"><i class="fas fa-info-circle mr-1"></i>À propos</a>
                        <a href="?rout=contact" class="nav-link glass-element"><i class="fas fa-envelope mr-1"></i>Contact</a>
                    </div>

                    <!-- Icônes Panier/Connexion avec effet de verre -->
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="?rout=panier" class="nav-icon glass-element" aria-label="Panier">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                        <a href="?rout=connexion" class="nav-icon glass-element" aria-label="Connexion">
                            <i class="fas fa-sign-in-alt"></i>
                        </a>
                    </div>

                    <!-- Bouton Hamburger pour mobile avec effet de verre -->
                    <div class="md:hidden flex items-center">
                        <button id="menu-toggle" class="menu-toggle-glass text-white focus:outline-none" aria-label="Ouvrir le menu">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Menu mobile avec effet de verre -->
                <div id="mobile-menu" class="md:hidden hidden mobile-menu-glass">
                    <div class="px-4 pt-2 pb-3 space-y-1">
                        <a href="index.php" class="mobile-nav-link"><i class="fas fa-home mr-2"></i>Accueil</a>
                        <a href="?rout=produits" class="mobile-nav-link"><i class="fas fa-leaf mr-2"></i>Produit</a>
                        <a href="?rout=conseils" class="mobile-nav-link"><i class="fas fa-lightbulb mr-2"></i>Conseils</a>
                        <a href="index.php?rout=about" class="mobile-nav-link"><i class="fas fa-info-circle mr-2"></i>À propos</a>
                        <a href="?rout=contact" class="mobile-nav-link"><i class="fas fa-envelope mr-2"></i>Contact</a>
                        <a href="?rout=panier" class="mobile-nav-link"><i class="fas fa-shopping-cart mr-2"></i>Panier</a>
                        <a href="?rout=connexion" class="mobile-nav-link"><i class="fas fa-sign-in-alt mr-2"></i>Connexion</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu de démonstration -->
       

        <!-- Décalage pour le contenu en raison du header fixe -->
        <div class="pt-16">
            <header class="header">
                <h1 class="welcome-title">Bienvenue sur votre Dashboard</h1>
                <div class="user-info">
                    <div class="avatar"><?php echo htmlspecialchars(substr($user['prenomUtil'] ?? 'Utilisateur', 0, 1)); ?></div>
                    <div class="user-details">
                        <div class="user-name"><?php echo htmlspecialchars($user['prenomUtil'] ?? 'Utilisateur'); ?></div>
                    </div>
                </div>
                <div class="action-buttons">
                    <a href="#" class="btn btn-primary"><i class="fas fa-cart-plus"></i> Nouvelle Commande</a>
                    <a href="#" class="btn btn-secondary"><i class="fas fa-gear"></i> Paramètres</a>
                    <a href="#" class="btn btn-secondary"><i class="fas fa-chart-bar"></i> Rapports</a>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($commandes); ?></div>
                    <div class="stat-label">Commandes Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo array_sum(array_column($commandes, 'totalMontant')) ?? 0; ?>€</div>
                    <div class="stat-label">Montant Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($favoris); ?></div>
                    <div class="stat-label">Favoris</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">4.8★</div>
                    <div class="stat-label">Satisfaction</div>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon"><i class="fas fa-file-invoice"></i></div>
                        <h2 class="card-title">Historique des Commandes</h2>
                    </div>
                    <?php if (!empty($commandes)): ?>
                        <?php foreach ($commandes as $commande): ?>
                            <div class="order-item">
                                <div class="order-header">
                                    <span class="order-id">Commande #<?php echo htmlspecialchars($commande['idCommande']); ?></span>
                                    <span class="order-amount"><?php echo htmlspecialchars($commande['totalMontant'] ?? 'N/A'); ?>€</span>
                                </div>
                                <div class="order-date"><?php echo htmlspecialchars($commande['dateCommande']); ?> - Livrée</div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                            <p>Aucune commande trouvée.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-icon"><i class="fas fa-star"></i></div>
                        <h2 class="card-title">Mes Favoris</h2>
                    </div>
                    <?php if (!empty($favoris)): ?>
                        <?php foreach ($favoris as $produit): ?>
                            <div class="favorite-item">
                                <div class="favorite-name"><?php echo htmlspecialchars($produit['nomProduit']); ?></div>
                                <div class="favorite-price"><?php echo htmlspecialchars($produit['prix']); ?>€</div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-star"></i></div>
                            <p>Aucun produit en favoris.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
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
                    <li><a href="?rout=favoris"><i class="fas fa-star mr-2"></i>Favoris</a></li>
                    <li><a href="?rout=connexion"><i class="fas fa-sign-in-alt mr-2"></i>Connexion</a></li>
                    <li><a href="?rout=inscription"><i class="fas fa-user-plus mr-2"></i>Inscription</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3><i class="fas fa-envelope mr-2"></i>Contactez-nous</h3>
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
    <script src="/Lfarkha-Dahabia-site-e-commerce/public/Dashboard.js"></script>

</body>
</html>
