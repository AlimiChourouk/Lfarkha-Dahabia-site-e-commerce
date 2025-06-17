<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/db.php';

function statutToCssClass($statut) {
    $statut = strtolower($statut);
    $statut = str_replace(' ', '-', $statut);
    $statut = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $statut);
    $statut = preg_replace('/[^a-z0-9\-]/', '', $statut);
    return $statut;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/adminDashboard.css?v=<?= time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> Tableau de bord Admin</h1>
        </div>

        <!-- Mobile Tabs -->
        <div class="mobile-tabs">
            <button class="tab-btn active" data-section="stats">Stats</button>
            <button class="tab-btn" data-section="commandes">Commandes</button>
            <button class="tab-btn" data-section="produits">Produits</button>
            <button class="tab-btn" data-section="messages">Messages</button>
            <button class="tab-btn" data-section="graphiques">Graphiques</button>
        </div>

        <div class="main-layout">
            <!-- Contenu principal -->
            <div class="content-wrapper">
                <!-- Alertes -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div id="alertSuccess" class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span id="successMessage"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php else: ?>
                    <div id="alertSuccess" class="alert alert-success hidden">
                        <i class="fas fa-check-circle"></i>
                        <span id="successMessage"></span>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div id="alertError" class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="errorMessage"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php else: ?>
                    <div id="alertError" class="alert alert-error hidden">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="errorMessage"></span>
                    </div>
                <?php endif; ?>

                <!-- Statistiques -->
                <div class="section-content" data-section="stats">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-users text-blue-500"></i>
                            <h3>Utilisateurs</h3>
                            <p class="text-blue-600"><?= htmlspecialchars($totalUsers, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-shopping-cart text-green-500"></i>
                            <h3>Commandes</h3>
                            <p class="text-green-600"><?= htmlspecialchars(array_sum(array_column($commandesByStatus, 'count')), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-box text-purple-500"></i>
                            <h3>Produits</h3>
                            <p class="text-purple-600"><?= htmlspecialchars(count($produits), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-envelope text-orange-500"></i>
                            <h3>Messages</h3>
                            <p class="text-orange-600"><?= htmlspecialchars(count($messages), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>

                    <!-- Commandes par statut -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-pie text-blue-500"></i>
                            Commandes par statut
                        </div>
                        <div class="status-grid">
                            <?php foreach ($commandesByStatus as $status): ?>
                                <?php $cssClass = statutToCssClass($status['statut']); ?>
                                <div class="status-box status-<?= htmlspecialchars($cssClass, ENT_QUOTES, 'UTF-8') ?>">
                                    <p><?= htmlspecialchars($status['statut'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="text-lg"><?= htmlspecialchars($status['count'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Commandes -->
                <div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Adresse</th>
                <th>Produits</th> <!-- Nouvelle colonne pour les produits -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['idCommande'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($commande['emailUtil'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($commande['dateCommande'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span class="status-box status-<?= htmlspecialchars(statutToCssClass($commande['statut']), ENT_QUOTES, 'UTF-8') ?>" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                            <?= htmlspecialchars($commande['statut'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($commande['adresseLivraison'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?= htmlspecialchars($commande['produits'] ?? 'Aucun produit', ENT_QUOTES, 'UTF-8') ?>
                        <br>
                        <small>(IDs: <?= htmlspecialchars($commande['produit_ids'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>)</small>
                    </td>
                    <td>
                        <form method="POST" action="<?= BASE_URL ?>index.php?rout=admin/updateCommandeStatus" class="inline-flex items-center">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="idCommande" value="<?= htmlspecialchars($commande['idCommande'], ENT_QUOTES, 'UTF-8') ?>">
                            <select name="statut" class="input-field status-<?= htmlspecialchars(statutToCssClass($commande['statut']), ENT_QUOTES, 'UTF-8') ?>" style="width: auto; padding: 0.3rem;">
                                <option value="En attente" <?= $commande['statut'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="Confirmée" <?= $commande['statut'] === 'Confirmée' ? 'selected' : '' ?>>Confirmée</option>
                                <option value="Expédiée" <?= $commande['statut'] === 'Expédiée' ? 'selected' : '' ?>>Expédiée</option>
                                <option value="Annulée" <?= $commande['statut'] === 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                            </select>
                            <button type="submit" class="btn btn-primary" style="margin-left: 0.5rem; padding: 0.3rem 0.6rem;">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

                <!-- Produits -->
                <div class="section-content" data-section="produits">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-boxes text-purple-500"></i>
                            Gestion des produits
                        </div>
                        
                        <!-- Formulaire d'ajout -->
                        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <h4 style="font-weight: 600; margin-bottom: 0.8rem; font-size: 0.95rem;">Ajouter un produit</h4>
                            <form method="POST" action="<?= BASE_URL ?>index.php?rout=admin/addProduit">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="form-grid">
                                    <input type="text" name="nomProduit" id="nomProduit" class="input-field" placeholder="Nom du produit" required>
                                    <input type="text" name="age" id="age" class="input-field" placeholder="Âge">
                                    <input type="number" name="prix" id="prix" step="0.01" class="input-field" placeholder="Prix" required>
                                    <input type="number" name="quantiteStock" id="quantiteStock" class="input-field" placeholder="Stock" required>
                                    <input type="url" name="imgProduit" id="imgProduit" class="input-field" placeholder="URL Image" style="grid-column: 1 / -1;">
                                    <textarea name="description" id="description" class="input-field" rows="2" placeholder="Description" style="grid-column: 1 / -1;"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Ajouter
                                </button>
                            </form>
                        </div>

                        <!-- Liste des produits -->
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Âge</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produits as $produit): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($produit['idProduit'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($produit['nomProduit'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($produit['age'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($produit['prix'], ENT_QUOTES, 'UTF-8') ?> DH</td>
                                            <td><?= htmlspecialchars($produit['quantiteStock'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <button onclick="toggleForm(<?= htmlspecialchars($produit['idProduit'], ENT_QUOTES, 'UTF-8') ?>)" class="btn btn-warning" style="margin-right: 0.3rem; padding: 0.3rem 0.6rem;">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" action="<?= BASE_URL ?>index.php?rout=admin/deleteProduit" class="inline-block">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <input type="hidden" name="idProduit" value="<?= htmlspecialchars($produit['idProduit'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <button type="submit" class="btn btn-danger" style="padding: 0.3rem 0.6rem;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <form id="form-<?= htmlspecialchars($produit['idProduit'], ENT_QUOTES, 'UTF-8') ?>" method="POST" action="<?= BASE_URL ?>index.php?rout=admin/updateProduit" class="hidden mt-2">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <input type="hidden" name="idProduit" value="<?= htmlspecialchars($produit['idProduit'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <div class="form-grid">
                                                        <input type="text" name="nomProduit" value="<?= htmlspecialchars($produit['nomProduit'], ENT_QUOTES, 'UTF-8') ?>" class="input-field" required>
                                                        <input type="text" name="age" value="<?= htmlspecialchars($produit['age'], ENT_QUOTES, 'UTF-8') ?>" class="input-field">
                                                        <input type="number" name="prix" value="<?= htmlspecialchars($produit['prix'], ENT_QUOTES, 'UTF-8') ?>" step="0.01" class="input-field" required>
                                                        <input type="number" name="quantiteStock" value="<?= htmlspecialchars($produit['quantiteStock'], ENT_QUOTES, 'UTF-8') ?>" class="input-field" required>
                                                        <input type="url" name="imgProduit" value="<?= htmlspecialchars($produit['imgProduit'], ENT_QUOTES, 'UTF-8') ?>" class="input-field" style="grid-column: 1 / -1;">
                                                        <textarea name="description" class="input-field" rows="2" style="grid-column: 1 / -1;"><?= htmlspecialchars($produit['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save"></i> Enregistrer
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="section-content" data-section="messages">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-comments text-teal-500"></i>
                            Messages
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Contenu</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($message['idMessage'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($message['emailUtil'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($message['contenu'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($message['dateMessage'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <span class="status-box status-<?= htmlspecialchars(statutToCssClass($message['statut']), ENT_QUOTES, 'UTF-8') ?>" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                                    <?= htmlspecialchars($message['statut'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <form method="POST" action="<?= BASE_URL ?>index.php?rout=admin/updateMessageStatus" class="inline-flex items-center">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <input type="hidden" name="idMessage" value="<?= htmlspecialchars($message['idMessage'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <select name="statut" class="input-field status-<?= htmlspecialchars(statutToCssClass($message['statut']), ENT_QUOTES, 'UTF-8') ?>" style="width: auto; padding: 0.3rem;">
                                                        <option value="Non lu" <?= $message['statut'] === 'Non lu' ? 'selected' : '' ?>>Non lu</option>
                                                        <option value="Lu" <?= $message['statut'] === 'Lu' ? 'selected' : '' ?>>Lu</option>
                                                        <option value="Répondu" <?= $message['statut'] === 'Répondu' ? 'selected' : '' ?>>Répondu</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary" style="margin-left: 0.5rem; padding: 0.3rem 0.6rem;">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="section-content" data-section="graphiques">
                    <div class="charts-grid">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-bar text-indigo-500"></i>
                                Produits demandés
                            </div>
                            <div class="chart-container">
                                <canvas id="produitsChart"></canvas>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-line text-pink-500"></i>
                                Évolution des ventes
                            </div>
                            <div class="chart-container">
                                <canvas id="ventesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="filter-title">
                    <i class="fas fa-filter"></i> Filtres
                </div>
                
                <!-- Recherche -->
                <div class="filter-section">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchGeneral" class="input-field" placeholder="Rechercher...">
                    </div>
                </div>

                <!-- Sections -->
                <div class="filter-section">
                    <h4 class="filter-title">Sections</h4>
                    <div class="checkbox-group">
                        <label class="checkbox-item">
                            <input type="checkbox" class="section-filter" value="stats" checked> Stats
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="section-filter" value="commandes" checked> Commandes
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="section-filter" value="produits" checked> Produits
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="section-filter" value="messages" checked> Messages
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="section-filter" value="graphiques" checked> Graphiques
                        </label>
                    </div>
                </div>

                <!-- Statuts -->
                <div class="filter-section">
                    <h4 class="filter-title">Statuts</h4>
                    <div class="checkbox-group">
                        <label class="checkbox-item">
                            <input type="checkbox" class="status-filter" value="en-attente" checked> En attente
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="status-filter" value="confirmee" checked> Confirmée
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="status-filter" value="expediee" checked> Expédiée
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="status-filter" value="annulee" checked> Annulée
                        </label>
                    </div>
                </div>

                <!-- Période -->
                <div class="filter-section">
                    <h4 class="filter-title">Période</h4>
                    <select class="input-field" id="periodFilter">
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month" selected>Ce mois</option>
                        <option value="year">Cette année</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="filter-section">
                    <button class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;" onclick="applyFilters()">
                        <i class="fas fa-check"></i> Appliquer
                    </button>
                    <button class="btn btn-danger" style="width: 100%;" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion des onglets mobiles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                document.querySelectorAll('.section-content').forEach(section => {
                    section.style.display = 'none';
                });
                
                const targetSection = this.dataset.section;
                const targetElement = document.querySelector(`[data-section="${targetSection}"]`);
                if (targetElement) {
                    targetElement.style.display = 'block';
                }
            });
        });

        // Initialiser l'affichage pour mobile
        function initMobileView() {
            if (window.innerWidth <= 768) {
                document.querySelectorAll('.section-content').forEach((section, index) => {
                    section.style.display = index === 0 ? 'block' : 'none';
                });
            } else {
                document.querySelectorAll('.section-content').forEach(section => {
                    section.style.display = 'block';
                });
            }
        }

        window.addEventListener('load', initMobileView);
        window.addEventListener('resize', initMobileView);

        // Gestion du formulaire de modification des produits
        function toggleForm(id) {
            const form = document.getElementById('form-' + id);
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }

        // Mise à jour dynamique des classes de statut
        document.querySelectorAll('select[name="statut"]').forEach(select => {
            select.addEventListener('change', function () {
                this.className = this.className.replace(/\bstatus-[\w-]+\b/g, '');
                const newClass = 'input-field status-' + this.value.toLowerCase().replace(/é/g, 'e').replace(/\s+/g, '-');
                this.classList.add(...newClass.split(' '));
            });
        });

        // Initialisation des graphiques
        const produitsChart = new Chart(document.getElementById('produitsChart'), {
            type: 'bar',
            data: {
                labels: [<?php foreach ($produitsDemande as $produit) { echo "'" . htmlspecialchars($produit['nomProduit'], ENT_QUOTES, 'UTF-8') . "',"; } ?>],
                datasets: [{
                    label: 'Quantité',
                    data: [<?php foreach ($produitsDemande as $produit) { echo $produit['totalQte'] . ","; } ?>],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        const ventesChart = new Chart(document.getElementById('ventesChart'), {
            type: 'line',
            data: {
                labels: [<?php foreach ($ventesData as $vente) { echo "'" . htmlspecialchars($vente['date'], ENT_QUOTES, 'UTF-8') . "',"; } ?>],
                datasets: [{
                    label: 'Ventes',
                    data: [<?php foreach ($ventesData as $vente) { echo $vente['count'] . ","; } ?>],
                    borderColor: 'rgba(236, 72, 153, 0.8)',
                    backgroundColor: 'rgba(236, 72, 153, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(236, 72, 153, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Gestion des filtres par section
        document.querySelectorAll('.section-filter').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const sectionName = this.value;
                const sections = document.querySelectorAll(`[data-section="${sectionName}"]`);
                sections.forEach(section => {
                    if (this.checked) {
                        section.style.display = '';
                    } else {
                        section.style.display = 'none';
                    }
                });
            });
        });

        // Recherche générale
        document.getElementById('searchGeneral').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const sections = document.querySelectorAll('.section-content');
            sections.forEach(section => {
                const titre = section.querySelector('.card-header');
                if (titre && titre.textContent.toLowerCase().includes(searchTerm)) {
                    section.style.display = '';
                } else {
                    section.style.display = 'none';
                }
            });
        });

        // Filtres par statut des commandes
        function applyFilters() {
            const statusFilters = Array.from(document.querySelectorAll('.status-filter:checked')).map(cb => cb.value);
            const rows = document.querySelectorAll('.section-content[data-section="commandes"] table tbody tr');
            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(4) span');
                if (statusCell) {
                    const statusClass = Array.from(statusCell.classList).find(cls => cls.startsWith('status-'));
                    const statusValue = statusClass ? statusClass.replace('status-', '') : '';
                    if (statusFilters.length === 0 || statusFilters.includes(statusValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // Réinitialisation des filtres
        function resetFilters() {
            document.querySelectorAll('.section-filter').forEach(cb => cb.checked = true);
            document.querySelectorAll('.status-filter').forEach(cb => cb.checked = true);
            document.getElementById('searchGeneral').value = '';
            document.getElementById('periodFilter').value = 'month';
            const sections = document.querySelectorAll('.section-content');
            sections.forEach(section => section.style.display = '');
            const rows = document.querySelectorAll('.section-content[data-section="commandes"] table tbody tr');
            rows.forEach(row => row.style.display = '');
            if (window.innerWidth <= 768) {
                initMobileView();
            }
        }
    </script>
</body>
</html>