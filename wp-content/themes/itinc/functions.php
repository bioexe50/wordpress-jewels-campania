<?php
/**
 * ITinc functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 */
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if( !function_exists('thsn_theme_setup') ){
function thsn_theme_setup() {

	/*
	 * Theme translation textdomain
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/itinc
	 */
	load_theme_textdomain( 'itinc', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 *  Image size
	 */
	add_image_size( 'thsn-img-500x700', 500, 700, true ); // For Team Style 1 - Use
	add_image_size( 'thsn-img-700x750', 700, 750, true ); // For ITinc Service Style 3 - Use
	add_image_size( 'thsn-img-600x800', 600, 800, true ); // For ITinc Portfolio Style 1 - Use
	add_image_size( 'thsn-img-600x700', 600, 700, true ); // For ITinc Service - style3 - Use
	add_image_size( 'thsn-img-770x635', 770, 635, true ); // For ITinc Blog - style2 - Use
	add_image_size( 'thsn-img-770x500', 770, 500, true ); // For ITinc Blog - style1 - Use
	add_image_size( 'thsn-img-770x9999', 770, 9999, false ); // For Client Logos  - Use	
	add_image_size( 'thsn-img-770x770', 770, 770, true ); //  - Use	
	add_image_size( 'thsn-img-300x300', 300, 300, true ); //  - Use	
	add_image_size( 'thsn-img-500x560', 500, 560, true ); // For ITinc Team  - Use	
	add_image_size( 'thsn-img-800x256', 800, 256, true ); // For Optimize  - Free

	/*
	 *  Editor style  
	 */
	add_editor_style();

	// Set the default content width.
	$GLOBALS['content_width'] = 847;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'themesion-top'		=> esc_attr__( 'Top Menu', 'itinc' ),
		'themesion-footer'	=> esc_attr__( 'Footer Menu', 'itinc' ),
	) );

		/*
	 * Add WooCommerce support
	 */
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
		'status',
		'chat',
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
}
add_action( 'after_setup_theme', 'thsn_theme_setup' );

/**
 * Favorites
 */
function thsn_elementor_add_to_fav(){
	$fav_added = get_user_meta( get_current_user_id(), 'thsn_add_to_fav', true );
	if( function_exists('is_user_logged_in') && is_user_logged_in() && $fav_added != 'yes' ){
		$data = get_user_meta( get_current_user_id(), 'wp_elementor_editor_user_favorites', true);
		if( is_string($data) ){
			$data = array();
		}
		if( !isset($data['widgets']) ){
			$data['widgets'] = array();
		}
		$existing_widgets = $data['widgets'];
		if( is_array($existing_widgets) ){
			$new_widgets = array(
				'thsn_blog_element',
				'thsn_client_element',
				'thsn_fid_element',
				'thsn_heading',
				'thsn_icon_heading',
				'thsn_multiple_icon_heading',
				'thsn_portfolio_element',
				'thsn_ptable_element',
				'thsn_service_element',
				'thsn_staticbox_element',
				'thsn_tabs_element',
				'thsn_team_element',
				'thsn_testimonial_element',
				'thsn_timeline_element',
			);
			if( !empty($existing_widgets) ){
				// Favorites is not empty
				$existing_widgets = array_merge($new_widgets, $existing_widgets );
			} else {
				// Favorites is empty
				$existing_widgets = $new_widgets;
			}
			$data['widgets'] = $existing_widgets;
			update_user_meta( get_current_user_id(), 'wp_elementor_editor_user_favorites', $data);
		}
		update_user_meta( get_current_user_id(), 'thsn_add_to_fav', 'yes' );
	}
}
add_action( 'init', 'thsn_elementor_add_to_fav' );
add_action( 'admin_init', 'thsn_elementor_add_to_fav' );

/* *** Kirki **** */
require_once get_template_directory().'/includes/kirki/kirki.php';


/* *** Envato Theme Setup Wizard settings **** */
require_once get_template_directory().'/setup/envato_setup_init.php';
// Please don't forgot to change filters tag.
// It must start from your theme's name.
add_filter('itinc_theme_setup_wizard_username', 'itinc_set_theme_setup_wizard_username', 10);
if( ! function_exists('itinc_set_theme_setup_wizard_username') ){
    function itinc_set_theme_setup_wizard_username($themesion){
        return 'themesion';
    }
}

add_filter('itinc_theme_setup_wizard_oauth_script', 'itinc_set_theme_setup_wizard_oauth_script', 10);
if( ! function_exists('itinc_set_theme_setup_wizard_oauth_script') ){
    function itinc_set_theme_setup_wizard_oauth_script($oauth_url){
        return 'https://themesion.com/envato-api/server-script.php';
    }
}

if ( ! defined( 'itinc_theme_version' ) ) {
	define( 'itinc_theme_version', '1.0' );
}

if ( ! class_exists( 'itincThemeManager', false ) ) {
	// includes core theme manager class and default settings.
	require_once( get_template_directory() . '/theme_setup_class.php' );
}


if ( class_exists( 'itincThemeManager', false ) ) {

	class itincThemeManager_custom extends itincThemeManager {

		/**
		 * Holds the current instance of the theme manager
		 *
		 * @var itincThemeManager
		 */
		private static $instance = null;

		/**
		 * @return itincThemeManager
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function start(){

			add_filter('itinc_default_headers', array($this,'default_headers'));
			add_filter('itinc_custom_header_args', array($this,'itinc_custom_header_args'));
			add_filter('itinc_featured_image_options', array($this,'itinc_featured_image_options'));
			add_filter('itinc_page_options', array($this,'itinc_page_options'));
			add_filter( 'woocommerce_show_page_title', array( $this, 'woocommerce_show_page_title' ), 1 );
			add_action( 'woocommerce_before_main_content', array( $this, 'woocommerce_before_main_content' ) );
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'remove_add_to_cart_buttons' ), 1 );
			add_filter( 'loop_shop_columns', array( $this, 'loop_shop_columns' ) );
            add_filter('elementor/widget/render_content', array($this,'elementor_render_content'), 10, 2);

			parent::start();
		}

        public function elementor_render_content( $html, $widget ){
            $settings = $widget->get_settings();
            // this config option is set from theme.json and controlled through the elementor UI
            if(!empty($settings['slider_labels']) && $settings['slider_labels'] == 'labels'){
                // inject our labels into the HTML
                if( preg_match_all('#<figure class="slick-slide-inner">.*alt="([^"]*)".*</figure>#imsU', $html, $matches) ){
                    foreach($matches[0] as $key => $attachment){

                        $image_caption = $matches[1][$key];

                        $image_id = !empty($settings['carousel'][$key]['id']) ? $settings['carousel'][$key]['id'] : false;
                        if($image_id){
                            $image_data = get_post( $image_id );
                            if($image_data) {
                                $image_caption = $image_data->post_excerpt;
                                $image_description = $image_data->post_content;
                            }
                        }
                        $html = str_replace( $attachment, str_replace('<figure class="slick-slide-inner">', '<figure class="slick-slide-inner"><div class="itinc-slider-caption"><div class="inner-content-width"><div><h3>' . esc_html( $image_caption ) . '</h3><div>' . esc_html( $image_description ) . '</div></div></div></div>', $attachment), $html );
                    }
                }
            }
            return $html;
        }

		public function loop_shop_columns(){
			return 3;
		}

		public function remove_add_to_cart_buttons() {
			if( is_product_category() || is_shop()) {
				//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
			}
		}

		public function woocommerce_show_page_title(){
			return false;
		}
		public function woocommerce_before_main_content(){
			$page_id = wc_get_page_id('shop');
			if($page_id) {
				$page = get_post( $page_id );
				if ( $page ) {
					?>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php echo esc_html( $page->post_title ); ?></h1>
                    </header>
					<?php
				}
			}
		}

		public function setup_background() {

			// Set up the WordPress core custom background feature.
			add_theme_support( 'custom-background', apply_filters( 'itinc_custom_background_args', array(
				'default-color' => 'ffffff',
				'default-image' => '',
			) ) );
		}


		public function itinc_page_options($page_options){
			$page_options['title']['options']['show'] = 'Fancy Title';
			$page_options['title']['options']['normal'] = 'Normal Title';

			$page_options['background'] = array(
				'title'   => 'Page Background',
				'options' => array(
					'transparent' => 'Transparent',
					'normal' => 'Bordered',
				),
				'default' => 'transparent',
			);

			return $page_options;
		}


		public function setup_images() {
			parent::setup_images();
			add_image_size( 'itinc_gallery_square', 600, 600, true );
			add_image_size( 'itinc_wide_slider', 1500, 385, true );
			add_image_size( 'itinc_blog-large', 1500, 9999, false );
			set_post_thumbnail_size( 800, 410, true );
		}

		public function excerpt_length() {
			return 70;
		}

		public function itinc_featured_image_options($images){
			return array();
		}
		public function itinc_custom_header_args($headerargs){
		    $headerargs['default-image'] = get_template_directory_uri() . '/images/header2-top-lg.png';
		    return $headerargs;
        }
		public function default_headers($headers){
			$headers['header1'] = array(
				'url'           => '%s/images/header1-bottom-lg.png',
				'thumbnail_url'           => '%s/images/header1-bottom-lg.png',
				'description'   => esc_html__( 'Header', 'itinc' )
			);
			$headers['header2'] = array(
				'url'           => '%s/images/header2-top-lg.png',
				'thumbnail_url'           => '%s/images/header2-top-lg.png',
				'description'   => esc_html__( 'Header', 'itinc' )
			);
			$headers['header3'] = array(
				'url'           => '%s/images/header3-top-sml.png',
				'thumbnail_url'           => '%s/images/header3-top-sml.png',
				'description'   => esc_html__( 'Header', 'itinc' )
			);
			return $headers;
		}

		public function after_setup_theme(){

			parent::after_setup_theme();
		}

		public function itinc_blog_date(){
			if(get_post_type() == 'post') {
				?>
                <div class="blog_date">
                    <span class="day"><?php echo get_the_date( 'j' ); ?></span>
                    <span class="month"><?php echo get_the_date( 'M' ); ?></span>
                    <span class="year"><?php echo get_the_date( 'Y' ); ?></span>
                </div>
				<?php
			}
		}

	}

	require_once get_template_directory().'/setup/envato_setup_init.php';

	itincThemeManager_custom::get_instance()->start();
}
/* **** End of Envato Theme Setup Wizard ***** */

if( !function_exists('thsn_init_calls') ){
function thsn_init_calls() {
	if( is_admin() ){
		// Meta boxes
		include( get_template_directory() . '/includes/meta-boxes.php' );
	}
}
}
add_action( 'init', 'thsn_init_calls' );
include( get_template_directory() . '/includes/core.php' );
// actions
include( get_template_directory() . '/includes/actions.php' );

// Advanced Custom Fields - Fonts Icon Picker
include( get_template_directory() . '/includes/acf/themesion-acf-iconpicker/acf-thsn_fonticonpicker.php' );

/*
 *  Plugins
 */
require_once get_parent_theme_file_path( '/includes/class-tgm-plugin-activation.php' );
add_action( 'tgmpa_register', 'thsn_register_required_plugins' );
if( !function_exists('thsn_register_required_plugins') ){
function thsn_register_required_plugins() {
	$plugins = array(
		array(
			'name'					=> esc_attr('Slider Revolution'),
			'slug'					=> esc_attr('revslider'),
			'source'				=> get_template_directory() . '/includes/plugins/revslider.zip',
			'version'				=> esc_attr('6.7.33'),
		),
		array(
			'name'					=> esc_attr('ITinc Theme Addons'),
			'slug'					=> esc_attr('itinc-addons'),
			'source'				=> get_template_directory() . '/includes/plugins/itinc-addons.zip',
			'required'				=> true,
			'version'				=> esc_attr('4.1'),
		),
		array(
			'name'					=> esc_attr('Elementor Page Builder'),
			'slug'					=> esc_attr('elementor'),
			'required'				=> true,
		),
		array(
			'name'					=> esc_attr('Advanced Custom Fields'),
			'slug'					=> esc_attr('advanced-custom-fields'),
			'required'				=> true,
		),
		array(
			'name'					=> esc_attr('ACF Photo Gallery Field'),
			'slug'					=> esc_attr('navz-photo-gallery'),
			'required'				=> true,
		),
		array(
			'name'					=> esc_attr('Envato Market'),
			'slug'					=> esc_attr('envato-market'),
			'source'				=> get_template_directory() . '/includes/plugins/envato-market.zip',
		),
		array(
			'name'					=> esc_attr('Breadcrumb NavXT'),
			'slug'					=> esc_attr('breadcrumb-navxt'),
		),
		array(
			'name'					=> esc_attr('MailChimp for WordPress'),
			'slug'					=> esc_attr('mailchimp-for-wp'),
		),
		array(
			'name'					=> esc_attr('Contact Form 7'),
			'slug'					=> esc_attr('contact-form-7'),
		),
		array(
			'name'					=> esc_attr('Widget CSS Classes'),
			'slug'					=> esc_attr('widget-css-classes'),
		),
	);
	$config = array(
		'id'           => 'itinc',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);
	tgmpa( $plugins, $config );
}
}

/**
 *  Merlin Message
 */
if( !function_exists('thsn_merlin_message') ){
function thsn_merlin_message() {
	?>
	<div class="thsn-merlin-message-box notice is-dismissible">
		<div class="thsn-merlin-message">
			<div class="thsn-merlin-message-conform">
				<div class="thsn-merlin-message-conform-inner">
					<div class="thsn-merlin-message-conform-i">
						<div class="thsn-merlin-message-conform-col thsn-merlin-message-conform-img">
							<img src="<?php echo get_template_directory_uri() ?>/includes/images/merlin-message.png" />
						</div>
						<div class="thsn-merlin-message-conform-col thsn-merlin-message-conform-text">
							<h3><?php esc_html_e('Are you sure you want to permenently close this wizard?', 'itinc'); ?></h3>
							<p><?php printf( esc_html__('You can start this wizard from %1$s Appearance > ITinc Theme Setup %2$s section', 'itinc') ,thsn_esc_kses('<strong>') ,thsn_esc_kses('</strong>') );  ?></p>
							<div class="thsn-merlin-message-conform-btn">
								<a href="#" class="button button-primary thsn-disable-merlin-message"><?php esc_html_e('Yes close this message', 'itinc'); ?></a>
								&nbsp; &nbsp;
								<a href="#" class="button thsn-disable-merlin-message-cancel"><?php esc_html_e('No, keep this message', 'itinc'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- .thsn-merlin-message-conform -->
			<div class="thsn-merlin-message-inner">
				<div class="thsn-merlin-message-logo">
					<img src="<?php echo get_template_directory_uri() ?>/includes/images/logo.png" />
				</div>
				<div class="thsn-merlin-message-vline">
					<div class="thsn-merlin-message-vline-i"></div>
				</div>
				<div class="thsn-merlin-message-text">
					<h2><?php esc_html_e('ITinc Theme Setup Wizard', 'itinc'); ?></h2>
					<p><?php esc_html_e('This ITinc theme comes with one-click setup wizard. This step-by-step wizard will install all required plugins, install demo content and also import sliders.', 'itinc'); ?></p>
				</div>
				<div class="thsn-merlin-message-btn">
					<div class="thsn-merlin-message-btn-i">
						<a href="<?php echo admin_url( 'themes.php?page=itinc-setup' ); ?>" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e('Start Theme Setup Wizard', 'itinc'); ?></a>
						<div class="thsn-merlin-message-small"><a href="#"><?php esc_html_e('Permanently disable this message', 'itinc'); ?></a></div>
					</div>
				</div>
				<div class="clear clearfix clr"></div>
			</div><!-- .thsn-merlin-message-inner -->
		</div><!-- .thsn-merlin-message -->
	</div><!-- .notice.is-dismissible -->
	<?php
}
}

if( !function_exists('thsn_merlin_fresh_setup_call') ){
function thsn_merlin_fresh_setup_call(){
	$thsn_merlin_all_done = get_option('thsn-merlin-all-done');
	if( empty($thsn_merlin_all_done) ){
		add_action( 'admin_notices', 'thsn_merlin_message' );
	}
	// Widget CSS Classes bug fix
	$wcssc_options = get_option('WCSSC_options');
	if( !empty($wcssc_options) && is_array($wcssc_options) && isset($wcssc_options['type']) ){
		if( $wcssc_options['type'] != 1 ){
			$wcssc_options['type'] = 1 ;
			update_option( 'WCSSC_options', $wcssc_options );
		}
	}
}
}
add_action( 'init', 'thsn_merlin_fresh_setup_call' );

/**
 *  Merlin message disable ajax call
 */
add_action( 'wp_ajax_thsn_remove_merlin_message', 'thsn_remove_merlin_message' );
if( !function_exists('thsn_remove_merlin_message') ){
function thsn_remove_merlin_message() {
	update_option( 'thsn-merlin-all-done', 'yes' );
	echo 'ok';
	wp_die(); // this is required to terminate immediately and return a proper response
}
}

/* Ratings and reviews */
/**
 *  Merlin Message
 */
if( !function_exists('thsn_ratings_message') ){
function thsn_ratings_message() {
	?>
	<div class="thsn-merlin-message-box notice is-dismissible thsn-merlin-ratings-box">
		<div class="thsn-merlin-ratings-box-back-link" style="display:none;"><a href="#"><i class="fa fa-chevron-circle-left"></i> <?php esc_html_e('Back','itinc') ?> </a></div>
		<div class="thsn-merlin-message">
			<!-- Ratings Main Box -->
			<div class="thsn-merlin-message-inner thsn-merlin-ratings-box-main" style="display:block;">
				<div class="thsn-merlin-message-logo">
					<img src="<?php echo get_template_directory_uri() ?>/includes/images/logo.png" />
				</div>
				<div class="thsn-merlin-message-vline">
					<div class="thsn-merlin-message-vline-i"></div>
				</div>
				<div class="thsn-merlin-message-text">
					<h2><?php esc_html_e('Happy with our theme?', 'itinc'); ?></h2>
					<p><?php esc_html_e('We like to know how is your experiance with our theme. If you have any question than you can create ticket on our support site', 'itinc'); ?></p>
				</div>
				<div class="thsn-merlin-message-btn">
					<div class="thsn-merlin-message-btn-1">
						<a href="#" class="button button-primary button-hero load-customize hide-if-no-customize thsn-question-btn"> <i class="fa fa-question-circle"></i> <?php esc_html_e('I have a question or problem', 'itinc'); ?></a>
					</div>
					<div class="thsn-merlin-message-btn-2 thsn-happy-btn-container">
						<a href="#" class="button button-primary button-hero load-customize thsn-happy-btn"> <i class="fa fa-thumbs-o-up"></i> <?php esc_html_e('I am happy with the theme', 'itinc'); ?></a>
					</div>
					<div class="clearfix clear"></div>
					<div class="thsn-merlin-message-small"><a href="#"><?php esc_html_e('Permanently disable this message', 'itinc'); ?></a></div>
				</div>
				<div class="clear clearfix clr"></div>
			</div><!-- .thsn-merlin-message-inner -->
			<!-- Ratings Close Permenetly message -->
			<div class="thsn-merlin-message-conform">
				<div class="thsn-merlin-message-conform-inner">
					<div class="thsn-merlin-message-conform-i">
						<div class="thsn-merlin-message-conform-col thsn-merlin-message-conform-text">
							<h3><?php esc_html_e('Are you sure you want to permenently close this box?', 'itinc'); ?></h3>
							<div class="thsn-merlin-message-conform-btn">
								<a href="#" class="button button-primary thsn-disable-ratings-message"><?php esc_html_e('Yes close this message', 'itinc'); ?></a>
								&nbsp; &nbsp;
								<a href="#" class="button thsn-disable-ratings-message-cancel"><?php esc_html_e('No, keep this message', 'itinc'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- .thsn-merlin-message-conform -->
			<!-- Questions or problem box -->
			<div class="thsn-merlin-message-inner thsn-merlin-ratings-box-questions" style="display:none;">
				<div class="thsn-merlin-message-text">
					<h2><?php esc_html_e('Have any question or problem?', 'itinc'); ?></h2>
					<p><?php printf( esc_html__('You can read our theme documents to get how to work with the theme. Still not solved, than feel free to contact us via our support site at %1$s', 'itinc'), thsn_esc_kses('<a href="' . esc_url('https://pbminfotech.support') . '" target="_blank">' . esc_url('https://pbminfotech.support') . '</a>') ); ?></p>
				</div>
				<div class="thsn-merlin-message-btn">
					<div class="thsn-merlin-message-btn-2 thsn-happy-btn-container thsn-pright-15">
						<a href="<?php echo esc_url('https://itinc-demo.pbminfotech.com/docs/'); ?>" target="_blank" class="button button-primary button-hero load-customize thsn-ratings-doc-btn"> <i class="fa fa-book"></i> <?php esc_html_e('ITinc Theme Documents', 'itinc'); ?></a>
					</div>
					<div class="thsn-merlin-message-btn-2 thsn-happy-btn-container">
						<a href="<?php echo esc_url('https://pbminfotech.support/'); ?>" target="_blank" class="button button-primary button-hero load-customize thsn-ratings-support-btn"> <i class="fa fa-question-circle"></i> <?php esc_html_e('Go to Themesion support site', 'itinc'); ?></a>
					</div>
					<div class="clearfix clear"></div>
				</div>
				<div class="clear clearfix clr"></div>
			</div><!-- .thsn-merlin-message-inner -->
			<!-- 5-star ratings box -->
			<div class="thsn-merlin-message-inner thsn-merlin-ratings-box-ratings" style="display:none;">
				<div class="thsn-merlin-message-text">
					<div class="thsn-merlin-message-arrow-area">
						<h2><?php esc_html_e('Glad to hear you like our theme', 'itinc'); ?></h2>
						<p><?php printf( esc_html__('Thanks for your support. Please provide us 5-star ratings. This will help us a lot.
It just take 1 minute. %1$s', 'itinc'), thsn_esc_kses('<a href="' . esc_url('https://themeforest.net/downloads') . '" target="_blank">'.esc_html__('Click here to go for review now','itinc').'</a>') ); ?></p>
					</div>
				</div>
				<div class="thsn-merlin-5star-right-area">
					<img src="<?php echo get_template_directory_uri(); ?>/includes/images/ratings-steps.png" alt="<?php esc_attr_e( 'Ratings Steps', 'itinc' ); ?>" />
				</div>
				<div class="clear clearfix clr"></div>
			</div><!-- .thsn-merlin-message-inner -->
		</div><!-- .thsn-merlin-message -->
	</div><!-- .notice.is-dismissible -->
	<?php
}
}

if( !function_exists('thsn_ratings_call') ){
function thsn_ratings_call(){
	$show_date = get_option('thsn-ratingsbox-show-date');
	if( empty($show_date) ){
		$nextWeek = time() + (7 * 24 * 60 * 60); // One week..
		$nextWeek = strval( $nextWeek );
		update_option('thsn-ratingsbox-show-date', $nextWeek);
	} else {
		$thsn_merlin_all_done	= get_option('thsn-merlin-all-done');
		$thsn_ratings_done		= get_option('thsn-ratings-done');
		$nextWeek				= get_option('thsn-ratingsbox-show-date');
		$curr_date				= time();
		if( $nextWeek < $curr_date && empty($thsn_ratings_done) && $thsn_merlin_all_done=='yes' ){
			add_action( 'admin_notices', 'thsn_ratings_message' );
		}
	}
}
}
add_action( 'init', 'thsn_ratings_call' );

/**
 *  Ratings message disable ajax call
 */
add_action( 'wp_ajax_thsn_remove_ratings_message', 'thsn_remove_ratings_message' );
if( !function_exists('thsn_remove_ratings_message') ){
function thsn_remove_ratings_message() {
	update_option( 'thsn-ratings-done', 'yes' );
	echo 'ok';
	wp_die(); // this is required to terminate immediately and return a proper response
}
}

/**
 * Kirki changes
 */
if( !function_exists('thsn_kirki_changes') ){
function thsn_kirki_changes(){
	if (!is_customize_preview() ) {
		add_filter( 'kirki_output_inline_styles', '__return_false' );
	}
	add_filter( 'kirki/config', function( $config = array() ) {
		$config['styles_priority'] = 10;
		return $config;
	} );
}
}
add_action( 'init', 'thsn_kirki_changes' );

/* Ratings and reviews */
/**
 *  Merlin Message
 */
if( !function_exists('thsn_ratings_message') ){
	function thsn_ratings_message() {
		?>
		<div class="thsn-merlin-message-box notice is-dismissible thsn-merlin-ratings-box">
			<div class="thsn-merlin-ratings-box-back-link" style="display:none;"><a href="#"><i class="fa fa-chevron-circle-left"></i> <?php esc_html_e('Back','itinc') ?> </a></div>
			<div class="thsn-merlin-message">
				<!-- Ratings Main Box -->
				<div class="thsn-merlin-message-inner thsn-merlin-ratings-box-main" style="display:block;">
					<div class="thsn-merlin-message-logo">
						<img src="<?php echo get_template_directory_uri() ?>/includes/images/logo.png" />
					</div>
					<div class="thsn-merlin-message-vline">
						<div class="thsn-merlin-message-vline-i"></div>
					</div>
					<div class="thsn-merlin-message-text">
						<h2><?php esc_html_e('Happy with our theme?', 'itinc'); ?></h2>
						<p><?php esc_html_e('We like to know how is your experiance with our theme. If you have any question than you can create ticket on our support site', 'itinc'); ?></p>
					</div>
					<div class="thsn-merlin-message-btn">
						<div class="thsn-merlin-message-btn-1">
							<a href="#" class="button button-primary button-hero load-customize hide-if-no-customize thsn-question-btn"> <i class="fa fa-question-circle"></i> <?php esc_html_e('I have a question or problem', 'itinc'); ?></a>
						</div>
						<div class="thsn-merlin-message-btn-2 thsn-happy-btn-container">
							<a href="#" class="button button-primary button-hero load-customize thsn-happy-btn"> <i class="fa fa-thumbs-o-up"></i> <?php esc_html_e('I am happy with the theme', 'itinc'); ?></a>
						</div>
						<div class="clearfix clear"></div>
						<div class="thsn-merlin-message-small"><a href="#"><?php esc_html_e('Permanently disable this message', 'itinc'); ?></a></div>
					</div>
					<div class="clear clearfix clr"></div>
				</div><!-- .thsn-merlin-message-inner -->
				<!-- Ratings Close Permenetly message -->
				<div class="thsn-merlin-message-conform">
					<div class="thsn-merlin-message-conform-inner">
						<div class="thsn-merlin-message-conform-i">
							<div class="thsn-merlin-message-conform-col thsn-merlin-message-conform-text">
								<h3><?php esc_html_e('Are you sure you want to permenently close this box?', 'itinc'); ?></h3>
								<div class="thsn-merlin-message-conform-btn">
									<a href="#" class="button button-primary thsn-disable-ratings-message"><?php esc_html_e('Yes close this message', 'itinc'); ?></a>
									&nbsp; &nbsp;
									<a href="#" class="button thsn-disable-ratings-message-cancel"><?php esc_html_e('No, keep this message', 'itinc'); ?></a>
								</div>
							</div>
						</div>
					</div>
				</div><!-- .thsn-merlin-message-conform -->
				<!-- Questions or problem box -->
				<div class="thsn-merlin-message-inner thsn-merlin-ratings-box-questions" style="display:none;">
					<div class="thsn-merlin-message-text">
						<h2><?php esc_html_e('Have any question or problem?', 'itinc'); ?></h2>
						<p><?php printf( esc_html__('You can read our theme documents to get how to work with the theme. Still not solved, than feel free to contact us via our support site at %1$s', 'itinc'), thsn_esc_kses('<a href="' . esc_url('https://pbminfotech.support') . '" target="_blank">' . esc_url('https://pbminfotech.support') . '</a>') ); ?></p>
					</div>
					<div class="thsn-merlin-message-btn">
						<div class="thsn-merlin-message-btn-2 thsn-happy-btn-container thsn-pright-15">
							<a href="<?php echo esc_url('https://itinc-demo.pbminfotech.com/docs/'); ?>" target="_blank" class="button button-primary button-hero load-customize thsn-ratings-doc-btn"> <i class="fa fa-book"></i> <?php esc_html_e('itinc Theme Documents', 'itinc'); ?></a>
						</div>
						<div class="thsn-merlin-message-btn-2 thsn-happy-btn-container">
							<a href="<?php echo esc_url('https://pbminfotech.support/'); ?>" target="_blank" class="button button-primary button-hero load-customize thsn-ratings-support-btn"> <i class="fa fa-question-circle"></i> <?php esc_html_e('Go to Themesion support site', 'itinc'); ?></a>
						</div>
						<div class="clearfix clear"></div>
					</div>
					<div class="clear clearfix clr"></div>
				</div><!-- .thsn-merlin-message-inner -->
				<!-- 5-star ratings box -->
				<div class="thsn-merlin-message-inner thsn-merlin-ratings-box-ratings" style="display:none;">
					<div class="thsn-merlin-message-text">
						<div class="thsn-merlin-message-arrow-area">
							<h2><?php esc_html_e('Glad to hear you like our theme', 'itinc'); ?></h2>
							<p><?php printf( esc_html__('Thanks for your support. Please provide us 5-star ratings. This will help us a lot.
	It just take 1 minute. %1$s', 'itinc'), thsn_esc_kses('<a href="' . esc_url('https://themeforest.net/downloads') . '" target="_blank">'.esc_html__('Click here to go for review now','itinc').'</a>') ); ?></p>
						</div>
					</div>
					<div class="thsn-merlin-5star-right-area">
						<img src="<?php echo get_template_directory_uri(); ?>/includes/images/ratings-steps.png" alt="<?php esc_attr_e( 'Ratings Steps', 'itinc' ); ?>" />
					</div>
					<div class="clear clearfix clr"></div>
				</div><!-- .thsn-merlin-message-inner -->
			</div><!-- .thsn-merlin-message -->
		</div><!-- .notice.is-dismissible -->
		<?php
	}
	}
	
	if( !function_exists('thsn_ratings_call') ){
	function thsn_ratings_call(){
		$show_date = get_option('thsn-ratingsbox-show-date');
		$thsn_merlin_all_done = get_option('thsn-merlin-all-done');
		if( empty($show_date) ){
			$nextWeek = time() + (7 * 24 * 60 * 60); // One week..
			$nextWeek = strval( $nextWeek );
			update_option('thsn-ratingsbox-show-date', $nextWeek);
		} else {
			$thsn_ratings_done	= get_option('thsn-ratings-done');
			$nextWeek			= get_option('thsn-ratingsbox-show-date');
			$curr_date			= time();
			$thsn_merlin_all_done = get_option('thsn-merlin-all-done');
			if( $nextWeek < $curr_date && empty($thsn_ratings_done) && $thsn_merlin_all_done=='yes' ){
				add_action( 'admin_notices', 'thsn_ratings_message' );
			}
		}
	}
	}
	add_action( 'init', 'thsn_ratings_call' );

	/**
 *  Ratings message disable ajax call
 */
add_action( 'wp_ajax_thsn_remove_ratings_message', 'thsn_remove_ratings_message' );
if( !function_exists('thsn_remove_ratings_message') ){
function thsn_remove_ratings_message() {
	update_option( 'thsn-ratings-done', 'yes' );
	echo 'ok';
	wp_die(); // this is required to terminate immediately and return a proper response
}
}

