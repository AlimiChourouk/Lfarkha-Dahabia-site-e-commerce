<?php
class MessageModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function hasSentMessageThisWeek($idUtil): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM Message 
            WHERE idUtil = ? 
            AND YEARWEEK(dateMessage, 1) = YEARWEEK(CURDATE(), 1)
        ");
        $stmt->execute([$idUtil]);
        return $stmt->fetchColumn() > 0;
    }

    public function saveMessage($idUtil, $contenu): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO Message (idUtil, contenu, dateMessage) 
            VALUES (?, ?, NOW())
        ");
        return $stmt->execute([$idUtil, $contenu]);
    }
}
