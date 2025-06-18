<?php
require_once '../app/models/MessageModel.php';

class MessageController {
    private $model;
    private $pdo;
    public $errorMessage = null;
    public $successMessage = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new MessageModel($pdo);
    }

    public function index() {
        $totalQte = isset($_SESSION['idUtilisateur']) ? $this->getCartQuantity($_SESSION['idUtilisateur']) : 0;
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

        if (strlen($contenu) > 1000) {
            $this->errorMessage = "Le message est trop long (max 1000 caractères).";
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

    private function getCartQuantity($idUtilisateur) {
        try {
            $sql = "SELECT SUM(pp.QTE) AS total_qte
                    FROM panier_produit pp
                    JOIN panier p ON pp.idPanier = p.idPanier
                    WHERE p.idUtilisateur = :idUtilisateur";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idUtilisateur' => $idUtilisateur]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_qte'] ?? 0;
        } catch (PDOException $e) {
            $this->errorMessage = "Erreur lors du calcul du panier.";
            return 0;
        }
    }
}
?>