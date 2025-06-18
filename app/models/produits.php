<?php

class Produits {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllProduits() {
        $stmt = $this->pdo->prepare("SELECT * FROM produit");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFavoris($idUtilisateur) {
        $stmt = $this->pdo->prepare("SELECT idProduit FROM favoris WHERE idUtilisateur = ?");
        $stmt->execute([$idUtilisateur]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idProduit');
    }
     public function getQuantiteTotale($idUtilisateur) {
    try {
        $stmt = $this->pdo->prepare("
            SELECT SUM(pp.QTE) AS total_qte
            FROM Panier_Produit pp
            JOIN Panier p ON pp.idPanier = p.idPanier
            WHERE p.idUtilisateur = ?
        ");
        $stmt->execute([$idUtilisateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && $result['total_qte'] !== null ? (int)$result['total_qte'] : 0;
    } catch (PDOException $e) {
        error_log("Produits: Erreur lors du calcul de la quantité totale: " . $e->getMessage());
        return 0;
    }
}
public function getProduitsParCategorie($categorie) {
    $stmt = $this->pdo->prepare("SELECT * FROM produit WHERE categorie = ?");
    $stmt->execute([$categorie]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Dans produits.php
public function ajouterFavori($idUtilisateur, $idProduit) {
    try {
        $stmt = $this->pdo->prepare("INSERT INTO Favoris (idUtilisateur, idProduit) VALUES (?, ?)");
        $stmt->execute([$idUtilisateur, $idProduit]);
        return true;
    } catch (PDOException $e) {
        error_log("Produits: Erreur lors de l'ajout du favori: " . $e->getMessage());
        return false;
    }
}

}

