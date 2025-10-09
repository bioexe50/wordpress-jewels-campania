# Shortcodes Disponibili - Prenotazione Aule SSM

## 📅 Calendario Prenotazioni

### Nuovo Calendario Multi-Slot (Consigliato)
```
[prenotazione_aule_ssm_new_calendar aula_id="2"]
```

**Caratteristiche:**
- ✅ Interfaccia stile Calendly moderna
- ✅ Selezione multipla slot con badge
- ✅ Modale per selezione slot giornalieri
- ✅ Form unico per prenotare più slot contemporaneamente
- ✅ Visualizzazione motivo prenotazione per slot occupati
- ✅ Sidebar con riepilogo slot selezionati
- ✅ Responsive design completo

**Parametri:**
- `aula_id` (obbligatorio): ID dell'aula da visualizzare
- `show_legend` (opzionale, default: "true"): Mostra legenda disponibile/selezionato
- `allow_booking` (opzionale, default: "true"): Abilita prenotazioni

**Esempi:**
```
[prenotazione_aule_ssm_new_calendar aula_id="1"]
[prenotazione_aule_ssm_new_calendar aula_id="2" show_legend="false"]
```

**Pagine di esempio:**
- https://raffaelevitulano.com/test-new-calendar/ (Aula 1)
- https://raffaelevitulano.com/test-ssm/ (Aula 2 - da aggiornare)

---

### Calendario Classico (Legacy)
```
[prenotazione_aule_ssm_calendar aula_id="2"]
```

**Caratteristiche:**
- ✅ FullCalendar view
- ✅ Prenotazione singola slot
- ✅ Vista mensile/settimanale/giornaliera

**Parametri:**
- `aula_id` (obbligatorio): ID dell'aula

**Note:** Questo shortcode usa l'interfaccia classica con FullCalendar. Per nuove implementazioni si consiglia `prenotazione_aule_ssm_new_calendar`.

---

## 📋 Altri Shortcodes

### Lista Aule
```
[prenotazione_aule_ssm_list]
```

**Parametri:**
- `stato` (opzionale, default: "attiva"): Filtra per stato (attiva, non_disponibile, manutenzione)
- `ubicazione` (opzionale): Filtra per ubicazione
- `show_details` (opzionale, default: "true"): Mostra dettagli aula
- `show_booking_link` (opzionale, default: "true"): Mostra link prenotazione

**Esempi:**
```
[prenotazione_aule_ssm_list]
[prenotazione_aule_ssm_list stato="attiva" ubicazione="Primo Piano"]
[prenotazione_aule_ssm_list show_booking_link="false"]
```

---

### Form Ricerca Aule
```
[prenotazione_aule_ssm_search]
```

**Parametri:**
- `show_filters` (opzionale, default: "true"): Mostra filtri avanzati
- `redirect_to` (opzionale): URL pagina risultati

**Esempi:**
```
[prenotazione_aule_ssm_search]
[prenotazione_aule_ssm_search show_filters="false"]
```

---

## 🎯 Shortcode Consigliati per Casi d'Uso

### Prenotazione Singola Aula
```
[prenotazione_aule_ssm_new_calendar aula_id="1"]
```
Ideale per: Pagine dedicate a singole aule

---

### Elenco Aule Disponibili
```
[prenotazione_aule_ssm_list stato="attiva" show_booking_link="true"]
```
Ideale per: Homepage, pagine di riepilogo

---

### Sistema Completo con Ricerca
```
[prenotazione_aule_ssm_search]
[prenotazione_aule_ssm_list]
```
Ideale per: Pagina principale sistema prenotazioni

---

## 📊 Database e Configurazione

### Tabelle Database
- `wp_prenotazione_aule_ssm_aule` - Aule
- `wp_prenotazione_aule_ssm_slot_disponibilita` - Slot orari
- `wp_prenotazione_aule_ssm_prenotazioni` - Prenotazioni
- `wp_prenotazione_aule_ssm_attrezzature` - Attrezzature aule

### ID Aule Attuali
- **Aula 1**: ID `1` - Aula Studio A1
- **Aula 2**: ID `2` - (verifica in wp-admin)

**Verifica ID aule:**
```sql
SELECT id, nome_aula, stato FROM wp_prenotazione_aule_ssm_aule;
```

Oppure in wp-admin: **Gestione Aule → Tutte le Aule**

---

## 🔄 Migrazione da Vecchio a Nuovo Sistema

### Da Fare per Aggiornare una Pagina

**Prima (vecchio):**
```
[prenotazione_aule_ssm_calendar aula_id="2"]
```

**Dopo (nuovo):**
```
[prenotazione_aule_ssm_new_calendar aula_id="2"]
```

**Vantaggi migrazione:**
1. ✅ UX moderna stile Calendly
2. ✅ Prenotazione multipla slot
3. ✅ Visualizzazione motivo prenotazioni esistenti
4. ✅ Form più intuitivo
5. ✅ Migliori performance (meno dipendenze)

---

## 🛠️ Versioni Plugin

**Versione corrente:** 1.1.0

**Changelog:**
- v1.1.0 (Oct 2024): Nuovo calendario multi-slot, badge selezionabili, sidebar slot
- v1.0.4 (Oct 2024): Sostituzione emoji con Dashicons, fix icone
- v1.0.0 (Sep 2024): Release iniziale

---

## 📝 Note Sviluppatore

### File Template
- **Nuovo calendario**: `/public/partials/prenotazione-aule-ssm-new-calendar.php`
- **Calendario classico**: `/public/partials/prenotazione-aule-ssm-calendar.php`
- **Lista aule**: `/public/partials/prenotazione-aule-ssm-list.php`

### JavaScript
- **Nuovo calendario**: `/public/js/prenotazione-aule-ssm-new-calendar.js`
- **Multi-slot backend**: `/public/class-prenotazione-aule-ssm-multi-slot.php`

### CSS
- **Base calendario**: `/public/css/aule-booking-new-calendar.css`
- **Multi-slot**: `/public/css/prenotazione-aule-ssm-multi-slot.css`

### AJAX Actions
- `get_slots_for_date` - Carica slot per una data specifica
- `prenotazione_aule_ssm_multi_booking` - Salva prenotazione multipla

---

## 🔒 Sicurezza

Tutti gli shortcode implementano:
- ✅ Nonce verification
- ✅ Sanitizzazione input
- ✅ Prepared statements database
- ✅ Controllo permessi

---

Ultimo aggiornamento: 9 Ottobre 2024
