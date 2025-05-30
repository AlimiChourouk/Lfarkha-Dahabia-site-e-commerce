<?php
require_once '../config/db.php'; 
$controllers = [
        'AccueilController' => '../app/controllers/AccueilController.php',
        'InscriptionController' => '../app/controllers/InscriptionController.php',
        'MessageController' => '../app/controllers/MessageController.php',
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
            case 'contact':
                (new MessageController($GLOBALS['pdo']))->index();
                exit;

            case 'contact/envoyer':
            $this->checkPost();
            (new MessageController($GLOBALS['pdo']))->envoyer();
            exit;
            default:
                echo "Page non trouvée.";
                break;
        }
    }
}