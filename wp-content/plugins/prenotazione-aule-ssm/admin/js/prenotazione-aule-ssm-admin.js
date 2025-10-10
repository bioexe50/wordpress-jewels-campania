/**
 * JavaScript per l'area admin del plugin
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 */

(function($) {
    'use strict';

    /**
     * Oggetto principale dell'admin
     */
    var AuleBookingAdmin = {

        /**
         * Configurazioni
         */
        config: {
            ajaxUrl: prenotazione_aule_ssm_admin_ajax.ajax_url,
            nonce: prenotazione_aule_ssm_admin_ajax.nonce,
            strings: prenotazione_aule_ssm_admin_ajax.strings
        },

        /**
         * Cache per calendari admin
         */
        calendars: {},

        /**
         * Inizializza l'admin
         */
        init: function() {
            this.bindEvents();
            this.initializeComponents();
            this.initializeCalendars();
            this.setupFormValidation();
        },

        /**
         * Bind degli eventi
         */
        bindEvents: function() {
            // Gestione prenotazioni - DISABILITATI per pagina prenotazioni (usa modali inline)
            // Solo per altre pagine che potrebbero usarli
            // $(document).on('click', '.approve-booking', this.handleApproveBooking);
            // $(document).on('click', '.reject-booking', this.handleRejectBooking);
            // $(document).on('click', '.delete-booking', this.handleDeleteBooking);

            // Gestione aule
            $(document).on('click', '.delete-aula', this.handleDeleteAula);
            $(document).on('change', '#aula_images', this.handleImageUpload);
            $(document).on('click', '.remove-image', this.handleRemoveImage);

            // Generazione slot
            $(document).on('click', '.generate-slots', this.handleGenerateSlots);
            $(document).on('change', '.aula-selector', this.handleAulaChange);
            $(document).on('click', '.giorno-item', this.handleDaySelection);

            // Gestione slot CRUD
            $(document).on('click', '.edit-slot', this.handleEditSlot);
            $(document).on('click', '.toggle-slot', this.handleToggleSlot);
            $(document).on('click', '.delete-slot', this.handleDeleteSlot);

            // Filtri e ricerca
            $(document).on('change', '.filter-aule', this.handleAuleFilter);
            $(document).on('input', '.search-bookings', this.debounce(this.handleBookingSearch, 300));

            // Bulk actions
            $(document).on('change', '#cb-select-all', this.handleSelectAll);
            $(document).on('click', '.bulk-action-apply', this.handleBulkAction);

            // Export data
            $(document).on('click', '.export-data', this.handleDataExport);
        },

        /**
         * Inizializza componenti dell'interfaccia
         */
        initializeComponents: function() {
            // Inizializza tooltips
            this.initializeTooltips();

            // Inizializza datepicker
            this.initializeDatePickers();

            // Inizializza media uploader
            this.initializeMediaUploader();

            // Inizializza sortable lists
            this.initializeSortables();

            // Setup tab navigation
            this.setupTabs();
        },

        /**
         * Inizializza tooltip
         */
        initializeTooltips: function() {
            $('[data-tooltip]').each(function() {
                $(this).attr('title', $(this).data('tooltip'));
            });
        },

        /**
         * Inizializza datepicker
         */
        initializeDatePickers: function() {
            if ($.fn.datepicker) {
                $('.date-picker').datepicker({
                    dateFormat: 'dd/mm/yy',
                    firstDay: 1,
                    dayNamesMin: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
                    monthNames: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                                'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre']
                });
            }
        },

        /**
         * Inizializza media uploader WordPress
         */
        initializeMediaUploader: function() {
            var self = this;

            $('.upload-image-btn').on('click', function(e) {
                e.preventDefault();

                var frame = wp.media({
                    title: 'Seleziona Immagini Aula',
                    button: {
                        text: 'Usa queste immagini'
                    },
                    multiple: true,
                    library: {
                        type: 'image'
                    }
                });

                frame.on('select', function() {
                    var selection = frame.state().get('selection');
                    var $container = $('#images-preview');

                    selection.map(function(attachment) {
                        attachment = attachment.toJSON();
                        self.addImageToPreview(attachment, $container);
                    });
                });

                frame.open();
            });
        },

        /**
         * Aggiunge immagine all'anteprima
         */
        addImageToPreview: function(attachment, $container) {
            var imageHtml = `
                <div class="image-preview-item" data-id="${attachment.id}">
                    <img src="${attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url}" alt="${attachment.alt}">
                    <button type="button" class="remove-image" title="Rimuovi immagine">
                        <span class="wp-icon wp-icon-remove"></span>
                    </button>
                    <input type="hidden" name="immagini[]" value="${attachment.id}">
                </div>
            `;

            $container.append(imageHtml);
        },

        /**
         * Inizializza liste ordinabili
         */
        initializeSortables: function() {
            if ($.fn.sortable) {
                $('.sortable-list').sortable({
                    handle: '.sort-handle',
                    axis: 'y',
                    opacity: 0.7,
                    update: function(event, ui) {
                        AuleBookingAdmin.updateSortOrder($(this));
                    }
                });
            }
        },

        /**
         * Setup navigazione a tab
         */
        setupTabs: function() {
            $('.nav-tab-wrapper').on('click', '.nav-tab', function(e) {
                e.preventDefault();

                var $tab = $(this);
                var target = $tab.data('target');

                // Aggiorna tab attivi
                $tab.siblings().removeClass('nav-tab-active');
                $tab.addClass('nav-tab-active');

                // Mostra contenuto corrispondente
                $('.tab-content').hide();
                $(target).show();

                // Salva tab attivo
                localStorage.setItem('prenotazione_aule_ssm_active_tab', target);
            });

            // Ripristina tab attivo
            var activeTab = localStorage.getItem('prenotazione_aule_ssm_active_tab');
            if (activeTab && $(activeTab).length) {
                $(`[data-target="${activeTab}"]`).trigger('click');
            }
        },

        /**
         * Inizializza calendari admin
         */
        initializeCalendars: function() {
            var self = this;

            $('.admin-calendar').each(function() {
                var $calendar = $(this);
                var auleId = $calendar.data('aule-id');

                if (auleId) {
                    self.initializeAdminCalendar($calendar, auleId);
                }
            });
        },

        /**
         * Inizializza singolo calendario admin
         */
        initializeAdminCalendar: function($calendarEl, auleId) {
            var self = this;
            var calendar = new FullCalendar.Calendar($calendarEl[0], {
                locale: 'it',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                height: 'auto',
                eventSources: [{
                    url: self.config.ajaxUrl,
                    method: 'POST',
                    extraParams: {
                        action: 'aule_get_availability',
                        aula_id: auleId,
                        nonce: self.config.nonce
                    },
                    failure: function(error) {
                        console.error('Errore caricamento eventi:', error);
                    }
                }],
                eventClick: function(info) {
                    self.handleAdminEventClick(info);
                },
                dateClick: function(info) {
                    self.handleAdminDateClick(info, auleId);
                }
            });

            calendar.render();
            this.calendars[auleId] = calendar;
        },

        /**
         * Gestisce il click su evento del calendario admin
         */
        handleAdminEventClick: function(info) {
            var event = info.event;
            var bookingId = event.extendedProps.booking_id;

            if (bookingId) {
                this.showBookingDetailsModal(bookingId);
            }
        },

        /**
         * Gestisce il click su data del calendario admin
         */
        handleAdminDateClick: function(info, auleId) {
            // Possibilità di creare prenotazione manuale
            console.log('Click su data:', info.dateStr, 'Aula:', auleId);
        },

        /**
         * Setup validazione form
         */
        setupFormValidation: function() {
            // Validazione real-time
            $('form').on('input change', 'input, textarea, select', function() {
                AuleBookingAdmin.validateField($(this));
            });

            // Validazione all'invio
            $('form').on('submit', function(e) {
                if (!AuleBookingAdmin.validateForm($(this))) {
                    e.preventDefault();
                    return false;
                }
            });
        },

        /**
         * Valida singolo campo
         */
        validateField: function($field) {
            var isValid = true;
            var value = $field.val().trim();
            var fieldType = $field.attr('type') || $field.prop('tagName').toLowerCase();

            // Rimuovi classi di validazione precedenti
            $field.removeClass('error valid');

            // Validazioni specifiche
            switch (fieldType) {
                case 'email':
                    isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    break;

                case 'url':
                    isValid = !value || /^https?:\/\/.+/.test(value);
                    break;

                case 'number':
                    var min = $field.attr('min');
                    var max = $field.attr('max');
                    var num = parseFloat(value);

                    isValid = !isNaN(num);
                    if (isValid && min !== undefined) isValid = num >= parseFloat(min);
                    if (isValid && max !== undefined) isValid = num <= parseFloat(max);
                    break;

                default:
                    if ($field.prop('required')) {
                        isValid = value.length > 0;
                    }
            }

            // Applica classi di validazione
            $field.addClass(isValid ? 'valid' : 'error');

            return isValid;
        },

        /**
         * Valida form completo
         */
        validateForm: function($form) {
            var isValid = true;

            $form.find('input, textarea, select').each(function() {
                var fieldValid = AuleBookingAdmin.validateField($(this));
                isValid = isValid && fieldValid;
            });

            if (!isValid) {
                this.showNotice('Per favore correggi gli errori nel form', 'error');

                // Scroll al primo campo con errore
                var $firstError = $form.find('.error').first();
                if ($firstError.length) {
                    $('html, body').animate({
                        scrollTop: $firstError.offset().top - 100
                    }, 500);
                }
            }

            return isValid;
        },

        /**
         * GESTORI EVENTI
         */

        /**
         * Gestisce approvazione prenotazione
         */
        handleApproveBooking: function(e) {
            e.preventDefault();

            var $button = $(this);
            var bookingId = $button.data('id');

            if (!confirm(AuleBookingAdmin.config.strings.confirm_approve)) {
                return;
            }

            AuleBookingAdmin.updateBookingStatus(bookingId, 'approve', '');
        },

        /**
         * Gestisce rifiuto prenotazione
         */
        handleRejectBooking: function(e) {
            e.preventDefault();

            var $button = $(this);
            var bookingId = $button.data('id');

            var note = prompt('Inserisci il motivo del rifiuto:');
            if (!note || note.trim() === '') {
                alert('Il motivo del rifiuto è obbligatorio');
                return;
            }

            AuleBookingAdmin.updateBookingStatus(bookingId, 'reject', note);
        },

        /**
         * Gestisce cancellazione prenotazione
         */
        handleDeleteBooking: function(e) {
            e.preventDefault();

            var $button = $(this);
            var bookingId = $button.data('id');

            if (!confirm(AuleBookingAdmin.config.strings.confirm_delete)) {
                return;
            }

            AuleBookingAdmin.updateBookingStatus(bookingId, 'delete', '');
        },

        /**
         * Aggiorna stato prenotazione
         */
        updateBookingStatus: function(bookingId, action, note) {
            var self = this;

            var actionMap = {
                'approve': 'aule_approve_booking',
                'reject': 'aule_reject_booking',
                'delete': 'aule_delete_booking'
            };

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: actionMap[action],
                    booking_id: bookingId,
                    note_admin: note,
                    nonce: AuleBookingAdmin.config.nonce
                },
                beforeSend: function() {
                    self.showLoading();
                },
                success: function(response) {
                    self.hideLoading();

                    if (response.success) {
                        self.showNotice(response.data, 'success');

                        // Ricarica pagina dopo breve pausa
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        self.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    self.hideLoading();
                    self.showNotice(self.config.strings.error, 'error');
                }
            });
        },

        /**
         * Gestisce cancellazione aula
         */
        handleDeleteAula: function(e) {
            e.preventDefault();

            var $link = $(this);
            var aulaName = $link.data('aula-name') || 'questa aula';

            if (!confirm(`Sei sicuro di voler eliminare ${aulaName}? Tutte le prenotazioni associate verranno cancellate.`)) {
                return;
            }

            // Procedi con la cancellazione (il link ha già l'URL corretto)
            window.location.href = $link.attr('href');
        },

        /**
         * Gestisce upload immagini
         */
        handleImageUpload: function(e) {
            var files = e.target.files;
            var $preview = $('#images-preview');

            for (var i = 0; i < files.length; i++) {
                AuleBookingAdmin.previewImage(files[i], $preview);
            }
        },

        /**
         * Genera anteprima immagine
         */
        previewImage: function(file, $container) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var imageHtml = `
                    <div class="image-preview-item">
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-image" title="Rimuovi immagine">
                            <span class="wp-icon wp-icon-remove"></span>
                        </button>
                    </div>
                `;

                $container.append(imageHtml);
            };

            reader.readAsDataURL(file);
        },

        /**
         * Gestisce rimozione immagine
         */
        handleRemoveImage: function(e) {
            e.preventDefault();

            var $button = $(this);
            var $item = $button.closest('.image-preview-item');

            if (confirm('Rimuovere questa immagine?')) {
                $item.remove();
            }
        },

        /**
         * Gestisce generazione slot
         */
        handleGenerateSlots: function(e) {
            e.preventDefault();

            var $button = $(this);
            var $form = $button.closest('form');

            if (!AuleBookingAdmin.validateForm($form)) {
                return;
            }

            var formData = $form.serialize() + '&action=aule_generate_slots&nonce=' + AuleBookingAdmin.config.nonce;

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $button.prop('disabled', true).text('Generazione...');
                },
                success: function(response) {
                    $button.prop('disabled', false).text('Genera Slot');

                    if (response.success) {
                        AuleBookingAdmin.showNotice(response.data, 'success');

                        // Aggiorna lista slot
                        setTimeout(() => {
                            AuleBookingAdmin.loadSlotsList($form.find('[name="aula_id"]').val());
                        }, 1000);
                    } else {
                        AuleBookingAdmin.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    $button.prop('disabled', false).text('Genera Slot');
                    AuleBookingAdmin.showNotice(AuleBookingAdmin.config.strings.error, 'error');
                }
            });
        },

        /**
         * Gestisce cambio aula
         */
        handleAulaChange: function(e) {
            var aulaId = $(this).val();

            if (aulaId) {
                AuleBookingAdmin.loadSlotsList(aulaId);

                // Aggiorna calendario se presente
                if (AuleBookingAdmin.calendars[aulaId]) {
                    AuleBookingAdmin.calendars[aulaId].refetchEvents();
                }
            }
        },

        /**
         * Carica lista slot per aula
         */
        loadSlotsList: function(aulaId) {
            var $container = $('.slots-list');

            if (!$container.length || !aulaId) {
                return;
            }

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'aule_get_slots',
                    aula_id: aulaId,
                    nonce: AuleBookingAdmin.config.nonce
                },
                beforeSend: function() {
                    $container.html('<div class="loading-slots">Caricamento slot...</div>');
                },
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        var slotsHtml = '';

                        response.data.forEach(function(slot) {
                            slotsHtml += AuleBookingAdmin.buildSlotItem(slot);
                        });

                        $container.html(slotsHtml);
                    } else {
                        $container.html('<div class="no-slots">Nessun slot configurato per questa aula</div>');
                    }
                },
                error: function() {
                    $container.html('<div class="error-slots">Errore nel caricamento degli slot</div>');
                }
            });
        },

        /**
         * Costruisce HTML per item slot
         */
        buildSlotItem: function(slot) {
            var giorni = ['', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];

            return `
                <div class="slot-item">
                    <div class="slot-info">
                        ${giorni[slot.giorno_settimana]} ${slot.ora_inizio} - ${slot.ora_fine}
                        (${slot.durata_slot_minuti}min)
                    </div>
                    <div class="slot-actions">
                        <button class="button button-small edit-slot" data-id="${slot.id}">
                            <span class="wp-icon wp-icon-edit"></span>
                        </button>
                        <button class="button button-small delete-slot" data-id="${slot.id}">
                            <span class="wp-icon wp-icon-remove"></span>
                        </button>
                    </div>
                </div>
            `;
        },

        /**
         * Gestisce selezione giorni
         */
        handleDaySelection: function(e) {
            var $item = $(this);
            var $checkbox = $item.find('input[type="checkbox"]');

            $checkbox.prop('checked', !$checkbox.prop('checked'));
            $item.toggleClass('selected', $checkbox.is(':checked'));
        },

        /**
         * Gestisce filtro aule
         */
        handleAuleFilter: function(e) {
            var $select = $(this);
            var filterValue = $select.val();
            var filterType = $select.data('filter');

            // Applica filtro alla lista
            $('.aula-card').each(function() {
                var $card = $(this);
                var cardValue = $card.data(filterType);

                if (!filterValue || cardValue === filterValue) {
                    $card.show();
                } else {
                    $card.hide();
                }
            });

            // Aggiorna contatore
            this.updateAuleCount();
        },

        /**
         * Gestisce ricerca prenotazioni
         */
        handleBookingSearch: function(e) {
            var query = $(this).val().toLowerCase().trim();

            $('.booking-row').each(function() {
                var $row = $(this);
                var text = $row.text().toLowerCase();

                if (!query || text.includes(query)) {
                    $row.show();
                } else {
                    $row.hide();
                }
            });
        },

        /**
         * Gestisce selezione multipla
         */
        handleSelectAll: function(e) {
            var checked = $(this).is(':checked');

            $('tbody input[type="checkbox"]').prop('checked', checked);
        },

        /**
         * Gestisce azioni bulk
         */
        handleBulkAction: function(e) {
            e.preventDefault();

            var action = $('.bulk-actions select').val();
            var $checked = $('tbody input[type="checkbox"]:checked');

            if (!action) {
                alert('Seleziona un\'azione');
                return;
            }

            if ($checked.length === 0) {
                alert('Seleziona almeno un elemento');
                return;
            }

            if (!confirm(`Applicare l'azione "${action}" a ${$checked.length} elementi?`)) {
                return;
            }

            // Implementa azione bulk
            var ids = $checked.map(function() {
                return $(this).val();
            }).get();

            this.processBulkAction(action, ids);
        },

        /**
         * Processa azione bulk
         */
        processBulkAction: function(action, ids) {
            var self = this;

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'aule_bulk_action',
                    bulk_action: action,
                    ids: ids,
                    nonce: AuleBookingAdmin.config.nonce
                },
                beforeSend: function() {
                    self.showLoading();
                },
                success: function(response) {
                    self.hideLoading();

                    if (response.success) {
                        self.showNotice(response.data, 'success');

                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        self.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    self.hideLoading();
                    self.showNotice(self.config.strings.error, 'error');
                }
            });
        },

        /**
         * Gestisce export dati
         */
        handleDataExport: function(e) {
            e.preventDefault();

            var $button = $(this);
            var exportType = $button.data('export');
            var format = $button.data('format') || 'csv';

            // Crea form temporaneo per download
            var $form = $('<form>', {
                method: 'POST',
                action: AuleBookingAdmin.config.ajaxUrl
            });

            $form.append($('<input>', {
                type: 'hidden',
                name: 'action',
                value: 'aule_export_data'
            }));

            $form.append($('<input>', {
                type: 'hidden',
                name: 'export_type',
                value: exportType
            }));

            $form.append($('<input>', {
                type: 'hidden',
                name: 'format',
                value: format
            }));

            $form.append($('<input>', {
                type: 'hidden',
                name: 'nonce',
                value: AuleBookingAdmin.config.nonce
            }));

            $form.appendTo('body').submit().remove();
        },

        /**
         * UTILITY FUNCTIONS
         */

        /**
         * Mostra loading overlay
         */
        showLoading: function(message) {
            var loadingHtml = `
                <div class="loading-overlay">
                    <div class="loading-spinner">
                        <div class="wp-loading-spinner">⟳</div>
                        <p>${message || AuleBookingAdmin.config.strings.loading}</p>
                    </div>
                </div>
            `;

            if (!$('.loading-overlay').length) {
                $('body').append(loadingHtml);
            }
        },

        /**
         * Nascondi loading overlay
         */
        hideLoading: function() {
            $('.loading-overlay').remove();
        },

        /**
         * Mostra notifica
         */
        showNotice: function(message, type) {
            type = type || 'info';

            var noticeHtml = `
                <div class="notice notice-${type} is-dismissible">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            `;

            // Rimuovi notifiche esistenti
            $('.notice').remove();

            // Aggiungi nuova notifica
            $('.wrap h1').after(noticeHtml);

            // Auto dismiss dopo 5 secondi
            setTimeout(() => {
                $('.notice').fadeOut();
            }, 5000);

            // Scroll alla notifica
            $('html, body').animate({
                scrollTop: $('.notice').offset().top - 50
            }, 500);
        },

        /**
         * Aggiorna contatore aule visibili
         */
        updateAuleCount: function() {
            var visibleCount = $('.aula-card:visible').length;
            var totalCount = $('.aula-card').length;

            $('.aule-count').text(`Mostrando ${visibleCount} di ${totalCount} aule`);
        },

        /**
         * Aggiorna ordinamento lista
         */
        updateSortOrder: function($list) {
            var order = [];

            $list.find('[data-id]').each(function() {
                order.push($(this).data('id'));
            });

            // Salva nuovo ordinamento
            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'aule_update_sort_order',
                    order: order,
                    nonce: AuleBookingAdmin.config.nonce
                }
            });
        },

        /**
         * Mostra modal dettagli prenotazione
         */
        showBookingDetailsModal: function(bookingId) {
            // Implementa modal con dettagli prenotazione
            console.log('Mostra dettagli prenotazione:', bookingId);
        },

        /**
         * Debounce utility
         */
        debounce: function(func, wait) {
            var timeout;
            return function executedFunction() {
                var context = this;
                var args = arguments;

                var later = function() {
                    timeout = null;
                    func.apply(context, args);
                };

                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * GESTIONE SLOT CRUD
         */

        /**
         * Handler per modifica slot
         */
        handleEditSlot: function(e) {
            e.preventDefault();
            var slotId = $(this).data('id');

            // Carica dati slot via AJAX
            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'aule_get_slot',
                    slot_id: slotId,
                    nonce: AuleBookingAdmin.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var slot = response.data;

                        // Popola modal con dati slot
                        $('#edit_slot_id').val(slot.id);
                        $('#edit_ora_inizio').val(slot.ora_inizio.substring(0, 5)); // HH:MM
                        $('#edit_ora_fine').val(slot.ora_fine.substring(0, 5)); // HH:MM
                        $('#edit_data_inizio').val(slot.data_inizio_validita);
                        $('#edit_data_fine').val(slot.data_fine_validita || '');

                        // Mostra modal
                        var modal = new bootstrap.Modal(document.getElementById('editSlotModal'));
                        modal.show();
                    } else {
                        AuleBookingAdmin.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    AuleBookingAdmin.showNotice('Errore nel caricamento dei dati dello slot', 'error');
                }
            });
        },

        /**
         * Handler per toggle stato slot
         */
        handleToggleSlot: function(e) {
            e.preventDefault();
            var $button = $(this);
            var slotId = $button.data('id');
            var status = $button.data('status'); // 'enable' or 'disable'

            // Imposta messaggio nel modal
            var message = status === 'disable' ?
                'Vuoi disabilitare questo slot? Non sarà più disponibile per le prenotazioni.' :
                'Vuoi abilitare questo slot? Diventerà nuovamente disponibile per le prenotazioni.';
            $('#toggleSlotMessage').text(message);

            // Salva dati nel modal per conferma
            $('#confirmToggleBtn').data('slot-id', slotId);
            $('#confirmToggleBtn').data('status', status);
            $('#confirmToggleBtn').data('button', $button);

            // Mostra modal
            var modal = new bootstrap.Modal(document.getElementById('toggleSlotModal'));
            modal.show();
        },

        /**
         * Handler per eliminazione slot
         */
        handleDeleteSlot: function(e) {
            e.preventDefault();
            var $button = $(this);
            var slotId = $button.data('id');

            // Salva dati nel modal per conferma
            $('#confirmDeleteBtn').data('slot-id', slotId);
            $('#confirmDeleteBtn').data('button', $button);

            // Mostra modal
            var modal = new bootstrap.Modal(document.getElementById('deleteSlotModal'));
            modal.show();
        }
    };

    /**
     * Inizializzazione quando il DOM è pronto
     */
    $(document).ready(function() {
        AuleBookingAdmin.init();

        // Handler per salvataggio slot edit
        $(document).on('click', '#saveSlotBtn', function() {
            var formData = {
                action: 'aule_update_slot',
                slot_id: $('#edit_slot_id').val(),
                ora_inizio: $('#edit_ora_inizio').val() + ':00',
                ora_fine: $('#edit_ora_fine').val() + ':00',
                data_inizio: $('#edit_data_inizio').val(),
                data_fine: $('#edit_data_fine').val(),
                nonce: AuleBookingAdmin.config.nonce
            };

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#saveSlotBtn').prop('disabled', true).text('Salvataggio...');
                },
                success: function(response) {
                    $('#saveSlotBtn').prop('disabled', false).text('Salva Modifiche');

                    if (response.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editSlotModal')).hide();
                        AuleBookingAdmin.showNotice(response.data, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        AuleBookingAdmin.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    $('#saveSlotBtn').prop('disabled', false).text('Salva Modifiche');
                    AuleBookingAdmin.showNotice('Errore nel salvataggio dello slot', 'error');
                }
            });
        });

        // Handler per conferma toggle slot
        $(document).on('click', '#confirmToggleBtn', function() {
            var $btn = $(this);
            var slotId = $btn.data('slot-id');
            var status = $btn.data('status');
            var $originalButton = $btn.data('button');

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'aule_toggle_slot',
                    slot_id: slotId,
                    status: status,
                    nonce: AuleBookingAdmin.config.nonce
                },
                beforeSend: function() {
                    $btn.prop('disabled', true);
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    bootstrap.Modal.getInstance(document.getElementById('toggleSlotModal')).hide();

                    if (response.success) {
                        AuleBookingAdmin.showNotice(response.data, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        AuleBookingAdmin.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    bootstrap.Modal.getInstance(document.getElementById('toggleSlotModal')).hide();
                    AuleBookingAdmin.showNotice('Errore nell\'operazione', 'error');
                }
            });
        });

        // Handler per conferma eliminazione slot
        $(document).on('click', '#confirmDeleteBtn', function() {
            var $btn = $(this);
            var slotId = $btn.data('slot-id');
            var $originalButton = $btn.data('button');

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'aule_delete_slot',
                    slot_id: slotId,
                    nonce: AuleBookingAdmin.config.nonce
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).find('.dashicons').removeClass('dashicons-trash').addClass('dashicons-update');
                },
                success: function(response) {
                    $btn.prop('disabled', false).find('.dashicons').removeClass('dashicons-update').addClass('dashicons-trash');
                    bootstrap.Modal.getInstance(document.getElementById('deleteSlotModal')).hide();

                    if (response.success) {
                        AuleBookingAdmin.showNotice(response.data, 'success');
                        // Rimuovi elemento con animazione
                        $originalButton.closest('.slot-item').fadeOut(300, function() {
                            $(this).remove();
                            // Aggiorna conteggio
                            var $dayGroup = $originalButton.closest('.day-slots-group');
                            var remainingSlots = $dayGroup.find('.slot-item').length;
                            if (remainingSlots === 0) {
                                $dayGroup.fadeOut(300, function() { $(this).remove(); });
                            } else {
                                $dayGroup.find('.slots-count').text('(' + remainingSlots + ' slot)');
                            }
                        });
                    } else {
                        AuleBookingAdmin.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).find('.dashicons').removeClass('dashicons-update').addClass('dashicons-trash');
                    bootstrap.Modal.getInstance(document.getElementById('deleteSlotModal')).hide();
                    AuleBookingAdmin.showNotice('Errore nell\'eliminazione', 'error');
                }
            });
        });

        // Handler per conferma bulk action
        $(document).on('click', '#confirmBulkBtn', function() {
            var $btn = $(this);
            var action = $btn.data('action');
            var slotIds = $btn.data('slot-ids');

            $.ajax({
                url: AuleBookingAdmin.config.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'aule_bulk_slots',
                    bulk_action: action,
                    slot_ids: slotIds,
                    nonce: AuleBookingAdmin.config.nonce
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).text('Elaborazione...');
                },
                success: function(response) {
                    $btn.prop('disabled', false).text('Conferma');
                    bootstrap.Modal.getInstance(document.getElementById('bulkActionModal')).hide();

                    if (response.success) {
                        AuleBookingAdmin.showNotice(response.data, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        AuleBookingAdmin.showNotice(response.data, 'error');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).text('Conferma');
                    bootstrap.Modal.getInstance(document.getElementById('bulkActionModal')).hide();
                    AuleBookingAdmin.showNotice('Errore nell\'operazione', 'error');
                }
            });
        });
    });

    /**
     * Gestione notice dismiss
     */
    $(document).on('click', '.notice-dismiss', function() {
        $(this).closest('.notice').fadeOut();
    });

    /**
     * Esporta per uso esterno
     */
    window.AuleBookingAdmin = AuleBookingAdmin;

})(jQuery);