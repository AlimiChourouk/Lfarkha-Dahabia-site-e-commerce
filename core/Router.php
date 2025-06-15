<?php
require_once '../config/db.php'; 
$controllers = [
        'AccueilController' => '../app/controllers/AccueilController.php',
        'InscriptionController' => '../app/controllers/InscriptionController.php',
        'ConnexionController' => '../app/controllers/ConnexionController.php',
        'MessageController' => '../app/controllers/MessageController.php',
        'AboutController' => '../app/controllers/AboutController.php',
        'PanierController' => '../app/controllers/PanierController.php',
    ];
    foreach ($controllers as $file) {
        require_once $file;
    }
class Router {
    
   public function dispatch($route) {
        $route = explode('?', $route)[0];

        switch ($route) {
            case 'accueil':
                (new AccueilController())->index();
                break;
            case 'inscription':
                (new InscriptionController())->index();
                exit;

            case 'inscription/register':
                $this->checkPost();
                (new InscriptionController())->register();
                exit;  
            case 'connexion':
                    (new ConnexionController())->index();
                    exit;
    
            case 'connexion/login':
                    $this->checkPost();
                    (new ConnexionController())->login();
                    exit;      
            case 'contact':
                (new MessageController($GLOBALS['pdo']))->index();
                exit;

            case 'contact/envoyer':
            $this->checkPost();
            (new MessageController($GLOBALS['pdo']))->envoyer();
            exit;
            case 'about':
                (new AboutController($GLOBALS['pdo']))->index();
                exit;
                  case 'panier':
                (new PanierController($GLOBALS['pdo']))->handleRequest();
                exit;

            case 'panier/ajouter':
                $this->checkPost();
                (new PanierController($GLOBALS['pdo']))->ajouterProduit();
                exit;

            case 'panier/modifier':
            case 'panier/supprimer':
                $this->checkPost();
                (new PanierController($GLOBALS['pdo']))->handleRequest();
                exit;

            default:
                echo "Page non trouvée.";
                break;
        }
    }
}