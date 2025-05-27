<?php
require_once '../config/db.php'; 
$controllers = [
        'AccueilController' => '../app/controllers/AccueilController.php',
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

            default:
                echo "Page non trouvée.";
                break;
        }
    }
}