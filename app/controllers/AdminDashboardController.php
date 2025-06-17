<?php
// Prevent multiple inclusions
if (class_exists('AdminDashboardController')) {
    return;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../app/utils/EmailService.php';

class AdminDashboardController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Vérifier si l'admin est connecté
        if (!isset($_SESSION['admin'])) {
            header("Location: " . BASE_URL . "index.php?rout=admin/login");
            exit;
        }

        // Nombre total d'utilisateurs
        $query = "SELECT COUNT(*) as total FROM utilisateur";
        $stmt = $this->pdo->query($query);
        $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Nombre de commandes par statut
        $query = "SELECT statut, COUNT(*) as count FROM commande GROUP BY statut";
        $stmt = $this->pdo->query($query);
        $commandesByStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Liste des commandes
       // Liste des commandes avec les produits associés
$query = "SELECT c.idCommande, c.dateCommande, c.statut, c.adresseLivraison, u.emailUtil, 
GROUP_CONCAT(p.nomProduit SEPARATOR ', ') as produits, 
GROUP_CONCAT(cp.idProduit SEPARATOR ', ') as produit_ids
FROM commande c 
JOIN utilisateur u ON c.idUtilisateur = u.idUtilisateur
LEFT JOIN Commande_Produit cp ON c.idCommande = cp.idCommande
LEFT JOIN produit p ON cp.idProduit = p.idProduit
GROUP BY c.idCommande, c.dateCommande, c.statut, c.adresseLivraison, u.emailUtil";
$stmt = $this->pdo->query($query);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Produits les plus/moins demandés (basé sur panier_produit)
        $query = "SELECT p.nomProduit, p.idProduit, COALESCE(SUM(cp.quantite), 0) as totalQte
FROM produit p
LEFT JOIN Commande_Produit cp ON p.idProduit = cp.idProduit
GROUP BY p.idProduit, p.nomProduit
ORDER BY totalQte DESC;";
        $stmt = $this->pdo->query($query);
        $produitsDemande = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Données pour le graphique des ventes (nombre de commandes par jour)
        $query = "SELECT DATE(dateCommande) as date, COUNT(*) as count 
                  FROM commande 
                  GROUP BY DATE(dateCommande) 
                  ORDER BY date";
        $stmt = $this->pdo->query($query);
        $ventesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Messages et leur statut
        $query = "SELECT m.idMessage, m.contenu, m.dateMessage, m.statut, u.emailUtil 
                  FROM message m 
                  JOIN utilisateur u ON m.idUtilisateur = u.idUtilisateur";
        $stmt = $this->pdo->query($query);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Liste des produits pour la gestion
        $query = "SELECT * FROM produit";
        $stmt = $this->pdo->query($query);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Générer un jeton CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Inclure la vue
        require_once __DIR__ . '/../views/adminDashboardView.php';
    }

    public function updateCommandeStatus() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        $idCommande = filter_input(INPUT_POST, 'idCommande', FILTER_VALIDATE_INT);
        $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_STRING);

        if ($idCommande && in_array($statut, ['En attente', 'Confirmée', 'Expédiée', 'Annulée'])) {
            try {
                $this->pdo->beginTransaction();

                // Update order status
                $query = "UPDATE commande SET statut = :statut WHERE idCommande = :idCommande";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(['statut' => $statut, 'idCommande' => $idCommande]);

                // Get customer email
                $query = "SELECT u.emailUtil 
                          FROM commande c 
                          JOIN utilisateur u ON c.idUtilisateur = u.idUtilisateur 
                          WHERE c.idCommande = :idCommande";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(['idCommande' => $idCommande]);
                $customer = $stmt->fetch(PDO::FETCH_ASSOC);

                $emailSent = false;
                if ($customer && filter_var($customer['emailUtil'], FILTER_VALIDATE_EMAIL)) {
                    // Send status update email
                    $emailSent = EmailService::sendOrderStatusUpdateEmail(
                        $customer['emailUtil'],
                        $idCommande,
                        $statut
                    );
                    if (!$emailSent) {
                        error_log("Warning: Failed to send status update email for order #$idCommande to " . $customer['emailUtil']);
                    }
                } else {
                    error_log("Invalid or missing email for order #$idCommande");
                }

                $this->pdo->commit();
                $_SESSION['success'] = "Statut de la commande mis à jour." . ($customer && $emailSent ? " Email envoyé au client." : "");
            } catch (Exception $e) {
                $this->pdo->rollBack();
                error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de la mise à jour du statut.";
            }
        } else {
            $_SESSION['error'] = "Données invalides.";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }

    public function updateMessageStatus() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        $idMessage = filter_input(INPUT_POST, 'idMessage', FILTER_VALIDATE_INT);
        $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_STRING);

        if ($idMessage && in_array($statut, ['Lu', 'Non lu', 'Répondu'])) {
            try {
                $query = "UPDATE message SET statut = :statut WHERE idMessage = :idMessage";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(['statut' => $statut, 'idMessage' => $idMessage]);
                $_SESSION['success'] = "Statut du message mis à jour.";
            } catch (PDOException $e) {
                error_log("Erreur lors de la mise à jour du statut du message : " . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de la mise à jour du statut du message.";
            }
        } else {
            $_SESSION['error'] = "Données invalides.";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }

    public function addProduit() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        $nomProduit = filter_input(INPUT_POST, 'nomProduit', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING);
        $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
        $imgProduit = filter_input(INPUT_POST, 'imgProduit', FILTER_SANITIZE_URL);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $quantiteStock = filter_input(INPUT_POST, 'quantiteStock', FILTER_VALIDATE_INT);

        if ($nomProduit && $prix && $quantiteStock !== false) {
            try {
                $query = "INSERT INTO produit (nomProduit, age, prix, imgProduit, description, quantiteStock) 
                          VALUES (:nomProduit, :age, :prix, :imgProduit, :description, :quantiteStock)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    'nomProduit' => $nomProduit,
                    'age' => $age,
                    'prix' => $prix,
                    'imgProduit' => $imgProduit,
                    'description' => $description,
                    'quantiteStock' => $quantiteStock
                ]);
                $_SESSION['success'] = "Produit ajouté avec succès.";
            } catch (PDOException $e) {
                error_log("Erreur lors de l'ajout du produit : " . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de l'ajout du produit.";
            }
        } else {
            $_SESSION['error'] = "Données du produit invalides.";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }

    public function updateProduit() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        $idProduit = filter_input(INPUT_POST, 'idProduit', FILTER_VALIDATE_INT);
        $nomProduit = filter_input(INPUT_POST, 'nomProduit', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING);
        $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
        $imgProduit = filter_input(INPUT_POST, 'imgProduit', FILTER_SANITIZE_URL);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $quantiteStock = filter_input(INPUT_POST, 'quantiteStock', FILTER_VALIDATE_INT);

        if ($idProduit && $nomProduit && $prix && $quantiteStock !== false) {
            try {
                $query = "UPDATE produit 
                          SET nomProduit = :nomProduit, age = :age, prix = :prix, imgProduit = :imgProduit, 
                              description = :description, quantiteStock = :quantiteStock 
                          WHERE idProduit = :idProduit";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    'nomProduit' => $nomProduit,
                    'age' => $age,
                    'prix' => $prix,
                    'imgProduit' => $imgProduit,
                    'description' => $description,
                    'quantiteStock' => $quantiteStock,
                    'idProduit' => $idProduit
                ]);
                $_SESSION['success'] = "Produit mis à jour avec succès.";
            } catch (PDOException $e) {
                error_log("Erreur lors de la mise à jour du produit : " . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de la mise à jour du produit.";
            }
        } else {
            $_SESSION['error'] = "Données du produit invalides.";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }

    public function deleteProduit() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        $idProduit = filter_input(INPUT_POST, 'idProduit', FILTER_VALIDATE_INT);

        if ($idProduit === false || $idProduit <= 0) {
            $_SESSION['error'] = "ID du produit invalide.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        try {
            // Commencer une transaction pour assurer l'intégrité
            $this->pdo->beginTransaction();

            // Supprimer les enregistrements liés dans panier_produit
            $query = "DELETE FROM panier_produit WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idProduit' => $idProduit]);

            // Supprimer les enregistrements liés dans favoris
            $query = "DELETE FROM favoris WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idProduit' => $idProduit]);

            // Supprimer le produit
            $query = "DELETE FROM produit WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idProduit' => $idProduit]);

            // Valider la transaction
            $this->pdo->commit();
            $_SESSION['success'] = "Produit supprimé avec succès.";
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->pdo->rollBack();
            error_log("Erreur lors de la suppression du produit : " . $e->getMessage());
            $_SESSION['error'] = "Impossible de supprimer le produit. Vérifiez les dépendances (panier ou favoris).";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }
    
}
?>