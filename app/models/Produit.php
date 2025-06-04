<?php
class Produit {
    private $pdo;

    public function __construct() {
        require '../config/db.php';
        $this->pdo = $pdo;
    }

    public function getProduits($limit, $offset) {
        $stmt = $this->pdo->prepare("SELECT idProduit, nomProduit, age, prix, imgProduit, quantiteStock 
                                     FROM Produit LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countProduits() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Produit");
        return $stmt->fetchColumn();
    }
}
