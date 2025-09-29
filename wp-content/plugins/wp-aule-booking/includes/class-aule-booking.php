<?php

/**
 * La classe principale del plugin
 *
 * Questa classe coordina tutti gli hook e le dipendenze del plugin
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/includes
 */

class Aule_Booking {

    /**
     * Il loader responsabile per il mantenimento e la registrazione di tutti gli hooks del plugin
     *
     * @since 1.0.0
     * @access protected
     * @var Aule_Booking_Loader $loader
     */
    protected $loader;

    /**
     * L'identificatore unico di questo plugin
     *
     * @since 1.0.0
     * @access protected
     * @var string $plugin_name
     */
    protected $plugin_name;

    /**
     * La versione corrente del plugin
     *
     * @since 1.0.0
     * @access protected
     * @var string $version
     */
    protected $version;

    /**
     * Definisce la funzionalità principale del plugin
     *
     * Imposta il nome del plugin e la versione che può essere utilizzata in tutto il plugin.
     * Carica le dipendenze, definisce la locale, e imposta gli hooks per l'area Admin e Public
     *
     * @since 1.0.0
     */
    public function __construct() {
        if (defined('WP_AULE_BOOKING_VERSION')) {
            $this->version = WP_AULE_BOOKING_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'aule-booking';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_cron_hooks();
        $this->define_api_hooks();
    }

    /**
     * Carica le dipendenze richieste per questo plugin
     *
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies() {

        /**
         * La classe responsabile per orchestrare le azioni e i filtri del plugin
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-loader.php';

        /**
         * La classe responsabile per definire l'internazionalizzazione
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-i18n.php';

        /**
         * La classe responsabile per definire tutte le azioni nell'area admin
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/class-aule-booking-admin.php';

        /**
         * La classe responsabile per definire tutte le azioni nell'area public
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'public/class-aule-booking-public.php';

        /**
         * La classe per la gestione delle email
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-email.php';

        /**
         * La classe per la gestione delle REST API
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-api.php';

        /**
         * La classe per la gestione dei cron job
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-cron.php';

        /**
         * Classi per la gestione dei dati
         */
        require_once WP_AULE_BOOKING_PLUGIN_DIR . 'includes/class-aule-booking-database.php';

        $this->loader = new Aule_Booking_Loader();
    }

    /**
     * Definisce la locale per questo plugin per l'internazionalizzazione
     *
     * @since 1.0.0
     * @access private
     */
    private function set_locale() {
        $plugin_i18n = new Aule_Booking_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Registra tutti gli hooks relativi all'area admin del plugin
     *
     * @since 1.0.0
     * @access private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Aule_Booking_Admin($this->get_plugin_name(), $this->get_version());

        // Enqueue scripts e styles
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Menu admin
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        // AJAX handlers per admin
        $this->loader->add_action('wp_ajax_aule_get_slots', $plugin_admin, 'ajax_get_slots');
        $this->loader->add_action('wp_ajax_aule_approve_booking', $plugin_admin, 'ajax_approve_booking');
        $this->loader->add_action('wp_ajax_aule_reject_booking', $plugin_admin, 'ajax_reject_booking');
        $this->loader->add_action('wp_ajax_aule_generate_slots', $plugin_admin, 'ajax_generate_slots');
        $this->loader->add_action('wp_ajax_aule_delete_booking', $plugin_admin, 'ajax_delete_booking');
        $this->loader->add_action('wp_ajax_aule_get_availability', $plugin_admin, 'ajax_get_availability');

        // Salvataggio impostazioni
        $this->loader->add_action('admin_post_aule_booking_save_settings', $plugin_admin, 'save_settings');
        $this->loader->add_action('admin_post_aule_booking_save_aula', $plugin_admin, 'save_aula');
        $this->loader->add_action('admin_post_aule_booking_delete_aula', $plugin_admin, 'delete_aula');
    }

    /**
     * Registra tutti gli hooks relativi all'area public del plugin
     *
     * @since 1.0.0
     * @access private
     */
    private function define_public_hooks() {
        $plugin_public = new Aule_Booking_Public($this->get_plugin_name(), $this->get_version());

        // Enqueue scripts e styles
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // Shortcodes
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');

        // AJAX handlers per public
        $this->loader->add_action('wp_ajax_nopriv_aule_check_availability', $plugin_public, 'ajax_check_availability');
        $this->loader->add_action('wp_ajax_aule_check_availability', $plugin_public, 'ajax_check_availability');
        $this->loader->add_action('wp_ajax_nopriv_aule_submit_booking', $plugin_public, 'ajax_submit_booking');
        $this->loader->add_action('wp_ajax_aule_submit_booking', $plugin_public, 'ajax_submit_booking');
        $this->loader->add_action('wp_ajax_aule_get_my_bookings', $plugin_public, 'ajax_get_my_bookings');
    }

    /**
     * Definisce gli hooks per i cron job
     *
     * @since 1.0.0
     * @access private
     */
    private function define_cron_hooks() {
        $plugin_cron = new Aule_Booking_Cron($this->get_plugin_name(), $this->get_version());

        // Eventi cron
        $this->loader->add_action('aule_booking_cleanup_expired', $plugin_cron, 'cleanup_expired_bookings');
        $this->loader->add_action('aule_booking_send_reminders', $plugin_cron, 'send_booking_reminders');
        $this->loader->add_action('aule_booking_weekly_report', $plugin_cron, 'send_weekly_report');
    }

    /**
     * Definisce gli hooks per le REST API
     *
     * @since 1.0.0
     * @access private
     */
    private function define_api_hooks() {
        $plugin_api = new Aule_Booking_API($this->get_plugin_name(), $this->get_version());

        // Registra endpoint REST
        $this->loader->add_action('rest_api_init', $plugin_api, 'register_routes');
    }

    /**
     * Esegue il loader per eseguire tutti gli hooks con WordPress
     *
     * @since 1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Il nome del plugin utilizzato per identificarlo univocamente nel contesto di WordPress e per definire l'internazionalizzazione
     *
     * @since 1.0.0
     * @return string Il nome del plugin
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Il riferimento alla classe che orchestra gli hooks del plugin
     *
     * @since 1.0.0
     * @return Aule_Booking_Loader Orchestra gli hooks del plugin
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Recupera il numero di versione del plugin
     *
     * @since 1.0.0
     * @return string Il numero di versione del plugin
     */
    public function get_version() {
        return $this->version;
    }
}