<?php
/**
 * Plugin Name: WP Aule Booking
 * Plugin URI: https://example.com/wp-aule-booking
 * Description: Sistema completo di prenotazione aule studio per WordPress
 * Version: 1.0.0
 * Author: Developer
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: aule-booking
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Network: false
 *
 * @package WP_Aule_Booking
 * @since 1.0.0
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Definisci costanti del plugin
define('WP_AULE_BOOKING_VERSION', '1.1.4');
define('WP_AULE_BOOKING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_AULE_BOOKING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_AULE_BOOKING_PLUGIN_FILE', __FILE__);
define('WP_AULE_BOOKING_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Codice eseguito durante l'attivazione del plugin
 */
function activate_aule_booking() {
    require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-activator.php';
    Aule_Booking_Activator::activate();
}

/**
 * Codice eseguito durante la disattivazione del plugin
 */
function deactivate_aule_booking() {
    require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-deactivator.php';
    Aule_Booking_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_aule_booking');
register_deactivation_hook(__FILE__, 'deactivate_aule_booking');

/**
 * La classe principale del plugin che coordina hooks e dipendenze
 */
require WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking.php';

/**
 * Inizia l'esecuzione del plugin
 */
function run_aule_booking() {
    $plugin = new Aule_Booking();
    $plugin->run();
}

// Esegui il plugin dopo che WordPress è stato caricato
add_action('plugins_loaded', 'run_aule_booking');

/**
 * Hook per la disinstallazione del plugin
 */
register_uninstall_hook(__FILE__, 'uninstall_aule_booking');

function uninstall_aule_booking() {
    // Include il file di disinstallazione
    require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-uninstaller.php';
    Aule_Booking_Uninstaller::uninstall();
}