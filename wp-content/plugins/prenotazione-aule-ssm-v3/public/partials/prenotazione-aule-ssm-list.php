<?php
/**
 * Template per la lista aule
 *
 * @package Prenotazione_Aule_SSM
 * @since 2.1.2
 */

// Prevent direct access
if (!defined("ABSPATH")) {
    exit;
}
?>

<div class="pas-aule-list-container">
    <?php if (empty($aule)): ?>
        <div class="pas-no-aule">
            <p><?php _e("Nessuna aula disponibile al momento.", "prenotazione-aule-ssm"); ?></p>
        </div>
    <?php else: ?>
        <div class="pas-aule-grid">
            <?php foreach ($aule as $aula): 
                // Get attrezzature
                $attrezzature = maybe_unserialize($aula->attrezzature);
                if (!is_array($attrezzature)) {
                    $attrezzature = array();
                }
                
                // Attrezzature labels with icons
                $attrezzature_labels = array(
                    'proiettore' => '<span class="dashicons dashicons-desktop"></span> ' . __('Proiettore', 'prenotazione-aule-ssm'),
                    'lavagna' => '<span class="dashicons dashicons-clipboard"></span> ' . __('Lavagna', 'prenotazione-aule-ssm'),
                    'pc' => '<span class="dashicons dashicons-laptop"></span> ' . __('Computer', 'prenotazione-aule-ssm'),
                    'webcam' => '<span class="dashicons dashicons-video-alt3"></span> ' . __('Webcam', 'prenotazione-aule-ssm'),
                    'microfono' => '<span class="dashicons dashicons-microphone"></span> ' . __('Microfono', 'prenotazione-aule-ssm'),
                    'wifi' => '<span class="dashicons dashicons-networking"></span> ' . __('Wi-Fi', 'prenotazione-aule-ssm'),
                    'condizionatore' => '<span class="dashicons dashicons-cloud"></span> ' . __('Aria Condizionata', 'prenotazione-aule-ssm'),
                    'stampante' => '<span class="dashicons dashicons-printer"></span> ' . __('Stampante', 'prenotazione-aule-ssm')
                );
            ?>
            <div class="pas-aula-card <?php echo esc_attr(\"pas-aula-\" . $aula->stato); ?>">
                <?php if (!empty($aula->immagine_url)): ?>
                <div class="pas-aula-image">
                    <img src="<?php echo esc_url($aula->immagine_url); ?>" alt="<?php echo esc_attr($aula->nome_aula); ?>">
                </div>
                <?php endif; ?>
                
                <div class="pas-aula-content">
                    <div class="pas-aula-header">
                        <h3 class="pas-aula-title">
                            <span class="dashicons dashicons-building"></span>
                            <?php echo esc_html($aula->nome_aula); ?>
                        </h3>
                        <?php if ($aula->stato === 'attiva'): ?>
                            <span class="pas-badge pas-badge-active"><?php _e('Attiva', 'prenotazione-aule-ssm'); ?></span>
                        <?php elseif ($aula->stato === 'manutenzione'): ?>
                            <span class="pas-badge pas-badge-maintenance"><?php _e('Manutenzione', 'prenotazione-aule-ssm'); ?></span>
                        <?php else: ?>
                            <span class="pas-badge pas-badge-inactive"><?php _e('Non Disponibile', 'prenotazione-aule-ssm'); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if ($atts['show_details'] === 'true'): ?>
                    <div class="pas-aula-details">
                        <?php if (!empty($aula->descrizione)): ?>
                        <p class="pas-aula-description"><?php echo esc_html($aula->descrizione); ?></p>
                        <?php endif; ?>
                        
                        <div class="pas-aula-info">
                            <?php if ($aula->capienza > 0): ?>
                            <span class="pas-info-item">
                                <span class="dashicons dashicons-groups"></span>
                                <?php printf(__('%d persone', 'prenotazione-aule-ssm'), $aula->capienza); ?>
                            </span>
                            <?php endif; ?>

                            <?php if (!empty($aula->ubicazione)): ?>
                            <span class="pas-info-item">
                                <span class="dashicons dashicons-location"></span>
                                <?php echo esc_html($aula->ubicazione); ?>
                            </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($attrezzature)): ?>
                        <div class="pas-aula-facilities">
                            <?php foreach ($attrezzature as $attrezzatura): 
                                if (isset($attrezzature_labels[$attrezzatura])):
                            ?>
                                <span class="pas-facility-tag">
                                    <?php echo wp_kses_post($attrezzature_labels[$attrezzatura]); ?>
                                </span>
                            <?php 
                                endif;
                            endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($atts['show_booking_link'] === 'true' && $aula->stato === 'attiva'): ?>
                    <div class="pas-aula-actions">
                        <a href="<?php echo esc_url(add_query_arg('aula_id', $aula->id, get_permalink())); ?>" 
                           class="pas-btn pas-btn-primary">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php _e('Prenota Ora', 'prenotazione-aule-ssm'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
