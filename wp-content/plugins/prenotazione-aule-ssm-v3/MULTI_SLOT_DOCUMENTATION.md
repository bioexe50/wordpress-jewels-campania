# Documentazione Sistema Multi-Slot Booking
## Plugin: Prenotazione Aule SSM

**Versione**: 1.0.0
**Data Implementazione**: 9 Ottobre 2025
**Autore**: Sviluppo SSM

---

## 📑 Indice

1. [Panoramica](#panoramica)
2. [Architettura](#architettura)
3. [File Componenti](#file-componenti)
4. [Flusso Utente](#flusso-utente)
5. [Database Schema](#database-schema)
6. [API Endpoints](#api-endpoints)
7. [Frontend JavaScript](#frontend-javascript)
8. [Backend PHP](#backend-php)
9. [Personalizzazione](#personalizzazione)
10. [Testing](#testing)
11. [Troubleshooting](#troubleshooting)

---

## 📖 Panoramica

Il sistema Multi-Slot Booking permette agli utenti di:
- Selezionare **multipli slot temporali** in una singola sessione
- Visualizzare **prenotazioni esistenti** per una data specifica
- Compilare il **form di prenotazione una sola volta** per tutti gli slot selezionati
- Ricevere **conferma email** automatica

### Vantaggi

- ✅ **UX migliorata**: Evita ripetizioni nella compilazione del form
- ✅ **Prenotazioni raggruppate**: Identificabili tramite `gruppo_prenotazione`
- ✅ **Controllo conflitti**: Verifica in tempo reale disponibilità slot
- ✅ **Transazionalità**: Salvataggio atomico (tutto o niente)
- ✅ **Branding SSM**: Colori e stile personalizzati (#b64c3c)

---

## 🏗️ Architettura

### Diagramma Flusso

```
┌─────────────┐
│   Utente    │
│  Click data │
└──────┬──────┘
       │
       ▼
┌─────────────────────────┐
│  Modal Multi-Slot       │
│  - Griglia slot         │
│  - Prenotazioni esistenti│
└──────┬──────────────────┘
       │
       ▼ (Selezione slot)
┌─────────────────────────┐
│  Form Prenotazione      │
│  - Cognome/Nome         │
│  - Email                │
│  - Motivo               │
└──────┬──────────────────┘
       │
       ▼ (Submit)
┌─────────────────────────┐
│  Backend Validation     │
│  - Check disponibilità  │
│  - Transazione DB       │
└──────┬──────────────────┘
       │
       ▼
┌─────────────────────────┐
│  Conferma & Email       │
└─────────────────────────┘
```

### Stack Tecnologico

- **Frontend**: JavaScript (Vanilla), Bootstrap 5, FullCalendar 6
- **Backend**: PHP 7.4+, WordPress AJAX API
- **Database**: MySQL 8.0
- **Librerie**: jQuery 3.6+

---

## 📁 File Componenti

### 1. Template Modale
**File**: `public/partials/prenotazione-aule-ssm-multi-slot-modal.php`

**Responsabilità**:
- HTML struttura modale Bootstrap 5
- Form prenotazione con validazione HTML5
- CSS personalizzato con colori brand SSM

**Sezioni Principali**:
```html
<!-- Griglia slot disponibili -->
<div id="available-slots-grid"></div>

<!-- Tabella prenotazioni esistenti -->
<table id="existing-bookings-list"></table>

<!-- Riepilogo slot selezionati -->
<div id="selected-slots-summary"></div>

<!-- Form prenotazione -->
<form id="multiSlotBookingForm-{aula_id}"></form>
```

### 2. JavaScript Multi-Slot
**File**: `public/js/prenotazione-aule-ssm-multi-slot.js`

**Funzioni Principali**:

| Funzione | Descrizione |
|----------|-------------|
| `initMultiSlot()` | Inizializza event listeners |
| `openMultiSlotModal(dateStr, aulaId)` | Apre modale e carica dati |
| `loadSlotsForDate(date, aulaId)` | AJAX per recupero slot |
| `renderSlots()` | Renderizza griglia slot |
| `renderBookedSlots()` | Renderizza prenotazioni esistenti |
| `toggleSlotSelection(slot)` | Gestisce selezione/deselezione |
| `updateSelectedSlotsUI()` | Aggiorna UI slot selezionati |
| `submitMultiSlotBooking(form)` | Invia prenotazione multipla |

**Oggetto Globale**:
```javascript
// Variabili stato
let selectedSlots = [];      // Array slot selezionati
let currentDate = null;       // Data corrente
let currentAulaId = null;     // ID aula corrente
let availableSlots = [];      // Slot disponibili
let bookedSlots = [];         // Slot già prenotati
```

### 3. Backend Handler
**File**: `public/class-prenotazione-aule-ssm-multi-slot.php`

**Classe**: `Prenotazione_Aule_SSM_Multi_Slot`

**Metodi Pubblici**:

| Metodo | Endpoint AJAX | Descrizione |
|--------|---------------|-------------|
| `ajax_get_slots_for_date()` | `get_slots_for_date` | Recupera slot disponibili per data |
| `ajax_multi_booking()` | `prenotazione_aule_ssm_multi_booking` | Gestisce prenotazione multipla |

**Metodi Privati**:

| Metodo | Descrizione |
|--------|-------------|
| `init_hooks()` | Registra actions AJAX |
| `send_booking_confirmation_email()` | Invia email conferma |

---

## 🗄️ Database Schema

### Tabella: `jc_prenotazione_aule_ssm_prenotazioni`

**Colonna Aggiunta**:
```sql
ALTER TABLE jc_prenotazione_aule_ssm_prenotazioni
ADD COLUMN gruppo_prenotazione VARCHAR(100) DEFAULT NULL AFTER stato;
```

**Struttura Completa Rilevante**:

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `id` | INT | Primary key auto-increment |
| `aula_id` | INT | Foreign key aula |
| `nome_richiedente` | VARCHAR(255) | Nome |
| `cognome_richiedente` | VARCHAR(255) | Cognome |
| `email_richiedente` | VARCHAR(255) | Email |
| `motivo_prenotazione` | TEXT | Motivo |
| `data_prenotazione` | DATE | Data slot |
| `ora_inizio` | TIME | Ora inizio slot |
| `ora_fine` | TIME | Ora fine slot |
| `stato` | ENUM | in_attesa/approvata/rifiutata/cancellata |
| **`gruppo_prenotazione`** | VARCHAR(100) | **ID gruppo per slot multipli** |
| `created_at` | DATETIME | Timestamp creazione |

**Esempio Query - Recupera Prenotazioni Raggruppate**:
```sql
SELECT
    gruppo_prenotazione,
    COUNT(*) as num_slots,
    MIN(ora_inizio) as prima_ora,
    MAX(ora_fine) as ultima_ora,
    nome_richiedente,
    cognome_richiedente,
    motivo_prenotazione
FROM jc_prenotazione_aule_ssm_prenotazioni
WHERE gruppo_prenotazione IS NOT NULL
GROUP BY gruppo_prenotazione;
```

**Generazione ID Gruppo**:
```php
$gruppo_prenotazione = uniqid('multi_', true);
// Esempio output: "multi_670617a3b5e4f8.12345678"
```

---

## 🔌 API Endpoints

### 1. GET Slots for Date

**Action**: `get_slots_for_date`
**Metodo**: POST (WordPress AJAX)
**Autenticazione**: Nonce `prenotazione_aule_ssm_public_nonce`

**Parametri Request**:
```javascript
{
    action: 'get_slots_for_date',
    aula_id: 1,              // ID aula
    date: '2025-10-21',      // Data formato YYYY-MM-DD
    nonce: 'xxx'             // Nonce sicurezza
}
```

**Response Success**:
```json
{
    "success": true,
    "data": {
        "available_slots": [
            {
                "time": "09:00",
                "end_time": "09:30",
                "duration": 30
            },
            {
                "time": "09:30",
                "end_time": "10:00",
                "duration": 30
            }
        ],
        "booked_slots": [
            {
                "ora_inizio": "17:00:00",
                "ora_fine": "17:30:00",
                "motivo_prenotazione": "Corso di Relatività Speciale",
                "nome_richiedente": "Mario",
                "cognome_richiedente": "Rossi"
            }
        ],
        "date": "2025-10-21"
    }
}
```

**Logica Generazione Slot**:
1. Recupera configurazione slot per giorno settimana (1-7)
2. Verifica validità data (tra `data_inizio_validita` e `data_fine_validita`)
3. Genera slot ogni N minuti (definito in `durata_slot_minuti`)
4. Recupera prenotazioni esistenti per la data
5. Restituisce entrambi gli array

### 2. Submit Multi-Slot Booking

**Action**: `prenotazione_aule_ssm_multi_booking`
**Metodo**: POST (WordPress AJAX)
**Autenticazione**: Nonce `prenotazione_aule_ssm_multi_booking`

**Parametri Request**:
```javascript
{
    action: 'prenotazione_aule_ssm_multi_booking',
    aula_id: 1,
    selected_slots: '[
        {"date":"2025-10-21","time":"09:00","aula_id":1},
        {"date":"2025-10-21","time":"09:30","aula_id":1}
    ]',
    cognome_richiedente: 'Rossi',
    nome_richiedente: 'Mario',
    email_richiedente: 'mario@example.com',
    motivo_prenotazione: 'Lezione teorica cardiologia',
    telefono_richiedente: '3331234567',      // Opzionale
    numero_partecipanti: 5,                  // Opzionale
    nonce: 'xxx'
}
```

**Response Success**:
```json
{
    "success": true,
    "data": {
        "message": "Prenotazione confermata per 2 slot!",
        "booking_group": "multi_670617a3b5e4f8.12345678"
    }
}
```

**Response Error**:
```json
{
    "success": false,
    "data": {
        "message": "Lo slot 09:00 e gia prenotato"
    }
}
```

**Logica Backend**:
1. **Validazione Dati**:
   - Campi obbligatori presenti
   - Email formato valido
   - Almeno uno slot selezionato

2. **Transazione Database**:
   ```php
   $wpdb->query('START TRANSACTION');

   try {
       foreach ($selected_slots as $slot) {
           // Verifica disponibilità
           // Inserisci prenotazione
       }
       $wpdb->query('COMMIT');
   } catch (Exception $e) {
       $wpdb->query('ROLLBACK');
   }
   ```

3. **Email Conferma**:
   - Oggetto: "Conferma prenotazione aula - Prenotazione Aule SSM"
   - Contenuto: Lista slot prenotati con date/orari

---

## 💻 Frontend JavaScript

### Inizializzazione

```javascript
$(document).ready(function() {
    initMultiSlot();
});
```

### Event Listeners

```javascript
// Click su data nel calendario
$(document).on('click', '.fc-daygrid-day, .fc-timegrid-slot', function(e) {
    const dateStr = $(this).data('date');
    if (dateStr) {
        openMultiSlotModal(dateStr, currentAulaId);
    }
});

// Submit form prenotazione
$(document).on('submit', '[id^="multiSlotBookingForm-"]', function(e) {
    e.preventDefault();
    submitMultiSlotBooking($(this));
});

// Rimuovi slot selezionato
$(document).on('click', '.remove-slot', function() {
    const slotTime = $(this).data('slot-time');
    removeSelectedSlot(slotTime);
});
```

### Gestione Stato Slot

```javascript
// Struttura oggetto slot
const slot = {
    time: '09:00',           // Orario inizio
    date: '2025-10-21',      // Data
    aula_id: 1               // ID aula
};

// Aggiunta slot
selectedSlots.push(slot);

// Rimozione slot
selectedSlots = selectedSlots.filter(s => s.time !== slotTime);
```

### Rendering Dinamico

**Griglia Slot**:
```javascript
availableSlots.forEach(slot => {
    const isBooked = bookedSlots.some(b => b.ora_inizio === slot.time);
    const isSelected = selectedSlots.some(s => s.time === slot.time);

    const button = $('<button>', {
        type: 'button',
        class: 'slot-button' +
               (isBooked ? ' slot-booked' : '') +
               (isSelected ? ' slot-selected' : ''),
        text: slot.time,
        disabled: isBooked
    });

    grid.append(button);
});
```

---

## 🔧 Backend PHP

### Classe Multi-Slot Handler

**Namespace**: Globale (WordPress Plugin)
**File**: `class-prenotazione-aule-ssm-multi-slot.php`

### Hooks Registrati

```php
add_action('wp_ajax_get_slots_for_date', array($this, 'ajax_get_slots_for_date'));
add_action('wp_ajax_nopriv_get_slots_for_date', array($this, 'ajax_get_slots_for_date'));

add_action('wp_ajax_prenotazione_aule_ssm_multi_booking', array($this, 'ajax_multi_booking'));
add_action('wp_ajax_nopriv_prenotazione_aule_ssm_multi_booking', array($this, 'ajax_multi_booking'));
```

### Sicurezza

**Nonce Verification**:
```php
check_ajax_referer('prenotazione_aule_ssm_public_nonce', 'nonce');
```

**Sanitizzazione Input**:
```php
$aula_id = intval($_POST['aula_id']);
$cognome = sanitize_text_field($_POST['cognome_richiedente']);
$email = sanitize_email($_POST['email_richiedente']);
$motivo = sanitize_textarea_field($_POST['motivo_prenotazione']);
```

**Validazione Email**:
```php
if (!is_email($email)) {
    wp_send_json_error(array('message' => 'Email non valida'));
}
```

### Query Database Ottimizzate

**Recupero Slot Configurati**:
```php
$slots_query = $wpdb->prepare(
    "SELECT ora_inizio, ora_fine, durata_slot_minuti
    FROM $table_slot
    WHERE aula_id = %d
    AND giorno_settimana = %d
    AND attivo = 1
    AND (data_inizio_validita <= %s AND (data_fine_validita IS NULL OR data_fine_validita >= %s))
    ORDER BY ora_inizio",
    $aula_id,
    $giorno_settimana,
    $date,
    $date
);
```

**Verifica Conflitti**:
```php
$existing = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM $table_prenotazioni
    WHERE aula_id = %d
    AND data_prenotazione = %s
    AND ora_inizio = %s
    AND stato IN ('approvata', 'in_attesa')",
    $aula_id,
    $slot['date'],
    $slot['time']
));

if ($existing > 0) {
    throw new Exception('Lo slot ' . $slot['time'] . ' e gia prenotato');
}
```

### Email Notification

```php
private function send_booking_confirmation_email($email, $nome, $cognome, $slots, $motivo) {
    $to = $email;
    $subject = 'Conferma prenotazione aula - Prenotazione Aule SSM';

    $slots_list = '';
    foreach ($slots as $slot) {
        $slots_list .= sprintf("\n- %s alle %s", $slot['date'], $slot['time']);
    }

    $message = sprintf(
        "Gentile %s %s,\n\nLa sua prenotazione e stata registrata con successo.\n\nDettagli:\n%s\n\nMotivo: %s\n\nGrazie,\nPrenotazione Aule SSM",
        $nome,
        $cognome,
        $slots_list,
        $motivo
    );

    wp_mail($to, $subject, $message);
}
```

---

## 🎨 Personalizzazione

### Colori Brand SSM

**File**: `prenotazione-aule-ssm-multi-slot-modal.php`

```css
/* Colore primario SSM */
.modal-header.bg-primary {
    background-color: #b64c3c !important;
}

.slot-button:hover:not(.slot-booked):not(.slot-disabled) {
    border-color: #b64c3c;
    background: #fce8e6;
}

.slot-button.slot-selected {
    background: #b64c3c;
    color: white;
    border-color: #b64c3c;
}

.slot-badge {
    background: #b64c3c;
}

.btn-primary {
    background-color: #b64c3c;
    border-color: #b64c3c;
}

.btn-primary:hover {
    background-color: #9a3f31;
    border-color: #9a3f31;
}
```

### Modifica Durata Slot Predefinita

**File**: `class-prenotazione-aule-ssm-multi-slot.php` (linea 147-148)

```php
// Cambia da 30 minuti a 60 minuti
$ora_fine_timestamp = strtotime($slot['time']) + (60 * 60); // Era 30
$ora_fine = date('H:i:s', $ora_fine_timestamp);
```

### Personalizza Email Template

**File**: `class-prenotazione-aule-ssm-multi-slot.php` (metodo `send_booking_confirmation_email`)

```php
// Cambia oggetto
$subject = 'La tua prenotazione SSM è confermata!';

// Aggiungi logo/header HTML
$headers = array('Content-Type: text/html; charset=UTF-8');
$message = '<html><body>...HTML personalizzato...</body></html>';

wp_mail($to, $subject, $message, $headers);
```

### Limita Numero Massimo Slot Selezionabili

**File**: `prenotazione-aule-ssm-multi-slot.js` (funzione `toggleSlotSelection`)

```javascript
function toggleSlotSelection(slot) {
    const index = selectedSlots.findIndex(s => s.time === slot.time);

    if (index > -1) {
        selectedSlots.splice(index, 1);
    } else {
        // AGGIUNTA: Limite massimo 5 slot
        if (selectedSlots.length >= 5) {
            showError('Puoi selezionare massimo 5 slot');
            return;
        }
        selectedSlots.push({
            time: slot.time,
            date: currentDate,
            aula_id: currentAulaId
        });
    }

    updateSelectedSlotsUI();
    renderSlots();
}
```

---

## 🧪 Testing

### Test Frontend

**1. Apertura Modale**:
```javascript
// Test: Click su data apre modale
1. Naviga su pagina con calendario
2. Click su una data con slot disponibili
3. Verifica apertura modale "21 Ottobre, 2025"
4. Verifica presenza griglia slot
```

**2. Selezione Slot**:
```javascript
// Test: Selezione multipla
1. Click su slot 09:00 → diventa blu (#b64c3c)
2. Click su slot 09:30 → diventa blu
3. Click su slot 10:00 → diventa blu
4. Verifica badge riepilogo: "3 slot selezionati"
5. Click di nuovo su 09:30 → deselezione
6. Verifica badge riepilogo: "2 slot selezionati"
```

**3. Visualizzazione Prenotazioni Esistenti**:
```javascript
// Test: Mostra prenotazioni
1. Prenotare manualmente uno slot (es. 17:00)
2. Riaprire modale stessa data
3. Verifica tabella "Prenotazioni per la data 21-10-2025"
4. Verifica riga: "17:00 | Corso di Relatività Speciale"
5. Verifica slot 17:00 disabilitato nella griglia
```

**4. Submit Form**:
```javascript
// Test: Prenotazione multipla
1. Seleziona 3 slot (09:00, 09:30, 10:00)
2. Compila form:
   - Cognome: "Rossi"
   - Nome: "Mario"
   - Email: "test@example.com"
   - Motivo: "Test prenotazione multipla"
3. Click "Conferma Prenotazione"
4. Verifica messaggio successo
5. Verifica email ricevuta
6. Verifica chiusura modale
```

### Test Backend

**1. Endpoint GET Slots**:
```bash
# cURL test
curl -X POST http://yoursite.com/wp-admin/admin-ajax.php \
  -d "action=get_slots_for_date" \
  -d "aula_id=1" \
  -d "date=2025-10-21" \
  -d "nonce=xxx"

# Expected: JSON con available_slots e booked_slots
```

**2. Endpoint Multi Booking**:
```bash
# Test prenotazione 2 slot
curl -X POST http://yoursite.com/wp-admin/admin-ajax.php \
  -d "action=prenotazione_aule_ssm_multi_booking" \
  -d "aula_id=1" \
  -d "selected_slots=[{\"date\":\"2025-10-21\",\"time\":\"09:00\"},{\"date\":\"2025-10-21\",\"time\":\"09:30\"}]" \
  -d "cognome_richiedente=Test" \
  -d "nome_richiedente=User" \
  -d "email_richiedente=test@test.com" \
  -d "motivo_prenotazione=Testing" \
  -d "nonce=xxx"

# Expected: success true + booking_group ID
```

**3. Database Verification**:
```sql
-- Verifica prenotazioni create
SELECT * FROM jc_prenotazione_aule_ssm_prenotazioni
WHERE gruppo_prenotazione LIKE 'multi_%'
ORDER BY created_at DESC
LIMIT 10;

-- Verifica raggruppamento corretto
SELECT
    gruppo_prenotazione,
    COUNT(*) as num_slots,
    nome_richiedente,
    cognome_richiedente
FROM jc_prenotazione_aule_ssm_prenotazioni
WHERE gruppo_prenotazione IS NOT NULL
GROUP BY gruppo_prenotazione;
```

### Test Casi Edge

**1. Conflitto Slot**:
```javascript
// Scenario: Due utenti prenotano stesso slot simultaneamente
User A: Seleziona 09:00, compila form
User B: Seleziona 09:00, compila form
User A: Submit → Success
User B: Submit → Error "Lo slot 09:00 e gia prenotato"
```

**2. Rollback Transazione**:
```javascript
// Scenario: 3 slot selezionati, il secondo è già occupato
Slot 09:00: Disponibile
Slot 09:30: Già prenotato (non visibile all'utente per race condition)
Slot 10:00: Disponibile

Submit → Error + Rollback
Risultato: Nessuno dei 3 slot viene prenotato (atomicità garantita)
```

**3. Validazione Campi**:
```javascript
// Test email invalida
Email: "notanemail"
Submit → Error "Email non valida"

// Test campi mancanti
Nome: ""
Submit → Error "Compila tutti i campi obbligatori"
```

---

## 🔧 Troubleshooting

### Problema: Modale non si apre

**Sintomi**: Click su data non fa nulla

**Cause Possibili**:
1. JavaScript non caricato
2. Conflitto librerie
3. Event listener non registrato

**Soluzione**:
```javascript
// Console browser (F12)
console.log('prenotazione_aule_ssm_public:', typeof prenotazione_aule_ssm_public);
// Expected: "object"

// Verifica script caricato
document.querySelector('script[src*="prenotazione-aule-ssm-multi-slot"]');
// Expected: <script> element

// Test manuale apertura
openMultiSlotModal('2025-10-21', 1);
```

### Problema: Slot non vengono caricati

**Sintomi**: Modale si apre ma griglia vuota

**Cause Possibili**:
1. Nessuno slot configurato per quel giorno
2. Errore AJAX
3. Date validation failed

**Soluzione**:
```javascript
// Console browser - Verifica chiamata AJAX
// Network tab: Cerca chiamata "admin-ajax.php?action=get_slots_for_date"

// Response dovrebbe contenere:
{
    "success": true,
    "data": {
        "available_slots": [...],
        "booked_slots": [...]
    }
}

// Se array vuoti, verifica slot configurati in admin
```

**Verifica Database**:
```sql
SELECT * FROM jc_prenotazione_aule_ssm_slot_disponibilita
WHERE aula_id = 1
AND giorno_settimana = 2  -- 2 = Martedì
AND attivo = 1;

-- Se nessun risultato: Configura slot dall'admin
```

### Problema: Submit fallisce

**Sintomi**: Click "Conferma" non fa nulla o mostra errore

**Cause Possibili**:
1. Nonce non valido
2. Validazione fallita
3. Errore backend

**Soluzione**:
```javascript
// Console browser
// Verifica dati form
$('#multiSlotBookingForm-1').serialize();

// Verifica slot selezionati
console.log('selectedSlots:', selectedSlots);

// Network tab: Verifica response
// Cerca errori tipo:
{
    "success": false,
    "data": {
        "message": "Compila tutti i campi obbligatori"
    }
}
```

**Backend Debug**:
```php
// In class-prenotazione-aule-ssm-multi-slot.php
// Aggiungi prima di check_ajax_referer:
error_log('POST data: ' . print_r($_POST, true));

// Verifica /wp-content/debug.log
```

### Problema: Transazione fallisce

**Sintomi**: Errore "Errore nel salvataggio dello slot"

**Cause Possibili**:
1. Permessi database
2. Tabella non esiste
3. Colonna `gruppo_prenotazione` mancante

**Soluzione**:
```sql
-- Verifica tabella esiste
SHOW TABLES LIKE 'jc_prenotazione_aule_ssm_prenotazioni';

-- Verifica colonna gruppo_prenotazione
DESCRIBE jc_prenotazione_aule_ssm_prenotazioni;

-- Se mancante, aggiungi:
ALTER TABLE jc_prenotazione_aule_ssm_prenotazioni
ADD COLUMN gruppo_prenotazione VARCHAR(100) DEFAULT NULL AFTER stato;

-- Verifica permessi
SHOW GRANTS FOR CURRENT_USER;
```

### Problema: Email non arriva

**Sintomi**: Prenotazione OK ma nessuna email

**Cause Possibili**:
1. SMTP non configurato
2. Email finisce in spam
3. Funzione `wp_mail()` fallisce

**Soluzione**:
```php
// Test wp_mail direttamente
add_action('init', function() {
    $result = wp_mail('test@example.com', 'Test', 'Messaggio test');
    error_log('wp_mail result: ' . ($result ? 'SUCCESS' : 'FAILED'));
});

// Installa plugin SMTP (es. WP Mail SMTP)
// Configura con Gmail/SendGrid/Mailgun
```

### Problema: Performance lenta

**Sintomi**: Modale impiega secondi per aprirsi

**Cause Possibili**:
1. Query database non ottimizzate
2. Troppi slot da generare
3. Rete lenta

**Soluzione**:
```php
// Aggiungi indici database
CREATE INDEX idx_aula_giorno ON jc_prenotazione_aule_ssm_slot_disponibilita (aula_id, giorno_settimana);
CREATE INDEX idx_prenotazioni_date ON jc_prenotazione_aule_ssm_prenotazioni (aula_id, data_prenotazione);

// Limita generazione slot
// In ajax_get_slots_for_date(), limita while loop:
$max_slots = 50;
$count = 0;
while ($current_time < $end_time && $count < $max_slots) {
    // ...
    $count++;
}
```

---

## 📊 Metriche e Monitoring

### Query Utili Admin

**Statistiche Prenotazioni Multiple**:
```sql
SELECT
    DATE(created_at) as data,
    COUNT(DISTINCT gruppo_prenotazione) as num_prenotazioni_multiple,
    COUNT(*) as totale_slot_prenotati,
    ROUND(AVG(slots_per_gruppo), 1) as media_slot_per_prenotazione
FROM (
    SELECT
        gruppo_prenotazione,
        created_at,
        COUNT(*) as slots_per_gruppo
    FROM jc_prenotazione_aule_ssm_prenotazioni
    WHERE gruppo_prenotazione IS NOT NULL
    GROUP BY gruppo_prenotazione
) as grouped
GROUP BY DATE(created_at)
ORDER BY data DESC;
```

**Top Utenti Multi-Slot**:
```sql
SELECT
    CONCAT(cognome_richiedente, ' ', nome_richiedente) as utente,
    COUNT(DISTINCT gruppo_prenotazione) as num_prenotazioni,
    COUNT(*) as totale_slot
FROM jc_prenotazione_aule_ssm_prenotazioni
WHERE gruppo_prenotazione IS NOT NULL
GROUP BY cognome_richiedente, nome_richiedente
ORDER BY num_prenotazioni DESC
LIMIT 10;
```

**Aule Più Utilizzate (Multi-Slot)**:
```sql
SELECT
    a.nome_aula,
    COUNT(DISTINCT p.gruppo_prenotazione) as prenotazioni_multiple,
    COUNT(*) as slot_totali
FROM jc_prenotazione_aule_ssm_prenotazioni p
JOIN jc_prenotazione_aule_ssm_aule a ON p.aula_id = a.id
WHERE p.gruppo_prenotazione IS NOT NULL
GROUP BY a.nome_aula
ORDER BY prenotazioni_multiple DESC;
```

---

## 🚀 Roadmap Futuri Sviluppi

### Priorità Alta

- [ ] **Dashboard Admin**: Vista dedicata prenotazioni raggruppate
- [ ] **Modifica Prenotazione**: Permetti aggiunta/rimozione slot da gruppo esistente
- [ ] **Notifiche Real-time**: WebSocket per aggiornare disponibilità live
- [ ] **Export Report**: CSV/PDF prenotazioni multiple

### Priorità Media

- [ ] **Ricerca Avanzata**: Filtra per gruppo_prenotazione
- [ ] **Statistiche Dashboard**: Grafici utilizzo multi-slot
- [ ] **Email HTML**: Template personalizzato con logo
- [ ] **Conferma SMS**: Integrazione Twilio/Vonage

### Priorità Bassa

- [ ] **App Mobile**: Versione nativa iOS/Android
- [ ] **Integrazione Calendar**: Google Calendar/Outlook sync
- [ ] **QR Code Check-in**: Genera QR per ogni gruppo prenotazione
- [ ] **Rating System**: Feedback post-utilizzo

---

## 📞 Supporto

### Contatti

- **Developer**: Team SSM Development
- **Email**: dev@ssm.example.com
- **Repository**: (Inserire link repository)

### Risorse Utili

- [WordPress AJAX API](https://developer.wordpress.org/plugins/javascript/ajax/)
- [wpdb Class Reference](https://developer.wordpress.org/reference/classes/wpdb/)
- [Bootstrap 5 Modal](https://getbootstrap.com/docs/5.0/components/modal/)
- [FullCalendar Docs](https://fullcalendar.io/docs)

---

## 📄 Licenza

GPL v2 or later

---

## 📝 Changelog

### v1.0.0 - 2025-10-09

**Aggiunto**:
- ✅ Sistema multi-slot booking completo
- ✅ Modale selezione multipla slot
- ✅ Visualizzazione prenotazioni esistenti
- ✅ Form unificato una sola compilazione
- ✅ Backend AJAX handlers
- ✅ Transazioni database atomiche
- ✅ Colonna `gruppo_prenotazione` database
- ✅ Email conferma automatica
- ✅ Branding SSM colori personalizzati
- ✅ Documentazione completa

**Modificato**:
- Database schema: aggiunta colonna `gruppo_prenotazione`
- Frontend: integrazione modale nel calendario esistente
- Backend: hooks AJAX per gestione multi-slot

**Rimosso**:
- N/A (prima versione)

---

**Fine Documentazione**
*Ultimo aggiornamento: 9 Ottobre 2025*

---

## ⚠️ IMPORTANTE: Content Security Policy Fix

### Errore CSP Web Workers

**Sintomo**:
```
Refused to create a worker from blob:https://... because it violates 
Content Security Policy directive: "script-src self unsafe-eval unsafe-inline https:"
```

**Causa**: Il plugin FullCalendar v6 usa Web Workers (blob:) per migliorare le performance. La policy CSP del sito li bloccava.

**Soluzione Applicata** (9 Ottobre 2025):

File modificato: `/wp-content/mu-plugins/csp-headers.php`

```php
<?php
/**
 * CSP Headers Configuration
 * Updated for Web Workers support (FullCalendar, etc.)
 */

if (!headers_sent()) {
    // IMPORTANTE: Aggiunte direttive blob: e worker-src
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline' https: blob:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' https:; worker-src blob:;");
}
```

**Modifiche Applicate**:
- ✅ Aggiunto `blob:` a `script-src`
- ✅ Aggiunto `worker-src blob:;` come direttiva dedicata

**Dopo il Fix**:
1. Ricarica la pagina con **CTRL+F5** (hard refresh)
2. Pulisci cache browser
3. Verifica console: nessun errore CSP
4. FullCalendar ora funziona perfettamente

**Note**:
- Il fix è **retrocompatibile** con Revolution Slider
- **Sicurezza mantenuta**: blob workers sono confinati al dominio
- **Performance**: FullCalendar ora usa multi-threading

---

**Aggiornamento Documentazione**: 9 Ottobre 2025, ore 08:10 UTC
