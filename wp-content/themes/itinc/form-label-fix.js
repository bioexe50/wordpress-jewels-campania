// Form Label Accessibility Fix
document.addEventListener('DOMContentLoaded', function() {
    console.log('Form label fix script loaded');

    // Fix search form label association
    function fixSearchFormLabels() {
        // Find all search forms
        var searchForms = document.querySelectorAll('.search-form, form[role="search"]');

        searchForms.forEach(function(form) {
            var searchInput = form.querySelector('input[type="search"]');
            var label = form.querySelector('label');

            if (searchInput && label) {
                var inputId = searchInput.getAttribute('id');
                var labelFor = label.getAttribute('for');

                console.log('Search input ID:', inputId);
                console.log('Label for:', labelFor);

                // If IDs don't match, fix them
                if (!inputId || !labelFor || inputId !== labelFor) {
                    var newId = 'search-field-' + Date.now();
                    searchInput.setAttribute('id', newId);
                    label.setAttribute('for', newId);
                    console.log('Fixed search form label association with ID:', newId);
                }
            }

            // If no label exists, create one
            if (searchInput && !label) {
                var newLabel = document.createElement('label');
                newLabel.setAttribute('for', searchInput.id || 'search-field-' + Date.now());
                newLabel.innerHTML = '<span class="screen-reader-text">Cerca nel sito:</span>';
                newLabel.style.position = 'absolute';
                newLabel.style.left = '-9999px'; // Screen reader only

                if (!searchInput.id) {
                    searchInput.setAttribute('id', newLabel.getAttribute('for'));
                }

                form.insertBefore(newLabel, searchInput);
                console.log('Created missing search form label');
            }

            // Ensure proper aria attributes
            if (searchInput) {
                if (!searchInput.getAttribute('aria-label') && !searchInput.getAttribute('aria-labelledby')) {
                    searchInput.setAttribute('aria-label', 'Cerca nel sito');
                }
            }
        });
    }

    // Fix any other unlabeled form controls
    function fixUnlabeledFormControls() {
        var formControls = document.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="image"]), select, textarea');

        formControls.forEach(function(control) {
            var hasLabel = false;
            var controlId = control.getAttribute('id');

            // Check for associated label
            if (controlId) {
                var associatedLabel = document.querySelector('label[for="' + controlId + '"]');
                if (associatedLabel) {
                    hasLabel = true;
                }
            }

            // Check if wrapped in label
            var parentLabel = control.closest('label');
            if (parentLabel) {
                hasLabel = true;
            }

            // Check for aria-label or aria-labelledby
            if (control.getAttribute('aria-label') || control.getAttribute('aria-labelledby')) {
                hasLabel = true;
            }

            // Check for title attribute
            if (control.getAttribute('title') && control.getAttribute('title').trim() !== '') {
                hasLabel = true;
            }

            // If no label found, add aria-label based on placeholder or context
            if (!hasLabel) {
                var placeholder = control.getAttribute('placeholder');
                var name = control.getAttribute('name');
                var type = control.getAttribute('type');

                var labelText = '';
                if (placeholder) {
                    labelText = placeholder;
                } else if (name) {
                    labelText = name.replace(/[_-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                } else if (type) {
                    labelText = type.charAt(0).toUpperCase() + type.slice(1) + ' field';
                } else {
                    labelText = 'Form field';
                }

                control.setAttribute('aria-label', labelText);
                console.log('Added aria-label to unlabeled form control:', labelText);
            }
        });
    }

    // Run fixes
    fixSearchFormLabels();
    fixUnlabeledFormControls();

    // Run again after a short delay to catch dynamically loaded content
    setTimeout(function() {
        fixSearchFormLabels();
        fixUnlabeledFormControls();
    }, 1000);

    console.log('Form accessibility fixes applied');
});