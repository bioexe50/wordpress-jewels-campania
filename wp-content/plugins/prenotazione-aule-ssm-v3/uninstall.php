<?php
/**
 * Fired when the plugin is uninstalled - VERSIONE DIRETTA
 *
 * Questa versione NON usa la classe Uninstaller per evitare problemi di class caching.
 * Tutto il codice di pulizia è qui direttamente per garantire esecuzione sicura.
 *
 * @package Prenotazione_Aule_SSM
 * @since 3.0.6
 */

// Se l'uninstall non viene chiamato da WordPress, esci
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Debug log
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('[Prenotazione Aule SSM] uninstall.php DIRECT VERSION called');
}

// Verifica permessi (solo se non è WP-CLI)
if (!defined('WP_CLI') && !current_user_can('activate_plugins')) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[Prenotazione Aule SSM] uninstall.php - no permissions');
    }
    return;
}

global $wpdb;

if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('[Prenotazione Aule SSM] uninstall.php - starting database cleanup');
}

// VERIFICA SE L'UTENTE VUOLE CONSERVARE I DATI
$table_impostazioni = $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni';
$conserva_dati = $wpdb->get_var($wpdb->prepare(
    "SELECT conserva_dati_disinstallazione FROM {$table_impostazioni} WHERE id = %d",
    1
));

if ($conserva_dati == 1) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[Prenotazione Aule SSM] uninstall.php - Conservazione dati abilitata, SKIP eliminazione database');
    }

    // Elimina solo le opzioni WordPress, ma mantiene tutti i dati
    delete_option('prenotazione_aule_ssm_version');
    delete_option('prenotazione_aule_ssm_db_version');
    delete_option('prenotazione_aule_ssm_installed');
    delete_option('prenotazione_aule_ssm_installed_date');

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[Prenotazione Aule SSM] uninstall.php - Completato (dati conservati)');
    }

    return; // ESCE SENZA ELIMINARE NULLA
}

// 1. ELIMINA FOREIGN KEYS
$table_slot = $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita';
$table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';

$constraints = array(
    array('table' => $table_slot, 'constraint' => 'fk_slot_aula'),
    array('table' => $table_prenotazioni, 'constraint' => 'fk_prenotazione_aula')
);

foreach ($constraints as $fk) {
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
         WHERE CONSTRAINT_SCHEMA = %s AND TABLE_NAME = %s AND CONSTRAINT_NAME = %s AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
        DB_NAME, $fk['table'], $fk['constraint']
    ));

    if ((int) $exists > 0) {
        $wpdb->query("ALTER TABLE {$fk['table']} DROP FOREIGN KEY {$fk['constraint']}");
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("[Prenotazione Aule SSM] Dropped FK: {$fk['constraint']}");
        }
    }
}

// 2. ELIMINA TABELLE
$tables = array(
    $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni',
    $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita',
    $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni',
    $wpdb->prefix . 'prenotazione_aule_ssm_aule'
);

foreach ($tables as $table) {
    $result = $wpdb->query("DROP TABLE IF EXISTS {$table}");
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log("[Prenotazione Aule SSM] Dropped table: {$table} - Result: " . ($result !== false ? 'SUCCESS' : 'FAILED'));
    }
}

// 3. ELIMINA OPZIONI
delete_option('prenotazione_aule_ssm_version');
delete_option('prenotazione_aule_ssm_settings');
delete_option('prenotazione_aule_ssm_db_version');
delete_option('prenotazione_aule_ssm_installed');
delete_option('prenotazione_aule_ssm_installed_date');
delete_option('prenotazione_aule_ssm_email_settings');
delete_option('prenotazione_aule_ssm_smtp_settings');
delete_option('prenotazione_aule_ssm_cache_settings');

// Log completamento
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('[Prenotazione Aule SSM] uninstall.php DIRECT - completed successfully');
}
