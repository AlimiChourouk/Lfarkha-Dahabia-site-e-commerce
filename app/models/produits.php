<?php
require_once __DIR__ . '/../models/Produits.php';

class ProduitsController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $produitModel = new Produits($this->pdo);
    
        // Vérifie si une catégorie est passée en paramètre
        $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;
    
        if ($categorie) {
            $produits = $produitModel->getProduitsParCategorie($categorie);
        } else {
            $produits = $produitModel->getAllProduits();
        }
    
        $favoris = [];
        $totalQte = 0;
    
        if (isset($_SESSION['idUtilisateur'])) {
            $favoris = $produitModel->getFavoris($_SESSION['idUtilisateur']);
            $totalQte = $produitModel->getQuantiteTotale($_SESSION['idUtilisateur']);
        }
    
        require_once __DIR__ . '/../views/produits.view.php';
    }
    // Dans produitsController.php
public function ajouterFavori() {
    if (!isset($_SESSION['idUtilisateur'])) {
        header('Location: /login.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
        exit;
    }

    if (isset($_POST['idProduit'])) {
        $produitModel = new Produits($this->pdo);
        $idUtilisateur = $_SESSION['idUtilisateur'];
        $idProduit = (int)$_POST['idProduit'];

        if ($produitModel->ajouterFavori($idUtilisateur, $idProduit)) {
            // Succès : rediriger vers la page des produits avec un message
            header('Location: /produits.php?success=Produit ajouté aux favoris');
        } else {
            // Échec : rediriger avec un message d'erreur
            header('Location: /produits.php?error=Erreur lors de l\'ajout du favori');
        }
        exit;
    }
}
}
