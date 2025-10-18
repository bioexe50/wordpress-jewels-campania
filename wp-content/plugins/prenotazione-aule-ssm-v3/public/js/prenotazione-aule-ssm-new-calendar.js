/**
 * JavaScript per il nuovo calendario con selezione multipla slot
 * @package Prenotazione_Aule_SSM
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Variabili globali
    const Calendar = {
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        selectedDate: null,
        selectedSlots: [],
        aulaId: null,
        availableSlots: [],
        bookedSlots: []
    };

    /**
     * Inizializzazione calendario
     */
    function init() {
        Calendar.aulaId = $('.aule-new-calendar-wrapper').data('aula-id');

        if (!Calendar.aulaId) {
            console.error('Aula ID non trovato');
            return;
        }

        renderCalendar();
        preloadMonthBookings();
        bindEvents();
    }

    /**
     * Bind eventi
     */
    function bindEvents() {
        // Navigazione mesi
        $(document).on('click', '.pas-btn-prev-month', function() {
            Calendar.currentMonth--;
            if (Calendar.currentMonth < 0) {
                Calendar.currentMonth = 11;
                Calendar.currentYear--;
            }
            renderCalendar();
            preloadMonthBookings();
        });

        $(document).on('click', '.pas-btn-next-month', function() {
            Calendar.currentMonth++;
            if (Calendar.currentMonth > 11) {
                Calendar.currentMonth = 0;
                Calendar.currentYear++;
            }
            renderCalendar();
            preloadMonthBookings();
        });

        // Click su giorno
        $(document).on('click', '.day-cell.available', function() {
            const dateStr = $(this).data('date');
            if (dateStr) {
                Calendar.selectedDate = dateStr;
                openSlotModal(dateStr);
            }
        });

        // Toggle selezione slot nel modale
        $(document).on('click', '.slot-badge-selectable', function() {
            toggleSlotInModal($(this));
        });

        // Conferma selezione slot
        $(document).on('click', '#confirmSlotsBtn', function() {
            confirmSlotSelection();
        });

        // Rimuovi singolo slot dalla sidebar
        $(document).on('click', '.remove-selected-slot', function() {
            const slotTime = $(this).data('slot-time');
            const slotDate = $(this).data('slot-date');
            removeSlot(slotTime, slotDate);
        });

        // Rimuovi tutti gli slot
        $(document).on('click', '.pas-btn-clear-all', function() {
            clearAllSlots();
        });

        // Submit form prenotazione multipla
        $(document).on('submit', '#multiSlotBookingForm', function(e) {
            e.preventDefault();
            submitMultiBooking($(this));
        });
    }

    /**
     * Renderizza calendario
     */

    /**
     * Controlla se un giorno ha tutti gli slot occupati
     */
    function isDayFullyBooked(dateStr) {
        // Verifica se ci sono slot disponibili per questa data
        // Quando apriamo il modale per una data, controlliamo se tutti gli slot sono occupati

        if (!Calendar.bookedSlots || Calendar.bookedSlots.length === 0) {
            return false;
        }

        // Conta quanti slot sono occupati per questa data
        const bookedCount = Calendar.bookedSlots.filter(b => {
            return b.data_prenotazione === dateStr;
        }).length;

        // Se ci sono 8 o più slot occupati, consideriamo il giorno pieno
        // (questo valore può essere configurato in base agli slot configurati)
        return bookedCount >= 8;
    }


    /**
     * Precarica le prenotazioni del mese corrente per il rendering del calendario
     */
    function preloadMonthBookings() {
        const firstDay = new Date(Calendar.currentYear, Calendar.currentMonth, 1);
        const lastDay = new Date(Calendar.currentYear, Calendar.currentMonth + 1, 0);

        $.ajax({
            url: prenotazioneAuleSSMData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'prenotazione_aule_ssm_get_month_bookings',
                aula_id: Calendar.aulaId,
                year: Calendar.currentYear,
                month: Calendar.currentMonth + 1,
                nonce: prenotazioneAuleSSMData.nonce
            },
            success: function(response) {
                if (response.success && response.data.bookings) {
                    // Popola bookedSlots con tutte le prenotazioni del mese
                    Calendar.bookedSlots = response.data.bookings;
                    // Ri-renderizza il calendario con i dati aggiornati
                    renderCalendar();
                }
            }
        });
    }

    /**
     * Controlla se un giorno ha alcuni slot occupati (ma non tutti)
     */
    function isDayPartiallyBooked(dateStr) {
        if (!Calendar.bookedSlots || Calendar.bookedSlots.length === 0) {
            return false;
        }

        const bookedCount = Calendar.bookedSlots.filter(b => {
            return b.data_prenotazione === dateStr;
        }).length;

        // Ha prenotazioni ma non è completamente pieno
        return bookedCount > 0 && bookedCount < 8;
    }
    /**
     * Renderizza il calendario
     */
    function renderCalendar() {
        const monthNames = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                          'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
        const dayNames = ['DOM', 'LUN', 'MAR', 'MER', 'GIO', 'VEN', 'SAB'];

        // Header mese/anno
        $('.month-year-display').text(monthNames[Calendar.currentMonth] + ' ' + Calendar.currentYear);

        // Header giorni settimana
        const weekdaysHtml = dayNames.map(day => `<div class="weekday-label">${day}</div>`).join('');
        $('.weekdays-header').html(weekdaysHtml);

        // Giorni del mese
        const firstDay = new Date(Calendar.currentYear, Calendar.currentMonth, 1).getDay();
        const daysInMonth = new Date(Calendar.currentYear, Calendar.currentMonth + 1, 0).getDate();
        const today = new Date();
        const todayStr = formatDate(today);

        let daysHtml = '';

        // Celle vuote prima del primo giorno
        for (let i = 0; i < firstDay; i++) {
            daysHtml += '<div class="day-cell empty"></div>';
        }

        // Giorni del mese
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(Calendar.currentYear, Calendar.currentMonth, day);
            const dateStr = formatDate(date);
            const isPast = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const isToday = dateStr === todayStr;
            const hasSelectedSlots = Calendar.selectedSlots.some(s => s.date === dateStr);
            const fullyBooked = !isPast && isDayFullyBooked(dateStr);
            const partiallyBooked = !isPast && !fullyBooked && isDayPartiallyBooked(dateStr);

            let classes = 'day-cell';
            if (isPast) {
                classes += ' disabled';
            } else {
                classes += ' available'; // Tutti i giorni futuri sono cliccabili
                if (fullyBooked) {
                    classes += ' fully-booked';
                } else if (partiallyBooked) {
                    classes += ' partially-booked';
                }
            }
            if (isToday) classes += ' today';
            if (hasSelectedSlots) classes += ' selected';

            daysHtml += `<div class="${classes}" data-date="${dateStr}">${day}</div>`;
        }

        $('.days-grid').html(daysHtml);
    }

    /**
     * Apri modale selezione slot
     */
    function openSlotModal(dateStr) {
        const modal = new bootstrap.Modal(document.getElementById('slotSelectionModal'));

        // Formatta data italiana
        const dateObj = new Date(dateStr + 'T00:00:00');
        const formattedDate = dateObj.toLocaleDateString('it-IT', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        $('#modalSelectedDate').text(formattedDate);

        // Mostra loading
        $('.modal-loading').show();
        $('.available-slots-grid').empty();
        $('.modal-empty').hide();
        $('.modal-selected-count').hide();

        modal.show();

        // Carica slot
        loadSlotsForDate(dateStr);
    }

    /**
     * Carica slot disponibili per una data
     */
    function loadSlotsForDate(dateStr) {
        $.ajax({
            url: prenotazioneAuleSSMData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_slots_for_date',
                aula_id: Calendar.aulaId,
                date: dateStr,
                nonce: prenotazioneAuleSSMData.nonce
            },
            success: function(response) {
                if (response.success) {
                    Calendar.availableSlots = response.data.available_slots || [];
                    Calendar.bookedSlots = response.data.booked_slots || [];
                    renderModalSlots(dateStr);
                } else {
                    showModalError(response.data.message || 'Errore caricamento slot');
                }
            },
            error: function() {
                showModalError('Errore di connessione');
            },
            complete: function() {
                $('.modal-loading').hide();
            }
        });
    }

    /**
     * Renderizza slot nel modale come badge
     */
    function renderModalSlots(dateStr) {
        const grid = $('.available-slots-grid');
        grid.empty();

        if (Calendar.availableSlots.length === 0) {
            $('.modal-empty').show();
            return;
        }

        Calendar.availableSlots.forEach(slot => {
            const isBooked = Calendar.bookedSlots.some(b => b.ora_inizio.substring(0, 5) === slot.time);
            const isSelected = Calendar.selectedSlots.some(s => s.time === slot.time && s.date === dateStr);

            let badgeClass = 'slot-badge-selectable';
            let disabled = '';
            let content = slot.time;

            if (isBooked) {
                badgeClass += ' slot-booked';
                disabled = 'disabled';
                const booking = Calendar.bookedSlots.find(b => b.ora_inizio.substring(0, 5) === slot.time);
                content += `<br><small>${booking.motivo_prenotazione}</small>`;
            } else if (isSelected) {
                badgeClass += ' slot-selected';
            }

            const badge = $(`
                <button type="button"
                        class="${badgeClass}"
                        data-slot-time="${slot.time}"
                        data-slot-date="${dateStr}"
                        ${disabled}>
                    <span class="dashicons dashicons-clock"></span>
                    ${content}
                </button>
            `);

            grid.append(badge);
        });

        updateModalCounter();
    }

    /**
     * Toggle selezione slot nel modale
     */
    function toggleSlotInModal($badge) {
        const slotTime = $badge.data('slot-time');
        const slotDate = $badge.data('slot-date');

        const index = Calendar.selectedSlots.findIndex(s => s.time === slotTime && s.date === slotDate);

        if (index > -1) {
            // Rimuovi
            Calendar.selectedSlots.splice(index, 1);
            $badge.removeClass('slot-selected');
        } else {
            // Aggiungi
            Calendar.selectedSlots.push({
                time: slotTime,
                date: slotDate,
                aula_id: Calendar.aulaId
            });
            $badge.addClass('slot-selected');
        }

        updateModalCounter();
    }

    /**
     * Aggiorna counter slot selezionati nel modale
     */
    function updateModalCounter() {
        const count = Calendar.selectedSlots.filter(s => s.date === Calendar.selectedDate).length;

        if (count > 0) {
            $('#modalSlotCount').text(count);
            $('.modal-selected-count').show();
            $('#confirmSlotsBtn').prop('disabled', false);
        } else {
            $('.modal-selected-count').hide();
            $('#confirmSlotsBtn').prop('disabled', true);
        }
    }

    /**
     * Conferma selezione slot e chiudi modale
     */
    function confirmSlotSelection() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('slotSelectionModal'));
        modal.hide();

        updateSidebar();
        renderCalendar(); // Aggiorna calendario con indicatori
    }

    /**
     * Aggiorna sidebar con slot selezionati
     */
    function updateSidebar() {
        const $slotsList = $('.selected-slots-list');
        $slotsList.empty();

        if (Calendar.selectedSlots.length === 0) {
            $('.selected-slots-widget').hide();
            $('.booking-form-widget').hide();
            $('.slots-empty-state').show();
            return;
        }

        // Ordina per data e ora
        Calendar.selectedSlots.sort((a, b) => {
            if (a.date !== b.date) return a.date.localeCompare(b.date);
            return a.time.localeCompare(b.time);
        });

        // Raggruppa per data
        const groupedByDate = {};
        Calendar.selectedSlots.forEach(slot => {
            if (!groupedByDate[slot.date]) {
                groupedByDate[slot.date] = [];
            }
            groupedByDate[slot.date].push(slot);
        });

        // Renderizza badge raggruppati per data
        Object.keys(groupedByDate).forEach(date => {
            const dateObj = new Date(date + 'T00:00:00');
            const formattedDate = dateObj.toLocaleDateString('it-IT', {
                weekday: 'short',
                day: 'numeric',
                month: 'short'
            });

            const $dateGroup = $(`<div class="slot-date-group">
                <div class="slot-date-label">${formattedDate}</div>
                <div class="slot-badges-container"></div>
            </div>`);

            groupedByDate[date].forEach(slot => {
                const $badge = $(`
                    <div class="selected-slot-badge">
                        <span class="dashicons dashicons-clock"></span>
                        <span class="slot-time-text">${slot.time}</span>
                        <button type="button" class="remove-selected-slot"
                                data-slot-time="${slot.time}"
                                data-slot-date="${slot.date}"
                                title="Rimuovi">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </div>
                `);
                $dateGroup.find('.slot-badges-container').append($badge);
            });

            $slotsList.append($dateGroup);
        });

        $('.selected-slots-widget').show();
        $('.booking-form-widget').show();
        $('.slots-empty-state').hide();

        // Aggiorna campo hidden
        $('#selected_slots_data').val(JSON.stringify(Calendar.selectedSlots));
    }

    /**
     * Rimuovi singolo slot
     */
    function removeSlot(slotTime, slotDate) {
        Calendar.selectedSlots = Calendar.selectedSlots.filter(s =>
            !(s.time === slotTime && s.date === slotDate)
        );
        updateSidebar();
        renderCalendar();
    }

    /**
     * Rimuovi tutti gli slot
     */
    function clearAllSlots() {
        if (confirm('Rimuovere tutti gli slot selezionati?')) {
            Calendar.selectedSlots = [];
            updateSidebar();
            renderCalendar();
        }
    }

    /**
     * Invia prenotazione multipla
     */
    function submitMultiBooking($form) {
        if (Calendar.selectedSlots.length === 0) {
            showFormError('Seleziona almeno uno slot');
            return;
        }

        const $submitBtn = $form.find('.pas-btn-submit-multi-booking');
        const $btnText = $submitBtn.find('.pas-btn-text');
        const $btnSpinner = $submitBtn.find('.pas-btn-spinner');

        $submitBtn.prop('disabled', true);
        $btnText.hide();
        $btnSpinner.show();

        $.ajax({
            url: prenotazioneAuleSSMData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'prenotazione_aule_ssm_multi_booking',
                aula_id: Calendar.aulaId,
                selected_slots: JSON.stringify(Calendar.selectedSlots),
                nome_richiedente: $('#multi_nome').val(),
                cognome_richiedente: $('#multi_cognome').val(),
                email_richiedente: $('#multi_email').val(),
                motivo_prenotazione: $('#multi_motivo').val(),
                privacy_accepted: $('#multi_privacy').is(':checked') ? 1 : 0,
                nonce: prenotazioneAuleSSMData.multiBookingNonce
            },
            success: function(response) {
                if (response.success) {
                    showFormSuccess(response.data.message || 'Prenotazione confermata!');

                    // Reset dopo 2 secondi
                    setTimeout(() => {
                        $form[0].reset();
                        Calendar.selectedSlots = [];
                        updateSidebar();
                        renderCalendar();
                        preloadMonthBookings(); // Ricarica le prenotazioni dal server
                    }, 2000);
                } else {
                    showFormError(response.data.message || 'Errore nella prenotazione');
                }
            },
            error: function() {
                showFormError('Errore di connessione. Riprova.');
            },
            complete: function() {
                $submitBtn.prop('disabled', false);
                $btnText.show();
                $btnSpinner.hide();
            }
        });
    }

    /**
     * Mostra errore nel modale
     */
    function showModalError(message) {
        $('.modal-empty').html(`<p class="text-danger">${message}</p>`).show();
    }

    /**
     * Mostra errore nel form
     */
    function showFormError(message) {
        const $alert = $('#multiSlotBookingForm .pas-alert-danger');
        $alert.text(message).show();
        setTimeout(() => $alert.fadeOut(), 5000);
    }

    /**
     * Mostra successo nel form
     */
    function showFormSuccess(message) {
        const $alert = $('#multiSlotBookingForm .pas-alert-success');
        $alert.text(message).show();
        setTimeout(() => $alert.fadeOut(), 5000);
    }

    /**
     * Formatta data in YYYY-MM-DD
     */
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Init al caricamento
    $(document).ready(function() {
        if ($('.aule-new-calendar-wrapper').length) {
            init();
        }
    });

})(jQuery);
