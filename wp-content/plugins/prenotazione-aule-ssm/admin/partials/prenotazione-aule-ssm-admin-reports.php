<?php

/**
 * Template per la pagina dei Report dell'amministrazione
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/admin/partials
 */

// Se questo file viene chiamato direttamente, termina l'esecuzione
if (!defined('WPINC')) {
    die;
}

// Ottieni l'istanza del database
global $wpdb;

// Parametri per i filtri
$periodo = isset($_GET['periodo']) ? sanitize_text_field($_GET['periodo']) : '30';
$aula_id = isset($_GET['aula_id']) ? intval($_GET['aula_id']) : '';
$stato = isset($_GET['stato']) ? sanitize_text_field($_GET['stato']) : '';

// Calcola le date per il periodo selezionato
$end_date = current_time('Y-m-d');
switch ($periodo) {
    case '7':
        $start_date = date('Y-m-d', strtotime('-7 days'));
        break;
    case '30':
        $start_date = date('Y-m-d', strtotime('-30 days'));
        break;
    case '90':
        $start_date = date('Y-m-d', strtotime('-90 days'));
        break;
    case '365':
        $start_date = date('Y-m-d', strtotime('-1 year'));
        break;
    case 'custom':
        $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
        $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : current_time('Y-m-d');
        break;
    default:
        $start_date = date('Y-m-d', strtotime('-30 days'));
}

// Query base per le statistiche
$where_conditions = array("p.data_prenotazione BETWEEN %s AND %s");
$query_params = array($start_date, $end_date);

if (!empty($aula_id)) {
    $where_conditions[] = "p.aula_id = %d";
    $query_params[] = $aula_id;
}

if (!empty($stato)) {
    $where_conditions[] = "p.stato = %s";
    $query_params[] = $stato;
}

$where_clause = implode(' AND ', $where_conditions);

// Statistiche generali
$stats_query = $wpdb->prepare("
    SELECT
        COUNT(*) as totale_prenotazioni,
        COUNT(CASE WHEN p.stato = 'confermata' THEN 1 END) as confermate,
        COUNT(CASE WHEN p.stato = 'in_attesa' THEN 1 END) as in_attesa,
        COUNT(CASE WHEN p.stato = 'rifiutata' THEN 1 END) as rifiutate,
        COUNT(CASE WHEN p.stato = 'cancellata' THEN 1 END) as cancellate,
        COUNT(DISTINCT p.email_richiedente) as utenti_unici
    FROM {$wpdb->prefix}prenotazione_aule_ssm_prenotazioni p
    WHERE $where_clause
", $query_params);

$stats = $wpdb->get_row($stats_query);

// Top aule più prenotate
$top_aule_query = $wpdb->prepare("
    SELECT
        a.nome_aula,
        COUNT(p.id) as prenotazioni,
        COUNT(CASE WHEN p.stato = 'confermata' THEN 1 END) as confermate,
        ROUND(COUNT(CASE WHEN p.stato = 'confermata' THEN 1 END) * 100.0 / COUNT(p.id), 1) as tasso_conferma
    FROM {$wpdb->prefix}prenotazione_aule_ssm_aule a
    LEFT JOIN {$wpdb->prefix}prenotazione_aule_ssm_prenotazioni p ON a.id = p.aula_id
        AND p.data_prenotazione BETWEEN %s AND %s
    WHERE a.stato = 'attiva'
    GROUP BY a.id, a.nome_aula
    ORDER BY prenotazioni DESC
    LIMIT 10
", $start_date, $end_date);

$top_aule = $wpdb->get_results($top_aule_query);

// Prenotazioni per giorno (ultimi 30 giorni)
$daily_stats_query = $wpdb->prepare("
    SELECT
        p.data_prenotazione,
        COUNT(*) as prenotazioni,
        COUNT(CASE WHEN p.stato = 'confermata' THEN 1 END) as confermate
    FROM {$wpdb->prefix}prenotazione_aule_ssm_prenotazioni p
    WHERE p.data_prenotazione BETWEEN %s AND %s
    GROUP BY p.data_prenotazione
    ORDER BY p.data_prenotazione ASC
", $start_date, $end_date);

$daily_stats = $wpdb->get_results($daily_stats_query);

// Fasce orarie più richieste
$hourly_stats_query = $wpdb->prepare("
    SELECT
        HOUR(p.ora_inizio) as ora,
        COUNT(*) as prenotazioni,
        COUNT(CASE WHEN p.stato = 'confermata' THEN 1 END) as confermate
    FROM {$wpdb->prefix}prenotazione_aule_ssm_prenotazioni p
    WHERE $where_clause
    GROUP BY HOUR(p.ora_inizio)
    ORDER BY prenotazioni DESC
", $query_params);

$hourly_stats = $wpdb->get_results($hourly_stats_query);

// Ottieni tutte le aule per il filtro
$aule = $wpdb->get_results("
    SELECT id, nome_aula
    FROM {$wpdb->prefix}prenotazione_aule_ssm_aule
    WHERE stato = 'attiva'
    ORDER BY nome_aula
");

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        📊 <?php _e('Report e Statistiche', 'prenotazione-aule-ssm'); ?>
    </h1>

    <!-- Filtri Report -->
    <div class="report-filters" style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ddd; border-radius: 5px;">
        <form method="get" action="">
            <input type="hidden" name="page" value="prenotazione-aule-ssm-reports">

            <div style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
                <div>
                    <label for="periodo"><?php _e('Periodo', 'prenotazione-aule-ssm'); ?>:</label>
                    <select name="periodo" id="periodo" style="margin-top: 5px;">
                        <option value="7" <?php selected($periodo, '7'); ?>><?php _e('Ultimi 7 giorni', 'prenotazione-aule-ssm'); ?></option>
                        <option value="30" <?php selected($periodo, '30'); ?>><?php _e('Ultimi 30 giorni', 'prenotazione-aule-ssm'); ?></option>
                        <option value="90" <?php selected($periodo, '90'); ?>><?php _e('Ultimi 3 mesi', 'prenotazione-aule-ssm'); ?></option>
                        <option value="365" <?php selected($periodo, '365'); ?>><?php _e('Ultimo anno', 'prenotazione-aule-ssm'); ?></option>
                        <option value="custom" <?php selected($periodo, 'custom'); ?>><?php _e('Periodo personalizzato', 'prenotazione-aule-ssm'); ?></option>
                    </select>
                </div>

                <div id="custom-dates" style="<?php echo $periodo === 'custom' ? '' : 'display: none;'; ?>">
                    <label for="start_date"><?php _e('Da', 'prenotazione-aule-ssm'); ?>:</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo esc_attr($start_date); ?>" style="margin: 5px;">
                    <label for="end_date"><?php _e('A', 'prenotazione-aule-ssm'); ?>:</label>
                    <input type="date" name="end_date" id="end_date" value="<?php echo esc_attr($end_date); ?>" style="margin: 5px;">
                </div>

                <div>
                    <label for="aula_id"><?php _e('Aula', 'prenotazione-aule-ssm'); ?>:</label>
                    <select name="aula_id" id="aula_id" style="margin-top: 5px;">
                        <option value=""><?php _e('Tutte le aule', 'prenotazione-aule-ssm'); ?></option>
                        <?php foreach ($aule as $aula): ?>
                            <option value="<?php echo $aula->id; ?>" <?php selected($aula_id, $aula->id); ?>>
                                <?php echo esc_html($aula->nome_aula); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="stato"><?php _e('Stato', 'prenotazione-aule-ssm'); ?>:</label>
                    <select name="stato" id="stato" style="margin-top: 5px;">
                        <option value=""><?php _e('Tutti gli stati', 'prenotazione-aule-ssm'); ?></option>
                        <option value="in_attesa" <?php selected($stato, 'in_attesa'); ?>><?php _e('In Attesa', 'prenotazione-aule-ssm'); ?></option>
                        <option value="confermata" <?php selected($stato, 'confermata'); ?>><?php _e('Confermata', 'prenotazione-aule-ssm'); ?></option>
                        <option value="rifiutata" <?php selected($stato, 'rifiutata'); ?>><?php _e('Rifiutata', 'prenotazione-aule-ssm'); ?></option>
                        <option value="cancellata" <?php selected($stato, 'cancellata'); ?>><?php _e('Cancellata', 'prenotazione-aule-ssm'); ?></option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="button button-primary">
                        🔍 <?php _e('Filtra', 'prenotazione-aule-ssm'); ?>
                    </button>
                </div>

                <div>
                    <button type="button" class="button" onclick="exportReport()">
                        📊 <?php _e('Esporta CSV', 'prenotazione-aule-ssm'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Cards Statistiche Principali -->
    <div class="stats-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
        <div class="stats-card" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2em; color: #0073aa; margin-bottom: 10px;">📅</div>
            <h3 style="margin: 0; font-size: 2.5em; color: #23282d;"><?php echo number_format($stats->totale_prenotazioni); ?></h3>
            <p style="margin: 5px 0 0; color: #666;"><?php _e('Prenotazioni Totali', 'prenotazione-aule-ssm'); ?></p>
        </div>

        <div class="stats-card" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2em; color: #46b450; margin-bottom: 10px;">✅</div>
            <h3 style="margin: 0; font-size: 2.5em; color: #23282d;"><?php echo number_format($stats->confermate); ?></h3>
            <p style="margin: 5px 0 0; color: #666;"><?php _e('Confermate', 'prenotazione-aule-ssm'); ?></p>
        </div>

        <div class="stats-card" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2em; color: #ffb900; margin-bottom: 10px;">⏳</div>
            <h3 style="margin: 0; font-size: 2.5em; color: #23282d;"><?php echo number_format($stats->in_attesa); ?></h3>
            <p style="margin: 5px 0 0; color: #666;"><?php _e('In Attesa', 'prenotazione-aule-ssm'); ?></p>
        </div>

        <div class="stats-card" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2em; color: #dc3232; margin-bottom: 10px;">❌</div>
            <h3 style="margin: 0; font-size: 2.5em; color: #23282d;"><?php echo number_format($stats->rifiutate + $stats->cancellate); ?></h3>
            <p style="margin: 5px 0 0; color: #666;"><?php _e('Rifiutate/Cancellate', 'prenotazione-aule-ssm'); ?></p>
        </div>

        <div class="stats-card" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2em; color: #826eb4; margin-bottom: 10px;">👥</div>
            <h3 style="margin: 0; font-size: 2.5em; color: #23282d;"><?php echo number_format($stats->utenti_unici); ?></h3>
            <p style="margin: 5px 0 0; color: #666;"><?php _e('Utenti Unici', 'prenotazione-aule-ssm'); ?></p>
        </div>

        <div class="stats-card" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="font-size: 2em; color: #00a32a; margin-bottom: 10px;">📊</div>
            <h3 style="margin: 0; font-size: 2.5em; color: #23282d;">
                <?php
                $tasso_conferma = $stats->totale_prenotazioni > 0
                    ? round(($stats->confermate * 100) / $stats->totale_prenotazioni, 1)
                    : 0;
                echo $tasso_conferma . '%';
                ?>
            </h3>
            <p style="margin: 5px 0 0; color: #666;"><?php _e('Tasso di Conferma', 'prenotazione-aule-ssm'); ?></p>
        </div>
    </div>

    <!-- Grafici e Tabelle -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">

        <!-- Top Aule -->
        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h3 style="margin-top: 0;">🏢 <?php _e('Aule Più Richieste', 'prenotazione-aule-ssm'); ?></h3>
            <div class="top-aule-chart">
                <?php if (!empty($top_aule)): ?>
                    <?php foreach ($top_aule as $aula): ?>
                        <div style="margin-bottom: 15px; padding: 10px; background: #f9f9f9; border-radius: 5px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong><?php echo esc_html($aula->nome_aula); ?></strong>
                                <span style="background: #0073aa; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9em;">
                                    <?php echo $aula->prenotazioni; ?> prenotazioni
                                </span>
                            </div>
                            <div style="margin-top: 8px; font-size: 0.9em; color: #666;">
                                <?php printf(__('%d confermate (%s%% tasso conferma)', 'prenotazione-aule-ssm'), $aula->confermate, $aula->tasso_conferma); ?>
                            </div>
                            <div style="margin-top: 8px; background: #e5e5e5; height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: #46b450; height: 100%; width: <?php echo min(100, ($aula->prenotazioni / max(1, $top_aule[0]->prenotazioni)) * 100); ?>%; border-radius: 4px;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666; font-style: italic;"><?php _e('Nessun dato disponibile per il periodo selezionato.', 'prenotazione-aule-ssm'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fasce Orarie -->
        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h3 style="margin-top: 0;">⏰ <?php _e('Fasce Orarie Più Richieste', 'prenotazione-aule-ssm'); ?></h3>
            <div class="hourly-chart">
                <?php if (!empty($hourly_stats)): ?>
                    <?php foreach ($hourly_stats as $hour): ?>
                        <div style="margin-bottom: 12px; display: flex; align-items: center;">
                            <div style="width: 80px; font-weight: bold; text-align: right; margin-right: 15px;">
                                <?php printf('%02d:00', $hour->ora); ?>
                            </div>
                            <div style="flex: 1; background: #e5e5e5; height: 25px; border-radius: 12px; overflow: hidden; position: relative;">
                                <div style="background: linear-gradient(90deg, #0073aa, #46b450); height: 100%; width: <?php echo min(100, ($hour->prenotazioni / max(1, $hourly_stats[0]->prenotazioni)) * 100); ?>%; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: white; font-weight: bold; font-size: 0.85em;">
                                        <?php echo $hour->prenotazioni; ?>
                                    </span>
                                </div>
                            </div>
                            <div style="margin-left: 10px; font-size: 0.9em; color: #666;">
                                (<?php echo $hour->confermate; ?> conf.)
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666; font-style: italic;"><?php _e('Nessun dato disponibile per il periodo selezionato.', 'prenotazione-aule-ssm'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Grafico Andamento Giornaliero -->
    <?php if (!empty($daily_stats)): ?>
    <div style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ddd; border-radius: 8px;">
        <h3 style="margin-top: 0;">📈 <?php _e('Andamento Prenotazioni', 'prenotazione-aule-ssm'); ?></h3>
        <div style="height: 300px; display: flex; align-items: end; gap: 3px; padding: 10px; background: #f9f9f9; border-radius: 5px; overflow-x: auto;">
            <?php
            $max_daily = max(array_map(function($day) { return $day->prenotazioni; }, $daily_stats));
            foreach ($daily_stats as $day):
                $height = $max_daily > 0 ? ($day->prenotazioni / $max_daily) * 250 : 0;
            ?>
            <div style="display: flex; flex-direction: column; align-items: center; min-width: 60px;">
                <div style="background: linear-gradient(180deg, #0073aa, #46b450); width: 20px; height: <?php echo $height; ?>px; border-radius: 3px 3px 0 0; margin-bottom: 5px; position: relative;" title="<?php echo date_i18n('d/m/Y', strtotime($day->data_prenotazione)) . ': ' . $day->prenotazioni . ' prenotazioni, ' . $day->confermate . ' confermate'; ?>">
                    <?php if ($day->prenotazioni > 0): ?>
                    <span style="position: absolute; top: -25px; left: 50%; transform: translateX(-50%); background: #333; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75em; white-space: nowrap;">
                        <?php echo $day->prenotazioni; ?>
                    </span>
                    <?php endif; ?>
                </div>
                <div style="font-size: 0.7em; color: #666; text-align: center; writing-mode: vertical-rl; text-orientation: mixed;">
                    <?php echo date_i18n('d/m', strtotime($day->data_prenotazione)); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="margin-top: 15px; display: flex; gap: 20px; justify-content: center;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <div style="width: 20px; height: 15px; background: linear-gradient(90deg, #0073aa, #46b450); border-radius: 3px;"></div>
                <span style="font-size: 0.9em;"><?php _e('Prenotazioni per giorno', 'prenotazione-aule-ssm'); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Info Periodo -->
    <div style="background: #f0f6fc; padding: 15px; margin: 20px 0; border: 1px solid #c3dcf0; border-radius: 5px;">
        <p style="margin: 0; color: #0c5890;">
            <strong>📅 <?php _e('Periodo analizzato', 'prenotazione-aule-ssm'); ?>:</strong>
            <?php echo date_i18n('d/m/Y', strtotime($start_date)); ?> - <?php echo date_i18n('d/m/Y', strtotime($end_date)); ?>
            <?php if (!empty($aula_id)): ?>
                | <strong><?php _e('Aula', 'prenotazione-aule-ssm'); ?>:</strong> <?php echo esc_html($wpdb->get_var($wpdb->prepare("SELECT nome_aula FROM {$wpdb->prefix}prenotazione_aule_ssm_aule WHERE id = %d", $aula_id))); ?>
            <?php endif; ?>
            <?php if (!empty($stato)): ?>
                | <strong><?php _e('Stato', 'prenotazione-aule-ssm'); ?>:</strong> <?php echo esc_html(ucfirst($stato)); ?>
            <?php endif; ?>
        </p>
    </div>
</div>

<script>
// Toggle date picker personalizzato
document.getElementById('periodo').addEventListener('change', function() {
    const customDates = document.getElementById('custom-dates');
    if (this.value === 'custom') {
        customDates.style.display = 'block';
    } else {
        customDates.style.display = 'none';
    }
});

// Funzione export CSV
function exportReport() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    params.set('action', 'export_reports');

    const url = ajaxurl + '?' + params.toString();

    // Create temporary link per download
    const link = document.createElement('a');
    link.href = url;
    link.download = 'report-prenotazioni-' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Responsive mobile
if (window.innerWidth < 768) {
    document.addEventListener('DOMContentLoaded', function() {
        const statsCards = document.querySelector('.stats-cards');
        if (statsCards) {
            statsCards.style.gridTemplateColumns = '1fr';
        }

        const chartsGrid = document.querySelector('[style*="grid-template-columns: 1fr 1fr"]');
        if (chartsGrid) {
            chartsGrid.style.gridTemplateColumns = '1fr';
        }
    });
}
</script>

<style>
.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
    transition: all 0.3s ease;
}

.top-aule-chart > div:hover {
    background: #f0f6fc !important;
    border-left: 4px solid #0073aa;
    transition: all 0.2s ease;
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr !important;
    }

    .report-filters > form > div {
        flex-direction: column !important;
        gap: 10px !important;
    }

    .report-filters label {
        font-weight: bold;
    }

    .report-filters select,
    .report-filters input {
        width: 100% !important;
        max-width: none !important;
    }
}

@media print {
    .report-filters,
    .button {
        display: none !important;
    }

    .stats-cards {
        break-inside: avoid;
    }
}
</style>