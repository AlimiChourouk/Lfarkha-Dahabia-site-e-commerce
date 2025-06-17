<?php
require_once '../config/db.php';

$controllers = [
    'AccueilController' => '../app/controllers/AccueilController.php',
    'InscriptionController' => '../app/controllers/InscriptionController.php',
    'ConnexionController' => '../app/controllers/ConnexionController.php',
    'PanierController' => '../app/controllers/PanierController.php',
    'ProduitController' => '../app/controllers/ProduitController.php',
    'ProduitsController' => '../app/controllers/ProduitsController.php',
    'FavorisController' => '../app/controllers/FavorisController.php',
    'LoginAdminController' => '../app/controllers/LoginAdminController.php',
    'AdminDashboardController' => '../app/controllers/AdminDashboardController.php',
    'DashboardController' => '../app/controllers/DashboardController.php',
    'OrderConfirmationController' => '../app/controllers/OrderConfirmationController.php',
    'MessageController' => '../app/controllers/MessageController.php',
    'AboutController' => '../app/controllers/AboutController.php',
    'conseilController' => '../app/controllers/conseilController.php',
    'DeconnexionController' => '../app/controllers/DeconnexionController.php',
];

foreach ($controllers as $file) {
    require_once $file;
}

class Router {
    public function dispatch($route) {
        $route = explode('?', $route)[0];

        // Rediriger un admin connecté
        if ($route === 'admin/login' && isset($_SESSION['admin'])) {
            header("Location: index.php?rout=admin/dashboard");
            exit;
        }

        switch ($route) {
            case 'accueil':
                (new AccueilController())->index();
                exit;

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

            case 'produit/details':
                (new ProduitController($GLOBALS['pdo']))->showDetails();
                exit;
            case 'produit':
                (new ProduitController($GLOBALS['pdo']))->index();
                exit;
            case 'produits':
                (new ProduitsController($GLOBALS['pdo']))->index();
                exit;

            case 'favoris':
                (new FavorisController($GLOBALS['pdo']))->index();
                exit;

            case 'favoris/toggle':
                $this->checkPost();
                (new FavorisController($GLOBALS['pdo']))->toggle();
                exit;

            case 'confirmation':
                (new OrderConfirmationController($GLOBALS['pdo']))->index();
                exit;

            case 'admin/login':
                $controller = new LoginAdminController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->login();
                } else {
                    $controller->index();
                }
                exit;

            case 'admin/dashboard':
                (new AdminDashboardController($GLOBALS['pdo']))->index();
                exit;

            case 'admin/updateCommandeStatus':
                $this->checkPost();
                (new AdminDashboardController($GLOBALS['pdo']))->updateCommandeStatus();
                exit;

            case 'admin/updateMessageStatus':
                $this->checkPost();
                (new AdminDashboardController($GLOBALS['pdo']))->updateMessageStatus();
                exit;

            case 'admin/addProduit':
                $this->checkPost();
                (new AdminDashboardController($GLOBALS['pdo']))->addProduit();
                exit;

            case 'admin/updateProduit':
                $this->checkPost();
                (new AdminDashboardController($GLOBALS['pdo']))->updateProduit();
                exit;

            case 'admin/deleteProduit':
                $this->checkPost();
                (new AdminDashboardController($GLOBALS['pdo']))->deleteProduit();
                exit;

            case 'dashboard':
                (new DashboardController($GLOBALS['pdo']))->index();
                exit;

            case 'dashboard/modifierFormation':
                $controller = new DashboardController($GLOBALS['pdo']);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->checkPost();
                    $controller->modifierFormation();
                } else {
                    $controller->modifierFormation();
                }
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

            case 'conseils':
                (new ConseilController($GLOBALS['pdo']))->index();
                exit;

            case 'Deconnexion':
                (new DeconnexionController($GLOBALS['pdo']))->index();
                exit;

            default:
                http_response_code(404);
                echo "Erreur 404 : Page non trouvée.";
                exit;
        }
    }

    private function checkPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée.";
            exit;
        }
    }
}

$router = new Router();
$router->dispatch($_GET['rout'] ?? 'accueil');
?>