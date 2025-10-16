/**
 * Sistema di Debug e Logging per Prenotazione Aule SSM
 * Versione 2.1.5
 *
 * Questo file aggiunge logging dettagliato per troubleshooting
 */

(function($) {
    'use strict';

    // Namespace per il debug
    window.PrenotazioneAuleDebug = {
        enabled: true, // Imposta a false in produzione
        logs: [],
        errors: [],
        ajaxCalls: [],
        cspViolations: [],

        /**
         * Log generico
         */
        log: function(message, data, level) {
            level = level || 'info';

            var logEntry = {
                timestamp: new Date().toISOString(),
                level: level,
                message: message,
                data: data || null,
                stack: new Error().stack
            };

            this.logs.push(logEntry);

            if (this.enabled) {
                var emoji = {
                    'info': 'ℹ️',
                    'success': '✅',
                    'warning': '⚠️',
                    'error': '❌',
                    'ajax': '🔄'
                }[level] || 'ℹ️';

                console.log(
                    '%c[Prenotazione Aule] ' + emoji + ' ' + message,
                    'color: ' + {
                        'info': '#2196F3',
                        'success': '#4CAF50',
                        'warning': '#FF9800',
                        'error': '#F44336',
                        'ajax': '#9C27B0'
                    }[level] || '#2196F3',
                    data || ''
                );
            }

            // Mantieni solo ultimi 100 log
            if (this.logs.length > 100) {
                this.logs.shift();
            }
        },

        /**
         * Log AJAX call
         */
        logAjax: function(action, requestData, phase) {
            var ajaxEntry = {
                timestamp: new Date().toISOString(),
                action: action,
                phase: phase, // 'start', 'success', 'error'
                data: requestData
            };

            this.ajaxCalls.push(ajaxEntry);

            this.log(
                'AJAX ' + phase + ': ' + action,
                requestData,
                phase === 'error' ? 'error' : 'ajax'
            );
        },

        /**
         * Log errore
         */
        logError: function(message, error) {
            var errorEntry = {
                timestamp: new Date().toISOString(),
                message: message,
                error: error ? {
                    message: error.message,
                    stack: error.stack,
                    name: error.name
                } : null
            };

            this.errors.push(errorEntry);
            this.log(message, error, 'error');

            // Invia a console.error per catturare nello stack trace
            if (error) {
                console.error('[Prenotazione Aule] Error:', error);
            }
        },

        /**
         * Log CSP violation
         */
        logCSPViolation: function(violation) {
            this.cspViolations.push({
                timestamp: new Date().toISOString(),
                violation: violation
            });

            this.log(
                'CSP Violation: ' + violation['violated-directive'],
                violation,
                'error'
            );
        },

        /**
         * Esporta log per supporto
         */
        exportLogs: function() {
            var exportData = {
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent,
                url: window.location.href,
                logs: this.logs,
                errors: this.errors,
                ajaxCalls: this.ajaxCalls,
                cspViolations: this.cspViolations
            };

            var blob = new Blob(
                [JSON.stringify(exportData, null, 2)],
                {type: 'application/json'}
            );

            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'prenotazione-aule-debug-' + Date.now() + '.json';
            link.click();

            console.log('✅ Log esportati!', exportData);
            return exportData;
        },

        /**
         * Stampa riepilogo in console
         */
        printSummary: function() {
            console.group('📊 Prenotazione Aule - Debug Summary');
            console.log('Total Logs:', this.logs.length);
            console.log('Total Errors:', this.errors.length);
            console.log('Total AJAX Calls:', this.ajaxCalls.length);
            console.log('CSP Violations:', this.cspViolations.length);

            if (this.errors.length > 0) {
                console.group('❌ Recent Errors');
                this.errors.slice(-5).forEach(function(err) {
                    console.error(err.timestamp, err.message, err.error);
                });
                console.groupEnd();
            }

            if (this.cspViolations.length > 0) {
                console.group('🚫 CSP Violations');
                this.cspViolations.forEach(function(v) {
                    console.warn(v.timestamp, v.violation);
                });
                console.groupEnd();
            }

            console.groupEnd();
        }
    };

    // Alias
    var Debug = window.PrenotazioneAuleDebug;

    /**
     * Inizializzazione al caricamento DOM
     */
    $(document).ready(function() {
        Debug.log('Plugin inizializzato', {
            jQuery: !!window.jQuery,
            jQueryVersion: $.fn.jquery,
            ajaxUrl: typeof prenotazione_aule_ssm_ajax !== 'undefined' ? prenotazione_aule_ssm_ajax.ajax_url : 'UNDEFINED'
        }, 'success');

        // Verifica oggetto AJAX globale
        if (typeof prenotazione_aule_ssm_ajax === 'undefined') {
            Debug.logError('CRITICAL: prenotazione_aule_ssm_ajax non definito!');
        } else {
            Debug.log('AJAX Config loaded', {
                ajaxUrl: prenotazione_aule_ssm_ajax.ajax_url,
                hasNonce: !!prenotazione_aule_ssm_ajax.nonce,
                hasSettings: !!prenotazione_aule_ssm_ajax.settings
            }, 'info');
        }
    });

    /**
     * Intercetta tutte le chiamate AJAX jQuery
     */
    $(document).ajaxSend(function(event, jqxhr, settings) {
        // Estrai action se presente
        var action = 'unknown';
        if (settings.data) {
            var match = settings.data.match(/action=([^&]+)/);
            if (match) action = match[1];
        }

        Debug.logAjax(action, {
            url: settings.url,
            type: settings.type,
            data: settings.data,
            dataType: settings.dataType
        }, 'start');
    });

    $(document).ajaxSuccess(function(event, jqxhr, settings, data) {
        var action = 'unknown';
        if (settings.data) {
            var match = settings.data.match(/action=([^&]+)/);
            if (match) action = match[1];
        }

        Debug.logAjax(action, {
            status: jqxhr.status,
            response: data
        }, 'success');
    });

    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        var action = 'unknown';
        if (settings.data) {
            var match = settings.data.match(/action=([^&]+)/);
            if (match) action = match[1];
        }

        Debug.logAjax(action, {
            status: jqxhr.status,
            statusText: jqxhr.statusText,
            error: thrownError,
            responseText: jqxhr.responseText
        }, 'error');
    });

    /**
     * Cattura errori JavaScript globali
     */
    window.addEventListener('error', function(event) {
        Debug.logError('JavaScript Error', {
            message: event.message,
            filename: event.filename,
            lineno: event.lineno,
            colno: event.colno,
            error: event.error
        });
    });

    /**
     * Cattura promise rejections
     */
    window.addEventListener('unhandledrejection', function(event) {
        Debug.logError('Unhandled Promise Rejection', {
            reason: event.reason
        });
    });

    /**
     * Cattura CSP violations
     */
    document.addEventListener('securitypolicyviolation', function(event) {
        Debug.logCSPViolation({
            'blocked-uri': event.blockedURI,
            'violated-directive': event.violatedDirective,
            'effective-directive': event.effectiveDirective,
            'original-policy': event.originalPolicy,
            'disposition': event.disposition,
            'source-file': event.sourceFile,
            'line-number': event.lineNumber
        });
    });

    /**
     * Comandi console per debug
     */
    window.exportPrenotazioneLogs = function() {
        return Debug.exportLogs();
    };

    window.prenotazioneDebugSummary = function() {
        Debug.printSummary();
    };

    // Auto-print summary dopo 5 secondi
    setTimeout(function() {
        if (Debug.errors.length > 0 || Debug.cspViolations.length > 0) {
            console.warn('⚠️ Rilevati errori! Usa prenotazioneDebugSummary() per dettagli');
        }
    }, 5000);

})(jQuery);
