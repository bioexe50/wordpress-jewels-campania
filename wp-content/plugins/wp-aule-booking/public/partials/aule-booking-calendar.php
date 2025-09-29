<?php

/**
 * Template per il calendario delle prenotazioni
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/public/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

$calendar_id = 'aule-booking-calendar-' . $aula_id;
$allow_booking = ($atts['allow_booking'] === 'true');
$show_legend = ($atts['show_legend'] === 'true');
?>

<div class="aule-booking-wrapper" data-aula-id="<?php echo esc_attr($aula_id); ?>">

    <!-- Intestazione -->
    <div class="aule-booking-header">
        <h3 class="aula-title">
            <i class="fas fa-door-open"></i>
            <?php echo esc_html($aula->nome_aula); ?>
        </h3>

        <?php if (!empty($aula->descrizione)): ?>
            <p class="aula-description"><?php echo esc_html($aula->descrizione); ?></p>
        <?php endif; ?>

        <div class="aula-info">
            <?php if (!empty($aula->ubicazione)): ?>
                <span class="aula-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo esc_html($aula->ubicazione); ?>
                </span>
            <?php endif; ?>

            <?php if ($aula->capienza > 0): ?>
                <span class="aula-capacity">
                    <i class="fas fa-users"></i>
                    <?php printf(__('Capienza: %d persone', 'aule-booking'), $aula->capienza); ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if (!empty($aula->attrezzature)): ?>
            <?php $attrezzature = maybe_unserialize($aula->attrezzature); ?>
            <?php if (is_array($attrezzature) && !empty($attrezzature)): ?>
                <div class="aula-facilities">
                    <h4><?php _e('Attrezzature disponibili:', 'aule-booking'); ?></h4>
                    <div class="facilities-list">
                        <?php foreach ($attrezzature as $attrezzatura): ?>
                            <span class="facility-item">
                                <?php
                                $icons = array(
                                    'proiettore' => 'fas fa-video',
                                    'lavagna' => 'fas fa-chalkboard',
                                    'pc' => 'fas fa-desktop',
                                    'webcam' => 'fas fa-camera',
                                    'microfono' => 'fas fa-microphone'
                                );
                                $icon = isset($icons[$attrezzatura]) ? $icons[$attrezzatura] : 'fas fa-check';
                                ?>
                                <i class="<?php echo esc_attr($icon); ?>"></i>
                                <?php echo esc_html(ucfirst($attrezzatura)); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Legenda -->
    <?php if ($show_legend): ?>
        <div class="aule-booking-legend">
            <div class="legend-item">
                <span class="legend-color legend-available"></span>
                <span class="legend-label"><?php _e('Disponibile', 'aule-booking'); ?></span>
            </div>
            <div class="legend-item">
                <span class="legend-color legend-booked"></span>
                <span class="legend-label"><?php _e('Occupato', 'aule-booking'); ?></span>
            </div>
            <div class="legend-item">
                <span class="legend-color legend-pending"></span>
                <span class="legend-label"><?php _e('In Attesa', 'aule-booking'); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Calendario -->
    <div id="<?php echo esc_attr($calendar_id); ?>" class="aule-booking-calendar" style="height: <?php echo esc_attr($atts['height']); ?>px;"></div>

    <!-- Loading overlay -->
    <div class="aule-booking-loading" id="loading-<?php echo esc_attr($aula_id); ?>">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p><?php _e('Caricamento calendario...', 'aule-booking'); ?></p>
        </div>
    </div>
</div>

<!-- Modal Prenotazione -->
<?php if ($allow_booking): ?>
<div class="modal fade" id="bookingModal-<?php echo esc_attr($aula_id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus"></i>
                    <?php _e('Prenota Aula', 'aule-booking'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Riepilogo slot selezionato -->
                <div class="booking-slot-summary">
                    <h6><?php _e('Slot selezionato:', 'aule-booking'); ?></h6>
                    <div class="slot-details">
                        <div class="slot-info">
                            <strong class="aula-name"><?php echo esc_html($aula->nome_aula); ?></strong>
                            <span class="slot-date"></span>
                            <span class="slot-time"></span>
                        </div>
                        <div class="slot-location">
                            <?php if (!empty($aula->ubicazione)): ?>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo esc_html($aula->ubicazione); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Form prenotazione -->
                <form id="bookingForm-<?php echo esc_attr($aula_id); ?>" class="booking-form">
                    <input type="hidden" name="aula_id" value="<?php echo esc_attr($aula_id); ?>">
                    <input type="hidden" name="data_prenotazione" id="booking_date">
                    <input type="hidden" name="ora_inizio" id="booking_start_time">
                    <input type="hidden" name="ora_fine" id="booking_end_time">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('aule_booking_public_nonce'); ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome_richiedente" class="form-label">
                                    <?php _e('Nome *', 'aule-booking'); ?>
                                </label>
                                <input type="text" class="form-control" id="nome_richiedente" name="nome_richiedente" required minlength="2">
                                <div class="invalid-feedback">
                                    <?php _e('Il nome deve contenere almeno 2 caratteri', 'aule-booking'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cognome_richiedente" class="form-label">
                                    <?php _e('Cognome *', 'aule-booking'); ?>
                                </label>
                                <input type="text" class="form-control" id="cognome_richiedente" name="cognome_richiedente" required minlength="2">
                                <div class="invalid-feedback">
                                    <?php _e('Il cognome deve contenere almeno 2 caratteri', 'aule-booking'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email_richiedente" class="form-label">
                            <?php _e('Email *', 'aule-booking'); ?>
                        </label>
                        <input type="email" class="form-control" id="email_richiedente" name="email_richiedente" required>
                        <div class="invalid-feedback">
                            <?php _e('Inserisci un indirizzo email valido', 'aule-booking'); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="motivo_prenotazione" class="form-label">
                            <?php _e('Motivo della prenotazione *', 'aule-booking'); ?>
                        </label>
                        <textarea class="form-control" id="motivo_prenotazione" name="motivo_prenotazione" rows="3" required minlength="10" placeholder="<?php _e('Descrivi brevemente il motivo della prenotazione...', 'aule-booking'); ?>"></textarea>
                        <div class="invalid-feedback">
                            <?php _e('Il motivo deve contenere almeno 10 caratteri', 'aule-booking'); ?>
                        </div>
                    </div>

                    <!-- Privacy checkbox -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="privacy_accepted" name="privacy_accepted" required>
                        <label class="form-check-label" for="privacy_accepted">
                            <?php _e('Accetto l\'informativa privacy e il trattamento dei dati personali *', 'aule-booking'); ?>
                        </label>
                        <div class="invalid-feedback">
                            <?php _e('Devi accettare l\'informativa privacy', 'aule-booking'); ?>
                        </div>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="mb-3" id="recaptcha-container" style="display: none;">
                        <div class="g-recaptcha" data-sitekey="" data-callback="onRecaptchaSuccess"></div>
                    </div>

                    <!-- Messaggi di errore/successo -->
                    <div class="alert alert-danger" id="bookingError" style="display: none;"></div>
                    <div class="alert alert-success" id="bookingSuccess" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'aule-booking'); ?>
                </button>
                <button type="button" class="btn btn-primary" id="submitBooking">
                    <span class="btn-text"><?php _e('Invia Prenotazione', 'aule-booking'); ?></span>
                    <span class="btn-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                        <?php _e('Invio...', 'aule-booking'); ?>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal Dettagli Prenotazione (per slot occupati) -->
<div class="modal fade" id="bookingDetailsModal-<?php echo esc_attr($aula_id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i>
                    <?php _e('Dettagli Prenotazione', 'aule-booking'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="booking-details-content">
                    <!-- Contenuto dinamico -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?php _e('Chiudi', 'aule-booking'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Stili per il calendario delle prenotazioni */
.aule-booking-wrapper {
    max-width: 100%;
    margin: 0 auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.aule-booking-header {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #007cba;
}

.aula-title {
    margin: 0 0 10px 0;
    color: #1d2327;
    font-size: 1.5em;
    display: flex;
    align-items: center;
    gap: 10px;
}

.aula-title i {
    color: #007cba;
}

.aula-description {
    margin: 0 0 15px 0;
    color: #646970;
    line-height: 1.6;
}

.aula-info {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.aula-location,
.aula-capacity {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #646970;
    font-size: 0.9em;
}

.aula-facilities {
    margin-top: 15px;
}

.aula-facilities h4 {
    margin: 0 0 10px 0;
    font-size: 1em;
    color: #1d2327;
}

.facilities-list {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.facility-item {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    background: #e3f2fd;
    border-radius: 4px;
    font-size: 0.85em;
    color: #1565c0;
}

.aule-booking-legend {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    border: 1px solid rgba(0,0,0,0.1);
}

.legend-available {
    background-color: #28a745;
}

.legend-booked {
    background-color: #dc3545;
}

.legend-pending {
    background-color: #ffc107;
}

.legend-label {
    font-size: 0.9em;
    color: #646970;
}

.aule-booking-calendar {
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
}

.aule-booking-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.loading-spinner {
    text-align: center;
    color: #646970;
}

.loading-spinner i {
    font-size: 2em;
    margin-bottom: 10px;
    color: #007cba;
}

/* Modal personalizzazioni */
.booking-slot-summary {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    border-left: 4px solid #007cba;
}

.booking-slot-summary h6 {
    margin: 0 0 10px 0;
    color: #1d2327;
    font-weight: 600;
}

.slot-details {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.slot-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.aula-name {
    font-size: 1.1em;
    color: #1d2327;
}

.slot-date,
.slot-time {
    color: #646970;
    font-size: 0.9em;
}

.slot-location {
    color: #646970;
    font-size: 0.85em;
    display: flex;
    align-items: center;
    gap: 5px;
}

.booking-form .form-control:invalid {
    border-color: #dc3545;
}

.booking-form .form-control:valid {
    border-color: #28a745;
}

.btn-spinner {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* FullCalendar personalizzazioni */
.fc-event.available-slot {
    cursor: pointer;
    transition: all 0.2s ease;
}

.fc-event.available-slot:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.fc-event.booked-slot {
    cursor: not-allowed;
    opacity: 0.8;
}

.fc-event-title {
    font-weight: 500;
    font-size: 0.85em;
}

.fc-toolbar-title {
    color: #1d2327;
    font-weight: 600;
}

.fc-button-primary {
    background-color: #007cba;
    border-color: #007cba;
}

.fc-button-primary:hover {
    background-color: #005a8b;
    border-color: #005a8b;
}

/* Responsive design */
@media (max-width: 768px) {
    .aule-booking-header {
        padding: 15px;
    }

    .aula-info {
        flex-direction: column;
        gap: 10px;
    }

    .aule-booking-legend {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .facilities-list {
        flex-direction: column;
        gap: 8px;
    }

    .slot-details {
        flex-direction: column;
        gap: 10px;
    }
}

@media (max-width: 576px) {
    .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }

    .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('<?php echo esc_js($calendar_id); ?>');
    var auleId = <?php echo intval($aula_id); ?>;
    var allowBooking = <?php echo $allow_booking ? 'true' : 'false'; ?>;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: '<?php echo esc_js($atts['view']); ?>GridView' === 'monthGridView' ? 'dayGridMonth' :
                     (<?php echo esc_js($atts['view']); ?> === 'week' ? 'timeGridWeek' : 'timeGridDay'),
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'it',
        height: <?php echo intval($atts['height']); ?>,
        eventSources: [{
            url: aule_booking_ajax.ajax_url,
            method: 'POST',
            extraParams: {
                action: 'aule_check_availability',
                aula_id: auleId,
                nonce: aule_booking_ajax.nonce
            },
            failure: function() {
                console.error('Errore nel caricamento degli eventi del calendario');
            }
        }],
        eventClick: function(info) {
            var event = info.event;

            if (event.extendedProps.type === 'available' && allowBooking) {
                // Slot disponibile - apri modal prenotazione
                openBookingModal(event);
            } else if (event.extendedProps.type === 'booked') {
                // Slot occupato - mostra dettagli (opzionale)
                showBookingDetails(event);
            }
        },
        loading: function(isLoading) {
            var loadingEl = document.getElementById('loading-<?php echo esc_js($aula_id); ?>');
            if (loadingEl) {
                loadingEl.style.display = isLoading ? 'flex' : 'none';
            }
        },
        eventDidMount: function(info) {
            // Personalizza tooltip
            info.el.setAttribute('title', info.event.title +
                '\n' + info.event.start.toLocaleString('it-IT'));
        }
    });

    calendar.render();

    // Funzione per aprire il modal di prenotazione
    function openBookingModal(event) {
        var modal = new bootstrap.Modal(document.getElementById('bookingModal-<?php echo esc_js($aula_id); ?>'));

        // Popola i dati del slot
        var startDate = event.start;
        var endDate = event.end;

        document.getElementById('booking_date').value = startDate.toISOString().split('T')[0];
        document.getElementById('booking_start_time').value = startDate.toTimeString().split(' ')[0];
        document.getElementById('booking_end_time').value = endDate.toTimeString().split(' ')[0];

        // Aggiorna il riepilogo
        document.querySelector('.slot-date').textContent = startDate.toLocaleDateString('it-IT', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.querySelector('.slot-time').textContent =
            startDate.toLocaleTimeString('it-IT', {hour: '2-digit', minute: '2-digit'}) +
            ' - ' +
            endDate.toLocaleTimeString('it-IT', {hour: '2-digit', minute: '2-digit'});

        // Setup reCAPTCHA se necessario
        if (aule_booking_ajax.settings.recaptcha_enabled && aule_booking_ajax.settings.recaptcha_site_key) {
            var recaptchaContainer = document.getElementById('recaptcha-container');
            recaptchaContainer.style.display = 'block';
            recaptchaContainer.querySelector('.g-recaptcha').setAttribute('data-sitekey', aule_booking_ajax.settings.recaptcha_site_key);
        }

        modal.show();
    }

    // Funzione per mostrare dettagli prenotazione
    function showBookingDetails(event) {
        var modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal-<?php echo esc_js($aula_id); ?>'));
        var content = document.getElementById('booking-details-content');

        var startDate = event.start;
        var endDate = event.end;

        content.innerHTML = `
            <div class="booking-detail-item">
                <strong>Data:</strong> ${startDate.toLocaleDateString('it-IT', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                })}
            </div>
            <div class="booking-detail-item">
                <strong>Orario:</strong> ${startDate.toLocaleTimeString('it-IT', {hour: '2-digit', minute: '2-digit'})} -
                ${endDate.toLocaleTimeString('it-IT', {hour: '2-digit', minute: '2-digit'})}
            </div>
            <div class="booking-detail-item">
                <strong>Stato:</strong> <span class="badge bg-${event.extendedProps.stato === 'confermata' ? 'success' : 'warning'}">
                    ${event.extendedProps.stato === 'confermata' ? 'Confermata' : 'In Attesa'}
                </span>
            </div>
        `;

        modal.show();
    }

    // Gestione submit prenotazione
    document.getElementById('submitBooking').addEventListener('click', function() {
        var form = document.getElementById('bookingForm-<?php echo esc_js($aula_id); ?>');
        var formData = new FormData(form);

        // Validazione form
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Controlla privacy
        if (!document.getElementById('privacy_accepted').checked) {
            showError(aule_booking_ajax.strings.privacy_required);
            return;
        }

        var submitBtn = this;
        var btnText = submitBtn.querySelector('.btn-text');
        var btnSpinner = submitBtn.querySelector('.btn-spinner');

        // Disabilita il bottone e mostra spinner
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-flex';

        // Aggiungi dati AJAX
        formData.append('action', 'aule_submit_booking');

        // Gestione reCAPTCHA
        if (aule_booking_ajax.settings.recaptcha_enabled) {
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.execute(aule_booking_ajax.settings.recaptcha_site_key, {action: 'booking'})
                .then(function(token) {
                    formData.append('recaptcha_token', token);
                    submitBookingRequest(formData, submitBtn, btnText, btnSpinner);
                });
            } else {
                showError('reCAPTCHA non caricato correttamente');
                resetSubmitButton(submitBtn, btnText, btnSpinner);
                return;
            }
        } else {
            submitBookingRequest(formData, submitBtn, btnText, btnSpinner);
        }
    });

    // Funzione per inviare la richiesta di prenotazione
    function submitBookingRequest(formData, submitBtn, btnText, btnSpinner) {
        fetch(aule_booking_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            resetSubmitButton(submitBtn, btnText, btnSpinner);

            if (data.success) {
                showSuccess(data.data.message);
                // Ricarica il calendario dopo 2 secondi
                setTimeout(function() {
                    calendar.refetchEvents();
                    bootstrap.Modal.getInstance(document.getElementById('bookingModal-<?php echo esc_js($aula_id); ?>')).hide();
                    document.getElementById('bookingForm-<?php echo esc_js($aula_id); ?>').reset();
                }, 2000);
            } else {
                showError(data.data);
            }
        })
        .catch(error => {
            resetSubmitButton(submitBtn, btnText, btnSpinner);
            showError(aule_booking_ajax.strings.error);
            console.error('Errore:', error);
        });
    }

    // Funzioni di utilità
    function resetSubmitButton(submitBtn, btnText, btnSpinner) {
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        btnSpinner.style.display = 'none';
    }

    function showError(message) {
        var errorEl = document.getElementById('bookingError');
        var successEl = document.getElementById('bookingSuccess');

        successEl.style.display = 'none';
        errorEl.innerHTML = message;
        errorEl.style.display = 'block';
    }

    function showSuccess(message) {
        var errorEl = document.getElementById('bookingError');
        var successEl = document.getElementById('bookingSuccess');

        errorEl.style.display = 'none';
        successEl.innerHTML = message;
        successEl.style.display = 'block';
    }
});

// Callback per reCAPTCHA
function onRecaptchaSuccess(token) {
    console.log('reCAPTCHA completato');
}
</script>