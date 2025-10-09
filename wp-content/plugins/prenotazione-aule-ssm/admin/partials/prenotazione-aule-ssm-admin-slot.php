<?php

/**
 * Template per la pagina "Slot Disponibilità"
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/admin/partials
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
    $database = new Prenotazione_Aule_SSM_Database();
    $slots = $database->get_slot_disponibilita($selected_aula_id);
    $selected_aula = $database->get_aula_by_id($selected_aula_id);
}

// Giorni della settimana (1=Lunedì, 7=Domenica)
$giorni_settimana = array(
    1 => __('Lunedì', 'prenotazione-aule-ssm'),
    2 => __('Martedì', 'prenotazione-aule-ssm'),
    3 => __('Mercoledì', 'prenotazione-aule-ssm'),
    4 => __('Giovedì', 'prenotazione-aule-ssm'),
    5 => __('Venerdì', 'prenotazione-aule-ssm'),
    6 => __('Sabato', 'prenotazione-aule-ssm'),
    7 => __('Domenica', 'prenotazione-aule-ssm')
);

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-clock"></span>
        <?php _e('Gestione Slot Disponibilità', 'prenotazione-aule-ssm'); ?>
    </h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                switch ($_GET['updated']) {
                    case 'slots_generated':
                        _e('Slot generati correttamente.', 'prenotazione-aule-ssm');
                        break;
                    case 'slot_deleted':
                        _e('Slot eliminato correttamente.', 'prenotazione-aule-ssm');
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

    <!-- Selezione Aula -->
    <div class="slot-aula-selector">
        <h2><?php _e('Seleziona Aula', 'prenotazione-aule-ssm'); ?></h2>
        <form method="get" class="aula-selector-form">
            <input type="hidden" name="page" value="prenotazione-aule-ssm-slot">

            <select name="aula_id" class="aula-selector" onchange="this.form.submit()">
                <option value=""><?php _e('Seleziona un\'aula...', 'prenotazione-aule-ssm'); ?></option>
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
                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-add-aula&edit=1&id=' . $selected_aula_id); ?>"
                   class="button button-secondary">
                    <span class="wp-icon wp-icon-edit"></span>
                    <?php _e('Modifica Aula', 'prenotazione-aule-ssm'); ?>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($aule)): ?>
        <!-- Nessuna aula disponibile -->
        <div class="no-data">
            <span class="wp-icon wp-icon-building" style="font-size: 3em; opacity: 0.3;"></span>
            <h3><?php _e('Nessuna aula disponibile', 'prenotazione-aule-ssm'); ?></h3>
            <p><?php _e('Crea prima un\'aula per configurare gli slot di disponibilità.', 'prenotazione-aule-ssm'); ?></p>
            <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-add-aula'); ?>" class="button button-primary">
                <span class="wp-icon wp-icon-add"></span>
                <?php _e('Aggiungi Aula', 'prenotazione-aule-ssm'); ?>
            </a>
        </div>

    <?php elseif (!$selected_aula_id): ?>
        <!-- Nessuna aula selezionata -->
        <div class="no-data">
            <span class="dashicons dashicons-clock" style="font-size: 48px; color: #ccc;"></span>
            <h3><?php _e('Seleziona un\'aula', 'prenotazione-aule-ssm'); ?></h3>
            <p><?php _e('Scegli un\'aula dal menu a tendina per gestire i suoi slot di disponibilità.', 'prenotazione-aule-ssm'); ?></p>
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
                    <span><span class="wp-icon wp-icon-users"></span> <?php printf(__('%d persone', 'prenotazione-aule-ssm'), $selected_aula->capienza); ?></span>
                    <span class="aula-status <?php echo esc_attr($selected_aula->stato); ?>">
                        <?php
                        switch ($selected_aula->stato) {
                            case 'attiva': echo '<span class="dashicons dashicons-yes-alt"></span> ' . __('Attiva', 'prenotazione-aule-ssm'); break;
                            case 'non_disponibile': echo '<span class="dashicons dashicons-warning"></span> ' . __('Non Disponibile', 'prenotazione-aule-ssm'); break;
                            case 'manutenzione': echo '<span class="dashicons dashicons-admin-tools"></span> ' . __('Manutenzione', 'prenotazione-aule-ssm'); break;
                        }
                        ?>
                    </span>
                </div>
            </div>

            <!-- Generatore automatico slot -->
            <div class="slot-generator">
                <div class="slot-generator-header">
                    <h3>
                        <span class="dashicons dashicons-admin-generic"></span>
                        <?php _e('Generatore Automatico Slot', 'prenotazione-aule-ssm'); ?>
                    </h3>
                    <p><?php _e('Crea rapidamente slot ricorrenti per l\'aula selezionata.', 'prenotazione-aule-ssm'); ?></p>
                </div>

                <div class="slot-generator-body">
                    <form id="slot-generator-form" class="generator-form">
                        <input type="hidden" name="aula_id" value="<?php echo $selected_aula_id; ?>">

                        <!-- Selezione giorni della settimana -->
                        <div class="form-group">
                            <label class="form-label"><?php _e('Giorni della Settimana', 'prenotazione-aule-ssm'); ?></label>
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
                                <?php _e('Lun-Ven', 'prenotazione-aule-ssm'); ?>
                            </button>
                            <button type="button" id="select-all-days" class="button button-small">
                                <?php _e('Tutti', 'prenotazione-aule-ssm'); ?>
                            </button>
                            <button type="button" id="clear-days" class="button button-small">
                                <?php _e('Nessuno', 'prenotazione-aule-ssm'); ?>
                            </button>
                        </div>

                        <!-- Configurazione orari -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ora_inizio" class="form-label"><?php _e('Ora Inizio', 'prenotazione-aule-ssm'); ?></label>
                                <input type="time" id="ora_inizio" name="ora_inizio" value="08:00" required>
                            </div>

                            <div class="form-group">
                                <label for="ora_fine" class="form-label"><?php _e('Ora Fine', 'prenotazione-aule-ssm'); ?></label>
                                <input type="time" id="ora_fine" name="ora_fine" value="18:00" required>
                            </div>

                            <div class="form-group">
                                <label for="durata_slot" class="form-label"><?php _e('Durata Slot (minuti)', 'prenotazione-aule-ssm'); ?></label>
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
                                <label for="data_inizio" class="form-label"><?php _e('Data Inizio Validità', 'prenotazione-aule-ssm'); ?></label>
                                <input type="date" id="data_inizio" name="data_inizio" value="<?php echo current_time('Y-m-d'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="data_fine" class="form-label"><?php _e('Data Fine Validità (opzionale)', 'prenotazione-aule-ssm'); ?></label>
                                <input type="date" id="data_fine" name="data_fine" value="<?php echo date('Y-m-d', strtotime('+6 months')); ?>">
                            </div>

                            <div class="form-group">
                                <label for="ricorrenza" class="form-label"><?php _e('Ricorrenza', 'prenotazione-aule-ssm'); ?></label>
                                <select id="ricorrenza" name="ricorrenza" required>
                                    <option value="settimanale" selected><?php _e('Settimanale', 'prenotazione-aule-ssm'); ?></option>
                                    <option value="mensile"><?php _e('Mensile', 'prenotazione-aule-ssm'); ?></option>
                                    <option value="singolo"><?php _e('Singolo (solo date specifiche)', 'prenotazione-aule-ssm'); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Anteprima generazione -->
                        <div class="generation-preview">
                            <div class="preview-header">
                                <strong><?php _e('Anteprima:', 'prenotazione-aule-ssm'); ?></strong>
                                <span id="preview-count">0</span> <?php _e('slot verranno generati', 'prenotazione-aule-ssm'); ?>
                            </div>
                            <div id="preview-details" class="preview-details"></div>
                        </div>

                        <!-- Azioni -->
                        <div class="generator-actions">
                            <button type="submit" class="button button-primary generate-slots">
                                <span class="dashicons dashicons-admin-generic"></span>
                                <?php _e('Genera Slot', 'prenotazione-aule-ssm'); ?>
                            </button>
                            <button type="button" class="button preview-slots">
                                <span class="dashicons dashicons-visibility"></span>
                                <?php _e('Anteprima', 'prenotazione-aule-ssm'); ?>
                            </button>
                            <button type="reset" class="button button-secondary">
                                <span class="dashicons dashicons-update"></span>
                                <?php _e('Reset', 'prenotazione-aule-ssm'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista slot esistenti -->
            <div class="slots-calendar">
                <div class="slots-calendar-header">
                    <h3><?php _e('Slot Configurati', 'prenotazione-aule-ssm'); ?></h3>
                    <div class="slot-actions-bulk">
                        <select class="bulk-action-selector" disabled>
                            <option value=""><?php _e('Azioni multiple', 'prenotazione-aule-ssm'); ?></option>
                            <option value="delete"><?php _e('Elimina selezionati', 'prenotazione-aule-ssm'); ?></option>
                            <option value="disable"><?php _e('Disabilita selezionati', 'prenotazione-aule-ssm'); ?></option>
                            <option value="enable"><?php _e('Abilita selezionati', 'prenotazione-aule-ssm'); ?></option>
                        </select>
                        <button class="button apply-bulk-action" disabled><?php _e('Applica', 'prenotazione-aule-ssm'); ?></button>
                    </div>
                </div>

                <?php if (empty($slots)): ?>
                    <div class="no-slots">
                        <span class="dashicons dashicons-clock" style="font-size: 48px; color: #ccc;"></span>
                        <h4><?php _e('Nessun slot configurato', 'prenotazione-aule-ssm'); ?></h4>
                        <p><?php _e('Usa il generatore automatico per creare i primi slot di disponibilità.', 'prenotazione-aule-ssm'); ?></p>
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
                                                            <span class="dashicons dashicons-calendar-alt"></span>
                                                            <?php echo date_i18n('d/m/Y', strtotime($slot->data_inizio_validita)); ?>
                                                            <?php if ($slot->data_fine_validita): ?>
                                                                - <?php echo date_i18n('d/m/Y', strtotime($slot->data_fine_validita)); ?>
                                                            <?php endif; ?>
                                                        </span>

                                                        <span class="slot-recurrence">
                                                            <span class="dashicons dashicons-update"></span>
                                                            <?php
                                                            switch ($slot->ricorrenza) {
                                                                case 'singolo': echo __('Singolo', 'prenotazione-aule-ssm'); break;
                                                                case 'settimanale': echo __('Settimanale', 'prenotazione-aule-ssm'); break;
                                                                case 'mensile': echo __('Mensile', 'prenotazione-aule-ssm'); break;
                                                            }
                                                            ?>
                                                        </span>

                                                        <?php if (!$slot->attivo): ?>
                                                            <span class="slot-status inactive">
                                                                <span class="dashicons dashicons-hidden"></span>
                                                                <?php _e('Disabilitato', 'prenotazione-aule-ssm'); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="slot-actions">
                                                    <button class="button button-small edit-slot"
                                                            data-id="<?php echo $slot->id; ?>"
                                                            title="<?php _e('Modifica slot', 'prenotazione-aule-ssm'); ?>">
                                                        <span class="dashicons dashicons-edit"></span>
                                                    </button>

                                                    <button class="button button-small toggle-slot"
                                                            data-id="<?php echo $slot->id; ?>"
                                                            data-status="<?php echo $slot->attivo ? 'disable' : 'enable'; ?>"
                                                            title="<?php echo $slot->attivo ? __('Disabilita slot', 'prenotazione-aule-ssm') : __('Abilita slot', 'prenotazione-aule-ssm'); ?>">
                                                        <span class="dashicons dashicons-<?php echo $slot->attivo ? 'hidden' : 'visibility'; ?>"></span>
                                                    </button>

                                                    <button class="button button-small delete-slot"
                                                            data-id="<?php echo $slot->id; ?>"
                                                            title="<?php _e('Elimina slot', 'prenotazione-aule-ssm'); ?>">
                                                        <span class="dashicons dashicons-trash"></span>
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
    display: flex;
    align-items: center;
    justify-content: center;
}

.slot-actions .button .dashicons {
    width: 18px;
    height: 18px;
    font-size: 18px;
}

.slot-meta .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
    vertical-align: text-top;
}

.slot-status .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}

/* Modal fixes */
.modal {
    display: none;
}

.modal.show {
    display: block;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
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
        formData += '&action=aule_generate_slots&nonce=' + '<?php echo wp_create_nonce('prenotazione_aule_ssm_admin_nonce'); ?>';

        var $button = $('.generate-slots');
        $button.prop('disabled', true).text('Generazione in corso...');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    AuleBookingAdmin.showNotice(response.data, 'success');
                    location.reload();
                } else {
                    AuleBookingAdmin.showNotice('Errore: ' + response.data, 'error');
                }
            },
            error: function() {
                AuleBookingAdmin.showNotice('Errore di comunicazione con il server', 'error');
            },
            complete: function() {
                $button.prop('disabled', false).html('<span class="dashicons dashicons-admin-generic"></span> Genera Slot');
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

    // Handler per pulsante applica bulk action
    $('.apply-bulk-action').on('click', function() {
        var action = $('.bulk-action-selector').val();
        var $checked = $('.select-slot:checked');

        if (!action) {
            alert('Seleziona un\'azione dal menu');
            return;
        }

        if ($checked.length === 0) {
            alert('Seleziona almeno uno slot');
            return;
        }

        // Prepara messaggio modal
        var actionText = {
            'enable': 'abilitare',
            'disable': 'disabilitare',
            'delete': 'eliminare definitivamente'
        };

        var message = 'Vuoi ' + actionText[action] + ' gli slot selezionati?';
        var countText = $checked.length + ' slot selezionati';

        if (action === 'delete') {
            countText += ' - Questa azione è irreversibile!';
        }

        $('#bulkActionMessage').text(message);
        $('#bulkActionCount').text(countText);
        $('#confirmBulkBtn').data('action', action);
        $('#confirmBulkBtn').data('slot-ids', $checked.map(function() {
            return $(this).val();
        }).get());

        // Mostra modal
        var modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
        modal.show();
    });

    // Azioni singole sui slot gestite dal file JS esterno (prenotazione-aule-ssm-admin.js)
    // NON aggiungere handler inline qui - creano conflitti!

    // Inizializza anteprima
    updatePreview();
});
</script>

<!-- Modal Edit Slot -->
<div class="modal fade" id="editSlotModal" tabindex="-1" aria-labelledby="editSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSlotModalLabel">
                    <span class="dashicons dashicons-edit"></span>
                    <?php _e('Modifica Slot', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSlotForm">
                    <input type="hidden" id="edit_slot_id" name="slot_id">

                    <div class="mb-3">
                        <label for="edit_ora_inizio" class="form-label"><?php _e('Ora Inizio', 'prenotazione-aule-ssm'); ?></label>
                        <input type="time" class="form-control" id="edit_ora_inizio" name="ora_inizio" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_ora_fine" class="form-label"><?php _e('Ora Fine', 'prenotazione-aule-ssm'); ?></label>
                        <input type="time" class="form-control" id="edit_ora_fine" name="ora_fine" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_data_inizio" class="form-label"><?php _e('Data Inizio Validità', 'prenotazione-aule-ssm'); ?></label>
                        <input type="date" class="form-control" id="edit_data_inizio" name="data_inizio" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_data_fine" class="form-label"><?php _e('Data Fine Validità', 'prenotazione-aule-ssm'); ?></label>
                        <input type="date" class="form-control" id="edit_data_fine" name="data_fine">
                        <small class="form-text text-muted"><?php _e('Opzionale - lascia vuoto per nessuna scadenza', 'prenotazione-aule-ssm'); ?></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="saveSlotBtn">
                    <?php _e('Salva Modifiche', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conferma Toggle Slot -->
<div class="modal fade" id="toggleSlotModal" tabindex="-1" aria-labelledby="toggleSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleSlotModalLabel">
                    <span class="dashicons dashicons-visibility"></span>
                    <?php _e('Conferma Azione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="toggleSlotMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="confirmToggleBtn">
                    <?php _e('Conferma', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conferma Eliminazione Slot -->
<div class="modal fade" id="deleteSlotModal" tabindex="-1" aria-labelledby="deleteSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteSlotModalLabel">
                    <span class="dashicons dashicons-trash" style="color: white;"></span>
                    <?php _e('Conferma Eliminazione', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong><?php _e('Attenzione!', 'prenotazione-aule-ssm'); ?></strong>
                    <?php _e('Questa azione è irreversibile.', 'prenotazione-aule-ssm'); ?>
                </div>
                <p><?php _e('Sei sicuro di voler eliminare definitivamente questo slot?', 'prenotazione-aule-ssm'); ?></p>
                <p class="text-muted"><?php _e('Le prenotazioni associate a questo slot potrebbero essere influenzate.', 'prenotazione-aule-ssm'); ?></p>
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

<!-- Modal Conferma Bulk Action -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <?php _e('Conferma Azione Multipla', 'prenotazione-aule-ssm'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="bulkActionMessage"></p>
                <div class="alert alert-info">
                    <strong id="bulkActionCount"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-bs-dismiss="modal">
                    <?php _e('Annulla', 'prenotazione-aule-ssm'); ?>
                </button>
                <button type="button" class="button button-primary" id="confirmBulkBtn">
                    <?php _e('Conferma', 'prenotazione-aule-ssm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>