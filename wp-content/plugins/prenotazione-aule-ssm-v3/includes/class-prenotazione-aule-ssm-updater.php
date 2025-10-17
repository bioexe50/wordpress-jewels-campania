<?php
/**
 * Gestisce gli aggiornamenti automatici del plugin
 *
 * Questa classe intercetta i controlli di aggiornamento di WordPress e li redirige
 * verso un endpoint JSON personalizzato sul sito specificato, permettendo
 * aggiornamenti automatici senza passare da WordPress.org
 *
 * @since 3.4.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/includes
 */

class Prenotazione_Aule_SSM_Updater {

    /**
     * URL base del server degli aggiornamenti
     *
     * @since 3.4.0
     * @var string
     */
    private $update_server_url;

    /**
     * Slug del plugin (folder/file.php)
     *
     * @since 3.4.0
     * @var string
     */
    private $plugin_slug;

    /**
     * Percorso file plugin
     *
     * @since 3.4.0
     * @var string
     */
    private $plugin_file;

    /**
     * Versione corrente del plugin
     *
     * @since 3.4.0
     * @var string
     */
    private $version;

    /**
     * Cache delle informazioni aggiornamento (12 ore)
     *
     * @since 3.4.0
     * @var string
     */
    private $cache_key;

    /**
     * Tempo cache in secondi (12 ore)
     *
     * @since 3.4.0
     * @var int
     */
    private $cache_duration = 43200;

    /**
     * Inizializza l'updater
     *
     * @since 3.4.0
     * @param string $plugin_file Percorso file plugin principale
     * @param string $update_server_url URL server aggiornamenti (es. https://raffaelevitulano.com)
     */
    public function __construct($plugin_file, $update_server_url) {
        $this->plugin_file = $plugin_file;
        $this->update_server_url = trailingslashit($update_server_url);
        $this->plugin_slug = plugin_basename($plugin_file);
        $this->version = PRENOTAZIONE_AULE_SSM_VERSION;
        $this->cache_key = 'prenotazione_aule_ssm_update_cache';

        // Hook per intercettare controllo aggiornamenti
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_for_update'));

        // Hook per mostrare informazioni plugin
        add_filter('plugins_api', array($this, 'plugin_info'), 20, 3);

        // Hook per eliminare cache aggiornamenti quando plugin viene aggiornato
        add_action('upgrader_process_complete', array($this, 'purge_cache'), 10, 2);
    }

    /**
     * Controlla se è disponibile un aggiornamento
     *
     * Questo metodo viene chiamato da WordPress quando controlla gli aggiornamenti
     * per tutti i plugin installati.
     *
     * @since 3.4.0
     * @param object $transient Transient con info aggiornamenti
     * @return object Transient modificato
     */
    public function check_for_update($transient) {
        // Se transient non ha campo "checked", skip
        if (empty($transient->checked)) {
            return $transient;
        }

        // Prova a recuperare info dalla cache
        $remote_info = $this->get_cached_update_info();

        if ($remote_info === false) {
            // Cache vuota o scaduta, fetch dal server
            $remote_info = $this->fetch_update_info();

            if ($remote_info !== false) {
                // Salva in cache per 12 ore
                set_transient($this->cache_key, $remote_info, $this->cache_duration);
            }
        }

        // Se fetch fallito, return transient originale
        if ($remote_info === false || !isset($remote_info->version)) {
            return $transient;
        }

        // Confronta versioni: se remota > locale, aggiungi a update list
        if (version_compare($this->version, $remote_info->version, '<')) {
            $plugin_data = array(
                'slug' => dirname($this->plugin_slug),
                'new_version' => $remote_info->version,
                'url' => $remote_info->homepage,
                'package' => $remote_info->download_url,
                'tested' => $remote_info->tested,
                'requires' => $remote_info->requires,
                'requires_php' => $remote_info->requires_php
            );

            // Aggiungi al transient degli aggiornamenti disponibili
            $transient->response[$this->plugin_slug] = (object) $plugin_data;
        } else {
            // Nessun aggiornamento disponibile
            $plugin_data = array(
                'slug' => dirname($this->plugin_slug),
                'new_version' => $remote_info->version,
                'url' => $remote_info->homepage,
                'package' => $remote_info->download_url
            );

            $transient->no_update[$this->plugin_slug] = (object) $plugin_data;
        }

        return $transient;
    }

    /**
     * Fornisce informazioni dettagliate sul plugin
     *
     * Chiamato quando utente clicca "Visualizza dettagli versione X.X.X"
     *
     * @since 3.4.0
     * @param false|object|array $result Risultato precedente
     * @param string $action Azione richiesta
     * @param object $args Argomenti richiesta
     * @return false|object Informazioni plugin o false
     */
    public function plugin_info($result, $action, $args) {
        // Check se la richiesta è per il nostro plugin
        if ($action !== 'plugin_information') {
            return $result;
        }

        if (empty($args->slug) || $args->slug !== dirname($this->plugin_slug)) {
            return $result;
        }

        // Recupera info dalla cache o dal server
        $remote_info = $this->get_cached_update_info();

        if ($remote_info === false) {
            $remote_info = $this->fetch_update_info();
        }

        if ($remote_info === false) {
            return $result;
        }

        // Converti in formato WordPress plugins_api
        $info = new stdClass();
        $info->name = $remote_info->name;
        $info->slug = dirname($this->plugin_slug);
        $info->version = $remote_info->version;
        $info->author = $remote_info->author;
        $info->homepage = $remote_info->homepage;
        $info->requires = $remote_info->requires;
        $info->tested = $remote_info->tested;
        $info->requires_php = $remote_info->requires_php;
        $info->download_link = $remote_info->download_url;
        $info->sections = array(
            'description' => $remote_info->sections->description,
            'installation' => $remote_info->sections->installation,
            'changelog' => $remote_info->sections->changelog
        );

        if (isset($remote_info->banners)) {
            $info->banners = array(
                'high' => $remote_info->banners->high,
                'low' => $remote_info->banners->low
            );
        }

        return $info;
    }

    /**
     * Recupera informazioni aggiornamento dalla cache
     *
     * @since 3.4.0
     * @return false|object Informazioni o false se cache vuota
     */
    private function get_cached_update_info() {
        return get_transient($this->cache_key);
    }

    /**
     * Scarica informazioni aggiornamento dal server
     *
     * Fa una richiesta HTTP al server degli aggiornamenti per recuperare
     * il file JSON con le informazioni della versione più recente.
     *
     * Formato JSON atteso:
     * {
     *   "name": "Prenotazione Aule SSM",
     *   "slug": "prenotazione-aule-ssm-v3",
     *   "version": "3.4.0",
     *   "download_url": "https://example.com/plugin.zip",
     *   "requires": "6.0",
     *   "tested": "6.4",
     *   "requires_php": "7.4",
     *   "author": "SSM Developer Team",
     *   "homepage": "https://example.com",
     *   "sections": {
     *     "description": "Plugin description...",
     *     "installation": "Installation instructions...",
     *     "changelog": "Changelog content..."
     *   }
     * }
     *
     * @since 3.4.0
     * @return false|object Informazioni o false se errore
     */
    private function fetch_update_info() {
        // Costruisci URL endpoint JSON
        $url = $this->update_server_url . 'wp-json/prenotazione-aule-ssm/v1/update-info';

        // Aggiungi parametri query per tracciare versione corrente
        $url = add_query_arg(
            array(
                'version' => $this->version,
                'slug' => dirname($this->plugin_slug)
            ),
            $url
        );

        // Richiesta HTTP GET
        $response = wp_remote_get(
            $url,
            array(
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/json'
                )
            )
        );

        // Check errori HTTP
        if (is_wp_error($response)) {
            error_log('Prenotazione Aule SSM Updater: Errore fetch update info - ' . $response->get_error_message());
            return false;
        }

        // Check status code
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            error_log('Prenotazione Aule SSM Updater: HTTP ' . $response_code . ' dal server aggiornamenti');
            return false;
        }

        // Parse JSON
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        // Validazione JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Prenotazione Aule SSM Updater: JSON invalido dal server');
            return false;
        }

        // Validazione campi obbligatori
        $required_fields = array('name', 'version', 'download_url', 'requires', 'tested', 'requires_php');
        foreach ($required_fields as $field) {
            if (!isset($data->$field)) {
                error_log('Prenotazione Aule SSM Updater: Campo obbligatorio mancante: ' . $field);
                return false;
            }
        }

        return $data;
    }

    /**
     * Elimina cache aggiornamenti dopo un aggiornamento
     *
     * @since 3.4.0
     * @param WP_Upgrader $upgrader Istanza upgrader
     * @param array $options Opzioni upgrade
     */
    public function purge_cache($upgrader, $options) {
        if ($options['action'] === 'update' && $options['type'] === 'plugin') {
            delete_transient($this->cache_key);
        }
    }

    /**
     * Forza controllo immediato aggiornamenti (bypassa cache)
     *
     * Utile per testing o per forzare check dopo pubblicazione nuova versione
     *
     * @since 3.4.0
     * @return false|object Informazioni aggiornamento o false
     */
    public function force_check() {
        delete_transient($this->cache_key);
        return $this->fetch_update_info();
    }
}
