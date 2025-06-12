<?php 
// Inclusion du modèle Produits pour accéder aux méthodes liées à la base de données des produits
require_once __DIR__ . '/../models/Produits.php';

class ProduitsController {
    private $pdo;

    // Constructeur : reçoit l'objet PDO pour interagir avec la base de données
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Affiche la page des produits.
     * Si une catégorie est fournie via l'URL (?categorie=...), on affiche uniquement les produits de cette catégorie.
     * Si l'utilisateur est connecté, on récupère aussi ses favoris et la quantité totale de produits dans son panier.
     */
    public function index() {
        $produitModel = new Produits($this->pdo);
    
        // Vérifie si une catégorie est passée dans l'URL
        $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;
    
        // Récupère les produits selon la catégorie ou tous les produits
        if ($categorie) {
            $produits = $produitModel->getProduitsParCategorie($categorie);
        } else {
            $produits = $produitModel->getAllProduits();
        }
    
        $favoris = [];
        $totalQte = 0;
    
        // Si l'utilisateur est connecté, on récupère ses favoris et la quantité dans son panier
        if (isset($_SESSION['idUtilisateur'])) {
            $favoris = $produitModel->getFavoris($_SESSION['idUtilisateur']);
            $totalQte = $produitModel->getQuantiteTotale($_SESSION['idUtilisateur']);
        }
    
        // Affiche la vue des produits
        require_once __DIR__ . '/../views/produits.view.php';
    }

    /**
     * Ajoute un produit aux favoris de l'utilisateur connecté.
     * Redirige vers la page des produits avec un message de succès ou d'erreur.
     */
    public function ajouterFavori() {
        // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['idUtilisateur'])) {
            header('Location: /login.php');
            exit;
        }

        // Vérifie que l'identifiant du produit est bien envoyé en POST
        if (isset($_POST['idProduit'])) {
            $produitModel = new Produits($this->pdo);
            $idUtilisateur = $_SESSION['idUtilisateur'];
            $idProduit = (int)$_POST['idProduit'];

            // Tente d’ajouter le produit aux favoris
            if ($produitModel->ajouterFavori($idUtilisateur, $idProduit)) {
                // Redirige avec message de succès
                header('Location: /produits.php?success=Produit ajouté aux favoris');
            } else {
                // Redirige avec message d'erreur
                header('Location: /produits.php?error=Erreur lors de l\'ajout du favori');
            }
            exit;
        }
    }
}