<?php

/**
 * Template per la pagina aggiungi/modifica aula
 *
 * @since 1.0.0
 * @package WP_Aule_Booking
 * @subpackage WP_Aule_Booking/admin/partials
 */

// Previeni accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? __('Modifica Aula', 'aule-booking') : __('Aggiungi Nuova Aula', 'aule-booking');
$button_text = $is_edit ? __('Aggiorna Aula', 'aule-booking') : __('Crea Aula', 'aule-booking');

// Valori predefiniti
$nome_aula = $is_edit && $aula ? $aula->nome_aula : '';
$descrizione = $is_edit && $aula ? $aula->descrizione : '';
$capienza = $is_edit && $aula ? $aula->capienza : 1;
$ubicazione = $is_edit && $aula ? $aula->ubicazione : '';
$stato = $is_edit && $aula ? $aula->stato : 'attiva';
$attrezzature = $is_edit && $aula ? maybe_unserialize($aula->attrezzature) : array();
$immagini = $is_edit && $aula ? maybe_unserialize($aula->immagini) : array();

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <span class="wp-icon wp-icon-building"></span>
        <?php echo esc_html($page_title); ?>
    </h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Aula salvata correttamente.', 'aule-booking'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <?php
                switch ($_GET['error']) {
                    case 'nome_required':
                        _e('Il nome dell\'aula è obbligatorio.', 'aule-booking');
                        break;
                    case 'save':
                        _e('Errore nel salvataggio dell\'aula.', 'aule-booking');
                        break;
                    default:
                        _e('Si è verificato un errore.', 'aule-booking');
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="aula-form-container">
        <div class="aula-form-header">
            <h2>
                <span class="wp-icon wp-icon-<?php echo $is_edit ? 'edit' : 'add'; ?>"></span>
                <?php echo esc_html($page_title); ?>
            </h2>
        </div>

        <div class="aula-form-body">
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data" class="aula-form">
                <?php wp_nonce_field('aule_booking_aula_nonce'); ?>

                <input type="hidden" name="action" value="aule_booking_save_aula">
                <?php if ($is_edit && $aula): ?>
                    <input type="hidden" name="aula_id" value="<?php echo esc_attr($aula->id); ?>">
                <?php endif; ?>

                <!-- Informazioni Base -->
                <div class="form-section">
                    <h3><?php _e('Informazioni Base', 'aule-booking'); ?></h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome_aula" class="required"><?php _e('Nome Aula', 'aule-booking'); ?></label>
                            <input type="text"
                                   id="nome_aula"
                                   name="nome_aula"
                                   value="<?php echo esc_attr($nome_aula); ?>"
                                   required
                                   maxlength="255"
                                   placeholder="<?php _e('es. Aula Studio A1', 'aule-booking'); ?>">
                            <span class="help-text"><?php _e('Nome identificativo dell\'aula', 'aule-booking'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="ubicazione"><?php _e('Ubicazione', 'aule-booking'); ?></label>
                            <input type="text"
                                   id="ubicazione"
                                   name="ubicazione"
                                   value="<?php echo esc_attr($ubicazione); ?>"
                                   maxlength="255"
                                   placeholder="<?php _e('es. Piano Terra, Ala Est', 'aule-booking'); ?>">
                            <span class="help-text"><?php _e('Posizione fisica dell\'aula', 'aule-booking'); ?></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="capienza"><?php _e('Capienza', 'aule-booking'); ?></label>
                            <input type="number"
                                   id="capienza"
                                   name="capienza"
                                   value="<?php echo esc_attr($capienza); ?>"
                                   min="1"
                                   max="999"
                                   placeholder="<?php _e('Numero massimo di persone', 'aule-booking'); ?>">
                            <span class="help-text"><?php _e('Numero massimo di persone che possono utilizzare l\'aula', 'aule-booking'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="stato"><?php _e('Stato', 'aule-booking'); ?></label>
                            <select id="stato" name="stato">
                                <option value="attiva" <?php selected($stato, 'attiva'); ?>><?php _e('Attiva', 'aule-booking'); ?></option>
                                <option value="non_disponibile" <?php selected($stato, 'non_disponibile'); ?>><?php _e('Non Disponibile', 'aule-booking'); ?></option>
                                <option value="manutenzione" <?php selected($stato, 'manutenzione'); ?>><?php _e('In Manutenzione', 'aule-booking'); ?></option>
                            </select>
                            <span class="help-text"><?php _e('Stato attuale dell\'aula', 'aule-booking'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descrizione"><?php _e('Descrizione', 'aule-booking'); ?></label>
                        <textarea id="descrizione"
                                  name="descrizione"
                                  rows="4"
                                  placeholder="<?php _e('Descrizione dettagliata dell\'aula, caratteristiche speciali, etc.', 'aule-booking'); ?>"><?php echo esc_textarea($descrizione); ?></textarea>
                        <span class="help-text"><?php _e('Descrizione opzionale dell\'aula visibile agli utenti', 'aule-booking'); ?></span>
                    </div>
                </div>

                <!-- Attrezzature -->
                <div class="form-section">
                    <h3><?php _e('Attrezzature Disponibili', 'aule-booking'); ?></h3>

                    <div class="attrezzature-grid">
                        <?php
                        $attrezzature_disponibili = array(
                            'proiettore' => array('label' => __('📺 Proiettore', 'aule-booking')),
                            'lavagna' => array('label' => __('📋 Lavagna', 'aule-booking')),
                            'pc' => array('label' => __('💻 Computer', 'aule-booking')),
                            'webcam' => array('label' => __('📹 Webcam', 'aule-booking')),
                            'microfono' => array('label' => __('🎤 Microfono', 'aule-booking')),
                            'wifi' => array('label' => __('📶 Wi-Fi', 'aule-booking')),
                            'condizionatore' => array('label' => __('❄️ Aria Condizionata', 'aule-booking')),
                            'stampante' => array('label' => __('🖨️ Stampante', 'aule-booking'))
                        );

                        foreach ($attrezzature_disponibili as $key => $attrezzatura):
                            $checked = is_array($attrezzature) && in_array($key, $attrezzature);
                        ?>
                            <div class="attrezzatura-item <?php echo $checked ? 'selected' : ''; ?>">
                                <input type="checkbox"
                                       id="attrezzatura_<?php echo $key; ?>"
                                       name="attrezzature[]"
                                       value="<?php echo esc_attr($key); ?>"
                                       <?php checked($checked); ?>>
                                <label for="attrezzatura_<?php echo $key; ?>">
                                    <?php echo esc_html($attrezzatura['label']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Immagini -->
                <div class="form-section">
                    <h3><?php _e('Immagini Aula', 'aule-booking'); ?></h3>

                    <div class="images-upload-container">
                        <button type="button" class="button upload-image-btn">
                            <span class="wp-icon wp-icon-image"></span>
                            <?php _e('Aggiungi Immagini', 'aule-booking'); ?>
                        </button>

                        <div id="images-preview" class="images-preview">
                            <?php if (!empty($immagini) && is_array($immagini)): ?>
                                <?php foreach ($immagini as $image_id): ?>
                                    <?php $image = wp_get_attachment_image_src($image_id, 'medium'); ?>
                                    <?php if ($image): ?>
                                        <div class="image-preview-item" data-id="<?php echo esc_attr($image_id); ?>">
                                            <img src="<?php echo esc_url($image[0]); ?>" alt="">
                                            <button type="button" class="remove-image" title="<?php _e('Rimuovi immagine', 'aule-booking'); ?>">
                                                <span class="wp-icon wp-icon-remove"></span>
                                            </button>
                                            <input type="hidden" name="immagini[]" value="<?php echo esc_attr($image_id); ?>">
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="image-preview-item add-image-placeholder">
                                <div class="add-image-btn">
                                    <span class="wp-icon wp-icon-add"></span>
                                    <small><?php _e('Clicca per aggiungere immagini', 'aule-booking'); ?></small>
                                </div>
                            </div>
                        </div>

                        <span class="help-text">
                            <?php _e('Aggiungi fino a 5 immagini dell\'aula. Formato consigliato: JPG, PNG. Dimensione massima: 2MB per immagine.', 'aule-booking'); ?>
                        </span>
                    </div>
                </div>

                <!-- Pulsanti Azione -->
                <div class="form-actions">
                    <button type="submit" class="button button-primary button-large">
                        <span class="wp-icon wp-icon-save"></span>
                        <?php echo esc_html($button_text); ?>
                    </button>

                    <a href="<?php echo admin_url('admin.php?page=aule-booking-aule'); ?>" class="button button-secondary button-large">
                        ← <?php _e('Torna alla Lista', 'aule-booking'); ?>
                    </a>

                    <?php if ($is_edit): ?>
                        <a href="<?php echo admin_url('admin.php?page=aule-booking-add-aula'); ?>" class="button button-large">
                            <span class="wp-icon wp-icon-add"></span>
                            <?php _e('Crea Nuova Aula', 'aule-booking'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Stili specifici per il form aula */
.form-section {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
}

.form-section h3 {
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
    color: #23282d;
    font-size: 1.1em;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 5px;
    color: #23282d;
}

.form-group label.required::after {
    content: ' *';
    color: #d63638;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: 8px 12px;
    border: 1px solid #8c8f94;
    border-radius: 3px;
    font-size: 14px;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 1px #2271b1;
    outline: none;
}

.help-text {
    font-size: 12px;
    color: #646970;
    margin-top: 5px;
    font-style: italic;
}

.attrezzature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.attrezzatura-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: #f6f7f7;
    border: 2px solid #dcdcde;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.attrezzatura-item:hover {
    background: #e5f3ff;
    border-color: #2271b1;
}

.attrezzatura-item.selected {
    background: #e5f3ff;
    border-color: #2271b1;
}

.attrezzatura-item input[type="checkbox"] {
    margin: 0;
}

.attrezzatura-item label {
    margin: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.images-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.image-preview-item {
    position: relative;
    aspect-ratio: 1;
    border: 2px dashed #dcdcde;
    border-radius: 4px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f6f7f7;
}

.image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #d63638;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.add-image-placeholder {
    cursor: pointer;
}

.add-image-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #646970;
}

.add-image-btn i {
    font-size: 24px;
}

.form-actions {
    text-align: left;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    gap: 10px;
    align-items: center;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .attrezzature-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Gestione click su attrezzature
    $('.attrezzatura-item').on('click', function() {
        var $item = $(this);
        var $checkbox = $item.find('input[type="checkbox"]');

        $checkbox.prop('checked', !$checkbox.prop('checked'));
        $item.toggleClass('selected', $checkbox.is(':checked'));
    });

    // Previeni il doppio click sul checkbox
    $('.attrezzatura-item input[type="checkbox"]').on('click', function(e) {
        e.stopPropagation();
        $(this).closest('.attrezzatura-item').toggleClass('selected', $(this).is(':checked'));
    });

    // Gestione placeholder immagine
    $('.add-image-placeholder').on('click', function() {
        $('.upload-image-btn').trigger('click');
    });
});
</script>