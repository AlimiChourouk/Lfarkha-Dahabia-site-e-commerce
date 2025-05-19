<?php
namespace App\Models;

use PDO;

class Utilisateur {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../../config/db.php';
        global $pdo;
        $this->pdo = $pdo;
    }

    public function register($prenom, $nom, $email, $tel, $motPasse, $statue = 'actif') {
        $sql = "INSERT INTO utilisateur (prenomUtil, nomUtil, emailUtil, telUtil, motPasse, statue)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$prenom, $nom, $email, $tel, $motPasse, $statue]);
    }

    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE emailUtil = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM utilisateur WHERE emailUtil = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>