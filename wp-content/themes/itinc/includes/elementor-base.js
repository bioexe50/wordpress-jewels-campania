jQuery( window ).load(function() {
	elementor.hooks.addAction( 'panel/open_editor/section', function( panel, model, view ) {

		jQuery(document).on('change', 'input[data-setting="thsn-extended-column"], input[data-setting="thsn-strech-content-left"], input[data-setting="thsn-strech-content-right"], select[data-setting="background_position"], select[data-setting="background_attachment"], select[data-setting="background_repeat"], select[data-setting="background_size"]' , function(e){
			setTimeout(function(){
				jQuery('#elementor-preview-iframe')[0].contentWindow.thsn_rearrange_stretched_col( model.id );
			}, 200);
		});

	});

	elementor.hooks.addAction( 'panel/open_editor/column', function( panel, model, view ) {
		setTimeout(function(){
			jQuery('.elementor-component-tab > a').on('click',function(){
				setTimeout(function(){
					jQuery(document).on('change', 'input[data-setting="thsn-extended-column"], input[data-setting="thsn-strech-content-left"], input[data-setting="thsn-strech-content-right"], select[data-setting="background_position"], select[data-setting="background_attachment"], select[data-setting="background_repeat"], select[data-setting="background_size"], input[type=radio]', function(e) {
						setTimeout(function(){
							jQuery('#elementor-preview-iframe')[0].contentWindow.thsn_rearrange_stretched_col( model.id );
						}, 200);
					});
					jQuery('label[for="elementor-control-classic"]').on('click', function(){
						jQuery('#elementor-preview-iframe')[0].contentWindow.thsn_rearrange_stretched_col( model.id );
					});
				}, 500);
			})
		 }, 500);
	});

});