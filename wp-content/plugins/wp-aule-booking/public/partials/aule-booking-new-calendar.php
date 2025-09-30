<?php
/**
 * Template per il nuovo calendario stile Calendly
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/public/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

$allow_booking = ($atts['allow_booking'] === 'true');
$show_legend = ($atts['show_legend'] === 'true');
?>

<div class="aule-new-calendar-wrapper" data-aula-id="<?php echo esc_attr($aula_id); ?>">

    <!-- Header Aula Info -->
    <div class="aule-new-calendar-header">
        <div class="aula-info-card">
            <h2 class="aula-name">
                <span class="aula-icon">🏢</span>
                <?php echo esc_html($aula->nome_aula); ?>
            </h2>

            <?php if (!empty($aula->descrizione)): ?>
                <p class="aula-description"><?php echo esc_html($aula->descrizione); ?></p>
            <?php endif; ?>

            <div class="aula-meta">
                <?php if (!empty($aula->ubicazione)): ?>
                    <span class="aula-location">
                        <span class="meta-icon">📍</span>
                        <?php echo esc_html($aula->ubicazione); ?>
                    </span>
                <?php endif; ?>

                <?php if ($aula->capienza > 0): ?>
                    <span class="aula-capacity">
                        <span class="meta-icon">👥</span>
                        <?php printf(__('%d posti', 'aule-booking'), $aula->capienza); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="aule-new-calendar-container">
        <div class="calendar-row">

            <!-- Colonna Sinistra: Calendario -->
            <div class="calendar-column">
                <div class="calendar-widget">
                    <div class="calendar-header-nav">
                        <h3 class="month-year-display">
                            <!-- Popolato da JavaScript -->
                        </h3>
                        <div class="nav-buttons">
                            <button type="button" class="btn-nav btn-prev-month" aria-label="<?php _e('Mese precedente', 'aule-booking'); ?>">
                                <span aria-hidden="true">‹</span>
                            </button>
                            <button type="button" class="btn-nav btn-next-month" aria-label="<?php _e('Mese successivo', 'aule-booking'); ?>">
                                <span aria-hidden="true">›</span>
                            </button>
                        </div>
                    </div>

                    <div class="calendar-grid">
                        <!-- Header giorni settimana -->
                        <div class="weekdays-header">
                            <!-- Popolato da JavaScript -->
                        </div>

                        <!-- Griglia giorni -->
                        <div class="days-grid">
                            <!-- Popolato da JavaScript -->
                        </div>
                    </div>

                    <?php if ($show_legend): ?>
                    <div class="calendar-legend">
                        <div class="legend-item">
                            <span class="legend-dot dot-available"></span>
                            <span class="legend-label"><?php _e('Disponibile', 'aule-booking'); ?></span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot dot-selected"></span>
                            <span class="legend-label"><?php _e('Selezionato', 'aule-booking'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Colonna Destra: Slot Orari -->
            <div class="slots-column">
                <div class="slots-widget">
                    <div class="selected-date-header">
                        <h3 class="selected-date-title">
                            <?php _e('Seleziona una data', 'aule-booking'); ?>
                        </h3>
                        <p class="selected-date-subtitle">
                            <?php _e('Scegli un giorno dal calendario per vedere gli orari disponibili', 'aule-booking'); ?>
                        </p>
                    </div>

                    <div class="slots-container">
                        <div class="slots-list">
                            <!-- Popolato da JavaScript -->
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div class="slots-empty-state">
                        <div class="empty-icon">📅</div>
                        <p class="empty-message"><?php _e('Seleziona una data per visualizzare gli slot disponibili', 'aule-booking'); ?></p>
                    </div>

                    <!-- Loading State -->
                    <div class="slots-loading-state" style="display: none;">
                        <div class="loading-spinner">
                            <span class="spinner-icon">⏳</span>
                            <p><?php _e('Caricamento slot...', 'aule-booking'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Prenotazione -->
<div class="modal fade aule-booking-modal" id="newCalendarBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="modal-icon">📝</span>
                    <?php _e('Prenota Aula', 'aule-booking'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e('Chiudi', 'aule-booking'); ?>"></button>
            </div>

            <div class="modal-body">
                <!-- Riepilogo Prenotazione -->
                <div class="booking-summary">
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Aula:', 'aule-booking'); ?></span>
                        <span class="summary-value summary-aula"><?php echo esc_html($aula->nome_aula); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Data:', 'aule-booking'); ?></span>
                        <span class="summary-value summary-date">-</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Orario:', 'aule-booking'); ?></span>
                        <span class="summary-value summary-time">-</span>
                    </div>
                </div>

                <!-- Form Prenotazione -->
                <form id="newCalendarBookingForm" class="booking-form" novalidate>
                    <input type="hidden" name="aula_id" value="<?php echo esc_attr($aula_id); ?>">
                    <input type="hidden" name="slot_id" id="booking_slot_id">
                    <input type="hidden" name="data_prenotazione" id="booking_date">
                    <input type="hidden" name="ora_inizio" id="booking_time_start">
                    <input type="hidden" name="ora_fine" id="booking_time_end">

                    <div class="form-group">
                        <label for="booking_nome" class="form-label">
                            <?php _e('Nome', 'aule-booking'); ?> <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="booking_nome" name="nome_richiedente" required minlength="2">
                        <div class="invalid-feedback">
                            <?php _e('Il nome è obbligatorio (min 2 caratteri)', 'aule-booking'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="booking_cognome" class="form-label">
                            <?php _e('Cognome', 'aule-booking'); ?> <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="booking_cognome" name="cognome_richiedente" required minlength="2">
                        <div class="invalid-feedback">
                            <?php _e('Il cognome è obbligatorio (min 2 caratteri)', 'aule-booking'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="booking_email" class="form-label">
                            <?php _e('Email', 'aule-booking'); ?> <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control" id="booking_email" name="email_richiedente" required>
                        <div class="invalid-feedback">
                            <?php _e('Inserisci un indirizzo email valido', 'aule-booking'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="booking_motivo" class="form-label">
                            <?php _e('Motivo della prenotazione', 'aule-booking'); ?> <span class="required">*</span>
                        </label>
                        <textarea class="form-control" id="booking_motivo" name="motivo_prenotazione" rows="3" required minlength="10" placeholder="<?php _e('Descrivi brevemente il motivo della prenotazione...', 'aule-booking'); ?>"></textarea>
                        <div class="invalid-feedback">
                            <?php _e('Il motivo è obbligatorio (min 10 caratteri)', 'aule-booking'); ?>
                        </div>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="booking_privacy" name="privacy_accepted" required>
                        <label class="form-check-label" for="booking_privacy">
                            <?php _e('Accetto l\'informativa privacy e il trattamento dei dati personali', 'aule-booking'); ?> <span class="required">*</span>
                        </label>
                        <div class="invalid-feedback">
                            <?php _e('Devi accettare l\'informativa privacy', 'aule-booking'); ?>
                        </div>
                    </div>

                    <!-- Messaggi -->
                    <div class="alert alert-danger booking-error" style="display: none;"></div>
                    <div class="alert alert-success booking-success" style="display: none;"></div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'aule-booking'); ?>
                </button>
                <button type="button" class="btn btn-primary btn-submit-booking" id="submitBookingBtn">
                    <span class="btn-text"><?php _e('Conferma Prenotazione', 'aule-booking'); ?></span>
                    <span class="btn-spinner" style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <?php _e('Invio...', 'aule-booking'); ?>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>