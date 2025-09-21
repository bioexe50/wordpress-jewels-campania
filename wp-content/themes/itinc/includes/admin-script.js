
"use strict";

var thsn_admin_menu_class = function(){
	jQuery('#adminmenu > li[id$="-wp-admin-customize"]').addClass('thsn-admin-customize-menu');
}

var thsn_kirki_bg_color_show_hide = function(){
	jQuery('.accordion-section').on('click', function(){
		setTimeout(function(){
			jQuery('li.customize-control.customize-control-kirki-background' ).each(function(){
				var this_bg_ele = jQuery(this);
				var selected = '';
				if( jQuery(this).prev().hasClass('customize-control-kirki-radio-image') ){
					selected = jQuery('input.image-select:checked',  jQuery(this).prev() ).val() ; 
					if ( selected ) {
						if( selected == 'custom' ) {
							jQuery( '.background-color', this_bg_ele ).show();
						} else {
							jQuery( '.background-color', this_bg_ele ).hide();
						}
					}
				}
			});
		}, 100);
	});
}

jQuery(document).ready(function($){
	thsn_admin_menu_class();
	thsn_kirki_bg_color_show_hide();
	jQuery( '#acf-thsn-photo-gallery-group' ).hide();
	jQuery( '.thsn-merlin-message-small a' ).on('click', function(e){
		e.preventDefault();
		var parent = jQuery(this).closest('.thsn-merlin-message-box');
		jQuery('.thsn-merlin-message-conform', parent).fadeIn();
		jQuery('.thsn-merlin-message-inner', parent).animate({opacity: 0}, 400);
		jQuery('.thsn-merlin-message-box button.notice-dismiss', parent).fadeOut(400);
	});
	jQuery( '.thsn-disable-merlin-message-cancel' ).on('click', function(e){
		e.preventDefault();
		var parent = jQuery(this).closest('.thsn-merlin-message-box');
		jQuery('.thsn-merlin-message-conform', parent).fadeOut();
		jQuery('.thsn-merlin-message-inner', parent).animate({opacity: 1}, 400);
		jQuery('.thsn-merlin-message-box button.notice-dismiss', parent).fadeIn(400);
	});
	jQuery( '.thsn-disable-merlin-message' ).on('click', function(e){
		e.preventDefault();
		jQuery(this).closest('.notice.is-dismissible').slideUp();
		jQuery.post(
			ajaxurl, 
			{ 'action': 'thsn_remove_merlin_message' },
			function(response) {
				// Do nothing
			}
		);
	});

	// Ratings box
	jQuery( '.thsn-merlin-ratings-box .thsn-question-btn' ).on('click', function(e){
		e.preventDefault();
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-main').slideUp(400);
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-questions').slideDown(400);
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-back-link').fadeIn(400);
	});
	jQuery( '.thsn-merlin-ratings-box .thsn-happy-btn' ).on('click', function(e){
		e.preventDefault();
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-main').slideUp(400);
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-ratings').slideDown(400);
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-back-link').fadeIn(400);
	});
	jQuery( '.thsn-merlin-ratings-box .thsn-merlin-ratings-box-back-link' ).on('click', function(e){
		e.preventDefault();
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-main').slideDown(400);
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-ratings').slideUp(400);
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-questions').slideUp(400);
		jQuery('.thsn-merlin-ratings-box .thsn-merlin-ratings-box-back-link').fadeOut(400);
	});
	jQuery( '.thsn-disable-ratings-message-cancel' ).on('click', function(e){
		var parent = jQuery(this).closest('.thsn-merlin-message-box');
		jQuery('.thsn-merlin-message-conform', parent).fadeOut();
		jQuery('.thsn-merlin-message-inner', parent).animate({opacity: 1}, 400);
		jQuery('.thsn-merlin-message-box button.notice-dismiss', parent).fadeIn(400);
	});
	jQuery( '.thsn-merlin-ratings-box .thsn-disable-ratings-message' ).on('click', function(e){
		e.preventDefault();
		jQuery(this).closest('.notice.is-dismissible').slideUp();
		jQuery.post(
			ajaxurl, 
			{ 'action': 'thsn_remove_ratings_message' },
			function(response) {
				// Do nothing
			}
		);
	});
});
jQuery(window).on( 'load', function($){

	// Post Format functions
	themesion_post_format_calls();

});	

var themesion_post_format_calls = function() {

	jQuery('#acf-form-data').insertAfter('#titlediv');
	jQuery('#acf_after_title-sortables').insertAfter('#acf-form-data');

	jQuery('input[type=radio][name=post_format]').change(function() {

		if( this.value == 'image' ){  // Post Format - Image
			jQuery('#postimagediv').after('<div id="thsn-postimagediv-place-holder"></div>').insertAfter('#titlediv');
		} else {
			jQuery('#thsn-postimagediv-place-holder').replaceWith( jQuery('#postimagediv') );
		}

		if( this.value == 'status' ){  // Post Format - Status
			jQuery('#content:visible').focus();
			jQuery('#titlewrap').hide();
		} else {
			jQuery('#titlewrap').show();
		}

	});

};