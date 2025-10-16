/**
 * JavaScript per gestione selezione multipla slot
 * @package Prenotazione_Aule_SSM
 */

(function($) {
    'use strict';

    // Variabili globali per la gestione slot
    let selectedSlots = [];
    let currentDate = null;
    let currentAulaId = null;
    let availableSlots = [];
    let bookedSlots = [];

    /**
     * Inizializza il sistema multi-slot
     */
    function initMultiSlot() {
        // Event listener per click su data nel calendario
        $(document).on('click', '.fc-daygrid-day, .fc-timegrid-slot', function(e) {
            const dateStr = $(this).data('date');
            if (dateStr) {
                currentAulaId = $(this).closest('.prenotazione-aule-ssm-wrapper').data('aula-id');
                openMultiSlotModal(dateStr, currentAulaId);
            }
        });

        // Submit form prenotazione multipla
        $(document).on('submit', '[id^="multiSlotBookingForm-"]', function(e) {
            e.preventDefault();
            submitMultiSlotBooking($(this));
        });

        // Rimuovi slot selezionato
        $(document).on('click', '.remove-slot', function() {
            const slotTime = $(this).data('slot-time');
            removeSelectedSlot(slotTime);
        });
    }

    /**
     * Apri modale selezione multipla slot
     */
    function openMultiSlotModal(dateStr, aulaId) {
        currentDate = dateStr;
        currentAulaId = aulaId;
        selectedSlots = [];

        const modal = $('#multiSlotModal-' + aulaId);
        
        // Formatta e mostra la data
        const formattedDate = formatDateItalian(dateStr);
        modal.find('#modal-date-title').text(formattedDate);
        modal.find('#booking-date-display').text(formattedDate.split(' ')[1]); // Solo la data

        // Mostra loading
        modal.find('#slots-loading').show();
        modal.find('#available-slots-grid').empty();
        modal.find('#existing-bookings-section').hide();
        modal.find('#booking-form-section').hide();
        modal.find('#selected-slots-summary').hide();

        // Apri modal
        const modalInstance = new bootstrap.Modal(modal[0]);
        modalInstance.show();

        // Carica slot disponibili
        loadSlotsForDate(dateStr, aulaId);
    }

    /**
     * Carica slot per la data selezionata
     */
    function loadSlotsForDate(date, aulaId) {
        $.ajax({
            url: prenotazione_aule_ssm_public.ajax_url,
            type: 'POST',
            data: {
                action: 'get_slots_for_date',
                aula_id: aulaId,
                date: date,
                nonce: prenotazione_aule_ssm_public.nonce
            },
            success: function(response) {
                if (response.success) {
                    availableSlots = response.data.available_slots || [];
                    bookedSlots = response.data.booked_slots || [];
                    
                    renderSlots();
                    renderBookedSlots();
                    
                    $('#slots-loading').hide();
                } else {
                    showError(response.data.message || 'Errore nel caricamento degli slot');
                }
            },
            error: function() {
                showError('Errore di connessione. Riprova.');
            }
        });
    }

    /**
     * Renderizza griglia slot disponibili
     */
    function renderSlots() {
        const grid = $('#available-slots-grid');
        grid.empty();

        if (availableSlots.length === 0) {
            grid.html('<p class="text-muted">Nessuno slot disponibile per questa data.</p>');
            return;
        }

        availableSlots.forEach(slot => {
            const isBooked = bookedSlots.some(b => b.ora_inizio === slot.time);
            const isSelected = selectedSlots.some(s => s.time === slot.time);
            
            const button = $('<button>', {
                type: 'button',
                class: 'slot-button' + 
                       (isBooked ? ' slot-booked' : '') +
                       (isSelected ? ' slot-selected' : ''),
                text: slot.time,
                disabled: isBooked
            });

            if (\!isBooked) {
                button.on('click', function() {
                    toggleSlotSelection(slot);
                });
            }

            grid.append(button);
        });
    }

    /**
     * Renderizza prenotazioni esistenti
     */
    function renderBookedSlots() {
        const container = $('#existing-bookings-section');
        const list = $('#existing-bookings-list');
        
        list.empty();

        if (bookedSlots.length === 0) {
            container.hide();
            return;
        }

        bookedSlots.forEach(booking => {
            const row = $('<tr>');
            row.append($('<td>'). text(booking.ora_inizio));
            row.append($('<td>'). text(booking.motivo_prenotazione));
            list.append(row);
        });

        container.show();
    }

    /**
     * Toggle selezione slot
     */
    function toggleSlotSelection(slot) {
        const index = selectedSlots.findIndex(s => s.time === slot.time);
        
        if (index > -1) {
            // Rimuovi selezione
            selectedSlots.splice(index, 1);
        } else {
            // Aggiungi selezione
            selectedSlots.push({
                time: slot.time,
                date: currentDate,
                aula_id: currentAulaId
            });
        }

        updateSelectedSlotsUI();
        renderSlots(); // Re-render per aggiornare stati
    }

    /**
     * Rimuovi slot selezionato
     */
    function removeSelectedSlot(slotTime) {
        selectedSlots = selectedSlots.filter(s => s.time \!== slotTime);
        updateSelectedSlotsUI();
        renderSlots();
    }

    /**
     * Aggiorna UI slot selezionati
     */
    function updateSelectedSlotsUI() {
        const summary = $('#selected-slots-summary');
        const badges = $('#selected-slots-badges');
        const formSection = $('#booking-form-section');
        
        badges.empty();

        if (selectedSlots.length === 0) {
            summary.hide();
            formSection.hide();
            return;
        }

        // Ordina slot per orario
        selectedSlots.sort((a, b) => a.time.localeCompare(b.time));

        selectedSlots.forEach(slot => {
            const badge = $('<div class="slot-badge">')
                .append($('<span>'). text(slot.date + ' ' + slot.time))
                .append($('<i class="fas fa-times remove-slot">'). data('slot-time', slot.time));
            
            badges.append(badge);
        });

        summary.show();
        formSection.show();

        // Aggiorna campo hidden con slot selezionati
        $('#selected_slots_input').val(JSON.stringify(selectedSlots));
    }

    /**
     * Invia prenotazione multipla
     */
    function submitMultiSlotBooking(form) {
        if (selectedSlots.length === 0) {
            showError('Seleziona almeno uno slot');
            return;
        }

        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Invio...');

        $.ajax({
            url: prenotazione_aule_ssm_public.ajax_url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    showSuccess('Prenotazione confermata per ' + selectedSlots.length + ' slot\!');
                    
                    // Reset e chiudi dopo 2 secondi
                    setTimeout(() => {
                        form[0].reset();
                        selectedSlots = [];
                        bootstrap.Modal.getInstance(form.closest('.modal')[0]).hide();
                        
                        // Ricarica calendario se esiste
                        if (typeof window.reloadCalendar === 'function') {
                            window.reloadCalendar(currentAulaId);
                        }
                    }, 2000);
                } else {
                    showError(response.data.message || 'Errore nella prenotazione');
                }
            },
            error: function() {
                showError('Errore di connessione');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Conferma Prenotazione');
            }
        });
    }

    /**
     * Formatta data in italiano
     */
    function formatDateItalian(dateStr) {
        const date = new Date(dateStr);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('it-IT', options);
    }

    /**
     * Mostra messaggio successo
     */
    function showSuccess(message) {
        const alert = $('#booking-success-message');
        alert.find('p').text(message);
        alert.show();
        $('#booking-error-message').hide();
        
        setTimeout(() => alert.fadeOut(), 3000);
    }

    /**
     * Mostra messaggio errore
     */
    function showError(message) {
        const alert = $('#booking-error-message');
        alert.find('#booking-error-text').text(message);
        alert.show();
        $('#booking-success-message').hide();
        $('#slots-loading').hide();
    }

    // Inizializza al caricamento del documento
    $(document).ready(function() {
        initMultiSlot();
    });

})(jQuery);
