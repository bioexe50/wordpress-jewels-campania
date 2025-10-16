<?php

/**
 * Definisce la funzionalità di internazionalizzazione
 *
 * Carica e definisce i file di internazionalizzazione per questo plugin
 * così che sia pronto per la traduzione
 *
 * @since 1.0.0
 * @package WP_Prenotazione_Aule_SSM
 * @subpackage WP_Prenotazione_Aule_SSM/includes
 */

class Prenotazione_Aule_SSM_i18n {

    /**
     * Carica il plugin text domain per la traduzione
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'prenotazione-aule-ssm',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}