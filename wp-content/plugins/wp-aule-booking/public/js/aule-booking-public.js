/**
 * JavaScript per l'area pubblica del plugin
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 */

(function($) {
    'use strict';

    /**
     * Oggetto principale del plugin
     */
    var AuleBooking = {

        /**
         * Configurazioni
         */
        config: {
            ajaxUrl: aule_booking_ajax.ajax_url,
            nonce: aule_booking_ajax.nonce,
            settings: aule_booking_ajax.settings,
            strings: aule_booking_ajax.strings
        },

        /**
         * Cache per calendari attivi
         */
        calendars: {},

        /**
         * Inizializza il plugin
         */
        init: function() {
            this.bindEvents();
            this.initializeCalendars();
            this.initializeForms();
            this.initializeSearch();
        },

        /**
         * Bind degli eventi
         */
        bindEvents: function() {
            // Event delegation per elementi dinamici
            $(document).on('click', '.btn-book-aula', this.handleBookingClick);
            $(document).on('submit', '.booking-form', this.handleBookingSubmit);
            $(document).on('click', '.btn-search', this.handleSearch);
            $(document).on('click', '.btn-reset', this.handleReset);

            // Validazione form in tempo reale
            $(document).on('input change', '.booking-form input, .booking-form textarea', this.validateField);
            $(document).on('change', '.form-check-input', this.validateForm);
        },

        /**
         * Inizializza tutti i calendari presenti nella pagina
         */
        initializeCalendars: function() {
            var self = this;

            $('.aule-booking-calendar').each(function() {
                var $calendar = $(this);
                var auleId = $calendar.closest('.aule-booking-wrapper').data('aula-id');

                if (auleId && !self.calendars[auleId]) {
                    self.initializeCalendar($calendar, auleId);
                }
            });
        },

        /**
         * Inizializza un singolo calendario
         */
        initializeCalendar: function($calendarEl, auleId) {
            var self = this;
            var calendarId = $calendarEl.attr('id');

            // Configurazione FullCalendar
            var calendarConfig = {
                locale: 'it',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                eventSources: [{
                    url: self.config.ajaxUrl,
                    method: 'POST',
                    extraParams: {
                        action: 'aule_check_availability',
                        aula_id: auleId,
                        nonce: self.config.nonce
                    },
                    failure: function(error) {
                        console.error('Errore nel caricamento degli eventi:', error);
                        self.showError('Errore nel caricamento del calendario');
                    }
                }],
                eventClick: function(info) {
                    self.handleEventClick(info, auleId);
                },
                loading: function(isLoading) {
                    self.toggleLoading(auleId, isLoading);
                },
                eventDidMount: function(info) {
                    self.customizeEvent(info);
                },
                dayCellDidMount: function(info) {
                    self.customizeDayCell(info);
                },
                viewDidMount: function(info) {
                    self.onViewChange(info, auleId);
                }
            };

            // Applica configurazione personalizzata
            calendarConfig = self.applyCustomConfig(calendarConfig, auleId);

            // Inizializza il calendario
            var calendar = new FullCalendar.Calendar($calendarEl[0], calendarConfig);
            calendar.render();

            // Salva riferimento
            self.calendars[auleId] = calendar;
        },

        /**
         * Applica configurazione personalizzata al calendario
         */
        applyCustomConfig: function(config, auleId) {
            // Personalizzazione colori
            if (this.config.settings && this.config.settings.colors) {
                // Colori verranno applicati negli eventi stessi
            }

            // Limiti temporali
            if (this.config.settings) {
                var today = new Date();
                var maxDate = new Date();
                maxDate.setDate(today.getDate() + (this.config.settings.max_future_days || 30));

                config.validRange = {
                    start: today,
                    end: maxDate
                };
            }

            return config;
        },

        /**
         * Gestisce il click su un evento del calendario
         */
        handleEventClick: function(info, auleId) {
            var event = info.event;

            if (event.extendedProps.type === 'available') {
                // Slot disponibile - apri modal prenotazione
                this.openBookingModal(event, auleId);
            } else if (event.extendedProps.type === 'booked') {
                // Slot occupato - mostra informazioni (se pubbliche)
                this.showBookingInfo(event);
            }

            info.jsEvent.preventDefault();
        },

        /**
         * Apre il modal di prenotazione
         */
        openBookingModal: function(event, auleId) {
            var modalId = '#bookingModal-' + auleId;
            var $modal = $(modalId);

            if ($modal.length === 0) {
                console.error('Modal non trovato:', modalId);
                return;
            }

            // Popola i dati del slot
            this.populateBookingModal(event, $modal);

            // Setup reCAPTCHA se necessario
            this.setupRecaptcha($modal);

            // Mostra il modal
            var modal = new bootstrap.Modal($modal[0]);
            modal.show();
        },

        /**
         * Popola il modal con i dati dello slot selezionato
         */
        populateBookingModal: function(event, $modal) {
            var startDate = event.start;
            var endDate = event.end;

            // Aggiorna campi nascosti
            $modal.find('#booking_date').val(this.formatDate(startDate, 'Y-m-d'));
            $modal.find('#booking_start_time').val(this.formatDate(startDate, 'H:i:s'));
            $modal.find('#booking_end_time').val(this.formatDate(endDate, 'H:i:s'));

            // Aggiorna il riepilogo visivo
            $modal.find('.slot-date').text(this.formatDate(startDate, 'full-date'));
            $modal.find('.slot-time').text(
                this.formatDate(startDate, 'H:i') + ' - ' + this.formatDate(endDate, 'H:i')
            );

            // Reset del form
            $modal.find('form')[0].reset();
            $modal.find('.is-invalid').removeClass('is-invalid');
            $modal.find('.alert').hide();
        },

        /**
         * Setup reCAPTCHA v3
         */
        setupRecaptcha: function($modal) {
            if (this.config.settings.recaptcha_enabled && this.config.settings.recaptcha_site_key) {
                var $container = $modal.find('#recaptcha-container');
                $container.show();

                // Aggiorna site key
                $container.find('.g-recaptcha').attr('data-sitekey', this.config.settings.recaptcha_site_key);
            }
        },

        /**
         * Mostra informazioni prenotazione (per slot occupati)
         */
        showBookingInfo: function(event) {
            // Per privacy, mostra solo informazioni generiche
            var info = {
                date: this.formatDate(event.start, 'full-date'),
                time: this.formatDate(event.start, 'H:i') + ' - ' + this.formatDate(event.end, 'H:i'),
                status: event.extendedProps.stato || 'occupato'
            };

            this.showInfoModal('Slot Occupato', this.buildBookingInfoHtml(info));
        },

        /**
         * Costruisce HTML per info prenotazione
         */
        buildBookingInfoHtml: function(info) {
            return `
                <div class="booking-info">
                    <div class="booking-info-item">
                        <strong>Data:</strong> ${info.date}
                    </div>
                    <div class="booking-info-item">
                        <strong>Orario:</strong> ${info.time}
                    </div>
                    <div class="booking-info-item">
                        <strong>Stato:</strong>
                        <span class="booking-status booking-status-${info.status}">
                            ${this.getStatusLabel(info.status)}
                        </span>
                    </div>
                </div>
            `;
        },

        /**
         * Gestisce il click del bottone di prenotazione
         */
        handleBookingClick: function(e) {
            e.preventDefault();

            var $button = $(this);
            var auleId = $button.data('aule-id');

            if (!auleId) {
                console.error('ID aula mancante');
                return;
            }

            // Redirect alla pagina calendario o apri modal
            var calendarUrl = $button.attr('href');
            if (calendarUrl) {
                window.location.href = calendarUrl;
            }
        },

        /**
         * Gestisce l'invio del form di prenotazione
         */
        handleBookingSubmit: function(e) {
            e.preventDefault();

            var $form = $(this);
            var self = AuleBooking;

            // Validazione form
            if (!self.validateForm($form)) {
                return false;
            }

            // Disabilita il form durante l'invio
            self.setFormLoading($form, true);

            // Prepara dati
            var formData = new FormData($form[0]);
            formData.append('action', 'aule_submit_booking');

            // Gestione reCAPTCHA
            if (self.config.settings.recaptcha_enabled && self.config.settings.recaptcha_site_key) {
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.execute(self.config.settings.recaptcha_site_key, {action: 'booking'})
                        .then(function(token) {
                            formData.append('recaptcha_token', token);
                            self.submitBookingRequest($form, formData);
                        })
                        .catch(function(error) {
                            console.error('Errore reCAPTCHA:', error);
                            self.showFormError($form, 'Errore reCAPTCHA');
                            self.setFormLoading($form, false);
                        });
                } else {
                    self.showFormError($form, 'reCAPTCHA non disponibile');
                    self.setFormLoading($form, false);
                }
            } else {
                self.submitBookingRequest($form, formData);
            }

            return false;
        },

        /**
         * Invia la richiesta di prenotazione
         */
        submitBookingRequest: function($form, formData) {
            var self = this;

            $.ajax({
                url: self.config.ajaxUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    self.handleBookingResponse($form, response);
                },
                error: function(xhr, status, error) {
                    console.error('Errore AJAX:', error);
                    self.showFormError($form, self.config.strings.error);
                    self.setFormLoading($form, false);
                }
            });
        },

        /**
         * Gestisce la risposta della prenotazione
         */
        handleBookingResponse: function($form, response) {
            this.setFormLoading($form, false);

            if (response.success) {
                this.showFormSuccess($form, response.data.message || this.config.strings.booking_sent);

                // Ricarica il calendario dopo 2 secondi
                setTimeout(() => {
                    var auleId = $form.find('[name="aula_id"]').val();
                    if (this.calendars[auleId]) {
                        this.calendars[auleId].refetchEvents();
                    }

                    // Chiudi il modal
                    var $modal = $form.closest('.modal');
                    if ($modal.length) {
                        bootstrap.Modal.getInstance($modal[0]).hide();
                    }

                    // Reset form
                    $form[0].reset();
                    $form.find('.is-invalid').removeClass('is-invalid');
                }, 2000);

            } else {
                this.showFormError($form, response.data || this.config.strings.error);
            }
        },

        /**
         * Gestisce la ricerca aule
         */
        handleSearch: function(e) {
            e.preventDefault();

            var $form = $(this).closest('form');
            var $button = $(this);

            // Mostra loading
            $button.addClass('loading').prop('disabled', true);

            // Simula ricerca (in un'implementazione reale, faresti una chiamata AJAX)
            setTimeout(() => {
                $button.removeClass('loading').prop('disabled', false);
                // Aggiorna risultati di ricerca
                AuleBooking.updateSearchResults($form);
            }, 1000);
        },

        /**
         * Gestisce il reset della ricerca
         */
        handleReset: function(e) {
            e.preventDefault();

            var $form = $(this).closest('form');
            $form[0].reset();

            // Reset risultati
            this.updateSearchResults($form);
        },

        /**
         * Aggiorna i risultati di ricerca
         */
        updateSearchResults: function($form) {
            // Implementazione ricerca (placeholder)
            console.log('Aggiornamento risultati ricerca');
        },

        /**
         * Validazione singolo campo
         */
        validateField: function() {
            var $field = $(this);
            var isValid = $field[0].checkValidity();

            $field.toggleClass('is-invalid', !isValid);
            $field.toggleClass('is-valid', isValid);

            return isValid;
        },

        /**
         * Validazione form completa
         */
        validateForm: function($form) {
            if (typeof $form === 'object' && $form.type) {
                // Chiamata da event handler
                $form = $(this).closest('form');
            }

            var isValid = true;
            var self = AuleBooking;

            // Valida tutti i campi required
            $form.find('input[required], textarea[required], select[required]').each(function() {
                var fieldValid = self.validateField.call(this);
                isValid = isValid && fieldValid;
            });

            // Valida privacy checkbox
            var $privacyCheck = $form.find('#privacy_accepted');
            if ($privacyCheck.length && !$privacyCheck.is(':checked')) {
                isValid = false;
                $privacyCheck.addClass('is-invalid');
                self.showFormError($form, self.config.strings.privacy_required);
            } else if ($privacyCheck.length) {
                $privacyCheck.removeClass('is-invalid').addClass('is-valid');
            }

            // Valida email specificamente
            var $email = $form.find('input[type="email"]');
            if ($email.length) {
                var emailValue = $email.val();
                var emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue);

                if (!emailValid && emailValue) {
                    isValid = false;
                    $email.addClass('is-invalid');
                }
            }

            $form.toggleClass('was-validated', true);
            return isValid;
        },

        /**
         * Imposta stato loading del form
         */
        setFormLoading: function($form, loading) {
            var $submitBtn = $form.find('button[type="submit"], .btn-submit');

            $submitBtn.toggleClass('loading', loading);
            $submitBtn.prop('disabled', loading);

            $form.find('input, textarea, select').prop('disabled', loading);

            if (loading) {
                $submitBtn.find('.btn-text').hide();
                $submitBtn.find('.btn-spinner').show();
            } else {
                $submitBtn.find('.btn-text').show();
                $submitBtn.find('.btn-spinner').hide();
            }
        },

        /**
         * Mostra messaggio di successo nel form
         */
        showFormSuccess: function($form, message) {
            var $alert = $form.find('.alert-success');

            if ($alert.length === 0) {
                $alert = $('<div class="alert alert-success"></div>');
                $form.prepend($alert);
            }

            $alert.html(message).show();
            $form.find('.alert-danger').hide();

            // Scroll al messaggio
            $alert[0].scrollIntoView({behavior: 'smooth', block: 'nearest'});
        },

        /**
         * Mostra messaggio di errore nel form
         */
        showFormError: function($form, message) {
            var $alert = $form.find('.alert-danger');

            if ($alert.length === 0) {
                $alert = $('<div class="alert alert-danger"></div>');
                $form.prepend($alert);
            }

            $alert.html(message).show();
            $form.find('.alert-success').hide();

            // Scroll al messaggio
            $alert[0].scrollIntoView({behavior: 'smooth', block: 'nearest'});
        },

        /**
         * Mostra/nascondi loading overlay
         */
        toggleLoading: function(auleId, show) {
            var $loadingEl = $('#loading-' + auleId);
            $loadingEl.toggle(show);
        },

        /**
         * Personalizza l'aspetto degli eventi
         */
        customizeEvent: function(info) {
            var event = info.event;

            // Aggiungi tooltip
            info.el.title = event.title + '\n' +
                           this.formatDate(event.start, 'full-date') + ' ' +
                           this.formatDate(event.start, 'H:i') + ' - ' +
                           this.formatDate(event.end, 'H:i');

            // Personalizza styling per tipo
            if (event.extendedProps.type === 'available') {
                info.el.classList.add('available-slot');
            } else if (event.extendedProps.type === 'booked') {
                info.el.classList.add('booked-slot');
            }
        },

        /**
         * Personalizza le celle dei giorni
         */
        customizeDayCell: function(info) {
            var date = info.date;
            var today = new Date();

            // Evidenzia oggi
            if (this.isSameDay(date, today)) {
                info.el.classList.add('today');
            }

            // Disabilita giorni passati
            if (date < today) {
                info.el.classList.add('fc-day-disabled');
            }
        },

        /**
         * Callback per cambio vista calendario
         */
        onViewChange: function(info, auleId) {
            console.log('Vista cambiata per aula', auleId, info.view.type);

            // Aggiorna configurazioni specifiche per vista
            this.updateViewSpecificSettings(info.view.type, auleId);
        },

        /**
         * Aggiorna configurazioni specifiche per vista
         */
        updateViewSpecificSettings: function(viewType, auleId) {
            var calendar = this.calendars[auleId];
            if (!calendar) return;

            // Configurazioni diverse per vista
            switch (viewType) {
                case 'dayGridMonth':
                    // Vista mensile
                    break;
                case 'timeGridWeek':
                    // Vista settimanale
                    break;
                case 'timeGridDay':
                    // Vista giornaliera
                    break;
            }
        },

        /**
         * Inizializza i form di ricerca
         */
        initializeForms: function() {
            // Inizializza datepicker se disponibile
            if ($.fn.datepicker) {
                $('.date-picker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'it',
                    autoclose: true,
                    todayHighlight: true
                });
            }
        },

        /**
         * Inizializza la funzionalità di ricerca
         */
        initializeSearch: function() {
            // Debounce per ricerca real-time
            var searchTimeout;

            $('.search-input').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    AuleBooking.performLiveSearch($(this));
                }, 300);
            });
        },

        /**
         * Esegue ricerca in tempo reale
         */
        performLiveSearch: function($input) {
            var query = $input.val().trim();

            if (query.length < 2) {
                return;
            }

            // Implementa ricerca real-time
            console.log('Ricerca live:', query);
        },

        /**
         * Mostra modal informativo
         */
        showInfoModal: function(title, content) {
            var modalHtml = `
                <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${content}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Rimuovi modal esistente
            $('#infoModal').remove();

            // Aggiungi e mostra nuovo modal
            $('body').append(modalHtml);
            var modal = new bootstrap.Modal($('#infoModal')[0]);
            modal.show();

            // Rimuovi dopo chiusura
            $('#infoModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        },

        /**
         * Mostra messaggio di errore globale
         */
        showError: function(message) {
            this.showInfoModal('Errore', `<div class="alert alert-danger">${message}</div>`);
        },

        /**
         * Utility: Formatta data
         */
        formatDate: function(date, format) {
            if (typeof date === 'string') {
                date = new Date(date);
            }

            switch (format) {
                case 'Y-m-d':
                    return date.toISOString().split('T')[0];

                case 'H:i:s':
                    return date.toTimeString().split(' ')[0];

                case 'H:i':
                    return date.toTimeString().substr(0, 5);

                case 'full-date':
                    return date.toLocaleDateString('it-IT', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                default:
                    return date.toLocaleDateString('it-IT');
            }
        },

        /**
         * Utility: Controlla se due date sono lo stesso giorno
         */
        isSameDay: function(date1, date2) {
            return date1.toDateString() === date2.toDateString();
        },

        /**
         * Utility: Ottieni label per stato
         */
        getStatusLabel: function(status) {
            var labels = {
                'in_attesa': 'In Attesa',
                'confermata': 'Confermata',
                'rifiutata': 'Rifiutata',
                'cancellata': 'Cancellata',
                'occupato': 'Occupato'
            };

            return labels[status] || status;
        }
    };

    // Callback globale per reCAPTCHA
    window.onRecaptchaSuccess = function(token) {
        console.log('reCAPTCHA completato con successo');
    };

    // Inizializzazione quando il DOM è pronto
    $(document).ready(function() {
        AuleBooking.init();
    });

    // Esporta per uso esterno
    window.AuleBooking = AuleBooking;

})(jQuery);