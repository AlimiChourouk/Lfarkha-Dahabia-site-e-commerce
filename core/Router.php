<?php
require_once '../config/db.php'; 

class Router {
    public function dispatch($route) {
        // Supprimer les paramètres GET de la route
        $route = explode('?', $route)[0];

        switch ($route) {
            case 'accueil':
                require_once '../app/controllers/AccueilController.php';
                $controller = new AccueilController();
                $controller->index();
                break;

            case 'inscription':
                require_once '../app/controllers/InscriptionController.php';
                $controller = new InscriptionController();
                $controller->index();
                break;

            case 'inscription/register':
                require_once '../app/controllers/InscriptionController.php';
                $controller = new InscriptionController();
                $controller->register();
                break;

            case 'connexion':
                require_once '../app/controllers/ConnexionController.php';
                $controller = new ConnexionController();
                $controller->index();
                break;

            case 'connexion/login':
                require_once '../app/controllers/ConnexionController.php';
                $controller = new ConnexionController();
                $controller->login();
                break;

            case 'panier':
                require_once '../app/controllers/PanierController.php';
                $controller = new PanierController($GLOBALS['pdo']);
                $controller->handleRequest();
                break;

            case 'panier/ajouter':
                require_once '../app/controllers/PanierController.php';
                $controller = new PanierController($GLOBALS['pdo']);
                $controller->ajouterProduit();
                break;

            case 'panier/modifier':
                require_once '../app/controllers/PanierController.php';
                $controller = new PanierController($GLOBALS['pdo']);
                $controller->handleRequest(); 
                break;

            case 'panier/supprimer':
                require_once '../app/controllers/PanierController.php';
                $controller = new PanierController($GLOBALS['pdo']);
                $controller->handleRequest(); 
                break;
            case 'produit/details':
                require_once '../app/controllers/ProduitController.php';
                $controller = new ProduitController($GLOBALS['pdo']);
                $controller->showDetails();
                break;
            case 'admin/login':
                require_once '../app/controllers/LoginAdminController.php';
                $controller = new LoginAdminController();
                $controller->index();
                break;
                
            default:
                echo "Page non trouvée.";
        }
    }
}