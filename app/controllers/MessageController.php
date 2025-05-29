<?php
require_once '../app/models/MessageModel.php';


class MessageController {
    private $model;
    public $errorMessage = null;
    public $successMessage = null;

    public function __construct($pdo) {
        $this->model = new MessageModel($pdo);
    }

    public function index() {
        require __DIR__ . '/../views/contacte.view.php';
    }

    public function envoyer() {
        if (!isset($_SESSION['idUtilisateur'])) {
            $this->errorMessage = "Vous devez être connecté pour envoyer un message.";
            $this->index();
            return;
        }

        $idUtil = $_SESSION['idUtilisateur'];
        $contenu = trim($_POST['contenu'] ?? '');

        if (empty($contenu)) {
            $this->errorMessage = "Le message ne peut pas être vide.";
            $this->index();
            return;
        }

        if ($this->model->hasSentMessageThisWeek($idUtil)) {
            $this->errorMessage = "Vous avez déjà envoyé un message cette semaine.";
            $this->index();
            return;
        }

        if ($this->model->saveMessage($idUtil, $contenu)) {
            $this->successMessage = "Message envoyé avec succès.";
        } else {
            $this->errorMessage = "Une erreur est survenue.";
        }

        $this->index();
    }
}
