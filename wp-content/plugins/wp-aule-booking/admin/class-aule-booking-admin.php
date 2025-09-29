<?php

/**
 * La funzionalità specifica per l'area admin del plugin
 *
 * Definisce il nome del plugin, la versione, e due hook per
 * registrare i fogli di stile e JavaScript per l'area admin
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/admin
 */

class Aule_Booking_Admin {

    /**
     * L'ID di questo plugin
     *
     * @since 1.0.0
     * @access private
     * @var string $plugin_name L'ID di questo plugin
     */
    private $plugin_name;

    /**
     * La versione di questo plugin
     *
     * @since 1.0.0
     * @access private
     * @var string $version La versione corrente di questo plugin
     */
    private $version;

    /**
     * L'istanza del database
     *
     * @since 1.0.0
     * @access private
     * @var Aule_Booking_Database $database
     */
    private $database;

    /**
     * Inizializza la classe e imposta le sue proprietà
     *
     * @since 1.0.0
     * @param string $plugin_name Il nome di questo plugin
     * @param string $version La versione di questo plugin
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->database = new Aule_Booking_Database();
    }

    /**
     * Registra i fogli di stile per l'area admin
     *
     * @since 1.0.0
     * @param string $hook_suffix Il suffisso della pagina corrente
     */
    public function enqueue_styles($hook_suffix) {

        // Carica CSS solo nelle pagine del plugin
        if (strpos($hook_suffix, 'aule-booking') === false) {
            return;
        }

        wp_enqueue_style(
            $this->plugin_name . '-admin',
            WP_AULE_BOOKING_PLUGIN_URL . 'admin/css/aule-booking-admin.css',
            array(),
            $this->version,
            'all'
        );

        // Bootstrap per i modal - solo per dashboard e prenotazioni
        if (in_array($hook_suffix, array('toplevel_page_aule-booking', 'gestione-aule_page_aule-booking-prenotazioni'))) {
            wp_enqueue_style(
                'bootstrap-modal',
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
                array(),
                '5.3.0'
            );
        }

        // Font Awesome per le icone - NON sulla pagina Add Aula per evitare CSP
        if (!in_array($hook_suffix, array('gestione-aule_page_aule-booking-add-aula'))) {
            wp_enqueue_style(
                'font-awesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                array(),
                '6.4.0'
            );
        }

        // FullCalendar CSS - solo nelle pagine che lo usano
        if (in_array($hook_suffix, array('toplevel_page_aule-booking', 'gestione-aule_page_aule-booking-prenotazioni'))) {
            wp_enqueue_style(
                'fullcalendar',
                'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css',
                array(),
                '6.1.8'
            );
        }
    }

    /**
     * Registra JavaScript per l'area admin
     *
     * @since 1.0.0
     * @param string $hook_suffix Il suffisso della pagina corrente
     */
    public function enqueue_scripts($hook_suffix) {

        // Carica JS solo nelle pagine del plugin
        if (strpos($hook_suffix, 'aule-booking') === false) {
            return;
        }

        wp_enqueue_script(
            $this->plugin_name . '-admin',
            WP_AULE_BOOKING_PLUGIN_URL . 'admin/js/aule-booking-admin.js',
            array('jquery'),
            $this->version,
            true
        );

        // Media Uploader per la pagina aggiungi/modifica aula
        if (in_array($hook_suffix, array('gestione-aule_page_aule-booking-add-aula'))) {
            wp_enqueue_media();
        }

        // Bootstrap JS per i modal - solo dove necessario
        if (in_array($hook_suffix, array('toplevel_page_aule-booking', 'gestione-aule_page_aule-booking-prenotazioni'))) {
            wp_enqueue_script(
                'bootstrap-modal',
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
                array(),
                '5.3.0',
                true
            );
        }

        // FullCalendar JS - solo nelle pagine che lo usano
        if (in_array($hook_suffix, array('toplevel_page_aule-booking', 'gestione-aule_page_aule-booking-prenotazioni'))) {
            wp_enqueue_script(
                'fullcalendar',
                'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js',
                array(),
                '6.1.8',
                true
            );
        }

        // Localizzazione per AJAX
        wp_localize_script(
            $this->plugin_name . '-admin',
            'aule_booking_admin_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aule_booking_admin_nonce'),
                'strings' => array(
                    'confirm_delete' => __('Sei sicuro di voler eliminare questo elemento?', 'aule-booking'),
                    'confirm_approve' => __('Confermare la prenotazione?', 'aule-booking'),
                    'confirm_reject' => __('Rifiutare la prenotazione?', 'aule-booking'),
                    'loading' => __('Caricamento...', 'aule-booking'),
                    'error' => __('Si è verificato un errore', 'aule-booking'),
                    'success' => __('Operazione completata', 'aule-booking')
                )
            )
        );
    }

    /**
     * Aggiunge il menu del plugin nell'area admin
     *
     * @since 1.0.0
     */
    public function add_plugin_admin_menu() {

        // Menu principale
        add_menu_page(
            __('Gestione Aule', 'aule-booking'),                    // Titolo pagina
            __('Gestione Aule', 'aule-booking'),                    // Titolo menu
            'manage_aule_booking',                                   // Capability
            'aule-booking',                                         // Menu slug
            array($this, 'display_dashboard_page'),                 // Callback
            'dashicons-calendar-alt',                               // Icona
            30                                                      // Posizione
        );

        // Sottomenu Dashboard
        add_submenu_page(
            'aule-booking',
            __('Dashboard', 'aule-booking'),
            __('Dashboard', 'aule-booking'),
            'manage_aule_booking',
            'aule-booking',
            array($this, 'display_dashboard_page')
        );

        // Sottomenu Aule
        add_submenu_page(
            'aule-booking',
            __('Tutte le Aule', 'aule-booking'),
            __('Tutte le Aule', 'aule-booking'),
            'manage_aule_booking',
            'aule-booking-aule',
            array($this, 'display_aule_page')
        );

        // Sottomenu Aggiungi Aula
        add_submenu_page(
            'aule-booking',
            __('Aggiungi Aula', 'aule-booking'),
            __('Aggiungi Aula', 'aule-booking'),
            'manage_aule_booking',
            'aule-booking-add-aula',
            array($this, 'display_add_aula_page')
        );

        // Sottomenu Prenotazioni
        add_submenu_page(
            'aule-booking',
            __('Prenotazioni', 'aule-booking'),
            __('Prenotazioni', 'aule-booking'),
            'manage_aule_booking',
            'aule-booking-prenotazioni',
            array($this, 'display_prenotazioni_page')
        );

        // Sottomenu Slot Disponibilità
        add_submenu_page(
            'aule-booking',
            __('Slot Disponibilità', 'aule-booking'),
            __('Slot Disponibilità', 'aule-booking'),
            'manage_aule_booking',
            'aule-booking-slot',
            array($this, 'display_slot_page')
        );

        // Sottomenu Impostazioni
        add_submenu_page(
            'aule-booking',
            __('Impostazioni', 'aule-booking'),
            __('Impostazioni', 'aule-booking'),
            'manage_aule_booking',
            'aule-booking-settings',
            array($this, 'display_settings_page')
        );

        // Sottomenu Report
        add_submenu_page(
            'aule-booking',
            __('Report', 'aule-booking'),
            __('Report', 'aule-booking'),
            'view_aule_booking_reports',
            'aule-booking-reports',
            array($this, 'display_reports_page')
        );
    }

    /**
     * PAGINE ADMIN
     */

    /**
     * Mostra la pagina dashboard
     *
     * @since 1.0.0
     */
    public function display_dashboard_page() {
        $stats = $this->database->get_dashboard_stats();
        $recent_bookings = $this->database->get_prenotazioni(array('limit' => 10));

        include_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/partials/aule-booking-admin-dashboard.php';
    }

    /**
     * Mostra la pagina delle aule
     *
     * @since 1.0.0
     */
    public function display_aule_page() {
        $aule = $this->database->get_aule();
        include_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/partials/aule-booking-admin-aule.php';
    }

    /**
     * Mostra la pagina aggiungi/modifica aula
     *
     * @since 1.0.0
     */
    public function display_add_aula_page() {
        $aula = null;
        $is_edit = false;

        if (!empty($_GET['edit']) && !empty($_GET['id'])) {
            $aula_id = absint($_GET['id']);
            $aula = $this->database->get_aula_by_id($aula_id);
            $is_edit = true;
        }

        include_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/partials/aule-booking-admin-add-aula.php';
    }

    /**
     * Mostra la pagina prenotazioni
     *
     * @since 1.0.0
     */
    public function display_prenotazioni_page() {
        $filters = array();

        // Gestisci filtri dalla query string
        if (!empty($_GET['stato'])) {
            $filters['stato'] = sanitize_text_field($_GET['stato']);
        }
        if (!empty($_GET['aula_id'])) {
            $filters['aula_id'] = absint($_GET['aula_id']);
        }
        if (!empty($_GET['data_da'])) {
            $filters['data_da'] = sanitize_text_field($_GET['data_da']);
        }
        if (!empty($_GET['data_a'])) {
            $filters['data_a'] = sanitize_text_field($_GET['data_a']);
        }
        if (!empty($_GET['search'])) {
            $filters['search'] = sanitize_text_field($_GET['search']);
        }

        $prenotazioni = $this->database->get_prenotazioni($filters);
        $aule = $this->database->get_aule(array('stato' => 'attiva'));

        include_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/partials/aule-booking-admin-prenotazioni.php';
    }

    /**
     * Mostra la pagina slot disponibilità
     *
     * @since 1.0.0
     */
    public function display_slot_page() {
        $aule = $this->database->get_aule(array('stato' => 'attiva'));
        include_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/partials/aule-booking-admin-slot.php';
    }

    /**
     * Mostra la pagina impostazioni
     *
     * @since 1.0.0
     */
    public function display_settings_page() {
        $settings = $this->database->get_impostazioni();
        include_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/partials/aule-booking-admin-settings.php';
    }

    /**
     * Mostra la pagina report
     *
     * @since 1.0.0
     */
    public function display_reports_page() {
        // Gestisci export CSV se richiesto
        if (isset($_GET['export']) && $_GET['export'] === 'csv' && isset($_GET['action']) && $_GET['action'] === 'export_reports') {
            $this->export_reports_csv();
            return;
        }

        include_once WP_AULE_BOOKING_PLUGIN_DIR . 'admin/partials/aule-booking-admin-reports.php';
    }

    /**
     * Export dei report in formato CSV
     *
     * @since 1.0.0
     */
    private function export_reports_csv() {
        // Verifica permessi
        if (!current_user_can('view_aule_booking_reports')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        global $wpdb;

        // Parametri per i filtri (stessi del template report)
        $periodo = isset($_GET['periodo']) ? sanitize_text_field($_GET['periodo']) : '30';
        $aula_id = isset($_GET['aula_id']) ? intval($_GET['aula_id']) : '';
        $stato = isset($_GET['stato']) ? sanitize_text_field($_GET['stato']) : '';

        // Calcola le date per il periodo selezionato
        $end_date = current_time('Y-m-d');
        switch ($periodo) {
            case '7':
                $start_date = date('Y-m-d', strtotime('-7 days'));
                break;
            case '30':
                $start_date = date('Y-m-d', strtotime('-30 days'));
                break;
            case '90':
                $start_date = date('Y-m-d', strtotime('-90 days'));
                break;
            case '365':
                $start_date = date('Y-m-d', strtotime('-1 year'));
                break;
            case 'custom':
                $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
                $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : current_time('Y-m-d');
                break;
            default:
                $start_date = date('Y-m-d', strtotime('-30 days'));
        }

        // Query per dati dettagliati
        $where_conditions = array("p.data_prenotazione BETWEEN %s AND %s");
        $query_params = array($start_date, $end_date);

        if (!empty($aula_id)) {
            $where_conditions[] = "p.aula_id = %d";
            $query_params[] = $aula_id;
        }

        if (!empty($stato)) {
            $where_conditions[] = "p.stato = %s";
            $query_params[] = $stato;
        }

        $where_clause = implode(' AND ', $where_conditions);

        $export_query = $wpdb->prepare("
            SELECT
                p.codice_prenotazione as 'Codice',
                a.nome_aula as 'Aula',
                p.nome_richiedente as 'Nome',
                p.cognome_richiedente as 'Cognome',
                p.email_richiedente as 'Email',
                p.data_prenotazione as 'Data',
                p.ora_inizio as 'Ora Inizio',
                p.ora_fine as 'Ora Fine',
                p.stato as 'Stato',
                p.motivo_prenotazione as 'Motivo',
                p.note_admin as 'Note Admin',
                p.created_at as 'Creata il'
            FROM {$wpdb->prefix}aule_booking_prenotazioni p
            LEFT JOIN {$wpdb->prefix}aule_booking_aule a ON p.aula_id = a.id
            WHERE $where_clause
            ORDER BY p.created_at DESC
        ", $query_params);

        $results = $wpdb->get_results($export_query, ARRAY_A);

        // Imposta headers per download CSV
        $filename = 'report-prenotazioni-' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Output CSV
        $output = fopen('php://output', 'w');

        // BOM per UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header CSV
        if (!empty($results)) {
            fputcsv($output, array_keys($results[0]), ';');

            // Righe dati
            foreach ($results as $row) {
                fputcsv($output, $row, ';');
            }
        } else {
            fputcsv($output, array('Messaggio'), ';');
            fputcsv($output, array('Nessun dato disponibile per il periodo selezionato'), ';');
        }

        fclose($output);
        exit;
    }

    /**
     * AJAX HANDLERS
     */

    /**
     * AJAX: Ottieni slot per aula
     *
     * @since 1.0.0
     */
    public function ajax_get_slots() {
        check_ajax_referer('aule_booking_admin_nonce', 'nonce');

        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        $aula_id = absint($_POST['aula_id']);

        if (!$aula_id) {
            wp_send_json_error(__('ID aula non valido', 'aule-booking'));
        }

        $slots = $this->database->get_slot_disponibilita($aula_id);

        wp_send_json_success($slots);
    }

    /**
     * AJAX: Approva prenotazione
     *
     * @since 1.0.0
     */
    public function ajax_approve_booking() {
        check_ajax_referer('aule_booking_admin_nonce', 'nonce');

        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        $booking_id = absint($_POST['booking_id']);
        $note_admin = sanitize_textarea_field($_POST['note_admin']);

        if (!$booking_id) {
            wp_send_json_error(__('ID prenotazione non valido', 'aule-booking'));
        }

        $result = $this->database->update_prenotazione_stato(
            $booking_id,
            'confermata',
            get_current_user_id(),
            $note_admin
        );

        if ($result) {
            // Invia email di conferma
            $email_handler = new Aule_Booking_Email();
            $email_handler->send_booking_confirmation($booking_id);

            wp_send_json_success(__('Prenotazione confermata', 'aule-booking'));
        } else {
            wp_send_json_error(__('Errore nell\'aggiornamento della prenotazione', 'aule-booking'));
        }
    }

    /**
     * AJAX: Rifiuta prenotazione
     *
     * @since 1.0.0
     */
    public function ajax_reject_booking() {
        check_ajax_referer('aule_booking_admin_nonce', 'nonce');

        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        $booking_id = absint($_POST['booking_id']);
        $note_admin = sanitize_textarea_field($_POST['note_admin']);

        if (!$booking_id) {
            wp_send_json_error(__('ID prenotazione non valido', 'aule-booking'));
        }

        if (empty($note_admin)) {
            wp_send_json_error(__('Il motivo del rifiuto è obbligatorio', 'aule-booking'));
        }

        $result = $this->database->update_prenotazione_stato(
            $booking_id,
            'rifiutata',
            get_current_user_id(),
            $note_admin
        );

        if ($result) {
            // Invia email di rifiuto
            $email_handler = new Aule_Booking_Email();
            $email_handler->send_booking_rejection($booking_id);

            wp_send_json_success(__('Prenotazione rifiutata', 'aule-booking'));
        } else {
            wp_send_json_error(__('Errore nell\'aggiornamento della prenotazione', 'aule-booking'));
        }
    }

    /**
     * AJAX: Genera slot automaticamente
     *
     * @since 1.0.0
     */
    public function ajax_generate_slots() {
        check_ajax_referer('aule_booking_admin_nonce', 'nonce');

        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        $aula_id = absint($_POST['aula_id']);
        $giorni_settimana = array_map('absint', $_POST['giorni_settimana']);
        $ora_inizio = sanitize_text_field($_POST['ora_inizio']);
        $ora_fine = sanitize_text_field($_POST['ora_fine']);
        $durata_slot = absint($_POST['durata_slot']);
        $data_inizio = sanitize_text_field($_POST['data_inizio']);
        $data_fine = sanitize_text_field($_POST['data_fine']);
        $ricorrenza = sanitize_text_field($_POST['ricorrenza']);

        if (!$aula_id || empty($giorni_settimana) || !$ora_inizio || !$ora_fine || !$durata_slot) {
            wp_send_json_error(__('Tutti i campi sono obbligatori', 'aule-booking'));
        }

        $slots_created = 0;

        foreach ($giorni_settimana as $giorno) {
            // Genera slot per il giorno
            $current_time = strtotime($ora_inizio);
            $end_time = strtotime($ora_fine);

            while ($current_time + ($durata_slot * 60) <= $end_time) {
                $slot_ora_inizio = date('H:i:s', $current_time);
                $slot_ora_fine = date('H:i:s', $current_time + ($durata_slot * 60));

                $slot_data = array(
                    'aula_id' => $aula_id,
                    'giorno_settimana' => $giorno,
                    'ora_inizio' => $slot_ora_inizio,
                    'ora_fine' => $slot_ora_fine,
                    'durata_slot_minuti' => $durata_slot,
                    'data_inizio_validita' => $data_inizio,
                    'data_fine_validita' => $data_fine,
                    'ricorrenza' => $ricorrenza
                );

                if ($this->database->insert_slot($slot_data)) {
                    $slots_created++;
                }

                $current_time += ($durata_slot * 60);
            }
        }

        if ($slots_created > 0) {
            wp_send_json_success(sprintf(__('Creati %d slot', 'aule-booking'), $slots_created));
        } else {
            wp_send_json_error(__('Nessun slot creato', 'aule-booking'));
        }
    }

    /**
     * AJAX: Elimina prenotazione
     *
     * @since 1.0.0
     */
    public function ajax_delete_booking() {
        check_ajax_referer('aule_booking_admin_nonce', 'nonce');

        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        $booking_id = absint($_POST['booking_id']);

        if (!$booking_id) {
            wp_send_json_error(__('ID prenotazione non valido', 'aule-booking'));
        }

        $result = $this->database->update_prenotazione_stato($booking_id, 'cancellata', get_current_user_id());

        if ($result) {
            wp_send_json_success(__('Prenotazione cancellata', 'aule-booking'));
        } else {
            wp_send_json_error(__('Errore nella cancellazione della prenotazione', 'aule-booking'));
        }
    }

    /**
     * AJAX: Ottieni disponibilità per calendario
     *
     * @since 1.0.0
     */
    public function ajax_get_availability() {
        check_ajax_referer('aule_booking_admin_nonce', 'nonce');

        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        $aula_id = absint($_POST['aula_id']);
        $start_date = sanitize_text_field($_POST['start']);
        $end_date = sanitize_text_field($_POST['end']);

        if (!$aula_id) {
            wp_send_json_error(__('ID aula non valido', 'aule-booking'));
        }

        // Ottieni prenotazioni nel periodo
        $prenotazioni = $this->database->get_prenotazioni(array(
            'aula_id' => $aula_id,
            'data_da' => $start_date,
            'data_a' => $end_date
        ));

        $events = array();

        foreach ($prenotazioni as $prenotazione) {
            $color = '#dc3545'; // Rosso per occupato
            if ($prenotazione->stato === 'in_attesa') {
                $color = '#ffc107'; // Giallo per in attesa
            } elseif ($prenotazione->stato === 'confermata') {
                $color = '#28a745'; // Verde per confermato
            }

            $events[] = array(
                'id' => $prenotazione->id,
                'title' => $prenotazione->nome_richiedente . ' ' . $prenotazione->cognome_richiedente,
                'start' => $prenotazione->data_prenotazione . 'T' . $prenotazione->ora_inizio,
                'end' => $prenotazione->data_prenotazione . 'T' . $prenotazione->ora_fine,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => array(
                    'email' => $prenotazione->email_richiedente,
                    'motivo' => $prenotazione->motivo_prenotazione,
                    'stato' => $prenotazione->stato,
                    'note_admin' => $prenotazione->note_admin
                )
            );
        }

        wp_send_json_success($events);
    }

    /**
     * SALVATAGGIO DATI
     */

    /**
     * Salva le impostazioni del plugin
     *
     * @since 1.0.0
     */
    public function save_settings() {
        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        check_admin_referer('aule_booking_settings_nonce');

        $settings_data = array(
            'conferma_automatica' => !empty($_POST['conferma_automatica']),
            'email_notifica_admin' => array_map('sanitize_email', explode(',', $_POST['email_notifica_admin'])),
            'template_email_conferma' => wp_kses_post($_POST['template_email_conferma']),
            'template_email_rifiuto' => wp_kses_post($_POST['template_email_rifiuto']),
            'template_email_admin' => wp_kses_post($_POST['template_email_admin']),
            'giorni_prenotazione_futura_max' => absint($_POST['giorni_prenotazione_futura_max']),
            'ore_anticipo_prenotazione_min' => absint($_POST['ore_anticipo_prenotazione_min']),
            'max_prenotazioni_per_utente_giorno' => absint($_POST['max_prenotazioni_per_utente_giorno']),
            'abilita_recaptcha' => !empty($_POST['abilita_recaptcha']),
            'recaptcha_site_key' => sanitize_text_field($_POST['recaptcha_site_key']),
            'recaptcha_secret_key' => sanitize_text_field($_POST['recaptcha_secret_key']),
            'colore_slot_libero' => sanitize_text_field($_POST['colore_slot_libero']),
            'colore_slot_occupato' => sanitize_text_field($_POST['colore_slot_occupato']),
            'colore_slot_attesa' => sanitize_text_field($_POST['colore_slot_attesa'])
        );

        $result = $this->database->update_impostazioni($settings_data);

        if ($result) {
            wp_redirect(add_query_arg('updated', 'settings', admin_url('admin.php?page=aule-booking-settings')));
        } else {
            wp_redirect(add_query_arg('error', 'settings', admin_url('admin.php?page=aule-booking-settings')));
        }
        exit;
    }

    /**
     * Salva aula (nuovo o modifica)
     *
     * @since 1.0.0
     */
    public function save_aula() {
        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        check_admin_referer('aule_booking_aula_nonce');

        $aula_id = !empty($_POST['aula_id']) ? absint($_POST['aula_id']) : null;

        $aula_data = array(
            'nome_aula' => sanitize_text_field($_POST['nome_aula']),
            'descrizione' => wp_kses_post($_POST['descrizione']),
            'capienza' => absint($_POST['capienza']),
            'ubicazione' => sanitize_text_field($_POST['ubicazione']),
            'attrezzature' => !empty($_POST['attrezzature']) ? $_POST['attrezzature'] : array(),
            'stato' => sanitize_text_field($_POST['stato']),
            'immagini' => !empty($_POST['immagini']) ? $_POST['immagini'] : array()
        );

        if (empty($aula_data['nome_aula'])) {
            wp_redirect(add_query_arg('error', 'nome_required', admin_url('admin.php?page=aule-booking-add-aula')));
            exit;
        }

        $result = $this->database->save_aula($aula_data, $aula_id);

        if ($result) {
            wp_redirect(add_query_arg('updated', 'aula', admin_url('admin.php?page=aule-booking-aule')));
        } else {
            wp_redirect(add_query_arg('error', 'save', admin_url('admin.php?page=aule-booking-add-aula')));
        }
        exit;
    }

    /**
     * Elimina aula
     *
     * @since 1.0.0
     */
    public function delete_aula() {
        if (!current_user_can('manage_aule_booking')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'aule-booking'));
        }

        check_admin_referer('delete_aula_' . absint($_GET['id']));

        $aula_id = absint($_GET['id']);

        $result = $this->database->delete_aula($aula_id);

        if ($result) {
            wp_redirect(add_query_arg('deleted', 'aula', admin_url('admin.php?page=aule-booking-aule')));
        } else {
            wp_redirect(add_query_arg('error', 'delete', admin_url('admin.php?page=aule-booking-aule')));
        }
        exit;
    }
}