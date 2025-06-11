<?php
 require_once __DIR__ . '/../models/Produit.php';
class AccueilController {
    public function index() {
        global $pdo; 
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $itemsPerPage = 12;
        $offset = ($page - 1) * $itemsPerPage;

               try {
            $query = "SELECT idProduit, nomProduit, age, prix, imgProduit, quantiteStock 
                      FROM produit 
                      LIMIT 4"; 
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
        
        require __DIR__ . '/../views/accueil.view.php';
    }
}