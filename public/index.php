<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set session configuration before starting the session
ini_set('session.cookie_lifetime', 0); // Session lasts until browser is closed
ini_set('session.use_strict_mode', 1); // Prevent session fixation

// Start the session
session_start();

// Include necessary files
require_once __DIR__ . '/../config/constants.php'; // For BASE_URL
require_once __DIR__ . '/../core/Router.php';

// Initialize and dispatch the router
$router = new Router();
$route = $_GET['rout'] ?? 'accueil'; // Default route
$router->dispatch($route);
