<?php
require_once '../config/db.php'; 

class Router {
    $controllers = [
        'AccueilController' => '../app/controllers/AccueilController.php',
    ];
    foreach ($controllers as $file) {
        require_once $file;
    }
    public function dispatch($route) {
        $route = explode('?', $route)[0];
        switch ($route) {
            case 'accueil':
  }
    default:
    http_response_code(404);
    echo "Erreur 404 : Page non trouvée.";
    exit;
}
}