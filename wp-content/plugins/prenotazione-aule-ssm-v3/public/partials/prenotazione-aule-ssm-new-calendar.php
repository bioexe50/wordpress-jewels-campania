<?php
/**
 * Template per il nuovo calendario stile Calendly
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/public/partials
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
                <span class="dashicons dashicons-building"></span>
                <?php echo esc_html($aula->nome_aula); ?>
            </h2>

            <?php if (!empty($aula->descrizione)): ?>
                <p class="aula-description"><?php echo esc_html($aula->descrizione); ?></p>
            <?php endif; ?>

            <div class="aula-meta">
                <?php if (!empty($aula->ubicazione)): ?>
                    <span class="aula-location">
                        <span class="dashicons dashicons-location"></span>
                        <?php echo esc_html($aula->ubicazione); ?>
                    </span>
                <?php endif; ?>

                <?php if ($aula->capienza > 0): ?>
                    <span class="aula-capacity">
                        <span class="dashicons dashicons-groups"></span>
                        <?php printf(__('%d posti', 'prenotazione-aule-ssm'), $aula->capienza); ?>
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
                            <button type="button" class="btn-nav btn-prev-month" aria-label="<?php _e('Mese precedente', 'prenotazione-aule-ssm'); ?>">
                                <span aria-hidden="true">‹</span>
                            </button>
                            <button type="button" class="btn-nav btn-next-month" aria-label="<?php _e('Mese successivo', 'prenotazione-aule-ssm'); ?>">
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
                            <div class="legend-box disabled">X</div>
                            <span><?php _e('Non prenotabile', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box available">15</div>
                            <span><?php _e('Disponibile', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box partially-booked">15</div>
                            <span><?php _e('Parzialmente Occupato', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box fully-booked">15</div>
                            <span><?php _e('Completo', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box today">15</div>
                            <span><?php _e('Oggi', 'prenotazione-aule-ssm'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Colonna Destra: Slot Selezionati e Form -->
            <div class="slots-column">
                <!-- Recap Slot Selezionati -->
                <div class="selected-slots-widget" style="display: none;">
                    <div class="selected-slots-header">
                        <h3 class="selected-slots-title">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php _e('Slot Selezionati', 'prenotazione-aule-ssm'); ?>
                        </h3>
                        <button type="button" class="btn-clear-all" title="<?php _e('Rimuovi tutti', 'prenotazione-aule-ssm'); ?>">
                            <span class="dashicons dashicons-dismiss"></span>
                        </button>
                    </div>
                    <div class="selected-slots-list">
                        <!-- Badge popolati da JavaScript -->
                    </div>
                </div>

                <!-- Form Prenotazione Multipla -->
                <div class="booking-form-widget" style="display: none;">
                    <form id="multiSlotBookingForm" class="multi-slot-booking-form">
                        <input type="hidden" name="aula_id" value="<?php echo esc_attr($aula_id); ?>">
                        <input type="hidden" name="selected_slots" id="selected_slots_data">

                        <div class="form-group">
                            <label for="multi_nome" class="form-label">
                                <?php _e('Nome', 'prenotazione-aule-ssm'); ?> <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="multi_nome" name="nome_richiedente" required minlength="2">
                        </div>

                        <div class="form-group">
                            <label for="multi_cognome" class="form-label">
                                <?php _e('Cognome', 'prenotazione-aule-ssm'); ?> <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="multi_cognome" name="cognome_richiedente" required minlength="2">
                        </div>

                        <div class="form-group">
                            <label for="multi_email" class="form-label">
                                <?php _e('Email', 'prenotazione-aule-ssm'); ?> <span class="required">*</span>
                            </label>
                            <input type="email" class="form-control" id="multi_email" name="email_richiedente" required>
                        </div>

                        <div class="form-group">
                            <label for="multi_motivo" class="form-label">
                                <?php _e('Motivo della prenotazione', 'prenotazione-aule-ssm'); ?> <span class="required">*</span>
                            </label>
                            <textarea class="form-control" id="multi_motivo" name="motivo_prenotazione" rows="3" required minlength="10" placeholder="<?php _e('Descrivi brevemente il motivo della prenotazione...', 'prenotazione-aule-ssm'); ?>"></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="multi_privacy" name="privacy_accepted" required>
                            <label class="form-check-label" for="multi_privacy">
                                <?php _e('Accetto l\'informativa privacy', 'prenotazione-aule-ssm'); ?> <span class="required">*</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-submit-multi-booking">
                            <span class="btn-text">
                                <span class="dashicons dashicons-yes"></span>
                                <?php _e('Prenota tutti gli slot', 'prenotazione-aule-ssm'); ?>
                            </span>
                            <span class="btn-spinner" style="display: none;">
                                <span class="spinner-border spinner-border-sm"></span>
                                <?php _e('Invio...', 'prenotazione-aule-ssm'); ?>
                            </span>
                        </button>

                        <div class="alert alert-danger mt-3" style="display: none;"></div>
                        <div class="alert alert-success mt-3" style="display: none;"></div>
                    </form>
                </div>

                <!-- Empty State -->
                <div class="slots-empty-state">
                    <div class="empty-icon">
                        <span class="dashicons dashicons-calendar-alt" style="font-size: 48px; opacity: 0.3;"></span>
                    </div>
                    <p class="empty-message"><?php _e('Seleziona una data per visualizzare gli slot disponibili', 'prenotazione-aule-ssm'); ?></p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Selezione Slot -->
<div class="modal fade prenotazione-aule-ssm-modal" id="slotSelectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="dashicons dashicons-calendar-alt"></span>
                    <?php _e('Seleziona Slot', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e('Chiudi', 'prenotazione-aule-ssm'); ?>"></button>
            </div>

            <div class="modal-body">
                <!-- Data Selezionata -->
                <div class="selected-date-info mb-3">
                    <strong><?php _e('Data:', 'prenotazione-aule-ssm'); ?></strong>
                    <span id="modalSelectedDate">-</span>
                </div>

                <!-- Lista Slot Disponibili come Badge -->
                <div class="available-slots-grid">
                    <!-- Popolato da JavaScript con badge selezionabili -->
                </div>

                <!-- Loading -->
                <div class="modal-loading text-center" style="display: none;">
                    <span class="spinner-border spinner-border-sm"></span>
                    <?php _e('Caricamento slot...', 'prenotazione-aule-ssm'); ?>
                </div>

                <!-- Empty State -->
                <div class="modal-empty text-center" style="display: none;">
                    <p class="text-muted"><?php _e('Nessuno slot disponibile per questa data', 'prenotazione-aule-ssm'); ?></p>
                </div>

                <!-- Counter Slot Selezionati -->
                <div class="modal-selected-count mt-3" style="display: none;">
                    <div class="alert alert-info">
                        <strong id="modalSlotCount">0</strong> <?php _e('slot selezionati', 'prenotazione-aule-ssm'); ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="btn btn-primary btn-confirm-slots" id="confirmSlotsBtn" disabled>
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Conferma selezione', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>