<?php

/**
 * Definisce la funzionalità REST API del plugin
 *
 * Registra endpoint REST API per accesso esterno alle funzionalità del plugin
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/includes
 */

class Aule_Booking_API {

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
     * Namespace dell'API
     *
     * @since 1.0.0
     * @access private
     * @var string $namespace
     */
    private $namespace = 'aule-booking/v1';

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
     * Registra le route REST API
     *
     * @since 1.0.0
     */
    public function register_routes() {

        // GET /wp-json/aule-booking/v1/aule - Lista di tutte le aule
        register_rest_route($this->namespace, '/aule', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_aule'),
            'permission_callback' => array($this, 'check_read_permission'),
            'args' => array(
                'stato' => array(
                    'description' => __('Filtra per stato aula', 'aule-booking'),
                    'type' => 'string',
                    'enum' => array('attiva', 'non_disponibile', 'manutenzione'),
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'ubicazione' => array(
                    'description' => __('Filtra per ubicazione', 'aule-booking'),
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            )
        ));

        // GET /wp-json/aule-booking/v1/aule/{id} - Singola aula
        register_rest_route($this->namespace, '/aule/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_aula'),
            'permission_callback' => array($this, 'check_read_permission'),
            'args' => array(
                'id' => array(
                    'description' => __('ID dell\'aula', 'aule-booking'),
                    'type' => 'integer',
                    'sanitize_callback' => 'absint',
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param) && $param > 0;
                    }
                )
            )
        ));

        // GET /wp-json/aule-booking/v1/availability/{aula_id} - Disponibilità aula
        register_rest_route($this->namespace, '/availability/(?P<aula_id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_availability'),
            'permission_callback' => array($this, 'check_read_permission'),
            'args' => array(
                'aula_id' => array(
                    'description' => __('ID dell\'aula', 'aule-booking'),
                    'type' => 'integer',
                    'sanitize_callback' => 'absint',
                    'required' => true
                ),
                'start' => array(
                    'description' => __('Data inizio (Y-m-d)', 'aule-booking'),
                    'type' => 'string',
                    'format' => 'date',
                    'sanitize_callback' => 'sanitize_text_field',
                    'validate_callback' => function($param) {
                        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $param);
                    }
                ),
                'end' => array(
                    'description' => __('Data fine (Y-m-d)', 'aule-booking'),
                    'type' => 'string',
                    'format' => 'date',
                    'sanitize_callback' => 'sanitize_text_field',
                    'validate_callback' => function($param) {
                        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $param);
                    }
                )
            )
        ));

        // POST /wp-json/aule-booking/v1/booking - Crea nuova prenotazione
        register_rest_route($this->namespace, '/booking', array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'create_booking'),
            'permission_callback' => array($this, 'check_booking_permission'),
            'args' => array(
                'aula_id' => array(
                    'description' => __('ID dell\'aula', 'aule-booking'),
                    'type' => 'integer',
                    'required' => true,
                    'sanitize_callback' => 'absint'
                ),
                'nome_richiedente' => array(
                    'description' => __('Nome del richiedente', 'aule-booking'),
                    'type' => 'string',
                    'required' => true,
                    'minLength' => 2,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'cognome_richiedente' => array(
                    'description' => __('Cognome del richiedente', 'aule-booking'),
                    'type' => 'string',
                    'required' => true,
                    'minLength' => 2,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'email_richiedente' => array(
                    'description' => __('Email del richiedente', 'aule-booking'),
                    'type' => 'string',
                    'format' => 'email',
                    'required' => true,
                    'sanitize_callback' => 'sanitize_email',
                    'validate_callback' => function($param) {
                        return is_email($param);
                    }
                ),
                'motivo_prenotazione' => array(
                    'description' => __('Motivo della prenotazione', 'aule-booking'),
                    'type' => 'string',
                    'required' => true,
                    'minLength' => 10,
                    'sanitize_callback' => 'sanitize_textarea_field'
                ),
                'data_prenotazione' => array(
                    'description' => __('Data prenotazione (Y-m-d)', 'aule-booking'),
                    'type' => 'string',
                    'format' => 'date',
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'ora_inizio' => array(
                    'description' => __('Ora inizio (H:i:s)', 'aule-booking'),
                    'type' => 'string',
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'ora_fine' => array(
                    'description' => __('Ora fine (H:i:s)', 'aule-booking'),
                    'type' => 'string',
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field'
                )
            )
        ));

        // GET /wp-json/aule-booking/v1/booking/{id} - Dettagli prenotazione
        register_rest_route($this->namespace, '/booking/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_booking'),
            'permission_callback' => array($this, 'check_booking_read_permission'),
            'args' => array(
                'id' => array(
                    'description' => __('ID della prenotazione', 'aule-booking'),
                    'type' => 'integer',
                    'sanitize_callback' => 'absint'
                )
            )
        ));

        // PUT /wp-json/aule-booking/v1/booking/{id}/status - Aggiorna stato prenotazione
        register_rest_route($this->namespace, '/booking/(?P<id>\d+)/status', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array($this, 'update_booking_status'),
            'permission_callback' => array($this, 'check_manage_permission'),
            'args' => array(
                'id' => array(
                    'description' => __('ID della prenotazione', 'aule-booking'),
                    'type' => 'integer',
                    'sanitize_callback' => 'absint'
                ),
                'stato' => array(
                    'description' => __('Nuovo stato', 'aule-booking'),
                    'type' => 'string',
                    'required' => true,
                    'enum' => array('in_attesa', 'confermata', 'rifiutata', 'cancellata'),
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'note_admin' => array(
                    'description' => __('Note admin', 'aule-booking'),
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_textarea_field'
                )
            )
        ));

        // GET /wp-json/aule-booking/v1/calendar/{aula_id} - Eventi calendario
        register_rest_route($this->namespace, '/calendar/(?P<aula_id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_calendar_events'),
            'permission_callback' => array($this, 'check_read_permission'),
            'args' => array(
                'aula_id' => array(
                    'description' => __('ID dell\'aula', 'aule-booking'),
                    'type' => 'integer',
                    'sanitize_callback' => 'absint'
                ),
                'start' => array(
                    'description' => __('Data inizio (Y-m-d)', 'aule-booking'),
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'end' => array(
                    'description' => __('Data fine (Y-m-d)', 'aule-booking'),
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            )
        ));

        // GET /wp-json/aule-booking/v1/prenotazioni - Lista prenotazioni (con filtri)
        register_rest_route($this->namespace, '/prenotazioni', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_prenotazioni'),
            'permission_callback' => array($this, 'check_manage_permission'),
            'args' => array(
                'aula_id' => array(
                    'description' => __('Filtra per aula', 'aule-booking'),
                    'type' => 'integer',
                    'sanitize_callback' => 'absint'
                ),
                'stato' => array(
                    'description' => __('Filtra per stato', 'aule-booking'),
                    'type' => 'string',
                    'enum' => array('in_attesa', 'confermata', 'rifiutata', 'cancellata'),
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'data_da' => array(
                    'description' => __('Data inizio (Y-m-d)', 'aule-booking'),
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'data_a' => array(
                    'description' => __('Data fine (Y-m-d)', 'aule-booking'),
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'per_page' => array(
                    'description' => __('Elementi per pagina', 'aule-booking'),
                    'type' => 'integer',
                    'default' => 20,
                    'minimum' => 1,
                    'maximum' => 100,
                    'sanitize_callback' => 'absint'
                ),
                'page' => array(
                    'description' => __('Numero pagina', 'aule-booking'),
                    'type' => 'integer',
                    'default' => 1,
                    'minimum' => 1,
                    'sanitize_callback' => 'absint'
                )
            )
        ));
    }

    /**
     * CALLBACK ENDPOINTS
     */

    /**
     * Ottieni lista aule
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_aule($request) {
        $filters = array();

        if ($request->get_param('stato')) {
            $filters['stato'] = $request->get_param('stato');
        }

        if ($request->get_param('ubicazione')) {
            $filters['ubicazione'] = $request->get_param('ubicazione');
        }

        $aule = $this->database->get_aule($filters);

        $data = array();
        foreach ($aule as $aula) {
            $data[] = $this->prepare_aula_for_response($aula);
        }

        return rest_ensure_response($data);
    }

    /**
     * Ottieni singola aula
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_aula($request) {
        $aula_id = $request->get_param('id');
        $aula = $this->database->get_aula_by_id($aula_id);

        if (!$aula) {
            return new WP_Error('aula_not_found', __('Aula non trovata', 'aule-booking'), array('status' => 404));
        }

        return rest_ensure_response($this->prepare_aula_for_response($aula));
    }

    /**
     * Ottieni disponibilità aula
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_availability($request) {
        $aula_id = $request->get_param('aula_id');
        $start_date = $request->get_param('start') ?: current_time('Y-m-d');
        $end_date = $request->get_param('end') ?: date('Y-m-d', strtotime($start_date . ' +30 days'));

        // Verifica che l'aula esista
        $aula = $this->database->get_aula_by_id($aula_id);
        if (!$aula) {
            return new WP_Error('aula_not_found', __('Aula non trovata', 'aule-booking'), array('status' => 404));
        }

        // Ottieni prenotazioni nel periodo
        $prenotazioni = $this->database->get_prenotazioni(array(
            'aula_id' => $aula_id,
            'data_da' => $start_date,
            'data_a' => $end_date
        ));

        // Ottieni slot disponibilità
        $slots_disponibilita = $this->database->get_slot_disponibilita($aula_id, array(
            'data_inizio' => $start_date,
            'data_fine' => $end_date
        ));

        return rest_ensure_response(array(
            'aula' => $this->prepare_aula_for_response($aula),
            'prenotazioni' => array_map(array($this, 'prepare_booking_for_response'), $prenotazioni),
            'slot_disponibilita' => $slots_disponibilita,
            'periodo' => array(
                'inizio' => $start_date,
                'fine' => $end_date
            )
        ));
    }

    /**
     * Crea nuova prenotazione
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function create_booking($request) {
        $booking_data = array(
            'aula_id' => $request->get_param('aula_id'),
            'nome_richiedente' => $request->get_param('nome_richiedente'),
            'cognome_richiedente' => $request->get_param('cognome_richiedente'),
            'email_richiedente' => $request->get_param('email_richiedente'),
            'motivo_prenotazione' => $request->get_param('motivo_prenotazione'),
            'data_prenotazione' => $request->get_param('data_prenotazione'),
            'ora_inizio' => $request->get_param('ora_inizio'),
            'ora_fine' => $request->get_param('ora_fine')
        );

        // Validazioni business logic
        $validation_result = $this->validate_booking_data($booking_data);
        if (is_wp_error($validation_result)) {
            return $validation_result;
        }

        // Determina stato iniziale
        $settings = $this->database->get_impostazioni();
        $booking_data['stato'] = !empty($settings->conferma_automatica) ? 'confermata' : 'in_attesa';

        // Inserisci prenotazione
        $booking_id = $this->database->insert_prenotazione($booking_data);

        if (!$booking_id) {
            return new WP_Error('booking_creation_failed', __('Errore nella creazione della prenotazione', 'aule-booking'), array('status' => 500));
        }

        // Invia email di notifica
        $email_handler = new Aule_Booking_Email();
        if ($booking_data['stato'] === 'confermata') {
            $email_handler->send_booking_confirmation($booking_id);
        } else {
            $email_handler->send_admin_notification($booking_id);
        }

        // Ottieni prenotazione completa
        $booking = $this->database->get_prenotazione_by_id($booking_id);

        return rest_ensure_response(array(
            'id' => $booking_id,
            'message' => __('Prenotazione creata correttamente', 'aule-booking'),
            'prenotazione' => $this->prepare_booking_for_response($booking),
            'stato' => $booking_data['stato']
        ));
    }

    /**
     * Ottieni dettagli prenotazione
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_booking($request) {
        $booking_id = $request->get_param('id');
        $booking = $this->database->get_prenotazione_by_id($booking_id);

        if (!$booking) {
            return new WP_Error('booking_not_found', __('Prenotazione non trovata', 'aule-booking'), array('status' => 404));
        }

        return rest_ensure_response($this->prepare_booking_for_response($booking));
    }

    /**
     * Aggiorna stato prenotazione
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update_booking_status($request) {
        $booking_id = $request->get_param('id');
        $nuovo_stato = $request->get_param('stato');
        $note_admin = $request->get_param('note_admin') ?: '';

        $booking = $this->database->get_prenotazione_by_id($booking_id);
        if (!$booking) {
            return new WP_Error('booking_not_found', __('Prenotazione non trovata', 'aule-booking'), array('status' => 404));
        }

        $result = $this->database->update_prenotazione_stato(
            $booking_id,
            $nuovo_stato,
            get_current_user_id(),
            $note_admin
        );

        if (!$result) {
            return new WP_Error('update_failed', __('Errore nell\'aggiornamento dello stato', 'aule-booking'), array('status' => 500));
        }

        // Invia email appropriata
        $email_handler = new Aule_Booking_Email();
        if ($nuovo_stato === 'confermata') {
            $email_handler->send_booking_confirmation($booking_id);
        } elseif ($nuovo_stato === 'rifiutata') {
            $email_handler->send_booking_rejection($booking_id);
        }

        // Ottieni prenotazione aggiornata
        $updated_booking = $this->database->get_prenotazione_by_id($booking_id);

        return rest_ensure_response(array(
            'message' => __('Stato aggiornato correttamente', 'aule-booking'),
            'prenotazione' => $this->prepare_booking_for_response($updated_booking)
        ));
    }

    /**
     * Ottieni eventi per calendario
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_calendar_events($request) {
        $aula_id = $request->get_param('aula_id');
        $start_date = $request->get_param('start');
        $end_date = $request->get_param('end');

        $aula = $this->database->get_aula_by_id($aula_id);
        if (!$aula || $aula->stato !== 'attiva') {
            return new WP_Error('aula_not_available', __('Aula non disponibile', 'aule-booking'), array('status' => 400));
        }

        // Ottieni prenotazioni
        $prenotazioni = $this->database->get_prenotazioni(array(
            'aula_id' => $aula_id,
            'data_da' => $start_date,
            'data_a' => $end_date
        ));

        $events = array();
        $settings = $this->database->get_impostazioni();

        foreach ($prenotazioni as $prenotazione) {
            if (in_array($prenotazione->stato, ['confermata', 'in_attesa'])) {
                $color = ($prenotazione->stato === 'confermata') ?
                    ($settings->colore_slot_occupato ?? '#dc3545') :
                    ($settings->colore_slot_attesa ?? '#ffc107');

                $events[] = array(
                    'id' => 'booking-' . $prenotazione->id,
                    'title' => __('Occupato', 'aule-booking'),
                    'start' => $prenotazione->data_prenotazione . 'T' . $prenotazione->ora_inizio,
                    'end' => $prenotazione->data_prenotazione . 'T' . $prenotazione->ora_fine,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => array(
                        'type' => 'booked',
                        'stato' => $prenotazione->stato,
                        'booking_id' => $prenotazione->id
                    )
                );
            }
        }

        return rest_ensure_response($events);
    }

    /**
     * Ottieni lista prenotazioni
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_prenotazioni($request) {
        $filters = array();
        $per_page = $request->get_param('per_page') ?: 20;
        $page = $request->get_param('page') ?: 1;

        // Aggiungi filtri
        $filter_params = array('aula_id', 'stato', 'data_da', 'data_a');
        foreach ($filter_params as $param) {
            if ($request->get_param($param)) {
                $filters[$param] = $request->get_param($param);
            }
        }

        $filters['limit'] = $per_page;
        $filters['offset'] = ($page - 1) * $per_page;

        $prenotazioni = $this->database->get_prenotazioni($filters);

        $data = array();
        foreach ($prenotazioni as $prenotazione) {
            $data[] = $this->prepare_booking_for_response($prenotazione);
        }

        return rest_ensure_response(array(
            'prenotazioni' => $data,
            'pagination' => array(
                'page' => $page,
                'per_page' => $per_page,
                'total' => count($data) // In produzione, implementa conteggio totale
            )
        ));
    }

    /**
     * METODI DI UTILITÀ
     */

    /**
     * Prepara un oggetto aula per la risposta API
     *
     * @since 1.0.0
     * @param object $aula
     * @return array
     */
    private function prepare_aula_for_response($aula) {
        return array(
            'id' => (int) $aula->id,
            'nome_aula' => $aula->nome_aula,
            'descrizione' => $aula->descrizione,
            'capienza' => (int) $aula->capienza,
            'ubicazione' => $aula->ubicazione,
            'attrezzature' => maybe_unserialize($aula->attrezzature),
            'stato' => $aula->stato,
            'immagini' => maybe_unserialize($aula->immagini),
            'created_at' => $aula->created_at,
            'updated_at' => $aula->updated_at
        );
    }

    /**
     * Prepara un oggetto prenotazione per la risposta API
     *
     * @since 1.0.0
     * @param object $booking
     * @return array
     */
    private function prepare_booking_for_response($booking) {
        return array(
            'id' => (int) $booking->id,
            'aula_id' => (int) $booking->aula_id,
            'nome_aula' => isset($booking->nome_aula) ? $booking->nome_aula : '',
            'ubicazione' => isset($booking->ubicazione) ? $booking->ubicazione : '',
            'nome_richiedente' => $booking->nome_richiedente,
            'cognome_richiedente' => $booking->cognome_richiedente,
            'email_richiedente' => $booking->email_richiedente,
            'motivo_prenotazione' => $booking->motivo_prenotazione,
            'data_prenotazione' => $booking->data_prenotazione,
            'ora_inizio' => $booking->ora_inizio,
            'ora_fine' => $booking->ora_fine,
            'stato' => $booking->stato,
            'note_admin' => $booking->note_admin,
            'codice_prenotazione' => $booking->codice_prenotazione,
            'created_at' => $booking->created_at,
            'updated_at' => $booking->updated_at
        );
    }

    /**
     * Valida dati prenotazione
     *
     * @since 1.0.0
     * @param array $booking_data
     * @return true|WP_Error
     */
    private function validate_booking_data($booking_data) {
        // Verifica aula esistente e attiva
        $aula = $this->database->get_aula_by_id($booking_data['aula_id']);
        if (!$aula || $aula->stato !== 'attiva') {
            return new WP_Error('invalid_aula', __('Aula non valida o non attiva', 'aule-booking'), array('status' => 400));
        }

        // Verifica conflitti
        $conflict = $this->database->check_booking_conflict(
            $booking_data['aula_id'],
            $booking_data['data_prenotazione'],
            $booking_data['ora_inizio'],
            $booking_data['ora_fine']
        );

        if ($conflict) {
            return new WP_Error('booking_conflict', __('Slot già prenotato', 'aule-booking'), array('status' => 409));
        }

        // Verifica timing (data/ora valide)
        $booking_datetime = new DateTime($booking_data['data_prenotazione'] . ' ' . $booking_data['ora_inizio']);
        $settings = $this->database->get_impostazioni();

        $now = new DateTime();
        $min_advance_hours = $settings->ore_anticipo_prenotazione_min ?? 24;
        $min_booking_time = clone $now;
        $min_booking_time->add(new DateInterval('PT' . $min_advance_hours . 'H'));

        if ($booking_datetime <= $min_booking_time) {
            return new WP_Error('booking_too_early', __('Prenotazione troppo ravvicinata', 'aule-booking'), array('status' => 400));
        }

        $max_future_days = $settings->giorni_prenotazione_futura_max ?? 30;
        $max_booking_time = clone $now;
        $max_booking_time->add(new DateInterval('P' . $max_future_days . 'D'));

        if ($booking_datetime > $max_booking_time) {
            return new WP_Error('booking_too_far', __('Prenotazione troppo lontana nel futuro', 'aule-booking'), array('status' => 400));
        }

        return true;
    }

    /**
     * CALLBACK DI PERMESSI
     */

    /**
     * Controlla permesso di lettura
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_read_permission($request) {
        // Permetti lettura a tutti (per il frontend pubblico)
        return true;
    }

    /**
     * Controlla permesso per prenotazioni
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_booking_permission($request) {
        // Permetti prenotazioni a tutti
        return true;
    }

    /**
     * Controlla permesso lettura prenotazioni
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_booking_read_permission($request) {
        // Permetti solo agli amministratori o al proprietario della prenotazione
        if (current_user_can('manage_aule_booking')) {
            return true;
        }

        // TODO: Implementa verifica proprietà prenotazione tramite email/codice
        return false;
    }

    /**
     * Controlla permesso di gestione
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_manage_permission($request) {
        return current_user_can('manage_aule_booking');
    }
}