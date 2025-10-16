/**
 * Script di debug da iniettare nella pagina
 * Crea un pulsante che esegue tutti i test automaticamente
 */

// Crea pulsante debug
jQuery(function($){
    var $btn = $('<button id="ccm-debug-btn" style="position:fixed;top:10px;right:10px;z-index:99999;padding:15px 20px;background:#ff0000;color:#fff;border:none;border-radius:8px;font-size:16px;font-weight:bold;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,0.3);">🐛 DEBUG MAPPA</button>');

    $('body').append($btn);

    $btn.on('click', function(){
        var report = '=== REPORT DEBUG MAPPA ===\n\n';

        // Test 1: Div esiste
        var divExists = $('#campania-companies-map').length;
        report += '1. Div mappa esiste: ' + (divExists ? '✅ SI' : '❌ NO') + '\n';

        // Test 2: Altezza div
        var divHeight = $('#campania-companies-map').height();
        report += '2. Altezza div: ' + divHeight + 'px ' + (divHeight > 100 ? '✅' : '❌') + '\n';

        // Test 3: Contenuto HTML del div
        var divContent = $('#campania-companies-map').html();
        report += '3. Contenuto div: ' + divContent.length + ' caratteri ' + (divContent.length > 100 ? '✅ Mappa inizializzata' : '❌ Div vuoto') + '\n';

        // Test 4: Leaflet caricato
        var leafletLoaded = typeof L !== 'undefined';
        report += '4. Leaflet caricato: ' + (leafletLoaded ? '✅ SI (v' + (L.version || '?') + ')' : '❌ NO') + '\n';

        // Test 5: Configurazione
        var configExists = typeof campaniaCompaniesMap !== 'undefined';
        report += '5. Configurazione campaniaCompaniesMap: ' + (configExists ? '✅ SI' : '❌ NO') + '\n';
        if(configExists){
            report += '   - AJAX URL: ' + campaniaCompaniesMap.ajax_url + '\n';
            report += '   - Nonce: ' + campaniaCompaniesMap.nonce + '\n';
            report += '   - Province: ' + (campaniaCompaniesMap.provinces || []).join(', ') + '\n';
            report += '   - Città: ' + (campaniaCompaniesMap.cities || []).join(', ') + '\n';
        }

        console.log(report);
        alert(report + '\n\nVedi console per dettagli completi');

        // Test 6: Chiamata AJAX
        if(configExists){
            console.log('\n6. Test AJAX in corso...');
            $.post(campaniaCompaniesMap.ajax_url, {
                action: 'campania_companies_map_get_data',
                nonce: campaniaCompaniesMap.nonce
            })
            .done(function(response){
                console.log('✅ AJAX SUCCESS:', response);
                if(response.success && response.data){
                    console.log('✅ Aziende ricevute: ' + response.data.length);
                    console.table(response.data);
                    alert('✅ AJAX OK: Ricevute ' + response.data.length + ' aziende!\n\nVedi console per la tabella dati.');
                } else {
                    console.error('❌ AJAX ritornato ma senza dati:', response);
                    alert('❌ AJAX ritornato ma response.success = false');
                }
            })
            .fail(function(xhr, status, error){
                console.error('❌ AJAX FAILED:', status, error, xhr);
                alert('❌ AJAX FALLITO: ' + status + ' - ' + error);
            });
        }
    });
});
