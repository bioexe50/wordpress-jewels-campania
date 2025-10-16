<?php
/**
 * Plugin Name:       Mappa Aziende Campania
 * Plugin URI:        https://example.com/campania-companies-map
 * Description:       Un plugin per georeferenziare aziende della Campania su una mappa Leaflet/OpenStreetMap, con upload CSV, filtri per città e campo ricerca.
 * Version:           1.2.9
 * Author:            Gabriele Bernini
 * Author URI:        https://speaktoai.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       campania-companies-map
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants.
define( 'CAMPANIA_COMPANIES_MAP_VERSION', '1.2.9' );
define( 'CAMPANIA_COMPANIES_MAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAMPANIA_COMPANIES_MAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CAMPANIA_COMPANIES_MAP_DB_VERSION', '1.4' ); // db schema invariato
define( 'CAMPANIA_COMPANIES_MAP_NOMINATIM_DELAY', 1 ); // seconds between requests
// === FEAMPA: fix altezza mappa specifico ===
add_action('wp_head', function () {
    ?>
    <style id="feampa-map-height-fix">
      #campania-companies-map {
        height: 70vh !important;
        min-height: 520px !important;
        width: 100% !important;
        display: block !important;
        visibility: visible !important;
      }

      .elementor, .elementor-section, .elementor-container, .elementor-widget-container {
        overflow: visible !important;
      }

      .feampa-map-wrapper, #campania-companies-map-wrapper {
        display: block !important;
        min-height: 520px !important;
      }
    </style>
    <?php
}, 99);

/**
 * Helpers to get uploads asset paths (dir & url)
 */
function campania_companies_map_assets_dir() {
    $upload = wp_upload_dir();
    return trailingslashit( $upload['basedir'] ) . 'campania-companies-map/assets/';
}
function campania_companies_map_assets_url() {
    $upload = wp_upload_dir();
    return trailingslashit( $upload['baseurl'] ) . 'campania-companies-map/assets/';
}

/**
 * Activation hook.
 * - Creates/updates DB table (with UNIQUE on partita_iva).
 * - Sets default options.
 * - Generates asset files in uploads.
 */
function campania_companies_map_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';
    $charset_collate = $wpdb->get_charset_collate();

    // Create/Update table with UNIQUE on partita_iva (allows multiple NULLs)
    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        ragione_sociale varchar(255) NOT NULL,
        email varchar(255) DEFAULT NULL,
        pec varchar(255) DEFAULT NULL,
        sitoweb varchar(255) DEFAULT NULL,
        indirizzo varchar(255) NOT NULL,
        citta varchar(100) NOT NULL,
        provincia varchar(100) NOT NULL,
        telefono varchar(50) DEFAULT NULL,
        partita_iva varchar(50) DEFAULT NULL,
        prodotto varchar(255) DEFAULT NULL,
        settore varchar(100) DEFAULT NULL,
        latitude decimal(10,7) DEFAULT NULL,
        longitude decimal(10,7) DEFAULT NULL,
        geocoded tinyint(1) DEFAULT 0, -- 0: pending, 1: success, 2: failed
        date_added datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id),
        KEY citta (citta),
        KEY provincia (provincia),
        KEY settore (settore),
        KEY geocoded (geocoded),
        UNIQUE KEY uniq_piva (partita_iva)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    update_option( 'campania_companies_map_db_version', CAMPANIA_COMPANIES_MAP_DB_VERSION );

    // Set default map options if not already set.
    $options = get_option( 'campania_companies_map_options' );
    // Opzioni settori (nome -> icona) + icona default
    if ( ! get_option( 'campania_companies_map_sectors' ) ) {
        add_option( 'campania_companies_map_sectors', array(
            'default_icon' => 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png', // pin generico
            'items' => array(
                // Esempio:
                // array('name' => 'Edilizia', 'icon' => 'https://.../pin-edilizia.png'),
            ),
        ) );
    }

    // Generate assets once on activation
    campania_companies_map_create_asset_files();
}
register_activation_hook( __FILE__, 'campania_companies_map_activate' );
// Esegui dbDelta anche sugli aggiornamenti del plugin (non solo all'attivazione)
add_action( 'plugins_loaded', function () {
    $installed = get_option( 'campania_companies_map_db_version' );
    if ( $installed !== CAMPANIA_COMPANIES_MAP_DB_VERSION ) {
        // Riusa la routine che già crea/aggiorna la tabella con dbDelta
        if ( function_exists( 'campania_companies_map_activate' ) ) {
            campania_companies_map_activate();
        }
    }
} );
/**
 * Uninstall hook.
 */
function campania_companies_map_uninstall() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';

    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
    delete_option( 'campania_companies_map_db_version' );
    delete_option( 'campania_companies_map_options' );
    delete_option( 'campania_companies_map_last_nominatim_request' );

    // Remove uploads assets dir
    $dir = campania_companies_map_assets_dir();
    if ( is_dir( $dir ) ) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS ),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ( $files as $fileinfo ) {
            $todo = ( $fileinfo->isDir() ? 'rmdir' : 'unlink' );
            @$todo( $fileinfo->getRealPath() );
        }
        @rmdir( $dir );
        @rmdir( dirname( $dir ) ); // /campania-companies-map
    }
}
register_uninstall_hook( __FILE__, 'campania_companies_map_uninstall' );

/**
 * Load textdomain
 */
function campania_companies_map_load_textdomain() {
    load_plugin_textdomain( 'campania-companies-map', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'campania_companies_map_load_textdomain' );

/**
 * Admin menu
 */
function campania_companies_map_add_admin_menu() {
    add_menu_page(
        __( 'Mappa Aziende Campania', 'campania-companies-map' ),
        __( 'Mappa Campania', 'campania-companies-map' ),
        'manage_options',
        'campania-companies-map',
        'campania_companies_map_admin_page_dispatcher',
        'dashicons-location-alt',
        60
    );
}
add_action( 'admin_menu', 'campania_companies_map_add_admin_menu' );

/**
 * Dispatcher per gestire azioni (edit/delete) + pagina standard.
 */
function campania_companies_map_admin_page_dispatcher() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    // Handle single delete via GET (con nonce)
    if ( isset( $_GET['action'], $_GET['id'], $_GET['_wpnonce'] ) && $_GET['action'] === 'delete' ) {
        $id = absint( $_GET['id'] );
        if ( wp_verify_nonce( $_GET['_wpnonce'], 'campania_companies_map_delete_' . $id ) ) {
            $ok = campania_companies_map_delete_company( $id );
            if ( $ok ) {
                add_settings_error( 'campania_companies_map_messages', 'delete_one_success', __( 'Azienda eliminata.', 'campania-companies-map' ), 'success' );
            } else {
                add_settings_error( 'campania_companies_map_messages', 'delete_one_error', __( 'Errore durante l\'eliminazione.', 'campania-companies-map' ), 'error' );
            }
        } else {
            add_settings_error( 'campania_companies_map_messages', 'delete_one_nonce', __( 'Nonce non valido per l\'eliminazione.', 'campania-companies-map' ), 'error' );
        }
    }

    // Handle update form POST
    if ( isset( $_POST['campania_companies_map_edit_nonce'] ) && wp_verify_nonce( $_POST['campania_companies_map_edit_nonce'], 'campania_companies_map_edit_action' ) ) {
        $saved = campania_companies_map_handle_update_post();
        if ( is_wp_error( $saved ) ) {
            add_settings_error( 'campania_companies_map_messages', 'update_error', $saved->get_error_message(), 'error' );
        } else {
            add_settings_error( 'campania_companies_map_messages', 'update_success', __( 'Azienda aggiornata con successo.', 'campania-companies-map' ), 'success' );
            // Redirect dopo POST (PRG) per evitare resubmit
            wp_safe_redirect( admin_url( 'admin.php?page=campania-companies-map#companies' ) );
            exit;
        }
    }

    // Se si richiede l'edit, mostra il form; altrimenti la pagina classica a tab
    if ( isset( $_GET['action'], $_GET['id'] ) && $_GET['action'] === 'edit' ) {
        campania_companies_map_render_edit_form( absint( $_GET['id'] ) );
    } else {
        campania_companies_map_admin_page_callback(); // pagina “tabs” originale
    }
}

/**
 * Settings
 */
function campania_companies_map_settings_init() {
    register_setting(
        'campania_companies_map_settings_group',
        'campania_companies_map_options',
        'campania_companies_map_options_validate'
    );

    add_settings_section(
        'campania_companies_map_main_section',
        __( 'Impostazioni Mappa', 'campania-companies-map' ),
        null,
        'campania-companies-map'
    );

    add_settings_field(
        'campania_companies_map_tile_url',
        __( 'URL Tile Mappa', 'campania-companies-map' ),
        'campania_companies_map_tile_url_callback',
        'campania-companies-map',
        'campania_companies_map_main_section'
    );

    add_settings_field(
        'campania_companies_map_attribution',
        __( 'Attribuzione Mappa', 'campania-companies-map' ),
        'campania_companies_map_attribution_callback',
        'campania-companies-map',
        'campania_companies_map_main_section'
    );
}
add_action( 'admin_init', 'campania_companies_map_settings_init' );

function campania_companies_map_tile_url_callback() {
    $options = get_option( 'campania_companies_map_options' );
    $tile_url = isset( $options['tile_url'] ) ? esc_attr( $options['tile_url'] ) : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    echo '<input type="text" id="campania_companies_map_tile_url" name="campania_companies_map_options[tile_url]" value="' . $tile_url . '" class="regular-text" />';
    echo '<p class="description">' . esc_html__( 'URL del servizio di tile (usa {s},{z},{x},{y}).', 'campania-companies-map' ) . '</p>';
}
function campania_companies_map_attribution_callback() {
    $options = get_option( 'campania_companies_map_options' );
    $attribution = isset( $options['attribution'] ) ? esc_attr( $options['attribution'] ) : '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
    echo '<input type="text" id="campania_companies_map_attribution" name="campania_companies_map_options[attribution]" value="' . $attribution . '" class="regular-text" />';
}

/**
 * Sanitize options
 */
function campania_companies_map_options_validate( $input ) {
    $new_input = array();
    if ( isset( $input['tile_url'] ) ) {
        $new_input['tile_url'] = sanitize_text_field( $input['tile_url'] );
    }
    if ( isset( $input['attribution'] ) ) {
        $new_input['attribution'] = sanitize_text_field( $input['attribution'] );
    }
    return $new_input;
}

/**
 * Admin page (tabs standard)
 */
function campania_companies_map_admin_page_callback() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    settings_errors( 'campania_companies_map_messages' );

    if ( isset( $_POST['campania_companies_map_upload_csv_nonce'] ) && wp_verify_nonce( $_POST['campania_companies_map_upload_csv_nonce'], 'campania_companies_map_upload_csv' ) ) {
        campania_companies_map_handle_csv_upload();
    }
    if ( isset( $_POST['campania_companies_map_delete_all_nonce'] ) && wp_verify_nonce( $_POST['campania_companies_map_delete_all_nonce'], 'campania_companies_map_delete_all' ) ) {
        campania_companies_map_delete_all_companies();
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Mappa Aziende Campania', 'campania-companies-map' ); ?></h1>

        <h2 class="nav-tab-wrapper">
            <a href="#settings" class="nav-tab nav-tab-active"><?php esc_html_e( 'Impostazioni Mappa', 'campania-companies-map' ); ?></a>
            <a href="#sectors" class="nav-tab"><?php esc_html_e( 'Settori', 'campania-companies-map' ); ?></a>
            <a href="#upload" class="nav-tab"><?php esc_html_e( 'Carica Aziende', 'campania-companies-map' ); ?></a>
            <a href="#companies" class="nav-tab"><?php esc_html_e( 'Aziende Caricate', 'campania-companies-map' ); ?></a>
        </h2>

        <div id="settings" class="tab-content active">
            <form method="post" action="options.php">
                <?php
                settings_fields( 'campania_companies_map_settings_group' );
                do_settings_sections( 'campania-companies-map' );
                submit_button( __( 'Salva Impostazioni Mappa', 'campania-companies-map' ) );
                ?>
            </form>
        </div>
        <?php
// Salvataggio settori
if ( isset($_POST['campania_companies_map_sectors_nonce']) && wp_verify_nonce($_POST['campania_companies_map_sectors_nonce'], 'campania_companies_map_sectors_action') ) {
    $default_icon = isset($_POST['default_icon']) ? esc_url_raw( wp_unslash($_POST['default_icon']) ) : '';
    $names  = isset($_POST['sector_name']) ? (array) $_POST['sector_name'] : array();
    $icons  = isset($_POST['sector_icon']) ? (array) $_POST['sector_icon'] : array();
    $items = array();

    foreach ( $names as $i => $name ) {
        $name = sanitize_text_field( wp_unslash($name) );
        $icon = isset($icons[$i]) ? esc_url_raw( wp_unslash($icons[$i]) ) : '';
        if ( $name !== '' ) {
            $items[] = array( 'name' => $name, 'icon' => $icon );
        }
    }
    update_option( 'campania_companies_map_sectors', array(
        'default_icon' => $default_icon ?: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        'items'        => $items,
    ) );
    add_settings_error( 'campania_companies_map_messages', 'sectors_saved', __( 'Settori salvati.', 'campania-companies-map' ), 'success' );
}

$sectors_opt = get_option( 'campania_companies_map_sectors', array(
    'default_icon' => 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    'items' => array(),
) );
?>
<div id="sectors" class="tab-content">
    <h3><?php esc_html_e('Gestione Settori', 'campania-companies-map'); ?></h3>
    <form method="post">
        <?php wp_nonce_field('campania_companies_map_sectors_action', 'campania_companies_map_sectors_nonce'); ?>
        <p>
            <label for="default_icon"><strong><?php esc_html_e('Icona default (generica):', 'campania-companies-map'); ?></strong></label><br>
            <input type="url" id="default_icon" name="default_icon" class="regular-text" value="<?php echo esc_attr($sectors_opt['default_icon']); ?>" placeholder="https://.../marker.png">
            <span class="description"><?php esc_html_e('Usata quando il settore dell’azienda non è definito o non corrisponde.', 'campania-companies-map'); ?></span>
        </p>

        <table class="widefat striped" style="max-width:800px">
            <thead>
                <tr>
                    <th><?php esc_html_e('Nome settore', 'campania-companies-map'); ?></th>
                    <th><?php esc_html_e('URL icona/pin', 'campania-companies-map'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="sectors-rows">
                <?php if ( ! empty($sectors_opt['items']) ) : ?>
                    <?php foreach ( $sectors_opt['items'] as $row ) : ?>
                        <tr>
                            <td><input type="text" name="sector_name[]" value="<?php echo esc_attr($row['name']); ?>" class="regular-text"></td>
                            <td><input type="url" name="sector_icon[]" value="<?php echo esc_attr($row['icon']); ?>" class="regular-text" placeholder="https://.../pin.png"></td>
                            <td><button class="button remove-sector-row">&times;</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr class="sector-row-template" style="display:none;">
                    <td><input type="text" name="sector_name[]" value="" class="regular-text" placeholder="Es. Edilizia"></td>
                    <td><input type="url" name="sector_icon[]" value="" class="regular-text" placeholder="https://.../pin-edilizia.png"></td>
                    <td><button class="button remove-sector-row">&times;</button></td>
                </tr>
            </tbody>
        </table>
        <p><button id="add-sector-row" class="button"><?php esc_html_e('Aggiungi settore', 'campania-companies-map'); ?></button></p>

        <?php submit_button( __( 'Salva Settori', 'campania-companies-map' ) ); ?>
    </form>

    <script>
    (function($){
        $('#add-sector-row').on('click', function(e){
            e.preventDefault();
            var $tpl = $('.sector-row-template').clone(true).removeClass('sector-row-template').show();
            $('#sectors-rows').append($tpl);
        });
        $('#sectors-rows').on('click', '.remove-sector-row', function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
        });
    })(jQuery);
    </script>
</div>

        <div id="upload" class="tab-content">
            <h3><?php esc_html_e( 'Carica File CSV Aziende', 'campania-companies-map' ); ?></h3>
            <p><?php esc_html_e( 'Ordine colonne: Ragione Sociale, Email, Pec, Sitoweb, Indirizzo, Città, Provincia, Telefono, Partita IVA.', 'campania-companies-map' ); ?></p>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field( 'campania_companies_map_upload_csv', 'campania_companies_map_upload_csv_nonce' ); ?>
                <input type="file" name="campania_companies_map_csv_file" accept=".csv" />
                <?php submit_button( __( 'Carica CSV', 'campania-companies-map' ) ); ?>
            </form>

            <h3><?php esc_html_e( 'Geocodifica Aziende', 'campania-companies-map' ); ?></h3>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'campania_companies_map';
            $total_companies = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
            $ungeocoded_companies = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE geocoded = 0" );
            $failed_geocoded_companies = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE geocoded = 2" );
            ?>
            <p><?php printf( esc_html__( 'Totale aziende caricate: %d', 'campania-companies-map' ), $total_companies ); ?></p>
            <p><?php printf( esc_html__( 'Aziende da geocodificare: %d', 'campania-companies-map' ), $ungeocoded_companies ); ?></p>
            <p><?php printf( esc_html__( 'Aziende con geocodifica fallita: %d', 'campania-companies-map' ), $failed_geocoded_companies ); ?></p>
            <form method="post">
                <?php wp_nonce_field( 'campania_companies_map_geocode_action', 'campania_companies_map_geocode_nonce' ); ?>
                <button class="button button-primary" id="campania_companies_map_geocode_submit"><?php esc_html_e( 'Geocodifica Aziende Non Mappate', 'campania-companies-map' ); ?></button>
                <span id="campania-companies-map-geocode-status" style="margin-left: 10px;"></span>
            </form>
            <p class="description"><?php esc_html_e( 'Si usa Nominatim (OpenStreetMap). Presente ritardo tra richieste per rispettare la policy.', 'campania-companies-map' ); ?></p>
        </div>

        <div id="companies" class="tab-content">
            <h3><?php esc_html_e( 'Elenco Aziende Caricate', 'campania-companies-map' ); ?></h3>
            <form method="post" onsubmit="return confirm('<?php esc_attr_e( 'Sei sicuro di voler eliminare TUTTE le aziende? Azione irreversibile.', 'campania-companies-map' ); ?>');">
                <?php wp_nonce_field( 'campania_companies_map_delete_all', 'campania_companies_map_delete_all_nonce' ); ?>
                <?php submit_button( __( 'Elimina Tutte le Aziende', 'campania-companies-map' ), 'delete', 'campania_companies_map_delete_all_submit', false ); ?>
            </form>
            <?php campania_companies_map_display_companies_table(); ?>
        </div>
    </div>
    <?php
}

/**
 * Handle CSV upload with delimiter auto-detect and P.IVA upsert
 */
function campania_companies_map_handle_csv_upload() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( empty( $_FILES['campania_companies_map_csv_file']['name'] ) ) {
        add_settings_error( 'campania_companies_map_messages', 'csv_upload_error', __( 'Nessun file CSV selezionato.', 'campania-companies-map' ), 'error' );
        return;
    }

    $file = $_FILES['campania_companies_map_csv_file'];
    $file_type = wp_check_filetype( $file['name'] );

    if ( 'csv' !== $file_type['ext'] ) {
        add_settings_error( 'campania_companies_map_messages', 'csv_upload_error', __( 'Il file deve essere un CSV.', 'campania-companies-map' ), 'error' );
        return;
    }

    $upload_dir = wp_upload_dir();
    $target_path = $upload_dir['path'] . '/' . sanitize_file_name( $file['name'] );

    if ( move_uploaded_file( $file['tmp_name'], $target_path ) ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'campania_companies_map';
        $inserted_count = 0;
        $updated_count  = 0;
        $skipped_count  = 0;

        if ( ( $handle = fopen( $target_path, 'r' ) ) !== FALSE ) {

            // Auto-detect delimiter from first line
            $firstLine = fgets( $handle );
            $commaCount = substr_count( $firstLine, ',' );
            $semiCount  = substr_count( $firstLine, ';' );
            $delimiter  = ( $semiCount > $commaCount ) ? ';' : ',';
            rewind( $handle );

            // Read (and skip) header
            $header = fgetcsv( $handle, 0, $delimiter );

            while ( ( $data = fgetcsv( $handle, 0, $delimiter ) ) !== FALSE ) {
                if ( count( $data ) >= 9 ) {
                    $ragione_sociale = sanitize_text_field( $data[0] );
                    $email           = sanitize_email( $data[1] );
                    $pec             = sanitize_email( $data[2] );
                    $sitoweb         = esc_url_raw( $data[3] );
                    $indirizzo       = sanitize_text_field( $data[4] );
                    $citta           = sanitize_text_field( $data[5] );
                    $provincia       = sanitize_text_field( $data[6] );
                    $telefono        = sanitize_text_field( $data[7] );
                    $partita_iva     = sanitize_text_field( $data[8] );

                    if ( empty( $ragione_sociale ) || empty( $indirizzo ) || empty( $citta ) || empty( $provincia ) ) {
                        $skipped_count++;
                        continue;
                    }

                    // If P.IVA presente -> upsert (REPLACE), else INSERT
                    $data_arr = array(
                        'ragione_sociale' => $ragione_sociale,
                        'email'           => $email,
                        'pec'             => $pec,
                        'sitoweb'         => $sitoweb,
                        'indirizzo'       => $indirizzo,
                        'citta'           => $citta,
                        'provincia'       => $provincia,
                        'telefono'        => $telefono,
                        'partita_iva'     => ( $partita_iva !== '' ? $partita_iva : null ),
                        'geocoded'        => 0,
                        'date_added'      => current_time( 'mysql' ),
                    );
                    $format_arr = array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%s' );

                    if ( ! empty( $partita_iva ) ) {
                        $result = $wpdb->replace( $table_name, $data_arr, $format_arr );
                        if ( $result === false ) {
                            $skipped_count++;
                        } elseif ( $result === 1 ) {
                            $inserted_count++;
                        } else {
                            $updated_count++;
                        }
                    } else {
                        $result = $wpdb->insert( $table_name, $data_arr, $format_arr );
                        if ( $result ) {
                            $inserted_count++;
                        } else {
                            $skipped_count++;
                        }
                    }
                } else {
                    $skipped_count++;
                }
            }
            fclose( $handle );
            unlink( $target_path );

            add_settings_error(
                'campania_companies_map_messages',
                'csv_upload_success',
                sprintf( __( 'CSV caricato. Inserite: %d, Aggiornate: %d, Saltate: %d.', 'campania-companies-map' ), $inserted_count, $updated_count, $skipped_count ),
                'success'
            );
        } else {
            add_settings_error( 'campania_companies_map_messages', 'csv_upload_error', __( 'Impossibile aprire il file CSV.', 'campania-companies-map' ), 'error' );
        }
    } else {
        add_settings_error( 'campania_companies_map_messages', 'csv_upload_error', __( 'Errore durante il caricamento del file.', 'campania-companies-map' ), 'error' );
    }
}

/**
 * Delete all companies
 */
function campania_companies_map_delete_all_companies() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';
    $deleted = $wpdb->query( "TRUNCATE TABLE $table_name" );

    if ( false !== $deleted ) {
        add_settings_error( 'campania_companies_map_messages', 'delete_all_success', __( 'Tutte le aziende sono state eliminate.', 'campania-companies-map' ), 'success' );
    } else {
        add_settings_error( 'campania_companies_map_messages', 'delete_all_error', __( 'Errore durante l\'eliminazione.', 'campania-companies-map' ), 'error' );
    }
}

/**
 * SINGLE DELETE helper
 */
function campania_companies_map_delete_company( $id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';
    return ( false !== $wpdb->delete( $table_name, array( 'id' => (int) $id ), array( '%d' ) ) );
}

/**
 * Companies table (admin) con colonna Azioni (Modifica/Elimina)
 */
function campania_companies_map_display_companies_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';

    $per_page = 20;
    $current_page = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
    $offset = ( $current_page - 1 ) * $per_page;

    $total_items = (int) $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );
    $companies = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, ragione_sociale, indirizzo, citta, provincia, latitude, longitude, geocoded FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        )
    );

    if ( empty( $companies ) ) {
        echo '<p>' . esc_html__( 'Nessuna azienda caricata.', 'campania-companies-map' ) . '</p>';
        return;
    }

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th>' . esc_html__( 'Ragione Sociale', 'campania-companies-map' ) . '</th>';
    echo '<th>' . esc_html__( 'Indirizzo', 'campania-companies-map' ) . '</th>';
    echo '<th>' . esc_html__( 'Città', 'campania-companies-map' ) . '</th>';
    echo '<th>' . esc_html__( 'Provincia', 'campania-companies-map' ) . '</th>';
    echo '<th>' . esc_html__( 'Geocodificato', 'campania-companies-map' ) . '</th>';
    echo '<th>' . esc_html__( 'Latitudine', 'campania-companies-map' ) . '</th>';
    echo '<th>' . esc_html__( 'Longitudine', 'campania-companies-map' ) . '</th>';
    echo '<th>' . esc_html__( 'Azioni', 'campania-companies-map' ) . '</th>';
    echo '</tr></thead><tbody>';

    foreach ( $companies as $company ) {
        $edit_url = add_query_arg(
            array(
                'page'   => 'campania-companies-map',
                'action' => 'edit',
                'id'     => (int) $company->id,
            ),
            admin_url( 'admin.php' )
        );

        $delete_url = wp_nonce_url(
            add_query_arg(
                array(
                    'page'   => 'campania-companies-map',
                    'action' => 'delete',
                    'id'     => (int) $company->id,
                ),
                admin_url( 'admin.php' )
            ),
            'campania_companies_map_delete_' . (int) $company->id
        );

        echo '<tr>';
        echo '<td>' . esc_html( $company->ragione_sociale ) . '</td>';
        echo '<td>' . esc_html( $company->indirizzo ) . '</td>';
        echo '<td>' . esc_html( $company->citta ) . '</td>';
        echo '<td>' . esc_html( $company->provincia ) . '</td>';
        echo '<td>';
        if ( (int) $company->geocoded === 1 ) {
            echo '<span class="dashicons dashicons-yes" style="color: green;"></span> ' . esc_html__( 'Sì', 'campania-companies-map' );
        } elseif ( (int) $company->geocoded === 2 ) {
            echo '<span class="dashicons dashicons-no-alt" style="color: red;"></span> ' . esc_html__( 'Fallito', 'campania-companies-map' );
        } else {
            echo '<span class="dashicons dashicons-clock" style="color: orange;"></span> ' . esc_html__( 'In attesa', 'campania-companies-map' );
        }
        echo '</td>';
        echo '<td>' . ( $company->latitude ? esc_html( $company->latitude ) : '-' ) . '</td>';
        echo '<td>' . ( $company->longitude ? esc_html( $company->longitude ) : '-' ) . '</td>';
        echo '<td>';
        echo '<a class="button button-small" href="' . esc_url( $edit_url ) . '">' . esc_html__( 'Modifica', 'campania-companies-map' ) . '</a> ';
        echo '<a class="button button-small button-link-delete" href="' . esc_url( $delete_url ) . '" onclick="return confirm(\'' . esc_attr__( 'Eliminare questa azienda?', 'campania-companies-map' ) . '\');">' . esc_html__( 'Elimina', 'campania-companies-map' ) . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';

    $total_pages = ceil( $total_items / $per_page );
    if ( $total_pages > 1 ) {
        echo '<div class="tablenav"><div class="tablenav-pages">';
        $base_url = add_query_arg( 'paged', '%#%', admin_url( 'admin.php?page=campania-companies-map#companies' ) );
        echo paginate_links( array(
            'base'      => $base_url,
            'format'    => '',
            'current'   => $current_page,
            'total'     => $total_pages,
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ) );
        echo '</div></div>';
    }
}

/**
 * Render form di modifica singola azienda
 */
function campania_companies_map_render_edit_form( $id ) {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $company = campania_companies_map_get_company( $id );
    if ( ! $company ) {
        echo '<div class="wrap"><h1>' . esc_html__( 'Azienda non trovata', 'campania-companies-map' ) . '</h1></div>';
        return;
    }

    settings_errors( 'campania_companies_map_messages' );

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Modifica Azienda', 'campania-companies-map' ) . '</h1>';
    echo '<a href="' . esc_url( admin_url( 'admin.php?page=campania-companies-map#companies' ) ) . '" class="button">&larr; ' . esc_html__( 'Torna all\'elenco', 'campania-companies-map' ) . '</a>';
    echo '<form method="post" style="margin-top:15px; max-width:900px;">';
    wp_nonce_field( 'campania_companies_map_edit_action', 'campania_companies_map_edit_nonce' );
    echo '<input type="hidden" name="id" value="' . (int) $company->id . '"/>';

    // helper field
    $f = function( $label, $name, $value, $type = 'text', $attrs = '' ) {
        echo '<p><label for="' . esc_attr( $name ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
        echo '<input type="' . esc_attr( $type ) . '" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="regular-text" ' . $attrs . ' />';
        echo '</p>';
    };

    $f( __( 'Ragione Sociale', 'campania-companies-map' ), 'ragione_sociale', $company->ragione_sociale, 'text', 'required' );
    $f( __( 'Email', 'campania-companies-map' ), 'email', $company->email, 'email' );
    $f( __( 'PEC', 'campania-companies-map' ), 'pec', $company->pec, 'email' );
    $f( __( 'Sito Web', 'campania-companies-map' ), 'sitoweb', $company->sitoweb, 'url' );
    $f( __( 'Indirizzo', 'campania-companies-map' ), 'indirizzo', $company->indirizzo, 'text', 'required' );
    $f( __( 'Città', 'campania-companies-map' ), 'citta', $company->citta, 'text', 'required' );
    $f( __( 'Provincia', 'campania-companies-map' ), 'provincia', $company->provincia, 'text', 'required' );
    $f( __( 'Telefono', 'campania-companies-map' ), 'telefono', $company->telefono, 'text' );
    $f( __( 'Partita IVA', 'campania-companies-map' ), 'partita_iva', $company->partita_iva, 'text' );
    $f( __( 'Prodotto', 'campania-companies-map' ), 'prodotto', $company->prodotto, 'text' );
    $f( __( 'Settore',  'campania-companies-map' ), 'settore',  $company->settore,  'text' );


    // Coordinate e stato geocoding
    echo '<p style="display:flex;gap:15px;align-items:flex-end;">';
    echo '<span>';
    echo '<label for="latitude"><strong>' . esc_html__( 'Latitudine', 'campania-companies-map' ) . '</strong></label><br>';
    echo '<input type="number" step="0.0000001" id="latitude" name="latitude" value="' . esc_attr( $company->latitude ) . '" class="regular-text" />';
    echo '</span>';
    echo '<span>';
    echo '<label for="longitude"><strong>' . esc_html__( 'Longitudine', 'campania-companies-map' ) . '</strong></label><br>';
    echo '<input type="number" step="0.0000001" id="longitude" name="longitude" value="' . esc_attr( $company->longitude ) . '" class="regular-text" />';
    echo '</span>';
    echo '<span>';
    echo '<label for="geocoded"><strong>' . esc_html__( 'Stato geocodifica', 'campania-companies-map' ) . '</strong></label><br>';
    echo '<select id="geocoded" name="geocoded">';
    $statuses = array(
        0 => __( 'In attesa (0)', 'campania-companies-map' ),
        1 => __( 'Successo (1)', 'campania-companies-map' ),
        2 => __( 'Fallito (2)', 'campania-companies-map' ),
    );
    foreach ( $statuses as $k => $label ) {
        echo '<option value="' . (int) $k . '" ' . selected( (int) $company->geocoded, $k, false ) . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select>';
    echo '</span>';
    echo '</p>';

    submit_button( __( 'Salva Modifiche', 'campania-companies-map' ) );
    echo ' <a class="button button-secondary" href="' . esc_url( admin_url( 'admin.php?page=campania-companies-map#companies' ) ) . '">' . esc_html__( 'Annulla', 'campania-companies-map' ) . '</a>';

    echo '</form>';
    echo '</div>';
}

/**
 * Recupera una singola azienda
 */
function campania_companies_map_get_company( $id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';
    return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ) );
}

/**
 * Gestisce il POST di aggiornamento
 */
function campania_companies_map_handle_update_post() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return new WP_Error( 'perm', __( 'Permessi insufficienti.', 'campania-companies-map' ) );
    }
    if ( empty( $_POST['id'] ) ) {
        return new WP_Error( 'missing', __( 'ID mancante.', 'campania-companies-map' ) );
    }
    $id = absint( $_POST['id'] );

    $ragione_sociale = sanitize_text_field( wp_unslash( $_POST['ragione_sociale'] ?? '' ) );
    $email           = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $pec             = sanitize_email( wp_unslash( $_POST['pec'] ?? '' ) );
    $sitoweb         = esc_url_raw( wp_unslash( $_POST['sitoweb'] ?? '' ) );
    $indirizzo       = sanitize_text_field( wp_unslash( $_POST['indirizzo'] ?? '' ) );
    $citta           = sanitize_text_field( wp_unslash( $_POST['citta'] ?? '' ) );
    $provincia       = sanitize_text_field( wp_unslash( $_POST['provincia'] ?? '' ) );
    $telefono        = sanitize_text_field( wp_unslash( $_POST['telefono'] ?? '' ) );
    $partita_iva     = sanitize_text_field( wp_unslash( $_POST['partita_iva'] ?? '' ) );
    $prodotto        = sanitize_text_field( wp_unslash( $_POST['prodotto'] ?? '' ) );
    $settore         = sanitize_text_field( wp_unslash( $_POST['settore'] ?? '' ) );


    // numerici (accetta vuoto -> NULL)
    $latitude        = isset( $_POST['latitude'] ) && $_POST['latitude'] !== '' ? floatval( $_POST['latitude'] ) : null;
    $longitude       = isset( $_POST['longitude'] ) && $_POST['longitude'] !== '' ? floatval( $_POST['longitude'] ) : null;
    $geocoded        = isset( $_POST['geocoded'] ) ? (int) $_POST['geocoded'] : 0;

    if ( $ragione_sociale === '' || $indirizzo === '' || $citta === '' || $provincia === '' ) {
        return new WP_Error( 'validation', __( 'Compila i campi obbligatori (Ragione Sociale, Indirizzo, Città, Provincia).', 'campania-companies-map' ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';

    $data = array(
        'ragione_sociale' => $ragione_sociale,
        'email'           => $email ?: null,
        'pec'             => $pec ?: null,
        'sitoweb'         => $sitoweb ?: null,
        'indirizzo'       => $indirizzo,
        'citta'           => $citta,
        'provincia'       => $provincia,
        'telefono'        => $telefono ?: null,
        'partita_iva'     => ($partita_iva !== '' ? $partita_iva : null),
        'prodotto'        => $prodotto ?: null,
        'settore'         => $settore  ?: null,
        'latitude'        => $latitude,
        'longitude'       => $longitude,
        'geocoded'        => $geocoded,
    );
    $format = array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%f','%f','%d' );

    // Sostituisci i null con NULL esplicito (per evitare '' su colonne numeric/email)
    foreach ( $data as $k => $v ) {
        if ( $v === null ) {
            $data[$k] = null;
        }
    }

    $updated = $wpdb->update(
        $table_name,
        $data,
        array( 'id' => $id ),
        $format,
        array( '%d' )
    );

    if ( $updated === false ) {
        return new WP_Error( 'db', __( 'Errore durante l\'aggiornamento nel database.', 'campania-companies-map' ) );
    }
    return true;
}

/**
 * AJAX: geocode batch
 */
function campania_companies_map_geocode_companies_batch() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'Permessi insufficienti.', 'campania-companies-map' ) ) );
    }
    if ( ! isset( $_POST['campania_companies_map_geocode_nonce'] ) || ! wp_verify_nonce( $_POST['campania_companies_map_geocode_nonce'], 'campania_companies_map_geocode_action' ) ) {
        wp_send_json_error( array( 'message' => __( 'Nonce non valido.', 'campania-companies-map' ) ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';
    $batch_size = 5;

    $companies_to_geocode = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, indirizzo, citta, provincia FROM $table_name WHERE geocoded IN (0,2) LIMIT %d",
            $batch_size
        )
    );

    if ( empty( $companies_to_geocode ) ) {
        wp_send_json_success( array( 'message' => __( 'Geocodifica completata.', 'campania-companies-map' ), 'finished' => true ) );
    }

    $geocoded_count = 0;
    $failed_count   = 0;
    $last_request_time = (float) get_option( 'campania_companies_map_last_nominatim_request', 0 );

    foreach ( $companies_to_geocode as $company ) {
        $time_since = microtime( true ) - $last_request_time;
        if ( $time_since < CAMPANIA_COMPANIES_MAP_NOMINATIM_DELAY ) {
            usleep( (int) ((CAMPANIA_COMPANIES_MAP_NOMINATIM_DELAY - $time_since) * 1e6) );
        }

        $address = sprintf( '%s, %s, %s, Italia', $company->indirizzo, $company->citta, $company->provincia );
        $coords = campania_companies_map_nominatim_geocode( $address );

        if ( $coords ) {
            $wpdb->update(
                $table_name,
                array(
                    'latitude'  => $coords['lat'],
                    'longitude' => $coords['lon'],
                    'geocoded'  => 1,
                ),
                array( 'id' => $company->id ),
                array( '%f', '%f', '%d' ),
                array( '%d' )
            );
            $geocoded_count++;
        } else {
            $wpdb->update(
                $table_name,
                array( 'geocoded' => 2 ),
                array( 'id' => $company->id ),
                array( '%d' ),
                array( '%d' )
            );
            $failed_count++;
        }
        update_option( 'campania_companies_map_last_nominatim_request', microtime( true ) );
    }

    $remaining = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE geocoded IN (0,2)" );
    wp_send_json_success( array(
        'message'         => sprintf( __( 'Geocodificate %d, fallite %d. Rimanenti: %d', 'campania-companies-map' ), $geocoded_count, $failed_count, $remaining ),
        'finished'        => ( $remaining === 0 ),
        'remaining_count' => $remaining,
    ) );
}
add_action( 'wp_ajax_campania_companies_map_geocode_companies_batch', 'campania_companies_map_geocode_companies_batch' );

/**
 * Nominatim geocode
 */
function campania_companies_map_nominatim_geocode( $address ) {
    $nominatim_url = 'https://nominatim.openstreetmap.org/search';
    $args = array(
        'format'         => 'json',
        'q'              => $address,
        'limit'          => 1,
        'addressdetails' => 0,
    );

    $response = wp_remote_get( add_query_arg( $args, $nominatim_url ), array(
        'user-agent' => 'MappaAziendeCampaniaWordPressPlugin/' . CAMPANIA_COMPANIES_MAP_VERSION . ' (' . get_bloginfo( 'url' ) . ')',
        'timeout'    => 10,
    ) );

    if ( is_wp_error( $response ) ) {
        error_log( 'Nominatim Geocoding Error: ' . $response->get_error_message() );
        return false;
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( ! empty( $data ) && isset( $data[0]['lat'], $data[0]['lon'] ) ) {
        return array(
            'lat' => (float) $data[0]['lat'],
            'lon' => (float) $data[0]['lon'],
        );
    }
    return false;
}

/**
 * Admin enqueue (assets from uploads)
 */
function campania_companies_map_admin_enqueue_scripts( $hook_suffix ) {
    if ( 'toplevel_page_campania-companies-map' !== $hook_suffix ) return;

    $assets_url = campania_companies_map_assets_url();

    wp_enqueue_style( 'campania-companies-map-admin-style', $assets_url . 'admin-style.css', array(), CAMPANIA_COMPANIES_MAP_VERSION );
    wp_enqueue_script( 'campania-companies-map-admin-script', $assets_url . 'admin-script.js', array( 'jquery' ), CAMPANIA_COMPANIES_MAP_VERSION, true );
    wp_localize_script( 'campania-companies-map-admin-script', 'campaniaCompaniesMapAdmin', array(
        'ajax_url'          => admin_url( 'admin-ajax.php' ),
        'geocode_nonce'     => wp_create_nonce( 'campania_companies_map_geocode_action' ),
        'processing_message'=> esc_html__( 'Geocodifica in corso...', 'campania-companies-map' ),
        'finished_message'  => esc_html__( 'Geocodifica completata!', 'campania-companies-map' ),
        'error_message'     => esc_html__( 'Errore durante la geocodifica.', 'campania-companies-map' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'campania_companies_map_admin_enqueue_scripts' );

/**
 * Shortcode [campania_companies_map]
 */
function campania_companies_map_shortcode_callback( $atts ) {
    // Leaflet & cluster libs (CDN)
    wp_enqueue_style( 'leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
    wp_enqueue_script( 'leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
    wp_enqueue_style( 'leaflet-markercluster-css', 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css', array('leaflet-css'), '1.5.3' );
    wp_enqueue_style( 'leaflet-markercluster-default-css', 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css', array('leaflet-css'), '1.5.3' );
    wp_enqueue_script( 'leaflet-markercluster', 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js', array( 'leaflet-js' ), '1.5.3', true );

    // Our frontend assets from uploads
    $assets_url = campania_companies_map_assets_url();
    wp_enqueue_script( 'campania-companies-map-frontend-script', $assets_url . 'frontend-script.js', array( 'jquery', 'leaflet-js', 'leaflet-markercluster' ), CAMPANIA_COMPANIES_MAP_VERSION, true );
    wp_enqueue_style( 'campania-companies-map-frontend-style', $assets_url . 'frontend-style.css', array(), CAMPANIA_COMPANIES_MAP_VERSION );

    $options = get_option( 'campania_companies_map_options' );
    $tile_url   = isset( $options['tile_url'] ) ? $options['tile_url'] : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    $attribution = isset( $options['attribution'] ) ? $options['attribution'] : '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';
    $provinces  = $wpdb->get_col( "SELECT DISTINCT provincia FROM $table_name WHERE geocoded = 1 ORDER BY provincia ASC" );
    $cities = $wpdb->get_col( "SELECT DISTINCT citta FROM $table_name WHERE citta IS NOT NULL AND citta <> '' AND geocoded = 1 ORDER BY citta ASC" );
    $sectors_list  = $wpdb->get_col( "SELECT DISTINCT settore FROM $table_name WHERE settore IS NOT NULL AND settore <> '' AND geocoded = 1 ORDER BY settore ASC" );
    $products_list = $wpdb->get_col( "SELECT DISTINCT prodotto FROM $table_name WHERE prodotto IS NOT NULL AND prodotto <> '' AND geocoded = 1 ORDER BY prodotto ASC" );


    $sectors_opt = get_option( 'campania_companies_map_sectors', array(
        'default_icon' => 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        'items' => array(),
    ) );

    // Normalizza in mappa (nome -> icona)
    $sectors_map = array();
    if ( ! empty( $sectors_opt['items'] ) ) {
        foreach ( $sectors_opt['items'] as $it ) {
            if ( ! empty( $it['name'] ) ) {
                $sectors_map[ $it['name'] ] = $it['icon'];
            }
        }
    }

    wp_localize_script( 'campania-companies-map-frontend-script', 'campaniaCompaniesMap', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'campania_companies_map_get_data_nonce' ),
        'map_options' => array(
            'tile_url'    => $tile_url,
            'attribution' => $attribution,
            'center_lat'  => 40.8518,
            'center_lon'  => 14.2681,
            'zoom'        => 9,
        ),
        'provinces' => $provinces,
        'cities'    => $cities,
        'sectors'   => $sectors_list,
        'products'  => $products_list,
        'sectors_map' => $sectors_map,         // <-- già aggiunto nello step precedente
        'default_icon' => $sectors_opt['default_icon'], // <-- idem
        'i18n' => array(
            'province' => __( 'Provincia', 'campania-companies-map' ),
            'city'     => __( 'Città', 'campania-companies-map' ),
            'sector'   => __( 'Settore', 'campania-companies-map' ),
            'product'  => __( 'Prodotto', 'campania-companies-map' ),
            'search'   => __( 'Cerca Azienda', 'campania-companies-map' ),
            'address'  => __( 'Indirizzo', 'campania-companies-map' ),
            'email'    => __( 'Email', 'campania-companies-map' ),
            'pec'      => __( 'PEC', 'campania-companies-map' ),
            'website'  => __( 'Sito Web', 'campania-companies-map' ),
            'phone'    => __( 'Telefono', 'campania-companies-map' ),
            'vat'      => __( 'Partita IVA', 'campania-companies-map' ),
        ),
    ) );



    ob_start(); ?>

    <?php if ( isset($_GET['debug_mappa']) ) : ?>
    <button id="ccm-debug-btn" style="position:fixed;top:10px;right:10px;z-index:99999;padding:15px 20px;background:#ff0000;color:#fff;border:none;border-radius:8px;font-size:16px;font-weight:bold;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,0.3);">🐛 DEBUG MAPPA</button>
    <script>
    jQuery(function($){
        $('#ccm-debug-btn').on('click', function(){
            var report = '=== REPORT DEBUG MAPPA ===\n\n';
            report += '1. Div mappa esiste: ' + ($('#campania-companies-map').length ? '✅ SI' : '❌ NO') + '\n';
            report += '2. Altezza div: ' + $('#campania-companies-map').height() + 'px\n';
            report += '3. Contenuto div: ' + $('#campania-companies-map').html().length + ' caratteri\n';
            report += '4. Leaflet caricato: ' + (typeof L !== 'undefined' ? '✅ SI' : '❌ NO') + '\n';
            report += '5. Config esiste: ' + (typeof campaniaCompaniesMap !== 'undefined' ? '✅ SI' : '❌ NO') + '\n';
            console.log(report);
            alert(report + '\n\nVedi console per dettagli');

            $.post(campaniaCompaniesMap.ajax_url, {
                action: 'campania_companies_map_get_data',
                nonce: campaniaCompaniesMap.nonce
            }).done(function(r){
                console.log('✅ AJAX OK:', r);
                if(r.success) { console.table(r.data); alert('✅ AJAX: ' + r.data.length + ' aziende!'); }
            }).fail(function(e){ console.error('❌ AJAX FAIL:', e); alert('❌ AJAX FALLITO'); });
        });
    });
    </script>
    <?php endif; ?>

    <div class="campania-companies-map-container">
        <div class="campania-companies-map-controls" id="campania-companies-map-filters">
            <label for="campania-companies-map-province-filter"><?php esc_html_e( 'Provincia:', 'campania-companies-map' ); ?></label>
            <select id="campania-companies-map-province-filter" name="provincia">
                <option value=""><?php esc_html_e( 'Tutte le province', 'campania-companies-map' ); ?></option>
                <?php foreach ( $provinces as $prov ) : ?>
                    <option value="<?php echo esc_attr( $prov ); ?>"><?php echo esc_html( $prov ); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="campania-companies-map-city-filter"><?php esc_html_e( 'Città:', 'campania-companies-map' ); ?></label>
            <select id="campania-companies-map-city-filter" name="citta">
                <option value=""><?php esc_html_e( 'Tutte le città', 'campania-companies-map' ); ?></option>
                <?php foreach ( $cities as $city ) : ?>
                    <option value="<?php echo esc_attr( $city ); ?>"><?php echo esc_html( $city ); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="campania-companies-map-sector-filter"><?php esc_html_e( 'Settore:', 'campania-companies-map' ); ?></label>
            <select id="campania-companies-map-sector-filter" name="settore">
                <option value=""><?php esc_html_e( 'Tutti i settori', 'campania-companies-map' ); ?></option>
                <?php foreach ( $sectors_list as $sec ) : ?>
                    <option value="<?php echo esc_attr( $sec ); ?>"><?php echo esc_html( $sec ); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="campania-companies-map-product-filter"><?php esc_html_e( 'Prodotto:', 'campania-companies-map' ); ?></label>
            <select id="campania-companies-map-product-filter" name="prodotto">
                <option value=""><?php esc_html_e( 'Tutti i prodotti', 'campania-companies-map' ); ?></option>
                <?php foreach ( $products_list as $prod ) : ?>
                    <option value="<?php echo esc_attr( $prod ); ?>"><?php echo esc_html( $prod ); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="campania-companies-map-search"><?php esc_html_e( 'Cerca azienda:', 'campania-companies-map' ); ?></label>
            <input type="text" id="campania-companies-map-search" name="search" placeholder="<?php esc_attr_e( 'Cerca per nome, indirizzo o prodotto...', 'campania-companies-map' ); ?>" />

            <button id="campania-companies-map-reset-filters"><?php esc_html_e( 'Reset Filtri', 'campania-companies-map' ); ?></button>
        </div>
        <div id="campania-companies-map"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'campania_companies_map', 'campania_companies_map_shortcode_callback' );

/**
 * AJAX data for frontend (public)
 */
function campania_companies_map_get_data_callback() {
    check_ajax_referer( 'campania_companies_map_get_data_nonce', 'nonce' );

    global $wpdb;
    $table_name = $wpdb->prefix . 'campania_companies_map';

   $province = isset($_REQUEST['province'])  ? sanitize_text_field($_REQUEST['province'])
            : (isset($_REQUEST['provincia']) ? sanitize_text_field($_REQUEST['provincia']) : '');

    $city     = isset($_REQUEST['city'])      ? sanitize_text_field($_REQUEST['city'])
             : (isset($_REQUEST['citta'])    ? sanitize_text_field($_REQUEST['citta'])     : '');

    $sector   = isset($_REQUEST['sector'])    ? sanitize_text_field($_REQUEST['sector'])
             : (isset($_REQUEST['settore'])  ? sanitize_text_field($_REQUEST['settore'])   : '');

    $product  = isset($_REQUEST['product'])   ? sanitize_text_field($_REQUEST['product'])
             : (isset($_REQUEST['prodotto']) ? sanitize_text_field($_REQUEST['prodotto'])  : '');

    $search   = isset($_REQUEST['search'])    ? sanitize_text_field($_REQUEST['search'])
             : (isset($_REQUEST['q'])        ? sanitize_text_field($_REQUEST['q'])         : '');

    // Normalizzazione input filtri
    $province = strtoupper( trim( $province ) );
    $city     = strtoupper( trim( $city ) );
    $sector   = strtoupper( trim( $sector ) );
    $product  = strtoupper( trim( $product ) );


   $where = array( 'geocoded = 1' );
    $args  = array();

    if ( $province !== '' ) {
        $where[] = 'TRIM(UPPER(provincia)) = %s';
    $args[]  = $province;
    }
    if ( $city !== '' ) {
        $where[] = 'TRIM(UPPER(citta)) = %s';
        $args[]  = $city;
    }
    if ( $sector !== '' ) {
        $where[] = 'TRIM(UPPER(settore)) = %s';
        $args[]  = $sector;
    }
    if ( $product !== '' ) {
        $where[] = 'TRIM(UPPER(prodotto)) = %s';
        $args[]  = $product;
    }
    if ( $search !== '' ) {
        $like     = '%' . $wpdb->esc_like( $search ) . '%';
        $where[]  = '(ragione_sociale LIKE %s OR indirizzo LIKE %s OR prodotto LIKE %s)';
        $args[]   = $like;
        $args[]   = $like;
        $args[]   = $like;
    }

    $where_sql = implode( ' AND ', $where );

    $sql = "SELECT id, ragione_sociale, indirizzo, citta, provincia, prodotto, settore, latitude, longitude, email, pec, sitoweb, telefono, partita_iva
        FROM $table_name
        WHERE $where_sql";

    $companies = ! empty( $args ) ? $wpdb->get_results( $wpdb->prepare( $sql, $args ), ARRAY_A )
                              : $wpdb->get_results( $sql, ARRAY_A );


    $sanitized = array();
    foreach ( $companies as $c ) {
        $sanitized[] = array(
            'id'              => (int) $c['id'],
            'ragione_sociale' => esc_html( $c['ragione_sociale'] ),
            'indirizzo'       => esc_html( $c['indirizzo'] ),
            'citta'           => esc_html( $c['citta'] ),
            'provincia'       => esc_html( $c['provincia'] ),
            'prodotto'        => esc_html( $c['prodotto'] ),
            'settore'         => esc_html( $c['settore'] ),
            'latitude'        => (float) $c['latitude'],
            'longitude'       => (float) $c['longitude'],
            'email'           => sanitize_email( $c['email'] ),
            'pec'             => sanitize_email( $c['pec'] ),
            'sitoweb'         => esc_url( $c['sitoweb'] ),
            'telefono'        => esc_html( $c['telefono'] ),
            'partita_iva'     => esc_html( $c['partita_iva'] ),
        );
    }

    wp_send_json_success( $sanitized );
}
add_action( 'wp_ajax_campania_companies_map_get_data', 'campania_companies_map_get_data_callback' );
add_action( 'wp_ajax_nopriv_campania_companies_map_get_data', 'campania_companies_map_get_data_callback' );

/**
 * Create asset files in uploads (called on activation)
 */
function campania_companies_map_create_asset_files() {
    $assets_dir = campania_companies_map_assets_dir();
    if ( ! is_dir( $assets_dir ) ) {
        wp_mkdir_p( $assets_dir );
    }

    // Admin CSS
    $admin_css = "
.campania-companies-map-container { margin-top: 20px; }
.nav-tab-wrapper { margin-bottom: 20px; }
.tab-content { display: none; padding: 20px 0; border-top: 1px solid #ccc; }
.tab-content.active { display: block; }
#campania-companies-map-geocode-status { font-weight: bold; }
";
    @file_put_contents( $assets_dir . 'admin-style.css', $admin_css );

    // Admin JS (tabs + geocoding AJAX)
    $admin_js = "
jQuery(function($){
    $('.nav-tab-wrapper a').on('click', function(e){
        e.preventDefault();
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tab-content').removeClass('active');
        $($(this).attr('href')).addClass('active');
    });
    $('.nav-tab-wrapper a:first').trigger('click');

    $('#campania_companies_map_geocode_submit').on('click', function(e){
        e.preventDefault();
        var btn = $(this), statusDiv = $('#campania-companies-map-geocode-status');
        btn.prop('disabled', true).text(campaniaCompaniesMapAdmin.processing_message);
        statusDiv.text(campaniaCompaniesMapAdmin.processing_message);

        function processBatch(){
            $.post(campaniaCompaniesMapAdmin.ajax_url, {
                action: 'campania_companies_map_geocode_companies_batch',
                campania_companies_map_geocode_nonce: campaniaCompaniesMapAdmin.geocode_nonce
            })
            .done(function(resp){
                if(resp.success){
                    statusDiv.text(resp.data.message);
                    if(!resp.data.finished){
                        setTimeout(processBatch, 1000);
                    } else {
                        btn.prop('disabled', false).text(campaniaCompaniesMapAdmin.finished_message);
                        location.reload();
                    }
                } else {
                    statusDiv.text(campaniaCompaniesMapAdmin.error_message + ' ' + (resp.data && resp.data.message ? resp.data.message : ''));
                    btn.prop('disabled', false).text(campaniaCompaniesMapAdmin.error_message);
                }
            })
            .fail(function(){
                statusDiv.text(campaniaCompaniesMapAdmin.error_message);
                btn.prop('disabled', false).text(campaniaCompaniesMapAdmin.error_message);
            });
        }
        processBatch();
    });
});
";
    @file_put_contents( $assets_dir . 'admin-script.js', $admin_js );

    // Frontend CSS
    $front_css = "
.campania-companies-map-container {
    margin: 20px 0;
    border: 1px solid #eee;
    padding: 15px;
    background: #fff;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
}
.campania-companies-map-controls {
    margin-bottom: 15px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}
.campania-companies-map-controls label { font-weight: bold; margin-right: 5px; }
.campania-companies-map-controls select,
.campania-companies-map-controls input[type=\"text\"],
.campania-companies-map-controls button {
    padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;
    -webkit-appearance: menulist;
    -moz-appearance: menulist;
    appearance: auto;
    background-image: none;
}
.campania-companies-map-controls button {
    background-color: #0073aa; color: #fff; cursor: pointer; border-color: #0073aa;
}
.campania-companies-map-controls button:hover { background-color: #0085ba; border-color: #0085ba; }
#campania-companies-map { height: 600px; border: 1px solid #ddd; border-radius: 4px; }
.leaflet-popup-content p { margin: 0 0 5px 0; }
.leaflet-popup-content strong { display: block; margin-bottom: 5px; }
";
    @file_put_contents( $assets_dir . 'frontend-style.css', $front_css );
   
// Frontend JS
$front_js = <<<'JS'
jQuery(function($){
    var map, markers = L.markerClusterGroup();
    var MAP_ID = 'campania-companies-map';

    // === DEBUG HELPERS ===
    var CCM_DEBUG = (window.CCM_DEBUG === true) || new URLSearchParams(location.search).has('map_debug');
    function dbg(){ if (CCM_DEBUG && window.console) { console.log.apply(console, arguments); } }

    var __hudEl = null;
    function hudInit(){
        if (!CCM_DEBUG || __hudEl) return;
        __hudEl = document.createElement('div');
        __hudEl.id = 'ccm-hud';
        __hudEl.style.cssText = 'position:fixed;right:12px;bottom:12px;z-index:99999;background:#111;color:#fff;padding:10px 12px;border-radius:10px;font:12px/1.3 system-ui,Arial;max-width:40ch;box-shadow:0 8px 20px rgba(0,0,0,.25);opacity:.95';
        __hudEl.innerHTML = '<b>MAP DEBUG</b><div id="ccm-hud-body" style="margin-top:6px;white-space:pre-wrap"></div>';
        document.body.appendChild(__hudEl);
    }
    function hudUpdate(obj){
        if (!CCM_DEBUG) return;
        hudInit();
        var body = document.getElementById('ccm-hud-body');
        if (body) body.textContent = JSON.stringify(obj, null, 2);
    }
    // === END DEBUG ===

    function initMap(){
        var el = document.getElementById(MAP_ID);
        if(!el){
            console.warn('initMap: elemento mappa non trovato');
            return false;
        }

        if (map) { // già esiste: solo ricalcola dimensioni
            console.log('initMap: mappa già inizializzata, invalidateSize');
            setTimeout(function(){ map.invalidateSize(); }, 50);
            return true;
        }

        console.log('initMap: inizializzazione mappa Leaflet...');
        var opts = campaniaCompaniesMap.map_options;

        try {
            map = L.map(MAP_ID).setView([opts.center_lat, opts.center_lon], opts.zoom);
            L.tileLayer(opts.tile_url, {
                attribution: opts.attribution,
                maxZoom: 19,
                subdomains: 'abc'
            }).addTo(map);

            map.addLayer(markers);

            // Attendi che la mappa sia completamente caricata
            map.whenReady(function(){
                console.log('initMap: mappa pronta e renderizzata');
            });

            console.log('initMap: mappa inizializzata con successo');
            return true;
        } catch(e) {
            console.error('initMap: errore durante inizializzazione:', e);
            return false;
        }
    }

    function iconForSector(sectorName){
        // Se è stata definita una mappa settore -> icona, usala, altrimenti default
        try{
            var sectorsMap = campaniaCompaniesMap.sectors_map || {};
            var url = sectorsMap[sectorName] || campaniaCompaniesMap.default_icon || null;
            return url ? L.icon({
                iconUrl: url,
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            }) : null;
        }catch(e){ return null; }
    }
    function norm(s){
      return (s == null ? '' : (''+s)).trim().toUpperCase();
    }

    function updateMarkers(data){
            if(!map) {
                console.error('updateMarkers: mappa non inizializzata!');
                return;
            }

            const stats = { total: (data||[]).length, shown: 0 };
            console.log('updateMarkers: ricevute', stats.total, 'aziende');

            markers.clearLayers();
            if(!data || !data.length) {
                console.warn('updateMarkers: nessun dato da visualizzare');
                return;
            }

            var bounds = L.latLngBounds([]);

            data.forEach(function(c){
                // Il filtro viene già fatto lato server dall'AJAX
                // Non serve rifiltrare qui lato client

                // se passa i filtri, aggiungi il marker
                var latlng = [c.latitude, c.longitude];

                if(!latlng[0] || !latlng[1]) {
                    console.warn('Azienda senza coordinate:', c.ragione_sociale, latlng);
                    return;
                }

                console.log('Aggiungendo marker:', c.ragione_sociale, 'a', latlng);

                var icon = iconForSector(c.settore);
                var m = icon ? L.marker(latlng, { icon: icon }) : L.marker(latlng);
                stats.shown++;
                var html = '<div class="campania-companies-map-popup">'+
                    '<h4>'+ (c.ragione_sociale || '') +'</h4>'+
                    '<p><strong>'+ (campaniaCompaniesMap.i18n.address || 'Indirizzo') +':</strong> '+ (c.indirizzo || '') +', '+ (c.citta || '') +' ('+ (c.provincia || '') +')</p>'+
                    (c.prodotto ? '<p><strong>'+ (campaniaCompaniesMap.i18n.product || 'Prodotto') +':</strong> '+ c.prodotto +'</p>' : '')+
                    (c.settore  ? '<p><strong>'+ (campaniaCompaniesMap.i18n.sector  || 'Settore')  +':</strong> '+ c.settore  +'</p>' : '')+
                    (c.telefono ? '<p><strong>'+ (campaniaCompaniesMap.i18n.phone   || 'Telefono') +':</strong> '+ c.telefono +'</p>' : '')+
                    (c.email    ? '<p><strong>'+ (campaniaCompaniesMap.i18n.email   || 'Email')    +':</strong> <a href="mailto:'+c.email+'">'+c.email+'</a></p>' : '')+
                    (c.sitoweb  ? '<p><strong>'+ (campaniaCompaniesMap.i18n.website || 'Sito Web') +':</strong> <a href="'+c.sitoweb+'" target="_blank" rel="noopener">'+c.sitoweb+'</a></p>' : '')+
                    '</div>';

                markers.addLayer( m.bindPopup(html) );
                bounds.extend(latlng);
        });

          console.log('updateMarkers: aggiunti', stats.shown, 'marker su', stats.total);

          if(bounds.isValid()){
              console.log('updateMarkers: zoom automatico sui marker');
              map.fitBounds(bounds, { padding: [50,50] });
          } else {
              console.warn('updateMarkers: bounds non validi, marker non visibili?');
          }
    }


    function getFilters(){

      function readSelect(id){
        var $el = $('#'+id);
        if (!$el.length) return '';

        // Leggi il VALUE della select (non il testo!)
        var v = ($el.val() || '').toString().trim();

        // Se il value è vuoto o contiene "tutt" (tutte/tutti), ritorna stringa vuota
        if (v === '' || /^tutt/i.test(v)) {
            return '';
        }

        return v;
      }

      const f = {
          province: readSelect('campania-companies-map-province-filter'),
          city:     readSelect('campania-companies-map-city-filter'),
          sector:   readSelect('campania-companies-map-sector-filter'),
          product:  readSelect('campania-companies-map-product-filter'),
          search:   ($('#campania-companies-map-search').val() || '').toString().trim()
        };

        console.log('getFilters() raw:', f);
        return f;

    }


    var xhr = null;
    function fetchData(){
        if(!initMap()) return;
        var f = getFilters();
        console.debug('Filtri correnti RAW:', f);

        // Pulisci filtri: rimuovi valori che contengono "tutt" o sono vuoti
        var cleanFilters = {};

        if(f.province && f.province !== '' && !/tutt/i.test(f.province)) {
            cleanFilters.province = f.province;
        }
        if(f.city && f.city !== '' && !/tutt/i.test(f.city)) {
            cleanFilters.city = f.city;
        }
        if(f.sector && f.sector !== '' && !/tutt/i.test(f.sector)) {
            cleanFilters.sector = f.sector;
        }
        if(f.product && f.product !== '' && !/tutt/i.test(f.product)) {
            cleanFilters.product = f.product;
        }
        if(f.search && f.search !== '') {
            cleanFilters.search = f.search;
        }

        console.log('Filtri PULITI inviati al server:', cleanFilters);

        var $map = $('#'+MAP_ID);

        if(xhr && xhr.readyState !== 4){ xhr.abort(); }

        $map.addClass('loading');

        xhr = $.ajax({
            url: campaniaCompaniesMap.ajax_url,
            method: 'POST',
            dataType: 'json',
            data: $.extend({ action: 'campania_companies_map_get_data', nonce: campaniaCompaniesMap.nonce }, cleanFilters)
        }).done(function(resp){
            dbg('AJAX OK, payload:', resp);
            if (resp && resp.meta) {
              dbg('Server META:', resp.meta);
              hudUpdate({ step: 'AJAX OK', filters: f, serverMeta: resp.meta, items: (resp.items || resp.data || []).length });
            }

            if(resp && resp.success){ updateMarkers(resp.data); }
            else { console.error('Data error', resp); }
        }).fail(function(xhr, s, e){
            hudUpdate({ step: 'AJAX FAIL', status: s, error: e, filters: f });

            console.error('AJAX error:', s, e);
        }).always(function(){
            $map.removeClass('loading');
        });
    }

    function debounce(fn, wait){
        var t; return function(){ var ctx=this, args=arguments; clearTimeout(t); t=setTimeout(function(){ fn.apply(ctx,args); }, wait||300); };
    }

    // Event listeners per TUTTI i filtri
    $(document).on('input', '#campania-companies-map-search', debounce(fetchData, 350));
    $(document).on('change', '#campania-companies-map-province-filter', fetchData);
    $(document).on('change', '#campania-companies-map-city-filter',     fetchData);
    $(document).on('change', '#campania-companies-map-sector-filter',   fetchData);
    $(document).on('change', '#campania-companies-map-product-filter',  fetchData);
    $('#campania-companies-map-search').on('input', debounce(fetchData, 350));

    // Reset: svuota tutti i campi e ricarica
    $('#campania-companies-map-reset-filters').on('click', function(e){
      e.preventDefault();

      function clearSelect(id){
        var $el = $('#'+id);
        if(!$el.length) return;
        // prova 1: imposta vuoto
        $el.val('');
        // prova 2: forza l’indice a 0 (prima opzione, es. “Tutte”)
        if ($el.prop('selectedIndex') !== 0) $el.prop('selectedIndex', 0);
        // notifica eventuali UI custom del tema (Select2, Choices, ecc.)
        $el.trigger('change');
      }

      clearSelect('campania-companies-map-province-filter');
      clearSelect('campania-companies-map-city-filter');
      clearSelect('campania-companies-map-sector-filter');
      clearSelect('campania-companies-map-product-filter');

      var $q = $('#campania-companies-map-search');
      $q.val('').trigger('input');

      // consenti ai select “custom” di sincronizzarsi, poi ricarica i dati
      setTimeout(fetchData, 0);
    });

    // Primo render: FORZA reset filtri e carica tutti i pin
    // Attendi che il DOM e Leaflet siano pronti
    setTimeout(function(){
        console.log('=== CARICAMENTO INIZIALE MAPPA ===');

        // Ignora eventuali preselezionamenti del tema
        $('#campania-companies-map-province-filter').val('').prop('selectedIndex', 0);
        $('#campania-companies-map-city-filter').val('').prop('selectedIndex', 0);
        $('#campania-companies-map-sector-filter').val('').prop('selectedIndex', 0);
        $('#campania-companies-map-product-filter').val('').prop('selectedIndex', 0);
        $('#campania-companies-map-search').val('');

        console.log('Filtri resettati, caricamento tutti i marker...');

        // Attendi che Leaflet sia completamente caricato
        setTimeout(function(){
            fetchData();
        }, 300);
    }, 500);
});
JS;
@file_put_contents( $assets_dir . 'frontend-script.js', $front_js );
}
// Rigenera gli asset se cambia la versione del plugin
function campania_companies_map_maybe_regenerate_assets() {
    $stored = get_option( 'campania_companies_map_assets_version' );
    if ( $stored !== CAMPANIA_COMPANIES_MAP_VERSION ) {
        campania_companies_map_create_asset_files();
        update_option( 'campania_companies_map_assets_version', CAMPANIA_COMPANIES_MAP_VERSION );
    }
}
add_action( 'plugins_loaded', 'campania_companies_map_maybe_regenerate_assets' );
/**
 * Loading spinner CSS in head for map loading state
 */
add_action('wp_head', function() {
    echo '<style>
        #campania-companies-map.loading { position: relative; }
        #campania-companies-map.loading::before {
            content: ""; position: absolute; inset: 0; background: rgba(255,255,255,0.7); z-index: 1000;
        }
        #campania-companies-map.loading::after {
            content: ""; position: absolute; top: 50%; left: 50%; width: 40px; height: 40px;
            margin: -20px 0 0 -20px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db;
            border-radius: 50%; animation: spin 1s linear infinite; z-index: 1001;
        }
        @keyframes spin { 0%{transform:rotate(0)} 100%{transform:rotate(360deg)} }
    </style>';
});

/**
 * Admin assets enqueue now points to uploads (done above);
 * NO automatic asset creation on every request anymore.
 */
// === FEAMPA: invalidateSize + guardia su altezza 0/2px ===
add_action('wp_footer', function () {
    ?>
    <script>
    (function() {
      const MAP_ID = 'campania-companies-map';
      // === DEBUG ===========
        const CCM_DEBUG = (window.CCM_DEBUG === true) || new URLSearchParams(location.search).has('map_debug');
        function dbg(){ if (CCM_DEBUG && window.console) { console.log.apply(console, arguments); } }
        // Pannello HUD
        let __hudEl = null;
        function hudInit(){
          if (!CCM_DEBUG || __hudEl) return;
          __hudEl = document.createElement('div');
          __hudEl.id = 'ccm-hud';
          __hudEl.style.cssText = 'position:fixed;right:12px;bottom:12px;z-index:99999;background:#111;color:#fff;padding:10px 12px;border-radius:10px;font:12px/1.3 system-ui,Arial;max-width:40ch;box-shadow:0 8px 20px rgba(0,0,0,.25);opacity:.95';
          __hudEl.innerHTML = '<b>MAP DEBUG</b><div id="ccm-hud-body" style="margin-top:6px;white-space:pre-wrap"></div>';
          document.body.appendChild(__hudEl);
        }
        function hudUpdate(obj){
          if (!CCM_DEBUG) return;
          hudInit();
          const body = document.getElementById('ccm-hud-body');
          body.textContent = JSON.stringify(obj, null, 2);
        }
        // =====================

      function getMapEl() {
        return document.getElementById(MAP_ID);
      }

      function getLeafletMapInstance() {
        if (window.feampaMap) return window.feampaMap;
        if (window.campaniaMap) return window.campaniaMap;
        if (window.map) return window.map;
        for (const k in window) {
          const v = window[k];
          if (v && v.invalidateSize && v.addLayer && v.getCenter) return v;
        }
        return null;
      }

      function ensureHeightAndInvalidate(when) {
        const el = getMapEl();
        if (!el) return;

        const rect = el.getBoundingClientRect();
        const h = Math.round(rect.height);

        if (h < 300) {
          el.style.height = '70vh';
          el.style.minHeight = '520px';
          const p = el.parentElement;
          if (p && p.getBoundingClientRect().height < 300) {
            p.style.minHeight = '520px';
            p.style.display = 'block';
            p.style.overflow = 'visible';
          }
        }

        const m = getLeafletMapInstance();
        if (m) {
          try {
            m.invalidateSize(true);
            setTimeout(() => m.invalidateSize(true), 200);
            setTimeout(() => m.invalidateSize(true), 600);
          } catch(e) {
            console.warn('[FEAMPA MAP] invalidate error', e);
          }
        }

        console.log(`[FEAMPA MAP] ${when} -> h:${h}px`);
      }

      document.addEventListener('DOMContentLoaded', () => {
        ensureHeightAndInvalidate('DOMContentLoaded');
      });

      window.addEventListener('load', () => {
        ensureHeightAndInvalidate('window.load');
      });

      window.addEventListener('resize', () => {
        ensureHeightAndInvalidate('resize');
      });

      const el = getMapEl();
      if (el && 'ResizeObserver' in window) {
        const ro = new ResizeObserver(() => ensureHeightAndInvalidate('ResizeObserver'));
        ro.observe(el);
      }

      if (document.body.classList.contains('admin-bar')) {
        setTimeout(() => ensureHeightAndInvalidate('admin-bar delayed'), 350);
      }
      document.addEventListener('visibilitychange', () => {
        if (!document.hidden) ensureHeightAndInvalidate('visibilitychange');
      });
    })();
    </script>
    <?php
}, 99);

