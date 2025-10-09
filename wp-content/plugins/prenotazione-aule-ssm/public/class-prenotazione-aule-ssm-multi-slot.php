<?php
/**
 * Gestione prenotazioni multi-slot
 *
 * @package Prenotazione_Aule_SSM
 * @since 1.0.0
 */

class Prenotazione_Aule_SSM_Multi_Slot {

    private $database;

    public function __construct() {
        require_once PRENOTAZIONE_AULE_SSM_PLUGIN_DIR . 'includes/class-prenotazione-aule-ssm-database.php';
        $this->database = new Prenotazione_Aule_SSM_Database();

        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('wp_ajax_get_slots_for_date', array($this, 'ajax_get_slots_for_date'));
        add_action('wp_ajax_nopriv_get_slots_for_date', array($this, 'ajax_get_slots_for_date'));
        add_action('wp_ajax_prenotazione_aule_ssm_multi_booking', array($this, 'ajax_multi_booking'));
        add_action('wp_ajax_nopriv_prenotazione_aule_ssm_multi_booking', array($this, 'ajax_multi_booking'));

        // Endpoint per precaricare prenotazioni del mese
        add_action('wp_ajax_prenotazione_aule_ssm_get_month_bookings', array($this, 'ajax_get_month_bookings'));
        add_action('wp_ajax_nopriv_prenotazione_aule_ssm_get_month_bookings', array($this, 'ajax_get_month_bookings'));
    }

    public function ajax_get_slots_for_date() {
        check_ajax_referer('prenotazione_aule_ssm_public_nonce', 'nonce');

        $aula_id = intval($_POST['aula_id']);
        $date = sanitize_text_field($_POST['date']);

        if (!$aula_id || !$date) {
            wp_send_json_error(array('message' => 'Parametri mancanti'));
        }

        global $wpdb;
        $table_slot = $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita';
        $table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';

        $timestamp = strtotime($date);
        $giorno_settimana = date('N', $timestamp);

        $slots_query = $wpdb->prepare(
            "SELECT ora_inizio, ora_fine, durata_slot_minuti
            FROM $table_slot
            WHERE aula_id = %d
            AND giorno_settimana = %d
            AND attivo = 1
            AND (data_inizio_validita <= %s AND (data_fine_validita IS NULL OR data_fine_validita >= %s))
            ORDER BY ora_inizio",
            $aula_id,
            $giorno_settimana,
            $date,
            $date
        );

        $slot_config = $wpdb->get_results($slots_query);
        $available_slots = array();

        foreach ($slot_config as $config) {
            $start_time = strtotime($config->ora_inizio);
            $end_time = strtotime($config->ora_fine);
            $duration = intval($config->durata_slot_minuti);

            $current_time = $start_time;
            while ($current_time < $end_time) {
                $next_time = $current_time + ($duration * 60);

                $available_slots[] = array(
                    'time' => date('H:i', $current_time),
                    'end_time' => date('H:i', $next_time),
                    'duration' => $duration
                );

                $current_time = $next_time;
            }
        }

        $bookings_query = $wpdb->prepare(
            "SELECT ora_inizio, ora_fine, motivo_prenotazione, nome_richiedente, cognome_richiedente
            FROM $table_prenotazioni
            WHERE aula_id = %d
            AND data_prenotazione = %s
            AND stato IN ('approvata', 'in_attesa')
            ORDER BY ora_inizio",
            $aula_id,
            $date
        );

        $booked_slots = $wpdb->get_results($bookings_query, ARRAY_A);

        wp_send_json_success(array(
            'available_slots' => $available_slots,
            'booked_slots' => $booked_slots,
            'date' => $date
        ));
    }

    public function ajax_multi_booking() {
        check_ajax_referer('prenotazione_aule_ssm_multi_booking', 'nonce');

        $aula_id = intval($_POST['aula_id']);
        $selected_slots = json_decode(stripslashes($_POST['selected_slots']), true);
        $cognome = sanitize_text_field($_POST['cognome_richiedente']);
        $nome = sanitize_text_field($_POST['nome_richiedente']);
        $email = sanitize_email($_POST['email_richiedente']);
        $motivo = sanitize_textarea_field($_POST['motivo_prenotazione']);

        if (!$aula_id || empty($selected_slots) || !$cognome || !$nome || !$email || !$motivo) {
            wp_send_json_error(array('message' => 'Compila tutti i campi obbligatori'));
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => 'Email non valida'));
        }

        if (count($selected_slots) === 0) {
            wp_send_json_error(array('message' => 'Seleziona almeno uno slot'));
        }

        global $wpdb;
        $table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';
        $gruppo_prenotazione = uniqid('multi_', true);

        $wpdb->query('START TRANSACTION');

        try {
            foreach ($selected_slots as $slot) {
                $existing = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_prenotazioni
                    WHERE aula_id = %d
                    AND data_prenotazione = %s
                    AND ora_inizio = %s
                    AND stato IN ('approvata', 'in_attesa')",
                    $aula_id,
                    $slot['date'],
                    $slot['time'] . ':00'
                ));

                if ($existing > 0) {
                    throw new Exception('Lo slot ' . $slot['time'] . ' e gia prenotato');
                }

                $ora_fine_timestamp = strtotime($slot['time']) + (30 * 60);
                $ora_fine = date('H:i:s', $ora_fine_timestamp);

                $inserted = $wpdb->insert(
                    $table_prenotazioni,
                    array(
                        'aula_id' => $aula_id,
                        'nome_richiedente' => $nome,
                        'cognome_richiedente' => $cognome,
                        'email_richiedente' => $email,
                        'motivo_prenotazione' => $motivo,
                        'data_prenotazione' => $slot['date'],
                        'ora_inizio' => $slot['time'] . ':00',
                        'ora_fine' => $ora_fine,
                        'stato' => 'in_attesa',
                        'gruppo_prenotazione' => $gruppo_prenotazione,
                        'created_at' => current_time('mysql')
                    ),
                    array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
                );

                if (!$inserted) {
                    throw new Exception('Errore nel salvataggio dello slot ' . $slot['time']);
                }
            }

            $wpdb->query('COMMIT');

            $this->send_booking_confirmation_email($email, $nome, $cognome, $selected_slots, $motivo);

            wp_send_json_success(array(
                'message' => 'Prenotazione confermata per ' . count($selected_slots) . ' slot!',
                'booking_group' => $gruppo_prenotazione
            ));

        } catch (Exception $e) {
            $wpdb->query('ROLLBACK');
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    private function send_booking_confirmation_email($email, $nome, $cognome, $slots, $motivo) {
        $to = $email;
        $subject = 'Conferma prenotazione aula - Prenotazione Aule SSM';

        $slots_list = '';
        foreach ($slots as $slot) {
            $slots_list .= sprintf("\n- %s alle %s", $slot['date'], $slot['time']);
        }

        $message = sprintf(
            "Gentile %s %s,\n\nLa sua prenotazione e stata registrata con successo.\n\nDettagli:\n%s\n\nMotivo: %s\n\nGrazie,\nPrenotazione Aule SSM",
            $nome,
            $cognome,
            $slots_list,
            $motivo
        );

        wp_mail($to, $subject, $message);
    }

    /**
     * Ottieni tutte le prenotazioni di un mese (per rendering calendario)
     */
    public function ajax_get_month_bookings() {
        check_ajax_referer('prenotazione_aule_ssm_public_nonce', 'nonce');

        $aula_id = intval($_POST['aula_id']);
        $year = intval($_POST['year']);
        $month = intval($_POST['month']);

        if (!$aula_id || !$year || !$month) {
            wp_send_json_error(array('message' => 'Parametri mancanti'));
        }

        global $wpdb;
        $table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';

        // Primo e ultimo giorno del mese
        $first_day = sprintf('%04d-%02d-01', $year, $month);
        $last_day = date('Y-m-t', strtotime($first_day));

        // Ottieni tutte le prenotazioni del mese
        $bookings = $wpdb->get_results($wpdb->prepare(
            "SELECT data_prenotazione, ora_inizio, ora_fine, motivo_prenotazione,
                    nome_richiedente, cognome_richiedente
             FROM $table_prenotazioni
             WHERE aula_id = %d
             AND data_prenotazione BETWEEN %s AND %s
             AND stato IN ('approvata', 'in_attesa')
             ORDER BY data_prenotazione, ora_inizio",
            $aula_id,
            $first_day,
            $last_day
        ), ARRAY_A);

        wp_send_json_success(array('bookings' => $bookings));
    }
}

new Prenotazione_Aule_SSM_Multi_Slot();
