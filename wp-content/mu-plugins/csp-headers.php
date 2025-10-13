<?php
/**
 * CSP Headers Configuration
 * Updated for Web Workers support (FullCalendar, etc.)
 * Updated for Dashicons font support (data: URLs)
 * 
 * IMPORTANTE: Rimuove header CSP preesistenti prima di impostare il nuovo
 */

if (!headers_sent()) {
    // Rimuovi eventuali header CSP già impostati
    header_remove("Content-Security-Policy");
    
    // Imposta il nuovo header CSP con supporto Web Workers e Dashicons
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline' https: blob:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' data: https:; worker-src blob:;", true);
}
