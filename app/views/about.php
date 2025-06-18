<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/about.css?v=<?= time(); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <title>Notre Histoire - Lfarkha Dahabia</title>
    
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
    
    <section id="notre-histoire">
        <div class="hero-histoire">
            <div class="container">
                <h2 class="section-title">Notre Histoire</h2>
                <p class="section-subtitle">Découvrez comment <strong>Lfarkha Dahabia</strong> est devenue une référence dans l'élevage avicole traditionnel de qualité.</p>
            </div>
        </div>
        
        <div class="content-section">
            <div class="container">
                <div class="voyage-section">
                    <div class="voyage-content">
                        <h3 class="voyage-title">Notre Voyage</h3>
                        <p>
                            Fondée en 2008 par la famille Rokaya, <strong>Lfarkha Dahabia</strong> a commencé comme une petite ferme familiale avec seulement quelques dizaines de poules. Notre passion pour l'élevage traditionnel et notre engagement envers la qualité nous ont rapidement permis de nous développer.
                        </p>
                        <p>
                            Au fil des années, nous avons perfectionné nos méthodes d'élevage pour garantir le bien-être de nos volailles tout en offrant des produits avicoles d'une qualité exceptionnelle. Aujourd'hui, notre ferme abrite plusieurs milliers de volailles et s'étend sur plus de 15 hectares.
                        </p>
                        <p>
                            Notre mission reste inchangée : fournir des produits avicoles 100% naturels, élevés selon les méthodes traditionnelles, pour offrir à nos clients une qualité supérieure et un goût authentique.
                        </p>
                    </div>
                    <div class="voyage-image">
                    <img  src="../public/img/Notre_Poule.jpg" 
                    alt="Ferme avicole de qualité" srcset="">
                    </div>
                </div>
                
                <div class="valeurs-section">
                    <div class="valeur-card">
    <h4 class="valeur-title">
        <i class="fas fa-leaf valeur-icon"></i> <br> Respect de la Nature
    </h4>
    <p>Nous nous engageons à utiliser des méthodes d'élevage durables et respectueuses de l'environnement. Nos pratiques préservent la biodiversité et maintiennent l'équilibre écologique.</p>
</div>

<div class="valeur-card">
    <h4 class="valeur-title">
        <i class="fas fa-drumstick-bite valeur-icon"></i> <br> Bien-être Animal
    </h4>
    <p>Nos volailles sont élevées dans des conditions optimales, avec accès à des espaces extérieurs naturels. Elles bénéficient d'une alimentation naturelle et de soins attentifs.</p>
</div>

<div class="valeur-card">
    <h4 class="valeur-title">
        <i class="fas fa-star valeur-icon"></i> <br> Excellence
    </h4>
    <p>Nous nous efforçons constamment d'améliorer nos méthodes d'élevage et la qualité de nos produits. Notre objectif est de toujours offrir le meilleur à nos clients.</p>
</div>

                    </div>
                </div>
                
                <div class="ferme-section">
                    <h3 class="ferme-title">Notre Ferme</h3>
                    <p class="ferme-description">
                        Située dans la région fertile de <strong>Meknès</strong>, notre ferme s'étend sur plus de 15 hectares d'espaces verts où nos volailles peuvent s'épanouir librement.
                    </p>
                    <div class="ferme-images">
                        <div class="ferme-image">
                            <img class="responsive-image" 
                                 src="../public/img/notre_Ferme.jpg" 
                                 alt="Notre Ferme">
                        </div>
                        <div class="ferme-image">
                            <img class="responsive-image" 
                                 src="img/Notre_Produits.jpg" 
                                 alt="Nos Produits" 
                                 loading="lazy" >
                        </div>
                    </div>
                    <h4 class="approche-title">Notre Approche</h4>
                    <ul class="approche-list">
                        <li>Élevage en plein air avec accès à de vastes espaces extérieurs</li>
                        <li>Alimentation 100% naturelle, sans antibiotiques ni hormones</li>
                        <li>Rotation régulière des pâturages pour préserver la qualité du sol</li>
                        <li>Densité d'élevage limitée pour le bien-être des volailles</li>
                        <li>Suivi vétérinaire régulier pour la santé des animaux</li>
                    </ul>
                </div>
            </div>
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

    <script src="/Lfarkha-Dahabia-site-e-commerce/public/scripte.js?v=<?= time(); ?>"></script>
</body>
</html>