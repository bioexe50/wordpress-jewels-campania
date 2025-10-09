/**
 * JavaScript per NEW_CALENDAR - Interfaccia stile Calendly
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 */

(function($) {
    'use strict';

    /**
     * Classe principale per il nuovo calendario
     */
    class AuleBookingNewCalendar {
        constructor(wrapper) {
            this.$wrapper = $(wrapper);
            this.aulaId = this.$wrapper.data('aula-id');
            this.currentDate = new Date();
            this.selectedDate = null;
            this.availableDates = [];
            this.slots = [];
            this.locale = this.getLocale();

            this.init();
        }

        init() {
            this.cacheElements();
            this.bindEvents();
            this.renderCalendar();
            this.loadAvailableDates();
        }

        cacheElements() {
            // Calendar elements
            this.$monthYearDisplay = this.$wrapper.find('.month-year-display');
            this.$weekdaysHeader = this.$wrapper.find('.weekdays-header');
            this.$daysGrid = this.$wrapper.find('.days-grid');
            this.$btnPrevMonth = this.$wrapper.find('.btn-prev-month');
            this.$btnNextMonth = this.$wrapper.find('.btn-next-month');

            // Slots elements
            this.$selectedDateTitle = this.$wrapper.find('.selected-date-title');
            this.$selectedDateSubtitle = this.$wrapper.find('.selected-date-subtitle');
            this.$slotsList = this.$wrapper.find('.slots-list');
            this.$slotsContainer = this.$wrapper.find('.slots-container');
            this.$emptyState = this.$wrapper.find('.slots-empty-state');
            this.$loadingState = this.$wrapper.find('.slots-loading-state');

            // Modal elements
            this.$modal = $('#newCalendarBookingModal');
            this.$form = $('#newCalendarBookingForm');
            this.$submitBtn = $('#submitBookingBtn');
            this.$errorAlert = this.$modal.find('.booking-error');
            this.$successAlert = this.$modal.find('.booking-success');
        }

        bindEvents() {
            // Navigation
            this.$btnPrevMonth.on('click', () => this.prevMonth());
            this.$btnNextMonth.on('click', () => this.nextMonth());

            // Day click
            this.$daysGrid.on('click', '.day-cell.available', (e) => {
                const date = $(e.currentTarget).data('date');
                this.selectDate(date);
            });

            // Slot click
            this.$slotsList.on('click', '.time-slot.available', (e) => {
                const slotData = $(e.currentTarget).data('slot');
                this.openBookingModal(slotData);
            });

            // Form submit
            this.$submitBtn.on('click', () => this.submitBooking());

            // Modal events
            this.$modal.on('hidden.bs.modal', () => this.resetForm());
        }

        getLocale() {
            return {
                monthNames: [
                    'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                    'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'
                ],
                dayNames: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
                today: 'Oggi',
                available: 'Disponibile',
                occupied: 'Occupato',
                selectDate: 'Seleziona una data',
                chooseDay: 'Scegli un giorno dal calendario per vedere gli orari disponibili',
                noSlots: 'Nessuno slot disponibile per questa data',
                loadingSlots: 'Caricamento slot...'
            };
        }

        renderCalendar() {
            this.renderMonthYear();
            this.renderWeekdays();
            this.renderDays();
        }

        renderMonthYear() {
            const monthName = this.locale.monthNames[this.currentDate.getMonth()];
            const year = this.currentDate.getFullYear();
            this.$monthYearDisplay.text(`${monthName} ${year}`);
        }

        renderWeekdays() {
            this.$weekdaysHeader.empty();
            this.locale.dayNames.forEach(day => {
                this.$weekdaysHeader.append(`<div class="weekday-label">${day}</div>`);
            });
        }

        renderDays() {
            this.$daysGrid.empty();

            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startingDayOfWeek = firstDay.getDay();
            const daysInMonth = lastDay.getDate();

            // Empty cells before first day
            for (let i = 0; i < startingDayOfWeek; i++) {
                this.$daysGrid.append('<div class="day-cell empty"></div>');
            }

            // Days in month
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dateStr = this.formatDateISO(date);
                const isPast = date < today;
                const isToday = date.getTime() === today.getTime();
                const isSelected = this.selectedDate === dateStr;
                const hasSlots = this.availableDates.includes(dateStr);

                let classes = ['day-cell'];
                if (isPast) {
                    classes.push('disabled');
                } else if (hasSlots) {
                    classes.push('available');
                }
                if (isToday) {
                    classes.push('today');
                }
                if (isSelected) {
                    classes.push('selected');
                }
                if (hasSlots) {
                    classes.push('has-slots');
                }

                this.$daysGrid.append(
                    `<div class="${classes.join(' ')}" data-date="${dateStr}">${day}</div>`
                );
            }
        }

        prevMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
            this.loadAvailableDates();
        }

        nextMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
            this.loadAvailableDates();
        }

        loadAvailableDates() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth() + 1; // JavaScript months are 0-indexed

            $.ajax({
                url: aulaBookingData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aule_get_available_dates',
                    aula_id: this.aulaId,
                    year: year,
                    month: month,
                    nonce: aulaBookingData.nonce
                },
                success: (response) => {
                    if (response.success && response.data) {
                        this.availableDates = response.data;
                        this.renderDays();
                    }
                },
                error: (xhr, status, error) => {
                    console.error('Errore caricamento date disponibili:', error);
                }
            });
        }

        selectDate(dateStr) {
            this.selectedDate = dateStr;
            this.renderDays();
            this.loadSlotsForDate(dateStr);
            this.updateSelectedDateHeader(dateStr);
        }

        updateSelectedDateHeader(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            const dayName = this.locale.dayNames[date.getDay()];
            const day = date.getDate();
            const monthName = this.locale.monthNames[date.getMonth()];
            const year = date.getFullYear();

            this.$selectedDateTitle.text(`${dayName} ${day} ${monthName} ${year}`);
            this.$selectedDateSubtitle.text('Seleziona un orario disponibile');
        }

        loadSlotsForDate(dateStr) {
            this.showLoadingState();

            $.ajax({
                url: aulaBookingData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aule_check_availability',
                    aula_id: this.aulaId,
                    date: dateStr,
                    nonce: aulaBookingData.nonce
                },
                success: (slots) => {
                    this.slots = Array.isArray(slots) ? slots : [];
                    this.renderSlots();
                },
                error: (xhr, status, error) => {
                    console.error('Errore caricamento slot:', error);
                    this.showEmptyState(true);
                }
            });
        }

        renderSlots() {
            this.$slotsList.empty();
            this.hideLoadingState();

            if (!this.slots || this.slots.length === 0) {
                this.showEmptyState(false);
                return;
            }

            this.hideEmptyState();

            this.slots.forEach(slot => {
                const isAvailable = !slot.occupied;
                const statusText = isAvailable ? this.locale.available : this.locale.occupied;
                const statusClass = isAvailable ? 'available' : 'occupied';

                const $slotEl = $(`
                    <div class="time-slot ${statusClass}">
                        <div class="slot-info">
                            <div class="slot-time">${slot.start} - ${slot.end}</div>
                            <div class="slot-status">${statusText}</div>
                        </div>
                    </div>
                `);

                if (isAvailable) {
                    $slotEl.data('slot', slot);
                }

                this.$slotsList.append($slotEl);
            });
        }

        showLoadingState() {
            this.$slotsContainer.hide();
            this.$emptyState.hide();
            this.$loadingState.show();
        }

        hideLoadingState() {
            this.$loadingState.hide();
            this.$slotsContainer.show();
        }

        showEmptyState(isError = false) {
            this.$slotsContainer.hide();
            this.$loadingState.hide();
            this.$emptyState.show();

            const message = isError ?
                'Errore nel caricamento degli slot' :
                this.locale.noSlots;
            this.$emptyState.find('.empty-message').text(message);
        }

        hideEmptyState() {
            this.$emptyState.hide();
        }

        openBookingModal(slot) {
            // Populate summary
            this.$modal.find('.summary-date').text(this.formatDateDisplay(this.selectedDate));
            this.$modal.find('.summary-time').text(`${slot.start} - ${slot.end}`);

            // Set hidden fields
            $('#booking_slot_id').val(slot.id || '');
            $('#booking_date').val(this.selectedDate);
            $('#booking_time_start').val(slot.start);
            $('#booking_time_end').val(slot.end);

            // Show modal
            const modal = new bootstrap.Modal(this.$modal[0]);
            modal.show();
        }

        submitBooking() {
            if (!this.validateForm()) {
                return;
            }

            this.setSubmitButtonLoading(true);
            this.hideAlerts();

            const formData = this.$form.serialize();

            $.ajax({
                url: aulaBookingData.ajaxurl,
                type: 'POST',
                data: formData + '&action=aule_submit_booking&nonce=' + aulaBookingData.nonce,
                success: (response) => {
                    this.setSubmitButtonLoading(false);

                    if (response.success) {
                        this.showSuccess(response.data.message || 'Prenotazione confermata!');
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(this.$modal[0]);
                            modal.hide();
                            this.resetForm();
                            // Reload slots for selected date
                            if (this.selectedDate) {
                                this.loadSlotsForDate(this.selectedDate);
                            }
                            // Reload available dates
                            this.loadAvailableDates();
                        }, 2000);
                    } else {
                        this.showError(response.data.message || 'Errore durante la prenotazione');
                    }
                },
                error: (xhr, status, error) => {
                    this.setSubmitButtonLoading(false);
                    this.showError('Errore di connessione. Riprova.');
                    console.error('Errore submit booking:', error);
                }
            });
        }

        validateForm() {
            let isValid = true;
            this.$form.find('.form-control, .form-check-input').removeClass('is-invalid');

            // Nome
            const nome = $('#booking_nome').val().trim();
            if (nome.length < 2) {
                $('#booking_nome').addClass('is-invalid');
                isValid = false;
            }

            // Cognome
            const cognome = $('#booking_cognome').val().trim();
            if (cognome.length < 2) {
                $('#booking_cognome').addClass('is-invalid');
                isValid = false;
            }

            // Email
            const email = $('#booking_email').val().trim();
            if (!this.isValidEmail(email)) {
                $('#booking_email').addClass('is-invalid');
                isValid = false;
            }

            // Motivo
            const motivo = $('#booking_motivo').val().trim();
            if (motivo.length < 10) {
                $('#booking_motivo').addClass('is-invalid');
                isValid = false;
            }

            // Privacy
            const privacy = $('#booking_privacy').is(':checked');
            if (!privacy) {
                $('#booking_privacy').addClass('is-invalid');
                isValid = false;
            }

            return isValid;
        }

        isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        setSubmitButtonLoading(isLoading) {
            if (isLoading) {
                this.$submitBtn.prop('disabled', true);
                this.$submitBtn.find('.btn-text').hide();
                this.$submitBtn.find('.btn-spinner').show();
            } else {
                this.$submitBtn.prop('disabled', false);
                this.$submitBtn.find('.btn-text').show();
                this.$submitBtn.find('.btn-spinner').hide();
            }
        }

        showError(message) {
            this.$errorAlert.text(message).fadeIn();
            setTimeout(() => this.$errorAlert.fadeOut(), 5000);
        }

        showSuccess(message) {
            this.$successAlert.text(message).fadeIn();
        }

        hideAlerts() {
            this.$errorAlert.hide();
            this.$successAlert.hide();
        }

        resetForm() {
            this.$form[0].reset();
            this.$form.find('.form-control, .form-check-input').removeClass('is-invalid');
            this.hideAlerts();
        }

        formatDateISO(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        formatDateDisplay(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            const day = date.getDate();
            const monthName = this.locale.monthNames[date.getMonth()];
            const year = date.getFullYear();
            return `${day} ${monthName} ${year}`;
        }
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        $('.aule-new-calendar-wrapper').each(function() {
            new AuleBookingNewCalendar(this);
        });
    });

})(jQuery);