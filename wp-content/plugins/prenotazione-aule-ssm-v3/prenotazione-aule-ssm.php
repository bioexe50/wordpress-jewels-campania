<?php
/**
 * Plugin Name: Prenotazione Aule SSM
 * Plugin URI: https://raffaelevitulano.com
 * Description: Sistema completo di gestione prenotazioni aule per istituzioni educative. Include calendario, multi-booking, notifiche email e dashboard amministrativa.
 * Version: 3.4.5
 * Author: Benny e Raffa
 * Author URI: https://raffaelevitulano.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: prenotazione-aule-ssm
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Network: false
 *
 * @package Prenotazione_Aule_SSM
 * @version 3.4.5
 * @since 1.0.0
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Definisci costanti del plugin
 */
define('PRENOTAZIONE_AULE_SSM_VERSION', '3.4.5');
define('PRENOTAZIONE_AULE_SSM_DB_VERSION', '3.0');
define('PRENOTAZIONE_AULE_SSM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PRENOTAZIONE_AULE_SSM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PRENOTAZIONE_AULE_SSM_PLUGIN_FILE', __FILE__);
define('PRENOTAZIONE_AULE_SSM_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Debug mode (disabilitare in produzione)
define('PRENOTAZIONE_AULE_SSM_DEBUG', false);

/**
 * Content Security Policy - Triple Layer Fix
 *
 * Questo fix è necessario per compatibilità con plugin di sicurezza come:
 * - Wordfence Security
 * - Really Simple SSL
 * - iThemes Security
 *
 * LAYER 1: PHP Direct Header (massima priorità)
 * LAYER 2: WordPress send_headers hook (backup)
 * LAYER 3: Meta tag HTML (frontend/admin)
 *
 * @since 3.0.0
 */

// LAYER 1: PHP Header diretto
if (!headers_sent()) {
    header_remove('Content-Security-Policy');
    header_remove('X-Content-Security-Policy');

    $csp_directives = array(
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net data: blob:",
        "style-src 'self' 'unsafe-inline' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com data:",
        "img-src 'self' data: https: http: cdn.jsdelivr.net blob:",
        "font-src 'self' data: https: http: cdnjs.cloudflare.com fonts.gstatic.com",
        "connect-src 'self' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net",
        "worker-src 'self' blob:",
        "child-src 'self' blob:",
        "frame-src 'self'",
        "object-src 'none'",
        "base-uri 'self'"
    );

    header('Content-Security-Policy: ' . implode('; ', $csp_directives), true);
}

// LAYER 2: WordPress send_headers hook
add_action('send_headers', function() {
    if (!headers_sent()) {
        header_remove('Content-Security-Policy');
        header_remove('X-Content-Security-Policy');

        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net data: blob:; ";
        $csp .= "style-src 'self' 'unsafe-inline' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com data:; ";
        $csp .= "img-src 'self' data: https: http: cdn.jsdelivr.net blob:; ";
        $csp .= "font-src 'self' data: https: http: cdnjs.cloudflare.com fonts.gstatic.com; ";
        $csp .= "connect-src 'self' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net; ";
        $csp .= "worker-src 'self' blob:; ";
        $csp .= "child-src 'self' blob:; ";
        $csp .= "object-src 'none'; ";
        $csp .= "base-uri 'self'";

        header('Content-Security-Policy: ' . $csp, true);
    }
}, 1);

// LAYER 3: Meta tag HTML
add_action('wp_head', function() {
    echo '<meta http-equiv="Content-Security-Policy" content="default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net data: blob:; style-src \'self\' \'unsafe-inline\' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com data:; img-src \'self\' data: https: http: cdn.jsdelivr.net blob:; font-src \'self\' data: https: http: cdnjs.cloudflare.com fonts.gstatic.com; connect-src \'self\' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net; object-src \'none\'">' . "\n";
}, 1);

add_action('admin_head', function() {
    echo '<meta http-equiv="Content-Security-Policy" content="default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net data: blob:; style-src \'self\' \'unsafe-inline\' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com data:; img-src \'self\' data: https: http: cdn.jsdelivr.net blob:; font-src \'self\' data: https: http: cdnjs.cloudflare.com fonts.gstatic.com; connect-src \'self\' https: http: cdn.jsdelivr.net cdnjs.cloudflare.com *.jsdelivr.net; object-src \'none\'">' . "\n";
}, 1);

/**
 * Enqueue Debug Script (solo se debug attivo)
 *
 * @since 3.0.0
 */
if (defined('PRENOTAZIONE_AULE_SSM_DEBUG') && PRENOTAZIONE_AULE_SSM_DEBUG) {
    add_action('wp_enqueue_scripts', function() {
        wp_enqueue_script(
            'prenotazione-aule-ssm-debug',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/js/prenotazione-aule-ssm-debug.js',
            array('jquery'),
            PRENOTAZIONE_AULE_SSM_VERSION,
            true
        );
    }, 5);

    add_action('admin_enqueue_scripts', function() {
        wp_enqueue_script(
            'prenotazione-aule-ssm-debug',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/js/prenotazione-aule-ssm-debug.js',
            array('jquery'),
            PRENOTAZIONE_AULE_SSM_VERSION,
            true
        );
    }, 5);
}

/**
 * Codice eseguito durante l'attivazione del plugin
 *
 * @since 1.0.0
 */
function activate_prenotazione_aule_ssm() {
    require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'includes/class-prenotazione-aule-ssm-activator.php';
    Prenotazione_Aule_SSM_Activator::activate();
}

/**
 * Codice eseguito durante la disattivazione del plugin
 *
 * @since 1.0.0
 */
function deactivate_prenotazione_aule_ssm() {
    require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'includes/class-prenotazione-aule-ssm-deactivator.php';
    Prenotazione_Aule_SSM_Deactivator::deactivate();
}

/**
 * Registra hooks di attivazione/disattivazione
 */
register_activation_hook(__FILE__, 'activate_prenotazione_aule_ssm');
register_deactivation_hook(__FILE__, 'deactivate_prenotazione_aule_ssm');

/**
 * Carica la classe principale del plugin
 */
require PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'includes/class-prenotazione-aule-ssm.php';

/**
 * Carica gestione multi-slot
 */
require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'public/class-prenotazione-aule-ssm-multi-slot.php';

/**
 * Carica sistema auto-aggiornamento
 *
 * @since 3.4.0
 */
require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'includes/class-prenotazione-aule-ssm-updater.php';
require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'includes/class-prenotazione-aule-ssm-update-endpoint.php';

/**
 * Inizializza ed esegue il plugin
 *
 * @since 1.0.0
 */
function run_prenotazione_aule_ssm() {
    $plugin = new Prenotazione_Aule_SSM();
    $plugin->run();
}

/**
 * Inizializza sistema auto-aggiornamento
 *
 * @since 3.4.0
 */
function init_prenotazione_aule_ssm_updater() {
    // URL del sito che ospita gli aggiornamenti
    // Cambia questo URL con il tuo sito di produzione
    $update_server_url = 'https://raffaelevitulano.com';

    // Inizializza updater (controlla aggiornamenti da sito remoto)
    $updater = new Prenotazione_Aule_SSM_Updater(
        PRENOTAZIONE_AULE_SSM_PLUGIN_FILE,
        $update_server_url
    );

    // Inizializza endpoint (fornisce info aggiornamenti AD altri siti)
    // URL download: modifica con il percorso reale dove caricherai i file ZIP
    $download_url = $update_server_url . '/downloads/prenotazione-aule-ssm-v' . PRENOTAZIONE_AULE_SSM_VERSION . '.zip';

    $endpoint = new Prenotazione_Aule_SSM_Update_Endpoint($download_url);
}

// Esegui il plugin dopo che WordPress è stato caricato
add_action('plugins_loaded', 'run_prenotazione_aule_ssm');

// Inizializza auto-aggiornamento
add_action('plugins_loaded', 'init_prenotazione_aule_ssm_updater');

