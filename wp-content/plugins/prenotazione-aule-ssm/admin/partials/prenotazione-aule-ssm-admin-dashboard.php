<?php

/**
 * Template per la dashboard amministratore
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/admin/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Dashboard Gestione Aule', 'prenotazione-aule-ssm'); ?></h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Dati salvati correttamente.', 'prenotazione-aule-ssm'); ?></p>
        </div>
    <?php endif; ?>

    <!-- Statistiche Overview -->
    <div class="prenotazione-aule-ssm-stats-grid">
        <div class="prenotazione-aule-ssm-stat-card">
            <div class="stat-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['total_aule']); ?></h3>
                <p><?php _e('Totale Aule', 'prenotazione-aule-ssm'); ?></p>
            </div>
        </div>

        <div class="prenotazione-aule-ssm-stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['aule_attive']); ?></h3>
                <p><?php _e('Aule Attive', 'prenotazione-aule-ssm'); ?></p>
            </div>
        </div>

        <div class="prenotazione-aule-ssm-stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['prenotazioni_oggi']); ?></h3>
                <p><?php _e('Prenotazioni Oggi', 'prenotazione-aule-ssm'); ?></p>
            </div>
        </div>

        <div class="prenotazione-aule-ssm-stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['prenotazioni_attesa']); ?></h3>
                <p><?php _e('In Attesa', 'prenotazione-aule-ssm'); ?></p>
                <?php if ($stats['prenotazioni_attesa'] > 0): ?>
                    <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-prenotazioni&stato=in_attesa'); ?>" class="button button-secondary">
                        <?php _e('Gestisci', 'prenotazione-aule-ssm'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contenuto principale -->
    <div class="prenotazione-aule-ssm-dashboard-content">

        <!-- Sezione Prenotazioni Recenti -->
        <div class="prenotazione-aule-ssm-dashboard-section">
            <div class="section-header">
                <h2><?php _e('Prenotazioni Recenti', 'prenotazione-aule-ssm'); ?></h2>
                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-prenotazioni'); ?>" class="button">
                    <?php _e('Vedi Tutte', 'prenotazione-aule-ssm'); ?>
                </a>
            </div>

            <?php if (!empty($recent_bookings)): ?>
                <div class="prenotazione-aule-ssm-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Richiedente', 'prenotazione-aule-ssm'); ?></th>
                                <th><?php _e('Aula', 'prenotazione-aule-ssm'); ?></th>
                                <th><?php _e('Data', 'prenotazione-aule-ssm'); ?></th>
                                <th><?php _e('Orario', 'prenotazione-aule-ssm'); ?></th>
                                <th><?php _e('Stato', 'prenotazione-aule-ssm'); ?></th>
                                <th><?php _e('Azioni', 'prenotazione-aule-ssm'); ?></th>
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
                                                    _e('In Attesa', 'prenotazione-aule-ssm');
                                                    break;
                                                case 'confermata':
                                                    _e('Confermata', 'prenotazione-aule-ssm');
                                                    break;
                                                case 'rifiutata':
                                                    _e('Rifiutata', 'prenotazione-aule-ssm');
                                                    break;
                                                case 'cancellata':
                                                    _e('Cancellata', 'prenotazione-aule-ssm');
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($booking->stato === 'in_attesa'): ?>
                                            <button class="button button-small approve-booking" data-id="<?php echo $booking->id; ?>">
                                                <i class="fas fa-check"></i> <?php _e('Approva', 'prenotazione-aule-ssm'); ?>
                                            </button>
                                            <button class="button button-small reject-booking" data-id="<?php echo $booking->id; ?>">
                                                <i class="fas fa-times"></i> <?php _e('Rifiuta', 'prenotazione-aule-ssm'); ?>
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-prenotazioni&search=' . urlencode($booking->email_richiedente)); ?>" class="button button-small">
                                            <i class="fas fa-eye"></i> <?php _e('Dettagli', 'prenotazione-aule-ssm'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-data"><?php _e('Nessuna prenotazione recente.', 'prenotazione-aule-ssm'); ?></p>
            <?php endif; ?>
        </div>

        <!-- Sezione Link Rapidi -->
        <div class="prenotazione-aule-ssm-dashboard-section">
            <div class="section-header">
                <h2><?php _e('Link Rapidi', 'prenotazione-aule-ssm'); ?></h2>
            </div>

            <div class="prenotazione-aule-ssm-quick-links">
                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-add-aula'); ?>" class="quick-link-card">
                    <i class="fas fa-plus-circle"></i>
                    <h3><?php _e('Aggiungi Aula', 'prenotazione-aule-ssm'); ?></h3>
                    <p><?php _e('Crea una nuova aula studio', 'prenotazione-aule-ssm'); ?></p>
                </a>

                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-slot'); ?>" class="quick-link-card">
                    <i class="fas fa-clock"></i>
                    <h3><?php _e('Gestisci Slot', 'prenotazione-aule-ssm'); ?></h3>
                    <p><?php _e('Configura orari disponibilità', 'prenotazione-aule-ssm'); ?></p>
                </a>

                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-prenotazioni&stato=in_attesa'); ?>" class="quick-link-card">
                    <i class="fas fa-tasks"></i>
                    <h3><?php _e('Prenotazioni in Attesa', 'prenotazione-aule-ssm'); ?></h3>
                    <p><?php printf(__('%d prenotazioni da gestire', 'prenotazione-aule-ssm'), $stats['prenotazioni_attesa']); ?></p>
                </a>

                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-reports'); ?>" class="quick-link-card">
                    <i class="fas fa-chart-bar"></i>
                    <h3><?php _e('Report', 'prenotazione-aule-ssm'); ?></h3>
                    <p><?php _e('Visualizza statistiche dettagliate', 'prenotazione-aule-ssm'); ?></p>
                </a>
            </div>
        </div>

        <!-- Sezione Statistiche Mensili -->
        <div class="prenotazione-aule-ssm-dashboard-section">
            <div class="section-header">
                <h2><?php _e('Statistiche Mensili', 'prenotazione-aule-ssm'); ?></h2>
            </div>

            <div class="monthly-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html($stats['prenotazioni_mese']); ?></span>
                    <span class="stat-label"><?php _e('Prenotazioni questo mese', 'prenotazione-aule-ssm'); ?></span>
                </div>

                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html(round($stats['prenotazioni_mese'] / max(1, date('j')) * date('t'))); ?></span>
                    <span class="stat-label"><?php _e('Proiezione mensile', 'prenotazione-aule-ssm'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conferma Approvazione -->
<div class="modal fade" id="approveBookingModal" tabindex="-1" aria-labelledby="approveBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveBookingModalLabel">
                    <span class="dashicons dashicons-yes-alt" style="color: white;"></span>
                    <?php _e('Conferma Approvazione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php _e('Sei sicuro di voler approvare questa prenotazione?', 'prenotazione-aule-ssm'); ?></p>
                <div class="alert alert-info">
                    <strong><?php _e('Nota:', 'prenotazione-aule-ssm'); ?></strong>
                    <?php _e('Verrà inviata un\'email di conferma al richiedente.', 'prenotazione-aule-ssm'); ?>
                </div>
                <div class="mb-3">
                    <label for="approve-note" class="form-label"><?php _e('Note aggiuntive (opzionale):', 'prenotazione-aule-ssm'); ?></label>
                    <textarea id="approve-note" class="form-control" rows="3"
                              placeholder="<?php _e('Es: Si ricorda di portare documento identificativo', 'prenotazione-aule-ssm'); ?>"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="confirmApproveBtn" style="background: #00a32a; border-color: #00a32a;">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <?php _e('Approva Prenotazione', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rifiuto Prenotazione -->
<div class="modal fade" id="rejectBookingModal" tabindex="-1" aria-labelledby="rejectBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectBookingModalLabel">
                    <span class="dashicons dashicons-dismiss" style="color: white;"></span>
                    <?php _e('Rifiuta Prenotazione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong><?php _e('Attenzione!', 'prenotazione-aule-ssm'); ?></strong>
                    <?php _e('Il richiedente riceverà un\'email di notifica del rifiuto.', 'prenotazione-aule-ssm'); ?>
                </div>
                <div class="mb-3">
                    <label for="reject-reason" class="form-label">
                        <?php _e('Motivo del rifiuto:', 'prenotazione-aule-ssm'); ?>
                        <span class="text-danger">*</span>
                    </label>
                    <textarea id="reject-reason" class="form-control" rows="4" required
                              placeholder="<?php _e('Es: Aula non disponibile per manutenzione straordinaria', 'prenotazione-aule-ssm'); ?>"></textarea>
                    <small class="form-text text-muted"><?php _e('Questo messaggio sarà visibile al richiedente.', 'prenotazione-aule-ssm'); ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="confirmRejectBtn" style="background: #d63638; border-color: #d63638;">
                    <span class="dashicons dashicons-dismiss"></span>
                    <?php _e('Conferma Rifiuto', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Stili per la dashboard */
.prenotazione-aule-ssm-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.prenotazione-aule-ssm-stat-card {
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

.prenotazione-aule-ssm-dashboard-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    margin-top: 30px;
}

.prenotazione-aule-ssm-dashboard-section {
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

.prenotazione-aule-ssm-table-container {
    padding: 0 20px 20px;
    overflow-x: auto;
}

.prenotazione-aule-ssm-quick-links {
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
    .prenotazione-aule-ssm-dashboard-content {
        grid-template-columns: 1fr;
    }

    .prenotazione-aule-ssm-stats-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Bootstrap overrides */
.modal-header.bg-success,
.modal-header.bg-danger {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.modal-content {
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.1em;
    font-weight: 600;
}

.modal-title .dashicons {
    font-size: 20px;
    width: 20px;
    height: 20px;
}

.modal-body .alert {
    margin-bottom: 15px;
    padding: 12px;
    border-radius: 6px;
}

.modal-body .alert-info {
    background-color: #e8f4fd;
    border-color: #72aee6;
    color: #135e96;
}

.modal-body .alert-warning {
    background-color: #fcf9e8;
    border-color: #dba617;
    color: #8a6116;
}

.modal-body .form-label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #1d2327;
}

.modal-body .form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    font-size: 14px;
}

.modal-body .form-control:focus {
    border-color: #2271b1;
    outline: none;
    box-shadow: 0 0 0 1px #2271b1;
}

.modal-body .text-danger {
    color: #d63638;
}

.modal-body .text-muted {
    color: #646970;
    font-size: 12px;
}

.modal-footer {
    padding: 15px 20px;
    background: #f9f9f9;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}

.btn-close-white {
    filter: brightness(0) invert(1);
}

.spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    vertical-align: text-bottom;
    border: 0.2em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

.spinner-border-sm {
    width: 0.875rem;
    height: 0.875rem;
    border-width: 0.15em;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}
</style>

<script>
jQuery(document).ready(function($) {
    var currentBookingId = null;

    // Gestione approvazione - Mostra modal
    $('.approve-booking').on('click', function(e) {
        e.preventDefault();
        currentBookingId = $(this).data('id');
        $('#approve-note').val('');

        // Mostra modal Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('approveBookingModal'));
        modal.show();
    });

    // Conferma approvazione dal modal
    $('#confirmApproveBtn').on('click', function() {
        var note = $('#approve-note').val().trim();
        var $btn = $(this);

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Approvazione...');

        $.ajax({
            url: prenotazione_aule_ssm_admin_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'aule_approve_booking',
                booking_id: currentBookingId,
                note_admin: note,
                nonce: prenotazione_aule_ssm_admin_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Errore: ' + response.data);
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-yes-alt"></span> Approva Prenotazione');
                }
            },
            error: function() {
                alert('Errore di comunicazione');
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-yes-alt"></span> Approva Prenotazione');
            }
        });
    });

    // Gestione rifiuto - Mostra modal
    $('.reject-booking').on('click', function(e) {
        e.preventDefault();
        currentBookingId = $(this).data('id');
        $('#reject-reason').val('');

        // Mostra modal Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('rejectBookingModal'));
        modal.show();
    });

    // Conferma rifiuto dal modal
    $('#confirmRejectBtn').on('click', function() {
        var reason = $('#reject-reason').val().trim();

        if (!reason) {
            alert('Il motivo del rifiuto è obbligatorio');
            $('#reject-reason').focus();
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Rifiuto...');

        $.ajax({
            url: prenotazione_aule_ssm_admin_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'aule_reject_booking',
                booking_id: currentBookingId,
                note_admin: reason,
                nonce: prenotazione_aule_ssm_admin_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Errore: ' + response.data);
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-dismiss"></span> Conferma Rifiuto');
                }
            },
            error: function() {
                alert('Errore di comunicazione');
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-dismiss"></span> Conferma Rifiuto');
            }
        });
    });

    // Reset dei campi quando si chiudono i modali
    $('#approveBookingModal, #rejectBookingModal').on('hidden.bs.modal', function() {
        currentBookingId = null;
        $('#approve-note, #reject-reason').val('');
    });
});
</script>