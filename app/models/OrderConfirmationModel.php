<?php

class OrderConfirmationModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getCartItems($userId) {
        $query = "
            SELECT p.idProduit, p.nomProduit, p.prix, p.imgProduit, pp.QTE
            FROM panier_produit pp
            JOIN panier pa ON pp.idPanier = pa.idPanier
            JOIN produit p ON pp.idProduit = p.idProduit
            WHERE pa.idUtilisateur = :userId
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartTotal($userId) {
        $query = "
            SELECT SUM(p.prix * pp.QTE) as total
            FROM panier_produit pp
            JOIN panier pa ON pp.idPanier = pa.idPanier
            JOIN produit p ON pp.idProduit = p.idProduit
            WHERE pa.idUtilisateur = :userId
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function saveOrder($userId, $adresse) {
        $query = "
            INSERT INTO commande (idUtilisateur, dateCommande, statut, adresseLivraison)
            VALUES (:userId, CURDATE(), 'En attente', :adresse)
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['userId' => $userId, 'adresse' => $adresse]);
        $orderId = $this->pdo->lastInsertId();

        $cartItems = $this->getCartItems($userId);

        $query = "
            INSERT INTO commande_produit (idCommande, idProduit, quantite)
            VALUES (:orderId, :productId, :quantity)
        ";
        $stmt = $this->pdo->prepare($query);
        foreach ($cartItems as $item) {
            $stmt->execute([
                'orderId' => $orderId,
                'productId' => $item['idProduit'],
                'quantity' => $item['QTE']
            ]);
            $this->updateProductStock($item['idProduit'], $item['QTE']);
        }

        $this->clearCart($userId);

        return $orderId;
    }

    private function updateProductStock($productId, $quantity) {
        $query = "
            UPDATE produit
            SET quantiteStock = quantiteStock - :quantity
            WHERE idProduit = :productId AND quantiteStock >= :quantity
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['productId' => $productId, 'quantity' => $quantity]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Stock insuffisant pour le produit ID $productId.");
        }
    }

    private function clearCart($userId) {
        $query = "
            DELETE pp FROM panier_produit pp
            JOIN panier pa ON pp.idPanier = pa.idPanier
            WHERE pa.idUtilisateur = :userId
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['userId' => $userId]);

        $query = "DELETE FROM panier WHERE idUtilisateur = :userId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['userId' => $userId]);
    }
}
?>