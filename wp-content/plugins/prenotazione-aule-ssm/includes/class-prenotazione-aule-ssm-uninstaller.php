<?php
/**
 * Gestisce la disinstallazione del plugin
 *
 * Questa classe gestisce la pulizia completa quando il plugin viene disinstallato:
 * - Rimozione tabelle database
 * - Cancellazione opzioni WordPress
 * - Pulizia capabilities
 *
 * @since 2.1.0
 * @package Prenotazione_Aule_SSM
 * @subpackage Prenotazione_Aule_SSM/includes
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

class Prenotazione_Aule_SSM_Uninstaller {

    /**
     * Esegue la disinstallazione completa del plugin
     *
     * ATTENZIONE: Questa funzione elimina TUTTI i dati del plugin:
     * - Tabelle database (aule, prenotazioni, slot, impostazioni)
     * - Opzioni WordPress
     * - Capabilities personalizzati
     *
     * @since 2.1.0
     * @static
     */
    public static function uninstall() {
        global $wpdb;

        // Verifica permessi amministratore
        if (!current_user_can('activate_plugins')) {
            return;
        }

        // Verifica che la disinstallazione sia legittima
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            return;
        }

        // 1. ELIMINA TABELLE DATABASE
        self::drop_database_tables();

        // 2. ELIMINA OPZIONI WORDPRESS
        self::delete_plugin_options();

        // 3. RIMUOVI CAPABILITIES PERSONALIZZATI
        self::remove_custom_capabilities();

        // 4. PULISCI TRANSIENTS
        self::delete_plugin_transients();

        // 5. PULISCI CRON JOBS (se esistono)
        self::clear_scheduled_events();

        // Log disinstallazione (opzionale)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[Prenotazione Aule SSM] Plugin uninstalled successfully');
        }
    }

    /**
     * Elimina tutte le tabelle del plugin
     *
     * @since 2.1.0
     * @static
     * @access private
     */
    private static function drop_database_tables() {
        global $wpdb;

        $tables = array(
            $wpdb->prefix . 'prenotazione_aule_ssm_aule',
            $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni',
            $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita',
            $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni'
        );

        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS {$table}");
        }
    }

    /**
     * Elimina tutte le opzioni del plugin
     *
     * @since 2.1.0
     * @static
     * @access private
     */
    private static function delete_plugin_options() {
        // Opzioni generali
        delete_option('prenotazione_aule_ssm_version');
        delete_option('prenotazione_aule_ssm_settings');
        delete_option('prenotazione_aule_ssm_db_version');
        delete_option('prenotazione_aule_ssm_installed');

        // Opzioni email
        delete_option('prenotazione_aule_ssm_email_settings');
        delete_option('prenotazione_aule_ssm_smtp_settings');

        // Opzioni cache/performance
        delete_option('prenotazione_aule_ssm_cache_settings');

        // Site options (per multisite)
        delete_site_option('prenotazione_aule_ssm_version');
        delete_site_option('prenotazione_aule_ssm_settings');
    }

    /**
     * Rimuove capabilities personalizzati dai ruoli WordPress
     *
     * @since 2.1.0
     * @static
     * @access private
     */
    private static function remove_custom_capabilities() {
        $roles_to_clean = array('administrator', 'editor');

        $capabilities = array(
            'manage_prenotazione_aule_ssm',
            'edit_prenotazione_aule_ssm',
            'delete_prenotazione_aule_ssm',
            'view_prenotazione_aule_ssm_reports'
        );

        foreach ($roles_to_clean as $role_name) {
            $role = get_role($role_name);
            if ($role) {
                foreach ($capabilities as $cap) {
                    $role->remove_cap($cap);
                }
            }
        }
    }

    /**
     * Elimina transients del plugin
     *
     * @since 2.1.0
     * @static
     * @access private
     */
    private static function delete_plugin_transients() {
        global $wpdb;

        // Elimina transients con prefisso plugin
        $wpdb->query(
            "DELETE FROM {$wpdb->options}
             WHERE option_name LIKE '_transient_prenotazione_aule_ssm_%'
             OR option_name LIKE '_transient_timeout_prenotazione_aule_ssm_%'"
        );

        // Elimina site transients (multisite)
        $wpdb->query(
            "DELETE FROM {$wpdb->options}
             WHERE option_name LIKE '_site_transient_prenotazione_aule_ssm_%'
             OR option_name LIKE '_site_transient_timeout_prenotazione_aule_ssm_%'"
        );
    }

    /**
     * Cancella eventi schedulati via WP-Cron
     *
     * @since 2.1.0
     * @static
     * @access private
     */
    private static function clear_scheduled_events() {
        // Rimuovi eventi cron personalizzati (se esistono)
        $cron_hooks = array(
            'prenotazione_aule_ssm_daily_cleanup',
            'prenotazione_aule_ssm_weekly_report',
            'prenotazione_aule_ssm_send_reminders'
        );

        foreach ($cron_hooks as $hook) {
            $timestamp = wp_next_scheduled($hook);
            if ($timestamp) {
                wp_unschedule_event($timestamp, $hook);
            }
            // Rimuovi tutti gli eventi con questo hook
            wp_clear_scheduled_hook($hook);
        }
    }
}
