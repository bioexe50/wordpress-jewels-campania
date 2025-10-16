# 📧 Placeholder Gestione Appuntamenti & Navigazione Aule

**Data**: 12 Ottobre 2025  
**Plugin**: Prenotazione Aule SSM v2.1.3

---

## 📋 PLACEHOLDER EMAIL COMPLETI

### 🧑 Dati Richiedente

```
{nome_richiedente}        → Mario
{cognome_richiedente}     → Rossi  
{email_richiedente}       → mario.rossi@esempio.it (solo email admin)
```

### 🏢 Dati Aula

```
{nome_aula}               → Aula Magna
{ubicazione}              → Edificio A - Piano 2
{capienza}                → 50 persone
{attrezzature}            → Proiettore, Wi-Fi, Lavagna
```

### 📅 Dati Prenotazione (Singola)

```
{data_prenotazione}       → Lunedì, 10 ottobre 2025
{ora_inizio}              → 09:00
{ora_fine}                → 11:00
{motivo}                  → Lezione Anatomia
{stato_prenotazione}      → Confermata / In Attesa / Rifiutata
{codice_prenotazione}     → BK2025100123
{note_admin}              → Ricordarsi badge accesso
{data_creazione}          → 08/10/2025 15:30
```

### 📅 Dati Prenotazione Multi-Slot

```
{numero_slot}             → 3
{gruppo_prenotazione}     → uuid-gruppo-12345
{elenco_slot}             → Lista formattata di tutti gli slot:
                            • 10 ottobre 2025, 09:00-11:00
                            • 10 ottobre 2025, 14:00-16:00  
                            • 11 ottobre 2025, 09:00-11:00
```

### 🌐 Dati Sistema

```
{sito_nome}               → Università SSM
{sito_url}                → https://example.com
{link_gestione}           → https://example.com/wp-admin/... (solo admin)
{link_cancella}           → Link diretto cancellazione (se abilitato)
{link_calendario}         → URL calendario pubblico aula
```

### 👤 Dati Amministratore (solo email admin)

```
{admin_email}             → admin@ssm.it
{ip_richiedente}          → 192.168.1.100
{user_agent}              → Mozilla/5.0...
```

---

## 🎨 TEMPLATE EMAIL APPUNTAMENTI

### Email Conferma Prenotazione

```html
Gentile {nome_richiedente} {cognome_richiedente},

La tua prenotazione è stata <strong>confermata</strong>!

<div class="booking-details">
<strong>📋 Dettagli Prenotazione:</strong><br><br>
🏢 Aula: {nome_aula}<br>
📍 Ubicazione: {ubicazione}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
📝 Motivo: {motivo}<br>
🔑 Codice: {codice_prenotazione}
</div>

<strong>Note importanti:</strong>
{note_admin}

<a href="{link_calendario}" class="button">Visualizza Calendario</a>

Cordiali saluti,<br>
Team {sito_nome}
```

---

### Email Prenotazione Multi-Slot

```html
Gentile {nome_richiedente},

Hai prenotato con successo <strong>{numero_slot} slot</strong>:

<div class="booking-details">
<strong>📅 Slot Prenotati:</strong><br>
{elenco_slot}
<br><br>
🏢 Aula: {nome_aula}<br>
📍 Ubicazione: {ubicazione}<br>
🔑 Codice Gruppo: {gruppo_prenotazione}
</div>

<strong>Tutti gli slot sono stati confermati!</strong>

<a href="{link_gestione}" class="button">Gestisci Prenotazioni</a>
```

---

### Email Rifiuto Prenotazione

```html
Gentile {nome_richiedente},

Ci dispiace informarti che la tua prenotazione è stata <strong>rifiutata</strong>.

<div class="booking-details">
📅 Data richiesta: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
🏢 Aula: {nome_aula}
</div>

<strong>Motivo rifiuto:</strong><br>
{note_admin}

Puoi verificare la disponibilità di altre fasce orarie:

<a href="{link_calendario}" class="button">Vedi Disponibilità</a>
```

---

### Email Reminder Appuntamento (24h prima)

```html
🔔 <strong>Promemoria Prenotazione</strong>

Gentile {nome_richiedente},

Ti ricordiamo che domani hai prenotato:

<div class="booking-details">
📅 {data_prenotazione}<br>
🕒 {ora_inizio} - {ora_fine}<br>
🏢 {nome_aula}<br>
📍 {ubicazione}
</div>

Assicurati di:
• Arrivare 10 minuti prima
• Portare badge identificativo
• Lasciare l'aula in ordine

<a href="{link_calendario}" class="button">Dettagli Completi</a>
```

---

## 🧭 NAVIGAZIONE TRA AULE ATTIVE

### 1. Shortcode Menu Aule (Dropdown)

**Nuovo shortcode da implementare**:

```php
[prenotazione_aule_ssm_menu style="dropdown" current_aula="2"]
```

**Rendering**:
```html
<div class="pas-aule-menu">
    <label>Seleziona Aula:</label>
    <select onchange="window.location.href=this.value">
        <option value="?aula_id=1">🏢 Aula 1</option>
        <option value="?aula_id=2" selected>🏢 Aula 2</option>
        <option value="?aula_id=3">🏢 Aula 3</option>
    </select>
</div>
```

---

### 2. Navigazione Breadcrumb

**Da aggiungere nel template calendario**:

```php
// In prenotazione-aule-ssm-new-calendar.php
<div class="pas-breadcrumb">
    <a href="{homepage}">🏠 Home</a> &raquo; 
    <a href="{lista_aule}">📚 Aule</a> &raquo; 
    <span>🏢 {nome_aula_corrente}</span>
</div>

<div class="pas-aule-navigation">
    <?php if ($prev_aula): ?>
    <a href="?aula_id={$prev_aula->id}" class="pas-nav-btn pas-prev">
        ← {$prev_aula->nome_aula}
    </a>
    <?php endif; ?>
    
    <span class="pas-nav-info">
        Aula {$current_position} di {$total_aule}
    </span>
    
    <?php if ($next_aula): ?>
    <a href="?aula_id={$next_aula->id}" class="pas-nav-btn pas-next">
        {$next_aula->nome_aula} →
    </a>
    <?php endif; ?>
</div>
```

---

### 3. Sidebar "Altre Aule"

**Widget da aggiungere**:

```html
<div class="pas-sidebar-aule">
    <h3>🏢 Altre Aule</h3>
    <ul class="pas-aule-list">
        <li>
            <a href="?aula_id=1">
                <span class="aula-name">Aula 1</span>
                <span class="aula-status active">Attiva</span>
            </a>
        </li>
        <li class="current">
            <a href="?aula_id=2">
                <span class="aula-name">Aula 2</span>
                <span class="aula-status active">Attiva</span>
            </a>
        </li>
        <li>
            <a href="?aula_id=3">
                <span class="aula-name">Aula 3</span>
                <span class="aula-status maintenance">Manutenzione</span>
            </a>
        </li>
    </ul>
    
    <a href="{tutte_aule}" class="pas-view-all">
        Vedi Tutte le Aule →
    </a>
</div>
```

---

### 4. Quick Switch Buttons

**Nel header del calendario**:

```html
<div class="pas-quick-switch">
    <button class="pas-switch-btn" data-aula="1">Aula 1</button>
    <button class="pas-switch-btn active" data-aula="2">Aula 2</button>
    <button class="pas-switch-btn" data-aula="3">Aula 3</button>
</div>

<script>
document.querySelectorAll('.pas-switch-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const aulaId = this.dataset.aula;
        window.location.href = '?aula_id=' + aulaId;
    });
});
</script>
```

---

## 📊 PLACEHOLDER STATISTICHE (per email admin)

### Report Mensile Admin

```
{totale_prenotazioni_mese}     → 156
{prenotazioni_confermate}      → 142
{prenotazioni_rifiutate}       → 8
{prenotazioni_in_attesa}       → 6
{aula_piu_usata}               → Aula Magna (89 prenotazioni)
{percentuale_occupazione}      → 78%
{orario_piu_richiesto}         → 09:00-11:00 (45% delle prenotazioni)
```

### Template Email Report Admin

```html
<strong>📊 Report Mensile - {mese_corrente}</strong>

<div class="booking-details">
📅 Totale Prenotazioni: {totale_prenotazioni_mese}<br>
✅ Confermate: {prenotazioni_confermate}<br>
❌ Rifiutate: {prenotazioni_rifiutate}<br>
⏳ In Attesa: {prenotazioni_in_attesa}<br><br>

🏆 Aula più usata: {aula_piu_usata}<br>
📈 Occupazione media: {percentuale_occupazione}<br>
🕒 Fascia più richiesta: {orario_piu_richiesto}
</div>

<a href="{link_gestione}" class="button">Vedi Report Completo</a>
```

---

## 🔧 IMPLEMENTAZIONE CODICE

### Funzione Get Aule Attive

```php
/**
 * Get lista aule attive per navigazione
 */
function get_aule_attive_navigation() {
    global $wpdb;
    $table = $wpdb->prefix . 'prenotazione_aule_ssm_aule';
    
    return $wpdb->get_results(
        "SELECT id, nome_aula, stato 
         FROM {$table} 
         WHERE stato = 'attiva'
         ORDER BY nome_aula ASC"
    );
}

/**
 * Get aula precedente e successiva
 */
function get_aula_prev_next($current_aula_id) {
    $aule = get_aule_attive_navigation();
    $total = count($aule);
    
    $current_index = array_search($current_aula_id, array_column($aule, 'id'));
    
    return [
        'prev' => $current_index > 0 ? $aule[$current_index - 1] : null,
        'next' => $current_index < $total - 1 ? $aule[$current_index + 1] : null,
        'position' => $current_index + 1,
        'total' => $total
    ];
}
```

---

### CSS per Navigazione

```css
/* Menu Dropdown */
.pas-aule-menu {
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.pas-aule-menu select {
    padding: 10px;
    font-size: 16px;
    border: 2px solid #0073aa;
    border-radius: 4px;
    width: 100%;
    max-width: 300px;
}

/* Breadcrumb */
.pas-breadcrumb {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}

.pas-breadcrumb a {
    color: #0073aa;
    text-decoration: none;
}

.pas-breadcrumb a:hover {
    text-decoration: underline;
}

/* Navigazione Prev/Next */
.pas-aule-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.pas-nav-btn {
    padding: 10px 20px;
    background: #0073aa;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s;
}

.pas-nav-btn:hover {
    background: #005a87;
    color: white;
}

.pas-nav-info {
    font-weight: 600;
    color: #666;
}

/* Sidebar Aule */
.pas-sidebar-aule {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
}

.pas-sidebar-aule h3 {
    margin-top: 0;
    border-bottom: 2px solid #0073aa;
    padding-bottom: 10px;
}

.pas-aule-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pas-aule-list li {
    border-bottom: 1px solid #f0f0f0;
}

.pas-aule-list li:last-child {
    border-bottom: none;
}

.pas-aule-list a {
    display: flex;
    justify-content: space-between;
    padding: 12px;
    text-decoration: none;
    color: #333;
    transition: background 0.2s;
}

.pas-aule-list a:hover {
    background: #f8f9fa;
}

.pas-aule-list li.current a {
    background: #e8f4fd;
    font-weight: 600;
}

.aula-status {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 4px;
}

.aula-status.active {
    background: #d1fae5;
    color: #065f46;
}

.aula-status.maintenance {
    background: #fed7aa;
    color: #9a3412;
}

/* Quick Switch Buttons */
.pas-quick-switch {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.pas-switch-btn {
    padding: 8px 16px;
    border: 2px solid #0073aa;
    background: white;
    color: #0073aa;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.pas-switch-btn:hover {
    background: #0073aa;
    color: white;
}

.pas-switch-btn.active {
    background: #0073aa;
    color: white;
    font-weight: 600;
}
```

---

## 📱 RESPONSIVE

```css
@media (max-width: 768px) {
    .pas-aule-navigation {
        flex-direction: column;
        gap: 10px;
    }
    
    .pas-quick-switch {
        flex-wrap: wrap;
    }
    
    .pas-sidebar-aule {
        margin-top: 20px;
    }
}
```

---

## 🎯 ESEMPI USO COMPLETO

### Pagina Calendario con Navigazione

```
<!-- Breadcrumb -->
Home > Aule > Aula 2

<!-- Menu Dropdown -->
[Seleziona Aula: ▼ Aula 2]

<!-- Calendario -->
[prenotazione_aule_ssm_new_calendar aula_id="2"]

<!-- Navigazione -->
[← Aula 1] [Aula 2 di 5] [Aula 3 →]

<!-- Sidebar (opzionale) -->
Altre Aule:
• Aula 1 (Attiva)
• Aula 2 (Attiva) ← Stai qui
• Aula 3 (Manutenzione)
```

---

**Status**: 📋 Guida Completa  
**Ultimo aggiornamento**: 12 Ottobre 2025  
**Versione Plugin**: 2.1.3
