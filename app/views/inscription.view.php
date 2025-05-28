<?php
require_once __DIR__ . '/../../config/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Lfarkha Dahabia</title>

</head>
<body>
    <section class="container">
        <h2 class="section-title">Inscription</h2>
        <p class="text-center">Ou <a href="?rout=connexion">connectez-vous</a> à votre compte existant</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="?rout=inscription/register" class="form-container">
            <div class="form-group">
                <div>
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
                    <?php if (isset($errors['prenom'])): ?>
                        <p><?= htmlspecialchars($errors['prenom']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                    <?php if (isset($errors['nom'])): ?>
                        <p><?= htmlspecialchars($errors['nom']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <p><?= htmlspecialchars($errors['email']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="tel">Téléphone</label>
                    <input type="tel" id="tel" name="tel" value="<?= htmlspecialchars($_POST['tel'] ?? '') ?>" required>
                    <?php if (isset($errors['tel'])): ?>
                        <p><?= htmlspecialchars($errors['tel']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="motPasse">Mot de passe</label>
                    <input type="password" id="motPasse" name="motPasse" required>
                    <?php if (isset($errors['motPasse'])): ?>
                        <p><?= htmlspecialchars($errors['motPasse']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="submit-button">S'inscrire</button>
            </div>
        </form>
    </section>

    <script src="../public/scripte.js"></script>
</body>
</html>