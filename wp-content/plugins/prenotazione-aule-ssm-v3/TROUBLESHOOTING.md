# TROUBLESHOOTING - Plugin Prenotazione Aule SSM

Documentazione dei problemi comuni e relative soluzioni definitive.

## Indice Rapido

### Problemi Risolti
1. [Popup di sistema + Modale Bootstrap](#problema-popup-di-sistema--modale-bootstrap-risolto-definitivamente) ⭐ **Più Recente**
2. [Contatori Prenotazioni Non Aggiornati](#fix-1-contatori-prenotazioni-non-aggiornati-2025-10-09)
3. [Mem0 Search API TypeError](#problema-mem0-search-api-typeerror-risolto)
4. [MySQL 8.0 Authentication](#problema-mysql-80-authentication-con-password-hardcoded)

### Guide Operative
5. [Bootstrap Non Caricato in Pagine Admin](#problema-bootstrap-non-caricato-in-pagine-admin)
6. [WordPress Debug Log](#problema-wordpress-debug-log-non-funziona)
7. [Five Layers Query Diagnostica](#problema-five-layers-query-non-restituisce-risultati)
8. [Git Authentication nei Container](#problema-git-authentication-issues-nei-container)

### Risorse
- [Checklist Pre-Deploy Completa](#checklist-pre-deploy-completa)
- [Cronologia Modifiche](#cronologia-modifiche-completa)
- [Documentazione e Script Utili](#risorse-utili)

---

## Problema: Popup di sistema + Modale Bootstrap (RISOLTO DEFINITIVAMENTE)

### Descrizione del Problema
Quando si interagisce con le funzionalità di approvazione, rifiuto o eliminazione delle prenotazioni nella pagina `/wp-admin/admin.php?page=prenotazione-aule-ssm-prenotazioni`, si verificava un comportamento duplicato:

1. Prima si apriva un **popup di sistema** (`confirm()` o `prompt()`)
2. Poi si apriva il **modale Bootstrap** professionale

Questo causava una pessima esperienza utente e sembrava un bug.

### Causa Root
Il problema era causato da **gestori eventi duplicati**:

1. **JavaScript globale** (`prenotazione-aule-ssm-admin.js`):
   - Linee 46-48 registravano handler con `$(document).on('click', '.approve-booking', ...)`
   - Questi handler usavano `confirm()` e `prompt()` nativi del browser
   - Gli handler venivano eseguiti PRIMA del codice inline della pagina

2. **JavaScript inline** (nella pagina `prenotazione-aule-ssm-admin-prenotazioni.php`):
   - Aveva handler moderni con modali Bootstrap
   - Ma venivano eseguiti DOPO gli handler globali

**Ordine di esecuzione problematico:**
```
User click → Handler globale JS (confirm popup) → Handler inline (modale Bootstrap)
```

### Soluzione Definitiva

#### 1. Disabilitare gli handler globali per le prenotazioni

**File modificato:** `/var/www/wordpress/jewels-campania/data/wp-content/plugins/prenotazione-aule-ssm/admin/js/prenotazione-aule-ssm-admin.js`

**Linee 44-48 (PRIMA):**
```javascript
bindEvents: function() {
    // Gestione prenotazioni
    $(document).on('click', '.approve-booking', this.handleApproveBooking);
    $(document).on('click', '.reject-booking', this.handleRejectBooking);
    $(document).on('click', '.delete-booking', this.handleDeleteBooking);
```

**Linee 44-48 (DOPO):**
```javascript
bindEvents: function() {
    // Gestione prenotazioni - DISABILITATI per pagina prenotazioni (usa modali inline)
    // Solo per altre pagine che potrebbero usarli
    // $(document).on('click', '.approve-booking', this.handleApproveBooking);
    // $(document).on('click', '.reject-booking', this.handleRejectBooking);
    // $(document).on('click', '.delete-booking', this.handleDeleteBooking);
```

#### 2. Implementare modali Bootstrap professionali

**File modificato:** `/var/www/wordpress/jewels-campania/data/wp-content/plugins/prenotazione-aule-ssm/admin/partials/prenotazione-aule-ssm-admin-prenotazioni.php`

**Componenti aggiunti:**
- ✅ Modal Bootstrap per approvazione (verde)
- ✅ Modal Bootstrap per rifiuto (rosso con campo obbligatorio)
- ✅ Modal Bootstrap per eliminazione (rosso con alert)
- ✅ Modal Bootstrap per dettagli prenotazione
- ✅ JavaScript inline con handler Bootstrap 5
- ✅ Spinner di caricamento durante le operazioni AJAX
- ✅ Validazione campi obbligatori
- ✅ Stili CSS personalizzati WordPress-compatibili

### Verifica della Soluzione

**Test da eseguire:**
1. Vai su `https://raffaelevitulano.com/wp-admin/admin.php?page=prenotazione-aule-ssm-prenotazioni`
2. Clicca su "✅ Approva" → Si apre SOLO il modale verde professionale (NO popup)
3. Clicca su "❌ Rifiuta" → Si apre SOLO il modale rosso con textarea (NO prompt)
4. Clicca su "🗑️ Elimina" → Si apre SOLO il modale rosso di conferma (NO confirm)
5. Clicca su "👁️ Dettagli" → Si apre SOLO il modale info (NO alert)

**Output atteso:**
- ✅ Zero popup di sistema
- ✅ Modali Bootstrap professionali con animazioni smooth
- ✅ Validazione lato client prima dell'invio AJAX
- ✅ Spinner durante il caricamento
- ✅ Messaggi di errore user-friendly

### Architettura della Soluzione

```
┌─────────────────────────────────────────────────────────┐
│                  User Click Button                       │
└─────────────────────┬───────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────┐
│  prenotazione-aule-ssm-admin.js (DISABILITATO)          │
│  // Handler commentati - non intercetta più l'evento    │
└─────────────────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────┐
│  Inline JavaScript (pagina prenotazioni)                │
│  ├─ Mostra modal Bootstrap 5                            │
│  ├─ Valida input utente                                 │
│  ├─ Invia AJAX request                                  │
│  └─ Gestisce response/errors                            │
└─────────────────────────────────────────────────────────┘
```

### Benefici della Soluzione

1. **UX Professionale**: Modali Bootstrap coerenti con il resto del plugin
2. **Validazione Avanzata**: Campi obbligatori verificati prima dell'invio
3. **Feedback Visivo**: Spinner e messaggi di stato durante le operazioni
4. **Coerenza**: Stesso stile usato nelle altre sezioni (Slot, Dashboard)
5. **Manutenibilità**: Codice inline evita conflitti con altri handler globali
6. **Accessibilità**: Modali Bootstrap 5 con attributi ARIA completi

### Nota Importante per Futuri Sviluppi

⚠️ **Non riabilitare gli handler in `prenotazione-aule-ssm-admin.js` senza prima verificare:**
- Se esistono altre pagine che li usano (Dashboard, Reports, ecc.)
- Se esistono, implementare una condizione per escludere la pagina prenotazioni:

```javascript
bindEvents: function() {
    // Solo su pagine diverse da prenotazioni
    if (!window.location.href.includes('prenotazione-aule-ssm-prenotazioni')) {
        $(document).on('click', '.approve-booking', this.handleApproveBooking);
        $(document).on('click', '.reject-booking', this.handleRejectBooking);
        $(document).on('click', '.delete-booking', this.handleDeleteBooking);
    }
}
```

---

## Fix Applicati

### Fix #1: Contatori Prenotazioni Non Aggiornati (2025-10-09)

**Problema:** Il contatore "Confermate" non mostrava le prenotazioni approvate.

**Causa:** Disallineamento tra stato database (`confermata` singolare) e chiave contatore (`confermate` plurale).

**Soluzione:** Mappatura stati nel loop contatori (linee 35-50).

```php
// Mappa lo stato singolare al contatore plurale
$stato_key = $prenotazione->stato;
if ($stato_key === 'confermata') {
    $stato_key = 'confermate';
} elseif ($stato_key === 'rifiutata') {
    $stato_key = 'rifiutate';
}
```

### Fix #2: Popup + Modale Duplicati (2025-10-09)

**Problema:** Doppia interazione (popup sistema + modale Bootstrap).

**Soluzione:** Handler globali disabilitati + modali Bootstrap inline (vedi sopra).

---

## Checklist Pre-Deploy

Prima di ogni deploy del plugin, verificare:

- [ ] I modali si aprono correttamente senza popup
- [ ] La validazione campi funziona (campo motivo rifiuto obbligatorio)
- [ ] Le operazioni AJAX completano con successo
- [ ] I contatori mostrano i numeri corretti
- [ ] Gli spinner appaiono durante le operazioni
- [ ] I messaggi di errore sono user-friendly
- [ ] Bootstrap 5 è caricato correttamente nella pagina
- [ ] Non ci sono errori in console JavaScript

---

## Cronologia Modifiche

| Data       | Problema                        | File Modificati                                       | Status     |
|------------|---------------------------------|-------------------------------------------------------|------------|
| 2025-10-09 | Contatori non aggiornati        | prenotazione-aule-ssm-admin-prenotazioni.php (L35-50) | ✅ Risolto |
| 2025-10-09 | Popup + Modale duplicati        | prenotazione-aule-ssm-admin.js (L46-48)               | ✅ Risolto |
| 2025-10-09 | Implementazione modali Bootstrap| prenotazione-aule-ssm-admin-prenotazioni.php (L307+)  | ✅ Risolto |

---

## Problema: Mem0 Search API TypeError (RISOLTO)

### Descrizione del Problema
Quando si tenta di usare l'API `/search` di Mem0, si verifica un errore Python:
```
TypeError: 'str' object has no attribute 'get'
```

### Causa Root
Bug nel codice Python di `mem0_server.py` - la funzione `search_context()` si aspettava che `memory.search()` restituisse dizionari, ma in realtà restituiva stringhe.

### Soluzione Definitiva
**File modificato:** `/docker/configs/mem0/mem0_server.py`

Implementato robust type handling nella funzione `search_context()`:

```python
def search_context(query: str, limit: int = 5):
    results = memory.search(query, limit=limit)
    formatted_results = []

    for item in results:
        # Handle both dict and string results
        if isinstance(item, dict):
            formatted_results.append({
                "content": item.get("memory", ""),
                "metadata": item.get("metadata", {}),
                "score": item.get("score", 0.0)
            })
        elif isinstance(item, str):
            formatted_results.append({
                "content": item,
                "metadata": {},
                "score": 1.0
            })

    return formatted_results
```

**Documentazione completa:** `/var/www/MEM0_PROCEDURE_STANDARD.md`

### Verifica
```bash
curl -X POST http://localhost:3335/search \
  -H "Content-Type: application/json" \
  -d '{"query": "test", "limit": 5}'

# Expected: {"results":[{"content":"...","metadata":{},"score":0.8}]}
```

**Status:** ✅ RISOLTO - Container ricostruito 2025-09-24

---

## Problema: MySQL 8.0 Authentication con Password Hardcoded

### Descrizione del Problema
I comandi MySQL con password hardcoded falliscono sempre:
```bash
docker exec mysql mysql -uroot -p'MySQL_Sicura_2025!' database
# ERROR: Access denied
```

### Causa Root
MySQL 8.0 usa `caching_sha2_password` che non accetta password in plain text nel comando per motivi di sicurezza.

### Soluzione Definitiva
**Usare sempre la variabile ambiente del container:**

```bash
# ❌ SBAGLIATO (sempre fallisce):
docker exec mysql mysql -uroot -p'PASSWORD' database

# ✅ CORRETTO (sempre funziona):
docker exec mysql bash -c "mysql -uroot -p\$MYSQL_ROOT_PASSWORD database"
```

**Script creati:**
- `/root/scripts/wp-db-connect.sh` - Connessione WordPress immediata
- `/root/scripts/mysql-restore-definitivo.sh` - Ripristino che funziona sempre
- `/root/scripts/wp-backup-rapido.sh` - Backup con dati reali verificati

### Esempio Utilizzo
```bash
# Test connessione
/root/scripts/wp-db-connect.sh test

# Query WordPress
/root/scripts/wp-db-connect.sh query "SELECT COUNT(*) FROM jc_posts;"

# Backup database
/root/scripts/wp-backup-rapido.sh backup

# Restore con auto-rollback
/root/scripts/mysql-restore-definitivo.sh jewels_campania /path/to/backup.sql
```

**Status:** ✅ RISOLTO - Script definitivi creati 2025-09-25

---

## Problema: Bootstrap Non Caricato in Pagine Admin

### Descrizione del Problema
I modali Bootstrap non funzionano in alcune pagine admin del plugin, generando errore:
```
Uncaught TypeError: bootstrap is not defined
```

### Causa Root
Bootstrap 5.3.0 viene caricato solo in pagine specifiche tramite condizione `in_array($hook_suffix, ...)` in `class-prenotazione-aule-ssm-admin.php`.

### Soluzione
Verificare che la pagina sia inclusa nella whitelist di caricamento Bootstrap:

**File:** `/var/www/wordpress/jewels-campania/data/wp-content/plugins/prenotazione-aule-ssm/admin/class-prenotazione-aule-ssm-admin.php`

**Linee 77-84 (CSS):**
```php
if (in_array($hook_suffix, array(
    'toplevel_page_prenotazione-aule-ssm',
    'gestione-aule_page_prenotazione-aule-ssm-prenotazioni',
    'gestione-aule_page_prenotazione-aule-ssm-slot'
))) {
    wp_enqueue_style('bootstrap-modal', ...);
}
```

**Linee 134-142 (JavaScript):**
```php
if (in_array($hook_suffix, array(
    'toplevel_page_prenotazione-aule-ssm',
    'gestione-aule_page_prenotazione-aule-ssm-prenotazioni',
    'gestione-aule_page_prenotazione-aule-ssm-slot'
))) {
    wp_enqueue_script('bootstrap-modal', ...);
}
```

### Come Aggiungere Nuova Pagina
1. Identifica `$hook_suffix` della pagina (usa `var_dump($hook_suffix)`)
2. Aggiungi alla whitelist in entrambe le funzioni `enqueue_styles()` e `enqueue_scripts()`
3. Verifica in console browser che Bootstrap sia caricato

**Status:** ✅ DOCUMENTATO

---

## Problema: WordPress Debug Log Non Funziona

### Descrizione del Problema
I log di debug WordPress non vengono scritti in `/var/www/html/wp-content/debug.log`.

### Soluzione Rapida
```bash
# Abilita debug WordPress
docker exec wordpress sh -c "cat >> /var/www/html/wp-config.php <<'EOF'

// Debug mode
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
EOF"

# Verifica debug attivo
docker exec wordpress grep "WP_DEBUG" /var/www/html/wp-config.php

# Monitora log in real-time
docker exec wordpress tail -f /var/www/html/wp-content/debug.log
```

**Note:**
- `WP_DEBUG_LOG` scrive su file
- `WP_DEBUG_DISPLAY` false nasconde errori da frontend
- Non attivare in produzione

**Status:** ✅ DOCUMENTATO

---

## Problema: Five Layers Query Non Restituisce Risultati

### Descrizione del Problema
Il comando `query-fusion.py` non restituisce risultati da uno o più layer.

### Checklist Diagnostica

#### Layer 1 (Semantic - Qdrant)
```bash
curl -s http://localhost:6333/collections/jewels-campania | jq '.result.points_count'
# Expected: 4505+
```

#### Layer 2 (Functions - Qdrant)
```bash
curl -s http://localhost:6333/collections/wordpress-functions-v2 | jq '.result.points_count'
# Expected: 7840+
```

#### Layer 3 (Database - Qdrant)
```bash
curl -s http://localhost:6333/collections/wordpress-database-enhanced | jq '.result.points_count'
# Expected: 28+
```

#### Layer 4 (Graph - Neo4j)
```bash
docker exec neo4j cypher-shell -u neo4j -p Neo4j_Sicura_2025! \
  "MATCH (n) RETURN count(n) as nodes;"
# Expected: 11633+
```

#### Layer 5 (Memory - Mem0)
```bash
curl -s http://localhost:3335/health
# Expected: {"status":"healthy"}
```

### Se Un Layer Fallisce
1. Controlla che il servizio sia up: `docker ps | grep <service>`
2. Verifica i log: `docker logs <service> --tail 50`
3. Re-index se necessario (script in `/opt/ai-stack/` e `/root/scripts/`)

**Status:** ✅ DOCUMENTATO

---

## Problema: Git Authentication Issues nei Container

### Descrizione del Problema
Le operazioni git falliscono con errore di autenticazione nei container WordPress.

### Soluzione
```bash
# Configura git credential helper nel container
docker exec wordpress-jewels-campania git config --global credential.helper store

# Imposta credenziali con PAT (Personal Access Token)
docker exec wordpress-jewels-campania git config --global user.name "bioexe50"
docker exec wordpress-jewels-campania git config --global user.email "your-email@example.com"

# Prima push richiederà username + PAT
# PAT viene salvato in ~/.git-credentials

# Verifica configurazione
docker exec wordpress-jewels-campania git config --list
```

**Genera PAT GitHub:**
1. GitHub → Settings → Developer Settings → Personal Access Tokens
2. Generate new token (classic)
3. Seleziona scopes: `repo` (full control)
4. Copia token e usalo come password

**Status:** ✅ DOCUMENTATO

---

## Checklist Pre-Deploy (COMPLETA)

Prima di ogni deploy del plugin, verificare:

### Frontend
- [ ] I modali si aprono correttamente senza popup
- [ ] La validazione campi funziona
- [ ] Le operazioni AJAX completano con successo
- [ ] I contatori mostrano i numeri corretti
- [ ] Gli spinner appaiono durante le operazioni
- [ ] I messaggi di errore sono user-friendly
- [ ] Bootstrap 5 è caricato correttamente
- [ ] Non ci sono errori in console JavaScript

### Backend
- [ ] Five Layers restituisce risultati da tutti i layer
- [ ] Mem0 API `/search` funziona senza errori
- [ ] MySQL connessioni funzionano con script aggiornati
- [ ] WordPress debug log è accessibile
- [ ] Git authentication funziona nei container
- [ ] Backup automatici sono attivi
- [ ] Tutti i container Docker sono UP

### Performance
- [ ] Query Qdrant < 100ms
- [ ] Query Neo4j < 200ms
- [ ] Mem0 response < 500ms
- [ ] WordPress load time < 2s
- [ ] Nessun memory leak

---

## Problema: Pagina Report Non Mostra Dati (RISOLTO)

### Descrizione del Problema
La pagina Report & Statistiche (`/wp-admin/admin.php?page=prenotazione-aule-ssm-reports`) non mostrava dati anche se esistevano prenotazioni nel database.

### Causa Root
Le prenotazioni nel database erano per date future (ottobre 2025), ma il filtro predefinito cercava solo gli "Ultimi 30 giorni" (date passate). La query SQL:
```sql
WHERE data_prenotazione BETWEEN '2025-09-09' AND '2025-10-09'
```
Non trovava prenotazioni con date come `2025-10-16` o `2025-10-23`.

### Soluzione Definitiva

#### 1. Aggiunti nuovi filtri periodo
**File:** `/var/www/wordpress/jewels-campania/data/wp-content/plugins/prenotazione-aule-ssm/admin/partials/prenotazione-aule-ssm-admin-reports.php`

**Nuove opzioni aggiunte (L152-154):**
```php
<option value="future">📅 Prossimi 30 giorni (Future)</option>
<option value="all">🗓️ Tutte le prenotazioni</option>
```

**Logica calcolo date (L39-48):**
```php
case 'future':
    $start_date = current_time('Y-m-d');
    $end_date = date('Y-m-d', strtotime('+30 days'));
    break;
case 'all':
    $start_date = date('Y-m-d', strtotime('-2 years'));
    $end_date = date('Y-m-d', strtotime('+1 year'));
    break;
```

#### 2. Integrato Chart.js per grafici professionali
**File:** `/var/www/wordpress/jewels-campania/data/wp-content/plugins/prenotazione-aule-ssm/admin/class-prenotazione-aule-ssm-admin.php`

**Caricamento libreria (L107-115):**
```php
if ($hook_suffix === 'gestione-aule_page_prenotazione-aule-ssm-reports') {
    wp_enqueue_style('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/...');
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/...');
}
```

**Grafico interattivo (L318-329 in reports.php):**
```html
<canvas id="trendsChart"></canvas>
```

**Configurazione Chart.js (L393-438):**
- Grafico a linee con doppio dataset (Totale + Confermate)
- Colori personalizzati WordPress (#0073aa, #46b450)
- Tooltip interattivi
- Responsive design

#### 3. Aggiornato Export CSV
Inclusi nuovi periodi nella funzione `export_reports_csv()` (L428-442 in class-prenotazione-aule-ssm-admin.php)

### Verifica della Soluzione

**Test da eseguire:**
1. Vai su `https://raffaelevitulano.com/wp-admin/admin.php?page=prenotazione-aule-ssm-reports`
2. Seleziona filtro "📅 Prossimi 30 giorni (Future)"
3. Verifica che appaiano le statistiche con dati reali
4. Controlla che il grafico Chart.js sia interattivo
5. Testa export CSV con i nuovi filtri

**Query di verifica dati:**
```sql
SELECT data_prenotazione, COUNT(*)
FROM jc_prenotazione_aule_ssm_prenotazioni
WHERE data_prenotazione >= CURDATE()
GROUP BY data_prenotazione;

-- Risultato atteso: 17 prenotazioni totali (2 confermate, 13 in attesa)
```

### Miglioramenti Implementati

1. **Filtri Periodo Estesi:**
   - Ultimi 7/30/90/365 giorni (passato)
   - 📅 Prossimi 30 giorni (futuro) ← **NUOVO**
   - 🗓️ Tutte le prenotazioni ← **NUOVO**
   - Periodo personalizzato

2. **Grafici Professionali:**
   - Chart.js 4.4.0 integrato
   - Grafico a linee interattivo
   - Tooltip con dettagli
   - Design responsive

3. **Export CSV Completo:**
   - Supporta tutti i nuovi filtri
   - Include intestazioni corrette
   - Formattazione Excel-compatibile

4. **UI/UX Migliorata:**
   - Card statistiche con hover effects
   - Grafici colorati coerenti con WP
   - Indicatori visuali chiari
   - Mobile-responsive

**Status:** ✅ RISOLTO - Pagina report completamente funzionale

---

## Cronologia Modifiche (COMPLETA)

| Data       | Problema                        | File Modificati                                       | Status     |
|------------|---------------------------------|-------------------------------------------------------|------------|
| 2025-09-24 | Mem0 Search TypeError           | /docker/configs/mem0/mem0_server.py                   | ✅ Risolto |
| 2025-09-25 | MySQL Authentication Issues     | /root/scripts/wp-db-connect.sh, mysql-restore.sh      | ✅ Risolto |
| 2025-10-09 | Contatori non aggiornati        | prenotazione-aule-ssm-admin-prenotazioni.php (L35-50) | ✅ Risolto |
| 2025-10-09 | Popup + Modale duplicati        | prenotazione-aule-ssm-admin.js (L46-48)               | ✅ Risolto |
| 2025-10-09 | Implementazione modali Bootstrap| prenotazione-aule-ssm-admin-prenotazioni.php (L307+)  | ✅ Risolto |
| 2025-10-09 | Pagina Report senza dati        | prenotazione-aule-ssm-admin-reports.php + admin.php   | ✅ Risolto |

---

## Risorse Utili

### Documentazione
- **Five Layers Architecture:** `/opt/ai-stack/FIVE_LAYERS_ARCHITECTURE.md`
- **Mem0 Procedures:** `/var/www/MEM0_PROCEDURE_STANDARD.md`
- **Debug Tools:** `/root/scripts/DEBUG_TOOLS_README.md`
- **System Documentation:** `/docker/configs/DOCUMENTAZIONE.md`
- **Claude Code Guide:** `/var/www/CLAUDE.md`

### Script Essenziali
- `/root/scripts/wp-db-connect.sh` - WordPress DB access
- `/root/scripts/wp-backup-rapido.sh` - Fast backup
- `/root/scripts/mysql-restore-definitivo.sh` - Safe restore
- `/root/scripts/pa-accessibility-audit.sh` - Accessibility audit
- `/opt/ai-stack/query-fusion.py` - Five Layers search

### Servizi e Porte
- Qdrant: http://72.60.178.169:6333/dashboard
- Neo4j: http://72.60.178.169:7474
- Mem0: http://72.60.178.169:3335/health
- WordPress: http://72.60.178.169:8084
- phpMyAdmin: http://72.60.178.169:8081

---

**Ultimo aggiornamento:** 2025-10-09
**Versione plugin:** 1.0.0
**Versione Bootstrap:** 5.3.0
**Sistema:** Ubuntu 24.04.3 LTS | Docker 27.3.1 | PHP 8.1 | MySQL 8.0
