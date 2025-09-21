<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if( !function_exists('thsn_widgets_init_20') ){
function thsn_widgets_init_20() {
	register_sidebar( array(
		'name'          => esc_attr__( 'Blog Sidebar', 'itinc' ),
		'id'            => 'thsn-sidebar-post',
		'description'   => esc_attr__( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'itinc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_attr__( 'Page Sidebar', 'itinc' ),
		'id'            => 'thsn-sidebar-page',
		'description'   => esc_attr__( 'Add widgets here to appear in your sidebar on pages.', 'itinc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
}
add_action( 'widgets_init', 'thsn_widgets_init_20', 20 );
if( !function_exists('thsn_widgets_init_22') ){
function thsn_widgets_init_22() {
	register_sidebar( array(
		'name'          => esc_attr__( 'Search Results Sidebar', 'itinc' ),
		'id'            => 'thsn-sidebar-search',
		'description'   => esc_attr__( 'Add widgets here to appear on search result pages.', 'itinc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_attr__( 'Footer Row - 1st Column', 'itinc' ),
		'id'            => 'thsn-footer-1',
		'description'   => esc_attr__( 'Add widgets here to appear in your footer.', 'itinc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_attr__( 'Footer Row - 2nd Column', 'itinc' ),
		'id'            => 'thsn-footer-2',
		'description'   => esc_attr__( 'Add widgets here to appear in your footer.', 'itinc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_attr__( 'Footer Row - 3rd Column', 'itinc' ),
		'id'            => 'thsn-footer-3',
		'description'   => esc_attr__( 'Add widgets here to appear in your footer.', 'itinc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_attr__( 'Footer Row - 4th Column', 'itinc' ),
		'id'            => 'thsn-footer-4',
		'description'   => esc_attr__( 'Add widgets here to appear in your footer.', 'itinc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
}
add_action( 'widgets_init', 'thsn_widgets_init_22', 22 );

/**
 * Customizer icon picker
 */
if( !function_exists('itinc_addons_configure_customizer') ){
function itinc_addons_configure_customizer(){
	if( class_exists('Kirki') ){
		/** Kirki icon picker **/
		include( get_template_directory() . '/includes/customizer/themesion-icon-picker/themesion-icon-picker.php' );
	}
}
}
add_action( 'init', 'itinc_addons_configure_customizer' );

/**
 *  Customizer options
 */
if( !function_exists('thsn_configure_customizer') ){
function thsn_configure_customizer(){
	if( class_exists('Kirki') ){
		include( get_template_directory() . '/includes/kirki-config.php' );
	}
}
}
add_action( 'init', 'thsn_configure_customizer', 99 );

/**
 *  Categories Widget - Wrap Post count in a span
 */
add_filter('wp_list_categories', 'thsn_cat_count_span');
if( !function_exists('thsn_cat_count_span') ){
function thsn_cat_count_span($links) {
	if(strpos($links, '<span class="count">') !== false){
		// WooComerce call
		$links = str_replace('<span class="count">(', '<span class="count">', $links);
		$links = str_replace(')</span>', '</span>', $links);
	} else {
		$links = str_replace('</a> (', '</a> <span>', $links);
		$links = str_replace(')', '</span>', $links);

	}
	return $links;
}
}

/**
 *  Archives Widget - Wrap Post count in a span
 */
add_filter('get_archives_link', 'thsn_archive_count_span');
if( !function_exists('thsn_archive_count_span') ){
function thsn_archive_count_span($links) {
	if( substr( trim($links), 0, 8 ) != '<option ' ){
		$links = str_replace('</a>&nbsp;(', '</a> <span>', $links);
		$links = str_replace(')', '</span>', $links);
	}
	return $links;
}
}

/**
 *  Default Enqueue scripts and styles.
 */
if( !function_exists('thsn_theme_gfonts') ){
function thsn_theme_gfonts() {
	$font_families = array();
	$gfont_family  = '';
	include( get_template_directory() . '/includes/customizer-options.php' );
	include( get_template_directory() . '/includes/gfonts-array.php' );
	foreach( $kirki_options_array as $options_key=>$options_val ){
		if( !empty( $options_val['section_fields'] ) ){
			foreach( $options_val['section_fields'] as $key=>$option ){
				if( !empty($option['type']) && $option['type']=='typography' ){
					$font_family = '';
					$value = thsn_get_base_option( $option['settings'] );
					$family = trim($value['font-family']);
					if( substr($family, -1) == ',' ){
						$family = substr($family, 0, -1);
					}
					// Repalce space with + character
					$spaces = substr_count($family, ' ');
					if( $spaces>0 ){
						for ($x = 1; $x <= $spaces; $x++) {
							$family = str_replace( ' ', '+', $family );
						} 
					}
					$variants = $value['variant'];
					if( isset($option['thsn-all-variants']) && $option['thsn-all-variants']==true ){
						$font_family = trim($value['font-family']);
						if( substr($font_family, -1) == ',' ){
							$font_family = substr($font_family, 0, -1);
						}
						if( !empty($gfonts_array[ $font_family ]['variants']) ){
							$variants = implode( ',', $gfonts_array[ $font_family ]['variants'] );
						}
					}
					$font_families[$family][] = $variants;
				}
			}
		}
	}
	if( !empty($font_families) && is_array($font_families) ){
		$x = 1;
		foreach( $font_families as $name=>$var){
			if( !empty($name) ){
				if( $x != 1 ){ $gfont_family .= '|'; }
				$var = array_unique($var);
				$gfont_family .= $name . ':'. implode(',',$var);
			}
			$x++;
		}
		if( !empty($gfont_family) ){
			$query_args = array(
				'family' => $gfont_family,
			);
			$fonts_url = add_query_arg( $query_args, esc_url('https://fonts.googleapis.com/css'), $query_args );
			wp_enqueue_style( 'thsn-all-gfonts', $fonts_url );
		}
	}
}
}
add_action( 'wp_enqueue_scripts', 'thsn_theme_gfonts' );
add_action( 'admin_enqueue_scripts', 'thsn_theme_gfonts' );

/**
 * Specially for Forminator plugin
 */
if( !function_exists('thsn_forminator_plugin_js_correction') ){
function thsn_forminator_plugin_js_correction(){
	$curr_screen = get_current_screen();
	if( !empty($curr_screen->base) && $curr_screen->base == 'customize' ){
		wp_enqueue_script( 'select2-forminator', get_template_directory_uri() . '/js/select2-forminator.min.js' );
	}
}
}
add_action( 'admin_enqueue_scripts', 'thsn_forminator_plugin_js_correction', 99 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
if( !function_exists('thsn_pingback_header') ){
function thsn_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
}
add_action( 'wp_head', 'thsn_pingback_header' );

/**
 * Enqueue scripts and styles.
 */
if( !function_exists('thsn_scripts') ){
function thsn_scripts() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	$min = '';
	if( thsn_get_base_option('min')=='1' ){
		$min = '.min';
	}

	// RTL 
	$rtl = ( is_rtl() ) ? '-rtl' : '' ;

	// Font Awesome base
	if( !wp_style_is( 'elementor-icons-shared-0', 'registered' ) ){
		wp_register_style( 'elementor-icons-shared-0', get_template_directory_uri() . '/libraries/font-awesome/css/fontawesome.min.css' );
	}
	$icon_libraries = thsn_icon_library_list();
	foreach( $icon_libraries as $library_id=>$library_data ){
		if( !wp_style_is( $library_id, 'registered' ) ){
			wp_register_style( $library_id, $library_data['css_path'] );
		}
	}

	// Bootstrap
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/libraries/bootstrap/css/bootstrap'.$rtl.'.min.css' );

	wp_register_script( 'waypoints', get_template_directory_uri() . '/libraries/waypoints/jquery.waypoints.min.js' , array( 'jquery' ) );
	wp_register_style( 'animate-css', get_template_directory_uri() . '/libraries/animate-css/animate.min.css' );

	wp_register_script( 'jquery-circle-progress', get_template_directory_uri() . '/libraries/jquery-circle-progress/circle-progress.min.js', array( 'jquery' ) );
	wp_register_script( 'numinate', get_template_directory_uri() . '/libraries/numinate/numinate.min.js', array( 'jquery' ) );

	wp_register_script( 'owl-carousel', get_template_directory_uri() . '/libraries/owl-carousel/owl.carousel.min.js' , array( 'jquery' ) );
	wp_register_style( 'owl-carousel', get_template_directory_uri() . '/libraries/owl-carousel/assets/owl.carousel.min.css' );
	wp_register_style( 'owl-carousel-theme', get_template_directory_uri() . '/libraries/owl-carousel/assets/owl.theme.default.min.css' );

	if( thsn_get_base_option('load-merged-css')!=true ){
		wp_enqueue_style( 'thsn-core-style', get_template_directory_uri() . '/css/core'.$min.'.css' );
		wp_enqueue_style( 'thsn-theme-style', get_template_directory_uri() . '/css/theme'.$min.'.css' );
	} else {
		wp_enqueue_style( 'thsn-all-style', get_template_directory_uri() . '/css/all'.$min.'.css' );
	}

	// Magnific Popup Lightbox
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/libraries/magnific-popup/jquery.magnific-popup.min.js', array('jquery') );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/libraries/magnific-popup/magnific-popup.css' );

	// jQuery Observe
	wp_enqueue_script( 'jquery-observe', get_template_directory_uri() . '/libraries/jquery-observe/jquery-observe.min.js', array('jquery') );

	// Base icon library
	wp_enqueue_style( 'thsn-base-icons', get_template_directory_uri() . '/libraries/themesion-base-icons/css/themesion-base-icons.css' );
	// Sticky
	if( thsn_get_base_option('sticky-header')==true ){
		wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/libraries/sticky-toolkit/jquery.sticky-kit.min.js' , array('jquery') );
	}
	// Theme base scripts
	wp_enqueue_script( 'thsn-core-script', get_template_directory_uri() . '/js/core'.$min.'.js' , array('jquery') );
	wp_enqueue_script( 'thsn-section-script', get_template_directory_uri() . '/js/section'.$min.'.js', array('jquery', 'thsn-core-script') );
	// Responsive variable
	$js_array = array(
		'responsive' => thsn_get_base_option('responsive-breakpoint'),
	);
	wp_localize_script( 'thsn-core-script', 'thsn_js_variables', $js_array );
	// ballon tooltip
	wp_enqueue_style( 'balloon', get_template_directory_uri() . '/libraries/balloon/balloon.min.css' );
	// Light Slider
	wp_register_script( 'lightslider', get_template_directory_uri() . '/libraries/lightslider/js/lightslider.min.js' , array('jquery') );
	wp_register_style( 'lightslider', get_template_directory_uri() . '/libraries/lightslider/css/lightslider.min.css' );
	// Isotope
	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/libraries/isotope/isotope.pkgd.min.js' , array('jquery') );
	// remove Kirki style
	wp_dequeue_style('kirki-styles');

	/******************** */

	if( function_exists('thsn_auto_css') ){
		// Addons plugin exists
		if( function_exists('is_customize_preview') && !is_customize_preview() ){
			wp_enqueue_style('thsn-dynamic-style', admin_url('admin-ajax.php').'?action=thsn_auto_css');
		} else {
			ob_start();
			include get_template_directory().'/css/theme-style.php'; // Fetching theme-style.php output and store in a variable
			$css    = ob_get_clean();
			if( thsn_get_base_option('load-merged-css')==true ){
				wp_add_inline_style( 'thsn-all-style', $css );
			} else {
				wp_add_inline_style( 'thsn-theme-style', $css );
			}
		}
	} else {
		// Addons plugin not exists
		wp_enqueue_style( 'thsn-dynamic-default-style', get_template_directory_uri() . '/css/dynamic-default-style.css' );
	}
	$min = '';
	if( thsn_get_base_option('min')=='1' ){
		$min = '.min';
	}

	wp_enqueue_style( 'thsn-responsive-style', get_template_directory_uri() . '/css/responsive'.$min.'.css' );

	global $thsn_inline_css;
	if( !empty($thsn_inline_css) ){
		if( function_exists('thsn_minify_css') ){
			$thsn_inline_css = thsn_minify_css( $thsn_inline_css );
		}
		wp_add_inline_style( 'thsn-dynamic-style', trim( $thsn_inline_css ) );
	}

	if( is_page() || is_singular() ){
		if( wp_style_is( 'elementor-post-'.get_the_ID() , 'enqueued' ) ){
			wp_dequeue_style( 'elementor-post-'.get_the_ID() );
			wp_enqueue_style( 'elementor-post-'.get_the_ID() );
		}
	}

	if ( defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		wp_enqueue_script( 'waypoints' );
		wp_enqueue_style( 'animate-css' );

		wp_enqueue_script( 'jquery-circle-progress' );
		wp_enqueue_script( 'numinate' );

		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-carousel' );
		wp_enqueue_style( 'owl-carousel-theme' );

		wp_enqueue_script( 'lightslider' );
		wp_enqueue_style( 'lightslider' );

		$libraries = thsn_icon_library_list();
		foreach($libraries as $library_id => $data){
			wp_enqueue_style( $library_id );
		}
	}

}
}
add_action( 'wp_enqueue_scripts', 'thsn_scripts', 20 );

/**
 * Admin scripts and styles
 */
if( !function_exists('thsn_wp_admin_scripts_styles') ){
function thsn_wp_admin_scripts_styles() {
	wp_register_script( 'thsn-admin-script', get_template_directory_uri() . '/includes/admin-script.js', array('jquery') );
	// Admin variable
	$admin_js_array = array(
		'theme_path' => get_template_directory_uri(),
	);
	wp_localize_script( 'thsn-admin-script', 'thsn_admin_js_variables', $admin_js_array );
	wp_enqueue_style( 'thsn-admin-style', get_template_directory_uri() . '/includes/admin-style.css' );
	wp_enqueue_script( 'thsn-admin-script' );
	wp_enqueue_style( 'wp-editor-classic-layout-styles' );

	// Admin widget view
	wp_enqueue_style( 'thsn-admin-widget-style', get_template_directory_uri() . '/includes/admin-widget.css' );
}
}
add_action( 'admin_enqueue_scripts', 'thsn_wp_admin_scripts_styles' );

/**
 * Enqueue script for custom customize control.
 */
function thsn_customize_enqueue() {
	wp_enqueue_script( 'thsn-customize-script', get_template_directory_uri() . '/includes/customizer-script.js', array( 'jquery', 'customize-controls' ), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'thsn_customize_enqueue' );

/**
 * Elementor correction for customize bug
 */
if( !function_exists('thsn_ele_correction') ){
function thsn_ele_correction() {
	if( function_exists('is_customize_preview') && is_customize_preview() ){
		if( wp_style_is( 'elementor-common', 'enqueued' ) ){
			wp_dequeue_style('elementor-common');
		}
		if( wp_style_is( 'elementor-admin', 'enqueued' ) ){
			wp_dequeue_style('elementor-admin');
		}
	}
}
}
add_action( 'admin_enqueue_scripts', 'thsn_ele_correction', 99 );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since ITinc 1.4
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
if( !function_exists('thsn_widget_tag_cloud_args') ){
function thsn_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';
	return $args;
}
}
add_filter( 'widget_tag_cloud_args', 'thsn_widget_tag_cloud_args' );

/*
 *  Body Tag: Class
 */
if( !function_exists('thsn_add_body_classes') ){
function thsn_add_body_classes($classes) {
	// Widget class
	$widget_class = '';
	// sidebar class
	$sidebar_class = thsn_get_base_option('sidebar-post');
	if( in_array( $sidebar_class, array('left','right') ) ){
		$widget_class = thsn_check_widget_exists('thsn-sidebar-page');
	}
	if( is_page() ){
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-page');
		$page_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
		if( !empty($page_meta) && $page_meta!='global' ){
			$sidebar_class = $page_meta;
		}
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-page');
		}
		if( function_exists('is_woocommerce') && is_woocommerce() ){
			$widget_class = '';
			$sidebar_class = thsn_get_base_option('sidebar-wc-shop');
		}
		// Curved style at slider bottom
		$slider_type	= get_post_meta( get_the_ID(), 'thsn-slider-type', true );
		$curved_style	= get_post_meta( get_the_ID(), 'thsn-slider-curved-style', true );
		if( !empty($slider_type) && $curved_style == true ){
			$classes[] = 'thsn-slider-curved-style';
		}
	} else if ( !is_front_page() && is_home() ) {
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-post');
		$page_for_posts = get_option( 'page_for_posts' );
		$post_meta = get_post_meta( $page_for_posts, 'thsn-sidebar', true );
		if( !empty($post_meta) && $post_meta!='global' ){
			$sidebar_class = $post_meta;
		}
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-post');
		}	
	} else if( function_exists('is_woocommerce') && is_woocommerce() && !is_product() ){
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-wc-shop');
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-wc-shop');
		}
	} else if( function_exists('is_product') && is_product() ){
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-wc-single');
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-wc-single');
		}
	} else if( is_singular() ){
		if( get_post_type()=='thsn-portfolio' ){
			$widget_class = '';
			$sidebar_class = thsn_get_base_option('sidebar-portfolio');
			$post_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
			if( !empty($post_meta) && $post_meta!='global' ){
				$sidebar_class = $post_meta;
			}
			if( in_array( $sidebar_class, array('left','right') ) ){
				$widget_class = thsn_check_widget_exists('thsn-sidebar-portfolio');
			}
		} else if( get_post_type()=='thsn-service' ){
			$widget_class = '';
			$sidebar_class = thsn_get_base_option('sidebar-service');
			$post_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
			if( !empty($post_meta) && $post_meta!='global' ){
				$sidebar_class = $post_meta;
			}
			if( in_array( $sidebar_class, array('left','right') ) ){
				$widget_class = thsn_check_widget_exists('thsn-sidebar-service');
			}
		} else if( get_post_type()=='thsn-team-member' ){
			$widget_class = '';
			$sidebar_class = thsn_get_base_option('sidebar-team-member');
			$post_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
			if( !empty($post_meta) && $post_meta!='global' ){
				$sidebar_class = $post_meta;
			}
			if( in_array( $sidebar_class, array('left','right') ) ){
				$widget_class = thsn_check_widget_exists('thsn-sidebar-team');
			}
		} else if( get_post_type()=='post' ){
			$widget_class = '';
			$sidebar_class = thsn_get_base_option('sidebar-post');
			$post_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
			if( !empty($post_meta) && $post_meta!='global' ){
				$sidebar_class = $post_meta;
			}
			if( in_array( $sidebar_class, array('left','right') ) ){
				$widget_class = thsn_check_widget_exists('thsn-sidebar-post');
			}
		}
	} else if( is_tax('thsn-portfolio-category') || is_post_type_archive('thsn-portfolio')  ){
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-portfolio-category');
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-portfolio-cat');
		}
	} else if( is_tax('thsn-service-category') || is_post_type_archive('thsn-service') ){
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-service-category');
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-service-cat');
		}
	} else if( is_tax('thsn-team-group') || is_post_type_archive('thsn-team-member') ){
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-team-group');
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-team-group');
		}
	} else if( is_search() ){
		$widget_class = '';
		$sidebar_class = thsn_get_base_option('sidebar-search');
		if( in_array( $sidebar_class, array('left','right') ) ){
			$widget_class = thsn_check_widget_exists('thsn-sidebar-search');
		}
	}
	// widget exists class
	if( !empty($widget_class) ){
		$classes[] = 'thsn-sidebar-no';
	} else {
		if( in_array( $sidebar_class, array('left','right') ) ){
			$classes[] = 'thsn-sidebar-exists';
		}
		$classes[] = 'thsn-sidebar-' . $sidebar_class;
	}
	return $classes;
}
}
add_filter('body_class', 'thsn_add_body_classes');

function thsn_update_comment_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = $req ? "aria-required='true'" : '';
	$fields['author'] =
		'<div class="thsn-comment-form-input-wrapper"><p class="thsn-comment-form-input comment-form-author">
			<input id="author" name="author" type="text" placeholder="' . esc_attr__( 'Name', 'itinc' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
		'" size="30" ' . $aria_req . ' />
		<span class="thsn-form-error thsn-error-author">'.esc_html__('This field is required.','itinc').'</span>
		</p>';
	$fields['email'] =
		'<p class="thsn-comment-form-input comment-form-email">
			<input id="email" name="email" type="email" placeholder="' . esc_attr__( 'Email', 'itinc' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
		'" size="30" ' . $aria_req . ' />
		<span class="thsn-form-error thsn-error-email thsn-empty-email">'.esc_html__('This field is required.','itinc').'</span>
		<span class="thsn-form-error thsn-error-email thsn-invalid-email">'.esc_html__('Please enter a valid email address.','itinc').'</span>	
		</p>';
	$fields['url'] =
		'<p class="thsn-comment-form-input comment-form-url">
			<input id="url" name="url" type="url"  placeholder="' . esc_attr__( 'Website', 'itinc' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
		'" size="30" />
			</p></div>';
	return $fields;
}
add_filter( 'comment_form_default_fields', 'thsn_update_comment_fields' );
function thsn_update_comment_textarea_field( $comment_field ) {
	$comment_field =
		'<p class="comment-form-comment">
		<textarea required id="comment" name="comment" placeholder="' . esc_attr__( "Enter your comment here...", 'itinc' ) . '" cols="45" rows="8"></textarea>
		<span class="thsn-form-error thsn-error-author">'.esc_html__('This field is required.','itinc').'</span>
		</p>';
	return $comment_field;
}
add_filter( 'comment_form_field_comment', 'thsn_update_comment_textarea_field' );
// Limit Posts Per Category/Archive Page
add_filter('pre_get_posts', 'thsn_limit_category_posts');
function thsn_limit_category_posts($query){
    if( is_tax( 'thsn-portfolio-category' ) && !empty($query->query['thsn-portfolio-category']) ){
		$count		= thsn_get_base_option('portfolio-cat-count');
        $query->set('posts_per_page', $count);
    } else if( is_tax( 'thsn-team-group' ) && !empty($query->query['thsn-team-group']) ){
		$count		= thsn_get_base_option('team-group-count');
        $query->set('posts_per_page', $count);
	} else if( is_tax( 'thsn-service-category' ) && !empty($query->query['thsn-service-category']) ){
		$count		= thsn_get_base_option('service-cat-count');
        $query->set('posts_per_page', $count);
    }
    return $query;
}

/**
 * Show cart contents / total Ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'thsn_woocommerce_header_add_to_cart_fragment' );
if( !function_exists('thsn_woocommerce_header_add_to_cart_fragment') ){
function thsn_woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	$header_style		= esc_attr(thsn_get_base_option('header-style'));
	$cart_icon_style	= '2';
	if( $header_style == '2' ){
		$cart_icon_style = '1';
	}
	ob_start();
	thsn_cart_icon($cart_icon_style);
	$fragments['.thsn-cart-wrapper'] = ob_get_clean();
	return $fragments;
}
}

/**
 * Elementor core things
 */
include( get_template_directory() . '/includes/elementor-core.php' );

/**
 * Elementor global settings
 */
add_filter( 'admin_init', 'thsn_elementor_global_settings' );
if( !function_exists('thsn_elementor_global_settings') ){
function thsn_elementor_global_settings() {

	if(get_option('thsn_elementor_global_done') === false){

		// change default color
		$default_color = array (
			1 => '',
			2 => '',
			3 => '',
			4 => '',
		);
		update_option('elementor_scheme_color', $default_color );

		// change default typo
		$default_typo = array (
			1 => array (
				'font_family' => '',
				'font_weight' => '',
			),
			2 => array (
				'font_family' => '',
				'font_weight' => '',
			),
			3 => array (
				'font_family' => '',
				'font_weight' => '',
			),
			4 => array (
				'font_family' => '',
				'font_weight' => '',
			),
		);
		update_option('elementor_scheme_typography', $default_typo );

		// Set a flag if the theme activation happened
		update_option('thsn_elementor_global_done', true, '', false);
	}
}
}

/**
 * Defined classes
 */
if( !function_exists('thsn_widget_classes') ){
function thsn_widget_classes(){
	$value = get_option('thsn-widget-classes');
	if( $value != 'yes' ){
		update_option(
			'WCSSC_options',
			array (
				'show_id'			=> false,
				'type'				=> 3,
				'defined_classes'	=> 
				array (
					0 => 'thsn-two-column-menu',
				),
				'show_number'		=> true,
				'show_location'		=> true,
				'show_evenodd'		=> true,
				'fix_widget_params'	=> false,
				'filter_unique'		=> false,
				'translate_classes'	=> false,
				)
		);
		update_option('thsn-widget-classes', 'yes');
	}
}
}
add_action( 'init', 'thsn_widget_classes' );

/**
 *  Inline code generator
 */
if( !function_exists('thsn_inline_css_code_generator') ){
function thsn_inline_css_code_generator(){
	$return		= '';
	$color_css	= '';
	if( is_page() || is_singular() || is_home() ){

		$page_id = get_the_ID();
		if( is_home() ){
			$page_id = get_option( 'page_for_posts');
		}

		// Body background
		$bg_img		= get_post_meta( $page_id, 'thsn-bg-img', true );
		$bg_image	= $bg_color_css = $bg_color_opacity_css = '';

		if( !empty($bg_img) ){
			$img_src			= wp_get_attachment_image_src($bg_img, 'full');
			if( !empty($img_src[0]) ){ $bg_image = $img_src[0]; }
		}

		// Background color and color-opacity
		$bg_color			= get_post_meta( $page_id, 'thsn-bg-color', true );
		$bg_color_opacity	= get_post_meta( $page_id, 'thsn-bg-color-opacity', true );
		if( !empty($bg_color) ){
			$bg_color_css .= 'background-color:' . $bg_color . ' !important;';
		}
		if( !empty($bg_color_opacity) ){
			$bg_color_opacity_css .= 'opacity:' . $bg_color_opacity . ' !important;';
		}

		// Generating CSS for background
		if( !empty($bg_image) ){
			$return .= 'body{background-image:url(\'' . $bg_image . '\') !important;}';
			if( !empty($bg_color_css) ){
				$return .= 'body:before{' . $bg_color_css . $bg_color_opacity_css . '}';
			}

		} else {

			if( !empty($bg_color_css) ){
				$return .= 'body{' . $bg_color_css . '}';
			}

		}

		$titlebar_img = '';
		// Check if Titlebar bg imge is set in page or post
		$titlebar_bg_img	= get_post_meta( $page_id, 'thsn-titlebar-bg-img', true );
		if( !empty($titlebar_bg_img) ){
			$img_src			= wp_get_attachment_image_src($titlebar_bg_img, 'full');
			if( !empty($img_src[0]) ){ $titlebar_img = $img_src[0]; }
			$titlebar_bg_color			= get_post_meta( $page_id, 'thsn-titlebar-bg-color', true );
			$titlebar_bg_color_opacity	= get_post_meta( $page_id, 'thsn-titlebar-bg-color-opacity', true );
			if( !empty($titlebar_bg_color) ){
				$color_css .= 'background-color:' . $titlebar_bg_color . ' !important;';
			}
			if( !empty($titlebar_bg_color_opacity) ){
				$color_css .= 'opacity:' . $titlebar_bg_color_opacity . ' !important;';
			}
		} else {
			// If not than check now if fetaured img as titlebar bg option is enabled or not
			$titlebar_bg_featured = thsn_get_base_option('titlebar-bg-featured');
			if( !empty($titlebar_bg_featured) && is_array($titlebar_bg_featured) ){
				if( ( is_page()							&& in_array( 'page', $titlebar_bg_featured ) ) ||
					( is_singular('post')				&& in_array( 'post', $titlebar_bg_featured ) ) ||
					( is_singular('thsn-portfolio')		&& in_array( 'thsn-portfolio',   $titlebar_bg_featured ) ) ||
					( is_singular('thsn-team-member')	&& in_array( 'thsn-team-member', $titlebar_bg_featured ) ) ||
					( is_singular('thsn-testimonial')	&& in_array( 'thsn-testimonial', $titlebar_bg_featured ) ) ||
					( is_singular('thsn-service')		&& in_array( 'thsn-service',     $titlebar_bg_featured ) )
				){
					if( has_post_thumbnail() ){
						$titlebar_img = get_the_post_thumbnail_url( $page_id , 'full' );
					}
				}
			}
		}
		// Titlebar bg
		if( !empty($titlebar_img) ){
			$return .= '.thsn-title-bar-wrapper{background-image:url(\'' . $titlebar_img . '\') !important;}';
			if( !empty($color_css) ){
				$return .= '.thsn-title-bar-wrapper:before{' . $color_css . '}';
			}
		}
		// Titlebar BG Color
		$titlebar_bg_color	= get_post_meta( $page_id, 'thsn-titlebar-bg-color', true );
		if( !empty($titlebar_bg_color) ){
			$opacity = get_post_meta( $page_id, 'thsn-titlebar-bg-color-opacity', true );
			if( empty($opacity) ){ $opacity = '0.5'; }
			$return .= '.thsn-title-bar-wrapper:after{background-color:' . thsn_hex2rgb($titlebar_bg_color, $opacity ) . ' !important;}';
		}
	}
	if( !empty($return) ){
		thsn_inline_css( $return );
	}
}
}
add_action( 'wp', 'thsn_inline_css_code_generator' );

/**
 * Register a custom menu page.
 */
if( !function_exists('thsn_register_my_custom_menu_page') ){
	function thsn_register_my_custom_menu_page() {
		if( class_exists('Kirki') ){
			add_menu_page(
				esc_attr__( 'Itinc Options', 'itinc' ),
				esc_attr__( 'Itinc Options', 'itinc' ),
				'manage_options',
				esc_url( site_url() . '/wp-admin/customize.php' ),
				'',
				'',
				6
			);
		}
	}
	}
add_action( 'admin_menu', 'thsn_register_my_custom_menu_page' );

/**
 * Widget custom class input
 */
function thsn_widget_custom_class( $widget, $return, $instance ){

	$id		= $widget->get_field_id( 'thsn-widget-class' );
	$name	= $widget->get_field_name( 'thsn-widget-class' );
	$value	= ( !empty($instance['thsn-widget-class']) ) ? $instance['thsn-widget-class'] : '' ;

	$id_image		= $widget->get_field_id( 'thsn-widget-bg-image' );
	$name_image		= $widget->get_field_name( 'thsn-widget-bg-image' );
	$value_image	= ( !empty($instance['thsn-widget-bg-image']) ) ? $instance['thsn-widget-bg-image'] : '' ;

	?>
	<div class="thsn-widget-option thsn-widget-class-wrapper">
		<p><label for="widget-text-2-classes">Custom CSS Class:</label><input type="text" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" class="widefat"></p>
	</div>

	<div class="thsn-widget-option thsn-widget-bg-image-wrapper">
		<p><label for="widget-text-2-classes">Custom Background Image for widget:</label><input type="text" name="<?php echo esc_attr($name_image); ?>" id="<?php echo esc_attr($id_image); ?>" value="<?php echo esc_attr($value_image); ?>" class="widefat"></p>
		<p class="thsn-widget-small-text">NOTE: Add image full path only. The background image size should be <code>500x580</code> pixel.</p>
	</div>

	<?php
}
add_action( 'in_widget_form', 'thsn_widget_custom_class', 10, 3 );

/**
 * Widget custom class store value
 */
function thsn_save_widget_custom_class( $instance, $new_instance, $old_instance, $object ) {

	// ID
	if( isset( $new_instance['ids'] ) ){
		$instance['ids'] = sanitize_text_field( $new_instance['ids'] );
	}

	// Widget Class
	$instance['thsn-widget-class'] = ( !empty( $new_instance['thsn-widget-class'] ) ) ? sanitize_text_field( $new_instance['thsn-widget-class'] ) : '' ;
	
	// Widget Background Image
	$instance['thsn-widget-bg-image'] = ( !empty( $new_instance['thsn-widget-bg-image'] ) ) ? sanitize_text_field( esc_url($new_instance['thsn-widget-bg-image']) ) : '' ;
	
	return $instance;
}
add_filter( 'widget_update_callback', 'thsn_save_widget_custom_class', 10, 4 );


/**
 * Add Class in frontend
 */
function thsn_frontend_class_event($params){
	global $wp_registered_widgets;
	
	$widget_id              = $params[0]['widget_id'];
	$widget_obj             = $wp_registered_widgets[ $widget_id ];
	$widget_num				= $widget_obj['params'][0]['number'];
	$widget_opt				= thsn_get_widget_info( $widget_obj );
	
	// Custom class
	if( !empty($widget_opt[ $widget_num ]['thsn-widget-class']) ){
		$custom_class	= trim($widget_opt[ $widget_num ]['thsn-widget-class']);

		$class						= 'class="'.$custom_class.' '; 
		$params[0]['before_widget']	= str_replace('class="', $class, $params[0]['before_widget']);

	}


	// Background image
	if( !empty($widget_opt[ $widget_num ]['thsn-widget-bg-image']) ){
		$bg_image	= trim($widget_opt[ $widget_num ]['thsn-widget-bg-image']);

		$bg_image_attr	= 'style="background-image:url(\''.$bg_image.'\');" class="'; 
		$params[0]['before_widget']	= str_replace('class="', $bg_image_attr, $params[0]['before_widget']);

	}

	return $params;
}
// add the action
add_action( "dynamic_sidebar_params", "thsn_frontend_class_event" , 10, 1);


/**
 * Get specific widget information
 */
function thsn_get_widget_info($widget_obj){
	global $post;
	$id = ( isset( $post->ID ) ? get_the_ID() : null );
	
	if( isset( $id ) && get_post_meta( $id, '_customize_sidebars' ) ){
		$custom_sidebarcheck = get_post_meta( $id, '_customize_sidebars' );
	}

	$option_name = '';
	if( isset( $widget_obj['callback'][0]->option_name ) ){
		$option_name = $widget_obj['callback'][0]->option_name;
	} else if( isset( $widget_obj['original_callback'][0]->option_name ) ){
		$option_name = $widget_obj['original_callback'][0]->option_name;
	}

	if( isset( $custom_sidebarcheck[0] ) && ( 'yes' === $custom_sidebarcheck[0] ) ){
		$widget_opt = get_option( 'widget_' . $id . '_' . substr( $option_name, 7 ) );
	} else if( $option_name ){
		$widget_opt = get_option( $option_name );
	}

	return $widget_opt;
}

/**
 * Clear Kirki font cache
 */
if( !function_exists('thsn_clear_kirki_font_cache') ){
function thsn_clear_kirki_font_cache(){
	$thsn_theme_version	= get_option('thsn-itinc-theme-version');
	$current_theme			= wp_get_theme();
	$current_theme_version	= $current_theme->Version;
	if( $thsn_theme_version != $current_theme_version ){
		delete_option( 'kirki_downloaded_font_files' );
		delete_transient( 'kirki_remote_url_contents' );
		delete_transient( 'kirki_googlefonts_cache' );
		if( is_dir( WP_CONTENT_DIR . 'fonts' ) ){
			rmdir( WP_CONTENT_DIR . 'fonts' );
		}
	}
}
}
add_action( 'init', 'thsn_clear_kirki_font_cache' );