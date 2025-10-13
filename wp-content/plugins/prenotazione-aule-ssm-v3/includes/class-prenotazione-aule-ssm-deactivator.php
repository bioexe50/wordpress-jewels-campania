<?php

/**
 * Eseguito durante la disattivazione del plugin
 *
 * Questa classe definisce tutti i codici necessari per eseguire durante la disattivazione del plugin
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/includes
 */

class Prenotazione_Aule_SSM_Deactivator {

    /**
     * Metodo eseguito durante la disattivazione
     *
     * Rimuove gli eventi cron pianificati e pulisce i dati temporanei
     *
     * @since 1.0.0
     */
    public static function deactivate() {

        // Rimuovi eventi cron
        self::unschedule_cron_events();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Pulisci cache transient
        self::clear_transients();
    }

    /**
     * Rimuove eventi cron pianificati
     *
     * @since 1.0.0
     */
    private static function unschedule_cron_events() {
        wp_clear_scheduled_hook('prenotazione_aule_ssm_cleanup_expired');
        wp_clear_scheduled_hook('prenotazione_aule_ssm_send_reminders');
        wp_clear_scheduled_hook('prenotazione_aule_ssm_weekly_report');
    }

    /**
     * Pulisce i transient del plugin
     *
     * @since 1.0.0
     */
    private static function clear_transients() {
        delete_transient('prenotazione_aule_ssm_stats');
        delete_transient('prenotazione_aule_ssm_availability_cache');
    }
}

/**
 * Classe per la disinstallazione completa del plugin
 *
 * @since 1.0.0
 */
class Prenotazione_Aule_SSM_Uninstaller {

    /**
     * Disinstallazione completa del plugin
     *
     * ATTENZIONE: Questo metodo elimina TUTTI i dati del plugin
     * Viene eseguito solo se il plugin viene rimosso definitivamente
     *
     * @since 1.0.0
     */
    public static function uninstall() {

        // Controlla le autorizzazioni
        if (!current_user_can('activate_plugins')) {
            return;
        }

        // Verifica che sia una disinstallazione legittima
        check_admin_referer('bulk-plugins');

        // Elimina le tabelle del database
        if (get_option('prenotazione_aule_ssm_delete_data_on_uninstall', false)) {
            self::drop_tables();
        }

        // Rimuovi tutte le opzioni
        self::remove_options();

        // Rimuovi le capacità
        self::remove_capabilities();

        // Pulisci completamente i cron
        wp_clear_scheduled_hook('prenotazione_aule_ssm_cleanup_expired');
        wp_clear_scheduled_hook('prenotazione_aule_ssm_send_reminders');
        wp_clear_scheduled_hook('prenotazione_aule_ssm_weekly_report');
    }

    /**
     * Elimina le tabelle del database
     *
     * @since 1.0.0
     */
    private static function drop_tables() {
        global $wpdb;

        $tables = array(
            $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni',
            $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita',
            $wpdb->prefix . 'prenotazione_aule_ssm_aule',
            $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni'
        );

        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
    }

    /**
     * Rimuove tutte le opzioni del plugin
     *
     * @since 1.0.0
     */
    private static function remove_options() {
        delete_option('prenotazione_aule_ssm_version');
        delete_option('prenotazione_aule_ssm_installed_date');
        delete_option('prenotazione_aule_ssm_delete_data_on_uninstall');

        // Rimuovi anche i transient
        delete_transient('prenotazione_aule_ssm_stats');
        delete_transient('prenotazione_aule_ssm_availability_cache');
    }

    /**
     * Rimuove le capacità degli utenti
     *
     * @since 1.0.0
     */
    private static function remove_capabilities() {
        $role = get_role('administrator');

        if ($role) {
            $role->remove_cap('manage_prenotazione_aule_ssm');
            $role->remove_cap('edit_prenotazione_aule_ssm');
            $role->remove_cap('delete_prenotazione_aule_ssm');
            $role->remove_cap('view_prenotazione_aule_ssm_reports');
        }
    }
}