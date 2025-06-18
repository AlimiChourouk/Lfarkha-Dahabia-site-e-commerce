<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/style.css?v=<?= time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>Produits de la ferme</title>
</head>
<body>
<header>
    <nav class="menu">
        <a href="index.php">Accueil</a>
    </nav>
    </nav>
        <nav class="dropdown">
    <p class="dropdown-btn">Catégorie</p>
    <div class="dropdown-content">
        <a href="?rout=produits">Tous</a>
        <a href="?rout=produits&categorie=Poules">Poules</a>
        <a href="?rout=produits&categorie=Coqs">Coqs</a>
        <a href="?rout=produits&categorie=Poussins">Poussins</a>
        <a href="?rout=produits&categorie=Œufs">Œufs</a>
    </div>
</nav>
<nav class="menu">
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
        <a href="?rout=Deconnexion" class="block text-red-600 hover:text-red-800">Déconnexion</a>
    </div>
</div>

        <?php else: ?>
            <a href="?rout=connexion" class="btn-connexion">Connexion</a>
        <?php endif; ?>
    </div>
</header>
<section id="section1">
<?php if (isset($_GET['categorie'])): ?>
    <h2>Nos <?= htmlspecialchars($_GET['categorie']) ?></h2>
<?php else: ?>
    <h2>Nos Produits</h2>
<?php endif; ?>

<!-- Affichage des messages de succès ou d'erreur -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="produits-container">
    <?php if (empty($produits)): ?>
        <p>Aucun produit disponible pour le moment.</p>
    <?php else: ?>
        <?php foreach ($produits as $produit): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($produit['imgProduit']) ?>" 
                     alt="<?= htmlspecialchars($produit['nomProduit']) ?>" 
                     loading="lazy">
                
                <div class="top-actions">
                    <button class="heart-btn <?= in_array($produit['idProduit'], $favoris) ? 'favorited' : '' ?>" 
                            aria-label="Ajouter aux favoris"
                            data-product-id="<?= $produit['idProduit'] ?>"
                            <?= !isset($_SESSION['idUtilisateur']) ? 'disabled' : '' ?>>
                        <svg class="heart-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>

                <div class="product-info">
                    <h3><?= htmlspecialchars($produit['nomProduit']) ?></h3>
                    <p>Âge: <?= htmlspecialchars($produit['age']) ?> mois</p>
                    <p>Stock: <?= htmlspecialchars($produit['quantiteStock']) ?></p>
                    
                    <div class="price-and-actions">
                        <div class="product-price"><?= htmlspecialchars($produit['prix']) ?> DH</div>

                        <div class="product-actions">
                          <?php if ($produit['quantiteStock'] > 0): ?>
    <?php if (isset($_SESSION['idUtilisateur']) && !empty($_SESSION['idUtilisateur'])): ?>
        <form method="POST" action="<?= BASE_URL ?>?rout=panier/ajouter" style="display: flex; align-items: center; gap: 8px;">
            <input type="hidden" name="idProduit" value="<?= $produit['idProduit'] ?>">
            <input type="number" name="qte" 
                   value="<?= $produit['categorie'] === 'Œufs' ? 20 : 10 ?>" 
                   min="<?= $produit['categorie'] === 'Œufs' ? 20 : 10 ?>" 
                   max="<?= $produit['quantiteStock'] ?>" 
                   class="w-16 p-1 border border-gray-300 rounded" 
                   required 
                   style="display: none;">
            <button type="submit" class="cart-btn" aria-label="Ajouter au panier">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </button>
        </form>
    <?php else: ?>
        <a class="cart-btn" href="<?= BASE_URL ?>?rout=connexion" aria-label="Connexion pour ajouter au panier">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </a>
    <?php endif; ?>
<?php else: ?>
    <span class="out-of-stock">Rupture de stock</span>
<?php endif; ?>
                                   
                            

                                <a href="<?= BASE_URL ?>?rout=produit/details&idProduit=<?= $produit['idProduit'] ?>" class="info-btn" aria-label="Voir les détails du produit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12" y2="8"></line>
                                    </svg>
                                </a>
                            
                                
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</section>

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
    <script src="/Lfarkha-Dahabia-site-e-commerce/public/scripte.js?v=<?= time(); ?>"></script>
</body>
</html>