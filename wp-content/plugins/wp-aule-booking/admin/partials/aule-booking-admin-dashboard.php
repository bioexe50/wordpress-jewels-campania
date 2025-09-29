<?php

/**
 * Template per la dashboard amministratore
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/admin/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Dashboard Gestione Aule', 'aule-booking'); ?></h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Dati salvati correttamente.', 'aule-booking'); ?></p>
        </div>
    <?php endif; ?>

    <!-- Statistiche Overview -->
    <div class="aule-booking-stats-grid">
        <div class="aule-booking-stat-card">
            <div class="stat-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['total_aule']); ?></h3>
                <p><?php _e('Totale Aule', 'aule-booking'); ?></p>
            </div>
        </div>

        <div class="aule-booking-stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['aule_attive']); ?></h3>
                <p><?php _e('Aule Attive', 'aule-booking'); ?></p>
            </div>
        </div>

        <div class="aule-booking-stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['prenotazioni_oggi']); ?></h3>
                <p><?php _e('Prenotazioni Oggi', 'aule-booking'); ?></p>
            </div>
        </div>

        <div class="aule-booking-stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['prenotazioni_attesa']); ?></h3>
                <p><?php _e('In Attesa', 'aule-booking'); ?></p>
                <?php if ($stats['prenotazioni_attesa'] > 0): ?>
                    <a href="<?php echo admin_url('admin.php?page=aule-booking-prenotazioni&stato=in_attesa'); ?>" class="button button-secondary">
                        <?php _e('Gestisci', 'aule-booking'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contenuto principale -->
    <div class="aule-booking-dashboard-content">

        <!-- Sezione Prenotazioni Recenti -->
        <div class="aule-booking-dashboard-section">
            <div class="section-header">
                <h2><?php _e('Prenotazioni Recenti', 'aule-booking'); ?></h2>
                <a href="<?php echo admin_url('admin.php?page=aule-booking-prenotazioni'); ?>" class="button">
                    <?php _e('Vedi Tutte', 'aule-booking'); ?>
                </a>
            </div>

            <?php if (!empty($recent_bookings)): ?>
                <div class="aule-booking-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Richiedente', 'aule-booking'); ?></th>
                                <th><?php _e('Aula', 'aule-booking'); ?></th>
                                <th><?php _e('Data', 'aule-booking'); ?></th>
                                <th><?php _e('Orario', 'aule-booking'); ?></th>
                                <th><?php _e('Stato', 'aule-booking'); ?></th>
                                <th><?php _e('Azioni', 'aule-booking'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_bookings as $booking): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html($booking->nome_richiedente . ' ' . $booking->cognome_richiedente); ?></strong><br>
                                        <small><?php echo esc_html($booking->email_richiedente); ?></small>
                                    </td>
                                    <td>
                                        <?php echo esc_html($booking->nome_aula); ?><br>
                                        <small><?php echo esc_html($booking->ubicazione); ?></small>
                                    </td>
                                    <td><?php echo date_i18n('d/m/Y', strtotime($booking->data_prenotazione)); ?></td>
                                    <td>
                                        <?php echo date('H:i', strtotime($booking->ora_inizio)) . ' - ' . date('H:i', strtotime($booking->ora_fine)); ?>
                                    </td>
                                    <td>
                                        <span class="booking-status booking-status-<?php echo esc_attr($booking->stato); ?>">
                                            <?php
                                            switch ($booking->stato) {
                                                case 'in_attesa':
                                                    _e('In Attesa', 'aule-booking');
                                                    break;
                                                case 'confermata':
                                                    _e('Confermata', 'aule-booking');
                                                    break;
                                                case 'rifiutata':
                                                    _e('Rifiutata', 'aule-booking');
                                                    break;
                                                case 'cancellata':
                                                    _e('Cancellata', 'aule-booking');
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($booking->stato === 'in_attesa'): ?>
                                            <button class="button button-small approve-booking" data-id="<?php echo $booking->id; ?>">
                                                <i class="fas fa-check"></i> <?php _e('Approva', 'aule-booking'); ?>
                                            </button>
                                            <button class="button button-small reject-booking" data-id="<?php echo $booking->id; ?>">
                                                <i class="fas fa-times"></i> <?php _e('Rifiuta', 'aule-booking'); ?>
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?php echo admin_url('admin.php?page=aule-booking-prenotazioni&search=' . urlencode($booking->email_richiedente)); ?>" class="button button-small">
                                            <i class="fas fa-eye"></i> <?php _e('Dettagli', 'aule-booking'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-data"><?php _e('Nessuna prenotazione recente.', 'aule-booking'); ?></p>
            <?php endif; ?>
        </div>

        <!-- Sezione Link Rapidi -->
        <div class="aule-booking-dashboard-section">
            <div class="section-header">
                <h2><?php _e('Link Rapidi', 'aule-booking'); ?></h2>
            </div>

            <div class="aule-booking-quick-links">
                <a href="<?php echo admin_url('admin.php?page=aule-booking-add-aula'); ?>" class="quick-link-card">
                    <i class="fas fa-plus-circle"></i>
                    <h3><?php _e('Aggiungi Aula', 'aule-booking'); ?></h3>
                    <p><?php _e('Crea una nuova aula studio', 'aule-booking'); ?></p>
                </a>

                <a href="<?php echo admin_url('admin.php?page=aule-booking-slot'); ?>" class="quick-link-card">
                    <i class="fas fa-clock"></i>
                    <h3><?php _e('Gestisci Slot', 'aule-booking'); ?></h3>
                    <p><?php _e('Configura orari disponibilità', 'aule-booking'); ?></p>
                </a>

                <a href="<?php echo admin_url('admin.php?page=aule-booking-prenotazioni&stato=in_attesa'); ?>" class="quick-link-card">
                    <i class="fas fa-tasks"></i>
                    <h3><?php _e('Prenotazioni in Attesa', 'aule-booking'); ?></h3>
                    <p><?php printf(__('%d prenotazioni da gestire', 'aule-booking'), $stats['prenotazioni_attesa']); ?></p>
                </a>

                <a href="<?php echo admin_url('admin.php?page=aule-booking-reports'); ?>" class="quick-link-card">
                    <i class="fas fa-chart-bar"></i>
                    <h3><?php _e('Report', 'aule-booking'); ?></h3>
                    <p><?php _e('Visualizza statistiche dettagliate', 'aule-booking'); ?></p>
                </a>
            </div>
        </div>

        <!-- Sezione Statistiche Mensili -->
        <div class="aule-booking-dashboard-section">
            <div class="section-header">
                <h2><?php _e('Statistiche Mensili', 'aule-booking'); ?></h2>
            </div>

            <div class="monthly-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html($stats['prenotazioni_mese']); ?></span>
                    <span class="stat-label"><?php _e('Prenotazioni questo mese', 'aule-booking'); ?></span>
                </div>

                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html(round($stats['prenotazioni_mese'] / max(1, date('j')) * date('t'))); ?></span>
                    <span class="stat-label"><?php _e('Proiezione mensile', 'aule-booking'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per gestione prenotazioni rapida -->
<div class="modal fade" id="quickBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('Gestisci Prenotazione', 'aule-booking'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickBookingForm">
                    <input type="hidden" id="booking_id" name="booking_id">
                    <div class="mb-3">
                        <label for="admin_note" class="form-label"><?php _e('Note Admin', 'aule-booking'); ?></label>
                        <textarea class="form-control" id="admin_note" name="admin_note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php _e('Chiudi', 'aule-booking'); ?></button>
                <button type="button" class="btn btn-danger" id="rejectBookingBtn"><?php _e('Rifiuta', 'aule-booking'); ?></button>
                <button type="button" class="btn btn-success" id="approveBookingBtn"><?php _e('Approva', 'aule-booking'); ?></button>
            </div>
        </div>
    </div>
</div>

<style>
/* Stili per la dashboard */
.aule-booking-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.aule-booking-stat-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 2em;
    color: #2271b1;
    width: 50px;
    text-align: center;
}

.stat-content h3 {
    margin: 0;
    font-size: 2em;
    font-weight: bold;
    color: #1d2327;
}

.stat-content p {
    margin: 5px 0 0;
    color: #646970;
    font-size: 14px;
}

.aule-booking-dashboard-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    margin-top: 30px;
}

.aule-booking-dashboard-section {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 20px 0;
    border-bottom: 1px solid #f0f0f1;
    margin-bottom: 20px;
}

.section-header h2 {
    margin: 0;
    font-size: 1.3em;
}

.aule-booking-table-container {
    padding: 0 20px 20px;
    overflow-x: auto;
}

.aule-booking-quick-links {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
    padding: 0 20px 20px;
}

.quick-link-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
}

.quick-link-card:hover {
    border-color: #2271b1;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.quick-link-card i {
    font-size: 1.5em;
    color: #2271b1;
}

.quick-link-card h3 {
    margin: 0;
    font-size: 1em;
    font-weight: 600;
}

.quick-link-card p {
    margin: 0;
    font-size: 0.9em;
    color: #646970;
}

.monthly-stats {
    display: flex;
    gap: 30px;
    padding: 0 20px 20px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2em;
    font-weight: bold;
    color: #2271b1;
}

.stat-label {
    font-size: 0.9em;
    color: #646970;
}

.booking-status {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.booking-status-in_attesa {
    background-color: #fef3cd;
    color: #856404;
}

.booking-status-confermata {
    background-color: #d1edff;
    color: #0c63e4;
}

.booking-status-rifiutata {
    background-color: #f8d7da;
    color: #721c24;
}

.booking-status-cancellata {
    background-color: #f5f5f5;
    color: #6c757d;
}

.no-data {
    text-align: center;
    color: #646970;
    font-style: italic;
    padding: 40px 20px;
}

@media (max-width: 768px) {
    .aule-booking-dashboard-content {
        grid-template-columns: 1fr;
    }

    .aule-booking-stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Gestione approvazione rapida
    $('.approve-booking').on('click', function(e) {
        e.preventDefault();
        var bookingId = $(this).data('id');

        if (confirm(aule_booking_admin_ajax.strings.confirm_approve)) {
            $.ajax({
                url: aule_booking_admin_ajax.ajax_url,
                method: 'POST',
                data: {
                    action: 'aule_approve_booking',
                    booking_id: bookingId,
                    note_admin: '',
                    nonce: aule_booking_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                }
            });
        }
    });

    // Gestione rifiuto rapido
    $('.reject-booking').on('click', function(e) {
        e.preventDefault();
        var bookingId = $(this).data('id');
        var note = prompt('Motivo del rifiuto (obbligatorio):');

        if (note && note.trim() !== '') {
            $.ajax({
                url: aule_booking_admin_ajax.ajax_url,
                method: 'POST',
                data: {
                    action: 'aule_reject_booking',
                    booking_id: bookingId,
                    note_admin: note,
                    nonce: aule_booking_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                }
            });
        }
    });
});
</script>