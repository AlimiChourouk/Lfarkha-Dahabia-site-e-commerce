<?php
require_once '../config/db.php'; 
$controllers = [
        'AccueilController' => '../app/controllers/AccueilController.php',
        'InscriptionController' => '../app/controllers/InscriptionController.php',
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

            default:
                echo "Page non trouvée.";
                break;
        }
    }
}