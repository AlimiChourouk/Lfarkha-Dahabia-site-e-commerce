<?php 
function connecterUtilisateur($email, $motPasse) {
    global $pdo;

    if (empty($email) || empty($motPasse)) {
        return ['success' => false, 'message' => 'Tous les champs sont requis.'];
    }

    // Rechercher l'utilisateur par email
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE emailUtil = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($user && password_verify($motPasse, $user['motPasse'])) {       
        session_start(); 
        $_SESSION['idUtilisateur'] = $user['idUtilisateur'];
        $_SESSION['nomUtil'] = $user['nomUtil'];
        $_SESSION['prenomUtil'] = $user['prenomUtil'];
        $_SESSION['emailUtil'] = $user['emailUtil'];
        $_SESSION['telUtil'] = $user['telUtil'];
        session_write_close(); 
        return ['success' => true, 'message' => 'Connexion réussie.'];
    } else {
        return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
    }
} 
