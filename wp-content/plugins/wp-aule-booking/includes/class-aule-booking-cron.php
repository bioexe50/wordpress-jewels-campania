<?php

/**
 * Gestisce i cron job del plugin
 *
 * Questa classe gestisce tutte le attività pianificate del plugin
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/includes
 */

class Aule_Booking_Cron {

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
     * L'istanza email handler
     *
     * @since 1.0.0
     * @access private
     * @var Aule_Booking_Email $email
     */
    private $email;

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
        $this->email = new Aule_Booking_Email();
    }

    /**
     * Pulisce prenotazioni scadute
     *
     * Eseguito giornalmente per rimuovere prenotazioni vecchie
     * e liberare spazio nel database
     *
     * @since 1.0.0
     */
    public function cleanup_expired_bookings() {
        global $wpdb;

        $table_prenotazioni = $wpdb->prefix . 'aule_booking_prenotazioni';

        // Log inizio operazione
        error_log('[Aule Booking Cron] Inizio pulizia prenotazioni scadute');

        try {
            // Rimuovi prenotazioni rifiutate o cancellate più vecchie di 90 giorni
            $deleted_old = $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$table_prenotazioni}
                     WHERE stato IN ('rifiutata', 'cancellata')
                     AND data_prenotazione < %s",
                    date('Y-m-d', strtotime('-90 days'))
                )
            );

            // Aggiorna a 'scaduta' prenotazioni confermate passate di più di 7 giorni
            $expired_confirmed = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$table_prenotazioni}
                     SET stato = 'cancellata', updated_at = %s
                     WHERE stato = 'confermata'
                     AND data_prenotazione < %s",
                    current_time('mysql'),
                    date('Y-m-d', strtotime('-7 days'))
                )
            );

            // Aggiorna a 'scaduta' prenotazioni in attesa passate di più di 2 giorni
            $expired_pending = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$table_prenotazioni}
                     SET stato = 'cancellata', updated_at = %s
                     WHERE stato = 'in_attesa'
                     AND data_prenotazione < %s",
                    current_time('mysql'),
                    date('Y-m-d', strtotime('-2 days'))
                )
            );

            $total_processed = $deleted_old + $expired_confirmed + $expired_pending;

            error_log(sprintf(
                '[Aule Booking Cron] Pulizia completata: %d prenotazioni eliminate, %d confermate scadute, %d in attesa scadute',
                $deleted_old,
                $expired_confirmed,
                $expired_pending
            ));

            // Ottimizza tabella se sono stati processati molti record
            if ($total_processed > 100) {
                $wpdb->query("OPTIMIZE TABLE {$table_prenotazioni}");
                error_log('[Aule Booking Cron] Tabella prenotazioni ottimizzata');
            }

        } catch (Exception $e) {
            error_log('[Aule Booking Cron ERROR] Errore durante pulizia prenotazioni: ' . $e->getMessage());
        }

        // Hook per estensioni personalizzate
        do_action('aule_booking_after_cleanup', $total_processed);
    }

    /**
     * Invia reminder per prenotazioni del giorno successivo
     *
     * Eseguito ogni ora per controllare prenotazioni
     * che necessitano di un reminder
     *
     * @since 1.0.0
     */
    public function send_booking_reminders() {
        // Ottieni prenotazioni confermate per domani
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $bookings = $this->database->get_prenotazioni(array(
            'stato' => 'confermata',
            'data_da' => $tomorrow,
            'data_a' => $tomorrow
        ));

        if (empty($bookings)) {
            return;
        }

        error_log(sprintf('[Aule Booking Cron] Invio reminder per %d prenotazioni del %s', count($bookings), $tomorrow));

        $sent_count = 0;
        $failed_count = 0;

        foreach ($bookings as $booking) {
            // Controlla se il reminder è già stato inviato (usando meta o campo custom)
            $reminder_sent = get_transient('aule_booking_reminder_sent_' . $booking->id);

            if (!$reminder_sent) {
                try {
                    $result = $this->email->send_booking_reminder($booking->id);

                    if ($result) {
                        $sent_count++;
                        // Marca come inviato per 48 ore
                        set_transient('aule_booking_reminder_sent_' . $booking->id, true, 48 * HOUR_IN_SECONDS);
                    } else {
                        $failed_count++;
                    }

                    // Pausa breve per evitare sovraccarico server email
                    usleep(500000); // 0.5 secondi

                } catch (Exception $e) {
                    $failed_count++;
                    error_log('[Aule Booking Cron ERROR] Errore invio reminder booking ID ' . $booking->id . ': ' . $e->getMessage());
                }
            }
        }

        if ($sent_count > 0 || $failed_count > 0) {
            error_log(sprintf(
                '[Aule Booking Cron] Reminder completati: %d inviati, %d falliti',
                $sent_count,
                $failed_count
            ));
        }

        // Hook per estensioni personalizzate
        do_action('aule_booking_after_reminders', $sent_count, $failed_count);
    }

    /**
     * Invia report settimanale agli amministratori
     *
     * Eseguito ogni lunedì per inviare statistiche
     * della settimana precedente
     *
     * @since 1.0.0
     */
    public function send_weekly_report() {
        error_log('[Aule Booking Cron] Generazione report settimanale');

        try {
            $result = $this->email->send_weekly_report();

            if ($result) {
                error_log('[Aule Booking Cron] Report settimanale inviato con successo');
            } else {
                error_log('[Aule Booking Cron ERROR] Errore nell\'invio del report settimanale');
            }

        } catch (Exception $e) {
            error_log('[Aule Booking Cron ERROR] Errore durante generazione report: ' . $e->getMessage());
        }

        // Hook per estensioni personalizzate
        do_action('aule_booking_after_weekly_report', $result);
    }

    /**
     * Verifica integrità database
     *
     * Metodo per verifiche periodiche dell'integrità dei dati
     *
     * @since 1.0.0
     */
    public function check_database_integrity() {
        global $wpdb;

        error_log('[Aule Booking Cron] Verifica integrità database');

        $issues = array();

        try {
            // Controlla prenotazioni con aule inesistenti
            $table_prenotazioni = $wpdb->prefix . 'aule_booking_prenotazioni';
            $table_aule = $wpdb->prefix . 'aule_booking_aule';

            $orphaned_bookings = $wpdb->get_var(
                "SELECT COUNT(*)
                 FROM {$table_prenotazioni} p
                 LEFT JOIN {$table_aule} a ON p.aula_id = a.id
                 WHERE a.id IS NULL"
            );

            if ($orphaned_bookings > 0) {
                $issues[] = sprintf('Trovate %d prenotazioni con aule inesistenti', $orphaned_bookings);
            }

            // Controlla prenotazioni duplicate
            $duplicate_bookings = $wpdb->get_var(
                "SELECT COUNT(*) FROM (
                    SELECT aula_id, data_prenotazione, ora_inizio, ora_fine, COUNT(*) as cnt
                    FROM {$table_prenotazioni}
                    WHERE stato IN ('confermata', 'in_attesa')
                    GROUP BY aula_id, data_prenotazione, ora_inizio, ora_fine
                    HAVING cnt > 1
                ) as duplicates"
            );

            if ($duplicate_bookings > 0) {
                $issues[] = sprintf('Trovate %d prenotazioni duplicate', $duplicate_bookings);
            }

            // Controlla slot disponibilità con aule inesistenti
            $table_slot = $wpdb->prefix . 'aule_booking_slot_disponibilita';

            $orphaned_slots = $wpdb->get_var(
                "SELECT COUNT(*)
                 FROM {$table_slot} s
                 LEFT JOIN {$table_aule} a ON s.aula_id = a.id
                 WHERE a.id IS NULL"
            );

            if ($orphaned_slots > 0) {
                $issues[] = sprintf('Trovati %d slot con aule inesistenti', $orphaned_slots);
            }

            if (!empty($issues)) {
                error_log('[Aule Booking Cron WARNING] Problemi integrità database: ' . implode(', ', $issues));

                // Invia notifica admin se ci sono problemi critici
                if (count($issues) >= 3) {
                    $this->send_integrity_alert($issues);
                }
            } else {
                error_log('[Aule Booking Cron] Database integro');
            }

        } catch (Exception $e) {
            error_log('[Aule Booking Cron ERROR] Errore verifica integrità: ' . $e->getMessage());
        }

        // Hook per estensioni personalizzate
        do_action('aule_booking_after_integrity_check', $issues);
    }

    /**
     * Aggiorna statistiche cache
     *
     * Rigenera le statistiche in cache per migliorare performance dashboard
     *
     * @since 1.0.0
     */
    public function update_stats_cache() {
        try {
            // Rigenera statistiche dashboard
            $stats = $this->database->get_dashboard_stats();

            // Salva in transient per 1 ora
            set_transient('aule_booking_dashboard_stats', $stats, HOUR_IN_SECONDS);

            // Calcola statistiche mensili
            $monthly_stats = $this->calculate_monthly_stats();
            set_transient('aule_booking_monthly_stats', $monthly_stats, HOUR_IN_SECONDS);

            error_log('[Aule Booking Cron] Cache statistiche aggiornata');

        } catch (Exception $e) {
            error_log('[Aule Booking Cron ERROR] Errore aggiornamento cache: ' . $e->getMessage());
        }
    }

    /**
     * METODI DI UTILITÀ
     */

    /**
     * Invia alert integrità database
     *
     * @since 1.0.0
     * @param array $issues Lista problemi trovati
     */
    private function send_integrity_alert($issues) {
        $settings = $this->database->get_impostazioni();

        $admin_emails = array();
        if (!empty($settings->email_notifica_admin)) {
            $admin_emails = maybe_unserialize($settings->email_notifica_admin);
        }

        if (empty($admin_emails)) {
            $admin_emails = array(get_option('admin_email'));
        }

        $subject = sprintf('[%s] Alert Integrità Database - Aule Booking', get_bloginfo('name'));

        $message = "Sono stati rilevati problemi di integrità nel database del sistema prenotazioni aule:\n\n";
        foreach ($issues as $issue) {
            $message .= "• " . $issue . "\n";
        }
        $message .= "\nSi consiglia di verificare manualmente il database e contattare l'assistenza se necessario.\n\n";
        $message .= "Messaggio generato automaticamente il " . current_time('d/m/Y H:i:s');

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
        );

        foreach ($admin_emails as $admin_email) {
            if (is_email($admin_email)) {
                wp_mail(trim($admin_email), $subject, nl2br($message), $headers);
            }
        }
    }

    /**
     * Calcola statistiche mensili
     *
     * @since 1.0.0
     * @return array
     */
    private function calculate_monthly_stats() {
        global $wpdb;

        $table_prenotazioni = $wpdb->prefix . 'aule_booking_prenotazioni';
        $current_month = date('Y-m');

        $stats = array();

        // Prenotazioni per mese corrente
        $stats['current_month_bookings'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table_prenotazioni}
             WHERE DATE_FORMAT(data_prenotazione, '%%Y-%%m') = %s",
            $current_month
        ));

        // Prenotazioni per mese precedente
        $last_month = date('Y-m', strtotime('-1 month'));
        $stats['last_month_bookings'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table_prenotazioni}
             WHERE DATE_FORMAT(data_prenotazione, '%%Y-%%m') = %s",
            $last_month
        ));

        // Calcola crescita percentuale
        if ($stats['last_month_bookings'] > 0) {
            $stats['growth_percentage'] = round(
                (($stats['current_month_bookings'] - $stats['last_month_bookings']) / $stats['last_month_bookings']) * 100,
                1
            );
        } else {
            $stats['growth_percentage'] = $stats['current_month_bookings'] > 0 ? 100 : 0;
        }

        // Orari più utilizzati
        $stats['popular_times'] = $wpdb->get_results(
            "SELECT HOUR(ora_inizio) as ora, COUNT(*) as count
             FROM {$table_prenotazioni}
             WHERE data_prenotazione >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             AND stato = 'confermata'
             GROUP BY HOUR(ora_inizio)
             ORDER BY count DESC
             LIMIT 5"
        );

        return $stats;
    }

    /**
     * Gestione errori cron
     *
     * @since 1.0.0
     * @param string $context Contesto dell'errore
     * @param string $message Messaggio di errore
     */
    private function handle_cron_error($context, $message) {
        // Log errore
        error_log(sprintf('[Aule Booking Cron ERROR] %s: %s', $context, $message));

        // Incrementa contatore errori
        $error_count = get_transient('aule_booking_cron_errors') ?: 0;
        $error_count++;
        set_transient('aule_booking_cron_errors', $error_count, DAY_IN_SECONDS);

        // Se ci sono troppi errori, invia notifica
        if ($error_count >= 10) {
            $this->send_error_notification($error_count);
            delete_transient('aule_booking_cron_errors');
        }
    }

    /**
     * Invia notifica errori cron
     *
     * @since 1.0.0
     * @param int $error_count Numero di errori
     */
    private function send_error_notification($error_count) {
        $admin_email = get_option('admin_email');
        $subject = sprintf('[%s] Errori Cron Frequenti - Aule Booking', get_bloginfo('name'));

        $message = sprintf(
            "Sono stati rilevati %d errori nei processi automatici del sistema prenotazioni aule nelle ultime 24 ore.\n\n" .
            "Si consiglia di verificare i log del server per maggiori dettagli.\n\n" .
            "Messaggio generato automaticamente il %s",
            $error_count,
            current_time('d/m/Y H:i:s')
        );

        wp_mail($admin_email, $subject, $message);
    }
}