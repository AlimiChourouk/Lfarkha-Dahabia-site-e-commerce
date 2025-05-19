<?php
require_once __DIR__ . '/../config/constants.php'; // Si tu veux BASE_URL
require_once __DIR__ . '/../core/Router.php';

$router = new Router();
$route = $_GET['rout'] ?? 'accueil'; // route par défaut
$router->dispatch($route);
