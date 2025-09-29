# WP Aule Booking

Sistema completo di prenotazione aule studio per WordPress.

## Descrizione

WP Aule Booking è un plugin WordPress professionale che permette di gestire le prenotazioni di aule studio con un sistema di booking avanzato. Il plugin offre un'interfaccia amministrativa completa per la gestione delle aule e delle prenotazioni, oltre a un frontend user-friendly con calendario interattivo.

## Caratteristiche Principali

### Backend Admin
- **Dashboard Completa**: Statistiche in tempo reale e panoramica delle prenotazioni
- **Gestione Aule**: CRUD completo per aule con immagini, attrezzature e ubicazioni
- **Sistema di Slot**: Configurazione flessibile degli orari di disponibilità
- **Gestione Prenotazioni**: Approvazione/rifiuto manuale o automatico
- **Sistema Email**: Notifiche personalizzabili per utenti e amministratori
- **Report Avanzati**: Statistiche dettagliate e export dati

### Frontend Pubblico
- **Calendario Interattivo**: FullCalendar.js v6 con vista mensile/settimanale/giornaliera
- **Form di Prenotazione**: Validazione completa e reCAPTCHA v3
- **Shortcode Flessibili**: Integrazione facile in pagine e post
- **Design Responsive**: Ottimizzato per mobile e desktop
- **Ricerca Aule**: Filtri avanzati per trovare l'aula ideale

### Funzionalità Avanzate
- **REST API Completa**: Endpoint per integrazioni esterne
- **Sistema Cron**: Pulizia automatica e invio reminder
- **Multi-lingua Ready**: Preparato per traduzioni
- **Sicurezza Avanzata**: Nonce, sanitizzazione, rate limiting
- **Performance Ottimizzate**: Cache intelligente e query ottimizzate

## Requisiti Tecnici

- **WordPress**: 6.0 o superiore
- **PHP**: 7.4 o superiore
- **MySQL**: 5.7 o superiore
- **Librerie JavaScript**: FullCalendar 6, jQuery 3.6+, Bootstrap 5

## Installazione

1. Carica la cartella `wp-aule-booking` nella directory `/wp-content/plugins/`
2. Attiva il plugin dal pannello admin di WordPress
3. Vai su "Gestione Aule" nel menu admin per configurare il plugin
4. Crea le tue prime aule e configura gli slot di disponibilità

## Configurazione Iniziale

### 1. Creazione Aule
- Vai su "Gestione Aule" → "Aggiungi Aula"
- Compila tutti i campi richiesti (nome, descrizione, ubicazione)
- Seleziona le attrezzature disponibili
- Carica immagini dell'aula (opzionale)

### 2. Configurazione Slot
- Vai su "Slot Disponibilità"
- Seleziona un'aula
- Configura gli orari usando il generatore automatico
- Imposta giorni della settimana, orari e durata degli slot

### 3. Impostazioni Sistema
- Vai su "Impostazioni"
- Configura approvazione automatica/manuale
- Imposta email di notifica per gli amministratori
- Personalizza i template email
- Configura reCAPTCHA (opzionale)

## Utilizzo Shortcode

### Calendario Prenotazioni
```php
[aule_booking_calendar aula_id="1" view="month" show_legend="true" allow_booking="true"]
```

**Parametri:**
- `aula_id`: ID dell'aula (obbligatorio)
- `view`: Vista iniziale (`month`, `week`, `day`)
- `show_legend`: Mostra legenda colori
- `allow_booking`: Abilita prenotazioni dal frontend

### Lista Aule
```php
[aule_booking_list stato="attiva" show_details="true" show_booking_link="true"]
```

**Parametri:**
- `stato`: Filtra per stato aula
- `show_details`: Mostra dettagli aula
- `show_booking_link`: Mostra link per prenotazione

### Form di Ricerca
```php
[aule_booking_search show_filters="true"]
```

## REST API

Il plugin espone una REST API completa per integrazioni esterne:

### Endpoint Principali

- `GET /wp-json/aule-booking/v1/aule` - Lista aule
- `GET /wp-json/aule-booking/v1/aule/{id}` - Dettagli aula
- `GET /wp-json/aule-booking/v1/availability/{aula_id}` - Disponibilità aula
- `POST /wp-json/aule-booking/v1/booking` - Crea prenotazione
- `GET /wp-json/aule-booking/v1/booking/{id}` - Dettagli prenotazione
- `PUT /wp-json/aule-booking/v1/booking/{id}/status` - Aggiorna stato

### Esempio Utilizzo API

```javascript
// Ottieni lista aule
fetch('/wp-json/aule-booking/v1/aule')
  .then(response => response.json())
  .then(aule => console.log(aule));

// Crea nuova prenotazione
fetch('/wp-json/aule-booking/v1/booking', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    aula_id: 1,
    nome_richiedente: 'Mario',
    cognome_richiedente: 'Rossi',
    email_richiedente: 'mario@example.com',
    motivo_prenotazione: 'Studio gruppo',
    data_prenotazione: '2024-03-15',
    ora_inizio: '09:00:00',
    ora_fine: '11:00:00'
  })
});
```

## Database Schema

Il plugin crea 4 tabelle personalizzate:

### `wp_aule_booking_aule`
Contiene informazioni delle aule (nome, descrizione, capienza, attrezzature, etc.)

### `wp_aule_booking_slot_disponibilita`
Definisce gli slot di disponibilità per ogni aula con ricorrenze

### `wp_aule_booking_prenotazioni`
Memorizza tutte le prenotazioni con stati e metadati

### `wp_aule_booking_impostazioni`
Configurazioni globali del plugin

## Personalizzazione

### Hook Disponibili

```php
// Actions
do_action('aule_booking_before_save', $booking_data);
do_action('aule_booking_after_approval', $booking_id);
do_action('aule_booking_after_rejection', $booking_id);

// Filters
$slots = apply_filters('aule_booking_available_slots', $slots, $aula_id, $date);
$headers = apply_filters('aule_booking_email_headers', $headers);
$events = apply_filters('aule_booking_calendar_events', $events);
```

### Personalizzazione CSS

Il plugin include CSS variabili per personalizzazione facile:

```css
.aule-booking-wrapper {
    --primary-color: #2271b1;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
}
```

## Troubleshooting

### Problemi Comuni

**Il calendario non si carica**
- Verifica che jQuery e FullCalendar siano caricati correttamente
- Controlla la console browser per errori JavaScript
- Assicurati che l'aula abbia slot configurati

**Le email non vengono inviate**
- Verifica configurazione SMTP di WordPress
- Controlla i template email nelle impostazioni
- Verifica che gli indirizzi email siano validi

**Errori di permessi**
- Assicurati che l'utente abbia i permessi `manage_aule_booking`
- Verifica la configurazione dei ruoli utente

### Log di Debug

Abilita il debug WordPress per vedere i log dettagliati:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

I log del plugin saranno in `/wp-content/debug.log` con prefisso `[Aule Booking]`.

## Supporto

Per supporto, segnalazioni bug o richieste funzionalità:

- Controlla la documentazione completa
- Verifica i requisiti di sistema
- Abilita il debug per raccogliere informazioni dettagliate

## Roadmap

- [ ] Integrazione con sistemi di pagamento
- [ ] App mobile dedicata
- [ ] Integrazione calendario Google/Outlook
- [ ] Sistema di recensioni aule
- [ ] Dashboard analytics avanzate
- [ ] Multi-sede support

## Licenza

GPL v2 or later

## Changelog

### v1.0.0
- Rilascio iniziale
- Sistema completo di gestione aule e prenotazioni
- REST API completa
- Sistema email avanzato
- Frontend responsivo con FullCalendar
- Backend admin completo

---

Sviluppato con ❤️ per la comunità WordPress