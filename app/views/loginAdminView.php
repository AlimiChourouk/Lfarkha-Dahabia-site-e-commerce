<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/loginAdmin.css?v=<?= time(); ?>">
        <link rel="stylesheet" href="/Lfarkha-Dahabia-site-e-commerce/public/css/loginAdmin.css?v=<?= time(); ?>">

    </head>
<body>
    <div class="login-container">
        <h2 class="login-title">Connexion Admin</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?rout=admin/login" id="adminLoginForm">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-group">
                <label for="email" class="form-label">Email :</label>
                <input type="email" name="email" id="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="motPasse" class="form-label">Mot de passe :</label>
                <input type="password" name="motPasse" id="motPasse" class="form-input" required>
            </div>
            <div class="form-submit">
                <button type="submit" class="submit-button">Se connecter</button>
            </div>
        </form>
    </div>

       <script src="/Lfarkha-Dahabia-site-e-commerce/public/loginAdmin.js?v=<?= time(); ?>"></script>

</body>
</html>