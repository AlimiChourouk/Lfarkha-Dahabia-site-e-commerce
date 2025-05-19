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

        // Démarrer la session pour accéder aux données utilisateur
        if (session_status() === PHP_SESSION_DISABLED || session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Charger la vue
        require __DIR__ . '/../views/accueil.view.php';
    }
}
?>