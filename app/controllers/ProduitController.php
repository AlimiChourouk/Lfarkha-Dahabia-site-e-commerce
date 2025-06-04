<?php
class ProduitController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function showDetails() {
        if (!isset($_GET['idProduit'])) {
            $_SESSION['error_message'] = "Produit non spécifié.";
            header("Location: index.php?rout=accueil");
            exit();
        }

        $idProduit = (int)$_GET['idProduit'];
        $stmt = $this->pdo->prepare("
            SELECT idProduit, nomProduit, age, prix, imgProduit, quantiteStock, description
            FROM produit 
            WHERE idProduit = ?
        ");
        $stmt->execute([$idProduit]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            $_SESSION['error_message'] = "Produit introuvable.";
            header("Location: index.php?rout=accueil");
            exit();
        }

        require __DIR__ . '/../views/produit.view.php';
    }
}