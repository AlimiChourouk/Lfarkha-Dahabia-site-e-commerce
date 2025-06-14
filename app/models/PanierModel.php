<?php
class Panier {
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

    public function getProduits() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT pp.idProduit, pp.QTE, p.nomProduit, p.prix, p.imgProduit, p.quantiteStock
                FROM Panier_Produit pp
                JOIN Panier pa ON pp.idPanier = pa.idPanier
                JOIN Produit p ON pp.idProduit = p.idProduit
                WHERE pa.idUtilisateur = ?
            ");
            $stmt->execute([$this->idUtilisateur]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PanierModel: Erreur lors de la récupération des produits: " . $e->getMessage());
            return [];
        }
    }

public function ajouterProduit($idProduit, $qte) {
    if (!is_numeric($idProduit) || $idProduit <= 0 || !is_numeric($qte) || $qte <= 0) {
        error_log("PanierModel: Invalid idProduit=$idProduit or qte=$qte");
        return ['success' => false, 'message' => 'Produit ou quantité invalide.'];
    }

    try {
        // Récupérer les informations du produit, y compris la catégorie
        $stmt = $this->pdo->prepare("SELECT quantiteStock, categorie FROM Produit WHERE idProduit = ?");
        $stmt->execute([$idProduit]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            error_log("PanierModel: Produit introuvable: idProduit=$idProduit");
            return ['success' => false, 'message' => 'Produit introuvable.'];
        }

        // Déterminer la quantité minimale en fonction de la catégorie
        $quantiteMinimale = ($produit['categorie'] === 'Œufs') ? 20 : 10;
        if ($qte < $quantiteMinimale) {
            error_log("PanierModel: Quantité inférieure à la minimale: idProduit=$idProduit, qte=$qte, quantiteMinimale=$quantiteMinimale");
            return ['success' => false, 'message' => "La quantité minimale pour ce produit est $quantiteMinimale."];
        }

        if ($produit['quantiteStock'] < $qte) {
            error_log("PanierModel: Stock insuffisant: idProduit=$idProduit, quantiteStock={$produit['quantiteStock']}, qte=$qte");
            return ['success' => false, 'message' => 'Stock insuffisant pour ce produit.'];
        }

        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare("SELECT idPanier FROM Panier WHERE idUtilisateur = ?");
        $stmt->execute([$this->idUtilisateur]);
        $panier = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$panier) {
            $stmt = $this->pdo->prepare("INSERT INTO Panier (idUtilisateur) VALUES (?)");
            $stmt->execute([$this->idUtilisateur]);
            $idPanier = $this->pdo->lastInsertId();
        } else {
            $idPanier = $panier['idPanier'];
        }

        $stmt = $this->pdo->prepare("SELECT QTE FROM Panier_Produit WHERE idPanier = ? AND idProduit = ?");
        $stmt->execute([$idPanier, $idProduit]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $nouvelleQte = $existing['QTE'] + $qte;
            if ($produit['quantiteStock'] < $nouvelleQte) {
                $this->pdo->rollBack();
                error_log("PanierModel: Stock insuffisant pour mise à jour: idProduit=$idProduit, nouvelleQte=$nouvelleQte");
                return ['success' => false, 'message' => 'Stock insuffisant pour la quantité demandée.'];
            }
            $stmt = $this->pdo->prepare("UPDATE Panier_Produit SET QTE = ? WHERE idPanier = ? AND idProduit = ?");
            $stmt->execute([$nouvelleQte, $idPanier, $idProduit]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO Panier_Produit (idPanier, idProduit, QTE) VALUES (?, ?, ?)");
            $stmt->execute([$idPanier, $idProduit, $qte]);
        }

        $this->pdo->commit();
        error_log("PanierModel: Produit ajouté: idProduit=$idProduit, qte=$qte, idPanier=$idPanier");
        return ['success' => true, 'message' => 'Produit ajouté au panier.'];
    } catch (PDOException $e) {
        $this->pdo->rollBack();
        error_log("PanierModel: Erreur lors de l'ajout au panier: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erreur lors de l’ajout au panier.'];
    }
}

    public function modifierQuantite($idProduit, $qte) {
        if (!is_numeric($idProduit) || $idProduit <= 0 || !is_numeric($qte) || $qte <= 0) {
            error_log("PanierModel: Invalid idProduit=$idProduit or qte=$qte");
            return ['success' => false, 'message' => 'Produit ou quantité invalide.'];
        }

        try {
            $stmt = $this->pdo->prepare("SELECT quantiteStock FROM Produit WHERE idProduit = ?");
            $stmt->execute([$idProduit]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$produit) {
                error_log("PanierModel: Produit introuvable: idProduit=$idProduit");
                return ['success' => false, 'message' => 'Produit introuvable.'];
            }
            if ($produit['quantiteStock'] < $qte) {
                error_log("PanierModel: Stock insuffisant: idProduit=$idProduit, quantiteStock={$produit['quantiteStock']}, qte=$qte");
                return ['success' => false, 'message' => 'Stock insuffisant pour la quantité demandée.'];
            }

            $stmt = $this->pdo->prepare("
                UPDATE Panier_Produit 
                SET QTE = ? 
                WHERE idProduit = ? 
                AND idPanier IN (SELECT idPanier FROM Panier WHERE idUtilisateur = ?)
            ");
            $stmt->execute([$qte, $idProduit, $this->idUtilisateur]);
            error_log("PanierModel: Quantité modifiée: idProduit=$idProduit, qte=$qte");
            return ['success' => true, 'message' => 'Quantité mise à jour.'];
        } catch (PDOException $e) {
            error_log("PanierModel: Erreur lors de la modification de la quantité: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour de la quantité.'];
        }
    }

    public function supprimerProduit($idProduit) {
        if (!is_numeric($idProduit) || $idProduit <= 0) {
            error_log("PanierModel: Invalid idProduit=$idProduit");
            return ['success' => false, 'message' => 'Produit invalide.'];
        }

        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM Panier_Produit 
                WHERE idProduit = ? 
                AND idPanier IN (SELECT idPanier FROM Panier WHERE idUtilisateur = ?)
            ");
            $stmt->execute([$idProduit, $this->idUtilisateur]);
            error_log("PanierModel: Produit supprimé: idProduit=$idProduit");
            return ['success' => true, 'message' => 'Produit supprimé du panier.'];
        } catch (PDOException $e) {
            error_log("PanierModel: Erreur lors de la suppression du produit: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la suppression du produit.'];
        }
    }

    public function clearCart() {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM Panier_Produit 
                WHERE idPanier IN (SELECT idPanier FROM Panier WHERE idUtilisateur = ?)
            ");
            $stmt->execute([$this->idUtilisateur]);
            error_log("PanierModel: Panier vidé pour idUtilisateur=$this->idUtilisateur");
            return ['success' => true, 'message' => 'Panier vidé.'];
        } catch (PDOException $e) {
            error_log("PanierModel: Erreur lors du vidage du panier: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors du vidage du panier.'];
        }
    }

    public function validateStockForCheckout() {
        try {
            $produits = $this->getProduits();
            foreach ($produits as $produit) {
                if ($produit['quantiteStock'] < $produit['QTE']) {
                    error_log("PanierModel: Stock insuffisant pour idProduit={$produit['idProduit']}, quantiteStock={$produit['quantiteStock']}, QTE={$produit['QTE']}");
                    return ['success' => false, 'message' => "Stock insuffisant pour {$produit['nomProduit']}."];
                }
            }
            return ['success' => true, 'message' => 'Stock suffisant.'];
        } catch (PDOException $e) {
            error_log("PanierModel: Erreur lors de la validation du stock: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la validation du stock.'];
        }
    }

    public function updateStockAfterCheckout() {
        try {
            $produits = $this->getProduits();
            $this->pdo->beginTransaction();

            foreach ($produits as $produit) {
                $stmt = $this->pdo->prepare("
                    UPDATE Produit 
                    SET quantiteStock = quantiteStock - ? 
                    WHERE idProduit = ?
                ");
                $stmt->execute([$produit['QTE'], $produit['idProduit']]);
            }

            $this->pdo->commit();
            error_log("PanierModel: Stock mis à jour pour idUtilisateur=$this->idUtilisateur");
            return ['success' => true, 'message' => 'Stock mis à jour.'];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("PanierModel: Erreur lors de la mise à jour du stock: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour du stock.'];
        }
    }

    public function getPanierId() {
        try {
            $stmt = $this->pdo->prepare("SELECT idPanier FROM Panier WHERE idUtilisateur = ?");
            $stmt->execute([$this->idUtilisateur]);
            $panier = $stmt->fetch(PDO::FETCH_ASSOC);
            return $panier ? $panier['idPanier'] : null;
        } catch (PDOException $e) {
            error_log("PanierModel: Erreur lors de la récupération de l'ID du panier: " . $e->getMessage());
            return null;
        }
    }
    public function getQuantiteTotale() {
    try {
        $stmt = $this->pdo->prepare("
            SELECT SUM(pp.QTE) AS total_qte
            FROM Panier_Produit pp
            JOIN Panier p ON pp.idPanier = p.idPanier
            WHERE p.idUtilisateur = ?
        ");
        $stmt->execute([$this->idUtilisateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && $result['total_qte'] !== null ? (int)$result['total_qte'] : 0;
    } catch (PDOException $e) {
        error_log("PanierModel: Erreur lors du calcul de la quantité totale: " . $e->getMessage());
        return 0;
    }
}

}