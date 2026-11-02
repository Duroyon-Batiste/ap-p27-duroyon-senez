
// Initialisation du menu utilisateur
function initUserMenu() {
    const toggle = document.getElementById('userMenuToggle');
    const dropdown = document.getElementById('userDropdown');

    if (!toggle || !dropdown) {
        return;
    }

    // Éviter d'ajouter plusieurs fois les événements
    if (toggle.dataset.initialized === 'true') {
        return;
    }

    toggle.dataset.initialized = 'true';

    // Ouvrir ou fermer le menu au clic
    toggle.addEventListener('click', (event) => {
        event.stopPropagation();

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

    // Fermer le menu avec Échap
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            dropdown.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
}

// Chargement classique
document.addEventListener('DOMContentLoaded', initUserMenu);

// Navigation avec Symfony UX Turbo
document.addEventListener('turbo:load', initUserMenu);
