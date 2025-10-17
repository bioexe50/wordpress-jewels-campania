<?php
/**
 * Endpoint REST API per informazioni aggiornamenti plugin
 *
 * Questo endpoint fornisce un JSON con informazioni sulla versione più recente
 * del plugin, permettendo ad altre installazioni di controllare e scaricare aggiornamenti.
 *
 * Endpoint: /wp-json/prenotazione-aule-ssm/v1/update-info
 *
 * @since 3.4.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/includes
 */

class Prenotazione_Aule_SSM_Update_Endpoint {

    /**
     * Namespace REST API
     *
     * @since 3.4.0
     * @var string
     */
    private $namespace = 'prenotazione-aule-ssm/v1';

    /**
     * URL download del file ZIP plugin
     *
     * @since 3.4.0
     * @var string
     */
    private $download_url;

    /**
     * Inizializza endpoint
     *
     * @since 3.4.0
     * @param string $download_url URL download ZIP plugin
     */
    public function __construct($download_url = '') {
        $this->download_url = $download_url;
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    /**
     * Registra rotte REST API
     *
     * @since 3.4.0
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/update-info',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_update_info'),
                'permission_callback' => '__return_true', // Pubblico (necessario per aggiornamenti)
                'args' => array(
                    'version' => array(
                        'required' => false,
                        'type' => 'string',
                        'description' => 'Versione corrente installata (per logging)',
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    'slug' => array(
                        'required' => false,
                        'type' => 'string',
                        'description' => 'Slug del plugin (per validazione)',
                        'sanitize_callback' => 'sanitize_text_field'
                    )
                )
            )
        );
    }

    /**
     * Restituisce informazioni aggiornamento in formato JSON
     *
     * Questo metodo genera dinamicamente le informazioni dalla versione
     * attualmente installata del plugin e dal CHANGELOG.md
     *
     * @since 3.4.0
     * @param WP_REST_Request $request Richiesta REST
     * @return WP_REST_Response Risposta JSON
     */
    public function get_update_info($request) {
        // Log richiesta per statistiche (opzionale)
        $requesting_version = $request->get_param('version');
        $requesting_slug = $request->get_param('slug');

        if ($requesting_version && $requesting_slug) {
            // Puoi loggare queste info per analytics
            error_log("Update check from: {$requesting_slug} v{$requesting_version}");
        }

        // Genera informazioni dalla versione corrente
        $plugin_data = $this->get_plugin_data();
        $changelog = $this->get_latest_changelog();

        // Costruisci risposta JSON
        $response_data = array(
            'name' => $plugin_data['Name'],
            'slug' => 'prenotazione-aule-ssm-v3',
            'version' => $plugin_data['Version'],
            'download_url' => $this->get_download_url(),
            'requires' => $plugin_data['RequiresWP'],
            'tested' => $plugin_data['Tested'],
            'requires_php' => $plugin_data['RequiresPHP'],
            'author' => $plugin_data['Author'],
            'author_profile' => $plugin_data['AuthorURI'],
            'homepage' => $plugin_data['PluginURI'],
            'last_updated' => $this->get_last_update_date(),
            'sections' => array(
                'description' => $plugin_data['Description'],
                'installation' => $this->get_installation_instructions(),
                'changelog' => $changelog
            ),
            'banners' => array(
                'high' => '', // Opzionale: URL banner 1544x500
                'low' => ''   // Opzionale: URL banner 772x250
            )
        );

        return new WP_REST_Response($response_data, 200);
    }

    /**
     * Recupera dati plugin dal file principale
     *
     * @since 3.4.0
     * @return array Dati plugin
     */
    private function get_plugin_data() {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugin_file = WP_PLUGIN_DIR . '/prenotazione-aule-ssm-v3/prenotazione-aule-ssm.php';

        if (!file_exists($plugin_file)) {
            // Fallback con valori di default
            return array(
                'Name' => 'Prenotazione Aule SSM',
                'Version' => PRENOTAZIONE_AULE_SSM_VERSION,
                'RequiresWP' => '6.0',
                'Tested' => '6.4',
                'RequiresPHP' => '7.4',
                'Author' => 'Raffaele Vitulano',
                'AuthorURI' => 'https://raffaelevitulano.com',
                'PluginURI' => 'https://raffaelevitulano.com',
                'Description' => 'Sistema completo di gestione prenotazioni aule per istituzioni educative.'
            );
        }

        $data = get_plugin_data($plugin_file, false, false);

        // Aggiungi campi mancanti che get_plugin_data() non legge
        if (!isset($data['Tested']) || empty($data['Tested'])) {
            $data['Tested'] = '6.4';  // WordPress 6.4
        }

        if (!isset($data['RequiresWP']) || empty($data['RequiresWP'])) {
            $data['RequiresWP'] = '6.0';
        }

        return $data;
    }

    /**
     * Recupera ultimo changelog dal file CHANGELOG.md
     *
     * @since 3.4.0
     * @return string Changelog formattato HTML
     */
    private function get_latest_changelog() {
        $changelog_file = WP_PLUGIN_DIR . '/prenotazione-aule-ssm-v3/CHANGELOG.md';

        if (!file_exists($changelog_file)) {
            return '<p>Nessun changelog disponibile.</p>';
        }

        $content = file_get_contents($changelog_file);

        // Parse Markdown: converti ## in <h3>, ### in <h4>, - in <li>
        $html = $this->markdown_to_html($content);

        // Limita alle ultime 3 versioni per non appesantire
        $html = $this->limit_changelog_versions($html, 3);

        return $html;
    }

    /**
     * Converte Markdown basilare in HTML
     *
     * @since 3.4.0
     * @param string $markdown Contenuto Markdown
     * @return string HTML
     */
    private function markdown_to_html($markdown) {
        // Headers
        $html = preg_replace('/^## (.+)$/m', '<h3>$1</h3>', $markdown);
        $html = preg_replace('/^### (.+)$/m', '<h4>$1</h4>', $html);

        // Liste
        $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.*<\/li>\n?)+/s', '<ul>$0</ul>', $html);

        // Code blocks
        $html = preg_replace('/```([a-z]*)\n(.*?)\n```/s', '<pre><code class="$1">$2</code></pre>', $html);

        // Bold e italic
        $html = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $html);

        // Paragrafi
        $html = wpautop($html);

        return $html;
    }

    /**
     * Limita changelog a N versioni
     *
     * @since 3.4.0
     * @param string $html Changelog HTML
     * @param int $limit Numero versioni
     * @return string HTML limitato
     */
    private function limit_changelog_versions($html, $limit = 3) {
        // Divide per h3 (versioni)
        $parts = preg_split('/(<h3>.*?<\/h3>)/s', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        if (count($parts) <= ($limit * 2 + 1)) {
            return $html; // Meno versioni del limite, return tutto
        }

        // Prendi solo le prime N versioni
        $result = array_slice($parts, 0, ($limit * 2 + 1));

        return implode('', $result) . '<p><em>... (versioni precedenti omesse)</em></p>';
    }

    /**
     * Restituisce istruzioni installazione
     *
     * @since 3.4.0
     * @return string HTML
     */
    private function get_installation_instructions() {
        return '<ol>
            <li>Scarica il file ZIP del plugin</li>
            <li>Vai su WordPress Admin → Plugin → Aggiungi nuovo</li>
            <li>Clicca "Carica plugin" e seleziona il file ZIP</li>
            <li>Attiva il plugin dopo l\'installazione</li>
            <li>Vai su Prenotazione Aule → Impostazioni per configurare</li>
        </ol>
        <p><strong>Nota</strong>: Il plugin supporta aggiornamenti automatici. Dopo la prima installazione,
        gli aggiornamenti verranno rilevati automaticamente da WordPress.</p>';
    }

    /**
     * Restituisce URL download ZIP plugin
     *
     * Se non specificato nel costruttore, genera URL dinamico
     *
     * @since 3.4.0
     * @return string URL download
     */
    private function get_download_url() {
        if (!empty($this->download_url)) {
            return $this->download_url;
        }

        // Genera URL dinamico basato su versione corrente
        // Esempio: https://raffaelevitulano.com/downloads/prenotazione-aule-ssm-v3.4.0.zip
        $version = PRENOTAZIONE_AULE_SSM_VERSION;
        return home_url("/downloads/prenotazione-aule-ssm-v{$version}.zip");
    }

    /**
     * Restituisce data ultimo aggiornamento in formato ISO 8601
     *
     * @since 3.4.0
     * @return string Data ISO 8601
     */
    private function get_last_update_date() {
        $changelog_file = WP_PLUGIN_DIR . '/prenotazione-aule-ssm-v3/CHANGELOG.md';

        if (file_exists($changelog_file)) {
            $modified_time = filemtime($changelog_file);
            return date('c', $modified_time); // ISO 8601 format
        }

        return date('c'); // Fallback: data corrente
    }

    /**
     * Metodo helper per testare endpoint
     *
     * Uso: da WordPress Admin Tools → Site Health → Info → Browser
     * Naviga a: /wp-json/prenotazione-aule-ssm/v1/update-info
     *
     * @since 3.4.0
     * @return array Info endpoint
     */
    public static function test_endpoint() {
        $url = rest_url('prenotazione-aule-ssm/v1/update-info');
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return array(
                'status' => 'error',
                'message' => $response->get_error_message()
            );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return array(
            'status' => 'success',
            'url' => $url,
            'data' => $data
        );
    }
}
