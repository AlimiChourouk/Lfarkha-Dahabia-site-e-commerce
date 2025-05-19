<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
        
* {
    margin: 0; /* Supprime les marges par défaut */
    padding: 0; /* Supprime les paddings par défaut */
    box-sizing: border-box; /* Inclut padding et bordure dans la taille totale */
    font-family: 'Montserrat', 'Poppins', sans-serif; /* Police moderne et élégante */
}

/* Définition des variables CSS pour une gestion centralisée des couleurs et styles */
:root {
    --primary: #4CAF50;/* Couleur principale (vert) */
    --primary-light: #80c883; /* Vert clair pour survols et bordures */
    --primary-dark: #3b8a3f; /* Vert foncé pour états actifs */
    --accent: #FFC107; /* Couleur d'accentuation (jaune/orange) */
    --text-dark: #212121; /* Texte sombre pour lisibilité */
    --text-medium: #757575; /* Texte gris pour éléments secondaires */
    --text-light: #FFFFFF; /* Texte blanc pour fonds sombres */
    --background: #F9F9F9; /* Fond clair pour le corps */
    --card-bg: #FFFFFF; /* Fond blanc pour cartes et sections */
    --border-light: #EEEEEE; /* Bordure claire pour séparations */
    --shadow: 0 8px 30px rgba(0, 0, 0, 0.08); /* Ombre douce pour profondeur */
    --radius: 12px; /* Rayon de bordure pour coins arrondis */
    --transition: all 0.3s ease; /* Transition fluide pour interactions */
}

/* Styles de base pour le corps de la page */
body {
    background-color: var(--background); /* Fond clair pour contraste */
    min-height: 100vh; /* Assure que le corps couvre toute la hauteur de la vue */
    font-size: 16px; /* Taille de police par défaut */
    color: var(--text-dark); /* Couleur de texte principale */
    line-height: 1.5; /* Espacement des lignes pour lisibilité */
}

/* Styles pour l'en-tête - Design moderne et minimaliste */
header {
    display: flex; /* Disposition en ligne pour aligner les éléments */
    justify-content: space-between; /* Espace les éléments uniformément */
    align-items: center; /* Centre verticalement */
    background-color: var(--card-bg); /* Fond blanc pour propreté */
    padding: 1.2rem 5%; /* Espacement interne généreux */
    position: sticky; /* Fixe l'en-tête en haut lors du défilement */
    top: 0; /* Colle au sommet */
    z-index: 1000; /* Priorité d'affichage */
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05); /* Ombre subtile */
}
#logoCustom-container{
  display: flex;
  align-items: center;

}
/* Styles pour le conteneur du texte du logo */
.custom-container {
    margin-left: 0.5rem; /* Petite marge à gauche */
    font-weight: bold; /* Texte en gras pour importance */
    font-size: 20px;
    letter-spacing: 0.05em; /* Espacement des lettres pour élégance */
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1)); /* Ombre portée légère */
    transition: all 0.3s ease-in-out; /* Transition fluide pour interactions */
}

/* Style pour le premier texte du logo (Lfarkha) */
.custom-text-1 {
    background: linear-gradient(to right, #f59e0b, #d97706, #b45309); /* Dégradé ambré */
    background-clip: text; /* Applique le dégradé au texte */
    color: transparent; /* Rend le texte transparent pour montrer le dégradé */
    transition: all 0.3s ease-in-out; /* Transition pour survol */
}

/* Style pour le second texte du logo (Dahabia) */
.custom-text-2 {
    background: linear-gradient(to right, #d97706, #fbbf24, #f59e0b); /* Dégradé ambré/jaune */
    background-clip: text; /* Applique le dégradé au texte */
    color: transparent; /* Rend le texte transparent pour le dégradé */
    margin-left: 0.25rem; /* Petite marge à gauche */
    transition: all 0.3s ease-in-out; /* Transition pour survol */
}

/* Effet au survol pour le premier texte */
.custom-container:hover .custom-text-1 {
    color: #b45309; /* Couleur ambrée foncée au survol */
}

/* Effet au survol pour le second texte */
.custom-container:hover .custom-text-2 {
    color: #eab308; /* Couleur jaune clair au survol */
}

/* Style pour l'image du logo */
#logoimg {
    width: 55px; /* Taille fixe pour le logo */
    height: 55px; /* Hauteur fixe */
    object-fit: contain; /* Ajuste l'image sans déformation */
}

/* Style pour les paragraphes dans l'en-tête */
header p {
    color: var(--text-dark); /* Texte sombre pour contraste */
    font-size: 1.3rem; /* Taille de police légèrement plus grande */
    font-weight: 700; /* Gras pour importance */
    letter-spacing: -0.5px; /* Resserre légèrement les lettres */
}

/* Style pour le menu de navigation principal */
.menu {
    display: flex; /* Affiche les liens en ligne */
    gap: 2rem; /* Espacement entre les liens */
}

/* Style pour les liens du menu */
.menu a {
    color: var(--text-medium); /* Couleur grise pour neutralité */
    text-decoration: none; /* Supprime le soulignement */
    font-size: 0.95rem; /* Taille de police légèrement réduite */
    font-weight: 500; /* Poids moyen pour lisibilité */
    position: relative; /* Permet de positionner l'effet de survol */
    transition: var(--transition); /* Transition fluide pour changements */
}

/* Effet au survol des liens du menu */
.menu a:hover {
    color: var(--primary); /* Passe au vert principal */
}

/* Pseudo-élément pour l'effet de soulignement au survol */
.menu a::after {
    content: ''; /* Crée un élément vide */
    position: absolute; /* Positionnement relatif au lien */
    bottom: -5px; /* Sous le lien */
    left: 0; /* Aligné à gauche */
    width: 0; /* Largeur initiale nulle */
    height: 2px; /* Hauteur de la ligne */
    background-color: var(--primary); /* Couleur verte */
    transition: var(--transition); /* Transition fluide */
}

/* Étend la ligne au survol */
.menu a:hover::after {
    width: 100%; /* Ligne complète */
}

/* Style pour le menu déroulant */
.dropdown {
    position: relative; /* Permet de positionner le contenu déroulant */
}

/* Style pour le bouton du menu déroulant */
.dropdown-btn {
    color: var(--text-medium); /* Couleur grise */
    cursor: pointer; /* Curseur indiquant interactivité */
    font-size: 0.95rem; /* Taille de police réduite */
    font-weight: 500; /* Poids moyen */
    transition: var(--transition); /* Transition fluide */
    display: flex; /* Alignement flexible */
    align-items: center; /* Centre verticalement */
    gap: 5px; /* Espacement entre texte et icône */
}

/* Ajoute une flèche au bouton déroulant */
.dropdown-btn::after {
    content: '▾'; /* Symbole de flèche vers le bas */
    font-size: 0.8rem; /* Taille réduite */
}

/* Effet au survol du bouton déroulant */
.dropdown-btn:hover {
    color: var(--primary); /* Passe au vert */
}

/* Style pour le contenu du menu déroulant */
.dropdown-content {
    display: none; /* Caché par défaut */
    position: absolute; /* Positionnement absolu */
    top: 130%; /* Décalé vers le bas */
    left: 0; /* Aligné à gauche */
    background-color: var(--card-bg); /* Fond blanc */
    min-width: 220px; /* Largeur minimale */
    border-radius: var(--radius); /* Coins arrondis */
    box-shadow: var(--shadow); /* Ombre pour profondeur */
    z-index: 1000; /* Priorité d'affichage */
    overflow: hidden; /* Cache les débordements */
}

/* Style pour les liens du menu déroulant */
.dropdown-content a {
    color: var(--text-medium); /* Couleur grise */
    padding: 0.9rem 1.5rem; /* Espacement interne */
    text-decoration: none; /* Sans soulignement */
    display: block; /* Occupe toute la largeur */
    transition: var(--transition); /* Transition fluide */
    font-weight: 500; /* Poids moyen */
    font-size: 0.9rem; /* Taille de police réduite */
}

/* Effet au survol des liens du menu déroulant */
.dropdown-content a:hover {
    background-color: var(--border-light); /* Fond gris clair */
    color: var(--primary); /* Texte vert */
    padding-left: 1.8rem; /* Décalage à gauche pour effet visuel */
}

/* Affiche le menu déroulant au survol */
.dropdown:hover .dropdown-content {
    display: block; /* Rend visible */
    animation: fadeInDown 0.3s ease forwards; /* Animation d'apparition */
}

/* Animation pour l'apparition du menu déroulant */
@keyframes fadeInDown {
    from {
        opacity: 0; /* Débute invisible */
        transform: translateY(-10px); /* Décalé vers le haut */
    }
    to {
        opacity: 1; /* Complètement visible */
        transform: translateY(0); /* Position finale */
    }
}

/* Style pour la barre de recherche */
.search-container {
    display: flex; /* Alignement en ligne */
    align-items: center; /* Centre verticalement */
    background-color: var(--border-light); /* Fond gris clair */
    border-radius: 30px; /* Coins très arrondis */
    padding: 0.5rem 1rem; /* Espacement interne */
    max-width: 300px; /* Largeur maximale */
    width: 100%; /* Pleine largeur dans son conteneur */
    transition: var(--transition); /* Transition fluide */
}

/* Effet au focus de la barre de recherche */
.search-container:focus-within {
    box-shadow: 0 0 0 2px var(--primary-light); /* Bordure verte claire */
}

/* Style pour l'input de recherche */
.search-container input {
    border: none; /* Sans bordure */
    background: none; /* Sans fond */
    width: 100%; /* Pleine largeur */
    padding: 0.4rem; /* Espacement interne */
    outline: none; /* Supprime le contour par défaut */
    color: var(--text-dark); /* Texte sombre */
    font-size: 0.9rem; /* Taille de police réduite */
}

/* Style pour le placeholder de l'input */
.search-container input::placeholder {
    color: var(--text-medium); /* Couleur grise */
    opacity: 0.7; /* Légèrement transparent */
}

/* Style pour l'icône de recherche */
.search-container .Icon {
    width: 18px; /* Taille réduite */
    height: 18px; /* Hauteur fixe */
    opacity: 0.6; /* Légèrement transparent */
    transition: var(--transition); /* Transition fluide */
}

/* Augmente l'opacité de l'icône au focus */
.search-container:focus-within .Icon {
    opacity: 1; /* Pleinement visible */
}

/* Style pour les liens d'authentification */
header p a {
    color: var(--text-medium); /* Couleur grise */
    text-decoration: none; /* Sans soulignement */
    transition: var(--transition); /* Transition fluide */
    font-weight: 500; /* Poids moyen */
    font-size: 0.9rem; /* Taille réduite */
}

/* Effet au survol des liens d'authentification */
header p a:hover {
    color: var(--primary); /* Passe au vert */
}
     </style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <title>Site de commerce</title>
</head>
<body>
    <header>
        <!-- Menu de navigation principal -->
        <nav class="menu">
            <a href="index.php">Accueil</a>
            <a href="?rout=panier">Panier</a>
        </nav>
        <!-- Menu déroulant pour les catégories -->
        <nav class="dropdown">
            <p class="dropdown-btn">Catégorie</p>     
            <div class="dropdown-content">
                <a href="#">Poules</a>
                <a href="#">Coqs</a>
                <a href="#">Poussins</a>
                <a href="#">Œufs</a>
            </div>
        </nav>
        <!-- Barre de recherche avec icône -->
        <div class="search-container">
            <input type="text" id="search" placeholder="Recherche">
            <img class="Icon" src="img/cherche.png" alt="Search Icon">
        </div>
        <!-- Logo du site -->
         <div id="logoCustom-container">
        <img id="logoimg" src="img/logo.png" alt="logo">
        <!-- Conteneur pour le texte personnalisé du site -->
        <div class="custom-container">
            <span class="custom-text-1">Lfarkha</span>
            <span class="custom-text-2">Dahabia</span>
        </div>
        </div>
        <a href="#">à propos</a>
        <a href="#">contact</a>
        <!-- Inclut Font Awesome pour les icônes -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<!-- Gestion de l'affichage selon l'état de connexion de l'utilisateur -->
    <div class="relative inline-block text-left" style="display: flex; align-items: center; gap: 15px;">
    <!-- Icône de panier -->
    <a href="?rout=panier" class="cart-icon" title="Voir le panier" style="color: #333; font-size: 22px;">
        <i class="fas fa-shopping-cart"></i>
    </a>

 

    <?php if (isset($_SESSION['idUtilisateur']) && !empty($_SESSION['idUtilisateur'])): ?>
        <!-- Bouton pour afficher/masquer le menu utilisateur si connecté -->
        <button onclick="toggleUserMenu()" class="text-gray-600 hover:text-black focus:outline-none" style="background: none; border: none;">
            <i class="fas fa-user-circle text-2xl"></i>
        </button>

        <!-- Menu déroulant pour les informations utilisateur -->
        <div id="userMenu" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
            <div class="px-4 py-3 text-sm text-gray-700">
                <p><strong>Nom :</strong> <?= htmlspecialchars($_SESSION['nomUtil'] ?? 'Inconnu') ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($_SESSION['emailUtil'] ?? '---') ?></p>
                <p><strong>Numéro :</strong> <?= htmlspecialchars($_SESSION['telUtil']) ?></p>
            </div>
            <div class="border-t px-4 py-2">
                <a href="deconnexion.php" class="block text-red-600 hover:text-red-800">Déconnexion</a>
            </div>
        </div>
    <?php else: ?>
        <!-- Liens pour connexion/inscription si l'utilisateur n'est pas connecté -->
        <a href="?route=connexion" class="text-blue-600 hover:underline mr-2">Connexion</a>
        <a href="?route=inscription" class="text-blue-600 hover:underline">Inscription</a>
    <?php endif; ?>
</div>

    </header> 
    <body>