<?php


// version 1.1.0 - FS_METHOD check in theme.json

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class itincThemeManager {

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

	/**
	 *
	 * Theme settings from the theme.json file.
	 *
	 * @var array
	 */
	public $theme_settings = array();

	/*
	 * This is the magic that sets up all the hooks and filters for the theme.
	 */
	/**
	 *
	 */
	public function start() {

		$theme_actions = array(
			'init'                     => array(
				'method' => 'init',
			),
			'after_setup_theme'        => array(
				'method' => 'after_setup_theme',
			),
			'admin_enqueue_scripts'    => array(
				'method'   => 'admin_enqueue_scripts',
				'priority' => 30,
			),
			'wp_enqueue_scripts'       => array(
				'method'   => 'wp_enqueue_scripts',
				'priority' => 30,
			),
			'itinc_page_header_before' => array(
				'method' => 'itinc_page_header_before',
			),
			'itinc_page_header_after'  => array(
				'method' => 'itinc_page_header_after',
			),
			'wp_footer'                => array(
				'method' => 'wp_footer',
			),
			'tgmpa_register'           => array(
				'method' => 'tgmpa_register',
			),
			'in_widget_form'           => array(
				'method' => 'in_widget_form',
			),
			'widget_update_callback'   => array(
				'method'   => 'widget_update_callback',
				'priority' => 11,
				'args'     => 2,
			),
			'widget_display_callback'   => array(
				'method'   => 'widget_display_callback',
				'priority' => 11,
				'args'     => 3,
			),
			'load-widgets.php'         => array(
				'method' => 'widget_color_picker',
			),
			'edit_form_after_title'    => array(
				'method' => 'edit_form_after_title',
			),
			'customize_register'    => array(
				'method' => 'customize_register',
			),
			'admin_notices'    => array(
				'method' => 'admin_notices',
			),
		);

		$theme_filters = array(
			'image_size_names_choose' => array(
				'method'   => 'image_size_names_choose',
				'priority' => 11,
			),
			'widget_title'            => array(
				'method'   => 'widget_title',
				'priority' => 5,
				'args'     => 3,
			),
			'excerpt_length'          => array(
				'method' => 'excerpt_length',
			),
			'excerpt_more'            => array(
				'method' => 'excerpt_more',
			),
			'wp_page_menu_args'       => array(
				'method' => 'wp_page_menu_args',
			),
			'body_class'              => array(
				'method' => 'body_class',
			),
			'tiny_mce_before_init'    => array(
				'method' => 'tiny_mce_before_init',
			),
			'wp_list_categories'      => array(
				'method' => 'wp_list_categories',
				'args'   => 2,
			),
			'get_archives_link'       => array(
				'method' => 'get_archives_link',
				'args'   => 6,
			),
			'wp_tag_cloud'            => array(
				'method' => 'wp_tag_cloud',
			),
			'wp_nav_menu_items'       => array(
				'method' => 'wp_nav_menu_items',
			),
			'wp_generate_tag_cloud'   => array(
				'method'   => 'wp_generate_tag_cloud',
				'args'     => 1,
				'priority' => 9999999,
			),
			'wp_page_menu'   => array(
				'method'   => 'wp_nav_menu',
				'args'     => 2,
			),
			'wp_nav_menu'   => array(
				'method'   => 'wp_nav_menu',
				'args'     => 2,
			),
		);

		foreach ( apply_filters( 'itinc_theme_actions', $theme_actions ) as $action_key => $action_args ) {
			add_action( $action_key, array(
				$this,
				$action_args['method'],
			), empty( $action_args['priority'] ) ? 10 : $action_args['priority'], empty( $action_args['args'] ) ? 1 : $action_args['args'] );
		}
		foreach ( apply_filters( 'itinc_theme_filters', $theme_filters ) as $filter_key => $filter_args ) {
			add_filter( $filter_key, array(
				$this,
				$filter_args['method'],
			), empty( $filter_args['priority'] ) ? 10 : $filter_args['priority'], empty( $filter_args['args'] ) ? 1 : $filter_args['args'] );
		}

		$this->include_theme_files();
	}

	public function init() {

		$this->get_theme_settings();

	}

	public function wp_nav_menu( $wpnavmenu, $args ){
		$wpnavmenu =  str_replace('><','> <',$wpnavmenu);
	    return $wpnavmenu;
    }

	public function admin_notices() {
		// look through default wp contact forms and notify of email errors.
		$forms = get_posts( array( 'post_type' => 'wpcf7_contact_form', 'numberposts' => -1 ) );
		$form_links = array();
		foreach ( $forms as $form ) {
			$meta1 = get_post_meta( $form->ID,'_mail',true );
			if ( $meta1 && ( empty($meta1['sender']) || empty($meta1['recipient']) || strpos( $meta1['sender'], 'dtbaker' ) || strpos( $meta1['recipient'], 'dtbaker' )) ) {
			    $form_links [] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=wpcf7&post='. $form->ID .'&action=edit' ) ), esc_attr( $form->post_title ) );
			}
			$meta2 = get_post_meta( $form->ID,'_mail_2',true );
			if ( $meta2 && ! empty( $meta2['active'] ) && strpos( $meta1['sender'], 'dtbaker' ) ) {
                $form_links [] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=wpcf7&post='. $form->ID .'&action=edit' ) ), esc_attr( $form->post_title ) );
			}
		}
		if($form_links){
            $class = 'notice notice-error';
            $message = esc_html__( 'Please set the correct email addresses on your contact forms', 'itinc' );
            printf( '<div class="%1$s"><p>%2$s: %3$s</p></div>', $class, $message, implode(', ', $form_links) );
        }

	}

	public function wp_generate_tag_cloud( $tag_string ) {
		$tag_string = preg_replace( "/style='font-size:.+pt;'/", '', $tag_string );
		return $tag_string;
	}

	public function edit_form_after_title( $post ) {
	}

	public function get_theme_setting( $key, $default = false ) {
	    if(!$this->theme_settings){
	        $this->get_theme_settings();
        }
		if ( isset( $this->theme_settings[ $key ] ) ) {
			return $this->theme_settings[ $key ];
		}

		return $default;
	}

	public function get_theme_settings() {
		if ( $this->theme_settings ) {
			return $this->theme_settings;
		}
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		WP_Filesystem();
		global $wp_filesystem;
		$this->theme_settings = json_decode( $wp_filesystem->get_contents( trailingslashit( get_template_directory() ) . 'theme.json' ), true );
		if ( ! is_array( $this->theme_settings ) ) {
			$this->theme_settings = array();
		}
		// todo - check FS_METHOD is set to 'direct' if we get no theme_settings back.
        if(empty($this->theme_settings)){
	        add_action('admin_notices', function() {
	            ?> <div class="notice notice-error is-dismissible">
                    <p>Unable to read <code>theme.json</code> settings file. Please try to add <code>define('FS_METHOD','direct');</code> to your <code>wp-config.php</code> file. Contact support or your hosing provider for assistance.</p>
                </div>
                <?php
            } );

        }

		return $this->theme_settings;
	}

	public function after_setup_theme() {
		// steps to do after theme setup
	}

	public function add_editor_styles() {
		add_editor_style( apply_filters( 'itinc_editor_styles', array(
			'style.editor.css',
		) ) );
	}

	/*
	 * Make itinc available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on itinc, use a find and replace
	 * to change 'itinc' to the name of your theme in all the template files.
	 */
	public function setup_translations() {
		load_theme_textdomain( 'itinc', get_template_directory() . '/languages' );

		$locale      = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable( $locale_file ) ) {
			require_once( $locale_file );
		}
	}

	/*
	public function add_theme_support() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );

		//add_theme_support( 'admin-bar', array( 'callback' => 'itinc_admin_bar_padding' ) );

		add_theme_support( 'post-thumbnails' );

		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'itinc' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

	}
	*/


	
	/*
	public function setup_background() {

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'itinc_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}
	*/

	public function setup_menu() {
		// This theme uses wp_nav_menu() in one location.
	}

	public function setup_images() {
		// post-thumbnail
	}

	public function admin_enqueue_scripts() {
	}

	public function wp_enqueue_scripts() {

        $theme_settings = $this->get_theme_settings();

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( ! empty( $theme_settings['minify-stylesheets'] ) && apply_filters( 'itinc_minify_styles', true ) ) {
			wp_enqueue_style( 'itinc_style', get_template_directory_uri() . '/style.min.css', array(), itinc_theme_version );
		} else {
			// individual styles.
			if ( ! empty( $theme_settings['stylesheets-separate'] ) ) {
                foreach ( $theme_settings['stylesheets-separate'] as $stylesheet ) {
                    $stylesheet_id = 'itinc_' . str_replace( '.', '_', str_replace( '.css', '', $stylesheet ) );
                    wp_enqueue_style( $stylesheet_id, get_template_directory_uri() . '/' . $stylesheet, array(), itinc_theme_version );
                }
			}else if ( ! empty( $theme_settings['stylesheets'] ) ) {
                foreach ( $theme_settings['stylesheets'] as $stylesheet ) {
                    $stylesheet_id = 'itinc_' . str_replace( '.', '_', str_replace( '.css', '', $stylesheet ) );
                    wp_enqueue_style( $stylesheet_id, get_template_directory_uri() . '/' . $stylesheet, array(), itinc_theme_version );
                }
			}
		}

	}

	public function default_css_output() {
		itinc_default_css_output( true );
	}

	public function include_theme_files() {
		$theme_files = array();
		
		$theme_files[] = 'setup/envato_setup.php';
		$theme_files[] = 'class-tgm-plugin-activation.php';

		foreach ( apply_filters( 'itinc_theme_files', $theme_files ) as $theme_file ) {
			$template_file = locate_template( $theme_file );
			if ( $template_file && is_readable( $template_file ) ) {
				require_once $template_file;
			}
		}
		

	}

	/**
	 * We run this filter on the first two times it is called during page load.
	 * We don't want to run it on the 3rd time because that is the insert image template which already has image sizes.
	 *
	 * @param $sizes
	 *
	 * @return array
	 */
	private static $_image_size_name_chooser_count = 0;

	public function image_size_names_choose( $sizes ) {
		global $_wp_additional_image_sizes;
		if ( isset( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $size => $settings ) {
				if ( ! isset( $sizes[ $size ] ) ) {
					$sizes[ $size ] = $size . ( 2 === self::$_image_size_name_chooser_count ? ' (' . $settings['width'] . 'x' . $settings['height'] . ')' : '' );
				}
			}
		}
		self::$_image_size_name_chooser_count ++;

		return $sizes;
	}

	/**
	 *
	 * Remove the calendar widget title if it's empty
	 *
	 * @param string $title
	 * @param string $instance
	 * @param string $id_base
	 *
	 * @return string
	 */
	public function widget_title( $title = '', $instance = '', $id_base = '' ) {
		if ( 'calendar' === $id_base && '&nbsp;' === $title ) {
			$title = '';
		}

		return $title;
	}

	/**
	 * Sets the post excerpt length
	 *
	 * To override this length in a child theme, remove the filter and add your own
	 * function tied to the excerpt_length filter hook.
	 */
	public function excerpt_length() {
		return 48;
	}

	/**
	 * Sets the post excerpt more string
	 *
	 */
	public function excerpt_more() {
		return '[&hellip;]';
	}

	/**
	 * @param $args array
	 *
	 * @return array
	 */
	public function wp_page_menu_args( $args ) {
		$args['show_home'] = true;

		return $args;
	}


	public function itinc_page_header_before() {

	}

	public function itinc_page_header_after() {

	}

	public $default_color = 'style1';

	public function body_class( $classes ) {

		if ( class_exists( 'itincThemeManager_custom' ) ) {
			$theme_settings = itincThemeManager_custom::get_instance()->get_theme_setting( 'customizer' );
		} else {
			$theme_settings = itincThemeManager::get_instance()->get_theme_setting( 'customizer' );
		}
		if ( is_array( $theme_settings ) ) {
			foreach ( $theme_settings as $id => $section_details ) {
				foreach ( $section_details['items'] as $setting_key => $setting_values ) {
					if ( isset( $setting_values['add_body_class'] ) && $setting_values['add_body_class'] ) {
						$classes[] = $setting_key . '_' . get_theme_mod( $setting_key, $setting_values['default'] );
					}
				}
			}
		}

		return $classes;
	}


	public function tiny_mce_before_init( $init_array ) {
		if ( get_theme_mod( 'itinc_site_color', $this->default_color ) ) {
			$init_array['body_class'] = 'itinc_color_' . get_theme_mod( 'itinc_site_color', $this->default_color );
		}

		return $init_array;
	}

	public function wp_list_categories( $cats, $args ) {
		$cats = str_replace( '&nbsp;', ' ',$cats );
		return preg_replace( '#</a>\s*\(([^\)]*)\)#', '<span class="number">$1</span></a>', $cats );
	}

	public function get_archives_link( $link_html, $url, $text, $format, $before, $after ) {
		$text = wptexturize( $text );
		$url  = esc_url( $url );

		$after = str_replace( '&nbsp;', ' ',$after );
		if ( 'link' == $format ) {
			$after     = preg_replace( '#\s*\(([^\)]*)\)#', ' <span class="number">$1</span>', $after );
			$link_html = "\t<link rel='archives' title='" . esc_attr( $text ) . "' href='$url' />\n";
		} elseif ( 'option' == $format ) {
			$link_html = "\t<option value='$url'>$before $text $after</option>\n";
		} elseif ( 'html' == $format ) {
			$after     = preg_replace( '#\s*\(([^\)]*)\)#', ' <span class="number">$1</span>', $after );
			$link_html = "\t<li>$before<a href='$url'>$text</a>$after</li>\n";
		} else // custom
		{
			$link_html = "\t$before<a href='$url'>$text</a>$after\n";
		}

		return $link_html;
	}


	public function wp_footer() {

	}

	public function wp_tag_cloud( $return ) {
		return $return;
	}

	public function wp_nav_menu_items( $items ) {

		return $items;
	}

	public function tgmpa_register() {

		$theme_settings = $this->get_theme_settings();
		// we load our required plugins from the theme settings array.
		$tgmpa_plugins = array();
		$plugin_config = array();
		if ( ! empty( $theme_settings['plugins'] ) && is_array( $theme_settings['plugins'] ) ) {
			foreach ( $theme_settings['plugins'] as $plugin_slug => $plugin_details ) {
				if ( ! empty( $plugin_details['local'] ) && ! empty( $plugin_details['source'] ) ) {
					// This is a locally build plugin in the /plugins/ folder.
					if ( is_readable( get_template_directory() . '/plugins/' . $plugin_details['source'] ) ) {
						$tgmpa_plugins[ $plugin_slug ] = array(
							'name'             => $plugin_details['name'],
							'version'          => $plugin_details['version'],
							'slug'             => $plugin_slug,
							'source'           => get_template_directory() . '/plugins/' . $plugin_details['source'],
							'required'         => ! empty( $plugin_details['required'] ),
							'recommended'      => ! empty( $plugin_details['required'] ),
							'force_activation' => ! empty( $plugin_details['required'] ),
						);
					}
				} else if ( ! empty( $plugin_details['wordpress'] ) ) {
					// This is a standard WordPress plugin.
					$tgmpa_plugins[ $plugin_slug ] = array(
						'name'             => $plugin_details['name'],
						'slug'             => $plugin_slug,
						'required'         => ! empty( $plugin_details['required'] ),
						'recommended'      => ! empty( $plugin_details['required'] ),
						'force_activation' => ! empty( $plugin_details['required'] ),
					);
				} else if ( ! empty( $plugin_details['source'] ) ) {
					// This is a 3rd party manually installed plugin (e.g. Envato Market)
					$tgmpa_plugins[ $plugin_slug ] = array(
						'name'             => $plugin_details['name'],
						'source'           => $plugin_details['source'],
						'slug'             => $plugin_slug,
						'required'         => ! empty( $plugin_details['required'] ),
						'recommended'      => ! empty( $plugin_details['required'] ),
						'force_activation' => ! empty( $plugin_details['required'] ),
					);
				}
				if ( ! empty( $plugin_details['config'] ) ) {
					// localize these out so the plugin can access them.
					$plugin_config[ $plugin_slug ] = $plugin_details['config'];
				}
			}
		}
		if ( ! empty( $plugin_config ) ) {
			$plugin_config = apply_filters( 'itinc_plugin_config', $plugin_config );
			wp_localize_script( 'jquery', 'itinc_plugin_config', $plugin_config );
		}

		$tgmpa_plugins = apply_filters( 'itinc_tgmpa_plugins', $tgmpa_plugins );

		tgmpa( $tgmpa_plugins, array() );
	}


	/**
	 * Adds the color picker to the widget page so we can select widget title/background colors
	 */
	public function widget_color_picker() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}

	/**
	 *
	 * Handles saving our custom widget options, as defined in theme.json
	 *
	 * @param $instance
	 * @param $new_instance
	 *
	 * @return mixed
	 */
	public function customize_register( $wp_customize ) {

		$theme_settings = $this->get_theme_settings();
		if ( ! empty( $theme_settings['widget-settings'] ) ) {

			$wp_customize->add_section( 'itinc_widget_defaults', array(
				'title'       => esc_html__( 'Widget Styles', 'itinc' ),
				'description' => '',
				'priority'    => 120,
			) );
			foreach ( $theme_settings['widget-settings'] as $setting ) {
				if ( ! empty( $setting['id'] ) && ! empty( $setting['type'] ) ) {
					switch ( $setting['type'] ) {
						case 'select':
							$wp_customize->add_setting( 'widgetsettings[' . $setting['id'] . ']', array(
								'default'           => key( $setting['options'] ),
								'capability'        => 'edit_theme_options',
								'type'              => 'theme_mod',
								'sanitize_callback' => 'itinc_theme_sanitize_callback',
							) );
							$wp_customize->add_control( 'widgetsettings[' . $setting['id'] . ']', array(
								'settings' => 'widgetsettings[' . $setting['id'] . ']',
								'label'    => 'Default ' . $setting['title'],
								'section'  => 'itinc_widget_defaults',
								'type'     => 'select',
								'choices'  => $setting['options'],
							) );
							break;
						// todo: add color
					}
				}
			}
		}

	}

	/**
	 *
	 * Handles saving our custom widget options, as defined in theme.json
	 *
	 * @param $instance
	 * @param $new_instance
	 *
	 * @return mixed
	 */
	public function widget_update_callback( $instance, $new_instance ) {
		$theme_settings = $this->get_theme_settings();
		if ( ! empty( $theme_settings['widget-settings'] ) ) {
			foreach ( $theme_settings['widget-settings'] as $setting ) {
				if ( ! empty( $setting['id'] ) && isset( $new_instance[ 'itinc_' . $setting['id'] ] ) ) {
					$instance[ 'itinc_' . $setting['id'] ] = $new_instance[ 'itinc_' . $setting['id'] ];
				}
			}
		}

		return $instance;
	}

	/**
	 *
	 * Handles saving our custom widget options, as defined in theme.json
	 *
	 * @param $instance
	 * @param $new_instance
	 *
	 * @return mixed
	 */
	public function widget_display_callback( $instance, $widget, $args ) {

		static $widget_count = 0;
		$widget_count++;
		$widget_classname = $widget->widget_options['classname'];

		$my_classname = 'itinc_widget ';

		$widget_defaults = get_theme_mod( 'widgetsettings' );
		if ( ! is_array( $widget_defaults ) ) {
			$widget_defaults = array();
		}

		$theme_settings = $this->get_theme_settings();
		if ( ! empty( $theme_settings['widget-settings'] ) ) {
			foreach ( $theme_settings['widget-settings'] as $setting ) {
				$default = isset( $widget_defaults[ $setting['id'] ] ) ? $widget_defaults[ $setting['id'] ] : key( $setting['options'] ); // pick first by default.
				if ( empty( $instance[ 'itinc_' . $setting['id'] ] ) ) {
					$instance[ 'itinc_' . $setting['id'] ] = $default;
				}
				if ( ! empty( $setting['id'] ) && isset( $instance[ 'itinc_' . $setting['id'] ] ) ) {
					$my_classname .= esc_attr( 'itinc_'.$setting['id'] . '_'. $instance[ 'itinc_' . $setting['id'] ] );
				}
			}
		}

		$my_classname .= ' itinc_widget_count_'.$widget_count;

		$args['before_widget'] = preg_replace( '#class="[^"]*'.preg_quote( $widget_classname,'#' ).'#', '$0 '.$my_classname, $args['before_widget'] );
		$widget->widget( $args, $instance );
		return false;
	}

	/**
	 *
	 * Applies our custom widget settings from the theme.json file
	 * Supports color picker and background select box class.
	 *
	 * @param $widget_instance
	 */
	public function in_widget_form( $widget_instance ) {
		$theme_settings = $this->get_theme_settings();
		$settings       = $widget_instance->get_settings();
		$settings       = isset( $widget_instance->number ) && isset( $settings[ $widget_instance->number ] ) ? $settings[ $widget_instance->number ] : array();
		$widget_defaults = get_theme_mod( 'widget_defaults',array() );
		if ( ! empty( $theme_settings['widget-settings'] ) ) {
			foreach ( $theme_settings['widget-settings'] as $setting ) {
				if ( ! empty( $setting['id'] ) && ! empty( $setting['type'] ) ) {
					switch ( $setting['type'] ) {
						case 'select':
							$default = isset( $widget_defaults[ $setting['id'] ] ) ? $widget_defaults[ $setting['id'] ] : key( $setting['options'] ); // pick first by default.

							?>
							<div class="itinc_widget_setting">
								<label
									for="<?php echo esc_attr( $widget_instance->get_field_id( 'itinc_' . $setting['id'] ) ); ?>"><?php echo esc_html( $setting['title'] ); ?>
									:</label>
								<select
									name="<?php echo esc_attr( $widget_instance->get_field_name( 'itinc_' . $setting['id'] ) ); ?>"
									id="<?php echo esc_attr( $widget_instance->get_field_id( 'itinc_' . $setting['id'] ) ); ?>"
									class="itinc_widget_style_select">
									<option value=""><?php printf( esc_html__( 'Default (%s)', 'itinc' ), $setting['options'][ $default ] );?></option>
									<?php
									foreach ( $setting['options'] as $key => $val ) { ?>
										<option
											value="<?php echo esc_attr( $key ); ?>" <?php echo ( isset( $settings[ 'itinc_' . $setting['id'] ] ) && $settings[ 'itinc_' . $setting['id'] ] == $key ) ? 'selected' : ''; ?>><?php echo esc_html( $val ); ?></option>
									<?php } ?>
								</select>
							</div>
							<?php
							break;
						case 'color':
							?>
							<div class="itinc_widget_setting">
								<script type='text/javascript'>
									var color_done = color_done || {};
									color_done['<?php echo esc_js( $widget_instance->get_field_id( 'itinc_' . $setting['id'] ) );?>'] = false;
									jQuery(document).ready(function ($) {
										$('.widget-liquid-right #<?php echo esc_js( $widget_instance->get_field_id( 'itinc_' . $setting['id'] ) );?>').wpColorPicker();

									});
								</script>
								<label
									for="<?php echo esc_attr( $widget_instance->get_field_id( 'itinc_' . $setting['id'] ) ); ?>"><?php echo esc_html( $setting['title'] ); ?>
									:</label>
								<input class="itinc_color_picker" type="text"
								       id="<?php echo esc_attr($widget_instance->get_field_id( 'itinc_' . $setting['id'] )); ?>"
								       name="<?php echo esc_attr($widget_instance->get_field_name( 'itinc_' . $setting['id'] )); ?>"
								       placeholder="<?php esc_attr_e( ' (Press Save)', 'itinc' ); ?>"
								       value="<?php echo esc_attr( ! empty( $settings[ 'itinc_' . $setting['id'] ] ) ? $settings[ 'itinc_' . $setting['id'] ] : '' ); ?>"/>
							</div>
							<?php
							break;
					}
				}
			}
		}
		?>

		<?php
	}
}

