    document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const motPasse = document.getElementById('motPasse').value;

        if (!email || !motPasse) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs.');
        }
    });