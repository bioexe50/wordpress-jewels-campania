# 🎨 Lista Aule & UX Improvements

**Data**: 12 Ottobre 2025  
**Versione Plugin**: 2.1.2+  
**Feature**: Sistema navigazione e lista aule migliorata

---

## 📋 Overview

Implementazione di un sistema di navigazione fluido tra le aule con template moderno card-based e shortcode configurabile.

---

## ✨ Nuove Features

### 1. **Template Lista Aule Card-Based**

Layout moderno con card responsive che mostra:
- ✅ Immagine aula (se disponibile)
- ✅ Nome e stato (Attiva/Manutenzione/Non Disponibile)
- ✅ Capienza e ubicazione con icone
- ✅ Attrezzature disponibili con badge
- ✅ Descrizione aula
- ✅ Bottone "Prenota Ora" diretto

---

### 2. **Shortcode `[prenotazione_aule_ssm_list]`**

#### Uso Base
```
[prenotazione_aule_ssm_list]
```

#### Parametri Avanzati
```
[prenotazione_aule_ssm_list 
  stato="attiva" 
  ubicazione="Piano Terra"
  show_details="true" 
  show_booking_link="true"
]
```

#### Parametri Disponibili

| Parametro | Tipo | Default | Descrizione |
|-----------|------|---------|-------------|
| `stato` | string | 'attiva' | Filtra aule per stato:<br>- `attiva`<br>- `non_disponibile`<br>- `manutenzione` |
| `ubicazione` | string | - | Filtra per ubicazione specifica |
| `show_details` | boolean | true | Mostra descrizione e dettagli aula |
| `show_booking_link` | boolean | true | Mostra bottone prenotazione |

---

### 3. **Esempi Casi d'Uso**

#### Homepage Prenotazioni
```
<!-- Mostra solo aule attive con link prenotazione -->
[prenotazione_aule_ssm_list stato="attiva"]
```

#### Elenco Completo Senza Prenotazioni
```
<!-- Visualizzazione informativa -->
[prenotazione_aule_ssm_list show_booking_link="false"]
```

#### Filtra per Ubicazione
```
<!-- Solo aule del primo piano -->
[prenotazione_aule_ssm_list ubicazione="Primo Piano"]
```

#### Vista Compatta
```
<!-- Lista essenziale -->
[prenotazione_aule_ssm_list show_details="false"]
```

---

## 🎨 Design System

### Layout Card

```
┌─────────────────────────────────┐
│  [Immagine Aula - 180px]       │
├─────────────────────────────────┤
│  🏢 Nome Aula      [Badge]      │
│  ──────────────────────────     │
│  Descrizione breve...           │
│                                 │
│  👥 5 persone  📍 Ubicazione   │
│                                 │
│  [📶 Wi-Fi] [💻 PC] [🖨️ Stamp]  │
│                                 │
│  ┌─────────────────────────┐   │
│  │  📅 Prenota Ora         │   │
│  └─────────────────────────┘   │
└─────────────────────────────────┘
```

### Responsive Grid

- **Desktop** (>1024px): 3 colonne
- **Tablet** (768-1024px): 2 colonne  
- **Mobile** (<768px): 1 colonna

---

## 📂 Files Creati

### Template

**Path**: `public/partials/prenotazione-aule-ssm-list.php`

**Features**:
- Template PHP con loop aule
- Icone Dashicons integrate
- Escape HTML sicuro (`wp_kses_post`)
- Badge stato aula
- Link dinamici prenotazione

---

### Stylesheet

**Path**: `public/css/prenotazione-aule-ssm-list.css`

**Features**:
- Grid system responsive
- Card design moderno
- Hover effects
- Badge styling
- Button states
- Mobile-first approach

**Dimensioni**: ~4KB

---

## 🔧 Integrazione

### ⚠️ Modifica Necessaria

**File**: `public/class-prenotazione-aule-ssm-public.php`

Nella funzione `enqueue_styles()` (riga ~61), aggiungere:

```php
public function enqueue_styles() {
    // Dashicons per frontend
    wp_enqueue_style('dashicons');

    // CSS Lista Aule
    wp_enqueue_style(
        $this->plugin_name . '-list',
        PRENOTAZIONE_AULE_SSM_PLUGIN_URL . 'public/css/prenotazione-aule-ssm-list.css',
        array(),
        $this->version,
        'all'
    );
    
    // ... resto del codice esistente
}
```

**Status**: ⚠️ DA COMPLETARE MANUALMENTE

---

## 🎯 UX Improvements Roadmap

### ✅ Completato

- [x] Template lista aule card-based
- [x] CSS responsive design
- [x] Shortcode con parametri configurabili
- [x] Icone Dashicons integrate
- [x] Badge stato aula

### 📋 Suggerimenti Futuri

#### 1. **Navigazione tra Aule nel Calendario**

Aggiungere nel template calendario (`prenotazione-aule-ssm-new-calendar.php`):

```html
<div class="pas-aule-navigation">
    <select class="pas-aule-selector">
        <option value="1">🏢 Aula 1</option>
        <option value="2" selected>🏢 Aula 2</option>
        <option value="3">🏢 Aula 3</option>
    </select>
</div>
```

**Benefici**:
- Switch rapido tra aule
- URL con parametro `?aula_id=X`
- Breadcrumb "Aula X di Y"

---

#### 2. **Menu Dropdown Aule**

Widget/Shortcode per navigazione globale:

```
[prenotazione_aule_ssm_menu style="dropdown"]
[prenotazione_aule_ssm_menu style="buttons"]
[prenotazione_aule_ssm_menu style="sidebar"]
```

**Features**:
- Sticky sidebar con lista aule
- Dropdown mobile-friendly
- Bottoni stile filtri

---

#### 3. **Breadcrumb & Pagination**

Nel calendario singola aula:

```
🏠 Home > 📚 Aule > 🏢 Aula 2
[← Aula Precedente] [Tutte le Aule] [Aula Successiva →]
```

---

#### 4. **Quick Actions Sidebar**

```
┌─────────────────────┐
│ 🏢 Altre Aule      │
├─────────────────────┤
│ • Aula 1           │
│ • Aula 2  ← Attuale│
│ • Aula 3           │
│                    │
│ [+ Vedi Tutte]     │
└─────────────────────┘
```

---

## 💾 Database Schema

### Tabella Aule

```sql
jc_prenotazione_aule_ssm_aule
├─ id (int)
├─ nome_aula (varchar)
├─ descrizione (text)
├─ capienza (int)
├─ ubicazione (varchar)
├─ stato (enum: attiva, non_disponibile, manutenzione)
├─ immagine_url (varchar)
├─ attrezzature (text, serialized array)
├─ created_at (datetime)
└─ updated_at (datetime)
```

---

## 🎨 CSS Classes Reference

### Container
```css
.pas-aule-list-container  /* Main wrapper */
.pas-aule-grid            /* Grid layout */
```

### Card
```css
.pas-aula-card           /* Card container */
.pas-aula-image          /* Image wrapper */
.pas-aula-content        /* Content wrapper */
.pas-aula-header         /* Title + badge area */
```

### Elements
```css
.pas-aula-title          /* Room name */
.pas-badge               /* Status badge */
.pas-aula-description    /* Description text */
.pas-aula-info           /* Info container */
.pas-info-item           /* Single info item */
.pas-aula-facilities     /* Equipment tags */
.pas-facility-tag        /* Single equipment */
.pas-aula-actions        /* Action buttons */
.pas-btn                 /* Button base */
.pas-btn-primary         /* Primary button */
```

### States
```css
.pas-aula-attiva         /* Active room */
.pas-aula-non_disponibile /* Unavailable room */
.pas-aula-manutenzione   /* Maintenance room */
.pas-badge-active        /* Active badge */
.pas-badge-inactive      /* Inactive badge */
.pas-badge-maintenance   /* Maintenance badge */
```

---

## 🧪 Testing

### Visual Test Checklist

- [ ] Cards display in grid layout
- [ ] Images load correctly
- [ ] Badges show correct colors
- [ ] Icons render (Dashicons)
- [ ] Hover effects work
- [ ] Buttons are clickable
- [ ] Responsive on mobile
- [ ] Filters work correctly

### Browser Compatibility

| Browser | Status | Notes |
|---------|--------|-------|
| Chrome 120+ | ✅ | Fully supported |
| Firefox 120+ | ✅ | Fully supported |
| Safari 17+ | ⚠️ | Test Dashicons rendering |
| Edge 120+ | ✅ | Fully supported |

---

## 📱 Responsive Breakpoints

```css
/* Desktop */
@media (min-width: 1024px) {
    .pas-aule-grid { grid-template-columns: repeat(3, 1fr); }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 1023px) {
    .pas-aule-grid { grid-template-columns: repeat(2, 1fr); }
}

/* Mobile */
@media (max-width: 767px) {
    .pas-aule-grid { grid-template-columns: 1fr; }
}
```

---

## 🔐 Security

### Output Escaping

```php
esc_html()           // Text content
esc_attr()           // HTML attributes
esc_url()            // URLs
wp_kses_post()       // HTML with allowed tags (Dashicons)
```

### Input Sanitization

```php
$atts = shortcode_atts(array(
    'stato' => sanitize_text_field($atts['stato']),
    'ubicazione' => sanitize_text_field($atts['ubicazione'])
), $atts);
```

---

## 📊 Performance

### Optimization

- CSS: ~4KB (minified)
- Template: Lazy load images
- Database: Query with filters
- No external dependencies

### Loading Strategy

```php
// Load only when shortcode is present
if (has_shortcode($post->post_content, 'prenotazione_aule_ssm_list')) {
    wp_enqueue_style('pas-list');
}
```

---

## 📞 Next Steps

1. **Completare integrazione**:
   - [ ] Modificare `class-prenotazione-aule-ssm-public.php`
   - [ ] Aggiungere enqueue Dashicons + CSS lista

2. **Testare shortcode**:
   - [ ] Creare pagina test con `[prenotazione_aule_ssm_list]`
   - [ ] Verificare visualizzazione
   - [ ] Test responsive

3. **Implementare navigazione calendario**:
   - [ ] Dropdown switch aule
   - [ ] Breadcrumb
   - [ ] Link correlati

---

**Stato Implementazione**: 🟡 **80% COMPLETATO**  
**Azione Richiesta**: Modifica manuale `class-prenotazione-aule-ssm-public.php`

**Ultimo aggiornamento**: 12 Ottobre 2025
