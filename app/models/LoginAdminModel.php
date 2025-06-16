<?php
class LoginAdminModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAdminByEmail($email) {
        try {
            $query = "SELECT * FROM admin WHERE emailAdmin = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'admin : " . $e->getMessage());
            return false;
        }
    }
}