<?php
/**
 * Template per la pagina di personalizzazione grafica
 *
 * @since 3.3.5
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/admin/partials
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Salvataggio impostazioni
if (isset($_POST['pas_customization_nonce']) && wp_verify_nonce($_POST['pas_customization_nonce'], 'pas_save_customization')) {
    $customization = array(
        // Colori
        'primary_color' => sanitize_hex_color($_POST['primary_color'] ?? '#2271b1'),
        'secondary_color' => sanitize_hex_color($_POST['secondary_color'] ?? '#72aee6'),
        'success_color' => sanitize_hex_color($_POST['success_color'] ?? '#28a745'),
        'warning_color' => sanitize_hex_color($_POST['warning_color'] ?? '#ffc107'),
        'danger_color' => sanitize_hex_color($_POST['danger_color'] ?? '#dc3545'),
        'light_color' => sanitize_hex_color($_POST['light_color'] ?? '#f8f9fa'),
        'dark_color' => sanitize_hex_color($_POST['dark_color'] ?? '#1d2327'),
        'border_color' => sanitize_hex_color($_POST['border_color'] ?? '#ddd'),
    );

    update_option('prenotazione_aule_ssm_customization', $customization);

    echo '<div class="notice notice-success is-dismissible"><p><strong>' . __('Personalizzazione salvata con successo!', 'prenotazione-aule-ssm') . '</strong></p></div>';

    // Aggiorna variabile per mostrare i nuovi valori
    $customization = $customization;
}

// Reset a valori default
if (isset($_POST['pas_reset_nonce']) && wp_verify_nonce($_POST['pas_reset_nonce'], 'pas_reset_customization')) {
    delete_option('prenotazione_aule_ssm_customization');
    echo '<div class="notice notice-success is-dismissible"><p><strong>' . __('Personalizzazione ripristinata ai valori predefiniti!', 'prenotazione-aule-ssm') . '</strong></p></div>';
    // Ricarica defaults
    $customization = array(
        'primary_color' => '#2271b1',
        'secondary_color' => '#72aee6',
        'success_color' => '#28a745',
        'warning_color' => '#ffc107',
        'danger_color' => '#dc3545',
        'light_color' => '#f8f9fa',
        'dark_color' => '#1d2327',
        'border_color' => '#ddd',
    );
}
?>

<div class="wrap">
    <h1><?php _e('Personalizzazione Grafica', 'prenotazione-aule-ssm'); ?></h1>
    <p class="description">
        <?php _e('Personalizza i colori del plugin. Il sistema è completamente isolato e non subirà interferenze dal tema WordPress attivo.', 'prenotazione-aule-ssm'); ?>
    </p>

    <hr class="wp-header-end">

    <!-- Box info isolamento CSS -->
    <div class="notice notice-info" style="margin-top: 20px;">
        <p><strong><?php _e('Protezione CSS Attiva', 'prenotazione-aule-ssm'); ?></strong></p>
        <p><?php _e('Tutti gli stili del plugin utilizzano namespace univoci (.pas-*) e specificity elevata per garantire che i temi WordPress non interferiscano con l\'aspetto del plugin.', 'prenotazione-aule-ssm'); ?></p>
    </div>

    <div class="pas-customization-wrapper" style="display: flex; gap: 30px; margin-top: 30px;">
        <!-- Pannello impostazioni (70%) -->
        <div style="flex: 0 0 65%; max-width: 800px;">
            <form method="post" action="" id="pas-customization-form">
                <?php wp_nonce_field('pas_save_customization', 'pas_customization_nonce'); ?>

                <!-- Sezione Colori -->
                <div class="postbox" style="margin-bottom: 20px;">
                    <div class="postbox-header">
                        <h2 class="hndle"><?php _e('Colori', 'prenotazione-aule-ssm'); ?></h2>
                    </div>
                    <div class="inside">
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <label for="primary_color"><?php _e('Colore Primario', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="primary_color"
                                               name="primary_color"
                                               value="<?php echo esc_attr($customization['primary_color']); ?>"
                                               class="pas-color-input"
                                               style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                                        <code style="margin-left: 10px; padding: 5px 10px; background: #f0f0f1; border-radius: 3px;"><?php echo esc_attr($customization['primary_color']); ?></code>
                                        <p class="description"><?php _e('Usato per bottoni principali e link', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="secondary_color"><?php _e('Colore Secondario', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="secondary_color"
                                               name="secondary_color"
                                               value="<?php echo esc_attr($customization['secondary_color']); ?>"
                                               class="pas-color-input" style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                               >
                                        <p class="description"><?php _e('Usato per bottoni secondari e accenti', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="success_color"><?php _e('Colore Successo', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="success_color"
                                               name="success_color"
                                               value="<?php echo esc_attr($customization['success_color']); ?>"
                                               class="pas-color-input" style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                               >
                                        <p class="description"><?php _e('Messaggi di successo e conferme', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="warning_color"><?php _e('Colore Avviso', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="warning_color"
                                               name="warning_color"
                                               value="<?php echo esc_attr($customization['warning_color']); ?>"
                                               class="pas-color-input" style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                               >
                                        <p class="description"><?php _e('Avvisi e attenzioni', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="danger_color"><?php _e('Colore Errore', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="danger_color"
                                               name="danger_color"
                                               value="<?php echo esc_attr($customization['danger_color']); ?>"
                                               class="pas-color-input" style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                               >
                                        <p class="description"><?php _e('Errori e cancellazioni', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="light_color"><?php _e('Colore Chiaro', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="light_color"
                                               name="light_color"
                                               value="<?php echo esc_attr($customization['light_color']); ?>"
                                               class="pas-color-input" style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                               >
                                        <p class="description"><?php _e('Sfondi e aree chiare', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="dark_color"><?php _e('Colore Scuro', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="dark_color"
                                               name="dark_color"
                                               value="<?php echo esc_attr($customization['dark_color']); ?>"
                                               class="pas-color-input" style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                               >
                                        <p class="description"><?php _e('Testi e elementi scuri', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="border_color"><?php _e('Colore Bordi', 'prenotazione-aule-ssm'); ?></label>
                                    </th>
                                    <td>
                                        <input type="color"
                                               id="border_color"
                                               name="border_color"
                                               value="<?php echo esc_attr($customization['border_color']); ?>"
                                               class="pas-color-input" style="width: 100px; height: 40px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                               >
                                        <p class="description"><?php _e('Bordi di card, input e separatori', 'prenotazione-aule-ssm'); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Azioni -->
                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <span class="dashicons dashicons-saved" style="margin-top: 3px;"></span>
                        <?php _e('Salva Modifiche', 'prenotazione-aule-ssm'); ?>
                    </button>
                </p>
            </form>

            <!-- Form Reset separato -->
            <form method="post" action="" id="pas-reset-form" style="margin-top: 10px;" onsubmit="return confirm('<?php echo esc_js(__('Sei sicuro di voler ripristinare i valori predefiniti?', 'prenotazione-aule-ssm')); ?>');">
                <?php wp_nonce_field('pas_reset_customization', 'pas_reset_nonce'); ?>
                <button type="submit" class="button button-secondary">
                    <span class="dashicons dashicons-image-rotate" style="margin-top: 3px;"></span>
                    <?php _e('Ripristina Valori Predefiniti', 'prenotazione-aule-ssm'); ?>
                </button>
            </form>
        </div>

        <!-- Pannello Anteprima (30%) -->
        <div style="flex: 0 0 30%; position: sticky; top: 32px; height: fit-content;">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Anteprima', 'prenotazione-aule-ssm'); ?></h2>
                </div>
                <div class="inside" id="pas-preview-container">
                    <div class="prenotazione-aule-ssm-wrapper" id="pas-live-preview" style="padding: 15px;">
                        <!-- Bottoni -->
                        <div style="margin-bottom: 15px;">
                            <button class="pas-btn pas-btn-primary" style="margin-right: 8px;">Primario</button>
                            <button class="pas-btn pas-btn-secondary">Secondario</button>
                        </div>

                        <!-- Alert Success -->
                        <div class="pas-alert pas-alert-success" style="margin-bottom: 10px; padding: 12px; border-radius: 6px;">
                            ✓ Messaggio di successo
                        </div>

                        <!-- Alert Warning -->
                        <div class="pas-alert pas-alert-warning" style="margin-bottom: 10px; padding: 12px; border-radius: 6px;">
                            ⚠ Messaggio di avviso
                        </div>

                        <!-- Alert Danger -->
                        <div class="pas-alert pas-alert-danger" style="margin-bottom: 10px; padding: 12px; border-radius: 6px;">
                            ✕ Messaggio di errore
                        </div>

                        <!-- Card -->
                        <div class="aula-card" style="border: 1px solid; padding: 15px; border-radius: 6px; margin-top: 15px;">
                            <h3 style="margin-top: 0;">Card Esempio</h3>
                            <p style="margin: 0;">Questa è un'anteprima degli stili applicati</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info tecnica -->
            <div class="notice notice-warning" style="margin-top: 20px; padding: 10px;">
                <p style="margin: 0;"><strong>ℹ️ Nota:</strong> Le modifiche saranno visibili dopo aver salvato e ricaricato una pagina del frontend.</p>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript per Live Preview -->
<script type="text/javascript">
jQuery(document).ready(function($) {
    // Event listener per tutti i color inputs
    $('.pas-color-input').on('input change', function() {
        updateLivePreview();
        updateHexCode($(this));
    });

    // Aggiorna il codice hex visibile
    function updateHexCode($input) {
        var colorId = $input.attr('id');
        var hexValue = $input.val().toUpperCase();
        $input.next('code').text(hexValue);
    }

    // Aggiorna anteprima live
    function updateLivePreview() {
        var colors = {
            primary: $('#primary_color').val() || '#2271b1',
            secondary: $('#secondary_color').val() || '#72aee6',
            success: $('#success_color').val() || '#28a745',
            warning: $('#warning_color').val() || '#ffc107',
            danger: $('#danger_color').val() || '#dc3545',
            light: $('#light_color').val() || '#f8f9fa',
            dark: $('#dark_color').val() || '#1d2327',
            border: $('#border_color').val() || '#dddddd'
        };

        // Applica stili inline per anteprima
        var previewCSS = `
            <style id="pas-preview-styles">
                #pas-live-preview .pas-btn-primary {
                    background-color: ${colors.primary} !important;
                    color: white !important;
                    border: none !important;
                    padding: 10px 20px !important;
                    border-radius: 6px !important;
                    cursor: pointer !important;
                }
                #pas-live-preview .pas-btn-secondary {
                    background-color: ${colors.secondary} !important;
                    color: white !important;
                    border: none !important;
                    padding: 10px 20px !important;
                    border-radius: 6px !important;
                    cursor: pointer !important;
                }
                #pas-live-preview .pas-alert-success {
                    background-color: ${hexToRGBA(colors.success, 0.1)} !important;
                    color: ${colors.success} !important;
                    border-left: 4px solid ${colors.success} !important;
                }
                #pas-live-preview .pas-alert-warning {
                    background-color: ${hexToRGBA(colors.warning, 0.1)} !important;
                    color: #856404 !important;
                    border-left: 4px solid ${colors.warning} !important;
                }
                #pas-live-preview .pas-alert-danger {
                    background-color: ${hexToRGBA(colors.danger, 0.1)} !important;
                    color: ${colors.danger} !important;
                    border-left: 4px solid ${colors.danger} !important;
                }
                #pas-live-preview .aula-card {
                    border-color: ${colors.border} !important;
                    background-color: ${colors.light} !important;
                }
            </style>
        `;

        // Rimuovi stile precedente e applica nuovo
        $('#pas-preview-styles').remove();
        $('head').append(previewCSS);
    }

    // Converti HEX in RGBA
    function hexToRGBA(hex, alpha) {
        var r = parseInt(hex.slice(1, 3), 16);
        var g = parseInt(hex.slice(3, 5), 16);
        var b = parseInt(hex.slice(5, 7), 16);
        return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
    }

    // Aggiorna anteprima al caricamento
    updateLivePreview();
});
</script>
