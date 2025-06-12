<?php
class Produits {
    private $pdo;

    // Constructeur : initialise la connexion PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les produits de la base de données.
     * @return array Tableau associatif de tous les produits.
     */
    public function getAllProduits() {
        $stmt = $this->pdo->prepare("SELECT * FROM produit");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère la liste des identifiants des produits ajoutés aux favoris par un utilisateur donné.
     * @param int $idUtilisateur Identifiant de l'utilisateur.
     * @return array Tableau des ID des produits favoris.
     */
    public function getFavoris($idUtilisateur) {
        $stmt = $this->pdo->prepare("SELECT idProduit FROM favoris WHERE idUtilisateur = ?");
        $stmt->execute([$idUtilisateur]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idProduit');
    }

    /**
     * Calcule la quantité totale de produits dans le panier d'un utilisateur.
     * @param int $idUtilisateur Identifiant de l'utilisateur.
     * @return int Quantité totale des produits, ou 0 en cas d'erreur ou si aucun produit.
     */
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

    /**
     * Récupère les produits appartenant à une catégorie spécifique.
     * @param string $categorie Nom de la catégorie.
     * @return array Tableau associatif des produits de cette catégorie.
     */
    public function getProduitsParCategorie($categorie) {
        $stmt = $this->pdo->prepare("SELECT * FROM produit WHERE categorie = ?");
        $stmt->execute([$categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute un produit aux favoris d'un utilisateur.
     * @param int $idUtilisateur Identifiant de l'utilisateur.
     * @param int $idProduit Identifiant du produit à ajouter.
     * @return bool True si l'ajout a réussi, false sinon.
     */
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
