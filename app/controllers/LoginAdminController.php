<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/LoginAdminModel.php';

class LoginAdminController {
    public function index() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        require_once __DIR__ . '/../views/loginAdminView.php';
    }

    public function login() {
        // Vérifier le jeton CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Erreur de validation CSRF.";
            header("Location: " . BASE_URL . "index.php?rout=admin/login");
            exit;
        }

        // Nettoyer et valider les entrées
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $motPasse = $_POST['motPasse'] ?? '';

        if (!$email || !$motPasse) {
            $_SESSION['error'] = "Veuillez remplir tous les champs.";
            header("Location: " . BASE_URL . "index.php?rout=admin/login");
            exit;
        }

        try {
            $model = new LoginAdminModel($GLOBALS['pdo']);
            $admin = $model->getAdminByEmail($email);

            if (!$admin) {
                $_SESSION['error'] = "Email non trouvé.";
                header("Location: " . BASE_URL . "index.php?rout=admin/login");
                exit;
            }

            if (password_verify($motPasse, $admin['motPasse'])) {
                session_regenerate_id(true); // Régénérer l'ID de session pour la sécurité
                $_SESSION['admin'] = [
                    'id' => $admin['idAdmin'],
                    'email' => $admin['emailAdmin'],
                    'nom' => $admin['nomAdmin']
                ];
                header("Location: " . BASE_URL . "index.php?rout=admin/dashboard");
                exit;
            } else {
                $_SESSION['error'] = "Mot de passe incorrect.";
                header("Location: " . BASE_URL . "index.php?rout=admin/login");
                exit;
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la connexion admin : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue. Veuillez réessayer plus tard.";
            header("Location: " . BASE_URL . "index.php?rout=admin/login");
            exit;
        }
    }
}