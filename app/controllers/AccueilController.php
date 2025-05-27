<?php
require_once __DIR__ . '/../models/Produit.php';

class AccueilController {
    public function index() {
        global $pdo; 
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $itemsPerPage = 12;
        $offset = ($page - 1) * $itemsPerPage;

        try {
            // Récupérer les produits
            $query = "SELECT idProduit, nomProduit, age, prix, imgProduit, quantiteStock 
                      FROM Produit 
                      LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Compter le total des produits pour la pagination
            $totalQuery = "SELECT COUNT(*) FROM Produit";
            $totalStmt = $pdo->query($totalQuery);
            $totalProducts = $totalStmt->fetchColumn();
            $totalPages = ceil($totalProducts / $itemsPerPage);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits : " . $e->getMessage());
            $produits = [];
            $totalPages = 0;
        }

        if (isset($_SESSION['idUtilisateur'])) {
            $idUtilisateur = $_SESSION['idUtilisateur'];
        
            $sql = "SELECT SUM(pp.QTE) AS total_qte
                    FROM panier_produit pp
                    JOIN panier p ON pp.idPanier = p.idPanier
                    WHERE p.idUtilisateur = :idUtilisateur";
        
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':idUtilisateur' => $idUtilisateur]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            $totalQte = $result['total_qte'] ?? 0; 
        } else {
            $totalQte = 0;
        }

        // Charger la vue
        require __DIR__ . '/../views/accueil.view.php';
    }
}
?>