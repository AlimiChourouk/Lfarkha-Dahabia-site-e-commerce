function toggleUserMenu() {
    const menu = document.getElementById("userMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Ferme le menu si l'utilisateur clique en dehors
document.addEventListener('click', function(event) {
    const menu = document.getElementById("userMenu");
    const button = document.querySelector("button[onclick='toggleUserMenu()']");
    
    // Vérifie si le clic est en dehors du menu et du bouton
    if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.style.display = "none";
    }
});
