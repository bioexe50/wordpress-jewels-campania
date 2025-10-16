# Guida Installazione - Plugin Prenotazione Aule SSM v2.1.0

## 📦 Download

Il plugin è disponibile come file ZIP: `prenotazione-aule-ssm-v2.1.0.zip`

## 📋 Requisiti di Sistema

### WordPress
- **WordPress:** 6.0 o superiore
- **PHP:** 7.4 o superiore
- **MySQL:** 5.7 o superiore (o MariaDB 10.3+)

### Estensioni PHP Richieste
- `mysqli` - Connessione database
- `json` - Gestione dati JSON
- `mbstring` - Supporto caratteri multibyte
- `curl` - Chiamate API esterne (opzionale)

### Raccomandazioni Server
- **Memoria PHP:** Minimo 128MB (consigliato 256MB)
- **Max Upload Size:** Minimo 10MB (per upload immagini aule)
- **Max Execution Time:** 60 secondi

## 🚀 Installazione

### Metodo 1: Tramite Dashboard WordPress (Consigliato)

1. **Login** in WordPress come amministratore
2. Vai su **Plugin → Aggiungi Nuovo**
3. Clicca su **Carica Plugin** (in alto)
4. Clicca su **Scegli File** e seleziona `prenotazione-aule-ssm-v2.1.0.zip`
5. Clicca su **Installa Ora**
6. Clicca su **Attiva Plugin**

### Metodo 2: Caricamento FTP/SFTP

1. **Estrai** il file ZIP sul tuo computer
2. **Carica** la cartella `prenotazione-aule-ssm` via FTP/SFTP in:
   ```
   /wp-content/plugins/
   ```
3. Vai su **Plugin** nella dashboard WordPress
4. Trova "Prenotazione Aule SSM" e clicca su **Attiva**

### Metodo 3: Via WP-CLI

```bash
wp plugin install /path/to/prenotazione-aule-ssm-v2.1.0.zip --activate
```

## ⚙️ Configurazione Iniziale

### 1. Verifica Installazione

Dopo l'attivazione, dovresti vedere nel menu laterale:
- **Gestione Aule** (menu principale)
  - Dashboard
  - Tutte le Aule
  - Aggiungi Aula
  - Prenotazioni
  - Slot Disponibilità
  - Impostazioni
  - Report

### 2. Configurazione Database

Il plugin crea automaticamente le seguenti tabelle:
- `{prefix}_prenotazione_aule_ssm_aule` - Aule
- `{prefix}_prenotazione_aule_ssm_prenotazioni` - Prenotazioni
- `{prefix}_prenotazione_aule_ssm_slot` - Slot disponibilità

⚠️ **Nota:** Dove `{prefix}` è il prefisso database WordPress (default: `wp_`)

### 3. Configurazione Permessi

Il plugin crea automaticamente i seguenti permessi personalizzati:
- `manage_prenotazione_aule_ssm` - Gestione completa
- `edit_prenotazione_aule_ssm` - Modifica aule e slot
- `delete_prenotazione_aule_ssm` - Eliminazione elementi
- `view_prenotazione_aule_ssm_reports` - Visualizzazione report

**Ruoli con accesso:**
- ✅ **Amministratore** - Accesso completo
- ✅ **Editor** - Gestione prenotazioni e aule
- ❌ **Autore, Collaboratore, Sottoscrittore** - Nessun accesso backend

### 4. Impostazioni Iniziali

Vai su **Gestione Aule → Impostazioni** e configura:

#### 4.1. Email Notifiche
- Email mittente (default: admin email WordPress)
- Nome mittente
- Attiva/Disattiva email di conferma

#### 4.2. Prenotazioni
- Ore di anticipo minime per prenotazione
- Numero massimo prenotazioni simultanee per utente
- Durata minima/massima prenotazione

#### 4.3. Calendario
- Giorni della settimana attivi
- Orari di apertura/chiusura
- Slot temporali (es: ogni 30 minuti, 1 ora, ecc.)

#### 4.4. Template Email - Placeholder Disponibili

I template email supportano i seguenti placeholder che vengono automaticamente sostituiti con i dati reali:

**📋 Dati Richiedente:**
- `{nome_richiedente}` - Nome del richiedente
- `{cognome_richiedente}` - Cognome del richiedente
- `{email_richiedente}` - Email del richiedente (solo template admin)

**🏛️ Dati Aula:**
- `{nome_aula}` - Nome dell'aula prenotata
- `{ubicazione}` - Ubicazione dell'aula

**📅 Dati Prenotazione:**
- `{data_prenotazione}` - Data formattata (es: "Lunedì, 10 ottobre 2025")
- `{ora_inizio}` - Ora inizio (formato HH:mm)
- `{ora_fine}` - Ora fine (formato HH:mm)
- `{motivo}` - Motivo della prenotazione
- `{stato_prenotazione}` - Stato attuale (In Attesa/Confermata/Rifiutata)
- `{codice_prenotazione}` - Codice univoco della prenotazione
- `{note_admin}` - Note dell'amministratore (se presenti)

**🌐 Dati Sistema:**
- `{link_gestione}` - Link diretto alla gestione prenotazione (solo admin)
- `{sito_nome}` - Nome del sito WordPress
- `{sito_url}` - URL homepage del sito

**🎨 Formattazione HTML ed Emoji:**

Puoi usare HTML ed emoji per personalizzare l'aspetto delle email:

```html
Gentile {nome_richiedente} {cognome_richiedente},

La sua prenotazione è stata confermata! 🎉

<div class="booking-details">
<strong>📋 Dettagli della prenotazione:</strong><br>
📍 Aula: {nome_aula}<br>
📍 Ubicazione: {ubicazione}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
📝 Motivo: {motivo}<br>
🔖 Codice: {codice_prenotazione}
</div>

{note_admin}

<a href="{link_gestione}" class="button">Gestisci Prenotazione</a>

Cordiali saluti,<br>
Il team di {sito_nome}
```

**Classi CSS Disponibili:**

- `.booking-details` - Box grigio chiaro con bordo blu a sinistra
- `.button` - Pulsante blu WordPress-style (solo per link)
- `<strong>` - Testo in grassetto
- `<br>` - A capo (interruzione linea)

**Stili Automatici dell'Email:**

Tutte le email vengono automaticamente incapsulate in un template HTML professionale con:
- 📧 **Header blu WordPress** (#2271b1) con titolo
- 📄 **Body bianco** con padding 30px per leggibilità
- 🔗 **Footer grigio chiaro** con nome e URL sito
- 📱 **Design responsive** (max-width: 600px, ottimizzato mobile)
- ✍️ **Font Arial** con line-height ottimizzato
- 🎨 **Box shadow** per profondità visiva

**Esempio Completo Email di Conferma:**

```html
Gentile {nome_richiedente} {cognome_richiedente},

La sua prenotazione è stata confermata con successo! ✅

<div class="booking-details">
<strong>📋 Riepilogo Prenotazione:</strong><br><br>
📍 <strong>Aula:</strong> {nome_aula}<br>
📍 <strong>Ubicazione:</strong> {ubicazione}<br>
📅 <strong>Data:</strong> {data_prenotazione}<br>
🕒 <strong>Orario:</strong> {ora_inizio} - {ora_fine}<br>
📝 <strong>Motivo:</strong> {motivo}<br>
🔖 <strong>Codice Prenotazione:</strong> {codice_prenotazione}
</div>

<strong>⚠️ Importante:</strong><br>
Si prega di presentarsi puntualmente all'orario prenotato con un documento d'identità valido.

Per qualsiasi modifica o cancellazione, contattare l'amministrazione.

Cordiali saluti,<br>
<strong>Il team di {sito_nome}</strong><br>
🌐 <a href="{sito_url}">{sito_url}</a>
```

**Note:**
- I placeholder vengono sostituiti automaticamente al momento dell'invio
- Se un placeholder non ha valore (es: `{note_admin}` vuoto), viene rimosso
- HTML è permesso, ma JavaScript e CSS inline esterni vengono filtrati per sicurezza
- Puoi testare i template con il pulsante "Anteprima Email" nelle impostazioni

## 📝 Creazione Prima Aula

1. Vai su **Gestione Aule → Aggiungi Aula**
2. Compila i campi:
   - **Nome Aula:** (es: "Aula Magna", "Sala Conferenze")
   - **Codice:** (es: "AM01", "SC02")
   - **Capacità:** Numero posti
   - **Descrizione:** Dettagli e attrezzature
   - **Immagini:** Upload foto aula (opzionale)
   - **Stato:** Attiva/Non attiva
3. Clicca **Salva Aula**

## 🗓️ Configurazione Slot Disponibilità

1. Vai su **Gestione Aule → Slot Disponibilità**
2. Clicca **+ Aggiungi Nuovo Slot**
3. Configura:
   - **Aula:** Seleziona aula
   - **Data:** Giorno disponibilità
   - **Ora Inizio/Fine:** Range orario
   - **Ricorrenza:** (opzionale) Giornaliera/Settimanale
4. Clicca **Salva Slot**

## 🎨 Shortcode per Frontend

### Calendario Prenotazioni

```php
[prenotazione_aule_calendar]
```

**Parametri disponibili:**
```php
[prenotazione_aule_calendar
    aula_id="1"           // Mostra solo una specifica aula
    theme="light"         // Tema: light o dark
    show_legend="true"    // Mostra legenda
]
```

### Inserimento in Pagina/Post

1. Crea una nuova **Pagina**
2. Titolo: "Prenota Aula"
3. Inserisci shortcode nel contenuto:
   ```
   [prenotazione_aule_calendar]
   ```
4. Pubblica

## 🔐 Sicurezza e Permessi

### File Permissions (Linux/Unix)

```bash
# Directory plugin
chmod 755 /wp-content/plugins/prenotazione-aule-ssm

# File PHP
chmod 644 /wp-content/plugins/prenotazione-aule-ssm/**/*.php

# Upload directory (se abiliti upload immagini)
chmod 755 /wp-content/uploads
```

### Capability Check

Il plugin verifica sempre i permessi utente:
- Backend: `manage_prenotazione_aule_ssm`
- Frontend: Utenti registrati (opzionale: anche guest)

## 📊 Test Funzionalità

### Checklist Post-Installazione

- [ ] Menu "Gestione Aule" visibile nel backend
- [ ] Dashboard mostra statistiche
- [ ] Puoi creare una nuova aula
- [ ] Puoi aggiungere slot disponibilità
- [ ] Calendario frontend funziona con shortcode
- [ ] Prenotazione test completata con successo
- [ ] Email di conferma ricevuta (se abilitate)
- [ ] Report mostrano dati corretti

## 🐛 Troubleshooting

### Plugin non appare nel menu

**Soluzione:**
1. Verifica attivazione: `Plugin → Plugin Installati`
2. Controlla permessi utente (deve essere Admin)
3. Pulisci cache WordPress

### Errore "Tabelle mancanti"

**Soluzione:**
```bash
# Via WP-CLI
wp plugin deactivate prenotazione-aule-ssm
wp plugin activate prenotazione-aule-ssm
```

**O via dashboard:**
1. Disattiva plugin
2. Riattiva plugin (crea tabelle automaticamente)

### Calendario frontend non mostra date

**Soluzione:**
1. Verifica che esistano slot disponibilità
2. Controlla impostazioni calendario
3. Abilita debug WordPress:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

### Email non inviate

**Soluzione:**
1. Verifica impostazioni SMTP WordPress
2. Installa plugin SMTP (es: WP Mail SMTP)
3. Controlla logs email in debug

### Errori JavaScript console

**Soluzione:**
1. Pulisci cache browser
2. Verifica Bootstrap 5.3.0 caricato
3. Controlla conflitti con altri plugin

## 📖 Documentazione Completa

Dopo l'installazione, troverai nella directory del plugin:

- **README.md** - Panoramica generale
- **CHANGELOG.md** - Cronologia modifiche
- **SHORTCODES.md** - Lista completa shortcode
- **MULTI_SLOT_DOCUMENTATION.md** - Guida slot multipli
- **TROUBLESHOOTING.md** - Problemi comuni e soluzioni

## 🆘 Supporto

### Log di Debug

Attiva debug WordPress:
```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Log disponibili in:
- `/wp-content/debug.log` - Errori PHP WordPress
- Browser Console - Errori JavaScript

### Informazioni Sistema

Per richiedere supporto, fornisci:
- Versione WordPress
- Versione PHP
- Versione plugin
- Log errori (se presenti)
- Screenshot problema

## 🔄 Aggiornamenti

### Aggiornamento Plugin

**IMPORTANTE:** Fai sempre backup prima di aggiornare!

1. **Backup database:**
   ```bash
   wp db export backup-$(date +%Y%m%d).sql
   ```

2. **Backup plugin:**
   - Copia cartella plugin via FTP
   - O usa plugin backup (es: UpdraftPlus)

3. **Aggiorna:**
   - Carica nuovo ZIP
   - Sovrascrivi vecchi file
   - Riattiva se necessario

## 📞 Informazioni Plugin

- **Nome:** Prenotazione Aule SSM
- **Versione:** 2.1.0
- **Autore:** SSM Developer
- **Licenza:** GPL v2 or later
- **Text Domain:** prenotazione-aule-ssm

## 🎉 Installazione Completata!

Il plugin è ora pronto all'uso. Buona gestione delle prenotazioni! 🚀

---

**Ultimo aggiornamento:** 2025-10-10
**Versione guida:** 1.0.0
