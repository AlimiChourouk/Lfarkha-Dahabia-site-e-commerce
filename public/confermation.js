function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    menu.classList.toggle('hidden');
}

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const userButton = event.target.closest('button');
    
    if (!userButton || !userButton.onclick) {
        userMenu.classList.add('hidden');
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const address = document.querySelector('textarea[name="adresse"]').value.trim();
    if (!address) {
        e.preventDefault();
        alert('Veuillez entrer une adresse de livraison.');
        return false;
    }
});

// Add smooth animations
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});