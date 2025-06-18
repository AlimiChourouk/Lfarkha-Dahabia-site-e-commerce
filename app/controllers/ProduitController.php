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
        try {
            // Ajout de categorie à la requête
            $stmt = $this->pdo->prepare("
                SELECT idProduit, nomProduit, age, prix, imgProduit, quantiteStock, description, categorie
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

            // Favoris
            $favoris = [];
            if (isset($_SESSION['idUtilisateur'])) {
                $stmt = $this->pdo->prepare("SELECT idProduit FROM favoris WHERE idUtilisateur = ?");
                $stmt->execute([$_SESSION['idUtilisateur']]);
                $favoris = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idProduit');
            }

            // Quantité totale dans le panier
            $totalQte = 0;
            if (isset($_SESSION['idUtilisateur'])) {
                $stmt = $this->pdo->prepare("
                    SELECT SUM(pp.QTE) AS total_qte
                    FROM panier_produit pp
                    JOIN panier p ON pp.idPanier = p.idPanier
                    WHERE p.idUtilisateur = ?
                ");
                $stmt->execute([$_SESSION['idUtilisateur']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $totalQte = $result['total_qte'] ?? 0;
            }

            require __DIR__ . '/../views/produit.view.php';
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du produit : " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue. Veuillez réessayer.";
            header("Location: index.php?rout=accueil");
            exit();
        }
    }

    public function index() {
        // Cette méthode semble incorrecte car elle charge produit.view.php
        // Redirigeons vers la liste des produits (produits.php) à la place
        header("Location: index.php?rout=produits");
        exit();
    }
}