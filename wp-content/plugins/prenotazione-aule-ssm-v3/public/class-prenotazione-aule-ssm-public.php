<?php

/**
 * La funzionalità specifica per l'area public del plugin
 *
 * Definisce il nome del plugin, la versione, e due hook per
 * registrare i fogli di stile e JavaScript per l'area public
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/public
 */

class Prenotazione_Aule_SSM_Public {

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
     * Registra i fogli di stile per l'area public
     *
     * @since 1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style(
            $this->plugin_name . '-public',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/css/prenotazione-aule-ssm-public.css',
            array(),
            $this->version,
            'all'
        );

        // Bootstrap per i modal
        wp_enqueue_style(
            'bootstrap-modal',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
            array(),
            '5.3.0'
        );

        // Font Awesome per le icone
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            array(),
            '6.4.0'
        );

        // FullCalendar CSS
        wp_enqueue_style(
            'fullcalendar',
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css',
            array(),
            '6.1.8'
        );
    }

    /**
     * Registra JavaScript per l'area public
     *
     * @since 1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script(
            $this->plugin_name . '-public',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/js/prenotazione-aule-ssm-public.js',
            array('jquery'),
            $this->version,
            true
        );

        // Bootstrap JS per i modal
        wp_enqueue_script(
            'bootstrap-modal',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
            array(),
            '5.3.0',
            true
        );

        // FullCalendar JS
        wp_enqueue_script(
            'fullcalendar',
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js',
            array(),
            '6.1.8',
            true
        );

        // Ottieni impostazioni
        $settings = $this->database->get_impostazioni();

        // Localizzazione per AJAX e configurazioni
        wp_localize_script(
            $this->plugin_name . '-public',
            'prenotazione_aule_ssm_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('prenotazione_aule_ssm_public_nonce'),
                'settings' => array(
                    'recaptcha_enabled' => !empty($settings->abilita_recaptcha),
                    'recaptcha_site_key' => !empty($settings->recaptcha_site_key) ? $settings->recaptcha_site_key : '',
                    'colors' => array(
                        'available' => !empty($settings->colore_slot_libero) ? $settings->colore_slot_libero : '#28a745',
                        'booked' => !empty($settings->colore_slot_occupato) ? $settings->colore_slot_occupato : '#dc3545',
                        'pending' => !empty($settings->colore_slot_attesa) ? $settings->colore_slot_attesa : '#ffc107'
                    ),
                    'max_future_days' => !empty($settings->giorni_prenotazione_futura_max) ? $settings->giorni_prenotazione_futura_max : 30,
                    'min_hours_advance' => !empty($settings->ore_anticipo_prenotazione_min) ? $settings->ore_anticipo_prenotazione_min : 24
                ),
                'strings' => array(
                    'loading' => __('Caricamento...', 'prenotazione-aule-ssm'),
                    'error' => __('Si è verificato un errore', 'prenotazione-aule-ssm'),
                    'success' => __('Operazione completata', 'prenotazione-aule-ssm'),
                    'confirm_booking' => __('Confermare la prenotazione?', 'prenotazione-aule-ssm'),
                    'booking_sent' => __('Prenotazione inviata correttamente', 'prenotazione-aule-ssm'),
                    'fill_required_fields' => __('Compila tutti i campi obbligatori', 'prenotazione-aule-ssm'),
                    'invalid_email' => __('Indirizzo email non valido', 'prenotazione-aule-ssm'),
                    'slot_unavailable' => __('Slot non più disponibile', 'prenotazione-aule-ssm'),
                    'select_slot' => __('Seleziona uno slot', 'prenotazione-aule-ssm'),
                    'privacy_required' => __('Devi accettare l\'informativa privacy', 'prenotazione-aule-ssm'),
                    'recaptcha_required' => __('Completa la verifica reCAPTCHA', 'prenotazione-aule-ssm')
                )
            )
        );

        // Carica reCAPTCHA se abilitato
        if (!empty($settings->abilita_recaptcha) && !empty($settings->recaptcha_site_key)) {
            wp_enqueue_script(
                'google-recaptcha',
                'https://www.google.com/recaptcha/api.js?render=' . $settings->recaptcha_site_key,
                array(),
                null,
                true
            );
        }
    }

    /**
     * Registra gli shortcodes
     *
     * @since 1.0.0
     */
    public function register_shortcodes() {
        add_shortcode('prenotazione_aule_ssm_calendar', array($this, 'shortcode_calendar'));
        add_shortcode('prenotazione_aule_ssm_new_calendar', array($this, 'shortcode_new_calendar'));
        add_shortcode('prenotazione_aule_ssm_list', array($this, 'shortcode_aule_list'));
        add_shortcode('prenotazione_aule_ssm_search', array($this, 'shortcode_search_form'));
    }

    /**
     * Shortcode per il calendario delle prenotazioni
     *
     * @since 1.0.0
     * @param array $atts Attributi dello shortcode
     * @return string HTML del calendario
     */
    public function shortcode_calendar($atts) {
        $atts = shortcode_atts(array(
            'aula_id' => '',
            'view' => 'month',
            'show_legend' => 'true',
            'allow_booking' => 'true',
            'height' => '600'
        ), $atts, 'prenotazione_aule_ssm_calendar');

        // Validazione aula_id
        if (empty($atts['aula_id']) || !is_numeric($atts['aula_id'])) {
            return '<p class="prenotazione-aule-ssm-error">' . __('ID aula non valido', 'prenotazione-aule-ssm') . '</p>';
        }

        $aula_id = absint($atts['aula_id']);
        $aula = $this->database->get_aula_by_id($aula_id);

        if (!$aula || $aula->stato !== 'attiva') {
            return '<p class="prenotazione-aule-ssm-error">' . __('Aula non disponibile', 'prenotazione-aule-ssm') . '</p>';
        }

        // Genera HTML del calendario
        ob_start();
        include PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'public/partials/prenotazione-aule-ssm-calendar.php';
        return ob_get_clean();
    }

    /**
     * Shortcode per il nuovo calendario stile Calendly
     *
     * @since 1.0.0
     * @param array $atts Attributi dello shortcode
     * @return string HTML del calendario
     */
    public function shortcode_new_calendar($atts) {
        $atts = shortcode_atts(array(
            'aula_id' => '',
            'show_legend' => 'true',
            'allow_booking' => 'true'
        ), $atts, 'prenotazione_aule_ssm_new_calendar');

        // Validazione aula_id
        if (empty($atts['aula_id']) || !is_numeric($atts['aula_id'])) {
            return '<p class="prenotazione-aule-ssm-error">' . __('ID aula non valido', 'prenotazione-aule-ssm') . '</p>';
        }

        $aula_id = absint($atts['aula_id']);
        $aula = $this->database->get_aula_by_id($aula_id);

        if (!$aula || $aula->stato !== 'attiva') {
            return '<p class="prenotazione-aule-ssm-error">' . __('Aula non disponibile', 'prenotazione-aule-ssm') . '</p>';
        }

        // Enqueue CSS base calendario
        wp_enqueue_style(
            $this->plugin_name . '-new-calendar-base',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/css/aule-booking-new-calendar.css',
            array(),
            $this->version . '.' . time(),
            'all'
        );

        // Enqueue CSS multi-slot
        wp_enqueue_style(
            $this->plugin_name . '-multi-slot',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/css/prenotazione-aule-ssm-multi-slot.css',
            array($this->plugin_name . '-new-calendar-base'),
            $this->version . '.' . time(),
            'all'
        );

        // Enqueue Bootstrap 5 per modale
        wp_enqueue_style(
            'bootstrap-5-css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
            array(),
            '5.3.0'
        );

        wp_enqueue_script(
            'bootstrap-5-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
            array('jquery'),
            '5.3.0',
            true
        );

        // Enqueue JS calendario
        wp_enqueue_script(
            $this->plugin_name . '-new-calendar',
            PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/js/prenotazione-aule-ssm-new-calendar.js',
            array('jquery', 'bootstrap-5-js'),
            $this->version,
            true
        );

        // Localize script con variabile corretta
        wp_localize_script(
            $this->plugin_name . '-new-calendar',
            'prenotazioneAuleSSMData',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('prenotazione_aule_ssm_public_nonce'),
                'multiBookingNonce' => wp_create_nonce('prenotazione_aule_ssm_multi_booking')
            )
        );

        // Genera HTML del calendario
        ob_start();
        include PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'public/partials/prenotazione-aule-ssm-new-calendar.php';
        return ob_get_clean();
    }

    /**
     * Shortcode per la lista delle aule
     *
     * @since 1.0.0
     * @param array $atts Attributi dello shortcode
     * @return string HTML della lista aule
     */
    public function shortcode_aule_list($atts) {
        $atts = shortcode_atts(array(
            'stato' => 'attiva',
            'ubicazione' => '',
            'show_details' => 'true',
            'show_booking_link' => 'true'
        ), $atts, 'prenotazione_aule_ssm_list');

        $filters = array();
        if (!empty($atts['stato'])) {
            $filters['stato'] = $atts['stato'];
        }
        if (!empty($atts['ubicazione'])) {
            $filters['ubicazione'] = $atts['ubicazione'];
        }

        $aule = $this->database->get_aule($filters);

        ob_start();
        include PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'public/partials/prenotazione-aule-ssm-list.php';
        return ob_get_clean();
    }

    /**
     * Shortcode per il form di ricerca aule
     *
     * @since 1.0.0
     * @param array $atts Attributi dello shortcode
     * @return string HTML del form di ricerca
     */
    public function shortcode_search_form($atts) {
        $atts = shortcode_atts(array(
            'show_filters' => 'true',
            'redirect_to' => ''
        ), $atts, 'prenotazione_aule_ssm_search');

        ob_start();
        include PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'public/partials/prenotazione-aule-ssm-search.php';
        return ob_get_clean();
    }

    /**
     * AJAX HANDLERS
     */

    /**
     * AJAX: Ottieni date disponibili per il mese corrente (NEW_CALENDAR)
     *
     * @since 1.0.0
     */
    public function ajax_get_available_dates() {
        check_ajax_referer('prenotazione_aule_ssm_public_nonce', 'nonce');

        $aula_id = absint($_POST['aula_id']);
        $year = absint($_POST['year']);
        $month = absint($_POST['month']);

        if (!$aula_id || !$year || !$month) {
            wp_send_json_error(__('Parametri non validi', 'prenotazione-aule-ssm'));
        }

        // Ottieni aula
        $aula = $this->database->get_aula_by_id($aula_id);
        if (!$aula || $aula->stato !== 'attiva') {
            wp_send_json_error(__('Aula non disponibile', 'prenotazione-aule-ssm'));
        }

        // Calcola primo e ultimo giorno del mese
        $first_day = sprintf('%04d-%02d-01', $year, $month);
        $last_day = date('Y-m-t', strtotime($first_day));

        // Ottieni slot disponibilità per l'aula
        $slots_disponibilita = $this->database->get_slot_disponibilita($aula_id, array(
            'data_inizio' => $first_day,
            'data_fine' => $last_day
        ));

        // Ottieni prenotazioni nel periodo
        $prenotazioni = $this->database->get_prenotazioni(array(
            'aula_id' => $aula_id,
            'data_da' => $first_day,
            'data_a' => $last_day
        ));

        // Trova date con almeno uno slot disponibile
        $available_dates = array();
        $settings = $this->database->get_impostazioni();

        $current_date = new DateTime($first_day);
        $end_date = new DateTime($last_day);

        while ($current_date <= $end_date) {
            $day_of_week = $current_date->format('N'); // 1=Monday, 7=Sunday
            $date_string = $current_date->format('Y-m-d');

            // Controlla se c'è almeno uno slot disponibile per questo giorno
            foreach ($slots_disponibilita as $slot) {
                if ($slot->giorno_settimana == $day_of_week) {
                    // Verifica se la data è nel range di validità
                    if ($date_string >= $slot->data_inizio_validita &&
                        (is_null($slot->data_fine_validita) || $date_string <= $slot->data_fine_validita)) {

                        // Verifica se c'è almeno uno slot libero
                        if ($this->has_available_slots($aula_id, $date_string, $slot, $prenotazioni, $settings)) {
                            $available_dates[] = $date_string;
                            break; // Trovato almeno uno slot disponibile, passa al giorno successivo
                        }
                    }
                }
            }

            $current_date->add(new DateInterval('P1D'));
        }

        wp_send_json_success($available_dates);
    }

    /**
     * Controlla se una data ha almeno uno slot disponibile
     *
     * @since 1.0.0
     * @param int $aula_id
     * @param string $date_string
     * @param object $slot_config
     * @param array $prenotazioni
     * @param object $settings
     * @return bool
     */
    private function has_available_slots($aula_id, $date_string, $slot_config, $prenotazioni, $settings) {
        $slot_start = new DateTime($date_string . ' ' . $slot_config->ora_inizio);
        $slot_end = new DateTime($date_string . ' ' . $slot_config->ora_fine);
        $duration_minutes = $slot_config->durata_slot_minuti;

        while ($slot_start->format('H:i:s') < $slot_config->ora_fine) {
            $current_slot_end = clone $slot_start;
            $current_slot_end->add(new DateInterval('PT' . $duration_minutes . 'M'));

            // Controlla se lo slot è libero e prenotabile
            if (!$this->is_slot_booked($slot_start, $current_slot_end, $prenotazioni) &&
                $this->is_slot_bookable($slot_start, $settings)) {
                return true; // Trovato almeno uno slot disponibile
            }

            $slot_start->add(new DateInterval('PT' . $duration_minutes . 'M'));
        }

        return false;
    }

    /**
     * AJAX: Controlla disponibilità
     *
     * @since 1.0.0
     */
    public function ajax_check_availability() {
        check_ajax_referer('prenotazione_aule_ssm_public_nonce', 'nonce');

        $aula_id = absint($_POST['aula_id']);

        // Supporta sia range (start/end) per FullCalendar che singola data (date) per NEW_CALENDAR
        if (isset($_POST['date'])) {
            // NEW_CALENDAR: singola data
            $date_start = sanitize_text_field($_POST['date']);
            $date_end = $date_start;
        } else {
            // FullCalendar: range
            $date_start = sanitize_text_field($_POST['start']);
            $date_end = sanitize_text_field($_POST['end']);
        }

        if (!$aula_id) {
            wp_send_json_error(__('ID aula non valido', 'prenotazione-aule-ssm'));
        }

        // Ottieni aula
        $aula = $this->database->get_aula_by_id($aula_id);
        if (!$aula || $aula->stato !== 'attiva') {
            wp_send_json_error(__('Aula non disponibile', 'prenotazione-aule-ssm'));
        }

        // Ottieni prenotazioni nel periodo
        $prenotazioni = $this->database->get_prenotazioni(array(
            'aula_id' => $aula_id,
            'data_da' => $date_start,
            'data_a' => $date_end
        ));

        // Ottieni slot disponibilità per l'aula
        $slots_disponibilita = $this->database->get_slot_disponibilita($aula_id, array(
            'data_inizio' => $date_start,
            'data_fine' => $date_end
        ));

        $events = array();

        // Aggiungi prenotazioni esistenti
        foreach ($prenotazioni as $prenotazione) {
            if (in_array($prenotazione->stato, ['confermata', 'in_attesa'])) {
                $color = ($prenotazione->stato === 'confermata') ? '#dc3545' : '#ffc107';

                $events[] = array(
                    'id' => 'booking-' . $prenotazione->id,
                    'title' => __('Occupato', 'prenotazione-aule-ssm'),
                    'start' => $prenotazione->data_prenotazione . 'T' . $prenotazione->ora_inizio,
                    'end' => $prenotazione->data_prenotazione . 'T' . $prenotazione->ora_fine,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'classNames' => array('booked-slot'),
                    'extendedProps' => array(
                        'type' => 'booked',
                        'stato' => $prenotazione->stato
                    )
                );
            }
        }

        // Genera slot disponibili basati sulla configurazione
        $available_events = $this->generate_available_slots($aula_id, $date_start, $date_end, $slots_disponibilita, $prenotazioni);
        $events = array_merge($events, $available_events);

        // Se richiesta con singola data (NEW_CALENDAR), restituisci formato semplificato
        if (isset($_POST['date'])) {
            $simple_slots = array();
            foreach ($events as $event) {
                $start_time = substr($event['start'], 11, 5); // Estrae HH:MM da ISO datetime
                $end_time = substr($event['end'], 11, 5);

                $simple_slots[] = array(
                    'id' => $event['id'],
                    'start' => $start_time,
                    'end' => $end_time,
                    'occupied' => ($event['extendedProps']['type'] === 'booked'),
                    'stato' => isset($event['extendedProps']['stato']) ? $event['extendedProps']['stato'] : 'disponibile'
                );
            }
            wp_send_json($simple_slots);
        }

        // Per FullCalendar eventSources, invia direttamente l'array
        wp_send_json($events);
    }

    /**
     * AJAX: Invia prenotazione
     *
     * @since 1.0.0
     */
    public function ajax_submit_booking() {
        check_ajax_referer('prenotazione_aule_ssm_public_nonce', 'nonce');

        // Validazione dati
        $booking_data = array(
            'aula_id' => absint($_POST['aula_id']),
            'nome_richiedente' => sanitize_text_field($_POST['nome_richiedente']),
            'cognome_richiedente' => sanitize_text_field($_POST['cognome_richiedente']),
            'email_richiedente' => sanitize_email($_POST['email_richiedente']),
            'motivo_prenotazione' => sanitize_textarea_field($_POST['motivo_prenotazione']),
            'data_prenotazione' => sanitize_text_field($_POST['data_prenotazione']),
            'ora_inizio' => sanitize_text_field($_POST['ora_inizio']),
            'ora_fine' => sanitize_text_field($_POST['ora_fine'])
        );

        // Controlli di validazione
        $errors = array();

        if (empty($booking_data['nome_richiedente']) || strlen($booking_data['nome_richiedente']) < 2) {
            $errors[] = __('Il nome deve contenere almeno 2 caratteri', 'prenotazione-aule-ssm');
        }

        if (empty($booking_data['cognome_richiedente']) || strlen($booking_data['cognome_richiedente']) < 2) {
            $errors[] = __('Il cognome deve contenere almeno 2 caratteri', 'prenotazione-aule-ssm');
        }

        if (empty($booking_data['email_richiedente']) || !is_email($booking_data['email_richiedente'])) {
            $errors[] = __('Indirizzo email non valido', 'prenotazione-aule-ssm');
        }

        if (empty($booking_data['motivo_prenotazione']) || strlen($booking_data['motivo_prenotazione']) < 10) {
            $errors[] = __('Il motivo della prenotazione deve contenere almeno 10 caratteri', 'prenotazione-aule-ssm');
        }

        if (!$booking_data['aula_id']) {
            $errors[] = __('Aula non valida', 'prenotazione-aule-ssm');
        }

        if (empty($booking_data['data_prenotazione']) || empty($booking_data['ora_inizio']) || empty($booking_data['ora_fine'])) {
            $errors[] = __('Data e orari sono obbligatori', 'prenotazione-aule-ssm');
        }

        // Verifica privacy
        if (empty($_POST['privacy_accepted'])) {
            $errors[] = __('Devi accettare l\'informativa privacy', 'prenotazione-aule-ssm');
        }

        // Verifica reCAPTCHA se abilitato
        $settings = $this->database->get_impostazioni();
        if (!empty($settings->abilita_recaptcha) && !empty($settings->recaptcha_secret_key)) {
            if (empty($_POST['recaptcha_token'])) {
                $errors[] = __('Verifica reCAPTCHA mancante', 'prenotazione-aule-ssm');
            } else {
                $recaptcha_valid = $this->verify_recaptcha($_POST['recaptcha_token'], $settings->recaptcha_secret_key);
                if (!$recaptcha_valid) {
                    $errors[] = __('Verifica reCAPTCHA fallita', 'prenotazione-aule-ssm');
                }
            }
        }

        if (!empty($errors)) {
            wp_send_json_error(implode('<br>', $errors));
        }

        // Controlli business logic
        $aula = $this->database->get_aula_by_id($booking_data['aula_id']);
        if (!$aula || $aula->stato !== 'attiva') {
            wp_send_json_error(__('Aula non disponibile', 'prenotazione-aule-ssm'));
        }

        // Controllo conflitti
        $conflict = $this->database->check_booking_conflict(
            $booking_data['aula_id'],
            $booking_data['data_prenotazione'],
            $booking_data['ora_inizio'],
            $booking_data['ora_fine']
        );

        if ($conflict) {
            wp_send_json_error(__('Slot già prenotato', 'prenotazione-aule-ssm'));
        }

        // Controllo limiti temporali
        if (!$this->validate_booking_timing($booking_data, $settings)) {
            wp_send_json_error(__('Prenotazione fuori dai limiti consentiti', 'prenotazione-aule-ssm'));
        }

        // Controllo limite prenotazioni per utente
        if (!$this->validate_user_booking_limit($booking_data, $settings)) {
            wp_send_json_error(__('Limite giornaliero di prenotazioni raggiunto', 'prenotazione-aule-ssm'));
        }

        // Determina stato iniziale
        $stato_iniziale = !empty($settings->conferma_automatica) ? 'confermata' : 'in_attesa';
        $booking_data['stato'] = $stato_iniziale;

        // Inserisci prenotazione
        $booking_id = $this->database->insert_prenotazione($booking_data);

        if ($booking_id) {
            // Invia email
            $email_handler = new Prenotazione_Aule_SSM_Email();

            if ($stato_iniziale === 'confermata') {
                $email_handler->send_booking_confirmation($booking_id);
            } else {
                $email_handler->send_admin_notification($booking_id);
            }

            wp_send_json_success(array(
                'message' => __('Prenotazione inviata correttamente', 'prenotazione-aule-ssm'),
                'booking_id' => $booking_id,
                'stato' => $stato_iniziale
            ));
        } else {
            wp_send_json_error(__('Errore nell\'invio della prenotazione', 'prenotazione-aule-ssm'));
        }
    }

    /**
     * AJAX: Ottieni le mie prenotazioni (se l'utente è loggato)
     *
     * @since 1.0.0
     */
    public function ajax_get_my_bookings() {
        check_ajax_referer('prenotazione_aule_ssm_public_nonce', 'nonce');

        $email = sanitize_email($_POST['email']);

        if (empty($email)) {
            wp_send_json_error(__('Email richiesta', 'prenotazione-aule-ssm'));
        }

        $prenotazioni = $this->database->get_prenotazioni(array(
            'email' => $email,
            'data_da' => current_time('Y-m-d')
        ));

        wp_send_json_success($prenotazioni);
    }

    /**
     * METODI DI UTILITÀ
     */

    /**
     * Genera slot disponibili per il calendario
     *
     * @since 1.0.0
     * @param int $aula_id
     * @param string $date_start
     * @param string $date_end
     * @param array $slots_disponibilita
     * @param array $prenotazioni
     * @return array
     */
    private function generate_available_slots($aula_id, $date_start, $date_end, $slots_disponibilita, $prenotazioni) {
        $events = array();
        $settings = $this->database->get_impostazioni();

        $start_date = new DateTime($date_start);
        $end_date = new DateTime($date_end);
        $current_date = clone $start_date;

        while ($current_date <= $end_date) {
            $day_of_week = $current_date->format('N'); // 1=Monday, 7=Sunday
            $date_string = $current_date->format('Y-m-d');

            // Trova slot per questo giorno della settimana
            foreach ($slots_disponibilita as $slot) {
                if ($slot->giorno_settimana == $day_of_week) {
                    // Verifica se la data è nel range di validità
                    if ($date_string >= $slot->data_inizio_validita &&
                        (is_null($slot->data_fine_validita) || $date_string <= $slot->data_fine_validita)) {

                        // Genera slot per la giornata
                        $slot_start = new DateTime($date_string . ' ' . $slot->ora_inizio);
                        $slot_end = new DateTime($date_string . ' ' . $slot->ora_fine);
                        $duration_minutes = $slot->durata_slot_minuti;

                        while ($slot_start->format('H:i:s') < $slot->ora_fine) {
                            $current_slot_end = clone $slot_start;
                            $current_slot_end->add(new DateInterval('PT' . $duration_minutes . 'M'));

                            // Controlla se lo slot è libero
                            if (!$this->is_slot_booked($slot_start, $current_slot_end, $prenotazioni)) {
                                // Controlla se lo slot è prenotabile (non nel passato)
                                if ($this->is_slot_bookable($slot_start, $settings)) {
                                    $events[] = array(
                                        'id' => 'slot-' . $aula_id . '-' . $slot_start->format('Y-m-d-H-i'),
                                        'title' => __('Disponibile', 'prenotazione-aule-ssm'),
                                        'start' => $slot_start->format('Y-m-d\TH:i:s'),
                                        'end' => $current_slot_end->format('Y-m-d\TH:i:s'),
                                        'backgroundColor' => $settings->colore_slot_libero ?? '#28a745',
                                        'borderColor' => $settings->colore_slot_libero ?? '#28a745',
                                        'classNames' => array('available-slot'),
                                        'extendedProps' => array(
                                            'type' => 'available',
                                            'aula_id' => $aula_id,
                                            'bookable' => true
                                        )
                                    );
                                }
                            }

                            $slot_start->add(new DateInterval('PT' . $duration_minutes . 'M'));
                        }
                    }
                }
            }

            $current_date->add(new DateInterval('P1D'));
        }

        return $events;
    }

    /**
     * Controlla se uno slot è già prenotato
     *
     * @since 1.0.0
     * @param DateTime $start
     * @param DateTime $end
     * @param array $prenotazioni
     * @return bool
     */
    private function is_slot_booked($start, $end, $prenotazioni) {
        foreach ($prenotazioni as $prenotazione) {
            if (in_array($prenotazione->stato, ['confermata', 'in_attesa'])) {
                $booking_start = new DateTime($prenotazione->data_prenotazione . ' ' . $prenotazione->ora_inizio);
                $booking_end = new DateTime($prenotazione->data_prenotazione . ' ' . $prenotazione->ora_fine);

                if ($start < $booking_end && $end > $booking_start) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Controlla se uno slot è prenotabile
     *
     * @since 1.0.0
     * @param DateTime $slot_start
     * @param object $settings
     * @return bool
     */
    private function is_slot_bookable($slot_start, $settings) {
        $now = new DateTime();

        // Controlla anticipo minimo
        $min_advance_hours = $settings->ore_anticipo_prenotazione_min ?? 24;
        $min_booking_time = clone $now;
        $min_booking_time->add(new DateInterval('PT' . $min_advance_hours . 'H'));

        if ($slot_start <= $min_booking_time) {
            return false;
        }

        // Controlla massimo futuro
        $max_future_days = $settings->giorni_prenotazione_futura_max ?? 30;
        $max_booking_time = clone $now;
        $max_booking_time->add(new DateInterval('P' . $max_future_days . 'D'));

        if ($slot_start > $max_booking_time) {
            return false;
        }

        return true;
    }

    /**
     * Valida timing della prenotazione
     *
     * @since 1.0.0
     * @param array $booking_data
     * @param object $settings
     * @return bool
     */
    private function validate_booking_timing($booking_data, $settings) {
        $booking_datetime = new DateTime($booking_data['data_prenotazione'] . ' ' . $booking_data['ora_inizio']);
        return $this->is_slot_bookable($booking_datetime, $settings);
    }

    /**
     * Valida limite prenotazioni per utente
     *
     * @since 1.0.0
     * @param array $booking_data
     * @param object $settings
     * @return bool
     */
    private function validate_user_booking_limit($booking_data, $settings) {
        $max_bookings = $settings->max_prenotazioni_per_utente_giorno ?? 3;

        $existing_bookings = $this->database->get_prenotazioni(array(
            'email' => $booking_data['email_richiedente'],
            'data_da' => $booking_data['data_prenotazione'],
            'data_a' => $booking_data['data_prenotazione'],
            'stato' => 'confermata'
        ));

        return count($existing_bookings) < $max_bookings;
    }

    /**
     * Verifica reCAPTCHA
     *
     * @since 1.0.0
     * @param string $token
     * @param string $secret_key
     * @return bool
     */
    private function verify_recaptcha($token, $secret_key) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret_key,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return false;
        }

        $response_data = json_decode($response, true);

        return isset($response_data['success']) && $response_data['success'] === true;
    }
}