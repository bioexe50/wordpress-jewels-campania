// Accessibility JavaScript Fix
document.addEventListener('DOMContentLoaded', function() {
    // Fix menu toggle button accessibility
    var menuToggle = document.getElementById('menu-toggle');
    if (menuToggle && !menuToggle.getAttribute('aria-label')) {
        menuToggle.setAttribute('aria-label', 'Apri menu di navigazione');
        menuToggle.setAttribute('aria-expanded', 'false');

        // Update aria-expanded when menu is toggled
        menuToggle.addEventListener('click', function() {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
        });
    }

    console.log('Accessibility fixes applied');
});