<?php

/**
 * Template per la pagina "Slot Disponibilità"
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/admin/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Ottieni aula selezionata dalla query string
$selected_aula_id = !empty($_GET['aula_id']) ? absint($_GET['aula_id']) : 0;

// Se c'è un'aula selezionata, ottieni i suoi slot
$slots = array();
$selected_aula = null;
if ($selected_aula_id) {
    $database = new Aule_Booking_Database();
    $slots = $database->get_slot_disponibilita($selected_aula_id);
    $selected_aula = $database->get_aula_by_id($selected_aula_id);
}

// Giorni della settimana (1=Lunedì, 7=Domenica)
$giorni_settimana = array(
    1 => __('Lunedì', 'aule-booking'),
    2 => __('Martedì', 'aule-booking'),
    3 => __('Mercoledì', 'aule-booking'),
    4 => __('Giovedì', 'aule-booking'),
    5 => __('Venerdì', 'aule-booking'),
    6 => __('Sabato', 'aule-booking'),
    7 => __('Domenica', 'aule-booking')
);

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        🕐 <?php _e('Gestione Slot Disponibilità', 'aule-booking'); ?>
    </h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                switch ($_GET['updated']) {
                    case 'slots_generated':
                        _e('Slot generati correttamente.', 'aule-booking');
                        break;
                    case 'slot_deleted':
                        _e('Slot eliminato correttamente.', 'aule-booking');
                        break;
                    default:
                        _e('Operazione completata.', 'aule-booking');
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Si è verificato un errore durante l\'operazione.', 'aule-booking'); ?></p>
        </div>
    <?php endif; ?>

    <!-- Selezione Aula -->
    <div class="slot-aula-selector">
        <h2><?php _e('Seleziona Aula', 'aule-booking'); ?></h2>
        <form method="get" class="aula-selector-form">
            <input type="hidden" name="page" value="aule-booking-slot">

            <select name="aula_id" class="aula-selector" onchange="this.form.submit()">
                <option value=""><?php _e('Seleziona un\'aula...', 'aule-booking'); ?></option>
                <?php foreach ($aule as $aula): ?>
                    <option value="<?php echo $aula->id; ?>" <?php selected($selected_aula_id, $aula->id); ?>>
                        <?php echo esc_html($aula->nome_aula); ?>
                        <?php if (!empty($aula->ubicazione)): ?>
                            - <?php echo esc_html($aula->ubicazione); ?>
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if ($selected_aula_id): ?>
                <a href="<?php echo admin_url('admin.php?page=aule-booking-add-aula&edit=1&id=' . $selected_aula_id); ?>"
                   class="button button-secondary">
                    <span class="wp-icon wp-icon-edit"></span>
                    <?php _e('Modifica Aula', 'aule-booking'); ?>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($aule)): ?>
        <!-- Nessuna aula disponibile -->
        <div class="no-data">
            <span class="wp-icon wp-icon-building" style="font-size: 3em; opacity: 0.3;"></span>
            <h3><?php _e('Nessuna aula disponibile', 'aule-booking'); ?></h3>
            <p><?php _e('Crea prima un\'aula per configurare gli slot di disponibilità.', 'aule-booking'); ?></p>
            <a href="<?php echo admin_url('admin.php?page=aule-booking-add-aula'); ?>" class="button button-primary">
                <span class="wp-icon wp-icon-add"></span>
                <?php _e('Aggiungi Aula', 'aule-booking'); ?>
            </a>
        </div>

    <?php elseif (!$selected_aula_id): ?>
        <!-- Nessuna aula selezionata -->
        <div class="no-data">
            🕐
            <h3><?php _e('Seleziona un\'aula', 'aule-booking'); ?></h3>
            <p><?php _e('Scegli un\'aula dal menu a tendina per gestire i suoi slot di disponibilità.', 'aule-booking'); ?></p>
        </div>

    <?php else: ?>
        <!-- Interfaccia gestione slot per aula selezionata -->
        <div class="slot-management-container">
            <!-- Header aula selezionata -->
            <div class="selected-aula-info">
                <h2>
                    <span class="wp-icon wp-icon-building"></span>
                    <?php echo esc_html($selected_aula->nome_aula); ?>
                </h2>
                <div class="aula-details">
                    <?php if (!empty($selected_aula->ubicazione)): ?>
                        <span><span class="wp-icon wp-icon-location"></span> <?php echo esc_html($selected_aula->ubicazione); ?></span>
                    <?php endif; ?>
                    <span><span class="wp-icon wp-icon-users"></span> <?php printf(__('%d persone', 'aule-booking'), $selected_aula->capienza); ?></span>
                    <span class="aula-status <?php echo esc_attr($selected_aula->stato); ?>">
                        <?php
                        switch ($selected_aula->stato) {
                            case 'attiva': echo '✅ ' . __('Attiva', 'aule-booking'); break;
                            case 'non_disponibile': echo '⚠️ ' . __('Non Disponibile', 'aule-booking'); break;
                            case 'manutenzione': echo '🔧 ' . __('Manutenzione', 'aule-booking'); break;
                        }
                        ?>
                    </span>
                </div>
            </div>

            <!-- Generatore automatico slot -->
            <div class="slot-generator">
                <div class="slot-generator-header">
                    <h3>
                        ⚙️ <?php _e('Generatore Automatico Slot', 'aule-booking'); ?>
                    </h3>
                    <p><?php _e('Crea rapidamente slot ricorrenti per l\'aula selezionata.', 'aule-booking'); ?></p>
                </div>

                <div class="slot-generator-body">
                    <form id="slot-generator-form" class="generator-form">
                        <input type="hidden" name="aula_id" value="<?php echo $selected_aula_id; ?>">

                        <!-- Selezione giorni della settimana -->
                        <div class="form-group">
                            <label class="form-label"><?php _e('Giorni della Settimana', 'aule-booking'); ?></label>
                            <div class="giorni-settimana">
                                <?php foreach ($giorni_settimana as $numero => $nome): ?>
                                    <div class="giorno-item">
                                        <input type="checkbox"
                                               id="giorno_<?php echo $numero; ?>"
                                               name="giorni_settimana[]"
                                               value="<?php echo $numero; ?>">
                                        <label for="giorno_<?php echo $numero; ?>" class="giorno-nome">
                                            <?php echo substr($nome, 0, 3); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="select-weekdays" class="button button-small">
                                <?php _e('Lun-Ven', 'aule-booking'); ?>
                            </button>
                            <button type="button" id="select-all-days" class="button button-small">
                                <?php _e('Tutti', 'aule-booking'); ?>
                            </button>
                            <button type="button" id="clear-days" class="button button-small">
                                <?php _e('Nessuno', 'aule-booking'); ?>
                            </button>
                        </div>

                        <!-- Configurazione orari -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ora_inizio" class="form-label"><?php _e('Ora Inizio', 'aule-booking'); ?></label>
                                <input type="time" id="ora_inizio" name="ora_inizio" value="08:00" required>
                            </div>

                            <div class="form-group">
                                <label for="ora_fine" class="form-label"><?php _e('Ora Fine', 'aule-booking'); ?></label>
                                <input type="time" id="ora_fine" name="ora_fine" value="18:00" required>
                            </div>

                            <div class="form-group">
                                <label for="durata_slot" class="form-label"><?php _e('Durata Slot (minuti)', 'aule-booking'); ?></label>
                                <select id="durata_slot" name="durata_slot" required>
                                    <option value="30">30 min</option>
                                    <option value="60" selected>60 min</option>
                                    <option value="90">90 min</option>
                                    <option value="120">120 min</option>
                                    <option value="180">180 min</option>
                                </select>
                            </div>
                        </div>

                        <!-- Periodo validità -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="data_inizio" class="form-label"><?php _e('Data Inizio Validità', 'aule-booking'); ?></label>
                                <input type="date" id="data_inizio" name="data_inizio" value="<?php echo current_time('Y-m-d'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="data_fine" class="form-label"><?php _e('Data Fine Validità (opzionale)', 'aule-booking'); ?></label>
                                <input type="date" id="data_fine" name="data_fine" value="<?php echo date('Y-m-d', strtotime('+6 months')); ?>">
                            </div>

                            <div class="form-group">
                                <label for="ricorrenza" class="form-label"><?php _e('Ricorrenza', 'aule-booking'); ?></label>
                                <select id="ricorrenza" name="ricorrenza" required>
                                    <option value="settimanale" selected><?php _e('Settimanale', 'aule-booking'); ?></option>
                                    <option value="mensile"><?php _e('Mensile', 'aule-booking'); ?></option>
                                    <option value="singolo"><?php _e('Singolo (solo date specifiche)', 'aule-booking'); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Anteprima generazione -->
                        <div class="generation-preview">
                            <div class="preview-header">
                                <strong><?php _e('Anteprima:', 'aule-booking'); ?></strong>
                                <span id="preview-count">0</span> <?php _e('slot verranno generati', 'aule-booking'); ?>
                            </div>
                            <div id="preview-details" class="preview-details"></div>
                        </div>

                        <!-- Azioni -->
                        <div class="generator-actions">
                            <button type="submit" class="button button-primary generate-slots">
                                ⚙️ <?php _e('Genera Slot', 'aule-booking'); ?>
                            </button>
                            <button type="button" class="button preview-slots">
                                👁️ <?php _e('Anteprima', 'aule-booking'); ?>
                            </button>
                            <button type="reset" class="button button-secondary">
                                🔄 <?php _e('Reset', 'aule-booking'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista slot esistenti -->
            <div class="slots-calendar">
                <div class="slots-calendar-header">
                    <h3><?php _e('Slot Configurati', 'aule-booking'); ?></h3>
                    <div class="slot-actions-bulk">
                        <select class="bulk-action-selector" disabled>
                            <option value=""><?php _e('Azioni multiple', 'aule-booking'); ?></option>
                            <option value="delete"><?php _e('Elimina selezionati', 'aule-booking'); ?></option>
                            <option value="disable"><?php _e('Disabilita selezionati', 'aule-booking'); ?></option>
                            <option value="enable"><?php _e('Abilita selezionati', 'aule-booking'); ?></option>
                        </select>
                        <button class="button apply-bulk-action" disabled><?php _e('Applica', 'aule-booking'); ?></button>
                    </div>
                </div>

                <?php if (empty($slots)): ?>
                    <div class="no-slots">
                        🕐
                        <h4><?php _e('Nessun slot configurato', 'aule-booking'); ?></h4>
                        <p><?php _e('Usa il generatore automatico per creare i primi slot di disponibilità.', 'aule-booking'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="slots-list">
                        <!-- Raggruppamento per giorno della settimana -->
                        <?php
                        $slots_by_day = array();
                        foreach ($slots as $slot) {
                            $slots_by_day[$slot->giorno_settimana][] = $slot;
                        }
                        ?>

                        <?php foreach ($giorni_settimana as $day_num => $day_name): ?>
                            <?php if (isset($slots_by_day[$day_num])): ?>
                                <div class="day-slots-group">
                                    <h4 class="day-header">
                                        <input type="checkbox" class="select-day-slots" data-day="<?php echo $day_num; ?>">
                                        <?php echo $day_name; ?>
                                        <span class="slots-count">(<?php echo count($slots_by_day[$day_num]); ?> slot)</span>
                                    </h4>

                                    <div class="day-slots">
                                        <?php foreach ($slots_by_day[$day_num] as $slot): ?>
                                            <div class="slot-item <?php echo $slot->attivo ? 'active' : 'inactive'; ?>">
                                                <input type="checkbox" class="select-slot" value="<?php echo $slot->id; ?>">

                                                <div class="slot-info">
                                                    <div class="slot-time">
                                                        <strong>
                                                            <?php echo date('H:i', strtotime($slot->ora_inizio)); ?> -
                                                            <?php echo date('H:i', strtotime($slot->ora_fine)); ?>
                                                        </strong>
                                                        <span class="slot-duration">(<?php echo $slot->durata_slot_minuti; ?> min)</span>
                                                    </div>

                                                    <div class="slot-meta">
                                                        <span class="slot-validity">
                                                            📅 <?php echo date_i18n('d/m/Y', strtotime($slot->data_inizio_validita)); ?>
                                                            <?php if ($slot->data_fine_validita): ?>
                                                                - <?php echo date_i18n('d/m/Y', strtotime($slot->data_fine_validita)); ?>
                                                            <?php endif; ?>
                                                        </span>

                                                        <span class="slot-recurrence">
                                                            🔄 <?php
                                                            switch ($slot->ricorrenza) {
                                                                case 'singolo': echo __('Singolo', 'aule-booking'); break;
                                                                case 'settimanale': echo __('Settimanale', 'aule-booking'); break;
                                                                case 'mensile': echo __('Mensile', 'aule-booking'); break;
                                                            }
                                                            ?>
                                                        </span>

                                                        <?php if (!$slot->attivo): ?>
                                                            <span class="slot-status inactive">❌ <?php _e('Disabilitato', 'aule-booking'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="slot-actions">
                                                    <button class="button button-small edit-slot"
                                                            data-id="<?php echo $slot->id; ?>"
                                                            title="<?php _e('Modifica slot', 'aule-booking'); ?>">
                                                        <span class="wp-icon wp-icon-edit"></span>
                                                    </button>

                                                    <button class="button button-small toggle-slot"
                                                            data-id="<?php echo $slot->id; ?>"
                                                            data-status="<?php echo $slot->attivo ? 'disable' : 'enable'; ?>"
                                                            title="<?php echo $slot->attivo ? __('Disabilita slot', 'aule-booking') : __('Abilita slot', 'aule-booking'); ?>">
                                                        <?php echo $slot->attivo ? '❌' : '✅'; ?>
                                                    </button>

                                                    <button class="button button-small delete-slot"
                                                            data-id="<?php echo $slot->id; ?>"
                                                            title="<?php _e('Elimina slot', 'aule-booking'); ?>">
                                                        <span class="wp-icon wp-icon-remove"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Stili specifici per gestione slot */
.slot-aula-selector {
    background: #f9f9f9;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.aula-selector-form {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.aula-selector {
    min-width: 300px;
    padding: 8px 12px;
    font-size: 14px;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
}

.selected-aula-info {
    background: white;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.selected-aula-info h2 {
    margin: 0 0 10px 0;
    color: #1d2327;
    font-size: 1.4em;
}

.aula-details {
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
    font-size: 14px;
    color: #646970;
}

.aula-details span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.slot-generator {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin: 20px 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.slot-generator-header {
    background: linear-gradient(135deg, #2271b1, #72aee6);
    color: white;
    padding: 20px;
    border-radius: 8px 8px 0 0;
}

.slot-generator-header h3 {
    margin: 0 0 5px 0;
    font-size: 1.3em;
}

.slot-generator-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.slot-generator-body {
    padding: 25px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1d2327;
    font-size: 14px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    font-size: 14px;
}

.giorni-settimana {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin: 10px 0;
}

.giorno-item {
    position: relative;
    text-align: center;
    cursor: pointer;
}

.giorno-item input[type="checkbox"] {
    display: none;
}

.giorno-nome {
    display: block;
    padding: 12px 8px;
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
}

.giorno-item input:checked + .giorno-nome {
    background: #2271b1;
    color: white;
    border-color: #2271b1;
}

.giorno-nome:hover {
    border-color: #2271b1;
    background: #e8f4fd;
}

.generation-preview {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin: 20px 0;
    border-left: 4px solid #2271b1;
}

.preview-header {
    font-size: 14px;
    margin-bottom: 10px;
}

.preview-details {
    font-size: 12px;
    color: #646970;
    line-height: 1.4;
}

.generator-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
    flex-wrap: wrap;
}

.slots-calendar {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin: 20px 0;
}

.slots-calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f9f9f9;
    border-bottom: 1px solid #e0e0e0;
    border-radius: 8px 8px 0 0;
}

.slots-calendar-header h3 {
    margin: 0;
    color: #1d2327;
}

.slot-actions-bulk {
    display: flex;
    gap: 10px;
    align-items: center;
}

.bulk-action-selector {
    padding: 6px 10px;
    font-size: 13px;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
}

.no-slots {
    text-align: center;
    padding: 40px 20px;
    color: #646970;
}

.no-slots h4 {
    margin: 10px 0;
    font-size: 1.2em;
}

.day-slots-group {
    border-bottom: 1px solid #f0f0f1;
}

.day-header {
    background: #f8f9fa;
    padding: 15px 20px;
    margin: 0;
    font-size: 1.1em;
    color: #1d2327;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #e0e0e0;
}

.slots-count {
    color: #646970;
    font-weight: normal;
    font-size: 0.9em;
}

.day-slots {
    padding: 0;
}

.slot-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    border-bottom: 1px solid #f6f7f7;
    transition: background-color 0.2s ease;
}

.slot-item:hover {
    background-color: #f8f9fa;
}

.slot-item.inactive {
    opacity: 0.6;
    background-color: #fef7f0;
}

.slot-info {
    flex: 1;
}

.slot-time {
    font-size: 16px;
    margin-bottom: 5px;
}

.slot-duration {
    color: #646970;
    font-weight: normal;
    font-size: 14px;
}

.slot-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: #8c8f94;
    flex-wrap: wrap;
}

.slot-status.inactive {
    color: #d63638;
    font-weight: 600;
}

.slot-actions {
    display: flex;
    gap: 5px;
}

.slot-actions .button {
    padding: 4px 8px;
    font-size: 11px;
    min-width: 32px;
    height: 28px;
}

/* Responsive */
@media (max-width: 768px) {
    .aula-selector-form {
        flex-direction: column;
        align-items: stretch;
    }

    .aula-selector {
        min-width: 100%;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .giorni-settimana {
        grid-template-columns: repeat(4, 1fr);
    }

    .generator-actions {
        flex-direction: column;
    }

    .generator-actions .button {
        width: 100%;
        justify-content: center;
    }

    .slots-calendar-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .slot-item {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .slot-meta {
        flex-direction: column;
        gap: 5px;
    }

    .slot-actions {
        justify-content: space-between;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Selezione rapida giorni
    $('#select-weekdays').on('click', function() {
        $('.giorni-settimana input[type="checkbox"]').each(function() {
            var value = parseInt($(this).val());
            $(this).prop('checked', value >= 1 && value <= 5);
        });
        updatePreview();
    });

    $('#select-all-days').on('click', function() {
        $('.giorni-settimana input[type="checkbox"]').prop('checked', true);
        updatePreview();
    });

    $('#clear-days').on('click', function() {
        $('.giorni-settimana input[type="checkbox"]').prop('checked', false);
        updatePreview();
    });

    // Gestione click sui giorni
    $('.giorno-item').on('click', function() {
        var checkbox = $(this).find('input[type="checkbox"]');
        checkbox.prop('checked', !checkbox.prop('checked'));
        updatePreview();
    });

    // Aggiorna anteprima quando cambiano i parametri
    $('#slot-generator-form input, #slot-generator-form select').on('change', updatePreview);

    // Anteprima slot
    $('.preview-slots').on('click', function() {
        updatePreview(true);
    });

    function updatePreview(detailed = false) {
        var form = $('#slot-generator-form');
        var selectedDays = $('input[name="giorni_settimana[]"]:checked').length;
        var oraInizio = $('#ora_inizio').val();
        var oraFine = $('#ora_fine').val();
        var durataSlot = parseInt($('#durata_slot').val());

        if (!selectedDays || !oraInizio || !oraFine || !durataSlot) {
            $('#preview-count').text('0');
            $('#preview-details').html('');
            return;
        }

        // Calcola slot per giorno
        var startTime = new Date('2000-01-01 ' + oraInizio);
        var endTime = new Date('2000-01-01 ' + oraFine);
        var slotDuration = durataSlot * 60 * 1000; // millisecondi
        var slotsPerDay = Math.floor((endTime - startTime) / slotDuration);
        var totalSlots = selectedDays * slotsPerDay;

        $('#preview-count').text(totalSlots);

        if (detailed) {
            var details = '';
            details += '<strong>Configurazione:</strong><br>';
            details += 'Giorni selezionati: ' + selectedDays + '<br>';
            details += 'Slot per giorno: ' + slotsPerDay + '<br>';
            details += 'Orario: ' + oraInizio + ' - ' + oraFine + '<br>';
            details += 'Durata per slot: ' + durataSlot + ' minuti<br>';
            details += '<strong>Totale slot: ' + totalSlots + '</strong>';
            $('#preview-details').html(details);
        }
    }

    // Generazione slot
    $('#slot-generator-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        formData += '&action=aule_generate_slots&nonce=' + '<?php echo wp_create_nonce('aule_booking_admin_nonce'); ?>';

        var $button = $('.generate-slots');
        $button.prop('disabled', true).text('Generazione in corso...');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.data);
                    location.reload();
                } else {
                    alert('❌ Errore: ' + response.data);
                }
            },
            error: function() {
                alert('❌ Errore di comunicazione con il server');
            },
            complete: function() {
                $button.prop('disabled', false).text('⚙️ Genera Slot');
            }
        });
    });

    // Selezione multipla slot
    $('.select-day-slots').on('change', function() {
        var day = $(this).data('day');
        var checked = $(this).is(':checked');
        $('.day-slots input[type="checkbox"]').prop('checked', checked);
        updateBulkActions();
    });

    $('.select-slot').on('change', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var selectedCount = $('.select-slot:checked').length;
        $('.bulk-action-selector, .apply-bulk-action').prop('disabled', selectedCount === 0);
    }

    // Azioni singole sui slot
    $('.delete-slot').on('click', function() {
        var slotId = $(this).data('id');

        if (!confirm('Eliminare definitivamente questo slot?')) {
            return;
        }

        // Placeholder - implementare AJAX per eliminazione
        alert('Eliminazione slot ID: ' + slotId + '\n(Da implementare)');
    });

    $('.toggle-slot').on('click', function() {
        var slotId = $(this).data('id');
        var action = $(this).data('status');

        // Placeholder - implementare AJAX per toggle
        alert('Toggle slot ID: ' + slotId + ' (' + action + ')\n(Da implementare)');
    });

    $('.edit-slot').on('click', function() {
        var slotId = $(this).data('id');

        // Placeholder - implementare modal di modifica
        alert('Modifica slot ID: ' + slotId + '\n(Da implementare con modal)');
    });

    // Inizializza anteprima
    updatePreview();
});
</script>