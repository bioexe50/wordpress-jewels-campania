<?php
/**
 * Plugin Name: Prenotazione Aule SSM
 * Plugin URI: https://ssm.example.com/prenotazione-aule-ssm
 * Description: Sistema di prenotazione aule per Scuola di Specializzazione Medica
 * Version: 1.0.0
 * Author: SSM Developer
 * Author URI: https://ssm.example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: prenotazione-aule-ssm
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Network: false
 *
 * @package Prenotazione_Aule_SSM
 * @since 1.0.0
 */

// Previeni accesso diretto
if (!defined("ABSPATH")) {
    exit;
}

// Definisci costanti del plugin
define("PRENOTAZIONE_AULE_SSM_VERSION", "1.0.0");
define("PRENOTAZIONE_AULE_SSM_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("PRENOTAZIONE_AULE_SSM_PLUGIN_URL", plugin_dir_url(__FILE__));
define("PRENOTAZIONE_AULE_SSM_PLUGIN_FILE", __FILE__);
define("PRENOTAZIONE_AULE_SSM_PLUGIN_BASENAME", plugin_basename(__FILE__));

/**
 * Codice eseguito durante l attivazione del plugin
 */
function activate_prenotazione_aule_ssm() {
    require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . "includes/class-prenotazione-aule-ssm-activator.php";
    Prenotazione_Aule_SSM_Activator::activate();
}

/**
 * Codice eseguito durante la disattivazione del plugin
 */
function deactivate_prenotazione_aule_ssm() {
    require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . "includes/class-prenotazione-aule-ssm-deactivator.php";
    Prenotazione_Aule_SSM_Deactivator::deactivate();
}

register_activation_hook(__FILE__, "activate_prenotazione_aule_ssm");
register_deactivation_hook(__FILE__, "deactivate_prenotazione_aule_ssm");

/**
 * La classe principale del plugin che coordina hooks e dipendenze
 */
require PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . "includes/class-prenotazione-aule-ssm.php";

// Carica gestione multi-slot
require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'public/class-prenotazione-aule-ssm-multi-slot.php';

/**
 * Inizia l esecuzione del plugin
 */
function run_prenotazione_aule_ssm() {
    $plugin = new Prenotazione_Aule_SSM();
    $plugin->run();
}

// Esegui il plugin dopo che WordPress è stato caricato
add_action("plugins_loaded", "run_prenotazione_aule_ssm");

/**
 * Hook per la disinstallazione del plugin
 */
register_uninstall_hook(__FILE__, "uninstall_prenotazione_aule_ssm");

function uninstall_prenotazione_aule_ssm() {
    // Include il file di disinstallazione
    require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . "includes/class-prenotazione-aule-ssm-uninstaller.php";
    Prenotazione_Aule_SSM_Uninstaller::uninstall();
}
