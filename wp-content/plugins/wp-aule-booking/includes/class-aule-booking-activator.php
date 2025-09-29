<?php

/**
 * Eseguito durante l'attivazione del plugin
 *
 * Questa classe definisce tutti i codici necessari per eseguire durante l'attivazione del plugin
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/includes
 */

class Aule_Booking_Activator {

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
     * @since 1.0.0
     */
    private static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Tabella aule
        $table_aule = $wpdb->prefix . 'aule_booking_aule';
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
        $table_slot = $wpdb->prefix . 'aule_booking_slot_disponibilita';
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
            KEY idx_attivo (attivo),
            FOREIGN KEY (aula_id) REFERENCES $table_aule(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Tabella prenotazioni
        $table_prenotazioni = $wpdb->prefix . 'aule_booking_prenotazioni';
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
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_codice (codice_prenotazione),
            KEY idx_aula_id (aula_id),
            KEY idx_email_richiedente (email_richiedente),
            KEY idx_data_prenotazione (data_prenotazione),
            KEY idx_stato (stato),
            KEY idx_created_at (created_at),
            FOREIGN KEY (aula_id) REFERENCES $table_aule(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Tabella impostazioni
        $table_impostazioni = $wpdb->prefix . 'aule_booking_impostazioni';
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
    }

    /**
     * Inserisce dati predefiniti
     *
     * @since 1.0.0
     */
    private static function insert_default_data() {
        global $wpdb;

        // Inserisci impostazioni di default
        $table_impostazioni = $wpdb->prefix . 'aule_booking_impostazioni';

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
        add_option('aule_booking_version', WP_AULE_BOOKING_VERSION);
        add_option('aule_booking_installed_date', current_time('mysql'));
    }

    /**
     * Aggiunge capacità agli utenti amministratori
     *
     * @since 1.0.0
     */
    private static function add_capabilities() {
        $role = get_role('administrator');

        if ($role) {
            $role->add_cap('manage_aule_booking');
            $role->add_cap('edit_aule_booking');
            $role->add_cap('delete_aule_booking');
            $role->add_cap('view_aule_booking_reports');
        }
    }

    /**
     * Pianifica eventi cron
     *
     * @since 1.0.0
     */
    private static function schedule_cron_events() {
        // Evento per pulizia prenotazioni scadute (ogni giorno)
        if (!wp_next_scheduled('aule_booking_cleanup_expired')) {
            wp_schedule_event(time(), 'daily', 'aule_booking_cleanup_expired');
        }

        // Evento per reminder prenotazioni (ogni ora)
        if (!wp_next_scheduled('aule_booking_send_reminders')) {
            wp_schedule_event(time(), 'hourly', 'aule_booking_send_reminders');
        }

        // Evento per report settimanale admin (ogni settimana)
        if (!wp_next_scheduled('aule_booking_weekly_report')) {
            wp_schedule_event(strtotime('next monday 9:00'), 'weekly', 'aule_booking_weekly_report');
        }
    }
}