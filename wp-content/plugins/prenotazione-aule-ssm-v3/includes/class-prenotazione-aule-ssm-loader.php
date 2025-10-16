<?php

/**
 * Registra tutti gli actions e filters per il plugin
 *
 * Questa classe mantiene una lista di tutti gli hooks che sono registrati nel plugin,
 * e li registra con WordPress quando il plugin viene caricato
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/includes
 */

class Prenotazione_Aule_SSM_Loader {

    /**
     * L'array degli actions registrati con WordPress
     *
     * @since 1.0.0
     * @access protected
     * @var array $actions Gli actions registrati con WordPress per caricare il plugin
     */
    protected $actions;

    /**
     * L'array dei filters registrati con WordPress
     *
     * @since 1.0.0
     * @access protected
     * @var array $filters I filters registrati con WordPress per caricare il plugin
     */
    protected $filters;

    /**
     * Inizializza le collections utilizzate per mantenere gli actions e filters
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
    }

    /**
     * Aggiunge un nuovo action alla collection da registrare con WordPress
     *
     * @since 1.0.0
     * @param string $hook Il nome dell'action a cui $component_and_callback è hooked
     * @param object $component Una referenza all'istanza dell'oggetto su cui è definito il callback
     * @param string $callback Il nome della definizione della funzione sull'istanza $component
     * @param int $priority Opzionale. La priorità a cui deve essere eseguita la funzione. Default 10
     * @param int $accepted_args Opzionale. Il numero di argomenti che dovrebbe accettare la funzione. Default 1
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Aggiunge un nuovo filter alla collection da registrare con WordPress
     *
     * @since 1.0.0
     * @param string $hook Il nome del filter a cui $component_and_callback è hooked
     * @param object $component Una referenza all'istanza dell'oggetto su cui è definito il callback
     * @param string $callback Il nome della definizione della funzione sull'istanza $component
     * @param int $priority Opzionale. La priorità a cui deve essere eseguita la funzione. Default 10
     * @param int $accepted_args Opzionale. Il numero di argomenti che dovrebbe accettare la funzione. Default 1
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Metodo di utilità che è utilizzato per registrare gli actions e hooks in un singolo array
     *
     * @since 1.0.0
     * @access private
     * @param array $hooks Gli hooks attualmente registrati con WordPress
     * @param string $hook Il nome dell'hook a cui $component_and_callback è hooked
     * @param object $component Una referenza all'istanza dell'oggetto su cui è definito il callback
     * @param string $callback Il nome della definizione della funzione sull'istanza $component
     * @param int $priority La priorità a cui deve essere eseguita la funzione
     * @param int $accepted_args Il numero di argomenti che dovrebbe accettare la funzione
     * @return array La collection aggiornata di actions e filters
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {

        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    /**
     * Registra i filters e actions con WordPress
     *
     * @since 1.0.0
     */
    public function run() {

        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }
    }
}