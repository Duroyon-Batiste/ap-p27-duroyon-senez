// Afficher le menu utilisateur au clic sur la photo de profil
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('userMenuToggle');
    const dropdown = document.getElementById('userDropdown');

    if (!toggle || !dropdown) {
        return;
    }

    // Ouvrir ou fermer le menu au clic
    toggle.addEventListener('click', () => {
        const isOpen = !dropdown.hidden;

        dropdown.hidden = isOpen;
        toggle.setAttribute('aria-expanded', String(!isOpen));
    });

    // Fermer le menu si l'utilisateur clique ailleurs
    document.addEventListener('click', (event) => {
        if (!toggle.contains(event.target)
            && !dropdown.contains(event.target)) {
            dropdown.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Fermer le menu avec la touche Échap
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            dropdown.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
});