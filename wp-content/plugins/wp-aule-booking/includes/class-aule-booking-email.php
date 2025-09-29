<?php

/**
 * Gestisce l'invio delle email di notifica
 *
 * Questa classe gestisce l'invio di email per conferme, rifiuti e notifiche admin
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/includes
 */

class Aule_Booking_Email {

    /**
     * L'istanza del database
     *
     * @since 1.0.0
     * @access private
     * @var Aule_Booking_Database $database
     */
    private $database;

    /**
     * Headers email predefiniti
     *
     * @since 1.0.0
     * @access private
     * @var array $headers
     */
    private $headers;

    /**
     * Costruttore della classe
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->database = new Aule_Booking_Database();

        // Imposta headers email
        $this->headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
        );

        // Applica filtro per personalizzazione headers
        $this->headers = apply_filters('aule_booking_email_headers', $this->headers);
    }

    /**
     * Invia email di conferma prenotazione
     *
     * @since 1.0.0
     * @param int $booking_id ID della prenotazione
     * @return bool
     */
    public function send_booking_confirmation($booking_id) {
        $booking = $this->database->get_prenotazione_by_id($booking_id);

        if (!$booking) {
            return false;
        }

        $settings = $this->database->get_impostazioni();
        $template = !empty($settings->template_email_conferma) ? $settings->template_email_conferma : $this->get_default_confirmation_template();

        $subject = sprintf(__('[%s] Prenotazione Confermata - %s', 'aule-booking'), get_bloginfo('name'), $booking->nome_aula);

        $message = $this->replace_placeholders($template, $booking);
        $message = $this->wrap_email_template($message, __('Prenotazione Confermata', 'aule-booking'));

        $result = wp_mail(
            $booking->email_richiedente,
            $subject,
            $message,
            $this->headers
        );

        // Log dell'invio email
        $this->log_email('confirmation', $booking_id, $booking->email_richiedente, $result);

        return $result;
    }

    /**
     * Invia email di rifiuto prenotazione
     *
     * @since 1.0.0
     * @param int $booking_id ID della prenotazione
     * @return bool
     */
    public function send_booking_rejection($booking_id) {
        $booking = $this->database->get_prenotazione_by_id($booking_id);

        if (!$booking) {
            return false;
        }

        $settings = $this->database->get_impostazioni();
        $template = !empty($settings->template_email_rifiuto) ? $settings->template_email_rifiuto : $this->get_default_rejection_template();

        $subject = sprintf(__('[%s] Prenotazione Rifiutata - %s', 'aule-booking'), get_bloginfo('name'), $booking->nome_aula);

        $message = $this->replace_placeholders($template, $booking);
        $message = $this->wrap_email_template($message, __('Prenotazione Rifiutata', 'aule-booking'));

        $result = wp_mail(
            $booking->email_richiedente,
            $subject,
            $message,
            $this->headers
        );

        // Log dell'invio email
        $this->log_email('rejection', $booking_id, $booking->email_richiedente, $result);

        return $result;
    }

    /**
     * Invia notifica admin per nuova prenotazione
     *
     * @since 1.0.0
     * @param int $booking_id ID della prenotazione
     * @return bool
     */
    public function send_admin_notification($booking_id) {
        $booking = $this->database->get_prenotazione_by_id($booking_id);

        if (!$booking) {
            return false;
        }

        $settings = $this->database->get_impostazioni();

        // Ottieni email admin
        $admin_emails = array();
        if (!empty($settings->email_notifica_admin)) {
            $admin_emails = maybe_unserialize($settings->email_notifica_admin);
        }

        if (empty($admin_emails)) {
            $admin_emails = array(get_option('admin_email'));
        }

        $template = !empty($settings->template_email_admin) ? $settings->template_email_admin : $this->get_default_admin_template();

        $subject = sprintf(__('[%s] Nuova Prenotazione - %s', 'aule-booking'), get_bloginfo('name'), $booking->nome_aula);

        $message = $this->replace_placeholders($template, $booking);
        $message = $this->wrap_email_template($message, __('Nuova Prenotazione', 'aule-booking'));

        $success_count = 0;

        foreach ($admin_emails as $admin_email) {
            if (is_email($admin_email)) {
                $result = wp_mail(
                    trim($admin_email),
                    $subject,
                    $message,
                    $this->headers
                );

                if ($result) {
                    $success_count++;
                }

                // Log dell'invio email
                $this->log_email('admin_notification', $booking_id, $admin_email, $result);
            }
        }

        return $success_count > 0;
    }

    /**
     * Invia reminder prenotazione
     *
     * @since 1.0.0
     * @param int $booking_id ID della prenotazione
     * @return bool
     */
    public function send_booking_reminder($booking_id) {
        $booking = $this->database->get_prenotazione_by_id($booking_id);

        if (!$booking || $booking->stato !== 'confermata') {
            return false;
        }

        $subject = sprintf(__('[%s] Promemoria Prenotazione - %s', 'aule-booking'), get_bloginfo('name'), $booking->nome_aula);

        $message = $this->get_reminder_template();
        $message = $this->replace_placeholders($message, $booking);
        $message = $this->wrap_email_template($message, __('Promemoria Prenotazione', 'aule-booking'));

        $result = wp_mail(
            $booking->email_richiedente,
            $subject,
            $message,
            $this->headers
        );

        // Log dell'invio email
        $this->log_email('reminder', $booking_id, $booking->email_richiedente, $result);

        return $result;
    }

    /**
     * Invia report settimanale agli admin
     *
     * @since 1.0.0
     * @return bool
     */
    public function send_weekly_report() {
        $settings = $this->database->get_impostazioni();

        // Ottieni email admin
        $admin_emails = array();
        if (!empty($settings->email_notifica_admin)) {
            $admin_emails = maybe_unserialize($settings->email_notifica_admin);
        }

        if (empty($admin_emails)) {
            $admin_emails = array(get_option('admin_email'));
        }

        // Calcola date settimana scorsa
        $end_date = date('Y-m-d', strtotime('last sunday'));
        $start_date = date('Y-m-d', strtotime($end_date . ' -6 days'));

        // Ottieni statistiche
        $stats = $this->get_weekly_stats($start_date, $end_date);

        $subject = sprintf(__('[%s] Report Settimanale Prenotazioni - %s', 'aule-booking'),
            get_bloginfo('name'),
            date_i18n('d/m/Y', strtotime($start_date)) . ' - ' . date_i18n('d/m/Y', strtotime($end_date))
        );

        $message = $this->get_weekly_report_template($stats, $start_date, $end_date);
        $message = $this->wrap_email_template($message, __('Report Settimanale', 'aule-booking'));

        $success_count = 0;

        foreach ($admin_emails as $admin_email) {
            if (is_email($admin_email)) {
                $result = wp_mail(
                    trim($admin_email),
                    $subject,
                    $message,
                    $this->headers
                );

                if ($result) {
                    $success_count++;
                }
            }
        }

        return $success_count > 0;
    }

    /**
     * METODI DI UTILITÀ
     */

    /**
     * Sostituisce i placeholder nel template
     *
     * @since 1.0.0
     * @param string $template Template con placeholder
     * @param object $booking Dati prenotazione
     * @return string Template compilato
     */
    private function replace_placeholders($template, $booking) {
        $placeholders = array(
            '{nome_richiedente}' => $booking->nome_richiedente,
            '{cognome_richiedente}' => $booking->cognome_richiedente,
            '{nome_aula}' => $booking->nome_aula ?? '',
            '{ubicazione}' => $booking->ubicazione ?? '',
            '{data_prenotazione}' => date_i18n('l, j F Y', strtotime($booking->data_prenotazione)),
            '{ora_inizio}' => date('H:i', strtotime($booking->ora_inizio)),
            '{ora_fine}' => date('H:i', strtotime($booking->ora_fine)),
            '{motivo}' => $booking->motivo_prenotazione,
            '{stato_prenotazione}' => $this->get_stato_display($booking->stato),
            '{codice_prenotazione}' => $booking->codice_prenotazione ?? '',
            '{note_admin}' => $booking->note_admin ?? '',
            '{link_gestione}' => admin_url('admin.php?page=aule-booking-prenotazioni&search=' . urlencode($booking->email_richiedente)),
            '{sito_nome}' => get_bloginfo('name'),
            '{sito_url}' => get_home_url()
        );

        // Applica filtro per placeholder personalizzati
        $placeholders = apply_filters('aule_booking_email_placeholders', $placeholders, $booking);

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }

    /**
     * Wrappa il messaggio nel template HTML
     *
     * @since 1.0.0
     * @param string $content Contenuto del messaggio
     * @param string $title Titolo dell'email
     * @return string HTML dell'email
     */
    private function wrap_email_template($content, $title) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="it">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo esc_html($title); ?></title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .email-header {
                    background-color: #2271b1;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }
                .email-header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .email-body {
                    padding: 30px;
                }
                .email-footer {
                    background-color: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #e9ecef;
                }
                .button {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #2271b1;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    margin: 10px 0;
                }
                .booking-details {
                    background-color: #f8f9fa;
                    border-left: 4px solid #2271b1;
                    padding: 15px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1><?php echo esc_html($title); ?></h1>
                </div>
                <div class="email-body">
                    <?php echo wp_kses_post(nl2br($content)); ?>
                </div>
                <div class="email-footer">
                    <p>
                        <?php printf(__('Questa email è stata inviata automaticamente da %s', 'aule-booking'), get_bloginfo('name')); ?><br>
                        <a href="<?php echo home_url(); ?>"><?php echo home_url(); ?></a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Template di conferma predefinito
     *
     * @since 1.0.0
     * @return string
     */
    private function get_default_confirmation_template() {
        return "Gentile {nome_richiedente} {cognome_richiedente},

La sua prenotazione è stata confermata con successo.

<div class=\"booking-details\">
<strong>Dettagli della prenotazione:</strong><br>
📍 Aula: {nome_aula}<br>
📍 Ubicazione: {ubicazione}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
📝 Motivo: {motivo}<br>
🔖 Codice prenotazione: {codice_prenotazione}
</div>

Si prega di presentarsi puntualmente all'orario prenotato.

Grazie per aver utilizzato il nostro sistema di prenotazione.

Cordiali saluti,
Il team di {sito_nome}";
    }

    /**
     * Template di rifiuto predefinito
     *
     * @since 1.0.0
     * @return string
     */
    private function get_default_rejection_template() {
        return "Gentile {nome_richiedente} {cognome_richiedente},

Siamo spiacenti di comunicarle che la sua prenotazione è stata rifiutata.

<div class=\"booking-details\">
<strong>Dettagli della prenotazione:</strong><br>
📍 Aula: {nome_aula}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
</div>

<strong>Motivo del rifiuto:</strong><br>
{note_admin}

La invitiamo a contattarci per ulteriori chiarimenti o per effettuare una nuova prenotazione.

Grazie per la comprensione.

Cordiali saluti,
Il team di {sito_nome}";
    }

    /**
     * Template notifica admin predefinito
     *
     * @since 1.0.0
     * @return string
     */
    private function get_default_admin_template() {
        return "È stata ricevuta una nuova prenotazione che richiede approvazione.

<div class=\"booking-details\">
<strong>Dettagli della prenotazione:</strong><br>
👤 Richiedente: {nome_richiedente} {cognome_richiedente}<br>
📧 Email: {email_richiedente}<br>
📍 Aula: {nome_aula}<br>
📍 Ubicazione: {ubicazione}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
📝 Motivo: {motivo}<br>
🔖 Codice: {codice_prenotazione}
</div>

<a href=\"{link_gestione}\" class=\"button\">Gestisci Prenotazione</a>

Accedi all'area admin per approvare o rifiutare la prenotazione.";
    }

    /**
     * Template reminder predefinito
     *
     * @since 1.0.0
     * @return string
     */
    private function get_reminder_template() {
        return "Gentile {nome_richiedente} {cognome_richiedente},

Le ricordiamo che ha una prenotazione confermata per domani.

<div class=\"booking-details\">
<strong>Dettagli della prenotazione:</strong><br>
📍 Aula: {nome_aula}<br>
📍 Ubicazione: {ubicazione}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
🔖 Codice prenotazione: {codice_prenotazione}
</div>

Si prega di presentarsi puntualmente.

Grazie,
Il team di {sito_nome}";
    }

    /**
     * Ottieni statistiche settimanali
     *
     * @since 1.0.0
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    private function get_weekly_stats($start_date, $end_date) {
        global $wpdb;

        $table_prenotazioni = $wpdb->prefix . 'aule_booking_prenotazioni';
        $table_aule = $wpdb->prefix . 'aule_booking_aule';

        // Prenotazioni totali
        $total_bookings = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table_prenotazioni}
             WHERE data_prenotazione BETWEEN %s AND %s",
            $start_date, $end_date
        ));

        // Prenotazioni per stato
        $bookings_by_status = $wpdb->get_results($wpdb->prepare(
            "SELECT stato, COUNT(*) as count
             FROM {$table_prenotazioni}
             WHERE data_prenotazione BETWEEN %s AND %s
             GROUP BY stato",
            $start_date, $end_date
        ));

        // Aule più utilizzate
        $most_used_rooms = $wpdb->get_results($wpdb->prepare(
            "SELECT a.nome_aula, COUNT(*) as count
             FROM {$table_prenotazioni} p
             LEFT JOIN {$table_aule} a ON p.aula_id = a.id
             WHERE p.data_prenotazione BETWEEN %s AND %s
             GROUP BY p.aula_id
             ORDER BY count DESC
             LIMIT 5",
            $start_date, $end_date
        ));

        return array(
            'total_bookings' => $total_bookings,
            'bookings_by_status' => $bookings_by_status,
            'most_used_rooms' => $most_used_rooms,
            'period' => array(
                'start' => $start_date,
                'end' => $end_date
            )
        );
    }

    /**
     * Template report settimanale
     *
     * @since 1.0.0
     * @param array $stats Statistiche
     * @param string $start_date Data inizio
     * @param string $end_date Data fine
     * @return string
     */
    private function get_weekly_report_template($stats, $start_date, $end_date) {
        $content = "Report settimanale delle prenotazioni aule studio.\n\n";

        $content .= "<div class=\"booking-details\">";
        $content .= "<strong>Periodo:</strong> " . date_i18n('d/m/Y', strtotime($start_date)) . " - " . date_i18n('d/m/Y', strtotime($end_date)) . "<br>";
        $content .= "<strong>Prenotazioni totali:</strong> " . $stats['total_bookings'] . "<br><br>";

        if (!empty($stats['bookings_by_status'])) {
            $content .= "<strong>Prenotazioni per stato:</strong><br>";
            foreach ($stats['bookings_by_status'] as $status) {
                $content .= "• " . $this->get_stato_display($status->stato) . ": " . $status->count . "<br>";
            }
            $content .= "<br>";
        }

        if (!empty($stats['most_used_rooms'])) {
            $content .= "<strong>Aule più utilizzate:</strong><br>";
            foreach ($stats['most_used_rooms'] as $room) {
                $content .= "• " . $room->nome_aula . ": " . $room->count . " prenotazioni<br>";
            }
        }

        $content .= "</div>";

        return $content;
    }

    /**
     * Ottieni display nome stato
     *
     * @since 1.0.0
     * @param string $stato
     * @return string
     */
    private function get_stato_display($stato) {
        $stati = array(
            'in_attesa' => __('In Attesa', 'aule-booking'),
            'confermata' => __('Confermata', 'aule-booking'),
            'rifiutata' => __('Rifiutata', 'aule-booking'),
            'cancellata' => __('Cancellata', 'aule-booking')
        );

        return isset($stati[$stato]) ? $stati[$stato] : $stato;
    }

    /**
     * Log invio email
     *
     * @since 1.0.0
     * @param string $type Tipo email
     * @param int $booking_id ID prenotazione
     * @param string $recipient Destinatario
     * @param bool $success Successo invio
     */
    private function log_email($type, $booking_id, $recipient, $success) {
        // Log base WordPress
        $message = sprintf(
            'Email %s %s - Booking ID: %d, Recipient: %s, Type: %s',
            $success ? 'sent successfully' : 'failed',
            $success ? '✓' : '✗',
            $booking_id,
            $recipient,
            $type
        );

        if ($success) {
            error_log('[Aule Booking Email] ' . $message);
        } else {
            error_log('[Aule Booking Email ERROR] ' . $message);
        }

        // Possibilità di estendere con logging più avanzato
        do_action('aule_booking_email_logged', $type, $booking_id, $recipient, $success);
    }
}