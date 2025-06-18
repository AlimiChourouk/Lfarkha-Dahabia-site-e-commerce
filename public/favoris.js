
       function toggleUserMenu() {
    const userMenu = document.getElementById('userMenu');
    userMenu.classList.toggle('hidden');
}

  document.querySelectorAll('.favorite-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const productId = this.dataset.productId;
                const isFavorited = this.classList.contains('favorited');

                try {
                    const response = await fetch('index.php?rout=favoris/toggle', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `idProduit=${productId}`
                    });
                    const result = await response.json();

                    if (result.success) {
                        this.classList.toggle('favorited');
                        if (!isFavorited) {
                            this.closest('.product-card').remove();
                        }
                    } else {
                        alert(result.message || 'Erreur lors de la mise à jour des favoris.');
                    }
                } catch (error) {
                    alert('Erreur réseau. Veuillez réessayer.');
                }
            });
        });
 