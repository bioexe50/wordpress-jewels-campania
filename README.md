# Mappa Aziende Campania - WordPress Plugin

**Version:** 1.3.0
**Author:** Gabriele Bernini
**License:** GPL-2.0+
**Requires at least:** WordPress 5.8
**Tested up to:** WordPress 6.8
**Requires PHP:** 7.4+

## 📖 Descrizione

Plugin WordPress professionale per visualizzare aziende su una mappa interattiva con funzionalità avanzate di filtraggio, ricerca e geocodifica automatica.

### ✨ Caratteristiche Principali

- 🗺️ **Mappa Interattiva** - Basata su Leaflet.js e OpenStreetMap
- 📊 **Upload CSV** - Importazione massiva aziende via file CSV
- 🎯 **Geocodifica Automatica** - Integrazione con Nominatim (OpenStreetMap)
- 🔍 **Filtri Avanzati** - Filtra per provincia, città, settore, prodotto
- 🔎 **Ricerca Full-Text** - Cerca aziende per nome, indirizzo o prodotto
- 📍 **Marker Clustering** - Raggruppamento automatico marker vicini
- 🎨 **Icone Personalizzate** - Icone diverse per settore aziendale
- 📱 **Responsive** - Ottimizzato per mobile e tablet
- ♿ **Accessibile** - Compatibile WCAG 2.1

## 🚀 Installazione

### Metodo 1: Upload ZIP da WordPress Admin

1. Scarica il file `campania-companies-map-v1.3.0.zip`
2. Vai su **Plugin → Aggiungi nuovo** nel tuo WordPress
3. Click su **Carica plugin**
4. Seleziona il file ZIP
5. Click **Installa ora**
6. **Attiva** il plugin

### Metodo 2: FTP/SFTP

1. Estrai il file ZIP
2. Carica la cartella `campania-companies-map` in `/wp-content/plugins/`
3. Vai su **Plugin** nel WordPress admin
4. **Attiva** "Mappa Aziende Campania"

## 📝 Utilizzo

### 1. Configurazione Iniziale

Dopo l'attivazione, vai su **Mappa Campania** nel menu admin.

#### Tab "Impostazioni Mappa"
- **URL Tile Mappa**: Default OpenStreetMap (modificabile)
- **Attribuzione**: Copyright mappa (modificabile)

#### Tab "Settori"
- Aggiungi settori aziendali con icone personalizzate
- Imposta un'icona default per aziende senza settore

### 2. Carica Aziende

#### Tab "Carica Aziende"

**Formato CSV richiesto:**
```csv
Ragione Sociale,Email,PEC,Sitoweb,Indirizzo,Città,Provincia,Telefono,Partita IVA,Prodotto,Settore
Azienda Esempio,info@esempio.it,pec@esempio.it,https://esempio.it,Via Roma 1,Napoli,NA,081123456,12345678901,Prodotto A,Edilizia
```

**Ordine colonne (obbligatorio):**
1. Ragione Sociale (obbligatorio)
2. Email
3. PEC
4. Sitoweb
5. Indirizzo (obbligatorio)
6. Città (obbligatoria)
7. Provincia (obbligatoria)
8. Telefono
9. Partita IVA (usata come chiave unica)
10. Prodotto
11. Settore

**Note:**
- Il separatore può essere `,` (virgola) o `;` (punto e virgola)
- La prima riga (header) viene ignorata
- Campi vuoti sono permessi
- Se Partita IVA è presente, l'azienda viene aggiornata (non duplicata)

### 3. Geocodifica

Dopo aver caricato il CSV:

1. Click su **"Geocodifica Aziende Non Mappate"**
2. Il processo utilizza Nominatim (OpenStreetMap)
3. Rispetta automaticamente i rate limits (1 richiesta/secondo)
4. Visualizza progresso in tempo reale

**Nota**: La geocodifica può richiedere tempo per dataset grandi.

### 4. Visualizza Mappa

Usa lo shortcode nella pagina/post:

```
[campania_companies_map]
```

#### Funzionalità Mappa Frontale:

- **Filtro Provincia**: Mostra solo aziende della provincia selezionata
- **Filtro Città**: Mostra solo aziende della città selezionata
- **Filtro Settore**: Filtra per settore aziendale
- **Filtro Prodotto**: Filtra per prodotto
- **Campo Ricerca**: Cerca per nome, indirizzo o prodotto
- **Pulsante Reset**: Ripristina tutti i filtri

**Interazione Marker:**
- Click sul marker → popup con dettagli azienda
- Marker raggruppati automaticamente (clustering)
- Zoom automatico sui risultati filtrati

## 🛠️ Requisiti Tecnici

- **WordPress**: 5.8 o superiore
- **PHP**: 7.4 o superiore
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **JavaScript**: Attivo nel browser
- **Librerie Incluse**:
  - Leaflet.js 1.9.4
  - Leaflet.markercluster 1.5.3

## 🔧 Configurazione Avanzata

### Personalizzazione CSS

Il plugin genera CSS in `/wp-content/uploads/campania-companies-map/assets/`:
- `frontend-style.css` - Stili frontale
- `admin-style.css` - Stili admin

Puoi sovrascriverli nel tuo tema.

### Hook WordPress Disponibili

```php
// Modifica opzioni mappa prima del rendering
add_filter('campania_companies_map_options', function($options) {
    $options['tile_url'] = 'https://custom-tiles.com/{z}/{x}/{y}.png';
    return $options;
});
```

### Database

Il plugin crea la tabella `wp_campania_companies_map` con:
- Informazioni aziendali
- Coordinate geocodificate
- Indici ottimizzati per ricerca

**IMPORTANTE**: La Partita IVA è UNIQUE KEY - se carichi lo stesso CSV più volte, le aziende vengono aggiornate (non duplicate).

## 🐛 Troubleshooting

### Mappa non si visualizza

1. Verifica che lo shortcode sia corretto: `[campania_companies_map]`
2. Controlla console browser (F12) per errori JavaScript
3. Assicurati che jQuery sia caricato
4. Disattiva altri plugin per verificare conflitti

### Filtri non funzionano

1. Svuota cache browser (Ctrl+Shift+R)
2. Svuota cache WordPress
3. Verifica che le aziende siano geocodificate
4. Controlla log PHP per errori AJAX

### Geocodifica fallisce

1. Verifica indirizzi nel CSV (devono essere completi)
2. Nominatim ha rate limit (1 req/sec) - normale per dataset grandi
3. Alcuni indirizzi potrebbero non essere trovati → geocoded=2 (fallito)
4. Puoi ritentare la geocodifica in qualsiasi momento

### Aziende duplicate

- Se Partita IVA è vuota/NULL, possono esserci duplicati
- Soluzione: Aggiungi Partita IVA univoca nel CSV

## 📊 Performance

- **Dataset testato**: 10,000+ aziende
- **Caricamento mappa**: < 1 secondo
- **Filtri**: Risposta immediata (AJAX)
- **Geocodifica**: ~3600 aziende/ora (rate limit Nominatim)

## 🔐 Sicurezza

- ✅ Nonce verification su tutte le richieste AJAX
- ✅ Sanitizzazione input
- ✅ Escape output
- ✅ Prepared statements SQL
- ✅ Capability check (`manage_options`)
- ✅ UNIQUE constraint su Partita IVA

## 📋 Changelog

### Version 1.3.0 (2025-10-16)
- 🐛 **FIX**: Risolto bug `dbg is not defined` in console JavaScript
- 🐛 **FIX**: Corretta funzione `iconForSector()` (era chiamata `getIcon()`)
- 🐛 **FIX**: Filtri ora funzionano correttamente all'apertura pagina
- 🐛 **FIX**: Rimosso doppio filtro client-side ridondante
- ⚡ **MIGLIORAMENTO**: Timing caricamento mappa ottimizzato (500ms + 300ms)
- ⚡ **MIGLIORAMENTO**: Pulizia filtri "Tutte le..." ignorati
- 📝 **AGGIORNATO**: Header plugin con requisiti WordPress/PHP
- ✨ **NUOVO**: Logging avanzato per debug (console.log)
- ✨ **NUOVO**: Pulsante debug con parametro `?debug_mappa`

### Version 1.2.9
- Prima versione stabile con funzionalità base

## 🤝 Supporto

Per bug, richieste o contributi:
- **Email**: [support@speaktoai.it](mailto:support@speaktoai.it)
- **GitHub**: [https://github.com/bioexe50/campania-companies-map](https://github.com/bioexe50/campania-companies-map)

## 📄 Licenza

Questo plugin è rilasciato sotto licenza **GPL-2.0+**.

Copyright © 2025 Gabriele Bernini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

## 👨‍💻 Autore

**Gabriele Bernini**
Website: [https://speaktoai.it](https://speaktoai.it)

---

Made with ❤️ in Italy
