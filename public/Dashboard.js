// Gestion du menu hamburger
const menuToggle = document.getElementById('menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');
menuToggle.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
    mobileMenu.classList.toggle('animate-slide-in');
});

// Ajouter des interactions
document.querySelectorAll('.order-item, .favorite-item').forEach(item => {
    item.addEventListener('click', function() {
        this.style.transform = 'scale(0.98)';
        setTimeout(() => {
            this.style.transform = '';
        }, 150);
    });
});

// Animation au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationDelay = '0.2s';
            entry.target.style.animationFillMode = 'both';
        }
    });
}, observerOptions);

document.querySelectorAll('.card, .stat-card').forEach(card => {
    observer.observe(card);
});

// Effet de particules au survol des stats
document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.background = `linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(128, 200, 131, 0.1))`;
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.background = `var(--card-bg)`;
    });
});