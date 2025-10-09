<?php

/**
 * Classe per la gestione del database
 *
 * Gestisce tutte le operazioni CRUD per le tabelle del plugin
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/includes
 */

class Prenotazione_Aule_SSM_Database {

    /**
     * Costruttore della classe
     *
     * @since 1.0.0
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_aule = $wpdb->prefix . 'prenotazione_aule_ssm_aule';
        $this->table_slot = $wpdb->prefix . 'prenotazione_aule_ssm_slot_disponibilita';
        $this->table_prenotazioni = $wpdb->prefix . 'prenotazione_aule_ssm_prenotazioni';
        $this->table_impostazioni = $wpdb->prefix . 'prenotazione_aule_ssm_impostazioni';
    }

    /**
     * OPERAZIONI AULE
     */

    /**
     * Ottieni tutte le aule
     *
     * @since 1.0.0
     * @param array $filters Filtri opzionali
     * @return array
     */
    public function get_aule($filters = array()) {
        $where = "WHERE 1=1";
        $params = array();

        if (!empty($filters['stato'])) {
            $where .= " AND stato = %s";
            $params[] = $filters['stato'];
        }

        if (!empty($filters['ubicazione'])) {
            $where .= " AND ubicazione LIKE %s";
            $params[] = '%' . $filters['ubicazione'] . '%';
        }

        $order_by = "ORDER BY nome_aula ASC";

        $sql = "SELECT * FROM {$this->table_aule} {$where} {$order_by}";

        if (!empty($params)) {
            $sql = $this->wpdb->prepare($sql, $params);
        }

        return $this->wpdb->get_results($sql);
    }

    /**
     * Ottieni una singola aula per ID
     *
     * @since 1.0.0
     * @param int $aula_id
     * @return object|null
     */
    public function get_aula_by_id($aula_id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_aule} WHERE id = %d",
                $aula_id
            )
        );
    }

    /**
     * Inserisci o aggiorna un'aula
     *
     * @since 1.0.0
     * @param array $data Dati dell'aula
     * @param int $aula_id ID dell'aula (opzionale per update)
     * @return int|false ID dell'aula inserita o false in caso di errore
     */
    public function save_aula($data, $aula_id = null) {
        $aula_data = array(
            'nome_aula' => sanitize_text_field($data['nome_aula']),
            'descrizione' => wp_kses_post($data['descrizione']),
            'capienza' => absint($data['capienza']),
            'ubicazione' => sanitize_text_field($data['ubicazione']),
            'attrezzature' => maybe_serialize($data['attrezzature']),
            'stato' => sanitize_text_field($data['stato']),
            'immagini' => maybe_serialize($data['immagini']),
            'updated_at' => current_time('mysql')
        );

        $format = array('%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s');

        if ($aula_id) {
            // Update
            $result = $this->wpdb->update($this->table_aule, $aula_data, array('id' => $aula_id), $format, array('%d'));
            return $result !== false ? $aula_id : false;
        } else {
            // Insert
            $aula_data['created_at'] = current_time('mysql');
            $format[] = '%s';

            $result = $this->wpdb->insert($this->table_aule, $aula_data, $format);
            return $result ? $this->wpdb->insert_id : false;
        }
    }

    /**
     * Elimina un'aula
     *
     * @since 1.0.0
     * @param int $aula_id
     * @return bool
     */
    public function delete_aula($aula_id) {
        return $this->wpdb->delete($this->table_aule, array('id' => $aula_id), array('%d'));
    }

    /**
     * OPERAZIONI SLOT DISPONIBILITÀ
     */

    /**
     * Ottieni slot di disponibilità per un'aula
     *
     * @since 1.0.0
     * @param int $aula_id
     * @param array $filters Filtri opzionali
     * @return array
     */
    public function get_slot_disponibilita($aula_id, $filters = array()) {
        $where = "WHERE aula_id = %d";
        $params = array($aula_id);

        if (!empty($filters['giorno_settimana'])) {
            $where .= " AND giorno_settimana = %d";
            $params[] = $filters['giorno_settimana'];
        }

        if (!empty($filters['data_inizio'])) {
            $where .= " AND data_inizio_validita <= %s";
            $params[] = $filters['data_inizio'];
        }

        if (!empty($filters['data_fine'])) {
            $where .= " AND (data_fine_validita IS NULL OR data_fine_validita >= %s)";
            $params[] = $filters['data_fine'];
        }

        $where .= " AND attivo = 1";

        $sql = "SELECT * FROM {$this->table_slot} {$where} ORDER BY giorno_settimana, ora_inizio";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, $params));
    }

    /**
     * Inserisci slot di disponibilità
     *
     * @since 1.0.0
     * @param array $data
     * @return int|false
     */
    public function insert_slot($data) {
        $slot_data = array(
            'aula_id' => absint($data['aula_id']),
            'giorno_settimana' => absint($data['giorno_settimana']),
            'ora_inizio' => sanitize_text_field($data['ora_inizio']),
            'ora_fine' => sanitize_text_field($data['ora_fine']),
            'durata_slot_minuti' => absint($data['durata_slot_minuti']),
            'data_inizio_validita' => sanitize_text_field($data['data_inizio_validita']),
            'data_fine_validita' => !empty($data['data_fine_validita']) ? sanitize_text_field($data['data_fine_validita']) : null,
            'ricorrenza' => sanitize_text_field($data['ricorrenza']),
            'attivo' => 1
        );

        $format = array('%d', '%d', '%s', '%s', '%d', '%s', '%s', '%s', '%d');

        $result = $this->wpdb->insert($this->table_slot, $slot_data, $format);
        return $result ? $this->wpdb->insert_id : false;
    }

    /**
     * Elimina slot di disponibilità
     *
     * @since 1.0.0
     * @param array $slot_ids Array di ID degli slot da eliminare
     * @return bool
     */
    public function delete_slots($slot_ids) {
        if (empty($slot_ids) || !is_array($slot_ids)) {
            return false;
        }

        $ids_placeholder = implode(',', array_fill(0, count($slot_ids), '%d'));

        return $this->wpdb->query(
            $this->wpdb->prepare(
                "DELETE FROM {$this->table_slot} WHERE id IN ($ids_placeholder)",
                ...$slot_ids
            )
        ) !== false;
    }

    /**
     * OPERAZIONI PRENOTAZIONI
     */

    /**
     * Ottieni prenotazioni
     *
     * @since 1.0.0
     * @param array $filters Filtri opzionali
     * @return array
     */
    public function get_prenotazioni($filters = array()) {
        $where = "WHERE 1=1";
        $params = array();

        if (!empty($filters['aula_id'])) {
            $where .= " AND p.aula_id = %d";
            $params[] = $filters['aula_id'];
        }

        if (!empty($filters['stato'])) {
            $where .= " AND p.stato = %s";
            $params[] = $filters['stato'];
        }

        if (!empty($filters['data_da'])) {
            $where .= " AND p.data_prenotazione >= %s";
            $params[] = $filters['data_da'];
        }

        if (!empty($filters['data_a'])) {
            $where .= " AND p.data_prenotazione <= %s";
            $params[] = $filters['data_a'];
        }

        if (!empty($filters['email'])) {
            $where .= " AND p.email_richiedente = %s";
            $params[] = $filters['email'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (p.nome_richiedente LIKE %s OR p.cognome_richiedente LIKE %s OR p.email_richiedente LIKE %s)";
            $search_term = '%' . $filters['search'] . '%';
            $params[] = $search_term;
            $params[] = $search_term;
            $params[] = $search_term;
        }

        $order_by = "ORDER BY p.data_prenotazione DESC, p.ora_inizio ASC";

        if (!empty($filters['limit'])) {
            $limit = "LIMIT %d";
            $params[] = absint($filters['limit']);
        } else {
            $limit = "";
        }

        if (!empty($filters['offset'])) {
            $offset = "OFFSET %d";
            $params[] = absint($filters['offset']);
        } else {
            $offset = "";
        }

        $sql = "SELECT p.*, a.nome_aula, a.ubicazione
                FROM {$this->table_prenotazioni} p
                LEFT JOIN {$this->table_aule} a ON p.aula_id = a.id
                {$where}
                {$order_by}
                {$limit}
                {$offset}";

        if (!empty($params)) {
            $sql = $this->wpdb->prepare($sql, $params);
        }

        return $this->wpdb->get_results($sql);
    }

    /**
     * Ottieni una prenotazione per ID
     *
     * @since 1.0.0
     * @param int $prenotazione_id
     * @return object|null
     */
    public function get_prenotazione_by_id($prenotazione_id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT p.*, a.nome_aula, a.ubicazione
                 FROM {$this->table_prenotazioni} p
                 LEFT JOIN {$this->table_aule} a ON p.aula_id = a.id
                 WHERE p.id = %d",
                $prenotazione_id
            )
        );
    }

    /**
     * Inserisci una nuova prenotazione
     *
     * @since 1.0.0
     * @param array $data
     * @return int|false
     */
    public function insert_prenotazione($data) {
        $prenotazione_data = array(
            'aula_id' => absint($data['aula_id']),
            'nome_richiedente' => sanitize_text_field($data['nome_richiedente']),
            'cognome_richiedente' => sanitize_text_field($data['cognome_richiedente']),
            'email_richiedente' => sanitize_email($data['email_richiedente']),
            'motivo_prenotazione' => sanitize_textarea_field($data['motivo_prenotazione']),
            'data_prenotazione' => sanitize_text_field($data['data_prenotazione']),
            'ora_inizio' => sanitize_text_field($data['ora_inizio']),
            'ora_fine' => sanitize_text_field($data['ora_fine']),
            'stato' => !empty($data['stato']) ? sanitize_text_field($data['stato']) : 'in_attesa',
            'codice_prenotazione' => $this->generate_booking_code(),
            'created_at' => current_time('mysql')
        );

        $format = array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

        $result = $this->wpdb->insert($this->table_prenotazioni, $prenotazione_data, $format);
        return $result ? $this->wpdb->insert_id : false;
    }

    /**
     * Aggiorna lo stato di una prenotazione
     *
     * @since 1.0.0
     * @param int $prenotazione_id
     * @param string $nuovo_stato
     * @param int $user_id_approvazione
     * @param string $note_admin
     * @return bool
     */
    public function update_prenotazione_stato($prenotazione_id, $nuovo_stato, $user_id_approvazione = null, $note_admin = '') {
        $update_data = array(
            'stato' => sanitize_text_field($nuovo_stato),
            'updated_at' => current_time('mysql')
        );

        $format = array('%s', '%s');

        if ($user_id_approvazione) {
            $update_data['user_id_approvazione'] = absint($user_id_approvazione);
            $format[] = '%d';
        }

        if (!empty($note_admin)) {
            $update_data['note_admin'] = sanitize_textarea_field($note_admin);
            $format[] = '%s';
        }

        return $this->wpdb->update(
            $this->table_prenotazioni,
            $update_data,
            array('id' => $prenotazione_id),
            $format,
            array('%d')
        ) !== false;
    }

    /**
     * Controlla conflitti di prenotazione
     *
     * @since 1.0.0
     * @param int $aula_id
     * @param string $data_prenotazione
     * @param string $ora_inizio
     * @param string $ora_fine
     * @param int $exclude_id ID da escludere dal controllo
     * @return bool
     */
    public function check_booking_conflict($aula_id, $data_prenotazione, $ora_inizio, $ora_fine, $exclude_id = null) {
        $where = "WHERE aula_id = %d AND data_prenotazione = %s AND stato IN ('confermata', 'in_attesa') AND (
            (ora_inizio < %s AND ora_fine > %s) OR
            (ora_inizio < %s AND ora_fine > %s) OR
            (ora_inizio >= %s AND ora_fine <= %s)
        )";

        $params = array(
            $aula_id,
            $data_prenotazione,
            $ora_fine, $ora_inizio,
            $ora_inizio, $ora_inizio,
            $ora_inizio, $ora_fine
        );

        if ($exclude_id) {
            $where .= " AND id != %d";
            $params[] = $exclude_id;
        }

        $count = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_prenotazioni} {$where}",
                ...$params
            )
        );

        return $count > 0;
    }

    /**
     * OPERAZIONI IMPOSTAZIONI
     */

    /**
     * Ottieni le impostazioni del plugin
     *
     * @since 1.0.0
     * @return object|null
     */
    public function get_impostazioni() {
        return $this->wpdb->get_row("SELECT * FROM {$this->table_impostazioni} LIMIT 1");
    }

    /**
     * Aggiorna le impostazioni
     *
     * @since 1.0.0
     * @param array $data
     * @return bool
     */
    public function update_impostazioni($data) {
        $impostazioni_data = array(
            'conferma_automatica' => !empty($data['conferma_automatica']) ? 1 : 0,
            'email_notifica_admin' => maybe_serialize($data['email_notifica_admin']),
            'template_email_conferma' => wp_kses_post($data['template_email_conferma']),
            'template_email_rifiuto' => wp_kses_post($data['template_email_rifiuto']),
            'template_email_admin' => wp_kses_post($data['template_email_admin']),
            'giorni_prenotazione_futura_max' => absint($data['giorni_prenotazione_futura_max']),
            'ore_anticipo_prenotazione_min' => absint($data['ore_anticipo_prenotazione_min']),
            'max_prenotazioni_per_utente_giorno' => absint($data['max_prenotazioni_per_utente_giorno']),
            'abilita_recaptcha' => !empty($data['abilita_recaptcha']) ? 1 : 0,
            'recaptcha_site_key' => sanitize_text_field($data['recaptcha_site_key']),
            'recaptcha_secret_key' => sanitize_text_field($data['recaptcha_secret_key']),
            'colore_slot_libero' => sanitize_text_field($data['colore_slot_libero']),
            'colore_slot_occupato' => sanitize_text_field($data['colore_slot_occupato']),
            'colore_slot_attesa' => sanitize_text_field($data['colore_slot_attesa']),
            'updated_at' => current_time('mysql')
        );

        $format = array('%d', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s');

        return $this->wpdb->update(
            $this->table_impostazioni,
            $impostazioni_data,
            array('id' => 1),
            $format,
            array('%d')
        ) !== false;
    }

    /**
     * METODI DI UTILITÀ
     */

    /**
     * Genera codice prenotazione univoco
     *
     * @since 1.0.0
     * @return string
     */
    private function generate_booking_code() {
        do {
            $code = strtoupper(wp_generate_password(8, false, false));
        } while ($this->wpdb->get_var($this->wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_prenotazioni} WHERE codice_prenotazione = %s",
            $code
        )) > 0);

        return $code;
    }

    /**
     * Ottieni statistiche per il dashboard
     *
     * @since 1.0.0
     * @return array
     */
    public function get_dashboard_stats() {
        $stats = array();

        // Totale aule
        $stats['total_aule'] = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_aule}");

        // Aule attive
        $stats['aule_attive'] = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_aule} WHERE stato = %s",
                'attiva'
            )
        );

        // Prenotazioni oggi
        $stats['prenotazioni_oggi'] = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_prenotazioni} WHERE data_prenotazione = %s",
                current_time('Y-m-d')
            )
        );

        // Prenotazioni in attesa
        $stats['prenotazioni_attesa'] = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_prenotazioni} WHERE stato = %s",
                'in_attesa'
            )
        );

        // Prenotazioni questo mese
        $stats['prenotazioni_mese'] = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_prenotazioni} WHERE MONTH(data_prenotazione) = MONTH(CURDATE()) AND YEAR(data_prenotazione) = YEAR(CURDATE())"
            )
        );

        return $stats;
    }
}