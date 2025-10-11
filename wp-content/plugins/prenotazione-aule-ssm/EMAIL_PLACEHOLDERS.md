# 📧 Guida Completa Placeholder Email - Plugin Prenotazione Aule SSM

## 📋 Riferimento Rapido Placeholder

### Dati Richiedente

| Placeholder | Descrizione | Esempio Output |
|------------|-------------|----------------|
| `{nome_richiedente}` | Nome del richiedente | Mario |
| `{cognome_richiedente}` | Cognome del richiedente | Rossi |
| `{email_richiedente}` | Email richiedente (solo admin) | mario.rossi@esempio.it |

### Dati Aula

| Placeholder | Descrizione | Esempio Output |
|------------|-------------|----------------|
| `{nome_aula}` | Nome dell'aula | Aula Magna |
| `{ubicazione}` | Ubicazione fisica | Edificio A - Piano 2 |

### Dati Prenotazione

| Placeholder | Descrizione | Esempio Output |
|------------|-------------|----------------|
| `{data_prenotazione}` | Data formattata italiano | Lunedì, 10 ottobre 2025 |
| `{ora_inizio}` | Ora inizio (HH:mm) | 09:00 |
| `{ora_fine}` | Ora fine (HH:mm) | 11:00 |
| `{motivo}` | Motivo prenotazione | Lezione Anatomia |
| `{stato_prenotazione}` | Stato attuale | Confermata |
| `{codice_prenotazione}` | Codice univoco | BK2025100123 |
| `{note_admin}` | Note amministratore | Ricordarsi badge accesso |

### Dati Sistema

| Placeholder | Descrizione | Esempio Output |
|------------|-------------|----------------|
| `{link_gestione}` | Link gestione (solo admin) | https://example.com/wp-admin/... |
| `{sito_nome}` | Nome sito WordPress | Università SSM |
| `{sito_url}` | URL homepage | https://example.com |

---

## 🎨 Formattazione HTML Disponibile

### Classi CSS Predefinite

#### `.booking-details`
Box grigio con bordo blu per evidenziare informazioni importanti.

**Stile applicato:**
- Background: grigio chiaro (#f8f9fa)
- Bordo sinistro: 4px blu WordPress (#2271b1)
- Padding: 15px
- Margin: 20px verticale

**Esempio:**
```html
<div class="booking-details">
<strong>Dettagli Prenotazione:</strong><br>
📍 Aula: {nome_aula}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}
</div>
```

#### `.button`
Pulsante blu WordPress per link cliccabili.

**Stile applicato:**
- Background: blu WordPress (#2271b1)
- Colore testo: bianco
- Padding: 12px 24px
- Border-radius: 4px
- Display: inline-block

**Esempio:**
```html
<a href="{link_gestione}" class="button">Gestisci Prenotazione</a>
```

### Tag HTML Supportati

| Tag | Utilizzo | Esempio |
|-----|----------|---------|
| `<strong>` | Testo in grassetto | `<strong>Importante:</strong>` |
| `<br>` | A capo | `Nome: {nome_richiedente}<br>` |
| `<div>` | Contenitore blocco | `<div class="booking-details">...</div>` |
| `<a>` | Link cliccabile | `<a href="{sito_url}">Visita il sito</a>` |
| `<p>` | Paragrafo | `<p>Testo paragrafo</p>` |

---

## 🎨 Template Wrapper Automatico

Ogni email viene automaticamente incapsulata in un template HTML professionale:

```html
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Stili automatici applicati */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #2271b1;  /* Blu WordPress */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 30px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>[Titolo Email]</h1>
        </div>
        <div class="email-body">
            <!-- IL TUO TEMPLATE VA QUI -->
        </div>
        <div class="email-footer">
            <p>Email inviata da {sito_nome}<br>
            <a href="{sito_url}">{sito_url}</a></p>
        </div>
    </div>
</body>
</html>
```

---

## 📝 Template Completi di Esempio

### 1. Email Conferma Prenotazione

```html
Gentile {nome_richiedente} {cognome_richiedente},

La sua prenotazione è stata <strong>confermata con successo!</strong> ✅

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
• Presentarsi puntualmente all'orario indicato<br>
• Portare un documento d'identità valido<br>
• In caso di ritardo superiore a 15 minuti, la prenotazione potrebbe essere annullata

{note_admin}

Per qualsiasi necessità, contattare l'amministrazione.

Cordiali saluti,<br>
<strong>Il team di {sito_nome}</strong><br>
🌐 <a href="{sito_url}">{sito_url}</a>
```

### 2. Email Rifiuto Prenotazione

```html
Gentile {nome_richiedente} {cognome_richiedente},

Siamo spiacenti di comunicarle che la sua richiesta di prenotazione è stata <strong>rifiutata</strong>. ❌

<div class="booking-details">
<strong>📋 Dettagli Richiesta:</strong><br><br>
📍 <strong>Aula:</strong> {nome_aula}<br>
📅 <strong>Data:</strong> {data_prenotazione}<br>
🕒 <strong>Orario:</strong> {ora_inizio} - {ora_fine}
</div>

<strong>📝 Motivo del rifiuto:</strong><br>
{note_admin}

<strong>💡 Cosa fare ora:</strong><br>
• Verificare la disponibilità di altre aule<br>
• Selezionare una fascia oraria alternativa<br>
• Contattare l'amministrazione per chiarimenti

La invitiamo a effettuare una nuova prenotazione con parametri diversi.

Grazie per la comprensione.

Cordiali saluti,<br>
<strong>Il team di {sito_nome}</strong><br>
🌐 <a href="{sito_url}">Torna al calendario</a>
```

### 3. Email Notifica Admin

```html
<strong>🔔 Nuova Prenotazione Ricevuta</strong>

È stata ricevuta una nuova richiesta di prenotazione che richiede approvazione.

<div class="booking-details">
<strong>👤 Dati Richiedente:</strong><br>
• Nome: {nome_richiedente} {cognome_richiedente}<br>
• Email: {email_richiedente}<br><br>

<strong>📋 Dettagli Prenotazione:</strong><br>
📍 Aula: {nome_aula}<br>
📍 Ubicazione: {ubicazione}<br>
📅 Data: {data_prenotazione}<br>
🕒 Orario: {ora_inizio} - {ora_fine}<br>
📝 Motivo: {motivo}<br>
🔖 Codice: {codice_prenotazione}
</div>

<a href="{link_gestione}" class="button">Gestisci Prenotazione Ora</a>

<strong>⏰ Azioni da compiere:</strong><br>
1. Verificare disponibilità aula<br>
2. Controllare correttezza dati richiedente<br>
3. Approvare o rifiutare la prenotazione<br>
4. Aggiungere eventuali note per il richiedente

---

Questa è un'email automatica dal sistema di gestione prenotazioni.
```

### 4. Email Promemoria (Reminder)

```html
Gentile {nome_richiedente} {cognome_richiedente},

📅 <strong>Promemoria Prenotazione per Domani</strong>

Le ricordiamo che ha una prenotazione confermata per <strong>domani</strong>.

<div class="booking-details">
<strong>📋 Riepilogo:</strong><br><br>
📍 <strong>Aula:</strong> {nome_aula}<br>
📍 <strong>Ubicazione:</strong> {ubicazione}<br>
📅 <strong>Data:</strong> {data_prenotazione}<br>
🕒 <strong>Orario:</strong> {ora_inizio} - {ora_fine}<br>
🔖 <strong>Codice:</strong> {codice_prenotazione}
</div>

<strong>✅ Checklist Pre-Arrivo:</strong><br>
• Verificare di avere il documento d'identità<br>
• Controllare la corretta ubicazione dell'aula<br>
• Presentarsi 5 minuti prima dell'orario<br>
• Portare il codice prenotazione (screenshot o stampa)

In caso di imprevisti, contattare urgentemente l'amministrazione.

A domani!<br>
<strong>Il team di {sito_nome}</strong>
```

---

## 🎯 Best Practices

### ✅ Da Fare

1. **Usare emoji con moderazione** - Rendono l'email più leggibile ma non esagerare
2. **Formattare informazioni importanti** - Usare `<strong>` per evidenziare
3. **Raggruppare dati simili** - Usare `.booking-details` per blocchi informativi
4. **Includere call-to-action chiare** - Link con classe `.button`
5. **Personalizzare il tono** - Formale per università, cordiale per altre situazioni
6. **Testare con "Anteprima Email"** - Verificare sempre prima di salvare

### ❌ Da Evitare

1. **JavaScript inline** - Viene rimosso per sicurezza
2. **CSS inline complessi** - Usare solo le classi predefinite
3. **Immagini remote** - Potrebbero non visualizzarsi
4. **Troppi colori** - Mantenere lo schema WordPress
5. **Testo tutto maiuscolo** - Difficile da leggere
6. **Link senza descrizione** - Sempre usare testo significativo

---

## 🧪 Testing dei Template

### Come Testare

1. Vai su **Gestione Aule → Impostazioni → Tab Email**
2. Modifica il template desiderato
3. Seleziona tipo email dal menu "Anteprima Email"
4. Clicca **Genera Anteprima**
5. Verifica la resa visuale nel box anteprima

### Cosa Verificare

- [ ] Tutti i placeholder sono sostituiti correttamente
- [ ] HTML è renderizzato senza errori
- [ ] Box `.booking-details` appare con bordo blu
- [ ] Link con `.button` sono cliccabili e blu
- [ ] Emoji sono visualizzate correttamente
- [ ] Testo è leggibile e ben formattato
- [ ] Footer include nome sito e URL

---

## 🔧 Personalizzazione Avanzata

### Aggiungere Placeholder Personalizzati

Puoi estendere i placeholder usando il filtro WordPress:

```php
add_filter('prenotazione_aule_ssm_email_placeholders', function($placeholders, $booking) {
    // Aggiungi placeholder personalizzato
    $placeholders['{nome_universita}'] = 'Università degli Studi';
    $placeholders['{telefono_segreteria}'] = '+39 081 1234567';

    return $placeholders;
}, 10, 2);
```

### Modificare gli Stili Email

Per personalizzare ulteriormente gli stili, usa il filtro:

```php
add_filter('prenotazione_aule_ssm_email_wrapper_style', function($styles) {
    // Personalizza colore header
    $styles['header_bg'] = '#0073aa'; // Blu personalizzato

    return $styles;
});
```

---

## 📞 Supporto

Per problemi con i template email:

1. **Verifica sintassi HTML** - Usa validatore online
2. **Controlla log WordPress** - `wp-content/debug.log`
3. **Testa invio email** - Con plugin SMTP come WP Mail SMTP
4. **Consulta documentazione** - `TROUBLESHOOTING.md` nel plugin

---

**Ultimo aggiornamento:** 2025-10-10
**Versione:** 1.0.0
**Plugin:** Prenotazione Aule SSM v2.1.0
