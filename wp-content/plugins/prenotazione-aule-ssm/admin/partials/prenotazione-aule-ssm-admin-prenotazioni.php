<?php

/**
 * Template per la pagina "Prenotazioni"
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/admin/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Ottieni parametri di filtro dalla query string
$filter_stato = !empty($_GET['stato']) ? sanitize_text_field($_GET['stato']) : '';
$filter_aula_id = !empty($_GET['aula_id']) ? absint($_GET['aula_id']) : 0;
$filter_data_da = !empty($_GET['data_da']) ? sanitize_text_field($_GET['data_da']) : '';
$filter_data_a = !empty($_GET['data_a']) ? sanitize_text_field($_GET['data_a']) : '';
$search_term = !empty($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// Statistiche rapide
$database = new Prenotazione_Aule_SSM_Database();
$stats = array(
    'totali' => count($prenotazioni),
    'in_attesa' => 0,
    'confermate' => 0,
    'rifiutate' => 0,
    'oggi' => 0
);

$oggi = current_time('Y-m-d');
foreach ($prenotazioni as $prenotazione) {
    $stats[$prenotazione->stato]++;
    if ($prenotazione->data_prenotazione === $oggi) {
        $stats['oggi']++;
    }
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        📅 <?php _e('Gestione Prenotazioni', 'prenotazione-aule-ssm'); ?>
    </h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                switch ($_GET['updated']) {
                    case 'approved':
                        _e('Prenotazione approvata correttamente.', 'prenotazione-aule-ssm');
                        break;
                    case 'rejected':
                        _e('Prenotazione rifiutata correttamente.', 'prenotazione-aule-ssm');
                        break;
                    case 'deleted':
                        _e('Prenotazione eliminata correttamente.', 'prenotazione-aule-ssm');
                        break;
                    default:
                        _e('Operazione completata.', 'prenotazione-aule-ssm');
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Si è verificato un errore durante l\'operazione.', 'prenotazione-aule-ssm'); ?></p>
        </div>
    <?php endif; ?>

    <!-- Statistiche rapide -->
    <div class="prenotazioni-stats-grid">
        <div class="stat-card total">
            <div class="stat-number"><?php echo $stats['totali']; ?></div>
            <div class="stat-label"><?php _e('Totali', 'prenotazione-aule-ssm'); ?></div>
        </div>
        <div class="stat-card pending">
            <div class="stat-number"><?php echo $stats['in_attesa']; ?></div>
            <div class="stat-label"><?php _e('In Attesa', 'prenotazione-aule-ssm'); ?></div>
        </div>
        <div class="stat-card confirmed">
            <div class="stat-number"><?php echo $stats['confermate']; ?></div>
            <div class="stat-label"><?php _e('Confermate', 'prenotazione-aule-ssm'); ?></div>
        </div>
        <div class="stat-card rejected">
            <div class="stat-number"><?php echo $stats['rifiutate']; ?></div>
            <div class="stat-label"><?php _e('Rifiutate', 'prenotazione-aule-ssm'); ?></div>
        </div>
        <div class="stat-card today">
            <div class="stat-number"><?php echo $stats['oggi']; ?></div>
            <div class="stat-label"><?php _e('Oggi', 'prenotazione-aule-ssm'); ?></div>
        </div>
    </div>

    <div class="prenotazioni-container">
        <!-- Filtri e Ricerca -->
        <div class="prenotazioni-filters">
            <form method="get" class="prenotazioni-filter-form">
                <input type="hidden" name="page" value="prenotazione-aule-ssm-prenotazioni">

                <select name="stato" class="filter-prenotazioni">
                    <option value=""><?php _e('Tutti gli stati', 'prenotazione-aule-ssm'); ?></option>
                    <option value="in_attesa" <?php selected($filter_stato, 'in_attesa'); ?>><?php _e('In Attesa', 'prenotazione-aule-ssm'); ?></option>
                    <option value="confermata" <?php selected($filter_stato, 'confermata'); ?>><?php _e('Confermate', 'prenotazione-aule-ssm'); ?></option>
                    <option value="rifiutata" <?php selected($filter_stato, 'rifiutata'); ?>><?php _e('Rifiutate', 'prenotazione-aule-ssm'); ?></option>
                    <option value="cancellata" <?php selected($filter_stato, 'cancellata'); ?>><?php _e('Cancellate', 'prenotazione-aule-ssm'); ?></option>
                </select>

                <select name="aula_id" class="filter-prenotazioni">
                    <option value=""><?php _e('Tutte le aule', 'prenotazione-aule-ssm'); ?></option>
                    <?php foreach ($aule as $aula): ?>
                        <option value="<?php echo $aula->id; ?>" <?php selected($filter_aula_id, $aula->id); ?>>
                            <?php echo esc_html($aula->nome_aula); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="date"
                       name="data_da"
                       value="<?php echo esc_attr($filter_data_da); ?>"
                       placeholder="<?php _e('Data da', 'prenotazione-aule-ssm'); ?>">

                <input type="date"
                       name="data_a"
                       value="<?php echo esc_attr($filter_data_a); ?>"
                       placeholder="<?php _e('Data a', 'prenotazione-aule-ssm'); ?>">

                <input type="search"
                       name="search"
                       value="<?php echo esc_attr($search_term); ?>"
                       placeholder="<?php _e('Cerca per nome, email, codice...', 'prenotazione-aule-ssm'); ?>">

                <button type="submit" class="button"><?php _e('Filtra', 'prenotazione-aule-ssm'); ?></button>

                <?php if ($filter_stato || $filter_aula_id || $filter_data_da || $filter_data_a || $search_term): ?>
                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-prenotazioni'); ?>" class="button">
                    <?php _e('Reset', 'prenotazione-aule-ssm'); ?>
                </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (empty($prenotazioni)): ?>
            <!-- Stato vuoto -->
            <div class="no-data">
                📅
                <h3><?php _e('Nessuna prenotazione trovata', 'prenotazione-aule-ssm'); ?></h3>
                <p><?php _e('Non ci sono prenotazioni che corrispondono ai criteri di ricerca.', 'prenotazione-aule-ssm'); ?></p>
            </div>
        <?php else: ?>
            <!-- Contatore risultati -->
            <div class="prenotazioni-results-info">
                <span class="prenotazioni-count">
                    <?php printf(__('Mostrando %d prenotazioni', 'prenotazione-aule-ssm'), count($prenotazioni)); ?>
                </span>
            </div>

            <!-- Lista Prenotazioni -->
            <div class="prenotazioni-table-container">
                <table class="wp-list-table widefat fixed striped prenotazioni-table">
                    <thead>
                        <tr>
                            <th class="column-codice"><?php _e('Codice', 'prenotazione-aule-ssm'); ?></th>
                            <th class="column-richiedente"><?php _e('Richiedente', 'prenotazione-aule-ssm'); ?></th>
                            <th class="column-aula"><?php _e('Aula', 'prenotazione-aule-ssm'); ?></th>
                            <th class="column-data"><?php _e('Data e Ora', 'prenotazione-aule-ssm'); ?></th>
                            <th class="column-stato"><?php _e('Stato', 'prenotazione-aule-ssm'); ?></th>
                            <th class="column-azioni"><?php _e('Azioni', 'prenotazione-aule-ssm'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prenotazioni as $prenotazione): ?>
                            <tr class="prenotazione-row" data-id="<?php echo $prenotazione->id; ?>">
                                <td class="column-codice">
                                    <strong><?php echo esc_html($prenotazione->codice_prenotazione ?: '#' . $prenotazione->id); ?></strong>
                                    <div class="row-actions">
                                        <span class="created">
                                            <?php printf(__('Creata: %s', 'prenotazione-aule-ssm'),
                                                  date_i18n('d/m/Y H:i', strtotime($prenotazione->created_at))); ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="column-richiedente">
                                    <strong>
                                        <?php echo esc_html($prenotazione->nome_richiedente . ' ' . $prenotazione->cognome_richiedente); ?>
                                    </strong>
                                    <div class="richiedente-meta">
                                        <a href="mailto:<?php echo esc_attr($prenotazione->email_richiedente); ?>">
                                            <?php echo esc_html($prenotazione->email_richiedente); ?>
                                        </a>
                                    </div>
                                    <?php if (!empty($prenotazione->motivo_prenotazione)): ?>
                                    <div class="motivo-prenotazione">
                                        <em><?php echo esc_html(wp_trim_words($prenotazione->motivo_prenotazione, 8)); ?></em>
                                    </div>
                                    <?php endif; ?>
                                </td>

                                <td class="column-aula">
                                    <strong><?php echo esc_html($prenotazione->nome_aula); ?></strong>
                                    <?php if (!empty($prenotazione->ubicazione)): ?>
                                    <div class="aula-ubicazione">
                                        <span class="dashicons dashicons-location"></span>
                                        <?php echo esc_html($prenotazione->ubicazione); ?>
                                    </div>
                                    <?php endif; ?>
                                </td>

                                <td class="column-data">
                                    <div class="data-prenotazione">
                                        <strong><?php echo date_i18n('d/m/Y', strtotime($prenotazione->data_prenotazione)); ?></strong>
                                    </div>
                                    <div class="orario-prenotazione">
                                        🕐 <?php echo date('H:i', strtotime($prenotazione->ora_inizio)); ?> -
                                        <?php echo date('H:i', strtotime($prenotazione->ora_fine)); ?>
                                        <?php
                                        $durata = (strtotime($prenotazione->ora_fine) - strtotime($prenotazione->ora_inizio)) / 60;
                                        printf('(%d min)', $durata);
                                        ?>
                                    </div>
                                </td>

                                <td class="column-stato">
                                    <span class="booking-status booking-status-<?php echo esc_attr($prenotazione->stato); ?>">
                                        <?php
                                        switch ($prenotazione->stato) {
                                            case 'in_attesa':
                                                echo '⏳ ' . __('In Attesa', 'prenotazione-aule-ssm');
                                                break;
                                            case 'confermata':
                                                echo '✅ ' . __('Confermata', 'prenotazione-aule-ssm');
                                                break;
                                            case 'rifiutata':
                                                echo '❌ ' . __('Rifiutata', 'prenotazione-aule-ssm');
                                                break;
                                            case 'cancellata':
                                                echo '🚫 ' . __('Cancellata', 'prenotazione-aule-ssm');
                                                break;
                                        }
                                        ?>
                                    </span>

                                    <?php if (!empty($prenotazione->note_admin)): ?>
                                    <div class="note-admin" title="<?php echo esc_attr($prenotazione->note_admin); ?>">
                                        💬 <em><?php echo esc_html(wp_trim_words($prenotazione->note_admin, 5)); ?></em>
                                    </div>
                                    <?php endif; ?>
                                </td>

                                <td class="column-azioni">
                                    <div class="booking-actions">
                                        <?php if ($prenotazione->stato === 'in_attesa'): ?>
                                            <button class="button button-small approve-booking"
                                                    data-id="<?php echo $prenotazione->id; ?>"
                                                    title="<?php _e('Approva prenotazione', 'prenotazione-aule-ssm'); ?>">
                                                ✅ <?php _e('Approva', 'prenotazione-aule-ssm'); ?>
                                            </button>

                                            <button class="button button-small reject-booking"
                                                    data-id="<?php echo $prenotazione->id; ?>"
                                                    title="<?php _e('Rifiuta prenotazione', 'prenotazione-aule-ssm'); ?>">
                                                ❌ <?php _e('Rifiuta', 'prenotazione-aule-ssm'); ?>
                                            </button>
                                        <?php endif; ?>

                                        <button class="button button-small view-details"
                                                data-id="<?php echo $prenotazione->id; ?>"
                                                title="<?php _e('Visualizza dettagli', 'prenotazione-aule-ssm'); ?>">
                                            👁️ <?php _e('Dettagli', 'prenotazione-aule-ssm'); ?>
                                        </button>

                                        <?php if (in_array($prenotazione->stato, ['rifiutata', 'cancellata'])): ?>
                                        <button class="button button-small delete-booking"
                                                data-id="<?php echo $prenotazione->id; ?>"
                                                title="<?php _e('Elimina prenotazione', 'prenotazione-aule-ssm'); ?>">
                                            🗑️ <?php _e('Elimina', 'prenotazione-aule-ssm'); ?>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal per dettagli prenotazione -->
<div id="booking-details-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><?php _e('Dettagli Prenotazione', 'prenotazione-aule-ssm'); ?></h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <!-- Contenuto caricato via AJAX -->
        </div>
    </div>
</div>

<!-- Modal per rifiuto prenotazione -->
<div id="reject-booking-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><?php _e('Rifiuta Prenotazione', 'prenotazione-aule-ssm'); ?></h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p><?php _e('Inserisci il motivo del rifiuto:', 'prenotazione-aule-ssm'); ?></p>
            <textarea id="reject-reason" rows="4" style="width: 100%;"
                      placeholder="<?php _e('Es: Aula non disponibile per manutenzione', 'prenotazione-aule-ssm'); ?>"></textarea>
            <div class="modal-actions">
                <button class="button button-primary confirm-reject"><?php _e('Conferma Rifiuto', 'prenotazione-aule-ssm'); ?></button>
                <button class="button cancel-reject"><?php _e('Annulla', 'prenotazione-aule-ssm'); ?></button>
            </div>
        </div>
    </div>
</div>

<style>
/* Stili specifici per gestione prenotazioni */
.prenotazioni-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.stat-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    border-left: 4px solid #ccc;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card.total { border-left-color: #2271b1; }
.stat-card.pending { border-left-color: #dba617; }
.stat-card.confirmed { border-left-color: #00a32a; }
.stat-card.rejected { border-left-color: #d63638; }
.stat-card.today { border-left-color: #8c8f94; }

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #1d2327;
}

.stat-label {
    font-size: 12px;
    color: #646970;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 5px;
}

.prenotazioni-filters {
    background: #f9f9f9;
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
}

.prenotazioni-filter-form {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.prenotazioni-filter-form select,
.prenotazioni-filter-form input {
    padding: 6px 10px;
    border: 1px solid #ccd0d4;
    border-radius: 3px;
    font-size: 13px;
}

.prenotazioni-filter-form input[type="search"] {
    min-width: 250px;
}

.prenotazioni-results-info {
    padding: 10px 0;
    font-size: 14px;
    color: #646970;
    border-bottom: 1px solid #e0e0e0;
    margin-bottom: 15px;
}

.prenotazioni-table-container {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    overflow-x: auto;
}

.prenotazioni-table th {
    background: #f9f9f9;
    font-weight: 600;
    padding: 12px 8px;
    border-bottom: 2px solid #e0e0e0;
}

.prenotazioni-table td {
    padding: 12px 8px;
    vertical-align: top;
}

.prenotazione-row:hover {
    background-color: #f8f9fa;
}

.richiedente-meta {
    font-size: 12px;
    color: #646970;
    margin-top: 2px;
}

.motivo-prenotazione {
    font-size: 11px;
    color: #8c8f94;
    margin-top: 4px;
    max-width: 200px;
}

.aula-ubicazione {
    font-size: 11px;
    color: #646970;
    margin-top: 2px;
}

.data-prenotazione {
    font-weight: 600;
}

.orario-prenotazione {
    font-size: 12px;
    color: #646970;
    margin-top: 2px;
}

.note-admin {
    font-size: 11px;
    color: #8c8f94;
    margin-top: 4px;
    cursor: help;
}

.booking-actions {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.booking-actions .button {
    font-size: 11px;
    padding: 4px 8px;
    white-space: nowrap;
}

/* Modal styles */
#booking-details-modal,
#reject-booking-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 8px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    background: #f9f9f9;
    border-radius: 8px 8px 0 0;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.modal-body {
    padding: 20px;
}

.modal-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* Responsive */
@media (max-width: 768px) {
    .prenotazioni-filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .prenotazioni-filter-form select,
    .prenotazioni-filter-form input {
        width: 100%;
        margin-bottom: 10px;
    }

    .prenotazioni-table {
        font-size: 12px;
    }

    .prenotazioni-table th,
    .prenotazioni-table td {
        padding: 8px 4px;
    }

    .booking-actions {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .booking-actions .button {
        flex: 1;
        min-width: 60px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Auto-submit filtri
    $('.filter-prenotazioni').on('change', function() {
        $(this).closest('form').submit();
    });

    // Gestione approvazione
    $('.approve-booking').on('click', function() {
        var bookingId = $(this).data('id');

        if (!confirm('<?php esc_js(_e('Confermare questa prenotazione?', 'prenotazione-aule-ssm')); ?>')) {
            return;
        }

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'aule_approve_booking',
                booking_id: bookingId,
                note_admin: '',
                nonce: '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Errore: ' + response.data);
                }
            },
            error: function() {
                alert('<?php esc_js(_e('Errore di comunicazione', 'prenotazione-aule-ssm')); ?>');
            }
        });
    });

    // Gestione rifiuto con modal
    var currentRejectId = null;

    $('.reject-booking').on('click', function() {
        currentRejectId = $(this).data('id');
        $('#reject-booking-modal').show();
        $('#reject-reason').focus();
    });

    $('.confirm-reject').on('click', function() {
        var reason = $('#reject-reason').val().trim();

        if (!reason) {
            alert('<?php esc_js(_e('Il motivo del rifiuto è obbligatorio', 'prenotazione-aule-ssm')); ?>');
            return;
        }

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'aule_reject_booking',
                booking_id: currentRejectId,
                note_admin: reason,
                nonce: '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Errore: ' + response.data);
                }
            },
            error: function() {
                alert('<?php esc_js(_e('Errore di comunicazione', 'prenotazione-aule-ssm')); ?>');
            }
        });
    });

    // Chiusura modali
    $('.modal-close, .cancel-reject').on('click', function() {
        $('#booking-details-modal, #reject-booking-modal').hide();
        $('#reject-reason').val('');
        currentRejectId = null;
    });

    // Chiudi modal cliccando fuori
    $('#booking-details-modal, #reject-booking-modal').on('click', function(e) {
        if (e.target === this) {
            $(this).hide();
            $('#reject-reason').val('');
            currentRejectId = null;
        }
    });

    // Visualizza dettagli (placeholder)
    $('.view-details').on('click', function() {
        var bookingId = $(this).data('id');
        alert('Funzione dettagli per prenotazione ID: ' + bookingId + '\n(Da implementare con caricamento AJAX)');
    });

    // Elimina prenotazione
    $('.delete-booking').on('click', function() {
        var bookingId = $(this).data('id');

        if (!confirm('<?php esc_js(_e('Eliminare definitivamente questa prenotazione?', 'prenotazione-aule-ssm')); ?>')) {
            return;
        }

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'aule_delete_booking',
                booking_id: bookingId,
                nonce: '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Errore: ' + response.data);
                }
            },
            error: function() {
                alert('<?php esc_js(_e('Errore di comunicazione', 'prenotazione-aule-ssm')); ?>');
            }
        });
    });
});
</script>