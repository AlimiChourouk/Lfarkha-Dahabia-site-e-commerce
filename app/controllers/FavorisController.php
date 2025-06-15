<?php
require_once __DIR__ . '/../models/FavorisModel.php';

class FavorisController {
    private $favorisModel;

    public function __construct($pdo) {
        if (!isset($_SESSION['idUtilisateur'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Vous devez être connecté.', 'redirect' => 'index.php?rout=connexion']);
                exit();
            }
            $_SESSION['redirect_after_login'] = 'favoris';
            header('Location: index.php?rout=connexion');
            exit();
        }
        error_log("FavorisController: idUtilisateur=" . $_SESSION['idUtilisateur']);
        $this->favorisModel = new Favoris($pdo, $_SESSION['idUtilisateur']);
    }

    public function index() {
        $produits = $this->favorisModel->getFavoris();
        require __DIR__ . '/../views/favoris.view.php';
    }

    public function toggle() {
        error_log("FavorisController::toggle called");
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProduit'])) {
            $idProduit = (int)$_POST['idProduit'];
            error_log("Toggling favori for idProduit=$idProduit, idUtilisateur=" . $_SESSION['idUtilisateur']);
            $result = $this->favorisModel->toggleFavori($idProduit);
            error_log("Toggle result: " . print_r($result, true));
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }
        error_log("Invalid request for favoris/toggle");
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
        exit();
    }
}