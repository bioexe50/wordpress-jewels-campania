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
- **Librerie JavaScript**: FullCalendar 6, jQuery 3.6+, Bootstrap 5

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

### `jc_prenotazione_aule_ssm_impostazioni`
Configurazioni globali SSM

## Shortcodes

```php
[prenotazione-aule-ssm-calendar aula_id="1" view="month"]
[prenotazione-aule-ssm-list specializzazione="cardiologia"]
[prenotazione-aule-ssm-search]
```

## REST API

Endpoint base: `/wp-json/prenotazione-aule-ssm/v1/`

- `GET /aule` - Lista aule
- `GET /aule/{id}` - Dettagli aula
- `POST /booking` - Crea prenotazione
- `GET /booking/{id}` - Dettagli prenotazione

## Licenza

GPL v2 or later

## Changelog

### v1.0.0
- Rilascio iniziale per SSM
- Sistema completo gestione aule specializzazione medica
- REST API dedicata
- Database schema ottimizzato
- Frontend responsive per ambiente ospedaliero

---

Sviluppato per Scuole di Specializzazione Medica
