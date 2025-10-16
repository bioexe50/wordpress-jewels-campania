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

        // Debug log
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[Uninstaller] START uninstall()');
            error_log('[Uninstaller] WP_UNINSTALL_PLUGIN defined: ' . (defined('WP_UNINSTALL_PLUGIN') ? 'YES' : 'NO'));
            error_log('[Uninstaller] WP_CLI defined: ' . (defined('WP_CLI') ? 'YES' : 'NO'));
            error_log('[Uninstaller] current_user_can: ' . (current_user_can('activate_plugins') ? 'YES' : 'NO'));
        }

        // Verifica che la disinstallazione sia legittima
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[Uninstaller] EXIT: WP_UNINSTALL_PLUGIN not defined');
            }
            return;
        }

        // Verifica permessi amministratore (solo se non è WP-CLI)
        if (!defined('WP_CLI') && !current_user_can('activate_plugins')) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[Uninstaller] EXIT: No permissions');
            }
            return;
        }

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[Uninstaller] Permissions OK, proceeding with database cleanup');
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
     * NOTA: Le FOREIGN KEY devono essere rimosse PRIMA delle tabelle.
     * Altrimenti MySQL potrebbe bloccare il DROP TABLE.
     *
     * @since 2.1.0
     * @static
     * @access private
     */
    private static function drop_database_tables() {
        global $wpdb;

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[Uninstaller] drop_database_tables() START');
        }

        // STEP 1: Rimuovi FOREIGN KEY constraints prima di eliminare tabelle
        self::drop_foreign_keys();

        // STEP 2: Elimina tabelle in ordine sicuro (prima figlie, poi genitori)
        $tables = array(
            // Prima elimina tabelle con FK
            $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni',
            $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita',
            // Poi elimina tabelle indipendenti
            $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni',
            // Infine tabella padre
            $wpdb->prefix . 'prenotazione_aule_ssm_aule'
        );

        foreach ($tables as $table) {
            $result = $wpdb->query("DROP TABLE IF EXISTS {$table}");
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("[Uninstaller] DROP TABLE {$table}: " . ($result !== false ? 'SUCCESS' : 'FAILED'));
                if ($wpdb->last_error) {
                    error_log("[Uninstaller] MySQL Error: {$wpdb->last_error}");
                }
            }
        }

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[Uninstaller] drop_database_tables() COMPLETE');
        }
    }

    /**
     * Rimuove FOREIGN KEY constraints prima di eliminare le tabelle
     *
     * Necessario per evitare errori MySQL durante DROP TABLE.
     *
     * @since 3.0.2
     * @static
     * @access private
     */
    private static function drop_foreign_keys() {
        global $wpdb;

        $table_slot = $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita';
        $table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';

        // Rimuovi FK solo se esistono (per evitare errori)
        $constraints = array(
            array('table' => $table_slot, 'constraint' => 'fk_slot_aula'),
            array('table' => $table_prenotazioni, 'constraint' => 'fk_prenotazione_aula')
        );

        foreach ($constraints as $fk) {
            // Verifica se FK esiste
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*)
                 FROM information_schema.TABLE_CONSTRAINTS
                 WHERE CONSTRAINT_SCHEMA = %s
                 AND TABLE_NAME = %s
                 AND CONSTRAINT_NAME = %s
                 AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
                DB_NAME,
                $fk['table'],
                $fk['constraint']
            ));

            // Rimuovi FK se esiste
            if ((int) $exists > 0) {
                $wpdb->query("ALTER TABLE {$fk['table']} DROP FOREIGN KEY {$fk['constraint']}");
            }
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
