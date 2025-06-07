<?php 

require_once __DIR__ . '/../../config/db.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/style.css?v=<?= time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>Site de commerce</title>
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
        <a href="?rout=Deconnexion" class="block text-red-600 hover:text-red-800">Déconnexion</a>
    </div>
</div>

            <?php else: ?>
                <a href="?rout=connexion" class="btn-connexion">Connexion</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Affiche un message de succès si présent dans l'URL -->
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_GET['success']) ?></p>
    <?php endif; ?>

    <!-- Affiche un message d'erreur si présent dans l'URL -->
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <!-- Section Hero pour la présentation principale -->
    <section class="hero-section">
        <!-- Image de fond avec superposition -->
        <div class="bg-image">
            <img src="img/hero.png.png" alt="Poulets fermiers" />
        </div>

        <div class="container">
            <div class="flex flex-col items-start max-w-xl">
                <!-- Titre principal -->
                <h1>Qualité 100% Naturelle Garantie</h1>
                <!-- Description -->
                <p>
                    Chez Lfarkha Dahabia, nous sommes engagés à vous fournir des produits avicoles de la plus haute qualité, 
                    élevés naturellement et dans le respect du bien-être animal.
                </p>
                <!-- Boutons d'appel à l'action -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="?rout=produits" class="btn btn-primary">Découvrir nos produits</a>
                    <a href="?rout=about" class="btn btn-secondary">En savoir plus</a>
                </div>
            </div>
        </div>

        <!-- Points forts des services -->
        <div class="service-highlights">
            <div class="container">
                <div class="flex">
                    <div class="service-item">
                        <h3>Livraison Rapide</h3>
                        <p>Livraison à domicile dans les 24 heures suivant votre commande</p>
                    </div>
                    <div class="service-item">
                        <h3>Garantie Fraîcheur</h3>
                        <p>Tous nos produits sont garantis frais, ou remboursés</p>
                    </div>
                    <div class="service-item">
                        <h3>Support Client</h3>
                        <p>Notre équipe est disponible 7j/7 pour répondre à vos questions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section des produits -->
   <section id="section1">
    <h2>Nos Produits</h2>

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
        <?php
        try {
            $query = "SELECT idProduit, nomProduit, age, prix, imgProduit, quantiteStock 
                      FROM produit 
                      LIMIT 4"; // Affiche seulement les 4 premiers produits
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Favoris
            $favoris = [];
            if (isset($_SESSION['idUtilisateur'])) {
                $stmt = $pdo->prepare("SELECT idProduit FROM favoris WHERE idUtilisateur = ?");
                $stmt->execute([$_SESSION['idUtilisateur']]);
                $favoris = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idProduit');
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits : " . $e->getMessage());
            echo "<p style='color: red;'>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
            $produits = [];
        }

        if (empty($produits)) {
            echo "<p>Aucun produit disponible pour le moment.</p>";
        } else {
            foreach ($produits as $produit):
        ?>
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
                             viewBox="0 0 24 24" 
                             fill="<?= in_array($produit['idProduit'], $favoris) ? '#ff0000' : 'none' ?>" 
                             stroke="<?= in_array($produit['idProduit'], $favoris) ? '#ff0000' : '#fff' ?>" 
                             stroke-width="2">
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
                                        <input type="number" name="qte" value="20" min="20" max="<?= $produit['quantiteStock'] ?>" class="w-16 p-1 border border-gray-300 rounded" required>
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
                                <a href="<?= BASE_URL ?>?rout=produit/details&idProduit=<?= $produit['idProduit'] ?>" class="info-btn" aria-label="Voir les détails du produit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12" y2="8"></line>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <span class="out-of-stock">Rupture de stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; } ?>
    </div>
    <a href="?rout=produits" class="continue-shopping">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
    </svg>
    Continuer mes achats
    </a>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Hide quantity inputs
        const inputQtes = document.querySelectorAll('input[name="qte"]');
        inputQtes.forEach(input => {
            input.style.display = "none";
        });

        // Handle heart button clicks
        document.querySelectorAll('.heart-btn').forEach(button => {
            button.addEventListener('click', async function(event) {
                event.preventDefault();

                // Ensure user is logged in
                if (this.hasAttribute('disabled')) {
                    alert('Veuillez vous connecter pour ajouter aux favoris.');
                    return;
                }

                const productId = this.dataset.productId;
                const isFavorited = this.classList.contains('favorited');

                try {
                    console.log(`Attempting to toggle favorite for product ID: ${productId}, currently favorited: ${isFavorited}`);
                    
                    // Correction de l'URL - utiliser BASE_URL et la bonne route
                    const response = await fetch('<?= BASE_URL ?>?rout=favoris/toggle', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest' // Indiquer que c'est une requête AJAX
                        },
                        body: `idProduit=${encodeURIComponent(productId)}`
                    });

                    // Check if response is OK
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log('Server response:', result);

                    if (result.success) {
                        // Toggle the favorited state
                        this.classList.toggle('favorited');
                        const heartIcon = this.querySelector('.heart-icon');
                        
                        if (isFavorited) {
                            // Retirer des favoris
                            heartIcon.style.fill = 'none';
                            heartIcon.style.stroke = '#FFFFFF';
                        } else {
                            // Ajouter aux favoris
                            heartIcon.style.fill = '#ff0000';
                            heartIcon.style.stroke = '#ff0000';
                        }
                        
                        // Optionnel: afficher un message de confirmation
                        // alert(result.message);
                    } else {
                        alert(result.message || 'Erreur lors de la mise à jour des favoris.');
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    alert('Erreur réseau ou serveur. Veuillez réessayer.');
                }
            });
        });
    });
    </script>
</section>
    


<!-- CSS pour les alertes -->

        <!-- Pagination -->
        <?php
        // Compte le nombre total de produits pour la pagination
        $totalQuery = "SELECT COUNT(*) FROM Produit";
        $totalStmt = $pdo->query($totalQuery);
        $totalProducts = $totalStmt->fetchColumn();
        $totalPages = ceil($totalProducts / $itemsPerPage);

        // Affiche les liens de pagination si nécessaire
        if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Section sur l'engagement qualité -->
    <section class="qualite-section">
        <div class="container">
            <div class="image-container">
                <!-- Image illustrant la qualité -->
                <img 
                    src="../public/img/produit.jpeg" 
                    alt="Ferme avicole de qualité"
                    class="qualite-image"
                />
            </div>
            <div class="content-container">
                <h2>Notre Engagement Qualité</h2>
                <p>
                    Chez Lfarkha Dahabia, nous sommes fiers de vous proposer des produits avicoles 
                    de la plus haute qualité. Nos volailles sont élevées avec soin, dans des 
                    conditions respectueuses et naturelles.
                </p>
                <!-- Liste des engagements -->
                <ul>
                    <li>
                        <span class="icon">✓</span>
                        <span><strong>Alimentation 100% naturelle</strong> - Sans OGM, cultivée localement.</span>
                    </li>
                    <li>
                        <span class="icon">✓</span>
                        <span><strong>Élevage en plein air</strong> - Liberté de mouvement assurée.</span>
                    </li>
                    <li>
                        <span class="icon">✓</span>
                        <span><strong>Sans antibiotiques</strong> - Soins naturels uniquement.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>
  
    <section class="testimonials-section">
        <h2 class="testimonials-title">Ce que disent nos clients</h2>
        <div class="testimonials-container">
            <div class="testimonial-card">
                <span class="testimonial-initial">M</span>
                <span class="testimonial-name">Mohammed L.</span>
                <div class="testimonial-rating">★★★★★</div>
                <p class="testimonial-text">"La qualité est incomparable ! Les poulets ont un goût authentique comme ceux qu'élevait ma grand-mère. Service client exemplaire et livraison rapide."</p>
            </div>
            <div class="testimonial-card">
                <span class="testimonial-initial">F</span>
                <span class="testimonial-name">Fatima B.</span>
                <div class="testimonial-rating">★★★★★</div>
                <p class="testimonial-text">"Depuis que j'achète chez Lfarkha Dahabia, ma famille remarque la différence. Des œufs avec un vrai goût et des poulets qui ont de la saveur. Je recommande vivement !"</p>
            </div>
            <div class="testimonial-card">
                <span class="testimonial-initial">K</span>
                <span class="testimonial-name">Karim M.</span>
                <div class="testimonial-rating">★★★★☆</div>
                <p class="testimonial-text">"Excellente qualité de produits et un service après-vente très réactif. Les coqs que j'ai achetés se portent à merveille et les œufs sont délicieux."</p>
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
    

    <script src="/Lfarkha-Dahabia-site-e-commerce/public/scripte.js"></script>

</body>
</html>