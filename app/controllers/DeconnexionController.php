<?php
class DeconnexionController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo; 
       }

    public function index()
    {
        session_start();

        $_SESSION = [];

        session_destroy();

        // Rediriger vers la page d’accueil ou de connexion
        header('Location: index.php?rout=accueil');
        exit;
    }
}
