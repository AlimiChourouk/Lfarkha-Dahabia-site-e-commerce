// Fonction globale pour afficher/masquer le menu utilisateur
function toggleUserMenu() {
    const userMenu = document.getElementById('userMenu');
    if (userMenu) {
        // Bascule la classe 'hidden' et ajuste l'affichage en conséquence
        userMenu.classList.toggle('hidden');
        userMenu.style.display = userMenu.classList.contains('hidden') ? 'none' : 'block';
    } else {
        console.warn('Élément #userMenu introuvable dans le DOM.');
    }
}

// Exécute le code une fois le DOM entièrement chargé
document.addEventListener('DOMContentLoaded', function () {

    // Ferme le menu utilisateur si on clique à l'extérieur
    document.addEventListener('click', function (event) {
        const userMenu = document.getElementById('userMenu');
        const userMenuButton = document.querySelector('button[onclick="toggleUserMenu()"]');
        if (userMenu && userMenuButton && !userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
            userMenu.classList.add('hidden');
            userMenu.style.display = 'none';
        }
    });

    // --- MENU BURGER ---

    // Fonction pour afficher/masquer le menu burger en version mobile
    function toggleBurgerMenu() {
        const burgerMenu = document.getElementById('burgerMenu');
        if (burgerMenu) {
            burgerMenu.classList.toggle('active');
        } else {
            console.warn('Élément #burgerMenu introuvable dans le DOM.');
        }
    }

    // Ajoute l'écouteur d'événement au bouton burger
    const burgerButton = document.querySelector('#burgerButton');
    if (burgerButton) {
        burgerButton.addEventListener('click', toggleBurgerMenu);
    }

    // --- AJOUT / RETRAIT DES FAVORIS ---

    // Ajoute un écouteur sur chaque bouton "coeur"
    document.querySelectorAll('.heart-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            // Vérifie si l'utilisateur est connecté
            if (this.hasAttribute('disabled') || !this.dataset.productId) {
                window.location.href = '?rout=connexion'; // Redirige vers la page de connexion
                return;
            }

            const productId = this.dataset.productId;
            const isFavorited = this.classList.contains('favorited');

            try {
                // Envoie une requête AJAX pour ajouter/retirer des favoris
                const response = await fetch('?rout=favoris/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `idProduit=${encodeURIComponent(productId)}`
                });

                if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);

                const data = await response.json();

                if (data.success) {
                    // Met à jour l'apparence du bouton coeur
                    this.classList.toggle('favorited');
                    const heartIcon = this.querySelector('.heart-icon');
                    if (heartIcon) {
                        heartIcon.style.fill = isFavorited ? 'none' : '#ff0000';
                        heartIcon.style.stroke = isFavorited ? '#666' : '#ff0000';
                    }

                    // Change le texte pour les lecteurs d'écran
                    this.setAttribute('aria-label', isFavorited ? 'Ajouter aux favoris' : 'Retirer des favoris');

                    alert(data.message || (isFavorited ? 'Retiré des favoris.' : 'Ajouté aux favoris.'));
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Erreur lors de la mise à jour des favoris.');
                }

            } catch (error) {
                console.error('Erreur lors du changement de favoris :', error);
                alert('Erreur réseau. Veuillez réessayer.');
            }
        });
    });

    // --- AJOUT AU PANIER ---

    // Gère la soumission du formulaire d'ajout au panier
    document.querySelectorAll('.ajout-panier-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(form);

            try {
                // Envoie des données du produit à ajouter au panier
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);

                const data = await response.json(); // On suppose que la réponse est en JSON
                alert(data.message || 'Produit ajouté au panier !');
            } catch (error) {
                console.error('Erreur lors de l\'ajout au panier :', error);
                alert('Erreur lors de l\'ajout au panier.');
            }
        });
    });

    // --- VALIDATION DU FORMULAIRE DE COMMANDE ---

    // Vérifie que l'adresse de livraison est bien renseignée
    const confirmationForm = document.querySelector('form[action="?rout=confirmation"]');
    if (confirmationForm) {
        confirmationForm.addEventListener('submit', function (e) {
            const adresse = document.querySelector('textarea[name="adresse"]');
            if (adresse && !adresse.value.trim()) {
                e.preventDefault();
                alert('Veuillez entrer une adresse de livraison.');
            }
        });
    }

    // --- CACHER LES CHAMPS DE QUANTITÉ (peut être utile si géré dynamiquement) ---

    // Cache tous les champs de quantité pour éviter la saisie manuelle
    document.querySelectorAll('input[name="qte"]').forEach(input => {
        input.style.display = 'none';
    });
});
