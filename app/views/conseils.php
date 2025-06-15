<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <title>Conseils Projet Avicole</title>

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
    <div class="hero-banner">
            <div>
            <h1>Conseils pour Démarrer un Projet Avicole</h1> <br>
            <p class="subtitle">Guide complet pour entrepreneurs débutants</p>
        </div>
    </div>

        <div class="content-section">
            <div class="intro-text">
                Découvrez les questions essentielles à vous poser avant de vous lancer dans l'aventure avicole. Chaque point vous aidera à construire un projet solide et durable.
            </div>

            <div class="question">
                <strong>1. Pourquoi veux-tu te lancer dans un projet avicole ?</strong>
                <div class="reponse">Identifie ta motivation (revenu, passion, opportunité). Cela t'aidera à garder le cap même en cas de difficulté.</div>
            </div>

            <div class="question">
                <strong>2. As-tu identifié un marché pour tes produits ?</strong>
                <div class="reponse">Fais une étude de marché locale : qui sont tes clients ? Quels sont les prix des œufs, poules ou poussins dans ta région ?</div>
            </div>

            <div class="question">
                <strong>3. As-tu un emplacement adapté ?</strong>
                <div class="reponse">Choisis un endroit propre, sécurisé, bien ventilé et facile d'accès pour l'élevage ou la distribution.</div>
            </div>

            <div class="question">
                <strong>4. De quel budget as-tu besoin pour commencer ?</strong>
                <div class="reponse">Prévois les coûts initiaux : achat de volailles, nourriture, équipements, vaccins, eau, transport, etc.</div>
            </div>

            <div class="question">
                <strong>5. As-tu des connaissances en soins et alimentation ?</strong>
                <div class="reponse">Renseigne-toi sur les besoins nutritionnels, les maladies courantes, et les méthodes de vaccination.</div>
            </div>

            <div class="question">
                <strong>6. As-tu un plan de vente ?</strong>
                <div class="reponse">Prévois comment tu vas vendre : en ligne, dans les marchés, en livraison ou en partenariat avec des commerçants.</div>
            </div>

            <div class="question">
                <strong>7. As-tu réfléchi à une identité de marque ?</strong>
                <div class="reponse">Crée un nom, un logo et un emballage qui donne confiance et attire les clients.</div>
            </div>

            <div class="question">
                <strong>8. Comment vas-tu assurer la qualité ?</strong>
                <div class="reponse">Utilise une bonne alimentation, respecte l'hygiène et propose toujours des produits frais et sains.</div>
            </div>

            <div class="question">
                <strong>9. Es-tu prêt pour les imprévus ?</strong>
                <div class="reponse">Prépare-toi à gérer les pertes, maladies ou périodes de baisse des ventes. Garde un fonds d'urgence.</div>
            </div>

            <div class="question">
                <strong>10. As-tu des soutiens ou partenaires ?</strong>
                <div class="reponse">Trouve des personnes de confiance, des coopératives ou des groupes qui peuvent t'accompagner ou t'aider.</div>
            </div>

            <div class="question">
                <strong>11. Quel type d'élevage souhaites-tu ? (poules pondeuses, poulets de chair, élevage mixte)</strong>
                <div class="reponse">Choisis selon la demande locale et ta capacité à gérer chaque type (cycle, alimentation, espace).</div>
            </div>

            <div class="question">
                <strong>12. Comment assurer la gestion de l'eau et de l'alimentation ?</strong>
                <div class="reponse">Installe des abreuvoirs propres et accessibles, et choisis une alimentation équilibrée adaptée à chaque étape de croissance.</div>
            </div>

            <div class="question">
                <strong>13. As-tu prévu un plan sanitaire ?</strong>
                <div class="reponse">Élabore un planning de vaccination et de prévention contre les maladies avicoles, en consultant un vétérinaire si possible.</div>
            </div>

            <div class="question">
                <strong>14. Quel système de suivi vas-tu mettre en place ?</strong>
                <div class="reponse">Note régulièrement la croissance, la ponte, les éventuelles maladies et la mortalité pour ajuster ta gestion.</div>
            </div>

            <div class="question">
                <strong>15. As-tu réfléchi à la gestion des déchets ?</strong>
                <div class="reponse">Planifie le compostage ou l'élimination des fientes et déchets pour maintenir la propreté et éviter la pollution.</div>
            </div>

            <div class="question">
                <strong>16. Comment vas-tu te former et rester informé ?</strong>
                <div class="reponse">Suis des formations agricoles, lis des livres spécialisés et échange avec des professionnels ou coopératives.</div>
            </div>

            <div class="question">
                <strong>17. As-tu pensé à la saisonnalité et à la gestion des périodes creuses ?</strong>
                <div class="reponse">Prévois comment gérer la production en basse saison, avec des stratégies de stockage ou diversification des produits.</div>
            </div>

            <div class="question">
                <strong>18. Quel est ton plan de croissance sur 1 an ?</strong>
                <div class="reponse">Fixe-toi des objectifs clairs (nombre de volailles, volume d'œufs, chiffre d'affaires) et adapte tes efforts progressivement.</div>
            </div>

            <div class="question">
                <strong>19. As-tu envisagé la possibilité d'associer d'autres activités agricoles ?</strong>
                <div class="reponse">Associer par exemple la production de légumes, ou l'apiculture, peut diversifier les revenus et optimiser l'espace.</div>
            </div>

            <div class="question">
                <strong>20. Comment vas-tu gérer la communication et la fidélisation des clients ?</strong>
                <div class="reponse">Utilise les réseaux sociaux, crée une page simple pour présenter tes produits, et propose des offres ou conseils pour fidéliser.</div>
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