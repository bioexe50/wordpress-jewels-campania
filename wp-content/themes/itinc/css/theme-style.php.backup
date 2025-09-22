<?php
$all_data = thsn_get_all_option_array();
extract($all_data);
$gradient_first = '#ffff00';
$gradient_last  = '#ffff00';
if( function_exists('thsn_get_base_option') ){
	$gradient_colors = thsn_get_base_option('gradient-color');
	$gradient_first  = ( !empty($gradient_colors['first']) ) ? $gradient_colors['first'] : '#ffff00' ;
	$gradient_last   = ( !empty($gradient_colors['last'])  ) ? $gradient_colors['last']  : '#ffff00' ;
}
?>
<?php echo thsn_all_options_values('background'); ?>
<?php echo thsn_all_options_values('typography'); ?>
/* --------------------------------------
*  Custom background color and text color
* ---------------------------------------*/
/* Custom preheader background color */
.thsn-pre-header-wrapper.thsn-bg-color-custom{
	background-color: <?php echo esc_attr($preheader_bgcolor_custom); ?>;
}
/* Custom Header background color */
.thsn-header-wrapper.thsn-bg-color-custom{
	background-color: <?php echo esc_attr($header_background_color); ?>;
}
/* Custom Menu area background color */
.thsn-header-menu-area.thsn-bg-color-custom{
	background-color: <?php echo esc_attr($menu_background_color); ?>;
}
/* sticky-header-background-color */
.thsn-sticky-on.thsn-sticky-bg-color-custom{
	background-color: <?php echo esc_attr($sticky_header_background_color); ?>;
}
/* Custom Menu text color */
.thsn-sticky-on .thsn-navbar div > ul > li > a{
	color: <?php echo esc_attr($main_menu_sticky_color); ?>;
}
<?php if($service_single_image_hide==true){ ?>
/* Hide single image in service */
.single.single-thsn-service .thsn-service-feature-image .thsn-featured-wrapper {
	display: none;
}
.single.single-thsn-service .thsn-service-single-style-1 .thsn-service-single .thsn-entry-content {
	margin-top: -40px;
}
<?php } ?>
/* --------------------------------------
*  A tag
* ---------------------------------------*/
a{
	color: <?php echo esc_attr($link_color['normal']); ?>
}
a:hover{
	color: <?php echo esc_attr($link_color['hover']); ?>
}

/* --------------------------------------
*  site-title
* ---------------------------------------*/
.site-title {
	height: <?php echo esc_attr($header_height); ?>px;
}
.site-title img.thsn-main-logo{
	max-height: <?php echo esc_attr($logo_height); ?>px;
}
.site-title img.thsn-responsive-logo{
	max-height: <?php echo esc_attr($responsive_logo_height); ?>px;
}

/* --------------------------------------
* Titlebar
* ---------------------------------------*/
.thsn-title-bar-content,
.thsn-title-bar-wrapper{
	min-height: <?php echo esc_attr($titlebar_height); ?>px;
}
.thsn-color-globalcolor,
.thsn-globalcolor,
.globalcolor{
	color: <?php echo esc_attr($global_color); ?> ;
}
.thsn-bg-color-globalcolor.thsn-title-bar-wrapper::before,
.themesion-ele-team .themesion-overlay{
	background-color: <?php echo thsn_hex2rgb($global_color, '0.5') ?>;
}

<?php include('elementor-style.php');  ?>

/*========================================== Base Css ==========================================*/

/* --------------------------------------
* Global Color
* ---------------------------------------*/
/*=== Global BG Color ===*/
.wp-block-button__link:hover,
.is-style-outline a.wp-block-button__link:hover,
.thsn-team-form button:hover,
.wp-block-tag-cloud a:hover,
.footer-wrap .widget_tag_cloud a:hover,
.post.sticky .thsn-blog-classic::after,
.nav-links .page-numbers:hover,
.nav-links .page-numbers.current,
.search-results .thsn-top-search-form .search-form button,
.search-no-results .search-no-results-content .search-form button,
input[type=submit]:hover,
.reply a:hover,
.thsn-ourhistory .thsn-ourhistory-right::before,
.site-header .thsn-bg-color-globalcolor,
.site-header .thsn-sticky-on.thsn-sticky-bg-color-globalcolor,
.thsn-btn-style-flat .elementor-button,
.thsn-btn-style-flat.thsn-btn-color-globalcolor .elementor-button,
.thsn-bg-color-globalcolor,
.thsn-footer-section.thsn-bg-color-globalcolor::before,
.error404 .thsn-bg-color-globalcolor .site-content-wrap,
.thsn-bg-color-global,
body .scroll-to-top{
	background-color: <?php echo esc_attr($global_color); ?>;
}

.error404 .thsn-bg-color-globalcolor > .site-content-wrap::before,
.thsn-footer-section.thsn-bg-color-globalcolor.thsn-bg-image-yes::before{
	background-color: <?php echo thsn_hex2rgb($global_color, '0.70') ?>;
}
/*=== Global Text Color ===*/
.thsn-search-results-right .thsn-post-title a:hover,
.thsn-portfolio-single .thsn-portfolio-nav-head,
.thsn-ourhistory .label,
.thsn-pricing-table-box .thsn-ptable-icon,
.thsn-footer-section.thsn-text-color-globalcolor .widget-title,
.thsn-footer-section.thsn-text-color-globalcolor,
.thsn-footer-section.thsn-text-color-globalcolor a,
.thsn-btn-style-text.thsn-btn-color-globalcolor .elementor-button,
.thsn-globalcolor,
.thsn-skincolor,
.post-navigation .nav-links a:hover{
	color: <?php echo esc_attr($global_color); ?>;
}
/*=== Global Border Color ===*/
.post.sticky{
	border-color: <?php echo esc_attr($global_color); ?>;
}
.thsn-btn-style-outline .elementor-button{
	border-color: <?php echo esc_attr($global_color); ?>;
	color: <?php echo esc_attr($global_color); ?>;
}

/* --------------------------------------
* Secondary Color
* ---------------------------------------*/
/*=== Secondary BG Color ===*/
.elementor-widget-button.thsn-btn-bg-color-secondary .elementor-button,
.thsn-bg-color-secondary,
.thsn-bg-color-secondarycolor,
.site-header .thsn-bg-color-secondarycolor,
.site-header .thsn-sticky-on.thsn-sticky-bg-color-secondarycolor,
.wp-block-search .wp-block-search__button,
.error404 .thsn-bg-color-secondarycolor .site-content-wrap,
.thsn-footer-section.thsn-bg-color-secondarycolor::before{
	background-color: <?php echo esc_attr($secondary_color); ?>;
}
.error404 .thsn-bg-color-secondarycolor > .site-content-wrap::before,
.thsn-bg-color-secondarycolor.thsn-title-bar-wrapper::before,
.thsn-footer-section.thsn-bg-color-secondarycolor.thsn-bg-image-yes::before{
	background-color: <?php echo thsn_hex2rgb($secondary_color, '0.90') ?>;
}
/*=== Secondary Text Color ===*/
.thsn-footer-section.thsn-text-color-secondarycolor .widget-title,
.thsn-footer-section.thsn-text-color-secondarycolor,
.thsn-footer-section.thsn-text-color-secondarycolor a,
.thsn-color-secondarycolor,
.thsn-btn-style-text.thsn-btn-color-secondary .elementor-button,
.testcolor{
	color: <?php echo esc_attr($secondary_color); ?>;
}
/*=== Global Border Color ===*/
.testcolor{
	border-color: <?php echo esc_attr($secondary_color); ?>;
}
.thsn-btn-style-outline.thsn-btn-color-secondary .elementor-button{
	border-color: <?php echo esc_attr($secondary_color); ?>;
	color: <?php echo esc_attr($secondary_color); ?>;
}

/* --------------------------------------
*  Gradient Color
* ---------------------------------------*/
/*=== Gradient BG Color ===*/
.elementor-widget-button.thsn-btn-color-gradient .elementor-button,
.thsn-footer-widget-area.thsn-bg-color-gradientcolor,
.thsn-bg-color-gradientcolor,
.thsn-bg-color-gradient{
	background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
}
.thsn-bg-color-gradientcolor.thsn-title-bar-wrapper::before,
.thsn-footer-section.thsn-bg-color-gradientcolor::before{
	background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%) !important;
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> ) !important;
}
.elementor-widget-button.thsn-btn-color-gradient .elementor-button {
	border-image-slice: 1;
	border-image-source: linear-gradient(to left, <?php echo esc_attr($gradient_first); ?>, <?php echo esc_attr($gradient_last); ?>);
}

/* --------------------------------------
*  Blackish Color
* ---------------------------------------*/
/*=== Blackish BG Color ===*/
button,
html input[type=button],
input[type=reset],
input[type=submit],
.thsn-ptable-btn a,
.thsn-btn-style-flat.thsn-btn-color-globalcolor .elementor-button:hover,
.thsn-btn-style-flat.thsn-btn-color-white .elementor-button:hover,
.thsn-btn-style-flat.thsn-btn-color-blackish .elementor-button,
.thsn-bg-color-blackish,
body .scroll-to-top:hover,
.thsn-footer-section.thsn-bg-color-blackish::before,
.error404 .thsn-bg-color-blackish .site-content-wrap,
.site-header .thsn-bg-color-blackish,
.site-header .thsn-sticky-on.thsn-sticky-bg-color-blackish{
	background-color: <?php echo esc_attr($blackish_color); ?>;
}

.error404 .thsn-bg-color-blackish > .site-content-wrap::before{
	background-color: <?php echo thsn_hex2rgb($blackish_color, '0.90') ?>;
}

/*=== Blackish Text Color ===*/
.nav-links .page-numbers,
.thsn-btn-style-outline.thsn-btn-color-white .elementor-button:hover,
.thsn-pricing-table-box .themesion-ptable-price-w,
.thsn-footer-section.thsn-text-color-blackish .widget-title,
.thsn-footer-section.thsn-text-color-blackish a,
.thsn-btn-style-text.thsn-btn-color-blackish .elementor-button,
.thsn-btn-style-flat.thsn-btn-color-light .elementor-button,
.thsn-btn-style-flat.thsn-btn-color-white .elementor-button,
.thsn-color-blackish,
.thsn-text-color-blackish h1,
.thsn-text-color-blackish h2,
.thsn-text-color-blackish h3,
.thsn-text-color-blackish h4,
.thsn-text-color-blackish h5,
.thsn-text-color-blackish h6,
.thsn-blackish{
	color: <?php echo esc_attr($blackish_color); ?>;
}
.thsn-footer-section.thsn-text-color-blackish{
	color: <?php echo thsn_hex2rgb($blackish_bg_color, '0.95') ?>;
}
.thsn-btn-style-outline.thsn-btn-color-blackish .elementor-button{
	border-color: <?php echo esc_attr($blackish_bg_color); ?>;
	color: <?php echo esc_attr($blackish_bg_color); ?>;
}

/* --------------------------------------
*  Light Color
* ---------------------------------------*/
.thsn-btn-style-flat.thsn-btn-color-light .elementor-button,
.thsn-bg-color-light,
.site-header .thsn-sticky-on.thsn-sticky-bg-color-light,
.thsn-footer-section.thsn-bg-color-light::before,
.error404 .thsn-bg-color-light .site-content-wrap{
	background-color: <?php echo esc_attr($light_bg_color); ?>;
}
.thsn-btn-style-text.thsn-btn-color-blackish .elementor-button{
	color: <?php echo esc_attr($light_bg_color); ?>;
}
.thsn-btn-style-outline.thsn-btn-color-light .elementor-button{
	border-color: <?php echo esc_attr($light_bg_color); ?>;
	color: <?php echo esc_attr($light_bg_color); ?>;
}
.error404 .thsn-bg-color-light > .site-content-wrap::before{
	background-color: <?php echo thsn_hex2rgb($light_bg_color, '0.90') ?>;
}
/* --------------------------------------
*  White Color
* ---------------------------------------*/
/*=== Light BG Color ===*/
.thsn-text-color-white .thsn-testimonial-style-1 .themesion-box-desc,
.thsn-elementor-bg-color-light .thsn-testimonial-style-1 .themesion-box-desc,
.thsn-elementor-bg-color-blackish .thsn-testimonial-style-1 .themesion-box-desc,
.thsn-text-color-white .thsn-testimonial-style-4 .themesion-testimonial-wrapper,
.thsn-elementor-bg-color-light .thsn-testimonial-style-4 .themesion-testimonial-wrapper,
.thsn-elementor-bg-color-globalcolor .thsn-testimonial-style-4 .themesion-testimonial-wrapper,
.thsn-elementor-bg-color-globalcolor .thsn-btn-style-flat.thsn-btn-color-blackish .elementor-button:hover,
.thsn-bg-color-white,
.thsn-footer-section.thsn-bg-color-white::before{
	background-color: #fff;
}
.thsn-btn-style-flat.thsn-btn-color-white .elementor-button:hover,
.thsn-color-white,
.thsn-text-color-white .thsn-heading-subheading .thsn-element-title,
.thsn-color-white,
.thsn-text-color-white h1,
.thsn-text-color-white h2,
.thsn-text-color-white h3,
.thsn-text-color-white h4,
.thsn-text-color-white h5,
.thsn-text-color-white h6,
.thsn-white{
	color: <?php echo esc_attr($white_color); ?>;
}
.thsn-text-color-white .thsn-testimonial-style-4 .themesion-testimonial-wrapper::after,
.thsn-elementor-bg-color-light .thsn-testimonial-style-4 .themesion-testimonial-wrapper::after,
.thsn-elementor-bg-color-globalcolor .thsn-testimonial-style-4 .themesion-testimonial-wrapper::after{
	border-color: #fff #fff transparent transparent;
}
.thsn-text-color-white .thsn-testimonial-style-1 .themesion-box-desc::before,
.thsn-elementor-bg-color-light .thsn-testimonial-style-1 .themesion-box-desc::before,
.thsn-elementor-bg-color-blackish .thsn-testimonial-style-1 .themesion-box-desc::before{
	border-right-color: #fff;
}

/*========================================== End Base Css ==========================================*/

/*==========================================THEME SPECIAL===========================================*/

/* --------------------------------------
*  Global color 
* ---------------------------------------*/
.thsn-elementor-bg-color-secondary .thsn-testimonial-style-4 .themesion-box-title,
.thsn-elementor-bg-color-blackish .thsn-testimonial-style-4 .themesion-box-title,
.thsn-sortable-list a.thsn-selected,
.thsn-team-style-3 .themesion-box-team-position,
.thsn-ihbox-style-18 .thsn-ihbox-btn a,
.thsn-ihbox-style-18 .thsn-ihbox-icon-wrapper,
.thsn-ihbox-style-15 .thsn-ihbox-icon-wrapper,
.thsn-blog-meta.thsn-blog-meta-top i,
.thsn-blog-classic .thsn-post-title a:hover,
.thsn-portfolio-style-1 .themesion-box-content .thsn-port-cat a,
.thsn-header-style-2 .thsn-right-box  .thsn-header-contactinfo::after,
.thsn-carousel-navs a:hover,
.thsn-elementor-bg-color-blackish .thsn-accordion-style1 .elementor-accordion .elementor-accordion-item .elementor-tab-title.elementor-active a,
.thsn-testimonial-style-2 .themesion-box-star-ratings,
.thsn-testimonial-style-1 .themesion-box-star-ratings .thsn-active,
.elementor-widget-progress .elementor-title,
.thsn-ihbox-style-14 .thsn-ihbox-icon-wrapper i,
.themesion-ele-fid-style-5-black .themesion-ele-fid-style-5  h4.thsn-fid-inner,
.thsn-tab-content .thsn-tab-content-title i,
.thsn-tabs .thsn-tab-content-inner ul li::after,
.thsn-tabs .thsn-tabs-heading li i,
.thsn-ihbox-style-12,
.thsn-ihbox-style-12 .thsn-element-title,
.thsn-footer-section.thsn-text-color-white a:hover,
.thsn-ihbox-style-10 .thsn-element-title,
.thsn-contact-widget-lines .thsn-contact-widget-line::before,
.thsn-entry-content p strong:hover,
.thsn-subheading-skincolor .thsn-heading-subheading .thsn-element-subtitle,
.themesion-ele-fid-style-5 .thsn-sbox-icon-wrapper,
.thsn-ihbox-style-9 .thsn-ihbox-icon .thsn-ihbox-icon-wrapper,
.thsn-ihbox-style-3 .thsn-ihbox-icon-wrapper i,
.themesion-ele-fid-style-4 .thsn-sbox-icon-wrapper,
.thsn-service-style-4 .thsn-service-icon-wrapper,
.thsn-contact-info li i,
.site-content .widget .wp-block-latest-comments li::before,
.site-content .widget .wp-block-latest-posts li>a::before,
.site-content .widget.widget_categories ul li>a::before,
.site-content .widget.widget_meta ul li a::before,
.site-content .widget.widget_archive ul li>a::before,
.site-content .widget.widget_recent_comments ul li::before,
.site-content .widget.widget_recent_entries ul li>a::before,
.site-content .widget.widget_nav_menu ul li>a::before,
.widget.widget_pages ul li a::before,
.itinc_recent_posts_widget .thsn-rpw-content .thsn-rpw-date a,
blockquote cite,
blockquote small,
.thsn-team-single-style-1 .thsn-team-designation,
.widget .download .item-download i,
.thsn-portfolio-style-2 .thsn-port-cat a,
.thsn-ihbox-style-7 .thsn-ihbox-icon-wrapper,
.elementor-widget .elementor-icon-list-icon i,
.thsn-ihbox-style-6 .thsn-heading-desc,
.themesion-accordion-shadow .elementor-tab-title a span,
.thsn-pricing-table-box .thsn-ptable-line i.fa-check,
.thsn-blog-style-2 .thsn-meta-container i,
.thsn-blog-style-1 .thsn-meta-container .thsn-meta-line i,
.thsn-team-style-2 .themesion-box-content .themesion-box-team-position,
.thsn-testimonial-style-1 .themesion-testimonial-detail,
.thsn-testimonial-style-2 .themesion-testimonial-detail,
.thsn-service-style-3 .thsn-service-cat a,
.thsn-service-style-3 .thsn-service-icon-wrapper,
.thsn-service-style-1 .thsn-service-cat a,
.thsn-service-style-1 .thsn-service-icon-wrapper,
.thsn-service-style-2 .thsn-service-cat a,
.thsn-service-style-2 .thsn-service-icon-wrapper,
.themesion-ele-fid-style-1 .thsn-fid-sub sup,
.themesion-ele-fid-style-2 .thsn-fid-sub sup,
.themesion-ele-fid-style-3 .thsn-sbox-icon-wrapper,
.thsn-ihbox-style-4 .thsn-ihbox-icon-wrapper{
	color: <?php echo esc_attr($global_color); ?>;
}
.elementor-widget .elementor-icon-list-icon svg,
.thsn-ihbox-style-18 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-18 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-15 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-15 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-14 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-14 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-12 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-12 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-9 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-9 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-7 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-7 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-4 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-4 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-3 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-3 .thsn-ihbox-svg-wrapper svg,
.thsn-service-style-4 .thsn-service-icon-wrapper svg,
.thsn-service-style-3 .thsn-service-icon-wrapper svg,
.thsn-service-style-2 .thsn-service-icon-wrapper svg,
.thsn-service-style-1 .thsn-service-icon-wrapper svg,
.themesion-ele-fid-style-4 .thsn-sbox-icon-wrapper svg,
.themesion-ele-fid-style-4 .thsn-fid-svg-wrapper svg,
.themesion-ele-fid-style-3 .thsn-sbox-icon-wrapper svg,
.themesion-ele-fid-style-3 .thsn-fid-svg-wrapper svg,
.thsn-tab-content .thsn-tab-content-title svg,
.thsn-tabs .thsn-tabs-heading li svg,
.thsn-pricing-table-box .thsn-ptable-line svg.e-fas-check,
.thsn-pricing-table-box .thsn-ptable-icon svg,
.thsn-pricing-table-box .thsn-ptable-svg svg{
	fill: <?php echo esc_attr($global_color); ?>;
}

.thsn-sortable-list a.thsn-selected:hover,
.elementor-widget-accordion:not(.thsn-accordion-style1) .elementor-accordion .elementor-tab-title .elementor-accordion-icon.elementor-accordion-icon-left,
.thsn-team-style-3 .thsn-team-social-links li a,
.thsn-team-style-3 .thsn-team-social-links li a:hover,
.thsn-team-style-3 .themesion-box-social-links i.thsn-base-icon-share,
.thsn-elementor-bg-color-blackish .custom-element-style .thsn-service-style-4 a.btn-arrow:hover,
.custom-element-style .elementor-progress-text,
.thsn-ihbox-style-15 .thsn-ihbox-box-number,
.thsn-blog-style-1 .thsn-meta-date-wrapper,
.thsn-blog-classic-inner .thsn-post-date,
.thsn-btn-style-flat.thsn-btn-color-blackish .elementor-button:hover,
.thsn-elementor-bg-color-blackish .thsn-accordion-style1 .elementor-accordion .elementor-accordion-item .elementor-tab-title.elementor-active .elementor-accordion-icon,
.thsn-static-box-style-2 .thsn-contentbox,
.thsn-static-box-style-1 .thsn-contentbox,
.thsn-tab-content .thsn-tab-content-title.thsn-tab-li-active,
.thsn-tabs .thsn-tabs-heading li.thsn-tab-li-active,
.thsn-element-service-style-3.themesion-element-viewtype-carousel .thsn-tbox-right .thsn-stretched-div-right,
.thsn-ihbox-style-9 .thsn-ihbox-svg .thsn-ihbox-svg-wrapper::after,
.thsn-ihbox-style-9 .thsn-ihbox-icon .thsn-ihbox-icon-wrapper::after,
.themesion-ele-fid-style-6 .thsn-fid-svg-wrapper,
.themesion-ele-fid-style-6 .thsn-sbox-icon-wrapper,
.site-footer .widget .widget-title::after,
.thsn-footer-widget .mc4wp-form-fields button,
.progress-bar-style-2  .elementor-progress-wrapper .elementor-progress-text::after,
.progress-bar-style-2 .elementor-progress-wrapper .elementor-progress-text,
.thsn-heading-subheading .thsn-element-subtitle::after,
.thsn-ihbox-style-9:hover .thsn-ihbox-svg .thsn-ihbox-svg-wrapper,
.thsn-ihbox-style-9:hover .thsn-ihbox-icon .thsn-ihbox-icon-wrapper,
.thsn-testimonial-style-2 .thsn-featured-wrapper::after,
.thsn-service-style-4 .btn-arrow,
.thsn-portfolio-style-2 .thsn-link-icon a,
.thsn-ihbox-style-8::after,
body .thsn-blog-meta-top span+span::before,
.thsn-team-single .thsn-team-social-links a:hover,
.site-content .thsn_widget_list_all_posts ul > li.thsn-post-active a,
.site-content .thsn_widget_list_all_posts ul > li a:hover,
.themesion-sidebar .widget.widget_search,
.themesion-sidebar .widget.widget_product_search,
.themesion-sidebar .widget .widget-title::after,
.themesion-sidebar .widget_block .wp-block-group h2::after,
.thsn-pricing-table-featured-col .thsn-ptable-btn a,
.thsn-team-style-2 .thsn-team-social-links li a,
.thsn-team-style-1 .thsn-team-social-links li a:hover,
.thsn-team-style-1 .themesion-box-social-links i.thsn-base-icon-share,
.thsn-ihbox-style-2 .thsn-ihbox-box-number{
	background-color: <?php echo esc_attr($global_color); ?>;
}
.thsn-blog-style-1 .post-item,
.thsn-service-style-1 .themesion-post-item,
.thsn-ihbox-style-3 {
	border-bottom-color: <?php echo esc_attr($global_color); ?>;
}
.thsn-pricing-table-featured-col .thsn-pricing-table-box{
	border-top-color: <?php echo esc_attr($global_color); ?>;
}
.thsn-carousel-navs a:hover,
.thsn-spc-blockquote blockquote{
	border-color: <?php echo esc_attr($global_color); ?>;
}
.thsn-tabs .thsn-tabs-heading li.thsn-tab-li-active::after{
	border-color: <?php echo esc_attr($global_color); ?> transparent transparent transparent;
}

/* --------------------------------------
* Secondary color
* ---------------------------------------*/
.themesion-sidebar .widget_search .wp-block-search__label::after,
.thsn-ihbox-style-18:hover,
.thsn-team-style-3 .themesion-box-social-links i.thsn-base-icon-share:hover,
.thsn-footer-social-area ul li a:hover,
.thsn-subheading-secondarycolor .thsn-heading-subheading .thsn-element-subtitle::after,
.themesion-ele-fid-style-5-black .themesion-ele-fid-style-5 .thsn-fld-contents,
.elementor-progress-text,
.thsn-service-style-4 a.btn-arrow:hover,
.thsn-bg-color-globalcolor .elementor-progress-text,
.thsn-ptable-btn a:hover,
.thsn-ihbox-style-8::before,
.thsn-team-single-style-1 .thsn-team-summary,
.thsn-ihbox-style-13::before,
.themesion-sidebar .widget.single-service-contact,
div.widget .single-service-contact-inner,
.progress-bar-style-2 .elementor-progress-wrapper,
.single-thsn-service .themesion-sidebar .widget:last-child,
.themesion-sidebar .widget.thsn_widget_list_all_posts,
div.widget.thsn_widget_list_all_posts,
.widget.widget_product_search .woocommerce-product-search button,
.widget.widget_search .search-form button{
	background-color: <?php echo esc_attr($secondary_color); ?>;
}

.thsn-elementor-bg-color-globalcolor .thsn-testimonial-style-1 .themesion-testimonial-detail,
.thsn-subheading-secondarycolor .thsn-heading-subheading .thsn-element-subtitle,
.thsn-ihbox-style-2 .thsn-ihbox-icon-wrapper i,
/* .progress-bar-style-2.elementor-widget-progress .elementor-progress-percentage, */
.progress-bar-style-2.elementor-widget-progress .elementor-title,
.thsn-ihbox-style-10 .thsn-element-title sup,
.themesion-ele-fid-style-5 .thsn-fid-title,
.thsn-testimonial-style-2 .themesion-box-content::after,
.thsn-ihbox-style-8 .thsn-ihbox-btn a:hover,
.thsn-entry-content p strong{
	color: <?php echo esc_attr($secondary_color); ?>;
}
.thsn-ihbox-style-2 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-2 .thsn-ihbox-svg-wrapper svg{
	fill: <?php echo esc_attr($secondary_color); ?>;
}
.thsn-pricing-table-box .thsn-pricing-table-wrap{
	border-top-color: <?php echo esc_attr($secondary_color); ?>;
}

/* --------------------------------------
* Blackish color
* ---------------------------------------*/
/* ITinc Special */
.thsn-elementor-bg-color-globalcolor .thsn-btn-style-flat.thsn-btn-color-blackish .elementor-button:hover,
.thsn-ihbox-style-17 .thsn-element-title,
.thsn-ihbox-style-17 .thsn-ihbox-icon-wrapper i,
.thsn-testimonial-style-4 .themesion-box-title,
.custom-element-style .elementor-widget-progress .elementor-title,
.thsn-elementor-bg-color-white .thsn-ihbox-style-9 .thsn-ihbox-box .thsn-element-title,
.elementor-column.thsn-elementor-bg-color-blackish .thsn-btn-style-flat.thsn-btn-color-globalcolor .elementor-button:hover,
.themesion-ele-fid-style-2 .thsn-fid-title,
.thsn-ihbox-style-13 .thsn-element-title,
.thsn-ihbox-style-1 .thsn-ihbox-icon-wrapper,
.thsn-ihbox-style-1.thsn-ihbox .thsn-element-title,
.elementor-widget-accordion:not(.thsn-accordion-style1) .elementor-accordion .elementor-tab-title a,
.thsn-elementor-bg-color-globalcolor .thsn-heading-subheading h4.thsn-element-subtitle,
.thsn-ihbox-style-13 .thsn-element-heading,
.thsn-tab-content .thsn-tab-content-title,
.thsn-tabs .thsn-tabs-heading li span,
.thsn-tabs .thsn-tab-content-inner ul li,
.site-footer.thsn-text-color-white .widget_tag_cloud a,
.search-form .search-submit::after,
.thsn-elementor-bg-color-globalcolor .thsn-ihbox-style-4 .thsn-ihbox-icon-wrapper,
.thsn-elementor-bg-color-gradient .thsn-ihbox-style-4 .thsn-ihbox-icon-wrapper,
.thsn-elementor-bg-color-globalcolor .thsn-ihbox-style-7 .thsn-ihbox-icon-wrapper,
.thsn-elementor-bg-color-gradient .thsn-ihbox-style-7 .thsn-ihbox-icon-wrapper,
.thsn-elementor-bg-color-globalcolor .thsn-ihbox-style-9 .thsn-ihbox-icon .thsn-ihbox-icon-wrapper,
.thsn-elementor-bg-color-gradient .thsn-ihbox-style-9 .thsn-ihbox-icon .thsn-ihbox-icon-wrapper,
.thsn-elementor-bg-color-globalcolor .thsn-ihbox-style-12 .thsn-element-title,
.thsn-elementor-bg-color-gradient .thsn-ihbox-style-12 .thsn-element-title,
.thsn-elementor-bg-color-globalcolor .themesion-ele-fid-style-1 .thsn-fid-sub sup,
.thsn-elementor-bg-color-gradient .themesion-ele-fid-style-1 .thsn-fid-sub sup,
.thsn-elementor-bg-color-globalcolor .themesion-ele-fid-style-3 .thsn-fid-title,
.thsn-elementor-bg-color-gradient .themesion-ele-fid-style-3 .thsn-fid-title{
	color: <?php echo esc_attr($blackish_color); ?>;
}

.thsn-ihbox-style-17 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-17 .thsn-ihbox-svg-wrapper svg,
.thsn-ihbox-style-1 .thsn-ihbox-icon-wrapper svg,
.thsn-ihbox-style-1 .thsn-ihbox-svg-wrapper svg{
	fill: <?php echo esc_attr($blackish_color); ?>;
}
.thsn-elementor-bg-color-white .thsn-ihbox-style-9 .thsn-ihbox-svg-wrapper,
.thsn-elementor-bg-color-white .thsn-ihbox-style-9 .thsn-ihbox-icon-wrapper,
.thsn-ihbox-style-13 .thsn-ihbox-svg-wrapper,
.thsn-ihbox-style-13 .thsn-ihbox-icon-wrapper,
.elementor-progress-percentage,
.thsn-form-style-2 input[type=submit]:hover{
	background-color: <?php echo esc_attr($blackish_color); ?>;
} 
.test{
	border-left-color:  <?php echo thsn_hex2rgb($blackish_color, '0.15') ?>;
}

/* --------------------------------------
* Light color
* ---------------------------------------*/
.thsn-testimonial-style-1 .themesion-box-desc,
.thsn-testimonial-style-4 .themesion-testimonial-wrapper,
.thsn-ihbox-style-18 .thsn-ihbox-svg-wrapper,
.thsn-ihbox-style-18 .thsn-ihbox-icon-wrapper,
.thsn-ihbox-style-16 .thsn-ihbox-svg-wrapper,
.thsn-ihbox-style-16 .thsn-ihbox-icon-wrapper,
.thsn-ihbox-style-11::after,
.thsn-ihbox-style-3 .thsn-ihbox-svg-wrapper,
.thsn-ihbox-style-3 .thsn-ihbox-icon-wrapper,
.thsn-ihbox-style-5 .thsn-ihbox-svg-wrapper,
.thsn-ihbox-style-5 .thsn-ihbox-icon-wrapper,
input[type="number"], input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="url"], input[type="search"], textarea,
.test-bg-color,
.themesion-ele-fid-style-4 .thsn-fid-svg-wrapper,
.themesion-ele-fid-style-4 .thsn-sbox-icon-wrapper{
	background-color: <?php echo esc_attr($light_bg_color); ?>;
}
.test-bg-color{
	color: <?php echo esc_attr($light_bg_color); ?>;
}
.thsn-testimonial-style-4 .themesion-testimonial-wrapper::after{
	border-color: <?php echo esc_attr($light_bg_color); ?> <?php echo esc_attr($light_bg_color); ?> transparent transparent;
}
.thsn-testimonial-style-1 .themesion-box-desc::before{
	border-right-color: <?php echo esc_attr($light_bg_color); ?>;
}

/* --------------------------------------
* Gradient color 
* ---------------------------------------*/
.testbg{
	background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
}

/*====================================  woocommerce  ====================================*/
.woocommerce-info, .woocommerce-message{
	border-top-color: <?php echo esc_attr($global_color); ?>;
}
.woocommerce-info::before,
.woocommerce ul.cart_list li ins,
.woocommerce ul.product_list_widget li ins{
	color: <?php echo esc_attr($global_color); ?>;
}
.single-product .entry-summary .product_meta .posted_in,
.single-product .entry-summary .product_meta .sku_wrapper{
	color: <?php echo esc_attr($blackish_bg_color); ?>;
}
.woocommerce-product-search [type=submit],
.woocommerce .woocommerce-form-login .woocommerce-form-login__submit:hover,
.woocommerce .woocommerce-error .button:hover,
.woocommerce .woocommerce-info .button:hover,
.woocommerce .woocommerce-message .button:hover,
.woocommerce-page .woocommerce-error .button:hover,
.woocommerce-page .woocommerce-info .button:hover,
.woocommerce-page .woocommerce-message .button:hover,
.woocommerce-form-coupon button[type=submit]:hover,
.woocommerce #payment #place_order,
.woocommerce-page #payment #place_order,
.woocommerce #review_form #respond .form-submit input,
.woocommerce .woocommerce-error .button:hover,
.woocommerce .woocommerce-info .button:hover,
.woocommerce .woocommerce-message .button:hover,
.woocommerce-page .woocommerce-error .button:hover,
.woocommerce-page .woocommerce-info .button:hover,
.woocommerce-page .woocommerce-message .button:hover,
.woocommerce nav.woocommerce-pagination ul li a:hover,
.woocommerce nav.woocommerce-pagination ul li span.current,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.woocommerce .widget_price_filter .ui-slider-horizontal .ui-slider-range,
.woocommerce .widget_shopping_cart .buttons a:not(.wcppec-cart-widget-button),
.woocommerce.widget_shopping_cart .buttons a:not(.wcppec-cart-widget-button),
.woocommerce .widget_price_filter .price_slider_amount .button,
.woocommerce .cart .button,
.woocommerce .cart input.button,
#add_payment_method .wc-proceed-to-checkout a.checkout-button,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
.woocommerce-checkout .wc-proceed-to-checkout a.checkout-button,
.woocommerce div.product form.cart .button,
.woocommerce div.product .woocommerce-tabs ul.tabs li a,
.woocommerce ul.products li.product .button{
	background-color: <?php echo esc_attr($global_color); ?>;
}
.woocommerce .woocommerce-form-login .woocommerce-form-login__submit,
.widget_product_categories ul li .count,
.woocommerce-form-coupon button[type=submit],
.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
.woocommerce .widget_price_filter .price_slider_amount .button:hover,
.woocommerce #review_form #respond .form-submit input:hover,
.woocommerce .woocommerce-error .button,
.woocommerce .woocommerce-info .button,
.woocommerce .woocommerce-message .button,
.woocommerce-page .woocommerce-error .button,
.woocommerce-page .woocommerce-info .button,
.woocommerce-page .woocommerce-message .button,
.woocommerce .woocommerce-error .button:hover,
.woocommerce .woocommerce-info .button:hover,
.woocommerce .woocommerce-message .button:hover,
.woocommerce-page .woocommerce-error .button:hover,
.woocommerce-page .woocommerce-info .button:hover,
.woocommerce-page .woocommerce-message .button:hover,
.woocommerce .cart .button:hover,
.woocommerce .cart input.button:hover,
#add_payment_method .wc-proceed-to-checkout a.checkout-button:hover,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
.woocommerce-checkout .wc-proceed-to-checkout a.checkout-button:hover,
.woocommerce div.product form.cart .button:hover,
.woocommerce ul.products li.product .button:hover{
	background-color: <?php echo esc_attr($blackish_bg_color); ?>;
}

.woocommerce-info,
.woocommerce-message {
	border-top-color: <?php echo esc_attr($global_color); ?>;
}

/*====================================  End Dynamic color  ====================================*/

/* * * * *  MENU AND BREAKPOINT CSS  * * * * * */
/*====================================  Max Width for dynamic breakpoint  ====================================*/
@media (max-width: <?php echo esc_attr($responsive_breakpoint); ?>px){

	.thsn-header-style-4 .thsn-header-top-area > .container > .d-flex,
	.thsn-header-top-area > .container{
		position: relative;
	}
	.thsn-header-info-inner,
	.something{
		display: none;
	}
	.navbar-expand-lg .navbar-nav{
		-ms-flex-direction: unset !important;
		flex-direction: unset !important;
	}
	.thsn-header-menu-area-inner,
	.thsn-navbar{
		display: block !important;
	}
	.nav-menu-toggle{
		display: block;
		position: absolute;
		right: 0px;
		top: 50%;
		-webkit-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
		transform: translateY(-50%);
		background-color: transparent;
		padding: 0;
		font-size: 35px;
		line-height: 35px;
		color: #2c2c2c;
		width: 40px;
	}
	.thsn-navbar > div{
		background-color: #fff;
	}
	.sub-menu{
		display: none;
	}
	.thsn-header-menu-area-wrapper{
		min-height: auto !important;
	}
	.closepanel{
		position: absolute;
		z-index: 99;
		right: 35px;
		top: 25px;
		display: block;
		width: 30px;
		height: 30px;
		line-height: 30px;
		border-radius: 50%;
		text-align: center;
		cursor: pointer;
		font-size: 35px;
		color: #fff;
	}
	.admin-bar .closepanel{
		top: 45px;
	}

	/*=== Responsive menu ===*/
	.thsn-navbar > div {
		background-color: #fff;
		position: fixed;
		top: 0;
		right: 0;
		z-index: 1000;
		width: 300px;
		height: 100%;
		padding: 0;
		display: block;
		background-color: #222;
		-webkit-transition: transform 0.4s ease;
		transition: transform 0.4s ease;
		-webkit-transform: translateX(400px);
		-ms-transform: translateX(400px);
		transform: translateX(400px);
		-webkit-backface-visibility: hidden;
		backface-visibility: hidden;
		visibility: hidden;
		opacity: 0
	}
	.thsn-navbar > div.active {
		-webkit-transform: translateX(0);
		-ms-transform: translateX(0);
		transform: translateX(0);
		visibility: visible;
		opacity: 1;
		overflow-y: scroll;
	}
	.thsn-navbar > div > ul{
		padding: 90px 0;
	}
	.thsn-navbar > div > ul li a {
		color: #fff !important;
		padding: 15px 25px;
		height: auto;
		display: inline-block;
	}
	.thsn-navbar > div > ul ul {
		padding-left: 1em;
		overflow: hidden;
		display: none;
	}
	ul .sub-menu.show,
	ul .children.show {
		display: block;
	}
	.thsn-navbar li{
		position: relative;
	}
	.thsn-navbar ul.menu > li{
		border-bottom: 1px solid rgba(204, 204, 204, 0.10);
	}
	.sub-menu-toggle{
		display: block;
		position: absolute;
		right: 25px;
		top: 15px;
		cursor: pointer;
		color: rgba(255, 255, 255, 0.80);
	}
	.thsn-navbar ul ul{
		background-color: transparent !important;
	}
	.thsn-header-style-5 .thsn-header-content {
		margin: 0 15px;
		position: relative;
	}
	.thsn-header-style-5 .thsn-right-box .thsn-header-social-wrapper li a{
		color: <?php echo esc_attr($secondary_color); ?>;
	}
	.thsn-header-style-6 .thsn-right-box .thsn-header-social-wrapper ul li a:hover,
	.thsn-header-style-5 .thsn-right-box .thsn-header-social-wrapper li a:hover{
		color: <?php echo esc_attr($global_color); ?>;
	}

	/* Reset Sticky */
	.thsn-header-style-2 .thsn-header-wrapper.thsn-sticky-on,
	.thsn-header-style-1 .thsn-header-wrapper.thsn-sticky-on{
		position: static !important;
		width: auto !important;
	}
	.thsn-header-style-2 .thsn-header-wrapper > .container > .d-flex,
	.thsn-header-style-1 .thsn-header-wrapper > .container > .d-flex{
		position: relative;
	}
	.thsn-header-style-2 .thsn-header-search-btn,
	.thsn-header-style-1 .thsn-header-search-btn {
		position: absolute;
		right: 60px;
	}
	.thsn-header-style-3 .nav-menu-toggle{
		color: <?php echo esc_attr($main_menu_typography['color']); ?>;
	}
	.thsn-header-style-4 .thsn-header-button,
	.thsn-header-style-2 .thsn-right-box,
	.thsn-header-style-4 .thsn-right-box,
	.thsn-header-style-3 .thsn-right-box,
	.thsn-header-style-5 .thsn-right-box,
	.thsn-header-style-6 .thsn-right-box,
	.thsn-header-style-1 .thsn-right-box{
		display: none;
	}
	.thsn-header-style-5 .thsn-menu-inner{
		display: none;
	}
	.thsn-header-style-6 .container > .d-flex {
		position: relative;
	}
	.thsn-mobile-search .thsn-header-search-btn a,
	.thsn-header-style-6 .nav-menu-toggle {
		color: #ffffff;
	}
	.thsn-header-style-4 .thsn-navbar div > ul > li > a {
		margin: 0 0px;
	}
	.thsn-mobile-search{
		display: block;
	}
	.thsn-mobile-search .thsn-header-search-btn{
		display: block;
		position: absolute;
		right: 60px;
		top: 50%;
		-webkit-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
		transform: translateY(-50%);
		font-size: 22px;
	}
	/*=== Responsive Logo ===*/
	.thsn-responsive-logo-yes .thsn-sticky-logo,
	.thsn-responsive-logo-yes .thsn-main-logo{
		display: none;
	}
	.thsn-responsive-logo-yes .thsn-responsive-logo{
		display: inline-block;
	}
	/*=== Responsive header background color ===*/
	.thsn-responsive-header-bgcolor-globalcolor .thsn-header-wrapper{
		background-color: <?php echo esc_attr($global_color); ?> !important;
	}
	.thsn-responsive-header-bgcolor-white .thsn-header-wrapper{
		background-color: #fff !important;
	}
	.thsn-responsive-header-bgcolor-blackish .thsn-header-wrapper{
		background-color: #222 !important;
	}
	.thsn-cart-wrapper{
		display: none !important
	}
}

/*====================================  End Max Break Point  ====================================*/
/*====================================  Min Width for dynamic breakpoint  ====================================*/
@media (min-width: <?php echo esc_attr($responsive_breakpoint+1); ?>px) {
	.thsn-responsive-logo{
		display: none;
	}
	.nav-menu-toggle,
	.something{
		display: none;
	}
	.thsn-sticky-on .site-title img.thsn-main-logo,
	.site-title img.thsn-sticky-logo{
		max-height: <?php echo esc_attr($sticky_logo_height); ?>px;
	}
	.thsn-sticky-on.thsn-header-wrapper{
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
	}
	.thsn-navbar > div > ul > li,
	.thsn-navbar > div > ul > li > a{
		line-height: <?php echo esc_attr($header_height); ?>px !important;
		height: <?php echo esc_attr($header_height); ?>px;
	}
	.thsn-sticky-on .thsn-navbar > div > ul > li,
	.thsn-sticky-on .thsn-navbar > div > ul > li > a,
	.thsn-sticky-on .site-title {
		line-height: <?php echo esc_attr($sticky_header_height); ?>px !important;
		height: <?php echo esc_attr($sticky_header_height); ?>px;
	}
	.thsn-navbar ul > li > ul > li.current-menu-item > a,
	.thsn-navbar ul > li > ul li.current_page_item > a,
	.thsn-navbar ul > li > ul li.current_page_ancestor > a,
	.thsn-navbar > div > ul > li:hover > a,
	.thsn-navbar > div > ul > li.current_page_item > a,
	.thsn-navbar > div > ul > li.current-menu-parent > a {
		color: <?php echo esc_attr($global_color); ?>;
	}
	.thsn-navbar ul > li > ul li.current_page_item > a::before,
	.thsn-navbar ul > li > ul li.current_page_ancestor > a::before,
	.thsn-navbar ul > li > ul li.current_page_parent > a::before{
		background-color: <?php echo esc_attr($global_color); ?>;
	}
	.thsn-navbar ul > li > ul li:hover > a {
		color: #ffffff !important;
	}
	.thsn-navbar > div > ul {
		position: relative;
		z-index: 597;
	}
	.thsn-navbar > div > ul > li {
		float: left;
		min-height: 1px;
		vertical-align: middle;
		position: relative;
	}
	.thsn-navbar > div > ul ul {
		visibility: hidden;
		position: absolute;
		top: 100%;
		left: 0;
		z-index: 598;
	}
	.thsn-navbar ul > li:hover > ul{
		z-index: 600;
	}
	.thsn-navbar > div > ul li ul.thsn-nav-left{
		left: inherit;
		right: 0;
	}
	.thsn-navbar > div > ul li ul ul.thsn-nav-left{
		left: -100%;
		right: 0;
	}	
	.thsn-navbar > div > ul ul li {
		float: none;
	}
	.thsn-navbar > div > ul ul ul {
		top: 0;
		left: 100%;
		width: 190px;
	}
	.thsn-navbar > div > ul ul {
		margin-top: 0;
	}
	.thsn-navbar > div > ul ul li {
		font-weight: normal;
	}
	.thsn-navbar a {
		display: block;
		line-height: 1em;
		text-decoration: none;
	}
	.thsn-navbar > div > ul ul li:hover > a{
		background-color: <?php echo esc_attr($global_color); ?>;
	}

/*=== Custom CSS Styles ===*/
	.thsn-navbar > ul {
		display: inline-block;
	}
	.thsn-navbar::after,
	.thsn-navbar ul::after {
		content: '';
		display: block;
		clear: both;
	}
	.thsn-navbar ul {
		text-transform: uppercase;
	}
	.thsn-navbar ul ul {
		min-width: 270px;
		opacity: 0;
		visibility: hidden;
		-webkit-transition: all 0.3s linear 0s;
		transition: all 0.3s linear 0s;
		box-shadow: 0px 10px 40px rgba(0,0,0,0.20);
		border-top: 3px solid <?php echo esc_attr($global_color); ?>;
	}
	.thsn-navbar ul > li:hover > ul {
		visibility: visible;
		opacity: 1;
	}
	.thsn-navbar ul > li > ul > li > a{
		padding: 15px 30px;
	}
	.thsn-navbar ul > li > ul > li:hover > a{
		padding-left: 40px;
	}
	.thsn-navbar ul > li > ul > li > a::before {
		position: absolute;
		content: '';
		left: 18px;
		top: 24px;
		width: 0px;
		height: 2px;
		background-color: transparent;
		-webkit-transition: all .500s ease-in-out;
		transition: all .500s ease-in-out;
	}
	.thsn-navbar ul > li > ul > li:hover >a::before{
		background-color: rgba(255, 255, 255, 0.50);
		width: 10px;
	}
	.thsn-navbar ul ul a {
		border-bottom: 1px solid rgba(0, 0, 0, 0.10);
		border-top: 0 none;
		line-height: 150%;
		padding: 16px 20px;
	}
	.thsn-navbar ul ul ul {
		border-top: 0 none;
	}
	.thsn-navbar ul ul li {
		position: relative;
	}
	.thsn-navbar ul li.last ul {
		left: auto;
		right: 0;
	}
	.thsn-navbar ul li.last ul ul {
		left: auto;
		right: 99.5%;
	}
	.thsn-navbar div > ul > li > a{
		margin: 0 20px;
	}

	/* Dropdown Menu ( Globalcolor )*/
	.thsn-navbar.thsn-dropdown-active-color-globalcolor ul > li > ul > li.current-menu-item > a,
	.thsn-navbar.thsn-dropdown-active-color-globalcolor ul > li > ul li.current_page_item > a,
	.thsn-navbar.thsn-dropdown-active-color-globalcolor ul > li > ul li.current_page_ancestor > a,
	/* Main Menu ( Globalcolor )*/
	.thsn-navbar.thsn-main-active-color-globalcolor > div > ul > li:hover > a,
	.thsn-navbar.thsn-main-active-color-globalcolor > div > ul > li.current_page_item > a,
	.thsn-navbar.thsn-main-active-color-globalcolor > div > ul >li.current-menu-parent > a{
		color: <?php echo esc_attr($global_color); ?>;
	}
	/* Dropdown Menu ( Secondarycolor )*/
	.thsn-navbar.thsn-dropdown-active-color-secondarycolor ul > li > ul > li.current-menu-item > a,
	.thsn-navbar.thsn-dropdown-active-color-secondarycolor ul > li > ul li.current_page_item > a,
	.thsn-navbar.thsn-dropdown-active-color-secondarycolor ul > li > ul li.current_page_ancestor > a,
	/* Main Menu ( Secondarycolor )*/
	.thsn-navbar.thsn-main-active-color-secondarycolor > div > ul > li:hover > a,
	.thsn-navbar.thsn-main-active-color-secondarycolor > div > ul > li.current_page_item > a,
	.thsn-navbar.thsn-main-active-color-secondarycolor > div > ul >li.current-menu-parent > a{
		color: <?php echo esc_attr($secondary_color); ?>;
	}
	.thsn-header-menu-area .thsn-navbar div > ul > li,
	.thsn-header-menu-area .thsn-navbar div > ul > li > a,
	.thsn-header-menu-area{
		height: 70px;
		line-height: 70px !important;
	}
	.thsn-header-menu-area{
		position: relative;
		z-index: 9;
	}

/*=== thsn-search-cart-box ===*/
	.thsn-search-cart-box .thsn-cart-wrapper a,
	.thsn-search-cart-box .thsn-header-search-btn a{
		font-size: 20px;
	}
	.thsn-search-cart-box{
		display: flex;
		align-items: center;
		position: relative;
	}
	.thsn-search-cart-box > *{
		padding: 0 20px;
		position: relative;
	}
	.thsn-search-cart-box > *:nth-child(2)::after{
		content: '';
		width: 1px;
		height: 30px;
		background-color: <?php echo thsn_hex2rgb($blackish_color, '0.15') ?>;
		position: absolute;
		left: 0px;
		top: 50%;
		-webkit-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
		transform: translateY(-50%);
	}
	.thsn-search-cart-box .thsn-cart-details{
		position: relative;
	}
	.thsn-search-cart-box .thsn-cart-count{
		position: absolute;
		top: -18px;
		left: 4px;
		background-color: <?php echo esc_attr($blackish_color); ?>;
		color: #fff;
		height: 20px;
		line-height: 20px;
		width: 20px;
		text-align: center;
		border-radius: 50%;
		font-size: 12px;
	}

	/*=== thsn-header-style-1 ===*/
	.thsn-header-style-1 .thsn-navbar div > ul > li > a{
		margin: 0 15px;
	}
	.thsn-header-style-1 .thsn-navbar.thsn-bigger-menu div > ul > li > a{
		margin: 0 10px;
	}
	.thsn-header-style-1 .thsn-right-box {
		margin-left: 10px;
		display: flex;
	}
	.thsn-header-style-1 .thsn-logo-menuarea {
		display: -ms-flexbox!important;
		display: flex!important;
		display: -ms-flexbox!important;
		display: flex!important;
		-webkit-flex: 1;
		-ms-flex: 1;
		flex: 1;
		-webkit-box-pack: justify!important;
		-ms-flex-pack: justify!important;
		justify-content: space-between!important;
	}
	.thsn-header-style-1 .thsn-header-button {
		line-height: normal;
	}
	.thsn-header-style-1 .thsn-header-button a{
		color: <?php echo esc_attr($blackish_color); ?>;
		height: 100%;
		display: inline-block;
		padding: 0 60px;
		vertical-align: middle;
		padding-right: 8px;
		font-weight: normal;
		font-size: 16px;
		position: relative;
		border-radius: 6px;
		letter-spacing: 1px;
		-webkit-transition: all .25s ease-in-out;
		transition: all .25s ease-in-out;
	}
	.thsn-header-style-1 .thsn-header-button a::after {
		content: "\e83f";
		font-family: "themesion-base-icons";
		font-size: 45px;
		line-height: 45px;
		top: 3px;
		position: absolute;
		left: 0;
		color: <?php echo esc_attr($global_color); ?>;
		font-weight: normal;
	}
	.thsn-header-style-1 .thsn-header-button a span{
		display: block;
	}
	.thsn-header-style-1 .thsn-header-button .thsn-header-button-text-1{
		font-weight: 700;
		margin-bottom: 5px;
	}
	.thsn-header-style-1 .thsn-header-button{
		line-height: normal;
	}
	.thsn-header-style-1 .thsn-sticky-on .thsn-header-button a{
		color: <?php echo esc_attr($blackish_color); ?>;
	}

	/*=== thsn-header-style-2 ===*/
	.thsn-header-style-2 .thsn-pre-header-wrapper > .container,
	.thsn-header-style-2 .thsn-header-wrapper > .container{
		max-width: none;
		width: auto;
		margin: 0 80px;
	}
	.thsn-header-style-2 .site-branding{
		margin-right: 90px;
	}
	.thsn-header-style-2 .thsn-navbar div > ul > li > a{
		margin: 0 15px;
	}
	.thsn-header-style-2 .thsn-navbar.thsn-bigger-menu div > ul > li > a{
		margin: 0 10px;
	}
	.thsn-header-style-2 .navigation-top{
	margin-left: auto!important;
	}

	.thsn-header-style-2 .thsn-logo-menuarea {
		display: -ms-flexbox!important;
		display: flex!important;
	}
	.thsn-header-style-2 .thsn-header-button {
		line-height: normal;
	}
	.thsn-header-style-2 .thsn-header-button a{
		color:#fff;
		background: <?php echo esc_attr($global_color); ?>;
		position: relative;
		border-radius: 5px;
		padding: 19px 30px;
		display: inline-block;
		-webkit-transition: all .25s ease-in-out;
		transition: all .25s ease-in-out;
	}
	.thsn-header-style-2 .thsn-header-button a::after {
		content: "\e82c";
		font-family: "themesion-base-icons";
		margin-left: 13px;
		font-size: 16px;
		top: 2px;
		position: relative;
	}
	.thsn-header-style-2 .thsn-header-button a:hover{
		background: <?php echo esc_attr($blackish_color); ?>;
	}
	.thsn-header-style-2 .thsn-header-button,
	.thsn-header-style-2 .thsn-right-box{
		line-height: <?php echo esc_attr($header_height); ?>px !important;
		height: <?php echo esc_attr($header_height); ?>px;
	}
	.thsn-header-style-2 .thsn-sticky-on .thsn-header-button,
	.thsn-header-style-2 .thsn-sticky-on .thsn-right-box{
		line-height: <?php echo esc_attr($sticky_header_height); ?>px !important;
		height: <?php echo esc_attr($sticky_header_height); ?>px;
	}

	/*=== .thsn-header-style-3 ===*/

	/*=== thsn-pre-header-wrapper ====*/
	.thsn-header-style-3 .thsn-sticky-on .thsn-pre-header-wrapper{
		height: 0;
		line-height: 0;
		display: none;
	}
	.thsn-header-style-3 .thsn-pre-header-wrapper.thsn-color-white a{
		color: #fff;
	}
	.thsn-header-style-3 .site-branding.thsn-logo-area {
		margin-right: 80px;
	}
	.thsn-header-style-3 .thsn-logo-menuarea{
		display: -ms-flexbox!important;
		display: flex!important;
		-webkit-box-pack: justify!important;
		-ms-flex-pack: justify!important;
		justify-content: space-between!important;
	}
	.thsn-header-style-3 .thsn-right-box{
		margin-left: 10px;
		display: flex;
		align-items: center;
	}
	.thsn-header-style-3 .thsn-right-box{
		line-height: <?php echo esc_attr($header_height); ?>px !important;
		height: <?php echo esc_attr($header_height); ?>px;
	}
	.thsn-header-style-3 .thsn-sticky-on .thsn-right-box{
		line-height: <?php echo esc_attr($sticky_header_height); ?>px !important;
		height: <?php echo esc_attr($sticky_header_height); ?>px;
	}
	.thsn-header-style-3 .thsn-header-button a{
		color: #fff;
		height: 100%;
		display: inline-block;
		padding: 20px 30px;
		position: relative;
		border-radius: 6px;
		background: <?php echo esc_attr($global_color); ?>;
	}
	.thsn-header-style-3 .navigation-top{
		margin-left: auto!important;
	}
	.thsn-header-style-3 .thsn-navbar div > ul > li > a {
		margin: 0 17px;
	}

	<?php if( !empty($main_menu_typography['color']) ){
		?>
		.thsn-header-style-3 .thsn-right-box .thsn-cart-link,
		.thsn-header-style-3  .thsn-header-search-btn a {
			color: <?php echo esc_attr($main_menu_typography['color']); ?>;
		}
		<?php
	}
	?>

	.thsn-header-style-3 .thsn-right-box .thsn-cart-wrapper .thsn-cart-details{
		position: relative;
	}
	.thsn-header-style-3 .thsn-cart-wrapper .thsn-cart-count {
		background-color: #fff;
		color: <?php echo esc_attr($blackish_color); ?>;
	}
	.thsn-header-style-3 .thsn-sticky-on .thsn-cart-wrapper .thsn-cart-count {
		background-color: <?php echo esc_attr($blackish_color); ?>;
		color: #fff;
	}
	.thsn-header-style-3 .thsn-search-cart-box > *:nth-child(2)::after{
		background-color: rgb(255 255 255 / 30%);
	}
	.thsn-header-style-3 .thsn-sticky-on .thsn-search-cart-box > *:nth-child(2)::after{
		background-color: <?php echo thsn_hex2rgb($blackish_color, '0.15') ?>;
	}

	/*** Custom Menu text color ***/
	.thsn-header-style-3 .thsn-sticky-on .thsn-right-box .thsn-cart-link,
	.thsn-header-style-3 .thsn-sticky-on .thsn-header-search-btn a,
	.thsn-header-style-3 .thsn-sticky-on .thsn-navbar div > ul > li > a{
		color: <?php echo esc_attr($main_menu_sticky_color); ?>;
	}
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-globalcolor > div > ul > li.current_page_item > a,
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-globalcolor > div > ul > li.current-menu-parent > a{
		color: <?php echo esc_attr($global_color); ?>;
	}
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-blackish > div > ul > li.current_page_item > a,
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-blackish  > div > ul > li.current-menu-parent > a{
		color: #232323;
	}
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-white > div > ul > li.current_page_item > a,
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-white  > div > ul > li.current-menu-parent > a{
		color: #fff;
	}
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-secondarycolor > div > ul > li.current_page_item > a,
	.thsn-header-style-3 .thsn-navbar.thsn-main-active-color-secondarycolor  > div > ul > li.current-menu-parent > a{
		color: #eee;
	}
	.thsn-header-style-3 .thsn-sticky-on .thsn-navbar > div > ul > li.current_page_item > a,
	.thsn-header-style-3 .thsn-sticky-on .thsn-navbar  > div > ul > li.current-menu-parent > a{
		color: <?php echo esc_attr($global_color); ?>;
	}

	/*==== thsn-header-style-4 ====*/
	.thsn-header-style-4 .thsn-header-menu-area.thsn-sticky-on {
		-webkit-box-shadow: 0 13px 25px -12px rgba(0,0,0,.25);
		-moz-box-shadow: 0 13px 25px -12px rgba(0,0,0,.25);
		box-shadow: 0 13px 25px -12px rgba(0,0,0,.25);
	}
	.thsn-header-style-4 .site-branding{
		position: relative;
		padding-right: 50px;
	}
	.thsn-header-style-4 .site-branding::before{
		content: "";
		position: absolute;
		height: <?php echo esc_attr($header_height); ?>px;
		width: 1000px;;
		background-color: <?php echo esc_attr($light_bg_color); ?>;
		right: 0;
		top: 0;
		z-index: -1;
	}
	.thsn-header-style-4 .thsn-navbar div > ul > li:first-child {
		margin-left: -10px;
	}
	.thsn-header-style-4 .thsn-header-menu-area .thsn-navbar div > ul > li,
	.thsn-header-style-4 .thsn-header-menu-area .thsn-navbar div > ul > li > a, .thsn-header-menu-area {
		height: auto;
		line-height: normal !important;
	}
	.thsn-header-style-4 .thsn-navbar div > ul > li > a {
		padding: 0 26px;
		margin: 25px 13px;
		position: relative;
		line-height: normal !important;
		height: auto;
	}
	.thsn-header-style-4 .thsn-navbar div > ul > li > a::before,
	.thsn-header-style-4 .thsn-header-menu-area .thsn-navbar div > ul > li.current-menu-item > a::before,
	.thsn-header-style-4 .thsn-header-menu-area .thsn-navbar div > ul > li.current-menu-parent > a::before {
		height: 40px;
		background-color: #202426;
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
		content: '';
		z-index: -1;
		border-radius: 3px;
		transform: scaleX(0.5);
		opacity: 0;
		transition: all 500ms ease;
		margin-top: -9px;
	}
	.thsn-header-style-4 .thsn-navbar div > ul > li:hover > a::before,
	.thsn-header-style-4 .thsn-header-menu-area .thsn-navbar div > ul > li.current-menu-item > a::before,
	.thsn-header-style-4 .thsn-header-menu-area .thsn-navbar div > ul > li.current-menu-parent > a::before {
		transform: scaleX(1);
		opacity: 1;
	}
	.thsn-header-style-4 .thsn-header-button a{
		color: #fff;
		height: 100%;
		display: inline-block;
		padding: 0 30px;
		border-radius: 5px;
		line-height: 50px;
		background-color: <?php echo esc_attr($blackish_color); ?>;
		text-transform: uppercase;
		position: relative;
		-webkit-transition: all .25s ease-in-out;
		transition: all .25s ease-in-out;
	}
	.thsn-header-style-4 .thsn-header-button a:hover{
		background-color:  <?php echo esc_attr($global_color); ?>
	}
	.thsn-header-style-4 .thsn-header-info-inner .thsn-header-box-icon i{
		color: <?php echo esc_attr($global_color); ?>;
	}

	<?php if( !empty($main_menu_typography['color']) ){
		?>
		.thsn-header-style-4 .thsn-right-box .thsn-cart-link,
		.thsn-header-style-4 .thsn-header-search-btn a {
			color: <?php echo esc_attr($main_menu_typography['color']); ?>;
		}
		<?php
	}
	?>

	.thsn-header-style-4 .thsn-cart-wrapper .thsn-cart-count {
		background-color: #fff;
		color: <?php echo esc_attr($blackish_color); ?>;
	}
	.thsn-header-style-4 .thsn-search-cart-box > *:nth-child(2)::after{
		background-color: rgb(255 255 255 / 30%);
	}
	.thsn-header-style-4 .thsn-search-cart-box>* {
		padding: 0 25px 0 0px;
	}
	.thsn-header-style-4 .thsn-search-cart-box>*:nth-child(2)::after {
		content: none;
	}

	/*--- .thsn-header-style-5 ---*/
	.thsn-header-style-5 .thsn-pre-header-wrapper .container{
		max-width: none;
		padding: 0;
	}
	.thsn-header-style-5 .site-branding.thsn-logo-area {
		padding-left: 50px;
		padding-right: 50px;
		border-right: 1px solid #eee;
	}
	.thsn-header-style-5 .thsn-logo-menuarea{
		display: -ms-flexbox!important;
		display: flex!important;
		-webkit-box-pack: justify!important;
		-ms-flex-pack: justify!important;
		justify-content: space-between!important;
	}
	.thsn-header-style-5 .thsn-right-box{
		display: flex;
		align-items: center;
	}
	.thsn-header-style-5 .thsn-right-box{
		line-height: <?php echo esc_attr($header_height); ?>px !important;
		height: <?php echo esc_attr($header_height); ?>px;
	}
	.thsn-header-style-5 .thsn-sticky-on .thsn-navbar > div > ul > li > a,
	.thsn-header-style-5 .thsn-sticky-on .thsn-navbar > div > ul > li,
	.thsn-header-style-5 .thsn-sticky-on .thsn-right-box{
		line-height: <?php echo esc_attr($sticky_header_height); ?>px !important;
		height: <?php echo esc_attr($sticky_header_height); ?>px;
	}
	.thsn-header-style-5 .thsn-pre-header-wrapper .thsn-label{
		font-weight: 700;
		text-transform: uppercase;
		margin-right: 15px;
	}
	.thsn-header-style-5 .thsn-pre-header-wrapper .thsn-label i{
		padding-left: 5px;
	}
	.thsn-header-style-5 .thsn-pre-header-wrapper .thsn-contact-info > li::after{
		position: absolute;
		right: 0;
		top: 50%;
		content: '';
		height: 20px;
		width: 1px;
		background-color: #b6bdc4;
		-webkit-transform: translateX(0%) translateY(-50%);
		-ms-transform: translateX(0%) translateY(-50%);
		transform: translateX(0%) translateY(-50%);
	}
	.thsn-header-style-5 .thsn-pre-header-wrapper .thsn-contact-info > li:last-child::after{
		display: none;
	}
	.thsn-header-style-5 .thsn-pre-header-wrapper{
		height: 85px;
		line-height: 85px;
		border-bottom: 1px solid #eee;
		padding: 0 20px; 
		visibility: visible;
		opacity: 1;
		color: #666666;
		-webkit-transition: all 300ms ease;
		transition: all 300ms ease;
	}
	.thsn-header-style-5 .thsn-sticky-on .thsn-pre-header-wrapper{
		height: 0;
		visibility: hidden;
		opacity: 0;	
		border-bottom: 0;
	}
	.thsn-header-style-5 .thsn-pre-header-wrapper .thsn-social-links li{
		padding: 0 5px;
	}
	.thsn-header-style-5 .thsn-menu-topbararea{
		flex: 1;
	}
	.thsn-header-style-5 .site-title {
		height: <?php echo esc_attr($header_height) + 85; ?>px;
	}
	.thsn-header-style-5 .thsn-navbar > div > ul > li, 
	.thsn-header-style-5 .thsn-navbar > div > ul > li > a {
		line-height: <?php echo esc_attr($header_height); ?>px !important;
		height: <?php echo esc_attr($header_height); ?>px;
	}
	.thsn-header-style-5 .thsn-sticky-on .site-title{
		line-height: <?php echo esc_attr($sticky_header_height) ; ?>px !important;
		height: <?php echo esc_attr($sticky_header_height)  ; ?>px;
	}
	.thsn-header-style-5 .thsn-navbar > div > ul > li:first-child > a{
		margin-left: 0;
	}
	.thsn-header-style-5 .thsn-right-box .thsn-header-social-wrapper .thsn-social-links{
		margin: 0;
		padding: 0;
	}
	.thsn-header-style-5 .thsn-right-box .thsn-header-social-wrapper li {
		display: inline-block;
		margin: 0 8px;
	}
	.thsn-header-style-5 .thsn-cart-details{
		position: relative;
	}
	.thsn-header-style-5 .thsn-cart-details .thsn-cart-icon .thsn-base-icon-supermarket-2::before{
		font-weight: 700;
	}
	.thsn-header-style-5 .thsn-cart-details .thsn-cart-count{
		background-color: <?php echo esc_attr($global_color); ?>;
		color: #fff;
	}
	.thsn-header-style-5 .thsn-header-button a{
		background-color:<?php echo esc_attr($secondary_color); ?>;
		color: #fff;
		height: 100%;
		display: inline-block;
		padding: 0px 40px;
		vertical-align: top;
		text-transform: uppercase;
		font-weight: 700;
		font-size: 14px;
		position: relative;
		border-radius: 0px;
		letter-spacing: 1px;
		-webkit-transition: all .25s ease-in-out;
		transition: all .25s ease-in-out;
		line-height: <?php echo esc_attr($header_height); ?>px !important;
		height: <?php echo esc_attr($header_height); ?>px;
	}
	.thsn-header-style-5 .thsn-header-button a::after{
		content: "\e82c";
		font-family: "themesion-base-icons";
		margin-left: 13px;
		font-size: 16px;
		top: 2px;
		position: relative;
	}
	.thsn-header-style-5 .thsn-sticky-on .thsn-header-button a{
		line-height: <?php echo esc_attr($sticky_header_height) ; ?>px !important;
		height: <?php echo esc_attr($sticky_header_height)  ; ?>px;
	}
	.thsn-header-style-5 .thsn-header-button a:hover{
		background-color: <?php echo esc_attr($global_color); ?>;
	}
	.thsn-header-style-5 .thsn-pre-header-wrapper{
		background-color: transparent;
	}
	.thsn-header-style-5 .thsn-menu-inner .navigation-top{
		padding-left: 20px;
	}

	/*=== thsn-header-style-6 ===*/
	.thsn-header-style-6 .thsn-pre-header-wrapper > .container,
	.thsn-header-style-6 .thsn-header-wrapper > .container{
		max-width: none;
		width: auto;
		margin: 0 20px;
		padding: 0;
	}
	.thsn-header-style-6 .site-branding{
		margin-right: 90px;
	}
	.thsn-header-style-6 .thsn-navbar div > ul > li > a{
		margin: 0 15px;
	}
	.thsn-header-style-6 .thsn-navbar.thsn-bigger-menu div > ul > li > a{
		margin: 0 10px;
	}
	.thsn-header-style-6 .navigation-top{
	margin-left: auto!important;
	}
	.thsn-header-style-6 .thsn-logo-menuarea {
		display: -ms-flexbox!important;
		display: flex!important;
	}
	.thsn-header-style-6 .thsn-right-box{
		display: flex;
	}
	.thsn-header-style-6 .thsn-right-box ul.thsn-social-links {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	.thsn-header-style-6 .thsn-right-box ul.thsn-social-links li {
		display: inline-block;
		margin: 0 12px;
	}
	.thsn-header-style-6 .thsn-header-button a{
		color:#fff;
		background: <?php echo esc_attr($global_color); ?>;
		position: relative;
		padding: 20px 30px;
		display: inline-block;
		-webkit-transition: all .25s ease-in-out;
		transition: all .25s ease-in-out;
	}
	.thsn-header-style-6 .thsn-header-button a::after {
		content: "\e82c";
		font-family: "themesion-base-icons";
		margin-left: 13px;
		font-size: 16px;
		top: 2px;
		position: relative;
	}
	.thsn-header-style-6 .thsn-header-button a:hover{
		background: <?php echo esc_attr($blackish_color); ?>;
	}
	.thsn-header-style-6 .thsn-header-button,
	.thsn-header-style-6 .thsn-right-box{
		line-height: <?php echo esc_attr($header_height); ?>px !important;
		height: <?php echo esc_attr($header_height); ?>px;
	}
	.thsn-header-style-6 .thsn-sticky-on .thsn-header-button,
	.thsn-header-style-6 .thsn-sticky-on .thsn-right-box{
		line-height: <?php echo esc_attr($sticky_header_height); ?>px !important;
		height: <?php echo esc_attr($sticky_header_height); ?>px;
	}
	.thsn-header-style-6.site-header .thsn-bg-color-transparent, .thsn-header-style-3 .thsn-header-height-wrapper > .thsn-bg-color-transparent {
		border-bottom: 1px solid rgba(255, 255, 255, 0.13);
	}

	<?php if( !empty($main_menu_typography['color']) ){
		?>
		.thsn-header-style-6 .thsn-header-social-wrapper ul li a,
		.thsn-header-style-6 .thsn-right-box .thsn-cart-link,
		.thsn-header-style-6  .thsn-header-search-btn a {
			color: <?php echo esc_attr($main_menu_typography['color']); ?>;
		}
		<?php
	}
	?>

	.thsn-header-style-6 .thsn-sticky-on .thsn-right-box .thsn-cart-link,
	.thsn-header-style-6 .thsn-sticky-on .thsn-header-search-btn a,
	.thsn-header-style-6 .thsn-sticky-on .thsn-header-social-wrapper ul li a{
		color: <?php echo esc_attr($main_menu_sticky_color); ?>;
	}
	.thsn-header-style-6 .thsn-search-cart-box .thsn-cart-count{
		background: <?php echo esc_attr($global_color); ?>;
		color: #fff;
	}
	.thsn-header-style-6 .thsn-search-cart-box > *:nth-child(2)::after{
		background-color: rgb(255 255 255 / 30%);
	}
	.thsn-header-style-6 .thsn-sticky-on .thsn-search-cart-box > *:nth-child(2)::after{
		background-color: <?php echo thsn_hex2rgb($blackish_color, '0.15') ?>;
	}
}

/*==== thsn-header-style-2 ====*/
.thsn-header-style-2 .thsn-pre-header-wrapper.thsn-bg-color-blackish a{
	color: <?php echo esc_attr($global_color); ?>;
}
.thsn-header-style-2 .thsn-pre-header-wrapper {
	height: 45px;
	line-height: 45px;
}
.thsn-header-style-3 .thsn-title-bar-content{
	padding-top: 180px;
}

/*==== footer ====*/
footer.site-footer.thsn-footer-widget-yes::after{
	background-color: <?php echo esc_attr($global_color); ?>;
}

/*====================================  End Min Break Point  ====================================*/
<?php if( !empty($preheader_responsive) ){ ?>
@media screen and (max-width: <?php echo esc_html($preheader_responsive); ?>px) {
	.thsn-pre-header-wrapper{
		display: none;
	}
}
<?php } ?>
<?php
$footer_column	= thsn_get_base_option('footer-column');
if( $footer_column=='custom' ) :
	$footer_column_1	= thsn_get_base_option('footer-1-col-width');
	$footer_column_2	= thsn_get_base_option('footer-2-col-width');
	$footer_column_3	= thsn_get_base_option('footer-3-col-width');
	$footer_column_4	= thsn_get_base_option('footer-4-col-width');
	?>
	@media screen and (min-width: 992px) {
		<?php if( !empty($footer_column_1) && $footer_column_1!='hide' ) : ?>
		.site-footer .thsn-footer-widget.thsn-footer-widget-col-1{
			-ms-flex: 0 0 <?php echo esc_attr($footer_column_1) ?>%;
			flex: 0 0 <?php echo esc_attr($footer_column_1) ?>%;
			max-width: <?php echo esc_attr($footer_column_1) ?>%;
		}
		<?php endif; ?>
		<?php if( !empty($footer_column_2) && $footer_column_2!='hide' ) : ?>
		.site-footer .thsn-footer-widget.thsn-footer-widget-col-2{
			-ms-flex: 0 0 <?php echo esc_attr($footer_column_2) ?>%;
			flex: 0 0 <?php echo esc_attr($footer_column_2) ?>%;
			max-width: <?php echo esc_attr($footer_column_2) ?>%;
		}
		<?php endif; ?>
		<?php if( !empty($footer_column_3) && $footer_column_3!='hide' ) : ?>
		.site-footer .thsn-footer-widget.thsn-footer-widget-col-3{
			-ms-flex: 0 0 <?php echo esc_attr($footer_column_3) ?>%;
			flex: 0 0 <?php echo esc_attr($footer_column_3) ?>%;
			max-width: <?php echo esc_attr($footer_column_3) ?>%;
		}
		<?php endif; ?>
		<?php if( !empty($footer_column_4) && $footer_column_4!='hide' ) : ?>
		.site-footer .thsn-footer-widget.thsn-footer-widget-col-4{
			-ms-flex: 0 0 <?php echo esc_attr($footer_column_4) ?>%;
			flex: 0 0 <?php echo esc_attr($footer_column_4) ?>%;
			max-width: <?php echo esc_attr($footer_column_4) ?>%;
		}
		<?php endif; ?>
	}
<?php endif; ?>