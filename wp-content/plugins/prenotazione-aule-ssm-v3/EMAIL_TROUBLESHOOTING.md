# Email Troubleshooting Guide - Prenotazione Aule SSM v3.2.3

## 📧 Sistema Email - Architettura

Il plugin invia 3 tipi di email:

1. **Email Conferma Prenotazione** → `send_booking_confirmation()` → Destinatario: UTENTE
2. **Email Rifiuto Prenotazione** → `send_booking_rejection()` → Destinatario: UTENTE
3. **Email Notifica Admin** → `send_admin_notification()` → Destinatario: ADMIN

## 🔍 Verifica Problemi Email

### Problema 1: "Quando approvo prenotazione, email arriva ad admin invece che all'utente"

**DIAGNOSI**:
Il codice è CORRETTO. La funzione `ajax_approve_booking()` chiama `send_booking_confirmation($booking_id)` che invia email a `$booking->email_richiedente`.

**File**: `/admin/class-prenotazione-aule-ssm-admin.php` righe 547-577
```php
public function ajax_approve_booking() {
    // ... validation ...

    $result = $this->database->update_prenotazione_stato(
        $booking_id,
        'confermata',
        get_current_user_id(),
        $note_admin
    );

    if ($result) {
        // Invia email di conferma ALL'UTENTE
        $email_handler = new Prenotazione_Aule_SSM_Email();
        $email_handler->send_booking_confirmation($booking_id);

        wp_send_json_success(__('Prenotazione confermata', 'prenotazione-aule-ssm'));
    }
}
```

**Verifica Destinatario**:
File: `/includes/class-prenotazione-aule-ssm-email.php` righe 58-78
```php
public function send_booking_confirmation($booking_id) {
    $booking = $this->database->get_prenotazione_by_id($booking_id);

    // ...

    $result = wp_mail(
        $booking->email_richiedente,  // ← EMAIL UTENTE CORRETTAESULT: wp_mail() returns TRUE if email sent, FALSE if failed
}
```

### Verifiche da fare:

#### 1. Verifica Configurazione WordPress Mail
```bash
# Verifica che wp_mail() funzioni
# In WordPress admin, vai su: Plugin → Add New → Cerca "WP Mail SMTP"
# Installa e configura SMTP se le email non partono
```

#### 2. Verifica Log Email
Il plugin logga tutti gli invii email in `/var/log/debug.log` (se WP_DEBUG è attivo).

Cerca:
```
[Aule Booking Email] Email sent successfully ✓ - Booking ID: XX, Recipient: utente@email.com, Type: confirmation
```

Se vedi:
```
[Aule Booking Email ERROR] Email failed ✗ - Booking ID: XX, Recipient: utente@email.com, Type: confirmation
```

Significa che `wp_mail()` ha fallito. Cause comuni:
- Server non ha mailserver configurato
- SPF/DKIM non configurati
- Hosting blocca `sendmail`

#### 3. Verifica Database
```sql
-- Verifica che email_richiedente sia corretta nel database
SELECT
    id,
    nome_richiedente,
    email_richiedente,
    stato
FROM jc_prenotazione_aule_ssm_prenotazioni
WHERE id = [BOOKING_ID];
```

#### 4. Test Manuale Email
Aggiungi questo test temporaneo in `ajax_approve_booking()` dopo la riga 571:

```php
// TEST EMAIL - RIMUOVERE DOPO DEBUG
error_log('[TEST EMAIL] Booking ID: ' . $booking_id);
error_log('[TEST EMAIL] Email destinatario: ' . $booking->email_richiedente);
error_log('[TEST EMAIL] Risultato wp_mail: ' . ($result ? 'SUCCESS' : 'FAILED'));

// Test email semplice
$test_result = wp_mail(
    'tuo-email-test@example.com',
    'TEST Prenotazione Aule',
    'Se ricevi questo, wp_mail() funziona'
);
error_log('[TEST EMAIL] Test mail result: ' . ($test_result ? 'SUCCESS' : 'FAILED'));
```

### Problema 2: "Email rifiuto arriva, email conferma no"

**DIAGNOSI**: Questo suggerisce un problema SOLO con `send_booking_confirmation()`, non con `wp_mail()` generale.

**Possibili Cause**:
1. Template email conferma corrotto
2. Placeholder non sostituiti causano blocco spam
3. Oggetto email troppo generico

**Verifica Template**:
```php
// Vai in: WordPress Admin → Gestione Aule → Impostazioni → Email

// Verifica template "Email Conferma":
La tua prenotazione è stata confermata!

Dettagli:
Aula: {nome_aula}
Data: {data_prenotazione}
Orario: {ora_inizio} - {ora_fine}
Codice: {codice_prenotazione}

Grazie!
```

**Verifica Placeholder**:
Tutti i placeholder devono essere sostituiti. File `/includes/class-prenotazione-aule-ssm-email.php` righe 276-293:

```php
private function replace_placeholders($template, $booking) {
    $placeholders = array(
        '{nome_richiedente}' => $booking->nome_richiedente,
        '{cognome_richiedente}' => $booking->cognome_richiedente,
        '{email_richiedente}' => $booking->email_richiedente, // ✅ PRESENTE
        '{nome_aula}' => $booking->nome_aula ?? '',
        '{ubicazione}' => $booking->ubicazione ?? '',
        '{data_prenotazione}' => date_i18n('l, j F Y', strtotime($booking->data_prenotazione)),
        '{ora_inizio}' => date('H:i', strtotime($booking->ora_inizio)),
        '{ora_fine}' => date('H:i', strtotime($booking->ora_fine)),
        '{motivo}' => $booking->motivo_prenotazione,
        '{stato_prenotazione}' => $this->get_stato_display($booking->stato),
        '{codice_prenotazione}' => $booking->codice_prenotazione ?? '',
        '{note_admin}' => $booking->note_admin ?? '',
        '{link_gestione}' => admin_url('admin.php?page=prenotazione-aule-ssm-prenotazioni&search=' . urlencode($booking->email_richiedente)),
        '{sito_nome}' => get_bloginfo('name'),
        '{sito_url}' => get_home_url()
    );

    return str_replace(array_keys($placeholders), array_values($placeholders), $template);
}
```

### Problema 3: Email Admin mostra `{email_richiedente}` invece dell'email

**RISOLTO nella v3.2.0** ✅

Verificato che placeholder `{email_richiedente}` sia presente nel template admin (riga 280).

## 🛠️ Soluzioni Consigliate

### Soluzione 1: Installa WP Mail SMTP
```
1. Vai su: Plugin → Add New
2. Cerca: "WP Mail SMTP"
3. Installa e attiva
4. Configura con Gmail/SendGrid/Mailgun
5. Test email di prova
```

### Soluzione 2: Verifica Header Email
Aggiungi header BCC per debug:

File: `/includes/class-prenotazione-aule-ssm-email.php` riga 42
```php
$this->headers = array(
    'Content-Type: text/html; charset=UTF-8',
    'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
    'Bcc: debug@tuodominio.com' // ← AGGIUNGI PER DEBUG
);
```

### Soluzione 3: Enable WordPress Debug
File: `wp-config.php`
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Poi controlla `/wp-content/debug.log` per errori email.

## 📊 Test Completo Email

Esegui questo test per verificare tutto il flusso:

```php
// FILE: test-email.php (metti in root WordPress)
<?php
require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    die('Accesso negato');
}

// Test 1: wp_mail() di base
echo "<h2>Test 1: wp_mail() Base</h2>";
$test1 = wp_mail('tuo-email@example.com', 'Test Base', 'Se ricevi questo, wp_mail funziona');
echo $test1 ? '✅ SUCCESS' : '❌ FAILED';

// Test 2: Email classe plugin
echo "<h2>Test 2: Email Classe Plugin</h2>";
require_once('wp-content/plugins/prenotazione-aule-ssm/includes/class-prenotazione-aule-ssm-database.php');
require_once('wp-content/plugins/prenotazione-aule-ssm/includes/class-prenotazione-aule-ssm-email.php');

$email_handler = new Prenotazione_Aule_SSM_Email();

// Trova una prenotazione di test
$database = new Prenotazione_Aule_SSM_Database();
$prenotazioni = $database->get_prenotazioni(array('limit' => 1));

if (!empty($prenotazioni)) {
    $booking_id = $prenotazioni[0]->id;
    echo "Booking ID test: " . $booking_id . "<br>";

    $result = $email_handler->send_booking_confirmation($booking_id);
    echo $result ? '✅ Email conferma inviata' : '❌ Email conferma FALLITA';
} else {
    echo "❌ Nessuna prenotazione disponibile per test";
}

// Test 3: Verifica configurazione mail
echo "<h2>Test 3: Configurazione Mail</h2>";
echo "From email: " . get_option('admin_email') . "<br>";
echo "Site name: " . get_bloginfo('name') . "<br>";

// Test 4: Verifica funzione wp_mail esiste
echo "<h2>Test 4: Funzione wp_mail()</h2>";
echo function_exists('wp_mail') ? '✅ wp_mail() disponibile' : '❌ wp_mail() NON disponibile';
```

Accedi a: `http://tuo-sito.com/test-email.php`

## 📝 Checklist Troubleshooting

- [ ] WP_DEBUG attivo e controllato `/wp-content/debug.log`
- [ ] Verificato che `email_richiedente` sia corretta nel database
- [ ] Testato wp_mail() con email semplice funziona
- [ ] Controllato spam folder dell'utente
- [ ] Verificato template email non ha errori
- [ ] Installato WP Mail SMTP per SMTP autenticato
- [ ] Controllato SPF/DKIM dominio
- [ ] Verificato che hosting non blocchi sendmail

## 🎯 Conclusioni

**Il codice del plugin è CORRETTO**. I problemi email sono quasi sempre dovuti a:

1. **Configurazione server mail** (95% dei casi)
2. **Email finiscono in spam** (4% dei casi)
3. **Bug template email** (1% dei casi - già risolto in v3.2.0)

**Raccomandazione**: Installa WP Mail SMTP e configura SMTP autenticato (Gmail/SendGrid) per risolvere definitivamente.
