<?php
require_once __DIR__ . '/../models/PanierModel.php';

class PanierController {
    private $panierModel;

    public function __construct($pdo) {
        if (!isset($_SESSION['idUtilisateur'])) {
            $_SESSION['redirect_after_login'] = 'panier';
            header('Location: index.php?rout=connexion');
            exit();
        }

        $this->panierModel = new Panier($pdo, $_SESSION['idUtilisateur']);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        }
        $this->showPanier();
    }

    public function ajouterProduit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProduit']) && isset($_POST['qte'])) {
            $idProduit = (int)$_POST['idProduit'];
            $qte = (int)$_POST['qte'];

            $result = $this->panierModel->ajouterProduit($idProduit, $qte);
            if ($result['success']) {
                $_SESSION['success_message'] = $result['message'];
            } else {
                $_SESSION['error_message'] = $result['message'];
            }
        } else {
            $_SESSION['error_message'] = 'Requête invalide.';
        }

        header('Location: index.php?rout=produits');
        exit();
    }

    private function handlePost() {
        if (isset($_POST['update']) && isset($_POST['idProduit']) && isset($_POST['qte'])) {
            $idProduit = (int)$_POST['idProduit'];
            $qte = (int)$_POST['qte'];

            if ($_POST['update'] === 'increase') {
                $qte++;
            } elseif ($_POST['update'] === 'decrease' && $qte > 1) {
                $qte--;
            }

            $result = $this->panierModel->modifierQuantite($idProduit, $qte);
            if ($result['success']) {
                $_SESSION['success_message'] = $result['message'];
            } else {
                $_SESSION['error_message'] = $result['message'];
            }
        }

        if (isset($_POST['delete']) && isset($_POST['idProduit'])) {
            $idProduit = (int)$_POST['idProduit'];
            $result = $this->panierModel->supprimerProduit($idProduit);
            if ($result['success']) {
                $_SESSION['success_message'] = $result['message'];
            } else {
                $_SESSION['error_message'] = $result['message'];
            }
        }

        header('Location: index.php?rout=panier');
        exit();
    }

    private function showPanier() {
    $produits = $this->panierModel->getProduits();
    $totalCommande = 0;

    foreach ($produits as $produit) {
        $totalCommande += $produit['QTE'] * $produit['prix'];
    }

    $totalQte = $this->panierModel->getQuantiteTotale(); 

    require __DIR__ . '/../views/panier.view.php';
}

    
    
}