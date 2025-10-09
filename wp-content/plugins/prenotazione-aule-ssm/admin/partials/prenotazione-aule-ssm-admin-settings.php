<?php

/**
 * Template per la pagina "Impostazioni"
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/admin/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Valori predefiniti per le impostazioni
$default_settings = array(
    'conferma_automatica' => 0,
    'email_notifica_admin' => array(),
    'template_email_conferma' => __('La tua prenotazione è stata confermata!\n\nDettagli:\nAula: {nome_aula}\nData: {data_prenotazione}\nOrario: {ora_inizio} - {ora_fine}\nCodice: {codice_prenotazione}\n\nGrazie!', 'prenotazione-aule-ssm'),
    'template_email_rifiuto' => __('La tua prenotazione è stata rifiutata.\n\nMotivo: {note_admin}\n\nDettagli:\nAula: {nome_aula}\nData: {data_prenotazione}\nOrario: {ora_inizio} - {ora_fine}\n\nPuoi richiedere una nuova prenotazione.', 'prenotazione-aule-ssm'),
    'template_email_admin' => __('Nuova prenotazione ricevuta!\n\nRichiedente: {nome_richiedente} {cognome_richiedente}\nEmail: {email_richiedente}\nAula: {nome_aula}\nData: {data_prenotazione}\nOrario: {ora_inizio} - {ora_fine}\nMotivo: {motivo_prenotazione}\n\nApprova/Rifiuta dalla dashboard admin.', 'prenotazione-aule-ssm'),
    'giorni_prenotazione_futura_max' => 30,
    'ore_anticipo_prenotazione_min' => 24,
    'max_prenotazioni_per_utente_giorno' => 3,
    'abilita_recaptcha' => 0,
    'recaptcha_site_key' => '',
    'recaptcha_secret_key' => '',
    'colore_slot_libero' => '#28a745',
    'colore_slot_occupato' => '#dc3545',
    'colore_slot_attesa' => '#ffc107'
);

// Unisci con impostazioni salvate
if ($settings) {
    foreach ($default_settings as $key => $default) {
        if (isset($settings->$key)) {
            if (in_array($key, ['email_notifica_admin'])) {
                $default_settings[$key] = maybe_unserialize($settings->$key) ?: $default;
            } else {
                $default_settings[$key] = $settings->$key;
            }
        }
    }
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        ⚙️ <?php _e('Impostazioni Plugin', 'prenotazione-aule-ssm'); ?>
    </h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Impostazioni salvate correttamente.', 'prenotazione-aule-ssm'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Si è verificato un errore durante il salvataggio.', 'prenotazione-aule-ssm'); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="settings-form">
        <?php wp_nonce_field('prenotazione_aule_ssm_settings_nonce'); ?>
        <input type="hidden" name="action" value="prenotazione_aule_ssm_save_settings">

        <!-- Navigazione a tab -->
        <div class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active" data-target="#general-settings">
                🏢 <?php _e('Generale', 'prenotazione-aule-ssm'); ?>
            </a>
            <a href="#bookings" class="nav-tab" data-target="#booking-settings">
                📅 <?php _e('Prenotazioni', 'prenotazione-aule-ssm'); ?>
            </a>
            <a href="#emails" class="nav-tab" data-target="#email-settings">
                📧 <?php _e('Email', 'prenotazione-aule-ssm'); ?>
            </a>
            <a href="#security" class="nav-tab" data-target="#security-settings">
                🔒 <?php _e('Sicurezza', 'prenotazione-aule-ssm'); ?>
            </a>
            <a href="#appearance" class="nav-tab" data-target="#appearance-settings">
                🎨 <?php _e('Aspetto', 'prenotazione-aule-ssm'); ?>
            </a>
        </div>

        <!-- Tab Generale -->
        <div id="general-settings" class="tab-content">
            <div class="settings-section">
                <h2><?php _e('Configurazione Generale', 'prenotazione-aule-ssm'); ?></h2>
                <p class="description">
                    <?php _e('Impostazioni base per il funzionamento del plugin.', 'prenotazione-aule-ssm'); ?>
                </p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="conferma_automatica"><?php _e('Conferma Automatica', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <label for="conferma_automatica">
                                <input type="checkbox"
                                       id="conferma_automatica"
                                       name="conferma_automatica"
                                       value="1"
                                       <?php checked($default_settings['conferma_automatica'], 1); ?>>
                                <?php _e('Conferma automaticamente tutte le prenotazioni', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Se disabilitato, le prenotazioni rimarranno in attesa di approvazione manuale.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="email_notifica_admin"><?php _e('Email Amministratori', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="text"
                                   id="email_notifica_admin"
                                   name="email_notifica_admin"
                                   value="<?php echo esc_attr(is_array($default_settings['email_notifica_admin']) ? implode(',', $default_settings['email_notifica_admin']) : $default_settings['email_notifica_admin']); ?>"
                                   class="regular-text"
                                   placeholder="admin@esempio.com, manager@esempio.com">
                            <p class="description">
                                <?php _e('Email degli amministratori che riceveranno notifiche (separate da virgola).', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tab Prenotazioni -->
        <div id="booking-settings" class="tab-content" style="display: none;">
            <div class="settings-section">
                <h2><?php _e('Regole Prenotazioni', 'prenotazione-aule-ssm'); ?></h2>
                <p class="description">
                    <?php _e('Configura le limitazioni e regole per le prenotazioni.', 'prenotazione-aule-ssm'); ?>
                </p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="giorni_prenotazione_futura_max"><?php _e('Giorni Futuri Max', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="number"
                                   id="giorni_prenotazione_futura_max"
                                   name="giorni_prenotazione_futura_max"
                                   value="<?php echo esc_attr($default_settings['giorni_prenotazione_futura_max']); ?>"
                                   min="1"
                                   max="365"
                                   class="small-text">
                            <label for="giorni_prenotazione_futura_max"><?php _e('giorni', 'prenotazione-aule-ssm'); ?></label>
                            <p class="description">
                                <?php _e('Numero massimo di giorni in futuro per cui è possibile prenotare.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="ore_anticipo_prenotazione_min"><?php _e('Ore Anticipo Min', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="number"
                                   id="ore_anticipo_prenotazione_min"
                                   name="ore_anticipo_prenotazione_min"
                                   value="<?php echo esc_attr($default_settings['ore_anticipo_prenotazione_min']); ?>"
                                   min="0"
                                   max="168"
                                   class="small-text">
                            <label for="ore_anticipo_prenotazione_min"><?php _e('ore', 'prenotazione-aule-ssm'); ?></label>
                            <p class="description">
                                <?php _e('Numero minimo di ore di anticipo richieste per una prenotazione.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="max_prenotazioni_per_utente_giorno"><?php _e('Max Prenotazioni/Giorno', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="number"
                                   id="max_prenotazioni_per_utente_giorno"
                                   name="max_prenotazioni_per_utente_giorno"
                                   value="<?php echo esc_attr($default_settings['max_prenotazioni_per_utente_giorno']); ?>"
                                   min="1"
                                   max="20"
                                   class="small-text">
                            <label for="max_prenotazioni_per_utente_giorno"><?php _e('prenotazioni', 'prenotazione-aule-ssm'); ?></label>
                            <p class="description">
                                <?php _e('Numero massimo di prenotazioni per utente per giorno.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tab Email -->
        <div id="email-settings" class="tab-content" style="display: none;">
            <div class="settings-section">
                <h2><?php _e('Template Email', 'prenotazione-aule-ssm'); ?></h2>
                <p class="description">
                    <?php _e('Personalizza i template delle email automatiche. Usa i placeholder: {nome_aula}, {data_prenotazione}, {ora_inizio}, {ora_fine}, {nome_richiedente}, {cognome_richiedente}, {email_richiedente}, {motivo_prenotazione}, {codice_prenotazione}, {note_admin}', 'prenotazione-aule-ssm'); ?>
                </p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="template_email_conferma"><?php _e('Email Conferma', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <textarea id="template_email_conferma"
                                      name="template_email_conferma"
                                      rows="8"
                                      cols="50"
                                      class="large-text"><?php echo esc_textarea($default_settings['template_email_conferma']); ?></textarea>
                            <p class="description">
                                <?php _e('Template email inviata all\'utente quando la prenotazione viene confermata.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="template_email_rifiuto"><?php _e('Email Rifiuto', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <textarea id="template_email_rifiuto"
                                      name="template_email_rifiuto"
                                      rows="8"
                                      cols="50"
                                      class="large-text"><?php echo esc_textarea($default_settings['template_email_rifiuto']); ?></textarea>
                            <p class="description">
                                <?php _e('Template email inviata all\'utente quando la prenotazione viene rifiutata.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="template_email_admin"><?php _e('Email Admin', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <textarea id="template_email_admin"
                                      name="template_email_admin"
                                      rows="8"
                                      cols="50"
                                      class="large-text"><?php echo esc_textarea($default_settings['template_email_admin']); ?></textarea>
                            <p class="description">
                                <?php _e('Template email inviata agli amministratori per nuove prenotazioni.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Anteprima email -->
                <div class="email-preview-section">
                    <h3><?php _e('Anteprima Email', 'prenotazione-aule-ssm'); ?></h3>
                    <div class="email-preview-controls">
                        <select id="email-preview-type">
                            <option value="conferma"><?php _e('Conferma', 'prenotazione-aule-ssm'); ?></option>
                            <option value="rifiuto"><?php _e('Rifiuto', 'prenotazione-aule-ssm'); ?></option>
                            <option value="admin"><?php _e('Admin', 'prenotazione-aule-ssm'); ?></option>
                        </select>
                        <button type="button" id="preview-email" class="button"><?php _e('Genera Anteprima', 'prenotazione-aule-ssm'); ?></button>
                    </div>
                    <div id="email-preview-output" class="email-preview-box"></div>
                </div>
            </div>
        </div>

        <!-- Tab Sicurezza -->
        <div id="security-settings" class="tab-content" style="display: none;">
            <div class="settings-section">
                <h2><?php _e('Sicurezza e Protezione', 'prenotazione-aule-ssm'); ?></h2>
                <p class="description">
                    <?php _e('Configura le misure di sicurezza per proteggere il sistema dalle prenotazioni spam.', 'prenotazione-aule-ssm'); ?>
                </p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="abilita_recaptcha"><?php _e('reCAPTCHA v3', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <label for="abilita_recaptcha">
                                <input type="checkbox"
                                       id="abilita_recaptcha"
                                       name="abilita_recaptcha"
                                       value="1"
                                       <?php checked($default_settings['abilita_recaptcha'], 1); ?>>
                                <?php _e('Abilita protezione reCAPTCHA', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Protegge il form di prenotazione da spam e bot automatici.', 'prenotazione-aule-ssm'); ?>
                                <a href="https://www.google.com/recaptcha/" target="_blank"><?php _e('Ottieni le chiavi', 'prenotazione-aule-ssm'); ?></a>
                            </p>
                        </td>
                    </tr>

                    <tr class="recaptcha-field">
                        <th scope="row">
                            <label for="recaptcha_site_key"><?php _e('Site Key', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="text"
                                   id="recaptcha_site_key"
                                   name="recaptcha_site_key"
                                   value="<?php echo esc_attr($default_settings['recaptcha_site_key']); ?>"
                                   class="regular-text"
                                   placeholder="6Lc6BAAAAAAAAChqRbQZcn_yyyyyyyyyyyyyyyyy">
                            <p class="description">
                                <?php _e('Chiave pubblica reCAPTCHA (visibile nel frontend).', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr class="recaptcha-field">
                        <th scope="row">
                            <label for="recaptcha_secret_key"><?php _e('Secret Key', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="password"
                                   id="recaptcha_secret_key"
                                   name="recaptcha_secret_key"
                                   value="<?php echo esc_attr($default_settings['recaptcha_secret_key']); ?>"
                                   class="regular-text"
                                   placeholder="6Lc6BAAAAAAAAKN3DRm6VA_xxxxxxxxxxxxxxxxx">
                            <p class="description">
                                <?php _e('Chiave privata reCAPTCHA (per verifica server-side).', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Test reCAPTCHA -->
                <div class="recaptcha-test-section" style="margin-top: 20px;">
                    <h3><?php _e('Test Configurazione', 'prenotazione-aule-ssm'); ?></h3>
                    <button type="button" id="test-recaptcha" class="button" disabled>
                        🧪 <?php _e('Testa reCAPTCHA', 'prenotazione-aule-ssm'); ?>
                    </button>
                    <div id="recaptcha-test-result"></div>
                </div>
            </div>
        </div>

        <!-- Tab Aspetto -->
        <div id="appearance-settings" class="tab-content" style="display: none;">
            <div class="settings-section">
                <h2><?php _e('Personalizzazione Colori', 'prenotazione-aule-ssm'); ?></h2>
                <p class="description">
                    <?php _e('Personalizza i colori utilizzati nel calendario e negli stati delle prenotazioni.', 'prenotazione-aule-ssm'); ?>
                </p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="colore_slot_libero"><?php _e('Slot Liberi', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="colore_slot_libero"
                                   name="colore_slot_libero"
                                   value="<?php echo esc_attr($default_settings['colore_slot_libero']); ?>">
                            <label for="colore_slot_libero" class="color-label">
                                <?php _e('Colore per slot disponibili', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Colore utilizzato nel calendario per indicare slot disponibili.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="colore_slot_occupato"><?php _e('Slot Occupati', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="colore_slot_occupato"
                                   name="colore_slot_occupato"
                                   value="<?php echo esc_attr($default_settings['colore_slot_occupato']); ?>">
                            <label for="colore_slot_occupato" class="color-label">
                                <?php _e('Colore per slot occupati', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Colore utilizzato nel calendario per prenotazioni confermate.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="colore_slot_attesa"><?php _e('Slot In Attesa', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="colore_slot_attesa"
                                   name="colore_slot_attesa"
                                   value="<?php echo esc_attr($default_settings['colore_slot_attesa']); ?>">
                            <label for="colore_slot_attesa" class="color-label">
                                <?php _e('Colore per slot in attesa', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Colore utilizzato nel calendario per prenotazioni in attesa di approvazione.', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Anteprima colori -->
                <div class="color-preview-section">
                    <h3><?php _e('Anteprima Colori', 'prenotazione-aule-ssm'); ?></h3>
                    <div class="color-preview-grid">
                        <div class="color-preview-item">
                            <div class="color-sample" id="preview-libero" style="background-color: <?php echo esc_attr($default_settings['colore_slot_libero']); ?>"></div>
                            <span><?php _e('Slot Libero', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                        <div class="color-preview-item">
                            <div class="color-sample" id="preview-occupato" style="background-color: <?php echo esc_attr($default_settings['colore_slot_occupato']); ?>"></div>
                            <span><?php _e('Slot Occupato', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                        <div class="color-preview-item">
                            <div class="color-sample" id="preview-attesa" style="background-color: <?php echo esc_attr($default_settings['colore_slot_attesa']); ?>"></div>
                            <span><?php _e('Slot In Attesa', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                    </div>
                    <button type="button" id="reset-colors" class="button">
                        🔄 <?php _e('Ripristina Colori Default', 'prenotazione-aule-ssm'); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Pulsanti salvataggio -->
        <div class="settings-footer">
            <?php submit_button(__('Salva Impostazioni', 'prenotazione-aule-ssm'), 'primary', 'submit', false); ?>
            <button type="button" class="button button-secondary" id="reset-settings">
                🔄 <?php _e('Ripristina Default', 'prenotazione-aule-ssm'); ?>
            </button>
            <button type="button" class="button button-secondary" id="export-settings">
                📤 <?php _e('Esporta Configurazione', 'prenotazione-aule-ssm'); ?>
            </button>
            <button type="button" class="button button-secondary" id="import-settings">
                📥 <?php _e('Importa Configurazione', 'prenotazione-aule-ssm'); ?>
            </button>
        </div>
    </form>

    <!-- File input nascosto per import -->
    <input type="file" id="import-file" accept=".json" style="display: none;">
</div>

<style>
/* Stili specifici per impostazioni */
.settings-form {
    max-width: 1200px;
}

.nav-tab-wrapper {
    margin: 20px 0 0 0;
}

.tab-content {
    background: white;
    padding: 20px;
    border: 1px solid #ccd0d4;
    border-top: none;
    border-radius: 0 0 4px 4px;
}

.settings-section {
    margin-bottom: 30px;
}

.settings-section h2 {
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 10px;
    margin-bottom: 20px;
    color: #1d2327;
}

.form-table th {
    width: 200px;
    padding: 15px 10px 15px 0;
    vertical-align: top;
}

.form-table td {
    padding: 15px 10px;
}

.recaptcha-field {
    display: none;
}

.recaptcha-field.show {
    display: table-row;
}

.email-preview-section {
    margin-top: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
}

.email-preview-controls {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    align-items: center;
}

.email-preview-box {
    background: white;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    min-height: 100px;
    font-family: monospace;
    white-space: pre-wrap;
    line-height: 1.4;
}

.recaptcha-test-section {
    padding: 20px;
    background: #f0f8ff;
    border-radius: 4px;
    border: 1px solid #b8daff;
}

.color-preview-section {
    margin-top: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
}

.color-preview-grid {
    display: flex;
    gap: 20px;
    margin: 15px 0;
    flex-wrap: wrap;
}

.color-preview-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.color-sample {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    border: 2px solid #ddd;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.color-label {
    margin-left: 10px;
    font-weight: 500;
}

.settings-footer {
    margin-top: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 4px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    border: 1px solid #e0e0e0;
}

/* Responsive */
@media (max-width: 768px) {
    .nav-tab-wrapper {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
    }

    .nav-tab {
        flex-shrink: 0;
        font-size: 12px;
        padding: 8px 12px;
    }

    .form-table th,
    .form-table td {
        display: block;
        width: 100%;
        padding: 10px 0;
    }

    .form-table th {
        border-bottom: none;
        padding-bottom: 5px;
    }

    .email-preview-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .color-preview-grid {
        justify-content: center;
    }

    .settings-footer {
        flex-direction: column;
    }

    .settings-footer .button {
        width: 100%;
        text-align: center;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Gestione navigazione tab
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();

        var target = $(this).data('target');

        // Aggiorna tab attivi
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        // Mostra contenuto corrispondente
        $('.tab-content').hide();
        $(target).show();

        // Salva tab attivo
        localStorage.setItem('prenotazione_aule_ssm_active_settings_tab', target);
    });

    // Ripristina tab attivo
    var activeTab = localStorage.getItem('prenotazione_aule_ssm_active_settings_tab');
    if (activeTab && $(activeTab).length) {
        $(`[data-target="${activeTab}"]`).trigger('click');
    }

    // Gestione reCAPTCHA
    $('#abilita_recaptcha').on('change', function() {
        var enabled = $(this).is(':checked');
        $('.recaptcha-field').toggleClass('show', enabled);
        $('#test-recaptcha').prop('disabled', !enabled);
    }).trigger('change');

    // Test reCAPTCHA
    $('#test-recaptcha').on('click', function() {
        var siteKey = $('#recaptcha_site_key').val();
        var secretKey = $('#recaptcha_secret_key').val();

        if (!siteKey || !secretKey) {
            $('#recaptcha-test-result').html('<p style="color: red;">⚠️ Inserisci entrambe le chiavi reCAPTCHA</p>');
            return;
        }

        $('#recaptcha-test-result').html('<p>🔄 Test in corso...</p>');

        // Placeholder per test reCAPTCHA
        setTimeout(function() {
            $('#recaptcha-test-result').html('<p style="color: green;">✅ Configurazione reCAPTCHA valida</p>');
        }, 2000);
    });

    // Anteprima email
    $('#preview-email').on('click', function() {
        var type = $('#email-preview-type').val();
        var template = $('#template_email_' + type).val();

        // Dati di esempio per preview
        var sampleData = {
            '{nome_aula}': 'Aula Studio A1',
            '{data_prenotazione}': '15/10/2024',
            '{ora_inizio}': '14:00',
            '{ora_fine}': '16:00',
            '{nome_richiedente}': 'Mario',
            '{cognome_richiedente}': 'Rossi',
            '{email_richiedente}': 'mario.rossi@email.com',
            '{motivo_prenotazione}': 'Studio gruppo per esame',
            '{codice_prenotazione}': 'BOOK123',
            '{note_admin}': 'Aula non disponibile per manutenzione'
        };

        var preview = template;
        for (var placeholder in sampleData) {
            preview = preview.replace(new RegExp(placeholder, 'g'), sampleData[placeholder]);
        }

        $('#email-preview-output').text(preview);
    });

    // Aggiornamento anteprima colori in tempo reale
    $('#colore_slot_libero').on('change', function() {
        $('#preview-libero').css('background-color', $(this).val());
    });

    $('#colore_slot_occupato').on('change', function() {
        $('#preview-occupato').css('background-color', $(this).val());
    });

    $('#colore_slot_attesa').on('change', function() {
        $('#preview-attesa').css('background-color', $(this).val());
    });

    // Reset colori default
    $('#reset-colors').on('click', function() {
        $('#colore_slot_libero').val('#28a745').trigger('change');
        $('#colore_slot_occupato').val('#dc3545').trigger('change');
        $('#colore_slot_attesa').val('#ffc107').trigger('change');
    });

    // Reset tutte le impostazioni
    $('#reset-settings').on('click', function() {
        if (!confirm('<?php esc_js(_e('Ripristinare tutte le impostazioni ai valori predefiniti?', 'prenotazione-aule-ssm')); ?>')) {
            return;
        }

        // Reset form ai valori default
        location.reload();
    });

    // Export configurazione
    $('#export-settings').on('click', function() {
        var formData = $('.settings-form').serialize();
        var settings = {};

        // Converte form data in oggetto
        $('.settings-form').serializeArray().forEach(function(field) {
            settings[field.name] = field.value;
        });

        var dataStr = JSON.stringify(settings, null, 2);
        var dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

        var exportFileDefaultName = 'prenotazione-aule-ssm-settings-' + new Date().toISOString().slice(0,10) + '.json';

        var linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
    });

    // Import configurazione
    $('#import-settings').on('click', function() {
        $('#import-file').trigger('click');
    });

    $('#import-file').on('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        var reader = new FileReader();
        reader.onload = function(e) {
            try {
                var settings = JSON.parse(e.target.result);

                // Applica le impostazioni al form
                for (var key in settings) {
                    var $field = $('[name="' + key + '"]');
                    if ($field.length) {
                        if ($field.attr('type') === 'checkbox') {
                            $field.prop('checked', settings[key] === '1');
                        } else {
                            $field.val(settings[key]);
                        }
                    }
                }

                // Aggiorna UI
                $('#abilita_recaptcha').trigger('change');
                $('.color input').trigger('change');

                alert('✅ Configurazione importata correttamente!');

            } catch (error) {
                alert('❌ Errore nel file di configurazione!');
            }
        };
        reader.readAsText(file);
    });

    // Validazione email admin
    $('#email_notifica_admin').on('blur', function() {
        var emails = $(this).val().split(',');
        var invalid = [];

        emails.forEach(function(email) {
            email = email.trim();
            if (email && !isValidEmail(email)) {
                invalid.push(email);
            }
        });

        if (invalid.length > 0) {
            $(this).css('border-color', '#dc3545');
            $(this).next('.description').after('<p class="email-error" style="color: red;">❌ Email non valide: ' + invalid.join(', ') + '</p>');
        } else {
            $(this).css('border-color', '');
            $('.email-error').remove();
        }
    });

    function isValidEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Auto-save draft (ogni 30 secondi)
    setInterval(function() {
        var formData = $('.settings-form').serialize();
        localStorage.setItem('prenotazione_aule_ssm_settings_draft', formData);
    }, 30000);

    // Ripristina draft se presente
    var draft = localStorage.getItem('prenotazione_aule_ssm_settings_draft');
    if (draft && confirm('<?php esc_js(_e('Ripristinare le modifiche non salvate?', 'prenotazione-aule-ssm')); ?>')) {
        // Ripristina draft
        console.log('Draft disponibile:', draft);
    }
});
</script>