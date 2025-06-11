<?php
class AboutController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        if (isset($_SESSION['idUtilisateur'])) {
            $idUtilisateur = $_SESSION['idUtilisateur'];

            $sql = "SELECT SUM(pp.QTE) AS total_qte
                    FROM panier_produit pp
                    JOIN panier p ON pp.idPanier = p.idPanier
                    WHERE p.idUtilisateur = :idUtilisateur";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idUtilisateur' => $idUtilisateur]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalQte = $result['total_qte'] ?? 0;
        } else {
            $totalQte = 0;
        }

        // Affiche la page À propos avec totalQte
        require '../app/views/about.php';
    }
}