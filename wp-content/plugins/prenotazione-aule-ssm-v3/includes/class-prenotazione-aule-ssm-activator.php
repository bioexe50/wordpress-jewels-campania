<?php

/**
 * Eseguito durante l'attivazione del plugin
 *
 * Questa classe definisce tutti i codici necessari per eseguire durante l'attivazione del plugin
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/includes
 */

class Prenotazione_Aule_SSM_Activator {

    /**
     * Metodo eseguito durante l'attivazione
     *
     * Crea le tabelle del database, inserisce dati predefiniti,
     * imposta opzioni e capabilities
     *
     * @since 1.0.0
     */
    public static function activate() {

        // Crea le tabelle del database
        self::create_tables();

        // Inserisci dati di default
        self::insert_default_data();

        // Imposta le opzioni del plugin
        self::set_default_options();

        // Aggiungi capacità agli amministratori
        self::add_capabilities();

        // Flush rewrite rules per i custom endpoints
        flush_rewrite_rules();

        // Pianifica eventi cron
        self::schedule_cron_events();
    }

    /**
     * Crea le tabelle del database
     *
     * NOTA: FOREIGN KEY constraints NON sono incluse nel CREATE TABLE.
     * WordPress dbDelta() non supporta FOREIGN KEY nella sintassi CREATE TABLE.
     * Le FOREIGN KEY vengono aggiunte DOPO con add_foreign_keys() per garantire
     * installazione pulita senza errori SQL.
     *
     * @since 1.0.0
     */
    private static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Tabella aule
        $table_aule = $wpdb->prefix . 'prenotazione_aule_ssm_aule';
        $sql_aule = "CREATE TABLE $table_aule (
            id int(11) NOT NULL AUTO_INCREMENT,
            nome_aula varchar(255) NOT NULL,
            descrizione text DEFAULT NULL,
            capienza int(11) DEFAULT 1,
            ubicazione varchar(255) DEFAULT NULL,
            attrezzature text DEFAULT NULL,
            stato enum('attiva','non_disponibile','manutenzione') DEFAULT 'attiva',
            immagini text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_stato (stato),
            KEY idx_nome_aula (nome_aula)
        ) $charset_collate;";

        // Tabella slot disponibilità
        $table_slot = $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita';
        $sql_slot = "CREATE TABLE $table_slot (
            id int(11) NOT NULL AUTO_INCREMENT,
            aula_id int(11) NOT NULL,
            giorno_settimana tinyint(1) NOT NULL COMMENT '1=Lunedi, 7=Domenica',
            ora_inizio time NOT NULL,
            ora_fine time NOT NULL,
            durata_slot_minuti int(11) DEFAULT 60,
            data_inizio_validita date NOT NULL,
            data_fine_validita date DEFAULT NULL,
            ricorrenza enum('singolo','settimanale','mensile') DEFAULT 'settimanale',
            attivo boolean DEFAULT true,
            PRIMARY KEY (id),
            KEY idx_aula_id (aula_id),
            KEY idx_giorno_settimana (giorno_settimana),
            KEY idx_data_validita (data_inizio_validita, data_fine_validita),
            KEY idx_attivo (attivo)
        ) $charset_collate;";

        // Tabella prenotazioni
        $table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';
        $sql_prenotazioni = "CREATE TABLE $table_prenotazioni (
            id int(11) NOT NULL AUTO_INCREMENT,
            aula_id int(11) NOT NULL,
            nome_richiedente varchar(255) NOT NULL,
            cognome_richiedente varchar(255) NOT NULL,
            email_richiedente varchar(255) NOT NULL,
            motivo_prenotazione text NOT NULL,
            data_prenotazione date NOT NULL,
            ora_inizio time NOT NULL,
            ora_fine time NOT NULL,
            stato enum('in_attesa','confermata','rifiutata','cancellata') DEFAULT 'in_attesa',
            user_id_approvazione int(11) DEFAULT NULL,
            note_admin text DEFAULT NULL,
            codice_prenotazione varchar(32) DEFAULT NULL,
            gruppo_prenotazione varchar(50) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_codice (codice_prenotazione),
            KEY idx_aula_id (aula_id),
            KEY idx_email_richiedente (email_richiedente),
            KEY idx_data_prenotazione (data_prenotazione),
            KEY idx_stato (stato),
            KEY idx_created_at (created_at),
            KEY idx_gruppo_prenotazione (gruppo_prenotazione)
        ) $charset_collate;";

        // Tabella impostazioni
        $table_impostazioni = $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni';
        $sql_impostazioni = "CREATE TABLE $table_impostazioni (
            id int(11) NOT NULL AUTO_INCREMENT,
            conferma_automatica boolean DEFAULT false,
            email_notifica_admin text DEFAULT NULL COMMENT 'JSON array di email',
            template_email_conferma text DEFAULT NULL,
            template_email_rifiuto text DEFAULT NULL,
            template_email_admin text DEFAULT NULL,
            giorni_prenotazione_futura_max int(11) DEFAULT 30,
            ore_anticipo_prenotazione_min int(11) DEFAULT 24,
            max_prenotazioni_per_utente_giorno int(11) DEFAULT 3,
            abilita_recaptcha boolean DEFAULT false,
            recaptcha_site_key varchar(255) DEFAULT NULL,
            recaptcha_secret_key varchar(255) DEFAULT NULL,
            colore_slot_libero varchar(7) DEFAULT '#28a745',
            colore_slot_occupato varchar(7) DEFAULT '#dc3545',
            colore_slot_attesa varchar(7) DEFAULT '#ffc107',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_aule);
        dbDelta($sql_slot);
        dbDelta($sql_prenotazioni);
        dbDelta($sql_impostazioni);

        // Aggiorna schema per installazioni esistenti
        self::update_database_schema();

        // Aggiungi FOREIGN KEY constraints (dopo dbDelta per compatibilità)
        self::add_foreign_keys();
    }

    /**
     * Aggiorna schema database per installazioni esistenti
     * Aggiunge colonne mancanti senza ricreare tabelle
     *
     * @since 2.1.8
     */
    private static function update_database_schema() {
        global $wpdb;
        $table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';

        // Verifica se la colonna gruppo_prenotazione esiste
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $table_prenotazioni LIKE 'gruppo_prenotazione'");

        if (empty($column_exists)) {
            // Aggiungi colonna gruppo_prenotazione
            $wpdb->query("ALTER TABLE $table_prenotazioni ADD COLUMN gruppo_prenotazione varchar(50) DEFAULT NULL AFTER codice_prenotazione");
            $wpdb->query("ALTER TABLE $table_prenotazioni ADD KEY idx_gruppo_prenotazione (gruppo_prenotazione)");
        }
    }

    /**
     * Aggiunge FOREIGN KEY constraints alle tabelle
     *
     * NOTA: Questa funzione viene chiamata DOPO dbDelta() perché WordPress
     * dbDelta() non supporta FOREIGN KEY nella sintassi CREATE TABLE.
     *
     * Le FOREIGN KEY garantiscono integrità referenziale:
     * - ON DELETE CASCADE: eliminando un'aula, elimina automaticamente slot e prenotazioni
     * - Previene record orfani nel database
     *
     * @since 3.0.2
     */
    private static function add_foreign_keys() {
        global $wpdb;

        $table_aule = $wpdb->prefix . 'prenotazione_aule_ssm_aule';
        $table_slot = $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita';
        $table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';

        // Verifica se le tabelle esistono
        $aule_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_aule'");
        $slot_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_slot'");
        $prenotazioni_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_prenotazioni'");

        if (!$aule_exists || !$slot_exists || !$prenotazioni_exists) {
            return; // Tabelle non esistono, skip
        }

        // 1. FOREIGN KEY per slot_disponibilita -> aule
        if (!self::foreign_key_exists($table_slot, 'fk_slot_aula')) {
            // Prima pulisci eventuali record orfani
            $wpdb->query("DELETE FROM $table_slot WHERE aula_id NOT IN (SELECT id FROM $table_aule)");

            // Aggiungi FOREIGN KEY
            $wpdb->query("ALTER TABLE $table_slot
                          ADD CONSTRAINT fk_slot_aula
                          FOREIGN KEY (aula_id)
                          REFERENCES $table_aule(id)
                          ON DELETE CASCADE
                          ON UPDATE CASCADE");
        }

        // 2. FOREIGN KEY per prenotazioni -> aule
        if (!self::foreign_key_exists($table_prenotazioni, 'fk_prenotazione_aula')) {
            // Prima pulisci eventuali record orfani
            $wpdb->query("DELETE FROM $table_prenotazioni WHERE aula_id NOT IN (SELECT id FROM $table_aule)");

            // Aggiungi FOREIGN KEY
            $wpdb->query("ALTER TABLE $table_prenotazioni
                          ADD CONSTRAINT fk_prenotazione_aula
                          FOREIGN KEY (aula_id)
                          REFERENCES $table_aule(id)
                          ON DELETE CASCADE
                          ON UPDATE CASCADE");
        }
    }

    /**
     * Verifica se una FOREIGN KEY esiste già
     *
     * @param string $table_name Nome della tabella
     * @param string $constraint_name Nome del constraint
     * @return bool True se esiste, false altrimenti
     * @since 3.0.2
     */
    private static function foreign_key_exists($table_name, $constraint_name) {
        global $wpdb;

        $database_name = DB_NAME;

        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*)
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = %s
             AND TABLE_NAME = %s
             AND CONSTRAINT_NAME = %s
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            $database_name,
            $table_name,
            $constraint_name
        ));

        return (int) $result > 0;
    }

    /**
     * Inserisce dati predefiniti
     *
     * @since 1.0.0
     */
    private static function insert_default_data() {
        global $wpdb;

        // Inserisci impostazioni di default
        $table_impostazioni = $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni';

        $existing = $wpdb->get_var("SELECT COUNT(*) FROM $table_impostazioni");

        if ($existing == 0) {
            $default_template_conferma = "Ciao {nome_richiedente},\n\nLa tua prenotazione è stata confermata:\n\nAula: {nome_aula}\nData: {data_prenotazione}\nOrario: {ora_inizio} - {ora_fine}\nMotivo: {motivo}\n\nGrazie!";

            $default_template_rifiuto = "Ciao {nome_richiedente},\n\nSiamo spiacenti, la tua prenotazione è stata rifiutata:\n\nAula: {nome_aula}\nData: {data_prenotazione}\nOrario: {ora_inizio} - {ora_fine}\n\nMotivo rifiuto: {note_admin}\n\nGrazie per la comprensione.";

            $default_template_admin = "Nuova prenotazione ricevuta:\n\nRichiedente: {nome_richiedente} {cognome_richiedente}\nEmail: {email_richiedente}\nAula: {nome_aula}\nData: {data_prenotazione}\nOrario: {ora_inizio} - {ora_fine}\nMotivo: {motivo}\n\nGestisci: {link_gestione}";

            $wpdb->insert(
                $table_impostazioni,
                array(
                    'conferma_automatica' => false,
                    'email_notifica_admin' => json_encode(array(get_option('admin_email'))),
                    'template_email_conferma' => $default_template_conferma,
                    'template_email_rifiuto' => $default_template_rifiuto,
                    'template_email_admin' => $default_template_admin,
                    'giorni_prenotazione_futura_max' => 30,
                    'ore_anticipo_prenotazione_min' => 24,
                    'max_prenotazioni_per_utente_giorno' => 3,
                    'colore_slot_libero' => '#28a745',
                    'colore_slot_occupato' => '#dc3545',
                    'colore_slot_attesa' => '#ffc107'
                ),
                array('%d', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s')
            );
        }
    }

    /**
     * Imposta opzioni WordPress predefinite
     *
     * @since 1.0.0
     */
    private static function set_default_options() {
        add_option('prenotazione_aule_ssm_version', PRENOTAZIONE_AULE_SSM_VERSION);
        add_option('prenotazione_aule_ssm_installed_date', current_time('mysql'));
    }

    /**
     * Aggiunge capacità agli utenti amministratori
     *
     * @since 1.0.0
     */
    private static function add_capabilities() {
        $role = get_role('administrator');

        if ($role) {
            $role->add_cap('manage_prenotazione_aule_ssm');
            $role->add_cap('edit_prenotazione_aule_ssm');
            $role->add_cap('delete_prenotazione_aule_ssm');
            $role->add_cap('view_prenotazione_aule_ssm_reports');
        }
    }

    /**
     * Pianifica eventi cron
     *
     * @since 1.0.0
     */
    private static function schedule_cron_events() {
        // Evento per pulizia prenotazioni scadute (ogni giorno)
        if (!wp_next_scheduled('prenotazione_aule_ssm_cleanup_expired')) {
            wp_schedule_event(time(), 'daily', 'prenotazione_aule_ssm_cleanup_expired');
        }

        // Evento per reminder prenotazioni (ogni ora)
        if (!wp_next_scheduled('prenotazione_aule_ssm_send_reminders')) {
            wp_schedule_event(time(), 'hourly', 'prenotazione_aule_ssm_send_reminders');
        }

        // Evento per report settimanale admin (ogni settimana)
        if (!wp_next_scheduled('prenotazione_aule_ssm_weekly_report')) {
            wp_schedule_event(strtotime('next monday 9:00'), 'weekly', 'prenotazione_aule_ssm_weekly_report');
        }
    }
}