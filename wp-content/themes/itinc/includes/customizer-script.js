"use strict";
jQuery(window).load(function($){

	wp.customize(
		'header-style',
		function( header_style ) {

			var header_height			= '120';
			var header_bgcolor			= 'white';
			var global_color			= '#8cbc43';
			var light_bg_color			= '#f7f9fa';
			var blackish_bg_color		= '#202426';
			var secondary_color			= '#202426';
			var menu_bgcolor			= 'white';
			var main_menu_typography	= {
				'font-family'		: 'Rajdhani',
				'variant'			: '700',
				'font-size'			: '16px',
				'line-height'		: '20px',
				'letter-spacing'	: '0.5px',
				'color'				: '#202426',
				'text-transform'	: 'uppercase',
				'font-backup'		: '',
			};
			var header_box_title_typography = {
				'font-family'		: 'Rajdhani',
				'variant'			: '800',
				'font-size'			: '17px',
				'line-height'		: '27px',
				'letter-spacing'	: '0px',
				'color'				: '#202426',
				'text-transform'	: 'none',
				'font-backup'		: ''
			};
			var header_box_content_typography = {
				'font-family'		: 'Rajdhani',
				'variant'			: '700',
				'font-size'			: '15px',
				'line-height'		: '25px',
				'letter-spacing'	: '1px',
				'color'				: '#b0b6bf',
				'text-transform'	: 'none',
				'font-backup'		: ''
			};
			var header_box1_title	= 'Call us for any question';
			var header_box1_content	= '(+00)888.666.88';
			var header_box1_link	= '';
			var header_box1_icon	= 'thsn-itinc-icon thsn-itinc-icon-email;fa fa-map-marker;sgicon sgicon-Pointer';
			var header_box2_title	= 'Request on';
			var header_box2_content	= 'Get Appointment';
			var header_box2_link	= '';
			var header_box2_icon	= 'thsn-itinc-icon thsn-itinc-icon-mail;fa fa-info-circle;sgicon sgicon-Mail';
			var header_box3_title	= '000 8888 999';
			var header_box3_content	= 'Free Call';
			var header_box3_link	= '';
			var header_box3_icon	= 'thsn-itinc-icon thsn-itinc-icon-;fa fa-info-circle;sgicon sgicon-Phone2';
			var sticky_header_bgcolor	= 'white';
			var main_menu_active_color	= 'globalcolor';
			var main_menu_sticky_color	= '#09162a';
			var preheader_enable		= false;
			var logo_height				= '55';
			var preheader_bgcolor		= 'white';
			var preheader_text_color	= 'blackish';
			var preheader_search		= false
			var titlebar_height			= '200';
			var titlebar_bgcolor		= 'custom';
			var titlebar_background		= {
				'background-color'			: '#f6f6f6',
				'background-repeat'			: 'no-repeat',
				'background-position'		: 'center center',
				'background-size'			: 'cover',
				'background-attachment'		: 'scroll',
				'background-image'			: ''
			};
			var titlebar_heading_typography		= {
				'font-family'		: 'Rajdhani',
				'variant'			: '700',
				'font-size'			: '42px',
				'line-height'		: '52px',
				'letter-spacing'	: '0px',
				'color'				: '#202426',
				'text-transform'	: 'none',
				'font-backup'		: ''
			};
			var titlebar_subheading_typography		= {
				'font-family'		: 'Rajdhani',
				'variant'			: '700',
				'font-size'			: '16px',
				'line-height'		: '1.5',
				'letter-spacing'	: '0px',
				'color'				: '#202426',
				'text-transform'	: 'none',
				'font-backup'		: ''
			};
			var titlebar_breadcrumb_typography		= {
				'font-family'		: 'Rajdhani',
				'variant'			: '700',
				'font-size'			: '12px',
				'line-height'		: '1.5',
				'letter-spacing'	: '1px',
				'color'				: '#6d7a8c',
				'text-transform'	: 'uppercase',
				'font-backup'		: ''
			};
			var logo					= thsn_admin_js_variables.theme_path + '/images/logo.png';
			var sticky_logo				= '';
			header_style.bind( function(value) {
				if( value == '1' ){ // Default header style
					wp.customize('global-color').set(global_color);
					wp.customize('light-bg-color').set(light_bg_color);
					wp.customize('blackish-bg-color').set(blackish_bg_color);
					wp.customize('secondary-color').set(secondary_color);
					wp.customize('header-height').set(header_height);
					wp.customize('header-bgcolor').set(header_bgcolor);
					wp.customize('menu-bgcolor').set(menu_bgcolor);
					wp.customize('main-menu-typography').set(main_menu_typography);
					wp.customize('header-box-title-typography').set(header_box_title_typography);
					wp.customize('header-box-content-typography').set(header_box_content_typography);
					wp.customize('header-search').set(false);
					wp.customize('sticky-header-bgcolor').set(sticky_header_bgcolor);
					wp.customize('main-menu-active-color').set(main_menu_active_color);
					wp.customize('main-menu-sticky-color').set(main_menu_sticky_color);
					wp.customize('preheader-enable').set(preheader_enable);
					wp.customize('logo-height').set(logo_height);
					wp.customize('header-btn-text').set('');
					wp.customize('header-btn-url').set('');
					wp.customize('preheader-bgcolor').set(preheader_bgcolor);
					wp.customize('preheader-text-color').set(preheader_text_color);
					wp.customize('titlebar-height').set(titlebar_height);
					wp.customize('titlebar-bgcolor').set(titlebar_bgcolor);
					wp.customize('titlebar-background').set(titlebar_background);
					wp.customize('titlebar-heading-typography').set(titlebar_heading_typography);
					wp.customize('titlebar-subheading-typography').set(titlebar_subheading_typography);
					wp.customize('titlebar-breadcrumb-typography').set(titlebar_breadcrumb_typography);
					wp.customize('logo').set(logo);
					wp.customize('sticky-logo').set(sticky_logo);
					
				} else if( value == '2' ){ // Header style 2 
					wp.customize('global-color').set('#8cbc43');
					wp.customize('light-bg-color').set('#f7f9fa');
					wp.customize('blackish-bg-color').set('#202426');
					wp.customize('secondary-color').set('202426');
					wp.customize('header-height').set('100');
					wp.customize('header-bgcolor').set('white');
					wp.customize('header-background-color').set('white');
					wp.customize('main-menu-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '16px',
						'line-height'		: '20px',
						'letter-spacing'	: '0.5px',
						'color'				: '#0c121d',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('buttons-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '15px',
						'line-height'		: '15px',
						'letter-spacing'	: '0.5px',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('header-search').set(true);
					wp.customize('sticky-header-bgcolor').set('white');
					wp.customize('main-menu-active-color').set(global_color);
					wp.customize('main-menu-sticky-color').set('#09162a');
					wp.customize('preheader-enable').set(true);
					wp.customize('logo-height').set('55');
					wp.customize('header-btn-text').set('Get a Quote');
					wp.customize('header-btn-url').set('#');
					wp.customize('preheader-bgcolor').set('blackish');
					wp.customize('preheader-text-color').set('white');
					wp.customize('preheader-left').set('<a href="#">Now Hiring:</a> Are you a driven and motivated 1st Line IT Support Engineer?');
					wp.customize('preheader-right').set('<ul class="thsn-contact-info"><li><i class="thsn-base-icon-contact"></i> Make a call  : +1 (212) 255-5511</li><li><i class="thsn-base-icon-marker"></i> Los Angeles Gournadi, 1230  Bariasl</li></ul>[thsn-social-links]');
					wp.customize('preheader-search').set(false);
					wp.customize('titlebar-height').set('500');
					wp.customize('titlebar-bgcolor').set('transparent');
					wp.customize('titlebar-background').set({
						'background-color'			: '#f6f6f6',
						'background-repeat'			: 'no-repeat',
						'background-position'		: 'center center',
						'background-size'			: 'cover',
						'background-attachment'		: 'scroll',
						'background-image'			: thsn_admin_js_variables.theme_path + '/images/title-bg.jpg',
					});
					wp.customize('titlebar-heading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '46px',
						'line-height'		: '52px',
						'letter-spacing'	: '0px',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: ''
					});
					wp.customize('titlebar-subheading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '0px',
						'color'				: '#fff',
						'text-transform'	: 'none',
						'font-backup'		: '',
					});
					wp.customize('titlebar-breadcrumb-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '1',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: ''
					});
					wp.customize('logo').set(thsn_admin_js_variables.theme_path + '/images/logo.png');
					wp.customize('sticky-logo').set(sticky_logo);
					
				} else if( value == '3' ){ // Header style 3
					wp.customize('global-color').set('#8cbc43');
					wp.customize('light-bg-color').set('#f7f9fa');
					wp.customize('blackish-bg-color').set('#202426');
					wp.customize('secondary-color').set('#202426');
					wp.customize('header-height').set('120');
					wp.customize('header-bgcolor').set('transparent');
					wp.customize('main-menu-typography').set( {
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '16px',
						'line-height'		: '20px',
						'letter-spacing'	: '0.5px',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('header-box-title-typography').set(header_box_title_typography);
					wp.customize('header-box-content-typography').set(header_box_content_typography);
					wp.customize('header-search').set(true);
					wp.customize('sticky-header-bgcolor').set('white');
					wp.customize('main-menu-active-color').set(global_color);
					wp.customize('main-menu-sticky-color').set('#09162a');
					wp.customize('preheader-enable').set(true);
					wp.customize('logo-height').set('55');
					wp.customize('header-btn-text').set('Get a Quote');
					wp.customize('header-btn-url').set('#');
					wp.customize('preheader-bgcolor').set('transparent');
					wp.customize('preheader-text-color').set('white');
					wp.customize('preheader-left').set('<a href="#">Now Hiring:</a> Are you a driven and motivated 1st Line IT Support Engineer?');
					wp.customize('preheader-right').set('<ul class="thsn-contact-info"><li><i class="thsn-base-icon-contact"></i> Make a call  : +1 (212) 255-5511</li><li><i class="thsn-base-icon-marker"></i> Los Angeles Gournadi, 1230  Bariasl</li></ul>');
					wp.customize('preheader-search').set(false);
					wp.customize('titlebar-height').set('550');
					wp.customize('titlebar-bgcolor').set('transparent');
					wp.customize('titlebar-background').set({
						'background-color'			: '#f6f6f6',
						'background-repeat'			: 'no-repeat',
						'background-position'		: 'center center',
						'background-size'			: 'cover',
						'background-attachment'		: 'scroll',
						'background-image'			: thsn_admin_js_variables.theme_path + '/images/title-bg.jpg',
					});
					wp.customize('titlebar-heading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '42px',
						'line-height'		: '52px',
						'letter-spacing'	: '0px',
						'color'				: '#ffffff',
						'text-transform'	: 'uppercase',
						'font-backup'		: ''
					});
					wp.customize('titlebar-subheading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '0px',
						'color'				: '#ffffff',
						'text-transform'	: 'none',
						'font-backup'		: '',
					});
					wp.customize('titlebar-breadcrumb-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '1',
						'color'				: '#ffffff',
						'text-transform'	: 'uppercase',
						'font-backup'		: ''
					});
					wp.customize('logo').set(thsn_admin_js_variables.theme_path + '/images/logo-white.png');
					wp.customize('sticky-logo').set(thsn_admin_js_variables.theme_path + '/images/logo.png');
					
				} else if( value == '4' ){ // Header style 4
					wp.customize('global-color').set('#8cbc43');
					wp.customize('light-bg-color').set('#f7f9fa');
					wp.customize('blackish-bg-color').set('#202426');
					wp.customize('secondary-color').set('#202426');
					wp.customize('header-height').set('120');
					wp.customize('header-bgcolor').set('white');
					wp.customize('menu-bgcolor').set('globalcolor');
					wp.customize('main-menu-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '15px',
						'line-height'		: '20px',
						'letter-spacing'	: '0.5px',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('header-box-title-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '18px',
						'line-height'		: '27px',
						'letter-spacing'	: '0.5px',
						'color'				: '#202426',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('header-box-content-typography').set({
						'font-family'		: 'Roboto',
						'variant'			: 'regular',
						'font-size'			: '15px',
						'line-height'		: '22px',
						'letter-spacing'	: '0px',
						'color'				: '#666666',
						'text-transform'	: 'None',
						'font-backup'		: '',
					});
					wp.customize('header-search').set(true);
					wp.customize('sticky-header-bgcolor').set('globalcolor');
					wp.customize('main-menu-active-color','white');
					wp.customize('main-menu-sticky-color').set('#fff');
					wp.customize('preheader-enable').set(false);
					wp.customize('logo-height').set('55');
					wp.customize('facebook').set('#');
					wp.customize('twitter').set('#');
					wp.customize('instagram').set('#');
					wp.customize('youtube').set('#');
					wp.customize('header-box1-title').set('ADDRESS');
					wp.customize('header-box1-content').set('66 Broklyn St. New York');
					wp.customize('header-box1-link').set('#');
					wp.customize('header-box1-icon').set('thsn-itinc-icon thsn-itinc-icon-pin');
					wp.customize('header-box2-title').set('Email');
					wp.customize('header-box2-content').set('needhelp@itinc.com');
					wp.customize('header-box2-link').set('#');
					wp.customize('header-box2-icon').set('thsn-itinc-icon thsn-itinc-icon-open');
					wp.customize('header-box3-title').set('PHONE');
					wp.customize('header-box3-content').set('+01-21-444-99990');
					wp.customize('header-box3-link').set('#');
					wp.customize('header-box3-icon').set('thsn-itinc-icon thsn-itinc-icon-phone-call');
					wp.customize('header-btn-text').set('Get a Free Quote');
					wp.customize('header-btn-url').set('#');
					wp.customize('preheader-bgcolor').set(preheader_bgcolor);
					wp.customize('preheader-text-color').set(preheader_text_color);
					wp.customize('titlebar-height').set('500');
					wp.customize('titlebar-bgcolor').set('transparent');
					wp.customize('titlebar-background').set({
						'background-color'			: '#f6f6f6',
						'background-repeat'			: 'no-repeat',
						'background-position'		: 'center center',
						'background-size'			: 'cover',
						'background-attachment'		: 'scroll',
						'background-image'			: thsn_admin_js_variables.theme_path + '/images/title-bg.jpg',
					});
					wp.customize('titlebar-heading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '46px',
						'line-height'		: '52px',
						'letter-spacing'	: '-1px',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('titlebar-subheading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '0px',
						'color'				: '#fff',
						'text-transform'	: 'none',
						'font-backup'		: '',
					});
					wp.customize('titlebar-breadcrumb-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '1px',
						'color'				: '#ffffff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('logo').set(thsn_admin_js_variables.theme_path + '/images/logo.png');
					wp.customize('sticky-logo').set(thsn_admin_js_variables.theme_path + '/images/logo.png');
					
				} else if( value == '5' ){ // Header style 5
					wp.customize('global-color').set('#8cbc43');
					wp.customize('light-bg-color').set('#f7f9fa');
					wp.customize('blackish-bg-color').set('#202426');
					wp.customize('secondary-color').set('#202426');
					wp.customize('header-height').set('70');
					wp.customize('header-bgcolor').set('white');
					wp.customize('menu-bgcolor').set('white');
					wp.customize('main-menu-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '16px',
						'line-height'		: '20px',
						'letter-spacing'	: '0.5px',
						'color'				: '#0c121d',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('header-box-title-typography').set(header_box_title_typography);
					wp.customize('header-box-content-typography').set(header_box_content_typography);
					wp.customize('header-search').set(true);
					wp.customize('sticky-header-bgcolor').set('white');
					wp.customize('main-menu-active-color','global');
					wp.customize('main-menu-sticky-color').set('#09162a');
					wp.customize('preheader-enable').set(true);
					wp.customize('logo-height').set('55');
					wp.customize('facebook').set('#');
					wp.customize('twitter').set('#');
					wp.customize('instagram').set('#');
					wp.customize('youtube').set('#');
					wp.customize('header-btn-text').set('Get a Quote');
					wp.customize('header-btn-url').set('#');
					wp.customize('preheader-bgcolor').set(preheader_bgcolor);
					wp.customize('preheader-text-color').set(preheader_text_color);
					wp.customize('preheader-left').set('<a href="# " class="thsn-skincolor">Now Hiring:</a> Are you a driven and motivated 1st Line IT Support Engineer?');
					wp.customize('preheader-right').set('<ul class="thsn-contact-info"><li><i class="thsn-base-icon-contact"></i> Make a call  : +1 (212) 255-5511</li><li><i class="thsn-base-icon-marker"></i> Los Angeles Gournadi, 1230  Bariasl</li></ul>');
					wp.customize('preheader-search').set(false);
					wp.customize('titlebar-height').set('500');
					wp.customize('titlebar-bgcolor').set('transparent');
					wp.customize('titlebar-background').set({
						'background-color'			: '#f6f6f6',
						'background-repeat'			: 'no-repeat',
						'background-position'		: 'center center',
						'background-size'			: 'cover',
						'background-attachment'		: 'scroll',
						'background-image'			: thsn_admin_js_variables.theme_path + '/images/title-bg.jpg',
					});
					wp.customize('titlebar-heading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '46px',
						'line-height'		: '52px',
						'letter-spacing'	: '-1px',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('titlebar-subheading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '0px',
						'color'				: '#fff',
						'text-transform'	: 'none',
						'font-backup'		: '',
					});
					wp.customize('titlebar-breadcrumb-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '1px',
						'color'				: '#ffffff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('logo').set(thsn_admin_js_variables.theme_path + '/images/logo.png');
					wp.customize('sticky-logo').set(thsn_admin_js_variables.theme_path + '/images/logo.png');
					
				} else if( value == '6' ){ // Header style 6
					wp.customize('global-color').set('#8cbc43');
					wp.customize('light-bg-color').set('#f7f9fa');
					wp.customize('blackish-bg-color').set('#202426');
					wp.customize('secondary-color').set('#202426');
					wp.customize('header-height').set('120');
					wp.customize('header-bgcolor').set('transparent');
					wp.customize('menu-bgcolor').set('transparent');
					wp.customize('main-menu-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '16px',
						'line-height'		: '20px',
						'letter-spacing'	: '0.5px',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('header-box-title-typography').set(header_box_title_typography);
					wp.customize('header-box-content-typography').set(header_box_content_typography);
					wp.customize('header-search').set(true);
					wp.customize('sticky-header-bgcolor').set('white');
					wp.customize('main-menu-active-color','global');
					wp.customize('main-menu-sticky-color').set('#09162a');
					wp.customize('preheader-enable').set(false);
					wp.customize('logo-height').set('55');
					wp.customize('facebook').set('#');
					wp.customize('twitter').set('#');
					wp.customize('instagram').set('#');
					wp.customize('youtube').set('#');
					wp.customize('header-btn-text').set('Get a Quote');
					wp.customize('header-btn-url').set('#');
					wp.customize('preheader-bgcolor').set(preheader_bgcolor);
					wp.customize('preheader-text-color').set(preheader_text_color);
					wp.customize('titlebar-height').set('500');
					wp.customize('titlebar-bgcolor').set('transparent');
					wp.customize('titlebar-background').set({
						'background-color'			: '#f6f6f6',
						'background-repeat'			: 'no-repeat',
						'background-position'		: 'center center',
						'background-size'			: 'cover',
						'background-attachment'		: 'scroll',
						'background-image'			: thsn_admin_js_variables.theme_path + '/images/title-bg.jpg',
					});
					wp.customize('titlebar-heading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '700',
						'font-size'			: '46px',
						'line-height'		: '52px',
						'letter-spacing'	: '-1px',
						'color'				: '#fff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('titlebar-subheading-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '0px',
						'color'				: '#fff',
						'text-transform'	: 'none',
						'font-backup'		: '',
					});
					wp.customize('titlebar-breadcrumb-typography').set({
						'font-family'		: 'Rajdhani',
						'variant'			: '600',
						'font-size'			: '14px',
						'line-height'		: '1.5',
						'letter-spacing'	: '1px',
						'color'				: '#ffffff',
						'text-transform'	: 'uppercase',
						'font-backup'		: '',
					});
					wp.customize('logo').set(thsn_admin_js_variables.theme_path + '/images/logo-white.png');
					wp.customize('sticky-logo').set(thsn_admin_js_variables.theme_path + '/images/logo.png');					
				} 
			});
		}
	);

});	 // window.load