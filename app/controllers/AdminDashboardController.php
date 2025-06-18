<?php
// Preventing multiple inclusions of the class to avoid redefinition errors
if (class_exists('AdminDashboardController')) {
    return;
}

// Including necessary configuration and utility files
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../app/utils/EmailService.php';

// Defining the AdminDashboardController class to manage admin dashboard functionalities
class AdminDashboardController {
    // Private property to store the PDO instance for database operations
    private $pdo;

    // Constructor to inject PDO dependency
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Function to display the admin dashboard
    public function index() {
        // Checking if the admin is logged in
        if (!isset($_SESSION['admin'])) {
            header("Location: " . BASE_URL . "index.php?rout=admin/login");
            exit;
        }

        // Fetching total number of users
        $query = "SELECT COUNT(*) as total FROM utilisateur";
        $stmt = $this->pdo->query($query);
        $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetching number of orders by status
        $query = "SELECT statut, COUNT(*) as count FROM commande GROUP BY statut";
        $stmt = $this->pdo->query($query);
        $commandesByStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetching list of orders with associated products
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

        // Fetching most/least demanded products based on order quantities
        $query = "SELECT p.nomProduit, p.idProduit, COALESCE(SUM(cp.quantite), 0) as totalQte
FROM produit p
LEFT JOIN Commande_Produit cp ON p.idProduit = cp.idProduit
GROUP BY p.idProduit, p.nomProduit
ORDER BY totalQte DESC;";
        $stmt = $this->pdo->query($query);
        $produitsDemande = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetching sales data for chart (orders per day)
        $query = "SELECT DATE(dateCommande) as date, COUNT(*) as count 
                  FROM commande 
                  GROUP BY DATE(dateCommande) 
                  ORDER BY date";
        $stmt = $this->pdo->query($query);
        $ventesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetching messages and their status
        $query = "SELECT m.idMessage, m.contenu, m.dateMessage, m.statut, u.emailUtil 
                  FROM message m 
                  JOIN utilisateur u ON m.idUtilisateur = u.idUtilisateur";
        $stmt = $this->pdo->query($query);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetching list of products for management
        $query = "SELECT * FROM produit";
        $stmt = $this->pdo->query($query);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generating CSRF token if not already set
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Including the admin dashboard view
        require_once __DIR__ . '/../views/adminDashboardView.php';
    }

    // Function to add a new product to the database, including the category field
    public function addProduit() {
        // Validating CSRF token to prevent cross-site request forgery
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        // Sanitizing and validating input data
        $nomProduit = filter_input(INPUT_POST, 'nomProduit', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING);
        $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
        $imgProduit = filter_input(INPUT_POST, 'imgProduit', FILTER_SANITIZE_URL);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $quantiteStock = filter_input(INPUT_POST, 'quantiteStock', FILTER_VALIDATE_INT);
        $categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_STRING);

        // Defining allowed categories based on the ENUM in the Produit table
        $allowedCategories = ['Poules', 'Coqs', 'Œufs', 'Poussins'];

        // Checking if required fields and category are valid
        if ($nomProduit && $prix !== false && $quantiteStock !== false && in_array($categorie, $allowedCategories)) {
            try {
                // Inserting new product into the database
                $query = "INSERT INTO produit (nomProduit, age, prix, imgProduit, description, quantiteStock, categorie) 
                          VALUES (:nomProduit, :age, :prix, :imgProduit, :description, :quantiteStock, :categorie)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    'nomProduit' => $nomProduit,
                    'age' => $age,
                    'prix' => $prix,
                    'imgProduit' => $imgProduit,
                    'description' => $description,
                    'quantiteStock' => $quantiteStock,
                    'categorie' => $categorie
                ]);
                $_SESSION['success'] = "Produit ajouté avec succès.";
            } catch (PDOException $e) {
                // Logging error and setting error message
                error_log("Erreur lors de l'ajout du produit : " . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de l'ajout du produit.";
            }
        } else {
            // Setting error message for invalid data or category
            $_SESSION['error'] = "Données du produit invalides ou catégorie non valide.";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }

    // Function to update order status
    public function updateCommandeStatus() {
        // Validating CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        // Sanitizing and validating input
        $idCommande = filter_input(INPUT_POST, 'idCommande', FILTER_VALIDATE_INT);
        $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_STRING);

        // Checking if inputs are valid and status is allowed
        if ($idCommande && in_array($statut, ['En attente', 'Confirmée', 'Expédiée', 'Annulée'])) {
            try {
                // Starting a transaction
                $this->pdo->beginTransaction();

                // Updating order status
                $query = "UPDATE commande SET statut = :statut WHERE idCommande = :idCommande";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(['statut' => $statut, 'idCommande' => $idCommande]);

                // Fetching customer email
                $query = "SELECT u.emailUtil 
                          FROM commande c 
                          JOIN utilisateur u ON c.idUtilisateur = u.idUtilisateur 
                          WHERE c.idCommande = :idCommande";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(['idCommande' => $idCommande]);
                $customer = $stmt->fetch(PDO::FETCH_ASSOC);

                $emailSent = false;
                // Sending email notification if customer email is valid
                if ($customer && filter_var($customer['emailUtil'], FILTER_VALIDATE_EMAIL)) {
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

                // Committing the transaction
                $this->pdo->commit();
                $_SESSION['success'] = "Statut de la commande mis à jour." . ($customer && $emailSent ? " Email envoyé au client." : "");
            } catch (Exception $e) {
                // Rolling back transaction on error
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

    // Function to update message status
    public function updateMessageStatus() {
        // Validating CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        // Sanitizing and validating input
        $idMessage = filter_input(INPUT_POST, 'idMessage', FILTER_VALIDATE_INT);
        $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_STRING);

        // Checking if inputs are valid and status is allowed
        if ($idMessage && in_array($statut, ['Lu', 'Non lu', 'Répondu'])) {
            try {
                // Updating message status
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

    // Function to update an existing product
    public function updateProduit() {
        // Validating CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        // Sanitizing and validating input
        $idProduit = filter_input(INPUT_POST, 'idProduit', FILTER_VALIDATE_INT);
        $nomProduit = filter_input(INPUT_POST, 'nomProduit', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING);
        $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
        $imgProduit = filter_input(INPUT_POST, 'imgProduit', FILTER_SANITIZE_URL);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $quantiteStock = filter_input(INPUT_POST, 'quantiteStock', FILTER_VALIDATE_INT);
        $categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_STRING);

        // Defining allowed categories based on the ENUM in the Produit table
        $allowedCategories = ['Poules', 'Coqs', 'Œufs', 'Poussins'];

        // Checking if required fields and category are valid
        if ($idProduit && $nomProduit && $prix !== false && $quantiteStock !== false && in_array($categorie, $allowedCategories)) {
            try {
                // Updating product details in database
                $query = "UPDATE produit 
                          SET nomProduit = :nomProduit, age = :age, prix = :prix, imgProduit = :imgProduit, 
                              description = :description, quantiteStock = :quantiteStock, categorie = :categorie 
                          WHERE idProduit = :idProduit";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    'nomProduit' => $nomProduit,
                    'age' => $age,
                    'prix' => $prix,
                    'imgProduit' => $imgProduit,
                    'description' => $description,
                    'quantiteStock' => $quantiteStock,
                    'categorie' => $categorie,
                    'idProduit' => $idProduit
                ]);
                $_SESSION['success'] = "Produit mis à jour avec succès.";
            } catch (PDOException $e) {
                error_log("Erreur lors de la mise à jour du produit : " . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de la mise à jour du produit.";
            }
        } else {
            $_SESSION['error'] = "Données du produit invalides ou catégorie non valide.";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }

    // Function to delete a product
    public function deleteProduit() {
        // Validating CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        // Sanitizing and validating input
        $idProduit = filter_input(INPUT_POST, 'idProduit', FILTER_VALIDATE_INT);

        // Checking if product ID is valid
        if ($idProduit === false || $idProduit <= 0) {
            $_SESSION['error'] = "ID du produit invalide.";
            header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
            exit;
        }

        try {
            // Starting a transaction
            $this->pdo->beginTransaction();

            // Deleting related records from panier_produit
            $query = "DELETE FROM panier_produit WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idProduit' => $idProduit]);

            // Deleting related records from favoris
            $query = "DELETE FROM favoris WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idProduit' => $idProduit]);

            // Deleting the product
            $query = "DELETE FROM produit WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['idProduit' => $idProduit]);

            // Committing the transaction
            $this->pdo->commit();
            $_SESSION['success'] = "Produit supprimé avec succès.";
        } catch (PDOException $e) {
            // Rolling back transaction on error
            $this->pdo->rollBack();
            error_log("Erreur lors de la suppression du produit : " . $e->getMessage());
            $_SESSION['error'] = "Impossible de supprimer le produit. Vérifiez les dépendances (panier ou favoris).";
        }
        header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
        exit;
    }
}
?>