// script.js
// Define toggleUserMenu globally
function toggleUserMenu() {
    const userMenu = document.getElementById('userMenu');
    if (userMenu) {
        userMenu.classList.toggle('hidden');
        userMenu.style.display = userMenu.classList.contains('hidden') ? 'none' : 'block';
    } else {
        console.warn('User menu element (#userMenu) not found in the DOM.');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Close user menu when clicking outside
    document.addEventListener('click', function (event) {
        const userMenu = document.getElementById('userMenu');
        const userMenuButton = document.querySelector('button[onclick="toggleUserMenu()"]');
        if (userMenu && userMenuButton && !userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
            userMenu.classList.add('hidden');
            userMenu.style.display = 'none';
        }
    });

    // Burger Menu Toggle (for responsive design)
    function toggleBurgerMenu() {
        const burgerMenu = document.getElementById('burgerMenu');
        if (burgerMenu) {
            burgerMenu.classList.toggle('active');
        } else {
            console.warn('Burger menu element (#burgerMenu) not found in the DOM.');
        }
    }

    // Add event listener for burger menu toggle
    const burgerButton = document.querySelector('#burgerButton'); // Adjust selector as needed
    if (burgerButton) {
        burgerButton.addEventListener('click', toggleBurgerMenu);
    }

    // Favorites Toggle
    document.querySelectorAll('.heart-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            // Check if user is logged in
            if (this.hasAttribute('disabled') || !this.dataset.productId) {
                window.location.href = '?rout=connexion';
                return;
            }

            const productId = this.dataset.productId;
            const isFavorited = this.classList.contains('favorited');

            try {
                const response = await fetch('?rout=favoris/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `idProduit=${encodeURIComponent(productId)}`
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.classList.toggle('favorited');
                    const heartIcon = this.querySelector('.heart-icon');
                    if (heartIcon) {
                        heartIcon.style.fill = isFavorited ? 'none' : '#ff0000';
                        heartIcon.style.stroke = isFavorited ? '#666' : '#ff0000';
                    }
                    this.setAttribute('aria-label', isFavorited ? 'Ajouter aux favoris' : 'Retirer des favoris');
                    alert(data.message || (isFavorited ? 'Retiré des favoris.' : 'Ajouté aux favoris.'));
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Erreur lors de la mise à jour des favoris.');
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
                alert('Erreur réseau. Veuillez réessayer.');
            }
        });
    });

    // Add to Cart
    document.querySelectorAll('.ajout-panier-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json(); // Assuming backend returns JSON
                alert(data.message || 'Produit ajouté au panier !');
            } catch (error) {
                console.error('Erreur lors de l\'ajout au panier :', error);
                alert('Erreur lors de l\'ajout au panier.');
            }
        });
    });

    // Validate Address on Confirmation Form
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

    // Hide Quantity Inputs
    document.querySelectorAll('input[name="qte"]').forEach(input => {
        input.style.display = 'none';
    });
});
