<?php
class UserModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getUserById($userId) {
        $query = "SELECT prenomUtil, nomUtil, emailUtil, telUtil FROM utilisateur WHERE idUtilisateur = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateUser($userId, $prenom, $nom, $email, $tel, $motPasse) {
        try {
            $query = "UPDATE utilisateur SET prenomUtil = ?, nomUtil = ?, emailUtil = ?, telUtil = ?";
            $params = [$prenom, $nom, $email, $tel];
            if ($motPasse !== null) {
                $query .= ", motPasse = ?";
                $params[] = $motPasse;
            }
            $query .= " WHERE idUtilisateur = ?";
            $params[] = $userId;
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Update user error for user $userId: " . $e->getMessage());
            if ($e->getCode() == '23000') { 
                return "Erreur : Cet email est déjà utilisé.";
            }
            return "Erreur lors de la mise à jour.";
        }
    }
}
?>