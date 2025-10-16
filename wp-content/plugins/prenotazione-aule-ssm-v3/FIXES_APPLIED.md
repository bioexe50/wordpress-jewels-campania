# Fix Applicati - Plugin Prenotazione Aule SSM

**Data**: 9 Ottobre 2025
**Versione Plugin**: 1.0.0

---

## 🔧 Fix #1: Content Security Policy (CSP) - Web Workers Bloccati

### Problema
```
Refused to create a worker from 'blob:https://...' because it violates
Content Security Policy directive: "script-src 'self' 'unsafe-eval' 'unsafe-inline' https:"
```

### Causa
FullCalendar v6 usa Web Workers (blob:) per migliorare performance, ma la CSP li bloccava.

### Soluzione Applicata

**File Modificato**: `/var/www/html/.htaccess` (linea 3)

**Prima**:
```apache
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline' https:; ..."
```

**Dopo**:
```apache
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline' https: blob:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' https:; worker-src blob:;"
```

**Modifiche**:
- ✅ Aggiunto `blob:` a `script-src`
- ✅ Aggiunto `worker-src blob:;`

**File Disabilitato**: `/wp-content/mu-plugins/csp-headers.php` → `.php.disabled`
(Non più necessario, CSP gestito da .htaccess)

**Status**: ✅ RISOLTO

---

## 🔧 Fix #2: AJAX 500 Error - array_map() su NULL

### Problema
```
PHP Fatal error: Uncaught TypeError: array_map(): Argument #2 ($array) must be of type array,
null given in /var/www/html/wp-content/plugins/prenotazione-aule-ssm/admin/class-prenotazione-aule-ssm-admin.php:601
```

### Causa
Il metodo `ajax_generate_slots()` non validava se `$_POST['giorni_settimana']` esisteva prima di usarlo con `array_map()`.

### Soluzione Applicata

**File Modificato**: `admin/class-prenotazione-aule-ssm-admin.php` (linea 601)

**Prima**:
```php
$giorni_settimana = array_map('absint', $_POST['giorni_settimana']);
```

**Dopo**:
```php
$giorni_settimana = isset($_POST['giorni_settimana']) && is_array($_POST['giorni_settimana'])
    ? array_map('absint', $_POST['giorni_settimana'])
    : array();
```

**Protezione Aggiunta**:
- ✅ Controllo `isset()` prima di usare `$_POST`
- ✅ Validazione `is_array()` per sicurezza
- ✅ Fallback a array vuoto se mancante

**Status**: ✅ RISOLTO

---

## 🔧 Fix #3: File Assets Admin Non Rinominati (404 Errors)

### Problema
```
404 Not Found:
- /wp-content/plugins/prenotazione-aule-ssm/admin/js/prenotazione-aule-ssm-admin.js
- /wp-content/plugins/prenotazione-aule-ssm/admin/css/prenotazione-aule-ssm-admin.css
```

### Causa
Durante la clonazione del plugin da "WP Aule Booking", i file JS/CSS admin non furono rinominati.

### Soluzione Applicata

**File Rinominati**:

| Prima | Dopo |
|-------|------|
| `admin/js/aule-booking-admin.js` | `admin/js/prenotazione-aule-ssm-admin.js` |
| `admin/css/aule-booking-admin.css` | `admin/css/prenotazione-aule-ssm-admin.css` |

**Comando Eseguito**:
```bash
mv aule-booking-admin.js prenotazione-aule-ssm-admin.js
mv aule-booking-admin.css prenotazione-aule-ssm-admin.css
```

**Status**: ✅ RISOLTO

---

## 📊 Riepilogo Fix

| Fix | File Modificati | Status | Criticità |
|-----|----------------|--------|-----------|
| CSP Web Workers | `.htaccess`, `mu-plugins/csp-headers.php` | ✅ Risolto | Alta |
| AJAX 500 Error | `admin/class-prenotazione-aule-ssm-admin.php` | ✅ Risolto | Critica |
| Assets 404 | `admin/js/*.js`, `admin/css/*.css` | ✅ Risolto | Media |

---

## ✅ Verifiche Post-Fix

### Test Eseguiti

1. **CSP Headers**
   ```bash
   curl -I https://raffaelevitulano.com/wp-admin/ | grep content-security-policy
   # Risultato: 1 header con blob: e worker-src ✅
   ```

2. **PHP Syntax**
   ```bash
   php -l admin/class-prenotazione-aule-ssm-admin.php
   # Risultato: No syntax errors ✅
   ```

3. **File Assets**
   ```bash
   ls admin/js/prenotazione-aule-ssm-admin.js
   ls admin/css/prenotazione-aule-ssm-admin.css
   # Risultato: Entrambi esistono ✅
   ```

4. **WordPress Cache**
   ```bash
   wp cache flush
   # Risultato: Success ✅
   ```

---

## 🧪 Come Testare

### Test 1: Creazione Slot Admin

1. Vai in **WordPress Admin → Gestione Aule → Slot Disponibilità**
2. Seleziona un'aula dal dropdown
3. Configura:
   - Giorni: Lunedì, Mercoledì, Venerdì
   - Ora inizio: 09:00
   - Ora fine: 18:00
   - Durata slot: 30 minuti
   - Data inizio: Data odierna
4. Click "Genera Slot"
5. **Risultato atteso**:
   - ✅ Nessun errore console
   - ✅ Nessun errore 500
   - ✅ Messaggio successo
   - ✅ Slot salvati nel database

### Test 2: Calendario Frontend

1. Apri pagina con shortcode calendario
2. Click su una data
3. **Risultato atteso**:
   - ✅ Modale multi-slot si apre
   - ✅ Griglia slot caricata
   - ✅ Nessun errore CSP nella console

### Test 3: Prenotazione Multi-Slot

1. Nel modale, seleziona 2-3 slot
2. Compila form prenotazione
3. Click "Conferma Prenotazione"
4. **Risultato atteso**:
   - ✅ Prenotazione salvata
   - ✅ Email conferma inviata
   - ✅ Modale si chiude

---

## 🔍 Debug Commands

Se riscontri problemi, usa questi comandi:

### Check CSP Headers
```bash
curl -I https://raffaelevitulano.com/wp-admin/ 2>&1 | grep -i content-security
```

### Check WordPress Error Log
```bash
docker exec wordpress-jewels-campania tail -50 /var/www/html/wp-content/debug.log
```

### Check Docker Container Logs
```bash
docker logs wordpress-jewels-campania --tail 100 | grep Error
```

### Check PHP Syntax
```bash
docker exec wordpress-jewels-campania php -l /var/www/html/wp-content/plugins/prenotazione-aule-ssm/admin/class-prenotazione-aule-ssm-admin.php
```

### Flush All Caches
```bash
docker exec wordpress-jewels-campania wp cache flush --path=/var/www/html --allow-root
```

---

## 📝 Note Tecniche

### Perché CSP in .htaccess e non MU-Plugin?

Apache (mod_headers) esegue prima di PHP, quindi:
- ✅ `.htaccess` imposta header prima che PHP venga eseguito
- ✅ MU-plugin tentava di modificare header già inviati
- ✅ Soluzione: CSP in `.htaccess`, MU-plugin disabilitato

### Perché array_map() Falliva?

WordPress AJAX non garantisce che tutti i parametri POST esistano:
- ❌ Accesso diretto a `$_POST['key']` può essere NULL
- ✅ Sempre usare `isset()` e validazione tipo
- ✅ Fornire fallback predefiniti (es. array vuoto)

### Best Practice PHP per AJAX

```php
// ❌ SBAGLIATO
$data = $_POST['data'];

// ✅ CORRETTO
$data = isset($_POST['data']) ? sanitize_text_field($_POST['data']) : '';

// ✅ ANCORA MEGLIO (per array)
$data = isset($_POST['data']) && is_array($_POST['data'])
    ? array_map('sanitize_text_field', $_POST['data'])
    : array();
```

---

## 🚀 Prossimi Step Raccomandati

### Opzionale - Miglioramenti Futuri

1. **Logging Avanzato**
   - Implementare error_log() in punti critici
   - Tracciare richieste AJAX per debug

2. **Validazione Frontend**
   - Aggiungere validazione JavaScript prima di submit
   - Prevenire invio dati incompleti

3. **Monitoraggio Errori**
   - Setup alert email per errori 500
   - Dashboard admin con log errori

4. **Performance**
   - Minify JS/CSS admin
   - Lazy load FullCalendar

---

## 📞 Supporto

Se i fix non risolvono i problemi:

1. **Hard Refresh Browser**: `CTRL + F5` (o `CMD + SHIFT + R` Mac)
2. **Pulisci Cache Browser**: Cancella cache completamente
3. **Verifica File**: Controlla che tutti i fix siano applicati
4. **Check Logs**: Leggi error log per dettagli

---

**File Creato**: 9 Ottobre 2025, ore 08:20 UTC
**Plugin**: Prenotazione Aule SSM v1.0.0
**Fixes Applicati**: 3 (tutti critici risolti)
