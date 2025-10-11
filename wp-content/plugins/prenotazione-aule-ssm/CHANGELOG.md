# CHANGELOG - Prenotazione Aule SSM

## v2.1.2 (10 Ottobre 2025) - Shortcode Fix

### 🐛 Bug Fixes

#### Shortcode Errato nelle Schede Aula
**Problema**: Lo shortcode mostrato nelle schede aula era quello vecchio (`prenotazione_aule_ssm_calendar`)  
**Causa**: Template admin-aule.php usava shortcode legacy invece del nuovo calendario multi-slot  
**Soluzione**: Aggiornato shortcode a `prenotazione_aule_ssm_new_calendar` con ID aula dinamico

**File modificato**: `/admin/partials/prenotazione-aule-ssm-admin-aule.php` (righe 264, 267, 273)

**Prima:**
```
[prenotazione_aule_ssm_calendar aula_id="2"]
```

**Dopo:**
```
[prenotazione_aule_ssm_new_calendar aula_id="2"]
```

**Impact**: ✅ Gli utenti ora copiano lo shortcode corretto con sistema multi-slot avanzato

---

## v2.1.1 (10 Ottobre 2025) - Critical Fix Release

### 🔴 Critical Bug Fixes

#### Errore Critico Durante Disinstallazione Plugin
**Problema**: WordPress genera errore critico quando si tenta di disinstallare il plugin
**Causa**: File `class-prenotazione-aule-ssm-uninstaller.php` mancante ma referenziato in `prenotazione-aule-ssm.php:78`
**Soluzione**: Creato file uninstaller completo con gestione pulizia:
- Eliminazione tabelle database
- Rimozione opzioni WordPress
- Pulizia capabilities personalizzati
- Cancellazione transients
- Rimozione eventi WP-Cron

**File creato**: `/includes/class-prenotazione-aule-ssm-uninstaller.php` (6.5KB)

**Funzionalità Uninstaller:**
```php
- drop_database_tables()          // Rimuove 4 tabelle plugin
- delete_plugin_options()         // Cancella opzioni e site_options
- remove_custom_capabilities()    // Pulisce capabilities da admin/editor
- delete_plugin_transients()      // Elimina transients con prefisso plugin
- clear_scheduled_events()        // Rimuove eventi cron schedulati
```

**Impact**: ✅ Plugin ora disinstallabile senza errori critici

---

## v2.1.0 (9 Ottobre 2025) - Major Update

### ✨ Nuove Funzionalità

#### Documentazione Email Placeholder Completa
- Guida completa placeholder email (EMAIL_PLACEHOLDERS.md - 11KB)
- Riferimento rapido stampabile (QUICK_REFERENCE_EMAIL_PLACEHOLDERS.md - 2.8KB)
- 4 template email pronti all'uso (Conferma, Rifiuto, Admin, Reminder)
- Documentazione formattazione HTML/CSS

#### Sistema Reports Migliorato
- Integrazione Chart.js 4.4.0 per grafici interattivi
- Nuovi filtri temporali: "Prossimi 30 giorni" e "Tutte le prenotazioni"
- Grafici trend giornalieri con tooltips
- Export CSV con nuovi filtri

#### Modali Bootstrap Professionali
- Dashboard con modali Bootstrap 5 (sostituzione popup sistema)
- Modali approvazione/rifiuto con note admin
- Spinner animati durante AJAX
- Design coerente su tutte le pagine admin

### 🐛 Bug Fixes (v2.1.0)

#### Reports Mostra Dati Zero
**Problema**: Pagina reports mostrava tutti zero nonostante 17 prenotazioni in DB
**Causa**: Filtro default "Ultimi 30 giorni" cercava nel passato, prenotazioni erano future
**Soluzione**: Cambiato filtro default a "Tutte le prenotazioni", aggiunti filtri future/all

#### Popup + Modal Duplicati
**Problema**: Click su azioni mostrava sia popup browser che modal Bootstrap
**Causa**: Handler JavaScript globali eseguiti prima di handler inline
**Soluzione**: Disabilitati handler globali, usati solo handler inline per pagina

#### Contatori Prenotazioni Non Aggiornati
**Problema**: Contatori dashboard non riflettevano stato reale prenotazioni
**Causa**: Mismatch stato singolare DB ('confermata') vs chiave plurale counter ('confermate')
**Soluzione**: Aggiunta mappatura stato prima di incrementare contatori

---

## v1.1.2 (Ottobre 2025) - Bug Fix Release

### 🐛 Bug Fixes

#### 1. Checkbox Privacy Invisibile
**Problema**: Il checkbox della privacy policy nel form multi-slot non era visibile
**Causa**: Stili Bootstrap 5 sovrapponevano gli stili di default del checkbox
**Soluzione**: Aggiunto CSS esplicito con `!important` flags

**File modificato**: `/public/css/prenotazione-aule-ssm-multi-slot.css`
```css
.multi-slot-booking-form .form-check-input {
    width: 18px !important;
    height: 18px !important;
    border: 2px solid #d1d5db !important;
    border-radius: 4px !important;
}

.multi-slot-booking-form .form-check-input:checked {
    background-color: #0066ff !important;
    border-color: #0066ff !important;
}
```

#### 2. Errore Salvataggio Slot - Mismatch Formato Tempo
**Problema**: Errore "Errore nel salvataggio dello slot 08:00" durante prenotazione multipla
**Causa**: Database memorizza orari in formato `HH:MM:SS` (es. `08:00:00`) ma JavaScript invia `HH:MM` (es. `08:00`). La query di verifica slot occupati falliva:
```php
WHERE ora_inizio = '08:00'  // Non trova '08:00:00' in DB
```

**Soluzione**: Aggiunto suffisso `:00` al parametro time nella query

**File modificato**: `/public/class-prenotazione-aule-ssm-multi-slot.php` (linea 140)
```php
// PRIMA:
$slot['time']

// DOPO:
$slot['time'] . ':00'  // '08:00' diventa '08:00:00'
```

### 📝 Dettagli Tecnici

**Query Affected:**
```php
$existing = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM $table_prenotazioni
    WHERE aula_id = %d AND data_prenotazione = %s
    AND ora_inizio = %s AND stato IN ('approvata', 'in_attesa')",
    $aula_id,
    $slot['date'],
    $slot['time'] . ':00'  // Fix applicato qui
));
```

**Impatto:**
- ✅ Prenotazioni multiple ora funzionano correttamente
- ✅ Verifica slot occupati funziona con formato DB corretto
- ✅ Transazioni DB completate senza errori

### 🚀 Cache Busting
**Plugin Version**: `1.0.4` → `1.1.2`
**Motivo**: Forzare browser a ricaricare CSS/JS con le fix

**File modificato**: `/prenotazione-aule-ssm.php`
```php
define('PRENOTAZIONE_AULE_SSM_VERSION', '1.1.2');
```

### ✅ Testing
- [x] Checkbox privacy visibile e cliccabile
- [x] Prenotazione multipla slot funzionante
- [x] Transazioni DB completate con successo
- [x] Messaggio conferma visualizzato
- [x] Slot salvati correttamente in database

### 📦 Files Changed
1. `/prenotazione-aule-ssm.php` - Version bump
2. `/public/css/prenotazione-aule-ssm-multi-slot.css` - Checkbox fix
3. `/public/class-prenotazione-aule-ssm-multi-slot.php` - Time format fix
4. `/README.md` - Documentazione aggiornata

---

## v1.1.0 (Ottobre 2025) - Feature Release: Sistema Multi-Slot

### ✨ Nuove Funzionalità

#### Sistema Calendario Multi-Slot
Implementazione completa di interfaccia moderna per prenotazione multipla di slot:

**User Flow:**
1. Click su giorno → Modal con badge slot disponibili
2. Click badge per selezione multipla
3. Conferma → Slot appaiono in sidebar destra
4. Form unificato per prenotare tutti gli slot insieme

**Componenti Creati:**

##### Template
- `prenotazione-aule-ssm-new-calendar.php`
  - Calendario con griglia giorni
  - Sidebar con recap slot selezionati
  - Modal Bootstrap 5 per selezione
  - Form prenotazione multipla integrato

##### JavaScript
- `prenotazione-aule-ssm-new-calendar.js`
  - Rendering calendario disponibilità
  - Gestione selezione badge multipli
  - Aggiornamento sidebar real-time
  - Submit AJAX con array slot
  - Gestione transazioni e errori

##### CSS
- `aule-booking-new-calendar.css` (base calendario)
- `prenotazione-aule-ssm-multi-slot.css` (multi-slot styling)
  - Badge interattivi con stati (default/hover/selected/disabled)
  - Sidebar responsive
  - Form styling professionale
  - Grid layout per slot badges

##### Backend
- `class-prenotazione-aule-ssm-multi-slot.php`
  - AJAX handler `prenotazione_aule_ssm_multi_booking`
  - Validazione input (nonce, sanitize, email)
  - Transazioni DB atomiche
  - Sistema `gruppo_prenotazione` automatico

### 🎨 UI/UX Improvements

**Emoji → Dashicons**
Sostituzione completa emoji con icone native WordPress:
- 📅 → `dashicons-calendar-alt`
- ✅ → `dashicons-yes`
- ❌ → `dashicons-dismiss`
- 🔍 → `dashicons-search`

**Bootstrap 5 Integration**
- Modal system professionale
- Form components responsive
- Grid system per layout

### 🗄️ Database Changes

**Nuovi Campi Tabella `jc_prenotazione_aule_ssm_prenotazioni`:**

```sql
ALTER TABLE jc_prenotazione_aule_ssm_prenotazioni
ADD COLUMN gruppo_prenotazione VARCHAR(50) NULL COMMENT 'Link slot prenotati insieme',
ADD COLUMN motivo_prenotazione TEXT NULL COMMENT 'Motivazione della prenotazione';
```

**Motivazione:**
- `gruppo_prenotazione`: UUID condiviso per slot prenotati insieme (stesso form)
- `motivo_prenotazione`: Descrizione scopo prenotazione (visibile accanto a slot occupati)

### 📚 Documentazione

**File Creati:**
- `SHORTCODES.md` - Documentazione completa shortcodes disponibili
  - `[prenotazione_aule_ssm_new_calendar]` (nuovo multi-slot)
  - `[prenotazione_aule_ssm_calendar]` (legacy)
  - `[prenotazione_aule_ssm_list]`
  - `[prenotazione_aule_ssm_search]`

### 🔒 Security

**AJAX Nonce Verification:**
```php
check_ajax_referer('prenotazione_aule_ssm_multi_booking', 'nonce');
```

**Input Sanitization:**
- `sanitize_text_field()` per nome/cognome/motivo
- `sanitize_email()` per email
- `intval()` per aula_id
- Prepared statements per query DB

**Transaction Safety:**
```php
$wpdb->query('START TRANSACTION');
// Multiple INSERTs
if (error) {
    $wpdb->query('ROLLBACK');
}
$wpdb->query('COMMIT');
```

### 🎯 Design Patterns

**Separation of Concerns:**
- Template (PHP) → Struttura HTML
- Logic (JS) → Comportamento utente
- Styling (CSS) → Presentazione
- Backend (PHP Class) → Business logic

**Progressive Enhancement:**
- Form appare solo dopo selezione slot
- Sidebar nascosta inizialmente
- Conferma visibile solo con slot selezionati

**Defensive Programming:**
- Verifica slot già occupati prima di INSERT
- Rollback transazione su qualsiasi errore
- Validazione email lato server
- Check privacy policy obbligatoria

### 🔧 Technical Details

**AJAX Endpoint:**
```javascript
action: 'prenotazione_aule_ssm_multi_booking'
data: {
    aula_id: int,
    selected_slots: JSON.stringify([{time, date, aula_id}]),
    nome_richiedente: string,
    cognome_richiedente: string,
    email_richiedente: string,
    motivo_prenotazione: string,
    privacy_accepted: 1|0,
    nonce: string
}
```

**Response Format:**
```json
{
    "success": true,
    "message": "Prenotazione confermata per 3 slot",
    "gruppo_id": "uuid-here"
}
```

### 📦 Dependencies

**JavaScript:**
- jQuery 3.6+ (WordPress core)
- Bootstrap 5.3.0 (CDN)

**PHP:**
- WordPress 6.0+
- PHP 7.4+
- MySQL 5.7+

### ⚙️ Configuration

**Enqueue Assets:**
```php
wp_enqueue_style('bootstrap-5-css');
wp_enqueue_script('bootstrap-5-js');
wp_enqueue_style($plugin_name . '-multi-slot');
wp_enqueue_script($plugin_name . '-new-calendar');

wp_localize_script($plugin_name . '-new-calendar', 'prenotazioneAuleSSMData', [
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('prenotazione_aule_ssm_public_nonce'),
    'multiBookingNonce' => wp_create_nonce('prenotazione_aule_ssm_multi_booking')
]);
```

---

## Roadmap Future

### v1.2.0 (Planned)
- [ ] Email notifiche per prenotazioni multiple
- [ ] Export calendario in formato iCal
- [ ] Filtri avanzati slot disponibili
- [ ] Statistiche utilizzo aule

### v2.0.0 (Planned)
- [ ] Recurring bookings (prenotazioni ricorrenti)
- [ ] Multi-aula booking (prenota stessa fascia su più aule)
- [ ] Admin dashboard con analytics
- [ ] Integration con Google Calendar

---

**Maintainer**: Team SSM Development
**Last Update**: Ottobre 2025
