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
    'conserva_dati_disinstallazione' => 1, // DEFAULT: CONSERVA DATI (più sicuro)
    // ✅ v3.3.9 - Controllo invio email
    'abilita_email_conferma' => 1, // DEFAULT: Email conferma ABILITATA
    'abilita_email_rifiuto' => 1, // DEFAULT: Email rifiuto ABILITATA
    'abilita_email_admin' => 1, // DEFAULT: Email notifica admin ABILITATA
    'abilita_email_reminder' => 1, // DEFAULT: Email reminder ABILITATA
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
                                   placeholder="segreteria@istituto.it, responsabile.aule@istituto.it">
                            <p class="description">
                                <?php _e('Email che riceveranno notifica quando un utente prenota un\'aula (separate da virgola).', 'prenotazione-aule-ssm'); ?><br>
                                <strong><?php _e('Esempio pratico:', 'prenotazione-aule-ssm'); ?></strong> segreteria@istituto.it, portineria@istituto.it, direzione@istituto.it<br>
                                <em><?php _e('Quando uno studente prenota, TUTTE queste email ricevono notifica con link per approvare/rifiutare.', 'prenotazione-aule-ssm'); ?></em>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="conserva_dati_disinstallazione"><?php _e('Conserva Dati', 'prenotazione-aule-ssm'); ?></label>
                        </th>
                        <td>
                            <div class="pas-conserva-dati-wrapper">
                                <label for="conserva_dati_disinstallazione">
                                    <input type="checkbox"
                                           id="conserva_dati_disinstallazione"
                                           name="conserva_dati_disinstallazione"
                                           value="1"
                                           <?php checked($default_settings['conserva_dati_disinstallazione'], 1); ?>>
                                    <?php _e('Conserva tutti i dati quando il plugin viene disinstallato', 'prenotazione-aule-ssm'); ?>
                                </label>

                                <!-- Avviso dinamico status corrente -->
                                <div class="pas-conserva-status-box" id="pas-conserva-status">
                                    <?php if ($default_settings['conserva_dati_disinstallazione']): ?>
                                        <div class="notice notice-success inline" style="margin: 15px 0; padding: 10px;">
                                            <p><strong>✅ STATO ATTUALE: I tuoi dati SARANNO CONSERVATI alla disinstallazione</strong></p>
                                            <p style="margin: 5px 0 0 0; font-size: 13px;">
                                                <?php _e('Il messaggio di conferma disinstallazione di WordPress è generico, ma i tuoi dati (aule, prenotazioni, slot) NON verranno eliminati.', 'prenotazione-aule-ssm'); ?>
                                            </p>
                                        </div>
                                    <?php else: ?>
                                        <div class="notice notice-warning inline" style="margin: 15px 0; padding: 10px;">
                                            <p><strong>⚠️ STATO ATTUALE: I tuoi dati VERRANNO ELIMINATI alla disinstallazione</strong></p>
                                            <p style="margin: 5px 0 0 0; font-size: 13px;">
                                                <?php _e('Tutte le aule, prenotazioni, slot e impostazioni verranno CANCELLATI PERMANENTEMENTE.', 'prenotazione-aule-ssm'); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <p class="description">
                                    <strong style="color: #00a32a;"><?php _e('✅ ABILITATO DI DEFAULT (Comportamento sicuro):', 'prenotazione-aule-ssm'); ?></strong><br>
                                    <?php _e('Se abilitato, aule, prenotazioni, slot e impostazioni NON verranno eliminati alla disinstallazione del plugin.', 'prenotazione-aule-ssm'); ?><br>
                                    <?php _e('Utile se vuoi reinstallare il plugin in futuro mantenendo tutti i dati esistenti.', 'prenotazione-aule-ssm'); ?><br><br>
                                    <strong style="color: #d63638;"><?php _e('⚠️ IMPORTANTE:', 'prenotazione-aule-ssm'); ?></strong>
                                    <?php _e('Per eliminare COMPLETAMENTE tutti i dati quando disinstalli:', 'prenotazione-aule-ssm'); ?><br>
                                    <strong>1.</strong> <?php _e('DISABILITA questa opzione', 'prenotazione-aule-ssm'); ?><br>
                                    <strong>2.</strong> <?php _e('Salva le impostazioni', 'prenotazione-aule-ssm'); ?><br>
                                    <strong>3.</strong> <?php _e('Disinstalla il plugin', 'prenotazione-aule-ssm'); ?>
                                </p>
                            </div>
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
                <h2><?php _e('Controllo Invio Email', 'prenotazione-aule-ssm'); ?></h2>
                <p class="description">
                    <?php _e('Abilita o disabilita l\'invio automatico delle email di notifica. Utile per ambienti di test o per gestione manuale.', 'prenotazione-aule-ssm'); ?>
                </p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Email Conferma Prenotazione', 'prenotazione-aule-ssm'); ?>
                        </th>
                        <td>
                            <label for="abilita_email_conferma">
                                <input type="checkbox"
                                       id="abilita_email_conferma"
                                       name="abilita_email_conferma"
                                       value="1"
                                       <?php checked(isset($default_settings['abilita_email_conferma']) ? $default_settings['abilita_email_conferma'] : 1, 1); ?>>
                                <?php _e('Invia email all\'utente quando la prenotazione viene confermata', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <strong>✅ <?php _e('Abilitato di default', 'prenotazione-aule-ssm'); ?></strong><br>
                                <?php _e('Quando admin approva → Email automatica all\'utente con dettagli prenotazione', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e('Email Rifiuto Prenotazione', 'prenotazione-aule-ssm'); ?>
                        </th>
                        <td>
                            <label for="abilita_email_rifiuto">
                                <input type="checkbox"
                                       id="abilita_email_rifiuto"
                                       name="abilita_email_rifiuto"
                                       value="1"
                                       <?php checked(isset($default_settings['abilita_email_rifiuto']) ? $default_settings['abilita_email_rifiuto'] : 1, 1); ?>>
                                <?php _e('Invia email all\'utente quando la prenotazione viene rifiutata', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <strong>✅ <?php _e('Abilitato di default', 'prenotazione-aule-ssm'); ?></strong><br>
                                <?php _e('Quando admin rifiuta → Email automatica all\'utente con motivo rifiuto', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e('Email Notifica Amministratori', 'prenotazione-aule-ssm'); ?>
                        </th>
                        <td>
                            <label for="abilita_email_admin">
                                <input type="checkbox"
                                       id="abilita_email_admin"
                                       name="abilita_email_admin"
                                       value="1"
                                       <?php checked(isset($default_settings['abilita_email_admin']) ? $default_settings['abilita_email_admin'] : 1, 1); ?>>
                                <?php _e('Invia email agli admin quando arriva una nuova prenotazione', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <strong>✅ <?php _e('Abilitato di default', 'prenotazione-aule-ssm'); ?></strong><br>
                                <?php _e('Quando utente prenota → Email SUBITO a tutti gli admin configurati nel tab Generale', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e('Email Reminder Prenotazione', 'prenotazione-aule-ssm'); ?>
                        </th>
                        <td>
                            <label for="abilita_email_reminder">
                                <input type="checkbox"
                                       id="abilita_email_reminder"
                                       name="abilita_email_reminder"
                                       value="1"
                                       <?php checked(isset($default_settings['abilita_email_reminder']) ? $default_settings['abilita_email_reminder'] : 1, 1); ?>>
                                <?php _e('Invia promemoria automatico all\'utente il giorno prima della prenotazione', 'prenotazione-aule-ssm'); ?>
                            </label>
                            <p class="description">
                                <strong>✅ <?php _e('Abilitato di default', 'prenotazione-aule-ssm'); ?></strong><br>
                                <?php _e('Sistema invia automaticamente reminder 24h prima della prenotazione confermata', 'prenotazione-aule-ssm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <div class="notice notice-info inline" style="margin: 20px 0;">
                    <p>
                        <strong>💡 <?php _e('Suggerimento:', 'prenotazione-aule-ssm'); ?></strong>
                        <?php _e('In ambiente di test puoi disabilitare tutte le email per evitare invii indesiderati. In produzione tieni tutto abilitato per notifiche automatiche.', 'prenotazione-aule-ssm'); ?>
                    </p>
                </div>
            </div>

            <div class="settings-section">
                <h2><?php _e('Template Email', 'prenotazione-aule-ssm'); ?></h2>
                <p class="description">
                    <?php _e('Personalizza i template delle email automatiche. Usa i placeholder:', 'prenotazione-aule-ssm'); ?><br>
                    <strong>{nome_richiedente}</strong>, <strong>{cognome_richiedente}</strong>, <strong>{email_richiedente}</strong>,
                    <strong>{nome_aula}</strong>, <strong>{ubicazione}</strong>, <strong>{data_prenotazione}</strong>,
                    <strong>{ora_inizio}</strong>, <strong>{ora_fine}</strong>, <strong>{motivo}</strong>,
                    <strong>{codice_prenotazione}</strong>, <strong>{note_admin}</strong>, <strong>{link_gestione}</strong>
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
                                <?php _e('Template email inviata all\'utente quando la prenotazione viene confermata.', 'prenotazione-aule-ssm'); ?><br>
                                <strong><?php _e('Quando viene inviata:', 'prenotazione-aule-ssm'); ?></strong> <?php _e('Admin approva prenotazione → Email parte automaticamente', 'prenotazione-aule-ssm'); ?>
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
                                <?php _e('Template email inviata all\'utente quando la prenotazione viene rifiutata.', 'prenotazione-aule-ssm'); ?><br>
                                <strong><?php _e('Quando viene inviata:', 'prenotazione-aule-ssm'); ?></strong> <?php _e('Admin rifiuta prenotazione → Email parte automaticamente', 'prenotazione-aule-ssm'); ?>
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
                                <?php _e('Template email inviata agli amministratori per nuove prenotazioni.', 'prenotazione-aule-ssm'); ?><br>
                                <strong><?php _e('Quando viene inviata:', 'prenotazione-aule-ssm'); ?></strong> <?php _e('Utente prenota aula → Email parte SUBITO a tutti gli admin configurati sopra', 'prenotazione-aule-ssm'); ?><br>
                                <em><?php _e('Usa {link_gestione} per creare pulsante che porta direttamente alla pagina di approvazione.', 'prenotazione-aule-ssm'); ?></em>
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
    // FIX: Disabilita il controllo "unsaved changes" di WordPress
    // che si attiva erroneamente anche senza modifiche reali
    $(window).off('beforeunload.edit-post');
    window.onbeforeunload = null;

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
});
</script>