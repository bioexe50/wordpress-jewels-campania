=== Prenotazione Aule SSM ===
Contributors: ssmdev
Tags: booking, reservation, rooms, calendar, multi-slot
Requires at least: 6.0
Tested up to: 6.8
Stable tag: 2.1.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sistema completo di prenotazione aule con calendario multi-slot, gestione attrezzature e interfaccia moderna.

== Description ==

**Prenotazione Aule SSM** è un plugin WordPress professionale per la gestione delle prenotazioni di aule e spazi. Perfetto per scuole, università, centri di formazione, coworking e qualsiasi struttura che necessiti di un sistema di booking avanzato.

= Caratteristiche Principali =

* 📅 **Calendario Multi-Slot**: Prenota più fasce orarie contemporaneamente con un solo form
* 🏢 **Gestione Aule Complete**: Capienza, ubicazione, attrezzature, immagini
* ✅ **Sistema Approvazioni**: Workflow approvazione/rifiuto prenotazioni
* 📊 **Reports & Analytics**: Statistiche utilizzo con grafici interattivi
* 📧 **Email Automatiche**: Notifiche personalizzabili con placeholder dinamici
* 🎨 **Design Moderno**: Interfaccia stile Calendly con Bootstrap 5
* 📱 **Responsive**: Funziona perfettamente su desktop, tablet e mobile
* 🔒 **Sicuro**: Nonce verification, prepared statements, transazioni DB

= Nuovo in v2.1.3 =

* ✅ Fix visualizzazione icone Dashicons nelle attrezzature
* ✅ Template lista aule card-based responsive
* ✅ Shortcode `[prenotazione_aule_ssm_list]` con parametri configurabili
* ✅ Content Security Policy aggiornato per font inline
* ✅ Icone native WordPress (Dashicons) integrate

= Shortcodes Disponibili =

**Calendario Prenotazioni**:
`[prenotazione_aule_ssm_new_calendar aula_id="1"]`

**Lista Aule**:
`[prenotazione_aule_ssm_list stato="attiva" show_details="true"]`

**Form Ricerca**:
`[prenotazione_aule_ssm_search]`

= Requisiti =

* WordPress 6.0 o superiore
* PHP 7.4 o superiore  
* MySQL 5.7 o superiore

= Supporto =

Per documentazione completa, visita la sezione "Installation" o consulta i file MD inclusi nel plugin.

== Installation ==

= Installazione Automatica =

1. Vai su "Plugin" > "Aggiungi Nuovo" nel tuo WordPress
2. Cerca "Prenotazione Aule SSM"
3. Clicca "Installa Ora" e poi "Attiva"

= Installazione Manuale =

1. Scarica il file .zip del plugin
2. Vai su "Plugin" > "Aggiungi Nuovo" > "Carica Plugin"
3. Seleziona il file .zip e clicca "Installa Ora"
4. Attiva il plugin

= Configurazione Iniziale =

1. Vai su **Gestione Aule** > **Aggiungi Aula**
2. Crea la tua prima aula con nome, capienza, ubicazione e attrezzature
3. Vai su **Slot Disponibilità** e configura gli orari disponibili
4. Crea una pagina e inserisci lo shortcode: `[prenotazione_aule_ssm_new_calendar aula_id="1"]`
5. Pubblica la pagina e inizia a ricevere prenotazioni!

= Shortcode Parametri =

**Calendario**:
* `aula_id` (obbligatorio): ID dell'aula
* `show_legend` (opzionale): Mostra legenda (default: true)
* `allow_booking` (opzionale): Abilita prenotazioni (default: true)

**Lista Aule**:
* `stato` (opzionale): Filtra per stato (attiva, non_disponibile, manutenzione)
* `ubicazione` (opzionale): Filtra per ubicazione
* `show_details` (opzionale): Mostra dettagli (default: true)
* `show_booking_link` (opzionale): Mostra bottone prenota (default: true)

== Frequently Asked Questions ==

= Come creo una nuova aula? =

Vai su **Gestione Aule** > **Aggiungi Aula** nel menu amministrazione. Compila il form con nome, descrizione, capienza, ubicazione e seleziona le attrezzature disponibili.

= Come configuro gli slot orari? =

Vai su **Slot Disponibilità**, seleziona un'aula, imposta giorno della settimana, orario inizio/fine e periodo di validità. Puoi creare slot ricorrenti per ogni giorno.

= Posso prenotare più slot contemporaneamente? =

Sì! Il nuovo calendario multi-slot permette di selezionare più fasce orarie con un solo form di prenotazione. Gli slot vengono raggruppati automaticamente.

= Come gestisco le approvazioni? =

Le prenotazioni possono essere configurate per richiedere approvazione. Vai su **Prenotazioni** nel menu admin per approvare o rifiutare le richieste.

= Posso personalizzare le email? =

Sì, vai su **Impostazioni** e configura i template email usando i placeholder disponibili (es. {nome_richiedente}, {data_prenotazione}, {ora_inizio}, etc.). Vedi EMAIL_PLACEHOLDERS.md per la lista completa.

= Il plugin è responsive? =

Assolutamente sì! Tutti i template (calendario, lista aule, form) sono completamente responsive e mobile-first.

= Posso esportare le prenotazioni? =

Sì, la pagina **Reports** include funzionalità di export CSV con filtri personalizzabili per data e stato.

= Quali icone sono supportate? =

Il plugin usa Dashicons (icone native WordPress) per attrezzature e UI. Icone disponibili: desktop, laptop, video-alt3, microphone, networking, printer, clipboard, cloud.

= Il plugin è sicuro? =

Sì, implementiamo: nonce verification, prepared statements SQL, transazioni atomiche, input sanitization, output escaping e Content Security Policy.

== Screenshots ==

1. Calendario multi-slot con selezione badge interattivi
2. Modale giornaliera per selezione slot disponibili
3. Sidebar con recap slot selezionati
4. Lista aule card-based responsive
5. Dashboard admin con statistiche prenotazioni
6. Form approvazione/rifiuto con note admin
7. Pagina reports con grafici Chart.js
8. Gestione slot disponibilità settimanali

== Changelog ==

= 2.1.3 (12 Ottobre 2025) =
* Fix: Visualizzazione icone Dashicons nelle attrezzature
* Fix: Content Security Policy per font data: URLs
* Fix: Icona Wi-Fi cambiata da dashicons-wifi (non esistente) a dashicons-networking
* Nuovo: Template lista aule card-based responsive
* Nuovo: Shortcode [prenotazione_aule_ssm_list] con parametri configurabili
* Nuovo: Documentazione completa (DASHICONS_FIX_DOCUMENTATION.md, AULE_LIST_UX_IMPROVEMENTS.md)
* Miglioramento: CSS Dashicons styling per `.facility-tag`
* Miglioramento: Enqueue Dashicons in admin e public

= 2.1.2 (10 Ottobre 2025) =
* Fix: Shortcode errato nelle schede aula (ora usa prenotazione_aule_ssm_new_calendar)

= 2.1.1 (10 Ottobre 2025) =
* Fix Critico: Errore durante disinstallazione plugin
* Nuovo: File uninstaller completo con pulizia database

= 2.1.0 (9 Ottobre 2025) =
* Nuovo: Documentazione email placeholder completa
* Nuovo: Sistema reports con Chart.js 4.4.0
* Nuovo: Filtri temporali avanzati
* Nuovo: Modali Bootstrap 5 professionali
* Fix: Reports mostra dati zero
* Fix: Contatori prenotazioni non aggiornati

= 1.1.2 (Ottobre 2025) =
* Fix: Checkbox privacy invisibile
* Fix: Errore salvataggio slot mismatch formato tempo
* Miglioramento: Cache busting con version bump

= 1.1.0 (Ottobre 2025) =
* Nuovo: Sistema calendario multi-slot completo
* Nuovo: Interfaccia moderna stile Calendly
* Nuovo: Shortcode [prenotazione_aule_ssm_new_calendar]
* Nuovo: Campo gruppo_prenotazione per slot multipli
* Nuovo: Campo motivo_prenotazione
* Miglioramento: Emoji → Dashicons
* Miglioramento: Bootstrap 5 integration

== Upgrade Notice ==

= 2.1.3 =
Fix importante per visualizzazione icone Dashicons. Update consigliato per tutti gli utenti. Include nuovo template lista aule.

= 2.1.1 =
Fix critico per disinstallazione. Update obbligatorio se si è verificato errore durante rimozione plugin.

= 1.1.0 =
Major update con sistema multi-slot. Richiede aggiornamento database (automatico all'attivazione).

== Privacy Policy ==

Questo plugin raccoglie e memorizza le seguenti informazioni fornite dagli utenti durante la prenotazione:
* Nome e Cognome
* Indirizzo email
* Motivo della prenotazione (opzionale)

Le informazioni sono memorizzate nel database WordPress e vengono utilizzate esclusivamente per:
* Gestire le prenotazioni aule
* Inviare email di conferma/rifiuto
* Generare statistiche aggregate (senza dati personali)

Il plugin NON:
* Condivide dati con servizi terzi
* Traccia comportamenti utente
* Usa cookies per profilazione
* Invia dati all'esterno del sito

L'amministratore del sito è responsabile del trattamento dati secondo GDPR.

== Support ==

Per supporto tecnico, documentazione o segnalare bug:

* Email: support@example.com
* GitHub: https://github.com/username/prenotazione-aule-ssm
* Documentazione: Vedi files .md inclusi nel plugin

== Credits ==

* Bootstrap 5: https://getbootstrap.com
* Chart.js: https://www.chartjs.org
* Dashicons: https://developer.wordpress.org/resource/dashicons

== License ==

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
