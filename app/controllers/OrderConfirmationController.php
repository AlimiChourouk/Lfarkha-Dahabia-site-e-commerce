<?php

require_once __DIR__ . '/../models/DashboardModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class DashboardController {
    private $model;
    private $userModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new DashboardModel($pdo);
        $this->userModel = new UserModel($pdo);
    }

   public function index() {
    if (!isset($_SESSION['idUtilisateur'])) {
        header('Location: ?rout=connexion');
        exit();
    }

    $userId = $_SESSION['idUtilisateur'];

    $user = $this->userModel->getUserById($userId);
    $commandes = $this->model->getCommandesByUserId($userId);

    // Pour les favoris, tu dois instancier ta classe Favoris avec le $pdo et $userId
    require_once __DIR__ . '/../models/FavorisModel.php';
    $favorisModel = new Favoris($this->pdo, $userId);
    $favoris = $favorisModel->getFavoris();

    require_once __DIR__ . '/../views/dashboard.php';
}

}
