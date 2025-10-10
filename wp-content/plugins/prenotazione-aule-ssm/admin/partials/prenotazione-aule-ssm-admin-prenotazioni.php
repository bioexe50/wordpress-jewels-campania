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
    // Mappa lo stato singolare al contatore plurale
    $stato_key = $prenotazione->stato;
    if ($stato_key === 'confermata') {
        $stato_key = 'confermate';
    } elseif ($stato_key === 'rifiutata') {
        $stato_key = 'rifiutate';
    }

    if (isset($stats[$stato_key])) {
        $stats[$stato_key]++;
    }

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

<!-- Modal Conferma Approvazione -->
<div class="modal fade" id="approveBookingModal" tabindex="-1" aria-labelledby="approveBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveBookingModalLabel">
                    <span class="dashicons dashicons-yes-alt" style="color: white;"></span>
                    <?php _e('Conferma Approvazione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php _e('Sei sicuro di voler approvare questa prenotazione?', 'prenotazione-aule-ssm'); ?></p>
                <div class="alert alert-info">
                    <strong><?php _e('Nota:', 'prenotazione-aule-ssm'); ?></strong>
                    <?php _e('Verrà inviata un\'email di conferma al richiedente.', 'prenotazione-aule-ssm'); ?>
                </div>
                <div class="mb-3">
                    <label for="approve-note" class="form-label"><?php _e('Note aggiuntive (opzionale):', 'prenotazione-aule-ssm'); ?></label>
                    <textarea id="approve-note" class="form-control" rows="3"
                              placeholder="<?php _e('Es: Si ricorda di portare documento identificativo', 'prenotazione-aule-ssm'); ?>"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="confirmApproveBtn" style="background: #00a32a; border-color: #00a32a;">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <?php _e('Approva Prenotazione', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rifiuto Prenotazione -->
<div class="modal fade" id="rejectBookingModal" tabindex="-1" aria-labelledby="rejectBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectBookingModalLabel">
                    <span class="dashicons dashicons-dismiss" style="color: white;"></span>
                    <?php _e('Rifiuta Prenotazione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong><?php _e('Attenzione!', 'prenotazione-aule-ssm'); ?></strong>
                    <?php _e('Il richiedente riceverà un\'email di notifica del rifiuto.', 'prenotazione-aule-ssm'); ?>
                </div>
                <div class="mb-3">
                    <label for="reject-reason" class="form-label">
                        <?php _e('Motivo del rifiuto:', 'prenotazione-aule-ssm'); ?>
                        <span class="text-danger">*</span>
                    </label>
                    <textarea id="reject-reason" class="form-control" rows="4" required
                              placeholder="<?php _e('Es: Aula non disponibile per manutenzione straordinaria', 'prenotazione-aule-ssm'); ?>"></textarea>
                    <small class="form-text text-muted"><?php _e('Questo messaggio sarà visibile al richiedente.', 'prenotazione-aule-ssm'); ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="confirmRejectBtn" style="background: #d63638; border-color: #d63638;">
                    <span class="dashicons dashicons-dismiss"></span>
                    <?php _e('Conferma Rifiuto', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dettagli Prenotazione -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsModalLabel">
                    <span class="dashicons dashicons-info"></span>
                    <?php _e('Dettagli Prenotazione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="booking-details-content">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden"><?php _e('Caricamento...', 'prenotazione-aule-ssm'); ?></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Chiudi', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conferma Eliminazione -->
<div class="modal fade" id="deleteBookingModal" tabindex="-1" aria-labelledby="deleteBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteBookingModalLabel">
                    <span class="dashicons dashicons-trash" style="color: white;"></span>
                    <?php _e('Conferma Eliminazione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong><?php _e('Attenzione!', 'prenotazione-aule-ssm'); ?></strong>
                    <?php _e('Questa azione è irreversibile.', 'prenotazione-aule-ssm'); ?>
                </div>
                <p><?php _e('Sei sicuro di voler eliminare definitivamente questa prenotazione?', 'prenotazione-aule-ssm'); ?></p>
                <p class="text-muted"><?php _e('Tutti i dati associati verranno rimossi dal sistema.', 'prenotazione-aule-ssm'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="confirmDeleteBtn" style="background: #d63638; border-color: #d63638;">
                    <span class="dashicons dashicons-trash"></span>
                    <?php _e('Elimina Definitivamente', 'prenotazione-aule-ssm'); ?>
                </button>
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

/* Modal Bootstrap overrides */
.modal-header.bg-success,
.modal-header.bg-danger {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.modal-content {
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.1em;
    font-weight: 600;
}

.modal-title .dashicons {
    font-size: 20px;
    width: 20px;
    height: 20px;
}

.modal-body {
    padding: 20px;
}

.modal-body .alert {
    margin-bottom: 15px;
    padding: 12px;
    border-radius: 6px;
}

.modal-body .alert-info {
    background-color: #e8f4fd;
    border-color: #72aee6;
    color: #135e96;
}

.modal-body .alert-warning {
    background-color: #fcf9e8;
    border-color: #dba617;
    color: #8a6116;
}

.modal-body .alert-danger {
    background-color: #fcebeb;
    border-color: #d63638;
    color: #8a2424;
}

.modal-body .form-label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #1d2327;
}

.modal-body .form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    font-size: 14px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.modal-body .form-control:focus {
    border-color: #2271b1;
    outline: none;
    box-shadow: 0 0 0 1px #2271b1;
}

.modal-body .text-danger {
    color: #d63638;
}

.modal-body .text-muted {
    color: #646970;
    font-size: 12px;
}

.modal-footer {
    padding: 15px 20px;
    background: #f9f9f9;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}

.modal-footer .button {
    margin: 0;
}

.btn-close-white {
    filter: brightness(0) invert(1);
}

/* Spinner per caricamento */
.spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    vertical-align: text-bottom;
    border: 0.2em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

.spinner-border-sm {
    width: 0.875rem;
    height: 0.875rem;
    border-width: 0.15em;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}

.visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0,0,0,0);
    white-space: nowrap;
    border: 0;
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
    var currentBookingId = null;

    // Auto-submit filtri
    $('.filter-prenotazioni').on('change', function() {
        $(this).closest('form').submit();
    });

    // Gestione approvazione - Mostra modal
    $('.approve-booking').on('click', function() {
        currentBookingId = $(this).data('id');
        $('#approve-note').val('');

        // Mostra modal Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('approveBookingModal'));
        modal.show();
    });

    // Conferma approvazione dal modal
    $('#confirmApproveBtn').on('click', function() {
        var note = $('#approve-note').val().trim();
        var $btn = $(this);

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> <?php esc_js(_e('Approvazione...', 'prenotazione-aule-ssm')); ?>');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'aule_approve_booking',
                booking_id: currentBookingId,
                note_admin: note,
                nonce: '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('<?php esc_js(_e('Errore:', 'prenotazione-aule-ssm')); ?> ' + response.data);
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-yes-alt"></span> <?php esc_js(_e('Approva Prenotazione', 'prenotazione-aule-ssm')); ?>');
                }
            },
            error: function() {
                alert('<?php esc_js(_e('Errore di comunicazione', 'prenotazione-aule-ssm')); ?>');
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-yes-alt"></span> <?php esc_js(_e('Approva Prenotazione', 'prenotazione-aule-ssm')); ?>');
            }
        });
    });

    // Gestione rifiuto - Mostra modal
    $('.reject-booking').on('click', function() {
        currentBookingId = $(this).data('id');
        $('#reject-reason').val('');

        // Mostra modal Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('rejectBookingModal'));
        modal.show();
    });

    // Conferma rifiuto dal modal
    $('#confirmRejectBtn').on('click', function() {
        var reason = $('#reject-reason').val().trim();

        if (!reason) {
            alert('<?php esc_js(_e('Il motivo del rifiuto è obbligatorio', 'prenotazione-aule-ssm')); ?>');
            $('#reject-reason').focus();
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> <?php esc_js(_e('Rifiuto...', 'prenotazione-aule-ssm')); ?>');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'aule_reject_booking',
                booking_id: currentBookingId,
                note_admin: reason,
                nonce: '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('<?php esc_js(_e('Errore:', 'prenotazione-aule-ssm')); ?> ' + response.data);
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-dismiss"></span> <?php esc_js(_e('Conferma Rifiuto', 'prenotazione-aule-ssm')); ?>');
                }
            },
            error: function() {
                alert('<?php esc_js(_e('Errore di comunicazione', 'prenotazione-aule-ssm')); ?>');
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-dismiss"></span> <?php esc_js(_e('Conferma Rifiuto', 'prenotazione-aule-ssm')); ?>');
            }
        });
    });

    // Visualizza dettagli - Mostra modal
    $('.view-details').on('click', function() {
        currentBookingId = $(this).data('id');

        // Mostra modal e carica contenuto
        var modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
        modal.show();

        // Carica dettagli via AJAX (da implementare lato server)
        $('#booking-details-content').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden"><?php esc_js(_e('Caricamento...', 'prenotazione-aule-ssm')); ?></span></div></div>');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'aule_get_booking_details',
                booking_id: currentBookingId,
                nonce: '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#booking-details-content').html(response.data);
                } else {
                    $('#booking-details-content').html('<div class="alert alert-warning"><?php esc_js(_e('Impossibile caricare i dettagli', 'prenotazione-aule-ssm')); ?></div>');
                }
            },
            error: function() {
                $('#booking-details-content').html('<div class="alert alert-danger"><?php esc_js(_e('Errore di comunicazione', 'prenotazione-aule-ssm')); ?></div>');
            }
        });
    });

    // Elimina prenotazione - Mostra modal
    $('.delete-booking').on('click', function() {
        currentBookingId = $(this).data('id');

        // Mostra modal Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('deleteBookingModal'));
        modal.show();
    });

    // Conferma eliminazione dal modal
    $('#confirmDeleteBtn').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> <?php esc_js(_e('Eliminazione...', 'prenotazione-aule-ssm')); ?>');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'aule_delete_booking',
                booking_id: currentBookingId,
                nonce: '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('<?php esc_js(_e('Errore:', 'prenotazione-aule-ssm')); ?> ' + response.data);
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-trash"></span> <?php esc_js(_e('Elimina Definitivamente', 'prenotazione-aule-ssm')); ?>');
                }
            },
            error: function() {
                alert('<?php esc_js(_e('Errore di comunicazione', 'prenotazione-aule-ssm')); ?>');
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-trash"></span> <?php esc_js(_e('Elimina Definitivamente', 'prenotazione-aule-ssm')); ?>');
            }
        });
    });

    // Reset dei campi quando si chiudono i modali
    $('#approveBookingModal, #rejectBookingModal, #deleteBookingModal').on('hidden.bs.modal', function() {
        currentBookingId = null;
        $('#approve-note, #reject-reason').val('');
    });
});
</script>