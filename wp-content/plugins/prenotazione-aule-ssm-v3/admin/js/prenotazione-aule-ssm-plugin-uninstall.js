/**
 * Script per personalizzare il messaggio di disinstallazione plugin
 *
 * Intercetta il click sul link "Elimina" nella pagina plugins.php
 * e mostra un messaggio personalizzato basato sull'impostazione "Conserva Dati"
 *
 * @since 3.3.9
 * @package WP_Prenotazione_Aule_SSM
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Trova il link "Elimina" del nostro plugin
        const deleteLink = $('tr[data-slug="prenotazione-aule-ssm-v3"] .delete a');

        if (deleteLink.length === 0) {
            return;
        }

        // Intercetta il click sul link "Elimina"
        deleteLink.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const originalUrl = $(this).attr('href');

            // Verifica impostazione "Conserva Dati" via AJAX
            $.ajax({
                url: prenotazione_aule_ssm_uninstall.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_conserva_dati_setting',
                    nonce: prenotazione_aule_ssm_uninstall.nonce
                },
                success: function(response) {
                    if (response.success) {
                        const conservaDati = response.data.conserva_dati;
                        let message;

                        if (conservaDati) {
                            // CONSERVA DATI ATTIVO
                            message = prenotazione_aule_ssm_uninstall.strings.confirm_keep_data;
                        } else {
                            // ELIMINA TUTTO
                            message = prenotazione_aule_ssm_uninstall.strings.confirm_delete_all;
                        }

                        // Mostra conferma personalizzata
                        if (confirm(message)) {
                            window.location.href = originalUrl;
                        }
                    } else {
                        // Fallback al messaggio WordPress standard
                        if (confirm(prenotazione_aule_ssm_uninstall.strings.confirm_default)) {
                            window.location.href = originalUrl;
                        }
                    }
                },
                error: function() {
                    // Fallback in caso di errore AJAX
                    if (confirm(prenotazione_aule_ssm_uninstall.strings.confirm_default)) {
                        window.location.href = originalUrl;
                    }
                }
            });

            return false;
        });
    });

})(jQuery);
