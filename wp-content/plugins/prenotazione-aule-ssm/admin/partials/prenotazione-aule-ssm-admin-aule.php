<?php

/**
 * Template per la pagina "Tutte le Aule"
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
$filter_stato = !empty($_GET['filter_stato']) ? sanitize_text_field($_GET['filter_stato']) : '';
$filter_ubicazione = !empty($_GET['filter_ubicazione']) ? sanitize_text_field($_GET['filter_ubicazione']) : '';
$search_term = !empty($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// Applica filtri se presenti
$filters = array();
if ($filter_stato) {
    $filters['stato'] = $filter_stato;
}
if ($filter_ubicazione) {
    $filters['ubicazione'] = $filter_ubicazione;
}

// Ricarica dati con filtri se necessario
if (!empty($filters) || $search_term) {
    $database = new Prenotazione_Aule_SSM_Database();
    $aule = $database->get_aule($filters);

    // Filtro di ricerca aggiuntivo per nome/descrizione (lato PHP)
    if ($search_term) {
        $aule = array_filter($aule, function($aula) use ($search_term) {
            return stripos($aula->nome_aula, $search_term) !== false ||
                   stripos($aula->descrizione, $search_term) !== false ||
                   stripos($aula->ubicazione, $search_term) !== false;
        });
    }
}

// Ottieni ubicazioni uniche per il filtro
$ubicazioni_uniche = array();
if (!empty($aule)) {
    foreach ($aule as $aula) {
        if (!empty($aula->ubicazione) && !in_array($aula->ubicazione, $ubicazioni_uniche)) {
            $ubicazioni_uniche[] = $aula->ubicazione;
        }
    }
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-building"></span>
        <?php _e('Tutte le Aule', 'prenotazione-aule-ssm'); ?>
    </h1>

    <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-add-aula'); ?>" class="page-title-action">
        <span class="dashicons dashicons-plus-alt"></span>
        <?php _e('Aggiungi Nuova Aula', 'prenotazione-aule-ssm'); ?>
    </a>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                switch ($_GET['updated']) {
                    case 'aula':
                        _e('Aula salvata correttamente.', 'prenotazione-aule-ssm');
                        break;
                    case 'deleted':
                        _e('Aula eliminata correttamente.', 'prenotazione-aule-ssm');
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
            <p>
                <?php
                switch ($_GET['error']) {
                    case 'delete':
                        _e('Errore nell\'eliminazione dell\'aula.', 'prenotazione-aule-ssm');
                        break;
                    case 'not_found':
                        _e('Aula non trovata.', 'prenotazione-aule-ssm');
                        break;
                    default:
                        _e('Si è verificato un errore.', 'prenotazione-aule-ssm');
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="aule-list-container">
        <div class="aule-list-header">
            <h2><?php _e('Gestione Aule Studio', 'prenotazione-aule-ssm'); ?></h2>

            <!-- Filtri e Ricerca -->
            <div class="aule-filters">
                <form method="get" class="aule-filter-form">
                    <input type="hidden" name="page" value="prenotazione-aule-ssm-aule">

                    <select name="filter_stato" class="filter-aule" data-filter="stato">
                        <option value=""><?php _e('Tutti gli stati', 'prenotazione-aule-ssm'); ?></option>
                        <option value="attiva" <?php selected($filter_stato, 'attiva'); ?>><?php _e('Attiva', 'prenotazione-aule-ssm'); ?></option>
                        <option value="non_disponibile" <?php selected($filter_stato, 'non_disponibile'); ?>><?php _e('Non Disponibile', 'prenotazione-aule-ssm'); ?></option>
                        <option value="manutenzione" <?php selected($filter_stato, 'manutenzione'); ?>><?php _e('In Manutenzione', 'prenotazione-aule-ssm'); ?></option>
                    </select>

                    <?php if (!empty($ubicazioni_uniche)): ?>
                    <select name="filter_ubicazione" class="filter-aule" data-filter="ubicazione">
                        <option value=""><?php _e('Tutte le ubicazioni', 'prenotazione-aule-ssm'); ?></option>
                        <?php foreach ($ubicazioni_uniche as $ubicazione): ?>
                            <option value="<?php echo esc_attr($ubicazione); ?>" <?php selected($filter_ubicazione, $ubicazione); ?>>
                                <?php echo esc_html($ubicazione); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>

                    <input type="search"
                           name="search"
                           value="<?php echo esc_attr($search_term); ?>"
                           placeholder="<?php _e('Cerca aule...', 'prenotazione-aule-ssm'); ?>"
                           class="search-bookings">

                    <button type="submit" class="button"><?php _e('Filtra', 'prenotazione-aule-ssm'); ?></button>

                    <?php if ($filter_stato || $filter_ubicazione || $search_term): ?>
                    <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-aule'); ?>" class="button">
                        <?php _e('Reset', 'prenotazione-aule-ssm'); ?>
                    </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if (empty($aule)): ?>
            <!-- Stato vuoto -->
            <div class="no-data">
                <span class="dashicons dashicons-building" style="font-size: 48px; opacity: 0.3;"></span>
                <h3><?php _e('Nessuna aula trovata', 'prenotazione-aule-ssm'); ?></h3>
                <p><?php _e('Nessuna aula corrisponde ai criteri di ricerca.', 'prenotazione-aule-ssm'); ?></p>
                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-add-aula'); ?>" class="button button-primary">
                    <span class="dashicons dashicons-plus-alt"></span>
                    <?php _e('Crea la prima aula', 'prenotazione-aule-ssm'); ?>
                </a>
            </div>
        <?php else: ?>
            <!-- Contatore risultati -->
            <div class="aule-results-info">
                <span class="aule-count">
                    <?php printf(__('Mostrando %d aule', 'prenotazione-aule-ssm'), count($aule)); ?>
                </span>
            </div>

            <!-- Lista Aule in formato Card -->
            <div class="aule-grid">
                <?php foreach ($aule as $aula): ?>
                    <?php
                    $attrezzature = !empty($aula->attrezzature) ? maybe_unserialize($aula->attrezzature) : array();
                    $immagini = !empty($aula->immagini) ? maybe_unserialize($aula->immagini) : array();
                    $immagine_principale = !empty($immagini) ? wp_get_attachment_image_src($immagini[0], 'medium') : false;
                    ?>

                    <div class="aula-card" data-stato="<?php echo esc_attr($aula->stato); ?>" data-ubicazione="<?php echo esc_attr($aula->ubicazione); ?>">
                        <!-- Immagine Aula -->
                        <div class="aula-card-image">
                            <?php if ($immagine_principale): ?>
                                <img src="<?php echo esc_url($immagine_principale[0]); ?>" alt="<?php echo esc_attr($aula->nome_aula); ?>">
                            <?php else: ?>
                                <div class="aula-card-placeholder">
                                    <span class="dashicons dashicons-building"></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Contenuto Card -->
                        <div class="aula-card-content">
                            <div class="aula-card-title">
                                <h3><?php echo esc_html($aula->nome_aula); ?></h3>
                                <span class="aula-status <?php echo esc_attr($aula->stato); ?>">
                                    <?php
                                    switch ($aula->stato) {
                                        case 'attiva':
                                            _e('Attiva', 'prenotazione-aule-ssm');
                                            break;
                                        case 'non_disponibile':
                                            _e('Non Disponibile', 'prenotazione-aule-ssm');
                                            break;
                                        case 'manutenzione':
                                            _e('Manutenzione', 'prenotazione-aule-ssm');
                                            break;
                                    }
                                    ?>
                                </span>
                            </div>

                            <?php if (!empty($aula->descrizione)): ?>
                            <p class="aula-description">
                                <?php echo wp_trim_words(esc_html($aula->descrizione), 15, '...'); ?>
                            </p>
                            <?php endif; ?>

                            <!-- Meta informazioni -->
                            <div class="aula-card-meta">
                                <span title="<?php _e('Capienza', 'prenotazione-aule-ssm'); ?>">
                                    <span class="dashicons dashicons-groups"></span>
                                    <?php printf(__('%d persone', 'prenotazione-aule-ssm'), $aula->capienza); ?>
                                </span>

                                <?php if (!empty($aula->ubicazione)): ?>
                                <span title="<?php _e('Ubicazione', 'prenotazione-aule-ssm'); ?>">
                                    <span class="dashicons dashicons-location"></span>
                                    <?php echo esc_html($aula->ubicazione); ?>
                                </span>
                                <?php endif; ?>
                            </div>

                            <!-- Attrezzature -->
                            <?php if (!empty($attrezzature)): ?>
                            <div class="aula-card-facilities">
                                <?php
                                $attrezzature_labels = array(
                                    'proiettore' => '<span class="dashicons dashicons-desktop"></span> ' . __('Proiettore', 'prenotazione-aule-ssm'),
                                    'lavagna' => '<span class="dashicons dashicons-clipboard"></span> ' . __('Lavagna', 'prenotazione-aule-ssm'),
                                    'pc' => '<span class="dashicons dashicons-laptop"></span> ' . __('Computer', 'prenotazione-aule-ssm'),
                                    'webcam' => '<span class="dashicons dashicons-video-alt3"></span> ' . __('Webcam', 'prenotazione-aule-ssm'),
                                    'microfono' => '<span class="dashicons dashicons-microphone"></span> ' . __('Microfono', 'prenotazione-aule-ssm'),
                                    'wifi' => '<span class="dashicons dashicons-wifi"></span> ' . __('Wi-Fi', 'prenotazione-aule-ssm'),
                                    'condizionatore' => '<span class="dashicons dashicons-cloud"></span> ' . __('Aria Condizionata', 'prenotazione-aule-ssm'),
                                    'stampante' => '<span class="dashicons dashicons-printer"></span> ' . __('Stampante', 'prenotazione-aule-ssm')
                                );

                                foreach ($attrezzature as $attrezzatura):
                                    if (isset($attrezzature_labels[$attrezzatura])):
                                ?>
                                    <span class="facility-tag">
                                        <?php echo esc_html($attrezzature_labels[$attrezzatura]); ?>
                                    </span>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                            <?php endif; ?>

                            <!-- Shortcode Section -->
                            <div class="aula-shortcode-section">
                                <label class="shortcode-label"><?php _e('Shortcode per questa aula:', 'prenotazione-aule-ssm'); ?></label>
                                <div class="shortcode-container">
                                    <input type="text" readonly value="[prenotazione_aule_ssm_new_calendar aula_id=&quot;<?php echo $aula->id; ?>&quot;]"
                                           class="shortcode-input" id="shortcode-<?php echo $aula->id; ?>">
                                    <button type="button" class="button button-secondary copy-shortcode-btn"
                                            data-shortcode="[prenotazione_aule_ssm_new_calendar aula_id=&quot;<?php echo $aula->id; ?>&quot;]"
                                            data-aula-name="<?php echo esc_attr($aula->nome_aula); ?>">
                                        <span class="dashicons dashicons-clipboard"></span> <?php _e('Copia', 'prenotazione-aule-ssm'); ?>
                                    </button>
                                </div>
                                <small class="shortcode-help">
                                    <?php _e('Copia e incolla questo shortcode in qualsiasi pagina o post per mostrare il calendario di prenotazione con selezione multi-slot.', 'prenotazione-aule-ssm'); ?>
                                </small>
                            </div>

                            <!-- Azioni -->
                            <div class="aula-card-actions">
                                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-add-aula&edit=1&id=' . $aula->id); ?>"
                                   class="button button-primary button-small">
                                    <span class="dashicons dashicons-edit"></span>
                                    <?php _e('Modifica', 'prenotazione-aule-ssm'); ?>
                                </a>

                                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-slot&aula_id=' . $aula->id); ?>"
                                   class="button button-secondary button-small">
                                    <span class="dashicons dashicons-admin-tools"></span>
                                    <?php _e('Slot', 'prenotazione-aule-ssm'); ?>
                                </a>

                                <a href="<?php echo admin_url('admin.php?page=prenotazione-aule-ssm-prenotazioni&filter_aula_id=' . $aula->id); ?>"
                                   class="button button-secondary button-small">
                                    <span class="dashicons dashicons-calendar-alt"></span> <?php _e('Prenotazioni', 'prenotazione-aule-ssm'); ?>
                                </a>

                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=prenotazione_aule_ssm_delete_aula&id=' . $aula->id), 'delete_aula_' . $aula->id); ?>"
                                   class="button button-secondary button-small delete-aula"
                                   data-aula-name="<?php echo esc_attr($aula->nome_aula); ?>"
                                   onclick="return confirm('<?php printf(esc_js(__('Sei sicuro di voler eliminare l\'aula \"%s\"? Tutte le prenotazioni associate verranno cancellate.', 'prenotazione-aule-ssm')), esc_js($aula->nome_aula)); ?>')">
                                    <span class="dashicons dashicons-dismiss"></span>
                                    <?php _e('Elimina', 'prenotazione-aule-ssm'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Azioni di massa (future) -->
            <div class="bulk-actions-container" style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px; display: none;">
                <p><strong><?php _e('Azioni di massa:', 'prenotazione-aule-ssm'); ?></strong></p>
                <select class="bulk-actions">
                    <option value=""><?php _e('Seleziona azione', 'prenotazione-aule-ssm'); ?></option>
                    <option value="activate"><?php _e('Attiva', 'prenotazione-aule-ssm'); ?></option>
                    <option value="deactivate"><?php _e('Disattiva', 'prenotazione-aule-ssm'); ?></option>
                    <option value="maintenance"><?php _e('In manutenzione', 'prenotazione-aule-ssm'); ?></option>
                </select>
                <button class="button bulk-action-apply"><?php _e('Applica', 'prenotazione-aule-ssm'); ?></button>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Stili specifici per la lista aule */
.aule-results-info {
    padding: 10px 24px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    font-size: 14px;
    color: #646970;
}

.aule-filter-form {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.aule-filter-form select,
.aule-filter-form input[type="search"] {
    padding: 6px 10px;
    border: 1px solid #ccd0d4;
    border-radius: 3px;
    font-size: 13px;
}

.aule-filter-form input[type="search"] {
    min-width: 200px;
}

.aula-card-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #adb5bd;
    font-size: 2.5em;
}

.aula-description {
    color: #646970;
    font-size: 13px;
    line-height: 1.4;
    margin: 10px 0;
}

.aule-card-image img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

/* Shortcode Section */
.aula-shortcode-section {
    margin: 15px 0;
    padding: 12px;
    background: #f8f9fa;
    border: 1px solid #e2e4e7;
    border-radius: 6px;
}

.shortcode-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #1d2327;
    font-size: 12px;
}

.shortcode-container {
    display: flex;
    gap: 8px;
    align-items: center;
}

.shortcode-input {
    flex: 1;
    font-family: monospace;
    font-size: 11px;
    padding: 6px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    color: #333;
    user-select: all;
}

.copy-shortcode-btn {
    white-space: nowrap;
    font-size: 11px !important;
    padding: 6px 10px !important;
    height: auto !important;
    line-height: 1.2 !important;
}

.copy-shortcode-btn:hover {
    background: #0071a1 !important;
    border-color: #0071a1 !important;
    color: #fff !important;
}

.copy-shortcode-btn.copied {
    background: #46b450 !important;
    border-color: #46b450 !important;
    color: #fff !important;
}

.shortcode-help {
    display: block;
    margin-top: 6px;
    color: #646970;
    font-style: italic;
    line-height: 1.3;
}

/* Responsive per mobile */
@media (max-width: 768px) {
    .aule-filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .aule-filter-form select,
    .aule-filter-form input[type="search"] {
        width: 100%;
        margin-bottom: 10px;
    }

    .aula-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .aula-card-actions .button {
        text-align: center;
        font-size: 11px;
        padding: 6px 8px;
    }

    .shortcode-container {
        flex-direction: column;
        align-items: stretch;
        gap: 6px;
    }

    .shortcode-input {
        font-size: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione copia shortcode
    document.querySelectorAll('.copy-shortcode-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const shortcode = this.getAttribute('data-shortcode');
            const aulaName = this.getAttribute('data-aula-name');

            // Usa l'API Clipboard moderna se disponibile
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(shortcode).then(function() {
                    showCopyFeedback(btn, aulaName);
                }).catch(function() {
                    // Fallback se clipboard API fallisce
                    fallbackCopyShortcode(shortcode, btn, aulaName);
                });
            } else {
                // Fallback per browser più vecchi
                fallbackCopyShortcode(shortcode, btn, aulaName);
            }
        });
    });

    // Funzione fallback per copia shortcode
    function fallbackCopyShortcode(shortcode, btn, aulaName) {
        const textArea = document.createElement('textarea');
        textArea.value = shortcode;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopyFeedback(btn, aulaName);
            } else {
                showCopyError(btn, aulaName);
            }
        } catch (err) {
            showCopyError(btn, aulaName);
        }

        document.body.removeChild(textArea);
    }

    // Mostra feedback positivo
    function showCopyFeedback(btn, aulaName) {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="dashicons dashicons-yes"></span> Copiato!';
        btn.classList.add('copied');
        btn.disabled = true;

        // Mostra notifica WordPress-style
        showAdminNotice('success', 'Shortcode copiato per: ' + aulaName);

        // Ripristina il pulsante dopo 2 secondi
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('copied');
            btn.disabled = false;
        }, 2000);
    }

    // Mostra errore
    function showCopyError(btn, aulaName) {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="dashicons dashicons-no"></span> Errore';
        btn.style.background = '#dc3545';
        btn.style.borderColor = '#dc3545';

        showAdminNotice('error', 'Errore nella copia dello shortcode per: ' + aulaName);

        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.style.background = '';
            btn.style.borderColor = '';
        }, 2000);
    }

    // Mostra notifica admin WordPress
    function showAdminNotice(type, message) {
        const notice = document.createElement('div');
        notice.className = 'notice notice-' + type + ' is-dismissible';
        notice.innerHTML = '<p><strong>' + message + '</strong></p>';

        // Inserisce la notifica dopo l'header
        const header = document.querySelector('.wp-heading-inline');
        if (header && header.parentNode) {
            header.parentNode.insertBefore(notice, header.nextSibling);

            // Auto-dismiss dopo 4 secondi
            setTimeout(function() {
                if (notice.parentNode) {
                    notice.remove();
                }
            }, 4000);
        }
    }

    // Gestione selezione automatica shortcode al click sull'input
    document.querySelectorAll('.shortcode-input').forEach(function(input) {
        input.addEventListener('click', function() {
            this.select();
        });

        input.addEventListener('focus', function() {
            this.select();
        });
    });
});
jQuery(document).ready(function($) {
    // Auto-submit filtri quando cambiano
    $('.filter-aule').on('change', function() {
        $(this).closest('form').submit();
    });

    // Aggiorna contatore risultati quando si filtra localmente
    function updateCounter() {
        var visibleCards = $('.aula-card:visible').length;
        var totalCards = $('.aula-card').length;

        $('.aule-count').text('<?php _e('Mostrando', 'prenotazione-aule-ssm'); ?> ' + visibleCards + ' <?php _e('aule', 'prenotazione-aule-ssm'); ?>');
    }

    // Evidenzia termine di ricerca nei risultati
    <?php if ($search_term): ?>
    var searchTerm = '<?php echo esc_js($search_term); ?>';
    if (searchTerm) {
        $('.aula-card').each(function() {
            var content = $(this).html();
            var regex = new RegExp('(' + searchTerm + ')', 'gi');
            var highlighted = content.replace(regex, '<mark>$1</mark>');
            $(this).html(highlighted);
        });
    }
    <?php endif; ?>
});
</script>