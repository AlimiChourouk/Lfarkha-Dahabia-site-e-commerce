<?php

require_once '../app/models/OrderConfirmationModel.php';
require_once '../app/models/UserModel.php';
require_once '../app/utils/EmailService.php';

class OrderConfirmationController {
    private $model;
    private $userModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new OrderConfirmationModel($pdo);
        $this->userModel = new UserModel($pdo);
    }

    public function index() {
        if (!isset($_SESSION['idUtilisateur'])) {
            $_SESSION['error_message'] = "Veuillez vous connecter pour accéder à la page de confirmation.";
            header('Location: ?rout=connexion');
            exit();
        }

        $userId = $_SESSION['idUtilisateur'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("POST data received: " . print_r($_POST, true));
            $this->confirmOrder($userId);
        }

        $produits = $this->model->getCartItems($userId);
        $totalCommande = $this->model->getCartTotal($userId);
        $user = $this->userModel->getUserById($userId);

        require_once '../app/views/order_confirmation.view.php';
    }

    private function confirmOrder($userId) {
        $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
        error_log("Adresse value: '$adresse'");
        if (empty($adresse)) {
            $_SESSION['error_message'] = "L'adresse de livraison est requise.";
            error_log("Error: Adresse is empty or not set");
            return;
        }
        

        try {
            $this->pdo->beginTransaction();

            // Get cart items and total before saving order
            $produits = $this->model->getCartItems($userId);
            $total = $this->model->getCartTotal($userId);

            // Save order
            $orderId = $this->model->saveOrder($userId, $adresse);

            // Get user email and validate
            $user = $this->userModel->getUserById($userId);
            $emailMessage = "";
            if (!filter_var($user['emailUtil'], FILTER_VALIDATE_EMAIL)) {
                error_log("Invalid email for user ID $userId: " . $user['emailUtil']);
                $emailMessage = "Veuillez vérifier votre adresse email pour recevoir la confirmation.";
            } else {
                // Send confirmation email
                $emailSent = EmailService::sendOrderConfirmationEmail(
                    $user['emailUtil'],
                    $orderId,
                    $produits,
                    $total,
                    $adresse,
                    'En attente'
                );
                $emailMessage = $emailSent ? "Un email de confirmation a été envoyé à votre adresse." : "Échec de l'envoi de l'email de confirmation. Veuillez contacter le support.";
            }

            $this->pdo->commit();

            $_SESSION['success_message'] = "Votre commande a été enregistrée avec succès ! $emailMessage";
            header('Location: ?rout=confirmation');
            exit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $_SESSION['error_message'] = "Une erreur s'est produite lors de l'enregistrement de la commande : " . $e->getMessage();
            error_log("Order save error: " . $e->getMessage());
        }
    }
}
?>