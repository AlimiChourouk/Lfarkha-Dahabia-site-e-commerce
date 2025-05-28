<?php
require_once __DIR__ . '/../models/Utilisateur.php';
use App\Models\Utilisateur;

class InscriptionController {
    public function index() {
        
        $errors = [];
        require __DIR__ . '/../views/inscription.view.php';
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $prenom = trim($_POST['prenom'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $tel = trim($_POST['tel'] ?? '');
            $motPasse = $_POST['motPasse'] ?? '';

            $errors = [];

            
            if (empty($prenom)) {
                $errors['prenom'] = "Le prénom est obligatoire.";
            }
            if (empty($nom)) {
                $errors['nom'] = "Le nom est obligatoire.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "L'adresse email est invalide.";
            }
            if (!preg_match('/^[0-9]{10}$/', $tel)) {
                $errors['tel'] = "Le numéro de téléphone doit contenir 10 chiffres.";
            }
            if (strlen($motPasse) < 6) {
                $errors['motPasse'] = "Le mot de passe doit contenir au moins 6 caractères.";
            }

            if (empty($errors)) {
                $utilisateurModel = new Utilisateur();

               
                if ($utilisateurModel->emailExists($email)) {
                    $errors['email'] = "Cet email est déjà utilisé.";
                    require __DIR__ . '/../views/inscription.view.php';
                    return;
                }

                
                $motPasseHash = password_hash($motPasse, PASSWORD_DEFAULT);

                
                if ($utilisateurModel->register($prenom, $nom, $email, $tel, $motPasseHash)) {
                    global $pdo;
                    $userId = $pdo->lastInsertId();

                    
                    $_SESSION['idUtilisateur'] = $userId;
                    $_SESSION['emailUtil'] = $email;
                    $_SESSION['telUtil'] = $tel;
                    $_SESSION['prenomUtil'] = $prenom;
                    $_SESSION['nomUtil'] = $nom;

                    session_write_close(); 
                    header("Location: ?rout=accueil");
                    exit;
                } else {
                    $error = "Erreur lors de l'inscription.";
                    require __DIR__ . '/../views/inscription.view.php';
                }
            } else {
                require __DIR__ . '/../views/inscription.view.php';
            }
        } else {
            $this->index();
        }
    }
}