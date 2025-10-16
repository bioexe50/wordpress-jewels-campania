<?php
/**
 * Template per il modale selezione multipla slot
 *
 * @since 1.0.0
 * @package Prenotazione_Aule_SSM
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Modal Selezione Multipla Slot -->
<div class="modal fade" id="multiSlotModal-<?php echo esc_attr($aula_id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check"></i>
                    <span id="modal-date-title"></span>
                </h5>
                <button type="button" class="btn-close pas-btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <!-- Slot disponibili -->
                <div class="slot-selection-section mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-clock"></i>
                        Seleziona l'ora per l'appuntamento
                    </h6>
                    <div id="available-slots-grid" class="slots-grid">
                        <!-- Popolato via JavaScript -->
                    </div>
                </div>

                <!-- Prenotazioni esistenti per la data -->
                <div class="existing-bookings-section" id="existing-bookings-section" style="display: none;">
                    <h6 class="fw-bold mb-3">
                        Prenotazioni per la data <span id="booking-date-display"></span>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ORA</th>
                                    <th>MOTIVO PRENOTAZIONE</th>
                                </tr>
                            </thead>
                            <tbody id="existing-bookings-list">
                                <!-- Popolato via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Riepilogo slot selezionati -->
                <div class="selected-slots-summary mt-4" id="selected-slots-summary" style="display: none;">
                    <div class="pas-alert pas-alert-info">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-check-circle"></i>
                            Slot selezionati:
                        </h6>
                        <div id="selected-slots-badges">
                            <!-- Popolato via JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Form prenotazione (visibile solo dopo selezione) -->
                <div class="booking-form-section mt-4" id="booking-form-section" style="display: none;">
                    <h6 class="fw-bold mb-3">Compila il form di prenotazione</h6>
                    
                    <form id="multiSlotBookingForm-<?php echo esc_attr($aula_id); ?>" class="booking-form">
                        <input type="hidden" name="aula_id" value="<?php echo esc_attr($aula_id); ?>">
                        <input type="hidden" name="selected_slots" id="selected_slots_input">
                        <input type="hidden" name="action" value="prenotazione_aule_ssm_multi_booking">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('prenotazione_aule_ssm_multi_booking'); ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cognome_richiedente" class="form-label">Cognome *</label>
                                <input type="text" class="pas-form-control" id="cognome_richiedente" name="cognome_richiedente" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="nome_richiedente" class="form-label">Nome *</label>
                                <input type="text" class="pas-form-control" id="nome_richiedente" name="nome_richiedente" required>
                            </div>

                            <div class="col-md-12">
                                <label for="email_richiedente" class="form-label">Email *</label>
                                <input type="email" class="pas-form-control" id="email_richiedente" name="email_richiedente" required>
                            </div>

                            <div class="col-md-12">
                                <label for="motivo_prenotazione" class="form-label">Motivo prenotazione *</label>
                                <textarea class="pas-form-control" id="motivo_prenotazione" name="motivo_prenotazione" rows="3" required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono_richiedente" class="form-label">Telefono</label>
                                <input type="tel" class="pas-form-control" id="telefono_richiedente" name="telefono_richiedente">
                            </div>

                            <div class="col-md-6">
                                <label for="numero_partecipanti" class="form-label">Numero partecipanti</label>
                                <input type="number" class="pas-form-control" id="numero_partecipanti" name="numero_partecipanti" min="1" max="<?php echo esc_attr($aula->capienza ?? 50); ?>">
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="button" class="pas-btn pas-btn-secondary me-2" data-bs-dismiss="modal">Annulla</button>
                            <button type="submit" class="pas-btn pas-btn-primary">
                                <i class="fas fa-save"></i>
                                Conferma Prenotazione
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Loading spinner -->
                <div class="text-center py-4" id="slots-loading" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento...</span>
                    </div>
                </div>

                <!-- Messaggio successo -->
                <div class="pas-alert pas-alert-success" id="booking-success-message" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <strong>Prenotazione confermata!</strong>
                    <p class="mb-0">La tua prenotazione è stata registrata con successo.</p>
                </div>

                <!-- Messaggio errore -->
                <div class="pas-alert pas-alert-danger" id="booking-error-message" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Errore!</strong>
                    <p class="mb-0" id="booking-error-text"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.slots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    margin-bottom: 20px;
}

.slot-button {
    padding: 10px;
    border: 2px solid #dee2e6;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s;
    font-weight: 500;
}

.slot-button:hover:not(.slot-booked):not(.slot-disabled) {
    border-color: #0d6efd;
    background: #e7f1ff;
}

.slot-button.slot-selected {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.slot-button.slot-booked {
    background: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.slot-button.slot-disabled {
    background: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
}

.existing-bookings-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
}

.selected-slots-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.slot-badge {
    background: #0d6efd;
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.slot-badge .remove-slot {
    cursor: pointer;
    opacity: 0.8;
}

.slot-badge .remove-slot:hover {
    opacity: 1;
}
</style>

<style>
/* Aggiorna con il colore SSM #b64c3c */
.modal-header.bg-primary {
    background-color: #b64c3c !important;
}

.slot-button:hover:not(.slot-booked):not(.slot-disabled) {
    border-color: #b64c3c;
    background: #fce8e6;
}

.slot-button.slot-selected {
    background: #b64c3c;
    color: white;
    border-color: #b64c3c;
}

.slot-badge {
    background: #b64c3c;
}

.btn-primary {
    background-color: #b64c3c;
    border-color: #b64c3c;
}

.btn-primary:hover {
    background-color: #9a3f31;
    border-color: #9a3f31;
}

.text-primary,
.spinner-border.text-primary {
    color: #b64c3c !important;
}
</style>
