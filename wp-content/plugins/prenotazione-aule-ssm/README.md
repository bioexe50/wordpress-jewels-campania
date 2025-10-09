# Prenotazione Aule SSM

Sistema di prenotazione aule per Scuola di Specializzazione Medica.

## Descrizione

Prenotazione Aule SSM è un plugin WordPress professionale sviluppato specificamente per la gestione delle prenotazioni di aule nelle Scuole di Specializzazione Medica. Il sistema offre funzionalità avanzate per coordinare l'uso degli spazi didattici.

## Caratteristiche Principali

### Backend Admin
- **Dashboard SSM**: Statistiche in tempo reale dedicate alla formazione medica
- **Gestione Aule**: CRUD completo per aule con classificazione per specializzazione
- **Sistema di Slot**: Configurazione orari compatibile con turni ospedalieri
- **Gestione Prenotazioni**: Workflow di approvazione personalizzato
- **Sistema Email**: Notifiche a specializzandi e coordinatori
- **Report Avanzati**: Analytics per ottimizzazione utilizzo spazi

### Frontend Pubblico

#### ⭐ Calendario Multi-Slot (NEW v1.1.0)
Sistema avanzato per prenotazione multipla di slot con interfaccia moderna:
- **Selezione Multipla**: Click su una giornata per selezionare più slot contemporaneamente
- **Badge Interattivi**: Slot visualizzati come badge cliccabili con feedback visivo immediato
- **Sidebar Dinamica**: Recap in tempo reale degli slot selezionati
- **Form Unificato**: Compila una sola volta i dati per prenotare tutti gli slot
- **Gruppo Prenotazione**: Gli slot prenotati insieme vengono linkati automaticamente
- **Motivazione Visibile**: Il motivo della prenotazione appare accanto agli slot occupati
- **Design Moderno**: Interfaccia pulita con Dashicons WordPress
- **Bootstrap 5**: Modal responsive e componenti professionali

**Flusso Utente:**
1. Click su giorno nel calendario → Si apre modal con slot disponibili
2. Click sui badge slot per selezionare/deselezionare
3. "Conferma selezione" → Modal si chiude, slot appaiono in sidebar
4. Compila form unico (Nome, Cognome, Email, Motivo)
5. "Prenota tutti gli slot" → Prenotazione multipla con transazione DB atomica

#### Altre Funzionalità Frontend
- **Calendario Interattivo**: Visualizzazione disponibilità in tempo reale
- **Form Prenotazione**: Validazione specifica per utenza medica
- **Shortcode Sistema**: Integrazione semplificata
- **Design Responsive**: Accessibile da dispositivi ospedalieri
- **Ricerca Aule**: Filtri per specializzazione e attrezzature mediche

### Funzionalità SSM-Specifiche
- **REST API**: Integrazione con sistemi gestionali ospedalieri
- **Sistema Cron**: Automazioni per gestione turni
- **Multi-specializzazione**: Supporto per diverse scuole
- **Sicurezza Avanzata**: Compliance GDPR e normative sanitarie
- **Performance**: Ottimizzato per ambienti ospedalieri

## Requisiti Tecnici

- **WordPress**: 6.0 o superiore
- **PHP**: 7.4 o superiore
- **MySQL**: 5.7 o superiore
- **Librerie JavaScript**: jQuery 3.6+, Bootstrap 5
- **Icone**: WordPress Dashicons (nativo)

## Installazione

1. Carica la cartella `prenotazione-aule-ssm` nella directory `/wp-content/plugins/`
2. Attiva il plugin dal pannello admin di WordPress
3. Vai su "Prenotazione Aule SSM" nel menu admin
4. Configura le aule e le specializzazioni

## Database Schema

Il plugin crea 4 tabelle personalizzate con prefisso SSM:

### `jc_prenotazione_aule_ssm_aule`
Informazioni aule (nome, capienza, attrezzature mediche, specializzazione)

### `jc_prenotazione_aule_ssm_slot_disponibilita`
Slot di disponibilità compatibili con orari didattici

### `jc_prenotazione_aule_ssm_prenotazioni`
Prenotazioni con riferimento a specializzandi e coordinatori
- **NEW v1.1.0**: Campo `gruppo_prenotazione` per linkare slot prenotati insieme
- **NEW v1.1.0**: Campo `motivo_prenotazione` per descrivere lo scopo della prenotazione

### `jc_prenotazione_aule_ssm_impostazioni`
Configurazioni globali SSM

## Shortcodes

### Calendario Multi-Slot (Consigliato) ⭐
```php
[prenotazione_aule_ssm_new_calendar aula_id="2"]
```
**Caratteristiche:**
- Interfaccia moderna con selezione multipla slot
- Form unificato per prenotazione gruppo
- Sidebar con recap slot selezionati
- Badge interattivi con Dashicons
- Gestione transazionale delle prenotazioni

### Calendario Classico (Legacy)
```php
[prenotazione_aule_ssm_calendar aula_id="2" view="month"]
```

### Lista Aule
```php
[prenotazione_aule_ssm_list specializzazione="cardiologia"]
```

### Form Ricerca Aule
```php
[prenotazione_aule_ssm_search]
```

**Documentazione Completa:** Vedi [SHORTCODES.md](SHORTCODES.md)

## Architettura Frontend

### File Principali Sistema Multi-Slot

#### Template
- `/public/partials/prenotazione-aule-ssm-new-calendar.php`
  - Struttura HTML calendario con sidebar
  - Modal Bootstrap 5 per selezione slot
  - Form prenotazione multipla integrato

#### JavaScript
- `/public/js/prenotazione-aule-ssm-new-calendar.js`
  - Rendering calendario con disponibilità
  - Gestione selezione multipla badge
  - Aggiornamento sidebar dinamico
  - Submit AJAX prenotazione gruppo
  - Gestione transazioni con rollback

#### CSS
- `/public/css/aule-booking-new-calendar.css` (base)
- `/public/css/prenotazione-aule-ssm-multi-slot.css` (multi-slot)
  - Stili badge selezionabili
  - Sidebar responsive
  - Form styling
  - Stati hover/selected/disabled

#### Backend
- `/public/class-prenotazione-aule-ssm-multi-slot.php`
  - Handler AJAX `prenotazione_aule_ssm_multi_booking`
  - Validazione multi-slot con nonce
  - Transazioni DB atomiche (INSERT multipli)
  - Gestione gruppo_prenotazione automatica

### Design Pattern

**Transazioni Atomiche:**
```php
$wpdb->query('START TRANSACTION');
foreach ($slots as $slot) {
    $insert = $wpdb->insert($table, $data);
    if (!$insert) {
        $wpdb->query('ROLLBACK');
        return error;
    }
}
$wpdb->query('COMMIT');
```

**Selezione Multipla:**
```javascript
Calendar.selectedSlots = [];  // Array globale
function toggleSlotInModal($badge) {
    // Aggiunge/rimuove slot dall'array
    // Aggiorna classe CSS .slot-selected
    // Refresh sidebar UI
}
```

## REST API

Endpoint base: `/wp-json/prenotazione-aule-ssm/v1/`

- `GET /aule` - Lista aule
- `GET /aule/{id}` - Dettagli aula
- `POST /booking` - Crea prenotazione
- `GET /booking/{id}` - Dettagli prenotazione

## Sicurezza

- **Nonce Verification**: Tutti gli endpoint AJAX protetti
- **Sanitizzazione Input**: `sanitize_text_field()`, `sanitize_email()`
- **Prepared Statements**: Query parametrizzate per prevenire SQL injection
- **Capability Check**: Verifica permessi utente WordPress
- **Transazioni DB**: Rollback automatico in caso di errori parziali

## Bug Fix Log

### v1.1.2 (Ottobre 2025)
- **Fix**: Checkbox privacy invisibile (aggiunto `!important` CSS per override Bootstrap)
- **Fix**: Errore salvataggio slot - mismatch formato tempo DB (`08:00` vs `08:00:00`)

### v1.1.0 (Ottobre 2025)
- **Feature**: Sistema calendario multi-slot con selezione badge
- **Feature**: Form unificato prenotazione gruppo
- **Feature**: Campo `motivo_prenotazione` e `gruppo_prenotazione`
- **Feature**: Sidebar dinamica con recap slot selezionati
- **UI**: Sostituzione emoji con Dashicons WordPress
- **UI**: Integrazione Bootstrap 5 modal system

## Licenza

GPL v2 or later

## Supporto

Per problemi o richieste di funzionalità, contatta il team di sviluppo SSM.

---

**Sviluppato per Scuole di Specializzazione Medica**
**Versione Corrente:** 1.1.2
**Ultimo Aggiornamento:** Ottobre 2025
