<?php

/**
 * La funzionalità specifica per l'area admin del plugin
 *
 * Definisce il nome del plugin, la versione, e due hook per
 * registrare i fogli di stile e JavaScript per l'area admin
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/admin
 */

class Prenotazione_Aule_SSM_Admin {

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
     * @var Prenotazione_Aule_SSM_Database $database
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
        $this->database = new Prenotazione_Aule_SSM_Database();
    }

    /**
     * Registra i fogli di stile per l'area admin
     *
     * @since 1.0.0
     * @param string $hook_suffix Il suffisso della pagina corrente
     */
    public function enqueue_styles($hook_suffix) {

        // Carica CSS solo nelle pagine del plugin
        if (strpos($hook_suffix, 'prenotazione-aule-ssm') === false) {
            return;
        }

        // Carica Dashicons (icone native WordPress)
        wp_enqueue_style('dashicons');

        wp_enqueue_style(
            $this->plugin_name . '-admin',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'admin/css/prenotazione-aule-ssm-admin.css',
            array(),
            $this->version,
            'all'
        );

        // Bootstrap per i modal - solo per dashboard, prenotazioni e slot
        if (in_array($hook_suffix, array('toplevel_page_prenotazione-aule-ssm', 'gestione-aule_page_prenotazione-aule-ssm-prenotazioni', 'gestione-aule_page_prenotazione-aule-ssm-slot'))) {
            wp_enqueue_style(
                'bootstrap-modal',
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
                array(),
                '5.3.0'
            );
        }

        // Font Awesome per le icone - NON sulla pagina Add Aula per evitare CSP
        if (!in_array($hook_suffix, array('gestione-aule_page_prenotazione-aule-ssm-add-aula'))) {
            wp_enqueue_style(
                'font-awesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                array(),
                '6.4.0'
            );
        }

        // FullCalendar CSS - solo nelle pagine che lo usano
        if (in_array($hook_suffix, array('toplevel_page_prenotazione-aule-ssm', 'gestione-aule_page_prenotazione-aule-ssm-prenotazioni'))) {
            wp_enqueue_style(
                'fullcalendar',
                'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css',
                array(),
                '6.1.8'
            );
        }

        // Chart.js CSS per pagina report
        if ($hook_suffix === 'gestione-aule_page_prenotazione-aule-ssm-reports') {
            wp_enqueue_style(
                'chartjs',
                'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.css',
                array(),
                '4.4.0'
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
        if (strpos($hook_suffix, 'prenotazione-aule-ssm') === false) {
            return;
        }

        wp_enqueue_script(
            $this->plugin_name . '-admin',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'admin/js/prenotazione-aule-ssm-admin.js?nocache=' . time(),
            array('jquery'),
            '2.0.0', // Version bump for cache busting
            true
        );

        // Media Uploader per la pagina aggiungi/modifica aula
        if (in_array($hook_suffix, array('gestione-aule_page_prenotazione-aule-ssm-add-aula'))) {
            wp_enqueue_media();
        }

        // Bootstrap JS per i modal - solo dove necessario (dashboard, prenotazioni, slot)
        if (in_array($hook_suffix, array('toplevel_page_prenotazione-aule-ssm', 'gestione-aule_page_prenotazione-aule-ssm-prenotazioni', 'gestione-aule_page_prenotazione-aule-ssm-slot'))) {
            wp_enqueue_script(
                'bootstrap-modal',
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
                array(),
                '5.3.0',
                true
            );
        }

        // Chart.js per pagina report
        if ($hook_suffix === 'gestione-aule_page_prenotazione-aule-ssm-reports') {
            wp_enqueue_script(
                'chartjs',
                'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
                array(),
                '4.4.0',
                true
            );
        }

        // FullCalendar JS - solo nelle pagine che lo usano
        if (in_array($hook_suffix, array('toplevel_page_prenotazione-aule-ssm', 'gestione-aule_page_prenotazione-aule-ssm-prenotazioni'))) {
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
            'prenotazione_aule_ssm_admin_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('prenotazione_aule_ssm_admin_nonce'),
                'strings' => array(
                    'confirm_delete' => __('Sei sicuro di voler eliminare questo elemento?', 'prenotazione-aule-ssm'),
                    'confirm_approve' => __('Confermare la prenotazione?', 'prenotazione-aule-ssm'),
                    'confirm_reject' => __('Rifiutare la prenotazione?', 'prenotazione-aule-ssm'),
                    'loading' => __('Caricamento...', 'prenotazione-aule-ssm'),
                    'error' => __('Si è verificato un errore', 'prenotazione-aule-ssm'),
                    'success' => __('Operazione completata', 'prenotazione-aule-ssm')
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
            __('Gestione Aule', 'prenotazione-aule-ssm'),                    // Titolo pagina
            __('Gestione Aule', 'prenotazione-aule-ssm'),                    // Titolo menu
            'manage_prenotazione_aule_ssm',                                   // Capability
            'prenotazione-aule-ssm',                                         // Menu slug
            array($this, 'display_dashboard_page'),                 // Callback
            'dashicons-calendar-alt',                               // Icona
            30                                                      // Posizione
        );

        // Sottomenu Dashboard
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('Dashboard', 'prenotazione-aule-ssm'),
            __('Dashboard', 'prenotazione-aule-ssm'),
            'manage_prenotazione_aule_ssm',
            'prenotazione-aule-ssm',
            array($this, 'display_dashboard_page')
        );

        // Sottomenu Aule
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('Tutte le Aule', 'prenotazione-aule-ssm'),
            __('Tutte le Aule', 'prenotazione-aule-ssm'),
            'manage_prenotazione_aule_ssm',
            'prenotazione-aule-ssm-aule',
            array($this, 'display_aule_page')
        );

        // Sottomenu Aggiungi Aula
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('Aggiungi Aula', 'prenotazione-aule-ssm'),
            __('Aggiungi Aula', 'prenotazione-aule-ssm'),
            'manage_prenotazione_aule_ssm',
            'prenotazione-aule-ssm-add-aula',
            array($this, 'display_add_aula_page')
        );

        // Sottomenu Prenotazioni
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('Prenotazioni', 'prenotazione-aule-ssm'),
            __('Prenotazioni', 'prenotazione-aule-ssm'),
            'manage_prenotazione_aule_ssm',
            'prenotazione-aule-ssm-prenotazioni',
            array($this, 'display_prenotazioni_page')
        );

        // Sottomenu Slot Disponibilità
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('Slot Disponibilità', 'prenotazione-aule-ssm'),
            __('Slot Disponibilità', 'prenotazione-aule-ssm'),
            'manage_prenotazione_aule_ssm',
            'prenotazione-aule-ssm-slot',
            array($this, 'display_slot_page')
        );

        // Sottomenu Impostazioni
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('Impostazioni', 'prenotazione-aule-ssm'),
            __('Impostazioni', 'prenotazione-aule-ssm'),
            'manage_prenotazione_aule_ssm',
            'prenotazione-aule-ssm-settings',
            array($this, 'display_settings_page')
        );

        // Sottomenu Personalizzazione Grafica
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('🎨 Personalizzazione', 'prenotazione-aule-ssm'),
            __('🎨 Personalizzazione', 'prenotazione-aule-ssm'),
            'manage_prenotazione_aule_ssm',
            'prenotazione-aule-ssm-customization',
            array($this, 'display_customization_page')
        );

        // Sottomenu Report
        add_submenu_page(
            'prenotazione-aule-ssm',
            __('Report', 'prenotazione-aule-ssm'),
            __('Report', 'prenotazione-aule-ssm'),
            'view_prenotazione_aule_ssm_reports',
            'prenotazione-aule-ssm-reports',
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

        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-dashboard.php';
    }

    /**
     * Mostra la pagina delle aule
     *
     * @since 1.0.0
     */
    public function display_aule_page() {
        $aule = $this->database->get_aule();
        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-aule.php';
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

        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-add-aula.php';
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

        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-prenotazioni.php';
    }

    /**
     * Mostra la pagina slot disponibilità
     *
     * @since 1.0.0
     */
    public function display_slot_page() {
        $aule = $this->database->get_aule(array('stato' => 'attiva'));
        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-slot.php';
    }

    /**
     * Mostra la pagina impostazioni
     *
     * @since 1.0.0
     */
    public function display_settings_page() {
        $settings = $this->database->get_impostazioni();
        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-settings.php';
    }

    /**
     * Mostra la pagina personalizzazione grafica
     *
     * @since 3.3.5
     */
    public function display_customization_page() {
        $customization = $this->get_customization_settings();
        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-customization.php';
    }

    /**
     * Ottieni impostazioni personalizzazione (con defaults)
     *
     * @since 3.3.5
     * @return array
     */
    private function get_customization_settings() {
        $defaults = array(
            // Colori
            'primary_color' => '#2271b1',
            'secondary_color' => '#72aee6',
            'success_color' => '#28a745',
            'warning_color' => '#ffc107',
            'danger_color' => '#dc3545',
            'light_color' => '#f8f9fa',
            'dark_color' => '#1d2327',
            'border_color' => '#ddd',

            // Typography
            'font_family' => 'system',
            'font_size_base' => '16',

            // Layout
            'border_radius' => '6',
            'shadow_intensity' => '100',
            'spacing_base' => '16',

            // Advanced
            'custom_css' => '',
            'use_animations' => true
        );

        $saved = get_option('prenotazione_aule_ssm_customization', array());

        return wp_parse_args($saved, $defaults);
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

        include_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'admin/partials/prenotazione-aule-ssm-admin-reports.php';
    }

    /**
     * Export dei report in formato CSV
     *
     * @since 1.0.0
     */
    private function export_reports_csv() {
        // Verifica permessi
        if (!current_user_can('view_prenotazione_aule_ssm_reports')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
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
            case 'future':
                $start_date = current_time('Y-m-d');
                $end_date = date('Y-m-d', strtotime('+30 days'));
                break;
            case 'all':
                $start_date = date('Y-m-d', strtotime('-2 years'));
                $end_date = date('Y-m-d', strtotime('+1 year'));
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
            FROM {$wpdb->prefix}prenotazione_aule_ssm_prenotazioni p
            LEFT JOIN {$wpdb->prefix}prenotazione_aule_ssm_aule a ON p.aula_id = a.id
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
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $aula_id = absint($_POST['aula_id']);

        if (!$aula_id) {
            wp_send_json_error(__('ID aula non valido', 'prenotazione-aule-ssm'));
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
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $booking_id = absint($_POST['booking_id']);
        $note_admin = sanitize_textarea_field($_POST['note_admin']);

        if (!$booking_id) {
            wp_send_json_error(__('ID prenotazione non valido', 'prenotazione-aule-ssm'));
        }

        $result = $this->database->update_prenotazione_stato(
            $booking_id,
            'confermata',
            get_current_user_id(),
            $note_admin
        );

        if ($result) {
            // Invia email di conferma
            $email_handler = new Prenotazione_Aule_SSM_Email();
            $email_handler->send_booking_confirmation($booking_id);

            wp_send_json_success(__('Prenotazione confermata', 'prenotazione-aule-ssm'));
        } else {
            wp_send_json_error(__('Errore nell\'aggiornamento della prenotazione', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Rifiuta prenotazione
     *
     * @since 1.0.0
     */
    public function ajax_reject_booking() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $booking_id = absint($_POST['booking_id']);
        $note_admin = sanitize_textarea_field($_POST['note_admin']);

        if (!$booking_id) {
            wp_send_json_error(__('ID prenotazione non valido', 'prenotazione-aule-ssm'));
        }

        if (empty($note_admin)) {
            wp_send_json_error(__('Il motivo del rifiuto è obbligatorio', 'prenotazione-aule-ssm'));
        }

        $result = $this->database->update_prenotazione_stato(
            $booking_id,
            'rifiutata',
            get_current_user_id(),
            $note_admin
        );

        if ($result) {
            // Invia email di rifiuto
            $email_handler = new Prenotazione_Aule_SSM_Email();
            $email_handler->send_booking_rejection($booking_id);

            wp_send_json_success(__('Prenotazione rifiutata', 'prenotazione-aule-ssm'));
        } else {
            wp_send_json_error(__('Errore nell\'aggiornamento della prenotazione', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Genera slot automaticamente
     *
     * @since 1.0.0
     */
    public function ajax_generate_slots() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $aula_id = absint($_POST['aula_id']);
        $giorni_settimana = isset($_POST['giorni_settimana']) && is_array($_POST['giorni_settimana']) ? array_map('absint', $_POST['giorni_settimana']) : array();
        $ora_inizio = sanitize_text_field($_POST['ora_inizio']);
        $ora_fine = sanitize_text_field($_POST['ora_fine']);
        $durata_slot = absint($_POST['durata_slot']);
        $data_inizio = sanitize_text_field($_POST['data_inizio']);
        $data_fine = sanitize_text_field($_POST['data_fine']);
        $ricorrenza = sanitize_text_field($_POST['ricorrenza']);

        if (!$aula_id || empty($giorni_settimana) || !$ora_inizio || !$ora_fine || !$durata_slot) {
            wp_send_json_error(__('Tutti i campi sono obbligatori', 'prenotazione-aule-ssm'));
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
            wp_send_json_success(sprintf(__('Creati %d slot', 'prenotazione-aule-ssm'), $slots_created));
        } else {
            wp_send_json_error(__('Nessun slot creato', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Elimina prenotazione
     *
     * @since 1.0.0
     */
    public function ajax_delete_booking() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $booking_id = absint($_POST['booking_id']);

        if (!$booking_id) {
            wp_send_json_error(__('ID prenotazione non valido', 'prenotazione-aule-ssm'));
        }

        $result = $this->database->update_prenotazione_stato($booking_id, 'cancellata', get_current_user_id());

        if ($result) {
            wp_send_json_success(__('Prenotazione cancellata', 'prenotazione-aule-ssm'));
        } else {
            wp_send_json_error(__('Errore nella cancellazione della prenotazione', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Bulk operations su prenotazioni (approva/rifiuta/elimina multiple)
     *
     * @since 3.3.4
     */
    public function ajax_bulk_bookings() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_send_json_error(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $bulk_action = sanitize_text_field($_POST['bulk_action']);
        $booking_ids = isset($_POST['booking_ids']) ? array_map('absint', $_POST['booking_ids']) : array();

        if (empty($booking_ids)) {
            wp_send_json_error(__('Nessuna prenotazione selezionata', 'prenotazione-aule-ssm'));
        }

        $success_count = 0;
        $error_count = 0;
        $email_handler = new Prenotazione_Aule_SSM_Email();

        foreach ($booking_ids as $booking_id) {
            $result = false;

            switch ($bulk_action) {
                case 'approve':
                    $result = $this->database->update_prenotazione_stato($booking_id, 'confermata', get_current_user_id(), '');
                    if ($result) {
                        // Send confirmation email
                        $email_handler->send_booking_confirmation($booking_id);
                    }
                    break;

                case 'reject':
                    $result = $this->database->update_prenotazione_stato($booking_id, 'rifiutata', get_current_user_id(), __('Rifiutata tramite operazione bulk', 'prenotazione-aule-ssm'));
                    if ($result) {
                        // Send rejection email
                        $email_handler->send_booking_rejection($booking_id);
                    }
                    break;

                case 'delete':
                    $result = $this->database->update_prenotazione_stato($booking_id, 'cancellata', get_current_user_id());
                    break;

                default:
                    wp_send_json_error(__('Azione non valida', 'prenotazione-aule-ssm'));
            }

            if ($result) {
                $success_count++;
            } else {
                $error_count++;
            }
        }

        if ($success_count > 0) {
            $message = '';
            switch ($bulk_action) {
                case 'approve':
                    $message = sprintf(__('%d prenotazioni approvate con successo', 'prenotazione-aule-ssm'), $success_count);
                    break;
                case 'reject':
                    $message = sprintf(__('%d prenotazioni rifiutate con successo', 'prenotazione-aule-ssm'), $success_count);
                    break;
                case 'delete':
                    $message = sprintf(__('%d prenotazioni eliminate con successo', 'prenotazione-aule-ssm'), $success_count);
                    break;
            }

            if ($error_count > 0) {
                $message .= sprintf(__(' (%d errori)', 'prenotazione-aule-ssm'), $error_count);
            }

            wp_send_json_success($message);
        } else {
            wp_send_json_error(__('Errore durante l\'operazione bulk', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Ottieni dettagli prenotazione per modal
     *
     * @since 3.3.3
     */
    public function ajax_get_booking_details() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_send_json_error(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $booking_id = absint($_POST['booking_id']);

        if (!$booking_id) {
            wp_send_json_error(__('ID prenotazione non valido', 'prenotazione-aule-ssm'));
        }

        $booking = $this->database->get_prenotazione_by_id($booking_id);

        if (!$booking) {
            wp_send_json_error(__('Prenotazione non trovata', 'prenotazione-aule-ssm'));
        }

        // Build HTML for modal content
        $html = '<table class="table table-bordered">';
        $html .= '<tbody>';

        $html .= '<tr>';
        $html .= '<th>' . __('Codice Prenotazione', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td><strong>' . esc_html($booking->codice_prenotazione) . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>' . __('Richiedente', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td>' . esc_html($booking->nome_richiedente . ' ' . $booking->cognome_richiedente) . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>' . __('Email', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td><a href="mailto:' . esc_attr($booking->email_richiedente) . '">' . esc_html($booking->email_richiedente) . '</a></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>' . __('Aula', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td>' . esc_html($booking->nome_aula) . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>' . __('Data', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td>' . esc_html(date_i18n('d/m/Y', strtotime($booking->data_prenotazione))) . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>' . __('Orario', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td>' . esc_html(date('H:i', strtotime($booking->ora_inizio)) . ' - ' . date('H:i', strtotime($booking->ora_fine))) . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>' . __('Stato', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td>';
        switch($booking->stato) {
            case 'in_attesa':
                $html .= '<span class="badge bg-warning text-dark">' . __('In Attesa', 'prenotazione-aule-ssm') . '</span>';
                break;
            case 'confermata':
                $html .= '<span class="badge bg-success">' . __('Confermata', 'prenotazione-aule-ssm') . '</span>';
                break;
            case 'rifiutata':
                $html .= '<span class="badge bg-danger">' . __('Rifiutata', 'prenotazione-aule-ssm') . '</span>';
                break;
            case 'cancellata':
                $html .= '<span class="badge bg-secondary">' . __('Cancellata', 'prenotazione-aule-ssm') . '</span>';
                break;
        }
        $html .= '</td>';
        $html .= '</tr>';

        if (!empty($booking->motivo_prenotazione)) {
            $html .= '<tr>';
            $html .= '<th>' . __('Motivo', 'prenotazione-aule-ssm') . '</th>';
            $html .= '<td>' . esc_html($booking->motivo_prenotazione) . '</td>';
            $html .= '</tr>';
        }

        if (!empty($booking->note_admin)) {
            $html .= '<tr>';
            $html .= '<th>' . __('Note Admin', 'prenotazione-aule-ssm') . '</th>';
            $html .= '<td>' . esc_html($booking->note_admin) . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<th>' . __('Data Creazione', 'prenotazione-aule-ssm') . '</th>';
        $html .= '<td>' . esc_html(date_i18n('d/m/Y H:i', strtotime($booking->created_at))) . '</td>';
        $html .= '</tr>';

        if (!empty($booking->updated_at)) {
            $html .= '<tr>';
            $html .= '<th>' . __('Ultima Modifica', 'prenotazione-aule-ssm') . '</th>';
            $html .= '<td>' . esc_html(date_i18n('d/m/Y H:i', strtotime($booking->updated_at))) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        wp_send_json_success($html);
    }

    /**
     * AJAX: Ottieni disponibilità per calendario
     *
     * @since 1.0.0
     */
    public function ajax_get_availability() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $aula_id = absint($_POST['aula_id']);
        $start_date = sanitize_text_field($_POST['start']);
        $end_date = sanitize_text_field($_POST['end']);

        if (!$aula_id) {
            wp_send_json_error(__('ID aula non valido', 'prenotazione-aule-ssm'));
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
        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        check_admin_referer('prenotazione_aule_ssm_settings_nonce');

        $settings_data = array(
            'conferma_automatica' => !empty($_POST['conferma_automatica']),
            'email_notifica_admin' => array_map('sanitize_email', explode(',', $_POST['email_notifica_admin'])),
            'conserva_dati_disinstallazione' => !empty($_POST['conserva_dati_disinstallazione']),
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
            wp_redirect(add_query_arg('updated', 'settings', admin_url('admin.php?page=prenotazione-aule-ssm-settings')));
        } else {
            wp_redirect(add_query_arg('error', 'settings', admin_url('admin.php?page=prenotazione-aule-ssm-settings')));
        }
        exit;
    }

    /**
     * Salva aula (nuovo o modifica)
     *
     * @since 1.0.0
     */
    public function save_aula() {
        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        check_admin_referer('prenotazione_aule_ssm_aula_nonce');

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
            wp_redirect(add_query_arg('error', 'nome_required', admin_url('admin.php?page=prenotazione-aule-ssm-add-aula')));
            exit;
        }

        $result = $this->database->save_aula($aula_data, $aula_id);

        if ($result) {
            wp_redirect(add_query_arg('updated', 'aula', admin_url('admin.php?page=prenotazione-aule-ssm-aule')));
        } else {
            wp_redirect(add_query_arg('error', 'save', admin_url('admin.php?page=prenotazione-aule-ssm-add-aula')));
        }
        exit;
    }

    /**
     * Elimina aula
     *
     * @since 1.0.0
     */
    public function delete_aula() {
        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_die(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        check_admin_referer('delete_aula_' . absint($_GET['id']));

        $aula_id = absint($_GET['id']);

        $result = $this->database->delete_aula($aula_id);

        if ($result) {
            wp_redirect(add_query_arg('deleted', 'aula', admin_url('admin.php?page=prenotazione-aule-ssm-aule')));
        } else {
            wp_redirect(add_query_arg('error', 'delete', admin_url('admin.php?page=prenotazione-aule-ssm-aule')));
        }
        exit;
    }

    /**
     * AJAX: Aggiorna slot
     *
     * @since 1.0.0
     */
    public function ajax_update_slot() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_send_json_error(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $slot_id = absint($_POST['slot_id']);
        $ora_inizio = sanitize_text_field($_POST['ora_inizio']);
        $ora_fine = sanitize_text_field($_POST['ora_fine']);
        $data_inizio = sanitize_text_field($_POST['data_inizio']);
        $data_fine = !empty($_POST['data_fine']) ? sanitize_text_field($_POST['data_fine']) : null;

        if (!$slot_id || !$ora_inizio || !$ora_fine || !$data_inizio) {
            wp_send_json_error(__('Dati non validi', 'prenotazione-aule-ssm'));
        }

        $slot_data = array(
            'ora_inizio' => $ora_inizio,
            'ora_fine' => $ora_fine,
            'data_inizio_validita' => $data_inizio,
            'data_fine_validita' => $data_fine
        );

        $result = $this->database->update_slot($slot_id, $slot_data);

        if ($result) {
            wp_send_json_success(__('Slot aggiornato correttamente', 'prenotazione-aule-ssm'));
        } else {
            wp_send_json_error(__('Errore nell\'aggiornamento dello slot', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Toggle stato slot (abilita/disabilita)
     *
     * @since 1.0.0
     */
    public function ajax_toggle_slot() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_send_json_error(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $slot_id = absint($_POST['slot_id']);
        $status = sanitize_text_field($_POST['status']); // 'enable' or 'disable'

        if (!$slot_id || !in_array($status, array('enable', 'disable'))) {
            wp_send_json_error(__('Dati non validi', 'prenotazione-aule-ssm'));
        }

        $attivo = ($status === 'enable') ? 1 : 0;
        $result = $this->database->update_slot($slot_id, array('attivo' => $attivo));

        if ($result) {
            $message = $attivo ? __('Slot abilitato', 'prenotazione-aule-ssm') : __('Slot disabilitato', 'prenotazione-aule-ssm');
            wp_send_json_success($message);
        } else {
            wp_send_json_error(__('Errore nell\'aggiornamento dello slot', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Elimina slot
     *
     * @since 1.0.0
     */
    public function ajax_delete_slot() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_send_json_error(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $slot_id = absint($_POST['slot_id']);

        if (!$slot_id) {
            wp_send_json_error(__('ID slot non valido', 'prenotazione-aule-ssm'));
        }

        $result = $this->database->delete_slot($slot_id);

        if ($result) {
            wp_send_json_success(__('Slot eliminato correttamente', 'prenotazione-aule-ssm'));
        } else {
            wp_send_json_error(__('Errore nell\'eliminazione dello slot', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Ottieni dati slot per editing
     *
     * @since 1.0.0
     */
    public function ajax_get_slot() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_send_json_error(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $slot_id = absint($_POST['slot_id']);

        if (!$slot_id) {
            wp_send_json_error(__('ID slot non valido', 'prenotazione-aule-ssm'));
        }

        $slot = $this->database->get_slot_by_id($slot_id);

        if ($slot) {
            wp_send_json_success($slot);
        } else {
            wp_send_json_error(__('Slot non trovato', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Operazioni bulk su slot
     *
     * @since 1.0.0
     */
    public function ajax_bulk_slots() {
        check_ajax_referer('prenotazione_aule_ssm_admin_nonce', 'nonce');

        if (!current_user_can('manage_prenotazione_aule_ssm')) {
            wp_send_json_error(__('Non hai i permessi per eseguire questa azione', 'prenotazione-aule-ssm'));
        }

        $action = sanitize_text_field($_POST['bulk_action']);
        $slot_ids = isset($_POST['slot_ids']) && is_array($_POST['slot_ids']) ? array_map('absint', $_POST['slot_ids']) : array();

        if (empty($action) || empty($slot_ids)) {
            wp_send_json_error(__('Parametri non validi', 'prenotazione-aule-ssm'));
        }

        $success_count = 0;
        $error_count = 0;

        switch ($action) {
            case 'enable':
                foreach ($slot_ids as $slot_id) {
                    if ($this->database->update_slot($slot_id, array('attivo' => 1))) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }
                $message = sprintf(__('%d slot abilitati', 'prenotazione-aule-ssm'), $success_count);
                break;

            case 'disable':
                foreach ($slot_ids as $slot_id) {
                    if ($this->database->update_slot($slot_id, array('attivo' => 0))) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }
                $message = sprintf(__('%d slot disabilitati', 'prenotazione-aule-ssm'), $success_count);
                break;

            case 'delete':
                if ($this->database->delete_slots($slot_ids)) {
                    $success_count = count($slot_ids);
                    $message = sprintf(__('%d slot eliminati', 'prenotazione-aule-ssm'), $success_count);
                } else {
                    wp_send_json_error(__('Errore nell\'eliminazione degli slot', 'prenotazione-aule-ssm'));
                }
                break;

            default:
                wp_send_json_error(__('Azione non valida', 'prenotazione-aule-ssm'));
        }

        if ($success_count > 0) {
            if ($error_count > 0) {
                $message .= sprintf(__(' (%d errori)', 'prenotazione-aule-ssm'), $error_count);
            }
            wp_send_json_success($message);
        } else {
            wp_send_json_error(__('Nessun slot modificato', 'prenotazione-aule-ssm'));
        }
    }
}