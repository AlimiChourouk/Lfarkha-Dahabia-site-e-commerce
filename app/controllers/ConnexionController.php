<?php
require_once __DIR__ . '/../models/connexionModule.php';

class ConnexionController {
    public function index() {
        require __DIR__ . '/../views/connexion.view.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $motPasse = $_POST['motPasse'] ?? '';

        $resultat = connecterUtilisateur($email, $motPasse);
        error_log("Login result: " . print_r($resultat, true)); 

        if ($resultat['success']) {
            header("Location: ?rout=accueil");
            exit;
        } else {
            $error = $resultat['message'];
            require __DIR__ . '/../views/connexion.view.php';
        }
    }
}