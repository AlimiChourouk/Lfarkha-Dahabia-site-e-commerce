<?php
class Favoris {
    private $pdo;
    private $idUtilisateur;

    public function __construct($pdo, $idUtilisateur) {
        if (!is_object($pdo) || !($pdo instanceof PDO)) {
            throw new InvalidArgumentException("Invalid PDO object provided.");
        }
        if (!is_numeric($idUtilisateur) || $idUtilisateur <= 0) {
            throw new InvalidArgumentException("Invalid user ID provided.");
        }
        $this->pdo = $pdo;
        $this->idUtilisateur = (int)$idUtilisateur;
    }

    public function toggleFavori($idProduit) {
        if (!is_numeric($idProduit) || $idProduit <= 0) {
            error_log("Invalid idProduit: $idProduit");
            return ['success' => false, 'message' => 'Produit invalide.'];
        }

        try {
            // Vérifier si le produit existe
            $stmt = $this->pdo->prepare("SELECT idProduit FROM produit WHERE idProduit = ?");
            $stmt->execute([$idProduit]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                error_log("Produit introuvable: idProduit=$idProduit");
                return ['success' => false, 'message' => 'Produit introuvable.'];
            }

            $stmt = $this->pdo->prepare("SELECT idFavoris FROM favoris WHERE idUtilisateur = ? AND idProduit = ?");
            $stmt->execute([$this->idUtilisateur, $idProduit]);
            $favori = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($favori) {
                $stmt = $this->pdo->prepare("DELETE FROM favoris WHERE idFavoris = ?");
                $stmt->execute([$favori['idFavoris']]);
                error_log("Removed favori: idFavoris={$favori['idFavoris']}");
                return ['success' => true, 'message' => 'Produit retiré des favoris.'];
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO favoris (idUtilisateur, idProduit) VALUES (?, ?)");
                $stmt->execute([$this->idUtilisateur, $idProduit]);
                error_log("Added favori: idUtilisateur=$this->idUtilisateur, idProduit=$idProduit");
                return ['success' => true, 'message' => 'Produit ajouté aux favoris.'];
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification des favoris : " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour des favoris.'];
        }
    }

    public function getFavoris() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.idProduit, p.nomProduit, p.prix, p.imgProduit, p.quantiteStock
                FROM favoris f
                JOIN produit p ON f.idProduit = p.idProduit
                WHERE f.idUtilisateur = ?
            ");
            $stmt->execute([$this->idUtilisateur]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des favoris : " . $e->getMessage());
            return [];
        }
    }
}