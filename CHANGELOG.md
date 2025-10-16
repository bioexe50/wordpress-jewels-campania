# Changelog - Mappa Aziende Campania

Tutte le modifiche notevoli a questo progetto saranno documentate in questo file.

Il formato si basa su [Keep a Changelog](https://keepachangelog.com/it/1.0.0/),
e questo progetto aderisce a [Semantic Versioning](https://semver.org/lang/it/).

## [1.3.0] - 2025-10-16

### 🐛 Bug Fixes Critici

#### JavaScript Runtime Errors
- **FIXED**: Errore `dbg is not defined` che bloccava completamente i filtri
  - Aggiunta funzione `dbg()` helper all'inizio del JavaScript generato
  - Implementato controllo `CCM_DEBUG` per attivazione logging
  - Aggiunta funzione `hudUpdate()` per pannello debug visuale

- **FIXED**: Chiamata a funzione inesistente `getIcon()`
  - Corretta in `iconForSector()` (nome corretto della funzione)
  - Ora le icone personalizzate per settore funzionano

#### Filtri Mappa

- **FIXED**: Filtri non funzionavano all'apertura della pagina
  - Rimosso doppio filtro client-side ridondante (filtro server + client)
  - Il server già filtra, non serve rifiltrare lato JavaScript

- **FIXED**: Tema WordPress che preselezionava filtri "Tutte le province/città"
  - Aggiunto reset forzato filtri al caricamento iniziale
  - Implementata pulizia parametri con regex `/tutt/i`
  - Doppia pulizia: in `getFilters()` e in `fetchData()`

- **FIXED**: Parametri vuoti inviati come stringhe "Tutte le..."
  - Funzione `readSelect()` riscritta per ignorare testo opzioni
  - Solo valori VALUE della select vengono inviati al server
  - Parametri vuoti non vengono più inviati all'AJAX

#### Timing & Inizializzazione

- **FIXED**: Mappa inizializzata prima del caricamento Leaflet
  - Aggiunto ritardo 500ms prima dell'init
  - Ulteriore ritardo 300ms per Leaflet completo
  - Totale: ~800ms garantisce stabilità cross-browser/tema

### ⚡ Miglioramenti Performance

- **OTTIMIZZATO**: Caricamento iniziale mappa
  - Sequenza: Reset filtri → Wait 500ms → Init mappa → Wait 300ms → Fetch data
  - Console logging strutturato per debug

- **OTTIMIZZATO**: Gestione filtri AJAX
  - Filtri vuoti non più inviati al server (riduzione payload)
  - Query SQL più efficiente senza WHERE inutili

### ✨ Nuove Funzionalità

- **DEBUG MODE**: Pulsante debug attivabile con `?debug_mappa` nell'URL
  - Mostra status completo: Div, Leaflet, Config, AJAX
  - Disponibile solo aggiungendo parametro URL
  - Non impatta performance in produzione

- **LOGGING AVANZATO**: Console.log strutturato per troubleshooting
  ```javascript
  - "=== CARICAMENTO INIZIALE MAPPA ==="
  - "initMap: inizializzazione mappa Leaflet..."
  - "updateMarkers: ricevute X aziende"
  - "Aggiungendo marker: [azienda] a [coordinate]"
  - "updateMarkers: aggiunti X marker su Y"
  ```

### 📝 Documentazione

- **AGGIORNATO**: Header plugin con metadati WordPress standard
  - `Requires at least: 5.8`
  - `Requires PHP: 7.4`
  - `Tested up to: 6.8`

- **CREATO**: README.md completo con:
  - Guida installazione (ZIP + FTP)
  - Formato CSV dettagliato con esempi
  - Troubleshooting comune
  - Configurazione avanzata
  - Performance benchmarks

- **CREATO**: CHANGELOG.md (questo file)

### 🔧 Modifiche Tecniche

#### File Modificati
- `campania-companies-map.php`: Plugin principale (~1,600 righe)
  - Rigenerazione assets JavaScript con fix
  - Gestione settori con icone personalizzate
  - Shortcode debug condizionale

#### Funzioni JavaScript Modificate/Aggiunte
- `dbg()`: Helper logging condizionale
- `hudUpdate()`: Pannello debug HUD
- `initMap()`: Try-catch + logging + whenReady()
- `getFilters()`: Semplificato, solo lettura VALUE
- `fetchData()`: Doppia pulizia filtri + logging
- `updateMarkers()`: Logging marker aggiunti
- Caricamento iniziale: Timeout strutturato

#### Database
- Schema invariato (versione 1.4)
- Nessuna migrazione richiesta
- Backward compatible con 1.2.9

### 🔐 Sicurezza

Nessun cambiamento di sicurezza in questa versione.
Tutte le pratiche esistenti mantenute:
- ✅ Nonce verification
- ✅ Capability checks
- ✅ Input sanitization
- ✅ Output escaping
- ✅ Prepared statements

### ⚠️ Breaking Changes

**NESSUNO** - La versione 1.3.0 è 100% backward compatible con 1.2.9.

Gli utenti possono aggiornare senza modifiche al database o configurazione.

### 📦 Installazione / Aggiornamento

#### Da 1.2.9 a 1.3.0:
1. Disattiva plugin 1.2.9
2. Elimina directory `wp-content/plugins/campania-companies-map/`
3. Carica e attiva 1.3.0
4. Gli assets verranno rigenerati automaticamente

**NOTA**: Non serve backup database (schema invariato).

#### Nuova Installazione:
1. Upload ZIP da WordPress admin → Plugin → Aggiungi nuovo
2. Attiva plugin
3. Vai su "Mappa Campania" per configurare

### 🐞 Known Issues

Nessun problema noto in questa versione.

Tutti i bug critici segnalati in 1.2.9 sono stati risolti.

### 👥 Contributori

- **Gabriele Bernini** - Sviluppo e manutenzione
- **Claude (Anthropic)** - Debug assistito e code review

---

## [1.2.9] - 2025-10-15

### 🎉 Release Iniziale Stabile

- ✅ Mappa interattiva Leaflet.js
- ✅ Upload CSV aziende
- ✅ Geocodifica Nominatim
- ✅ Filtri provincia/città/settore/prodotto
- ✅ Ricerca full-text
- ✅ Marker clustering
- ✅ Icone personalizzate per settore
- ✅ Admin CRUD aziende
- ✅ Shortcode `[campania_companies_map]`

### ⚠️ Problemi Noti (RISOLTI in 1.3.0)
- ❌ Errore JavaScript `dbg is not defined`
- ❌ Filtri non funzionavano al caricamento iniziale
- ❌ Tema interferiva con select box
- ❌ Timing inizializzazione mappa instabile

---

## Legenda Emoji

- 🎉 Release / Milestone
- ✨ Nuova funzionalità
- 🐛 Bug fix
- ⚡ Miglioramento performance
- 🔧 Modifiche tecniche
- 📝 Documentazione
- 🔐 Sicurezza
- ⚠️ Breaking change / Deprecato
- 🐞 Known issue
- 👥 Contributori

---

**Per segnalare bug o richiedere funzionalità:**
- Email: support@speaktoai.it
- GitHub: https://github.com/bioexe50/campania-companies-map/issues
