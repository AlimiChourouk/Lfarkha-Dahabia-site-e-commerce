<?php
require_once __DIR__ . '/../../config/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/favorit.css?v=<?= time(); ?>">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>Favoris - Lfarkha Dahabia</title>
</head>
<body>
<header>
        <nav class="menu">
            <a href="index.php">Accueil</a>
            <a href="?rout=produits">Produit</a>
            <a href="?rout=conseils">Conseils</a>
        </nav>
        <div id="logoCustom-container">
            <img id="logoimg" src="../public/img/logo.png" alt="logo" class="responsive-image" loading="lazy">
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


    <section class="favorites-section">
        <div class="section-header">
            <h2 class="section-title">Vos Favoris</h2>
            <p class="section-subtitle">Retrouvez ici tous vos produits préférés.</p>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (empty($produits)): ?>
            <div class="empty-state">
                <i class="fas fa-heart empty-icon"></i>
                <h3 class="empty-title">Aucun produit dans vos favoris</h3>
                <p class="empty-description">Ajoutez des produits à vos favoris pour les retrouver ici.</p>
                <a href="?rout=produits" class="continue-shopping-btn">
                    <i class="fas fa-shopping-bag"></i> Continuer mes achats
                </a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($produits as $produit): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($produit['imgProduit']) ?>" alt="<?= htmlspecialchars($produit['nomProduit']) ?>">
                            <button class="favorite-btn <?= $isFavori ? 'favorited' : '' ?>" 
        aria-label="<?= $isFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>"
        data-product-id="<?= $produit['idProduit'] ?>">
    <i class="fas fa-heart"></i>
</button>

                        </div>
                        <div class="product-content">
                            <h3 class="product-name"><?= htmlspecialchars($produit['nomProduit']) ?></h3>
                            <div class="product-details">
                                <span>Prix: <?= htmlspecialchars($produit['prix']) ?> DH</span>
                                <span>Stock: <?= htmlspecialchars($produit['quantiteStock']) ?></span>
                            </div>
                            <div class="product-price"><?= htmlspecialchars($produit['prix']) ?> DH</div>
                            <?php if ($produit['quantiteStock'] > 0): ?>
                                <form method="POST" action="?rout=panier/ajouter" class="product-actions">
                                    <input type="hidden" name="idProduit" value="<?= $produit['idProduit'] ?>">
                                    <input type="number" name="qte" value="20" min="20" max="<?= $produit['quantiteStock'] ?>" class="quantity-input">
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="out-of-stock">Rupture de stock</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
                        <i class="fas fa-leaf mr-1"></i>Certifié Bio
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


              <script src="/Lfarkha-Dahabia-site-e-commerce/public/favoris.js?v=<?= time(); ?>"></script>

</body>
</html>