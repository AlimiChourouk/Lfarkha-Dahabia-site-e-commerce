<?php
require_once __DIR__ . '/../models/UserModel.php';

class DashboardModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getCommandesByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM commande WHERE idUtilisateur = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching commands for user $userId: " . $e->getMessage());
            return [];
        }
    }

    public function getUserOrders($userId) {
        try {
            $query = "SELECT idCommande, dateCommande, statue, adresseLivraison, total
                      FROM commande
                      WHERE idUtilisateur = :idUtilisateur
                      ORDER BY dateCommande DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idUtilisateur' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching orders for user $userId: " . $e->getMessage());
            return [];
        }
    }

    public function getOrderDetails($orderId) {
        try {
            $query = "SELECT p.nomProduit, p.type, cp.QTE, cp.prix
                      FROM commande_produit cp
                      JOIN produit p ON cp.idProduit = p.idProduit
                      WHERE cp.idCommande = :idCommande";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idCommande' => $orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching order details for order $orderId: " . $e->getMessage());
            return [];
        }
    }

    public function getUserFormations($userId) {
        try {
            $query = "SELECT p.nomProduit AS titre, p.description, p.prix, p.nomProduit
                      FROM produit p
                      JOIN commande_produit cp ON p.idProduit = cp.idProduit
                      JOIN commande c ON cp.idCommande = c.idCommande
                      WHERE c.idUtilisateur = :idUtilisateur AND p.type = 'formation'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idUtilisateur' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching formations for user $userId: " . $e->getMessage());
            return [];
        }
    }

    public function getNumberOfCommands($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM commande WHERE idUtilisateur = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting commands for user $userId: " . $e->getMessage());
            return 0;
        }
    }

    public function getNumberOfFavorites($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM favoris WHERE idUtilisateur = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting favorites for user $userId: " . $e->getMessage());
            return 0;
        }
    }

    public function getNumberOfUniqueProducts($userId) {
        try {
            $query = "
                SELECT COUNT(DISTINCT p.idProduit) 
                FROM produit p 
                INNER JOIN favoris f ON p.idProduit = f.idProduit 
                WHERE f.idUtilisateur = ?
                UNION
                SELECT COUNT(DISTINCT cp.idProduit)
                FROM commande_produit cp
                INNER JOIN commande c ON cp.idCommande = c.idCommande
                WHERE c.idUtilisateur = ?
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$userId, $userId]);
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return array_sum($results);
        } catch (PDOException $e) {
            error_log("Error counting unique products for user $userId: " . $e->getMessage());
            return 0;
        }
    }

    public function getUserFavorites($userId) {
        try {
            $query = "
                SELECT p.nomProduit, p.prix 
                FROM favoris f 
                INNER JOIN produit p ON f.idProduit = p.idProduit 
                WHERE f.idUtilisateur = ?
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching favorites for user $userId: " . $e->getMessage());
            return [];
        }
    }
}
?>