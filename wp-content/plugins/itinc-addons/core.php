<?php
if( !function_exists('thsn_icon_library_list') ){
function thsn_icon_library_list() {
	$icon_libraries = array(
		'thsn_itinc_icon'		=> array(
			'name'			=> esc_attr__( 'ITinc Icon', 'itinc' ),
			'default_icon'	=> 'thsn-itinc-icon thsn-itinc-icon-light',
			'css_path'		=> esc_url( get_template_directory_uri() . '/libraries/thsn-itinc-icon/flaticon.css' ),
			'common_class'	=> 'thsn-itinc-icon',
			'class_prefix'	=> 'thsn-itinc-icon-',
		),
		'elementor-icons-fa-regular'	=> array(
			'name'			=> esc_attr__( 'Font Awesome - Regular', 'itinc' ),
			'default_icon'	=> 'far fa-address-book',
			'css_path'		=> esc_url( get_template_directory_uri() . '/libraries/font-awesome/css/regular.min.css' ),
			'common_class'	=> 'far', 
			'class_prefix'	=> 'fa-',
		),
		'elementor-icons-fa-solid'	=> array(
			'name'			=> esc_attr__( 'Font Awesome - Solid', 'itinc' ),
			'default_icon'	=> 'fas fa-star',
			'css_path'		=> esc_url( get_template_directory_uri() . '/libraries/font-awesome/css/solid.min.css' ),
			'common_class'	=> 'fas', 
			'class_prefix'	=> 'fa-',
		),
		'elementor-icons-fa-brands'	=> array(
			'name'			=> esc_attr__( 'Font Awesome - Brands', 'itinc' ),
			'default_icon'	=> 'fab fa-facebook-square',
			'css_path'		=> esc_url( get_template_directory_uri() . '/libraries/font-awesome/css/brands.min.css' ),
			'common_class'	=> 'fab', 
			'class_prefix'	=> 'fa-',
		),
		'material-icons'	=> array(
			'name'			=> esc_attr__( 'Material Icons', 'itinc' ),
			'default_icon'	=> 'mdi mdi-group',
			'css_path'		=> esc_url( get_template_directory_uri() . '/libraries/material-icons/css/material-icons.min.css' ),
			'common_class'	=> 'mdi', 
			'class_prefix'	=> 'mdi-',
		),
		'sgicon'	=> array(
			'name'			=> esc_attr__( 'Stroke Gap Icons', 'itinc' ),
			'default_icon'	=> 'sgicon sgicon-WorldWide',
			'css_path'		=> esc_url( get_template_directory_uri() . '/libraries/stroke-gap-icons/style.css' ),
			'common_class'	=> 'sgicon', 
			'class_prefix'	=> 'sgicon-',
		),
	);
	return $icon_libraries;
}
}

/**
 *  Global function - This will return array of different templates for CPT and other boxes
 */
if( !function_exists('thsn_element_template_list') ){
function thsn_element_template_list( $for='portfolio', $elementor=false ){
	$return = array();
	if( !empty($for) ){
		// Default titles
		$portfolio_cpt_singular_title	= esc_attr__('Portfolio','itinc');
		$service_cpt_singular_title		= esc_attr__('Service','itinc');
		$team_cpt_singular_title		= esc_attr__('Team Member','itinc');
		if( class_exists('Kirki') ){
			// Portfolio - singular
			$portfolio_cpt_singular_title2	= Kirki::get_option( 'portfolio-cpt-singular-title' );
			$portfolio_cpt_singular_title	= ( !empty($portfolio_cpt_singular_title2) ) ? $portfolio_cpt_singular_title2 : $portfolio_cpt_singular_title ;
			// Service - singular
			$service_cpt_singular_title2	= Kirki::get_option( 'service-cpt-singular-title' );
			$service_cpt_singular_title	= ( !empty($service_cpt_singular_title2) ) ? $service_cpt_singular_title2 : $service_cpt_singular_title ;
			// Team - singular
			$team_cpt_singular_title2	= Kirki::get_option( 'team-cpt-singular-title' );
			$team_cpt_singular_title	= ( !empty($team_cpt_singular_title2) ) ? $team_cpt_singular_title2 : $team_cpt_singular_title ;
		}

		$elements_array = array(
			'icon-heading'			=> array( 'name' => esc_attr__('Icon Heading', 'itinc'),			'total_styles' => 15 ),
			'portfolio'				=> array( 'name' => $portfolio_cpt_singular_title,					'total_styles' => 2 ),
			'service'				=> array( 'name' => $service_cpt_singular_title,					'total_styles' => 4 ),
			'team'					=> array( 'name' => $team_cpt_singular_title,						'total_styles' => 2 ),
			'testimonial'			=> array( 'name' => esc_attr__('Testimonial', 'itinc'),			'total_styles' => 3 ),
			'client'				=> array( 'name' => esc_attr__('Client', 'itinc'),				'total_styles' => 2 ),
			'blog'					=> array( 'name' => esc_attr__('Blog', 'itinc'),					'total_styles' => 2 ),
			'pricing-table'			=> array( 'name' => esc_attr__('Pricing Table', 'itinc'),			'total_styles' => 1 ),
			'facts-in-digits'		=> array( 'name' => esc_attr__('Facts In Digits', 'itinc'),		'total_styles' => 6 ),
			'static-box'			=> array( 'name' => esc_attr__('Static Box', 'itinc'),			'total_styles' => 2 ),
			'opening-hours-list'	=> array( 'name' => esc_attr__('Opening Hours List', 'itinc'),	'total_styles' => 2 ),
		);

		if( !empty($elements_array[$for]) ){
			for ($x = 1; $x <= $elements_array[$for]['total_styles']; $x++) {
				$thumb = get_template_directory_uri() . '/includes/images/no-style-thumb.jpg';
				if( file_exists( get_stylesheet_directory() . '/includes/images/'.$for.'-style-'.$x.'.jpg' ) ){
					$thumb = get_stylesheet_directory_uri() . '/includes/images/'.$for.'-style-'.$x.'.jpg';
				} else if( file_exists( get_template_directory() . '/includes/images/'.$for.'-style-'.$x.'.jpg' ) ){
					$thumb = get_template_directory_uri() . '/includes/images/'.$for.'-style-'.$x.'.jpg';
				}
				if( $elementor==true ){
					$return[$x] = $thumb;
				} else {
					$return[] = array(
						'label'	=> sprintf( esc_attr( '%1$s - Style %2$s', 'itinc'), $elements_array[$for]['name'], $x ),
						'value'	=> "$x",
						'thumb'	=> $thumb,
					);
				}
			}
		}
	}
	return $return;
}
}

/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
if ( ! function_exists( 'thsn_edit_link' ) ) {
function thsn_edit_link() {
	edit_post_link(
		esc_attr__( 'Edit', 'itinc' ),
		'<span class="edit-link">',
		'</span>'
	);
}
}

if( !function_exists('thsn_get_base_option') ) {
function thsn_get_base_option( $option='' ){
	$return = '';
	if( class_exists('Kirki') ){
		$return = Kirki::get_option( $option );
	} else {
		if( empty($kirki_options_array) ){
			if( file_exists( get_template_directory() . '/includes/customizer-options.php' ) ){
				include get_template_directory() . '/includes/customizer-options.php';
			}
		}
		if( !empty($kirki_options_array) ){
			foreach( $kirki_options_array as $kirki_options ){
				if( !empty($kirki_options['section_fields']) ){
					foreach( $kirki_options['section_fields'] as $field ){
						if( !empty($field['settings']) && $field['settings']==$option && isset($field['default']) ){
							$return = $field['default'];
						}
					}
				}
			}
		}
	}
	return $return;
}
}

/*
 *  Themesion element container
 */
if( !function_exists('themesion_element_container') ){
function themesion_element_container( $settings = array( 'position' => 'start', 'cpt' => 'blog', 'data' => array() ) ){

	$return 	 = '';
	$inner_class_array = array('themesion-element-inner');

	// New Vars
	$position	= ( !empty($settings['position']) ) ? $settings['position'] : 'start' ;
	$cpt		= ( !empty($settings['cpt']) ) ? $settings['cpt'] : 'blog' ;
	$view_type	= ( !empty($settings['data']['view-type']) ) ? $settings['data']['view-type'] : 'row-column' ;
	$show		= ( !empty($settings['data']['show']) ) ? $settings['data']['show'] : '3' ;
	$columns	= ( !empty($settings['data']['columns']) ) ? $settings['data']['columns'] : '3' ;
	$gap		= ( !empty($settings['data']['gap']) ) ? $settings['data']['gap'] : '' ;
	$style		= ( !empty($settings['data']['style']) ) ? $settings['data']['style'] : '1' ;

	// Carousel
	$car_loop			= ( !empty($settings['data']['carousel-loop']) && $settings['data']['carousel-loop']=='1' ) ? 'true' : 'false' ;
	$car_autoplay		= ( !empty($settings['data']['carousel-autoplay']) && $settings['data']['carousel-autoplay']=='1' ) ? 'true' : 'false' ;
	$car_center			= ( !empty($settings['data']['carousel-center']) && $settings['data']['carousel-center']=='1' ) ? 'true' : 'false' ;
	$car_dots			= ( !empty($settings['data']['carousel-dots']) && $settings['data']['carousel-dots']=='1' ) ? 'true' : 'false' ;
	$car_autoplayspeed	= ( !empty($settings['data']['carousel-autoplayspeed']) ) ? trim($settings['data']['carousel-autoplayspeed']) : '1000' ;

	$car_nav = 'false';
	if( !empty($settings['data']['carousel-nav']) ) {
		if( $settings['data']['carousel-nav']=='1' ) {
			$car_nav = 'true';
		} else if( $settings['data']['carousel-nav']=='above' ) {
			$car_nav = 'above';
		}
	}

	if( $position=='start' ){

		// Enqueue scripts and styles
		if( $view_type=='carousel' ){
			wp_enqueue_script( 'owl-carousel' );
			wp_enqueue_style( 'owl-carousel' );
			wp_enqueue_style( 'owl-carousel-theme' );
		}

		// Data tags
		$data_array = array();
		$data_array[] = 'data-show="'.$show.'"';
		$data_array[] = 'data-columns="'.$columns.'"';
		$data_array[] = 'data-loop="'.$car_loop.'"';
		$data_array[] = 'data-autoplay="'.$car_autoplay.'"';
		$data_array[] = 'data-center="'.$car_center.'"';
		$data_array[] = 'data-nav="'.$car_nav.'"';
		$data_array[] = 'data-dots="'.$car_dots.'"';
		$data_array[] = 'data-autoplayspeed="'. esc_attr($car_autoplayspeed).'"';
		$data_array[] = 'data-margin="'. esc_attr($gap).'"';

		// class
		$class_array = array();
		$class_array[] = 'themesion-element';
		$class_array[] = 'themesion-element-'.$cpt;
		$class_array[] = 'thsn-element-'.$cpt.'-style-'.$style;
		$class_array[] = 'themesion-element-viewtype-'.$view_type;
		if( !empty($gap) ){
			$class_array[] = 'themesion-gap-'.$gap;
		}
		if( !empty($settings['data']['sortable']) ){
			$class_array[] = 'thsn-sortable-' . esc_attr($settings['data']['sortable']);
		}

		// Return
		$return = '<div class="'. implode(' ', $class_array) .'" '. implode(' ', $data_array) . '><div class="'. implode(' ', $inner_class_array) .'">';

	} else {

		$return = '</div><!-- .themesion-element-inner -->   </div><!-- .themesion-element -->  ';

	}

	return $return;
}
}

if( !function_exists('thsn_social_links_list') ){
function thsn_social_links_list( $settings = array( 'position' => 'start', 'column' => '3' ) ){
	return array(
		array(
			'id'			=> 'facebook',
			'label'			=> 'Facebook',
			'icon_class'	=> 'thsn-base-icon-facebook-squared',
		),
		array(
			'id'			=> 'twitter',
			'label'			=> 'Twitter',
			'icon_class'	=> 'thsn-base-icon-twitter',
		),
		array(
			'id'			=> 'linkedin',
			'label'			=> 'LinkedIn',
			'icon_class'	=> 'thsn-base-icon-linkedin-squared',
		),
		array(
			'id'			=> 'youtube',
			'label'			=> 'Youtube',
			'icon_class'	=> 'thsn-base-icon-youtube-play',
		),
		array(
			'id'			=> 'instagram',
			'label'			=> 'Instagram',
			'icon_class'	=> 'thsn-base-icon-instagram',
		),
		array(
			'id'			=> 'flickr',
			'label'			=> 'Flickr',
			'icon_class'	=> 'thsn-base-icon-flickr',
		),
		array(
			'id'			=> 'pinterest',
			'label'			=> 'Pinterest',
			'icon_class'	=> 'thsn-base-icon-pinterest',
		),
	);
}
}

if( !function_exists('thsn_team_social_links') ){
function thsn_team_social_links(){
	$return = '';
	$social_list = thsn_social_links_list();
	foreach( $social_list as $social ){
		$social_link = get_post_meta( get_the_ID(), 'thsn-social-links_' . $social['id'], true );
		if( !empty($social_link) ){
			$return .= '<li class="thsn-social-li thsn-social-'.$social['id'].'"><a href="' . esc_url($social_link) . '" title="' . esc_attr($social['label']) . '" target="_blank"><span><i class="' . esc_attr($social['icon_class']) . '"></i></span></a></li>';
		}
	}
	if( !empty($return) ){
		echo thsn_esc_kses('<ul class="thsn-social-links thsn-team-social-links">'.$return.'</ul>');
	}
}
}

if( !function_exists('thsn_social_share_list') ){
function thsn_social_share_list( $for='' ){
	$list = array(
		'facebook'	=> array(
			'title'			=> esc_attr('Facebook'),
			'link'			=> 'https://facebook.com/sharer/sharer.php?u=%1$s&title=%2$s',
			'icon_class'	=> 'thsn-base-icon-facebook-squared',
		),
		'twitter'	=> array(
			'title' 		=> esc_attr('Twitter'),
			'link'			=> 'https://twitter.com/intent/tweet/?text=%2$s&amp;url=%1$s',
			'icon_class'	=> 'thsn-base-icon-twitter',
		),
		'google-plus'	=> array(
			'title' 		=> esc_attr('Google Plus'),
			'link'			=> 'https://plus.google.com/share?url=%1$s',
			'icon_class'	=> 'thsn-base-icon-gplus',
		),
		'tumblr'		=> array(
			'title' 		=> esc_attr('Tumblr'),
			'link'			=> 'https://www.tumblr.com/widgets/share/tool?posttype=link&amp;title=%2$s&amp;caption=%2$s&amp;content=%1$s&amp;canonicalUrl= &amp;shareSource=tumblr_share_button',
			'icon_class'	=> 'thsn-base-icon-tumbler',
		),
		'pinterest'		=> array(
			'title'			=> esc_attr('Pinterest'),
			'link'			=> 'https://pinterest.com/pin/create/button/?url=%1$s&amp;media=%1$s&amp;description=%2$s',
			'icon_class'	=> 'thsn-base-icon-pinterest',
		),
		'linkedin'		=> array(
			'title'			=> esc_attr('LinkedIn'),
			'link'			=> 'https://www.linkedin.com/shareArticle?mini=true&amp;url=%1$s&amp;title=%2$s&amp;summary=%2$s&amp;source=%1$s',
			'icon_class'	=> 'thsn-base-icon-linkedin-squared',
		),
		'reddit'		=> array(
			'title'			=> esc_attr('Reddit'),
			'link'			=> 'https://reddit.com/submit/?url=%1$s&title=%2$s',
			'icon_class'	=> 'thsn-base-icon-reddit',
		),
	);
	if( $for=='customizer' ){
		$return_array = array();
		foreach( $list as $social=>$data ){
			$return_array[$social] = $data['title'];
		}
		return $return_array;
	}
	return $list;
}
}

if( !function_exists('thsn_blog_social_share') ){
function thsn_blog_social_share(){
	$return		 = '';
	$list        = thsn_social_share_list();
	$social_list = thsn_get_base_option('blog-social-share');
	if( !empty($social_list) && is_array($social_list) && count($social_list)>0 ){
		foreach( $social_list as $social ){
			if( !empty($list[$social]) ){
				$link = sprintf( $list[$social]['link'] , get_permalink() , get_the_title()  ) ;
				$return .= '<li class="thsn-social-li thsn-social-li-'.esc_attr($social).'"><a class="thsn-popup" href="'.esc_url($link).'" title="' . sprintf( esc_attr__('Share on %1$s','itinc'), $list[$social]['title'] ) . '"><i class="'.$list[$social]['icon_class'].'"></i></a></li>';
			}
		}
	}
	if( !empty($return) ){
		echo thsn_esc_kses('<div class="thsn-social-share"><ul>'.$return.'</ul></div>');
	}
}
}

if( !function_exists('thsn_team_designation') ){
function thsn_team_designation(){
	// Designation
	$designation = get_post_meta( get_the_ID(), 'thsn-team-details_designation', true );
	if( !empty($designation) ){
		?>
		<div class="themesion-box-team-position"><?php echo esc_html($designation); ?></div>
		<?php
	}
}
}

if( !function_exists('thsn_get_all_option_array') ) {
function thsn_get_all_option_array(){
	$return = array();
	include get_template_directory() . '/includes/customizer-options.php';
	foreach( $kirki_options_array as $kirki_options ){
		if( !empty($kirki_options['section_fields']) ){
			foreach( $kirki_options['section_fields'] as $field ){
				$settings            = str_replace( '-', '_', $field['settings'] );
				$settings            = str_replace( '-', '_', $settings );
				$settings            = str_replace( '-', '_', $settings );
				$settings            = str_replace( '-', '_', $settings );
				$settings            = str_replace( '-', '_', $settings );
				$return[ $settings ] = thsn_get_base_option( $field['settings'] );
			}
		}
	}
	return $return;
}
}

if( !function_exists('thsn_inline_css') ) {
function thsn_inline_css( $css='' ){
	if( !empty($css) ){
		global $thsn_inline_css;
		if( empty($thsn_inline_css) ){
			$thsn_inline_css = '';
		}
		$thsn_inline_css .= $css;
	}
}
}

if( !function_exists('thsn_footer_copyright_area') ){
function thsn_footer_copyright_area() {
	$footer_copyright_content	= array();
	$right_content				= '';
	$footer_copyright_text		= thsn_get_base_option('copyright-text');
	$footer_right_content		= thsn_get_base_option('footer-copyright-right-content');

	if( $footer_right_content=='menu' ){
		if( has_nav_menu('themesion-footer') ){
			ob_start();
			wp_nav_menu( array(
				'theme_location' => 'themesion-footer',
				'menu_id'        => 'thsn-footer-menu',
				'menu_class'     => 'thsn-footer-menu',
			) );
			$right_content = ob_get_contents();
			ob_end_clean();
		}

	} else {
		// Social 
		if( shortcode_exists('thsn-social-links') ){
			$right_content = do_shortcode('[thsn-social-links]');
		}

	}

	// preparing column contents
	if( !empty($footer_copyright_text) ){
		$footer_copyright_content[] = '<div class="thsn-footer-copyright-text-area"> ' . esc_html($footer_copyright_text) . '</div>';
	}
	if( thsn_footer_logo('checkonly') ){
		$footer_copyright_content[] = '<div class="thsn-footer-logo-box">' . thsn_footer_logo() . '</div>';
	}
	if( !empty($right_content) ){
		$footer_copyright_content[] = '<div class="thsn-footer-' . esc_attr($footer_right_content) . '-area">' . thsn_esc_kses($right_content) . '</div>';
	}

	/* Footer Copyright Content area - column class */
	switch( count($footer_copyright_content) ){
		case 1;
			$footer_copyright_class = 'col-md-12';
			break;
		case 2;
			$footer_copyright_class = 'col-md-6';
			break;
		case 3;
			$footer_copyright_class = 'col-md-4';
			break;
	}

	if( !empty($footer_copyright_content) ){
		foreach( $footer_copyright_content as $content ){
			echo thsn_esc_kses('<div class="' . esc_attr( $footer_copyright_class ) . '">' . thsn_esc_kses($content) . '</div>');
		}
	}
}
}

if( !function_exists('thsn_footer_boxes_area') ){
function thsn_footer_boxes_area() {

	$return = '';

	$footer_boxes_area = thsn_get_base_option('footer-boxes-area');

	if( $footer_boxes_area == true ){

		$footer_box_content	= array();
		$footer_box_class			= '';

		for( $x=1; $x<=3; $x++ ){
			$icon_html	= '';
			$title		= thsn_get_base_option('footer-box-'.$x.'-title');
			$desc		= thsn_get_base_option('footer-box-'.$x.'-content');
			$icon		= thsn_get_base_option('footer-box-'.$x.'-icon');

			if( !empty($icon) ){
				$icon = explode(';',$icon);
				$icon = $icon[0];
				// load icon library
				$icon_array = explode(' ',$icon);
				$icon_prefix = $icon_array[0];
				$lib_list_array = thsn_icon_library_list();
				foreach($lib_list_array as $lib_id=>$lib_data){
					if( $lib_data['common_class']==$icon_prefix ){
						wp_enqueue_style( $lib_id );
					}
				}
				$icon_html = '<i class="'.esc_attr($icon).'"></i>';
			}

			if( !empty($title) ){
				$footer_box_content[] = '<div class="thsn-footer-contact-info"><div class="thsn-footer-contact-info-inner">
				' . thsn_esc_kses($icon_html) . '
				<span class="thsn-label thsn-label-'.esc_attr($x).'">'.esc_html($title).'</span> '.esc_html($desc).'
			</div></div>';
			}
		}

		/* Footer Copyright Content area - column class */
		switch( count($footer_box_content) ){
			case 1;
				$footer_box_class = 'col-md-12';
				break;
			case 2;
				$footer_box_class = 'col-md-6';
				break;
			case 3;
				$footer_box_class = 'col-md-4';
				break;
		}

		$footer_boxes_html = '';
		if( !empty($footer_box_content) && count($footer_box_content)>0 ){
			$x = 1;
			foreach( $footer_box_content as $content ){
				if( !empty($title) ){
					$footer_boxes_html .= thsn_esc_kses('<div class="thsn-footer-boxes thsn-footer-boxes-'.$x.'">'.thsn_esc_kses($content).'</div>');
					$x++;
				}
			}
		} // if

		$footer_boxes_social		= thsn_get_base_option('footer-boxes-social');
		$social_html = '';
		if( $footer_boxes_social == true && shortcode_exists('thsn-social-links') ){
			$social_html = do_shortcode('[thsn-social-links]');
		}

		$col_class1 = 'col-md-12';
		$col_class2 = 'col-md-12';
		if( !empty($footer_boxes_html) && !empty($social_html) ){
			$col_class1 = 'col-md-8';
			$col_class2 = 'col-md-4';
		}

		if( !empty($social_html) || !empty($footer_boxes_html) ){ ?>
			<div class="thsn-footer-section thsn-footer-big-area-wrapper"><div class="footer-wrap thsn-footer-big-area"><div class="container"><div class="row thsn-bg-color-globalcolor align-items-center">
		<?php }
		?>

			<?php if( !empty($footer_boxes_html) ) { ?>
				<div class="<?php echo esc_attr($col_class1); ?>">
				<?php echo thsn_esc_kses($footer_boxes_html); ?>
				</div>
			<?php } ?>

			<?php if( !empty($social_html) ) { ?>
				<div class="<?php echo esc_attr($col_class2); ?>"><div class="thsn-footer-social-area">
				<?php echo thsn_esc_kses($social_html); ?>
				</div></div>
			<?php } ?>

		<?php if( !empty($social_html) || !empty($footer_boxes_html) ){ ?>
			</div></div></div></div>
		<?php }

	}
}
}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 */
if( !function_exists('thsn_color_luminance') ) {
function thsn_color_luminance( $hex='#ff0000', $percent='0.1' ) {
	$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
	$new_hex = '#';
	if ( strlen( $hex ) < 6 ) {
		$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
	}
	// convert to decimal and change luminosity
	for ($i = 0; $i < 3; $i++) {
		$dec = hexdec( substr( $hex, $i*2, 2 ) );
		$dec = min( max( 0, $dec + $dec * $percent ), 255 );
		$new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
	}
	return $new_hex;
}
}

/*
 *  Main logo
 */
if( !function_exists('thsn_logo') ) {
function thsn_logo( $inneronly='' ) {
	$return				= '';
	$logo_img			= '';
	$main_logo			= thsn_get_base_option('logo');
	$sticky_logo		= thsn_get_base_option('sticky-logo');
	$responsive_logo	= thsn_get_base_option('responsive-logo');
	if( !empty($main_logo) ){
		$logo_img .= '<img class="thsn-main-logo" src="'.esc_url($main_logo).'" alt="' . get_bloginfo( 'name' ) . '" title="' . get_bloginfo( 'name' ) . '" />';
	}
	if( !empty($sticky_logo) ){
		$logo_img .= '<img class="thsn-sticky-logo" src="'.esc_url($sticky_logo).'" alt="' . get_bloginfo( 'name' ) . '" title="' . get_bloginfo( 'name' ) . '" />';
	}
	if( !empty($responsive_logo) ){
		$logo_img .= '<img class="thsn-responsive-logo" src="'.esc_url($responsive_logo).'" alt="' . get_bloginfo( 'name' ) . '" title="' . get_bloginfo( 'name' ) . '" />';
	}
	if( !empty($logo_img) ){
		if( $inneronly=='yes' ){
			$return .= '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . thsn_esc_kses($logo_img) . '</a>';
		} else {
			$return .= ( is_front_page() ) ? '<h1 class="site-title">' : '<div class="site-title">' ;
			$return .= '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
			$return .= ( is_front_page() ) ? '<span class="site-title-text">' . get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' ) . '</span>' : '' ;
			$return .= thsn_esc_kses($logo_img);
			$return .= '</a>';
			$return .= ( is_front_page() ) ? '</h1>' : '</div>' ;
		}
	}
	return thsn_esc_kses($return);
}
}

/*
 *  Main logo
 */
if( !function_exists('thsn_footer_logo') ) {
function thsn_footer_logo( $inneronly='' ) {
	$return				= '';
	$footer_logo_img	= '';
	$footer_logo		= thsn_get_base_option('footer-logo');

	if( !empty($footer_logo) ){
		$footer_logo_img = '<img class="thsn-footer-logo" src="'.esc_url($footer_logo).'" alt="' . get_bloginfo( 'name' ) . '" title="' . get_bloginfo( 'name' ) . '" />';
	}

	if( !empty($footer_logo_img) ){
		if( $inneronly=='yes' ){
			$return .= thsn_esc_kses($footer_logo_img);
		} else {
			$return .= '<div class="thsn-footer-logo">' . thsn_esc_kses($footer_logo_img) . '</div>';
		}
	}

	if( $inneronly=='checkonly' ){
		if( !empty($footer_logo) ){
			return true;
		} else {
			return false;
		}
	} else {
		return thsn_esc_kses($return);
	}
}
}

/*
 *  HTML Filter
 */
if( !function_exists('thsn_esc_kses') ) {
function thsn_esc_kses( $html = '' ) {
	$return = '';
	$allowed_html = array(
		'p'	=> array(
			'class'		=> array(),
			'id'		=> array(),
		),
		'noscript'	=> array(),
		'a'			=> array(
			'class'			  => array(),
			'href'			  => array(),
			'title'			  => array(),
			'target'		  => array(),
			'rel'			  => array(),
			'data-sortby'	  => array(),
			'data-balloon-pos'=> array(),
			'data-balloon'	  => array()
		),
		'button'	=> array(
			'class'		=> array(),
			'href'		=> array(),
			'title'		=> array(),
		),
		'ul'		=> array(
			'class'		=> array(),
		),
		'ol'		=> array(
			'class'		=> array(),
		),
		'li'		=> array(
			'class'			=> array(),
			'data-content'	=> array(),
		),
		'br'		=> array(),
		'em'		=> array(),
		'strong'	=> array(),
		'i'			=> array(
			'class'		=> array(),
			'style'		=> array(),
		),
		'small'	=> array(
			'name'			=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'div'		=> array(
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
			'role'			=> array(),
			'data-bg'		=> array(),
			'data-iconset'	=> array(),
			'data-icon'		=> array(),
			'data-show'		=> array(),
			'data-columns'	=> array(),
			'data-appear-animation'	=> array(),
			'data-from'			=> array(),
			'data-to'			=> array(),
			'data-interval'		=> array(),
			'data-before'		=> array(),
			'data-before-style'	=> array(),
			'data-after'		=> array(),
			'data-after-style'	=> array(),
			'data-digit'		=> array(),
			'data-fill'			=> array(),
			'data-emptyfill'	=> array(),
			'data-thickness'	=> array(),
			'data-filltype'		=> array(),
			'data-gradient1'	=> array(),
			'data-gradient2'	=> array(),
			'data-loop'			=> array(),
			'data-autoplay'		=> array(),
			'data-center'		=> array(),
			'data-nav'			=> array(),
			'data-dots'			=> array(),
			'data-autoplayspeed'=> array(),
			'data-margin'		=> array(),
			'data-tag'			=> array(),
			'data-id'			=> array(),
			'data-model-id'		=> array(),
			'data-shortcode-controls'		=> array(),
		),
		'span'		=> array(
			'class'				=> array(),
			'id'				=> array(),
			'style'				=> array(),
			'data-appear-animation'	=> array(),
			'data-from'			=> array(),
			'data-to'			=> array(),
			'data-interval'		=> array(),
			'data-before'		=> array(),
			'data-before-style'	=> array(),
			'data-after'		=> array(),
			'data-after-style'	=> array(),
			'data-digit'		=> array(),
			'data-fill'			=> array(),
			'data-emptyfill'	=> array(),
			'data-thickness'	=> array(),
			'data-filltype'		=> array(),
			'data-gradient1'	=> array(),
			'data-gradient2'	=> array(),
			'data-percentage-value'	=> array(),
			'data-value'		=> array(),
		),
		'h1'			=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
		),
		'h2'			=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
		),
		'h3'			=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
		),
		'h4'			=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
		),
		'h5'			=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
		),
		'h6'			=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
		),
		'header'	=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
		),
		'img'		=> array(
			'class'		=> array(),
			'src'		=> array(),
			'alt'		=> array(),
			'title'		=> array(),
			'width'		=> array(),
			'height'	=> array(),
			'srcset'	=> array(),
			'sizes'		=> array(),
			'data-id'	=> array(),
			'data-srcset' => array(),
			'data-src'	=> array(),
		),
		'time'	=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
			'datetime'	=> array(),
		),
		'iframe'	=> array(
			'class'		=> array(),
			'id'		=> array(),
			'style'		=> array(),
			'width'		=> array(),
			'height'	=> array(),
			'src'		=> array(),
			'frameborder'	=> array(),
			'allow'		=> array(),
			'allowfullscreen'	=> array(),
		),
		'blockquote'	=> array(
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'article'	=> array(
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'input'	=> array(
			'type'			=> array(),
			'name'			=> array(),
			'value'			=> array(),
			'placeholder'	=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
			'checked'		=> array(),
		),
		'textarea'	=> array(
			'name'			=> array(),
			'value'			=> array(),
			'placeholder'	=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'form'	=> array(
			'name'			=> array(),
			'method'		=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
			'data-id'		=> array(),
			'data-name'		=> array(),
		),
		'label'	=> array(
			'for'			=> array(),
			'name'			=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'aside'	=> array(
			'name'			=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'sup'	=> array(
			'class'			=> array(),
		),
		'sub'	=> array(
			'class'			=> array(),
		),
	);
	if( !empty($html) ){
		$return = wp_kses($html, $allowed_html);
	}
	return $return;
}
}

if ( !function_exists( 'thsn_header_slider' ) ){
function thsn_header_slider(){
	if( is_page() || is_singular() ){
		$slider_type = get_post_meta( get_the_ID(), 'thsn-slider-type', true );
		if( !empty($slider_type) ){
			// Check if Slider Revolution
			if( $slider_type=='revolution-slider' ){
				$slider_slug = get_post_meta( get_the_ID(), 'thsn-revolution-slider', true );
				if( !empty($slider_slug) ){
					echo thsn_esc_kses('<div class="thsn-slider-area">');
					add_revslider( $slider_slug );
					echo thsn_esc_kses('</div>');
				}
			} else if( $slider_type=='custom-code' ){
				$custom_slider_code = get_post_meta( get_the_ID(), 'thsn-custom-slider-code', true );
				if( !empty($custom_slider_code) ){
					echo thsn_esc_kses('<div class="thsn-slider-area">');
					echo do_shortcode( thsn_esc_kses($custom_slider_code) );
					echo thsn_esc_kses('</div>');
				}
			}
		}
	}
}
}

if ( !function_exists( 'thsn_get_featured_data' ) ){
function thsn_get_featured_data( $settings = array() ){
	$return				= '';
	$post_id			= ( !empty($settings['post_id']) ) ? $settings['post_id'] : get_the_ID() ;
	$post_type			= get_post_type();
	$get_post_format	= get_post_format( $post_id );
	$post_format		= ( !empty( $get_post_format ) ) ? $get_post_format : 'standard' ;
	$featured_img_only	= ( isset($settings['featured_img_only']) && $settings['featured_img_only']==true ) ? true : false ;
	$image_size			= ( !empty($settings['size']) ) ? $settings['size'] : 'full' ;
	// for portfolio
	if( is_singular('thsn-portfolio') ){
		$post_format = get_post_meta( $post_id, 'thsn-featured-type', true );
		$post_format = ($post_format=='slider') ? 'gallery' : $post_format ;
	}
	if( $featured_img_only==true || !in_array($post_format, array('audio', 'video', 'gallery', 'quote', 'link')) ){
		if ( has_post_thumbnail( $post_id ) ) {
			if( !is_singular() ) { $return .= '<a href="' . get_permalink( $post_id ) . '">'; }
			$return .= get_the_post_thumbnail( $post_id, $image_size );
			if( !is_singular() ) { $return .= '</a>'; }
		};
	} else {

		switch( $post_format ){

			case 'audio' :
				$audio_code = get_post_meta( $post_id, 'thsn-pformat-audio', true );
				if( is_singular('thsn-portfolio') ){
					$audio_code = get_post_meta( $post_id, 'thsn-audio-url', true );
				}
				$attr = array(
					'width'		=> 725,
					'height'	=> 400
				);
				$return .= wp_oembed_get( $audio_code, $attr );
				break;

			case 'video' :
				$video_code = get_post_meta( $post_id, 'thsn-pformat-video', true );
				if( is_singular('thsn-portfolio') ){
					$video_code = get_post_meta( $post_id, 'thsn-video-url', true );
				}
				$attr = array(
					'width'		=> 725,
					'height'	=> 400
				);
				$return .= wp_oembed_get( $video_code, $attr );
				break;

			case 'gallery' :
				// Enqueue scripts and styles
				wp_enqueue_script( 'lightslider' );
				wp_enqueue_style( 'lightslider' );
				$images = get_post_meta( $post_id, 'thsn-pformat-gallery', true );
				if( !empty($images) ){
					$images_array = explode(',',$images);
					foreach( $images_array as $image_id ){
						$return .= '<div class="thsn-gallery-image">'.wp_get_attachment_image($image_id, $image_size).'</div>';
					}
				}
				if( !empty($return) ){
					$return = '<div class="thsn-gallery">'.$return.'</div>';
				}
				break;

			case 'quote' :
				$name		= get_post_meta( $post_id, 'thsn-pformat-quote-source-name', true );
				$url		= get_post_meta( $post_id, 'thsn-pformat-quote-source-url', true );
				$content	= get_the_content();
				if( !empty($url) && !empty($name) ){
					$name = '<a href="'.$url.'">'.$name.'</a>';
				}
				if( !empty($name) ){
					$name = '<div class="thsn-block-quote-citation">'.$name.'</div>';
				}
				if( !empty($content) ){
					$return .= '<div class="thsn-block-quote-content">'.nl2br($content) . $name .'</div>';
				}
				if( !empty($return) ){
					if( has_post_thumbnail($post_id) ){
						$bg_src = get_the_post_thumbnail_url($post_id);
						if( !empty($bg_src) ){
							thsn_inline_css( '.thsn-block-quote-wrapper-' . esc_attr($post_id) . '{background-image:url(\'' . esc_url($bg_src) . '\');}' );
						}
					}
					if( strpos($return, '<blockquote') === false ){
						$return = '<blockquote class="thsn-block-quote">'.$return.'</blockquote>';
					}
					$return = '<div class="thsn-block-quote-wrapper thsn-block-quote-wrapper-'.$post_id.'">'.$return.'</div>';
				}
				break;

			case 'link' :
				$link		= get_post_meta( $post_id, 'thsn-pformat-link-url', true );
				$title		= get_post_meta( $post_id, 'thsn-pformat-link-title', true );
				if( empty($title) ){ $title = get_post_meta( $post_id, 'thsn-pformat-link-url', true ); }

				if( !empty($link) ){
					$return = '<a href="'.$link.'">'.$title.'</a>';
				}
				if( !empty($return) ){
					if( has_post_thumbnail($post_id) ){
						$bg_src = get_the_post_thumbnail_url($post_id);
						if( !empty($bg_src) ){
							thsn_inline_css( '.thsn-link-wrapper-' . esc_attr($post_id) . '{background-image:url(\'' . esc_url($bg_src) . '\');}' );
						}
					}
					$return = '<div class="thsn-link-wrapper thsn-link-wrapper-'.$post_id.'"><div class="thsn-link-inner">'.$return.'</div></div>';
				}
				break;

		}

	}
	if( !empty($return) ){
		$return = '<div class="thsn-featured-wrapper">'.$return.'</div>';
		echo thsn_esc_kses($return);
	}
}
}

if ( !function_exists( 'thsn_hex2rgb' ) ){
function thsn_hex2rgb($color, $opacity='1'){
    $default = 'rgb(0,0,0)';
    if (empty($color))
        return $default;
    if ($color[0] == '#')
        $color = substr($color, 1);
    if (strlen($color) == 6)
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    elseif (strlen($color) == 3)
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    else
        return $default;
    $rgb = array_map('hexdec', $hex);
    if ($opacity) {
        if (abs($opacity) > 1)
            $opacity = 1.0;
        $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode(",", $rgb) . ')';
    }
    return $output;
}
}

if( !function_exists('thsn_element_block_container') ){
function thsn_element_block_container( $settings = array( 'position' => 'start', 'column' => '3', 'cpt' => 'blog', 'taxonomy' => 'category', 'style' => '1', 'odd_even' => '', 'col_odd_even' => '' ) ){
	$return = '';
	$cpt	= ( !empty($settings['cpt']) ) ? $settings['cpt'] : 'blog' ;
	$style	= ( !empty($settings['style']) ) ? $settings['style'] : '1' ;
	$terms	= '';
	if( !empty($settings['taxonomy']) ){
		$terms = get_the_terms( get_the_ID(), $settings['taxonomy'] );
	}
	$odd_even_class = '';
	if( !empty($settings['odd_even']) ){
		$odd_even_class = 'thsn-' . $settings['odd_even'] ;
	}
	$col_odd_even_class = '';
	if( !empty($settings['col_odd_even']) ){
		$col_odd_even_class = 'thsn-col-' . $settings['col_odd_even'] ;
	}
	$term_slug = '';
	if( is_array($terms) && count($terms)>0 ){
		foreach( $terms as $term ){
			$term_slug .= $term->slug.' ';
		}
		$term_slug = trim($term_slug);
	}

	$style_class = 'thsn-'.$cpt.'-style-'.$style;

	$column_class = '';

	if( $settings['position']=='start' ){
		switch( $settings['column'] ){
			case '1':
				$column_class = 'col-md-12';
			break;
			case '2':
				$column_class = 'col-md-6';
			break;
			case '3':
				$column_class = 'col-md-4';
			break;
			case '4':
				$column_class = 'col-md-6 col-lg-3';
			break;
			case '5':
				$column_class = 'col-md-20percent';
			break;
			case '6':
				$column_class = 'col-md-2';
				break;
		}

		$return = '<article class="thsn-ele thsn-ele-'.esc_attr($cpt).' '.esc_attr($style_class).' '.esc_attr($column_class).' '.esc_attr($term_slug).' '.esc_attr($odd_even_class).' '.esc_attr($col_odd_even_class).'">';

	} else {
		$return = '</article>';
	}
	return thsn_esc_kses($return);
}
}

/**
 *
 */
if( !function_exists('thsn_client_hover_img') ){
function thsn_client_hover_img(){
	$return = '';
	$hover_logo = get_post_meta( get_the_ID(), 'thsn-logo-image-for-hover', true );
	if( !empty($hover_logo) ){
		$hover_image = wp_get_attachment_image_src($hover_logo, 'full');
		if( !empty($hover_image[0]) ){
			$return = '<div class="thsn-client-hover-img"><img src="'.esc_url($hover_image[0]).'" alt /></div>';
		}
	}
	return thsn_esc_kses($return);
}
}

if( !function_exists('thsn_client_logo_link') ){
function thsn_client_logo_link( $type='start' ){
	$return = '';
	$link = get_post_meta( get_the_ID(), 'thsn-logo-link', true );
	if( !empty($link['url']) ){
		if( $type=='start' ){
			$target_code = '';
			if( !empty($link['target']) && $link['target']=='_blank' ){ $target_code = ' target="_blank"'; }
			$return = '<a href="' . esc_url($link['url']) . '" title="' . esc_attr($link['title']) . '"' . $target_code . '>';
		} else {
			$return = '</a>';
		}
	}
	echo thsn_esc_kses($return);
}
}

/*
 *  Titlebar Breadcrumb
 */
if( !function_exists('thsn_titlebar_breadcrumb') ){
function thsn_titlebar_breadcrumb(){
	$return = '';
	$hide_breadcrumb = thsn_get_base_option('titlebar-hide-breadcrumb');
	if(function_exists('bcn_display') && $hide_breadcrumb!=true ){
		$return = '<div class="thsn-breadcrumb"><div class="thsn-breadcrumb-inner">' . bcn_display(true) . '</div></div>';
	}
	return thsn_esc_kses($return);
}
}

if( !function_exists('thsn_titlebar_headings') ){
function thsn_titlebar_headings(){
	$title		= get_the_title();
	$subtitle	= '';
	if( is_singular() || is_home() ){
		if( is_home() || is_singular('post') ){
			$page_id	= get_option( 'page_for_posts' );
			$title		= esc_attr__( 'Blog', 'itinc' );  // Setting for Titlebar title
			if( is_singular('post') ){
				$title		= esc_attr__( 'Blog', 'itinc' );  // Setting for Titlebar title
			}
		} else if( is_singular('thsn-team-member') ){
			$page_id	= get_the_ID();
			$cpt_title	= thsn_get_base_option('team-cpt-singular-title');
			$title		= sprintf( esc_attr__( '%1$s ', 'itinc' ), $cpt_title );  // Setting for Titlebar title
		} else {
			$page_id	= get_the_ID();
		}
		$single_title		= get_post_meta( $page_id, 'thsn-titlebar-title', true );
		$single_subtitle	= get_post_meta( $page_id, 'thsn-titlebar-subtitle', true );
		$title				= ( !empty($single_title) )		? trim($single_title)		: $title ;
		$subtitle			= ( !empty($single_subtitle) )	? trim($single_subtitle)	: $subtitle ;
		// Single post custom title and subtitle
		if( is_home() || is_singular('post') ){
			$current_single_title		= get_post_meta( get_the_ID(), 'thsn-titlebar-title', true );
			$current_single_subtitle	= get_post_meta( get_the_ID(), 'thsn-titlebar-subtitle', true );
			$title				= ( !empty($current_single_title) )		? trim($current_single_title)		: $title ;
			$subtitle			= ( !empty($current_single_subtitle) )	? trim($current_single_subtitle)	: $subtitle ;
		}
		if( function_exists('is_woocommerce') && is_woocommerce() ){ // WooCommerce
			$title	= thsn_get_base_option('wc-title');
			$subtitle = '';
		}
	} else if( function_exists('is_woocommerce') && is_woocommerce() && !is_product() ){ // WooCommerce
		$title	= thsn_get_base_option('wc-title');
		$subtitle = '';
	} else if( is_category() ){ // Category
		$title = sprintf(
			esc_attr__('Category: %s', 'itinc'),
			esc_attr( single_cat_title( '', false) )
		);
	} else if( is_author() ){ // Author
		global $post;
		$author_id = $post->post_author;
		$title	   = sprintf(
			esc_attr__('Author: %s', 'itinc'),
			get_the_author_meta( 'display_name', $author_id )
		);
	} else if( is_tax() ){ // Taxonomy
		global $wp_query;
		$tax = $wp_query->get_queried_object();
		$title = esc_attr($tax->name);
	} else if( is_tag() ){ // Tag
		$title = sprintf(
			esc_attr__('Tag: %s','itinc'),
			esc_attr( single_tag_title( '', false) )
		);
	} else if( is_404() ){ // 404
		$title = esc_attr__( 'PAGE NOT FOUND', 'itinc' );
	} else if( is_search()  ){ // Search Results
		$title = sprintf( esc_attr__( 'Search Results for %s', 'itinc' ), ' <span class="thsn-tbar-search-word">' . get_search_query() . '</span>' );
	} else if( is_archive() ){
		$title = esc_attr__( 'Archives', 'itinc' );
	} else {
		$title = get_the_title();
	}
	// return data
	$return  = '';
	$return .= ( !empty($title) ) ? '<h1 class="thsn-tbar-title"> '. do_shortcode($title) . '</h1>' : '' ;
	$return .= ( !empty($subtitle) ) ? '<h3 class="thsn-tbar-subtitle"> '. do_shortcode($subtitle) .'</h3>' : '' ;
	if( $return!='' ){
		$return = '<div class="thsn-tbar"><div class="thsn-tbar-inner container">'.$return.'</div></div>';
	}
	// Return data
	return thsn_esc_kses($return);
}
}

if( !function_exists('thsn_sticky_class') ){
function thsn_sticky_class(){
	$return = '';
	$class = array();
	if( thsn_get_base_option('sticky-header')=='1' ) {
		$class[] = 'thsn-header-sticky-yes';
		$class[] = 'thsn-sticky-type-'. thsn_get_base_option('sticky-type');
	}
	// Sticky
	if( thsn_get_base_option('sticky-header')=='1' ){
		$class[] = 'thsn-sticky-bg-color-'. thsn_get_base_option('sticky-header-bgcolor');
	}
	if( !empty($class) ){
		$return = implode( ' ', $class );
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_header_class') ){
function thsn_header_class(){
	$return = '';
	$class = array();
	// Check if sticky logo exists
	$sticky_logo				= thsn_get_base_option('sticky-logo');
	$responsive_logo			= thsn_get_base_option('responsive-logo');
	$responsive_header_bgcolor	= thsn_get_base_option('responsive-header-bgcolor');
	if( !empty($sticky_logo) ){
		$class[] = 'thsn-sticky-logo-yes';
	} else {
		$class[] = 'thsn-sticky-logo-no';
	}
	if( !empty($responsive_logo) ){
		$class[] = 'thsn-responsive-logo-yes';
	} else {
		$class[] = 'thsn-responsive-logo-no';
	}
	if( !empty($responsive_header_bgcolor) ){
		$class[] = 'thsn-responsive-header-bgcolor-'.$responsive_header_bgcolor;
	}
	if( !empty($class) ){
		$return = implode( ' ', $class );
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_header_bg_class') ){
function thsn_header_bg_class(){
	$return  = 'thsn-header-wrapper';
	$bgcolor = thsn_get_base_option('header-bgcolor');
	if( !empty($bgcolor) ){
		$return .= ' thsn-bg-color-'. thsn_get_base_option('header-bgcolor');
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_header_box_contents') ){
function thsn_header_box_contents( $settings = array() ){
	for( $i=1 ; $i<=3 ; $i++ ){
		$title		= thsn_get_base_option( 'header-box'.$i.'-title' );
		$content	= thsn_get_base_option( 'header-box'.$i.'-content' );
		$link		= thsn_get_base_option( 'header-box'.$i.'-link' );
		$icon		= thsn_get_base_option( 'header-box'.$i.'-icon' );
		if( !empty($icon) ){
			$icon = explode(';',$icon);
			$icon = $icon[0];
			// load icon library
			$icon_array = explode(' ',$icon);
			$icon_prefix = $icon_array[0];
			$lib_list_array = thsn_icon_library_list();
			foreach($lib_list_array as $lib_id=>$lib_data){
				if( $lib_data['common_class']==$icon_prefix ){
					wp_enqueue_style( $lib_id );
				}
			}
		}
		if( !empty($title) || !empty($content) ){
			?>
			<div class="thsn-header-box thsn-header-box-<?php echo esc_attr($i); ?>">
				<?php if( !empty($link) ) : ?><a href="<?php echo esc_url($link); ?>"><?php endif; ?>
					<?php if( !empty($icon) ) : ?><span class="thsn-header-box-icon"><i class="<?php echo esc_attr($icon); ?>"></i></span><?php endif; ?>
					<span class="thsn-header-box-title"><?php echo esc_html($title); ?></span>
					<span class="thsn-header-box-content"><?php echo esc_html($content); ?></span>
				<?php if( !empty($link) ) : ?></a><?php endif; ?>
			</div>
			<?php
		}
	} // for loop
}
}

if( !function_exists('thsn_header_button') ){
function thsn_header_button( $settings = array() ){
	$btn_text  = thsn_get_base_option('header-btn-text');
	$btn_url   = thsn_get_base_option('header-btn-url');
	if( (!empty($btn_text)) && !empty($btn_url) ){
		?>
		<?php if( isset($settings['inneronly']) && $settings['inneronly']=='yes' ){ ?>
			<?php // No wrapper needed ?>
		<?php } else { ?>
			<div class="thsn-header-button">
		<?php } ?>
		<a href="<?php echo esc_url($btn_url); ?>">
			<?php if(!empty($btn_text)) : ?><?php echo esc_html($btn_text); ?><?php endif; ?>
		</a>
		<?php if( isset($settings['inneronly']) && $settings['inneronly']=='yes' ){ ?>
			<?php // No wrapper needed ?>
		<?php } else { ?>
			</div>
		<?php } ?>
		<?php
	}
}
}

if( !function_exists('thsn_header_search') ){
function thsn_header_search(){
	$header_search = thsn_get_base_option('header-search');
	if( !empty($header_search) && $header_search=='1' ){
		?>
		<div class="thsn-header-search-btn"><a href="#"><i class="thsn-base-icon-search-1"></i></a></div>
		<?php
	}
}
}

if( !function_exists('thsn_nav_class') ){
function thsn_nav_class(){
	$return = '';
	$main_active_link_color = thsn_get_base_option('main-menu-active-color');
	$drop_active_link_color = thsn_get_base_option('drop-down-menu-active-color');
	if( !empty($main_active_link_color) ){
		$return .= ' thsn-main-active-color-'.$main_active_link_color;
	}
	if( !empty($drop_active_link_color) ){
		$return .= ' thsn-dropdown-active-color-'.$drop_active_link_color;
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_preheader_class') ){
function thsn_preheader_class(){
	$return = '';
	$bgcolor = thsn_get_base_option('preheader-bgcolor');
	$textcolor = thsn_get_base_option('preheader-text-color');
	if( !empty($bgcolor) ){
		$return .= ' thsn-bg-color-'.$bgcolor;
	}
	if( !empty($textcolor) ){
		$return .= ' thsn-color-'.$textcolor;
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_footer_classes') ){
function thsn_footer_classes(){
	$return = '';
	$footer_boxes_area = thsn_get_base_option('footer-boxes-area');
	if( $footer_boxes_area == true ){
		$return .= ' thsn-footer-3-boxes-exists';
	} else {
		$return .= ' thsn-footer-3-boxes-not';
	}
	$textcolor = thsn_get_base_option('footer-text-color');
	if( !empty($textcolor) ){
		$return .= ' thsn-text-color-'.$textcolor;
	}
	$bgcolor = thsn_get_base_option('footer-bgcolor');
	if( !empty($bgcolor) ){
		$return .= ' thsn-bg-color-'.$bgcolor;
	}
	$background = thsn_get_base_option('footer-background');
	if( !empty($background['background-image']) ){
		$return .= ' thsn-bg-image-yes';
	}
	if ( has_nav_menu( 'themesion-footer' ) ){
		$return .= ' thsn-footer-menu-yes';
	} else {
		$return .= ' thsn-footer-menu-no';
	}
	$footer_widget_columns	= thsn_footer_widget_columns(); // array
	if( $footer_widget_columns[0]==false ){
		$return .= ' thsn-footer-widget-no';
	} else {
		$return .= ' thsn-footer-widget-yes';
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_footer_widget_classes') ){
function thsn_footer_widget_classes(){
	$return = '';
	$textcolor = thsn_get_base_option('footer-widget-text-color');
	$switch    = thsn_get_base_option('footer-widget-color-switch');
	if( !empty($textcolor) && $switch=='1' ){
		$return .= ' thsn-color-'.$textcolor;
	}
	$bgcolor = thsn_get_base_option('footer-widget-bgcolor');
	if( !empty($bgcolor) ){
		$return .= ' thsn-bg-color-'.$bgcolor;
	}
	$background = thsn_get_base_option('footer-widget-background');
	if( !empty($background['background-image']) ){
		$return .= ' thsn-bg-image-yes';
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_footer_widget_columns') ){
function thsn_footer_widget_columns(){
	$return			= array(false, false, false);
	$widget_exists	= false;
	$footer_column	= thsn_get_base_option('footer-column');
	$footer_column	= ( empty($footer_column) ) ? '3-3-3-3' : $footer_column ;
	if( $footer_column=='custom' ){
		$footer_column_1	= thsn_get_base_option('footer-1-col-width');
		$footer_column_2	= thsn_get_base_option('footer-2-col-width');
		$footer_column_3	= thsn_get_base_option('footer-3-col-width');
		$footer_column_4	= thsn_get_base_option('footer-4-col-width');
		$footer_column_array = array();
		if( !empty($footer_column_1) && $footer_column_1!='hide' ){ $footer_column_array[] = 'yes'; }
		if( !empty($footer_column_2) && $footer_column_2!='hide' ){ $footer_column_array[] = 'yes'; }
		if( !empty($footer_column_3) && $footer_column_3!='hide' ){ $footer_column_array[] = 'yes'; }
		if( !empty($footer_column_4) && $footer_column_4!='hide' ){ $footer_column_array[] = 'yes'; }
		if( count($footer_column_array)=='1' ){
			$footer_column = '12';
		} else if( count($footer_column_array)=='2' ){
			$footer_column = '6-6';
		} else if( count($footer_column_array)=='3' ){
			$footer_column = '4-4-4';
		} else if( count($footer_column_array)=='4' ){
			$footer_column = '3-3-3-3';
		}
	}
	if( !empty($footer_column) ){
		$footer_columns	= explode('-', $footer_column );
		// Checking if widget exists
		if( is_array($footer_columns) && count($footer_columns)>0 ){
			$col = 1;
			foreach( $footer_columns as $column ){
				if ( is_active_sidebar( 'thsn-footer-'.$col ) ){
					$widget_exists = true;
				}
				$col++;
			} // end foreach
		}
		$return = array( $widget_exists, $footer_columns, $footer_column );
	}
	return $return;
}
}

if( !function_exists('thsn_footer_copyright_classes') ){
function thsn_footer_copyright_classes(){
	$return = '';
	$textcolor = thsn_get_base_option('footer-copyright-text-color');
	$switch    = thsn_get_base_option('footer-copyright-color-switch');
	if( !empty($textcolor) && $switch=='1' ){
		$return .= ' thsn-color-'.$textcolor;
	}
	$bgcolor = thsn_get_base_option('footer-copyright-bgcolor');
	if( !empty($bgcolor) ){
		$return .= ' thsn-bg-color-'.$bgcolor;
	}
	$background = thsn_get_base_option('footer-copyright-background');
	if( !empty($background['background-image']) ){
		$return .= ' thsn-bg-image-yes';
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_all_options_values') ){
function thsn_all_options_values( $for='typography', $admin=false ) {
	$return			= '';
	$css_code		= '';
	include( get_template_directory() . '/includes/customizer-options.php' );
	foreach( $kirki_options_array as $options_key=>$options_val ){
		if( !empty( $options_val['section_fields'] ) ){
			foreach( $options_val['section_fields'] as $key=>$option ){
				if( !empty($option['type']) && $option['type']==$for && !empty($option['default']) && !empty($option['thsn-output']) ){
					$class		= $option['thsn-output'];
					$css_code	= '';
					$values = thsn_get_base_option( $option['settings'] );
					foreach( $values as $key=>$val ){
						if( !empty($val) && $key!='variant' ){
							if( $key == 'background-image' ){
								$val = 'url("'.$val.'")';
							} else if( $key == 'font-family' ){
								$val = trim($val);
								if( substr($val, -1)!=',' ){ $val = $val.','; }
								$val = $val.'sans-serif';
							}
							$css_code .= $key.':'.$val.';';
						}
					}
					if($admin==true){
						if( $class=='body' ){
							$class = $class.esc_attr('#tinymce.wp-editor');
						} else {
							$class = esc_attr('body#tinymce.wp-editor ').$class;
						}
					}
					$return .= $class.'{'.$css_code.'}';
				}
			}
		}
	}
	return $return;
}
}

if( !function_exists('thsn_titlebar_class') ){
function thsn_titlebar_class(){
	$return = '';
	$bgcolor = thsn_get_base_option('titlebar-bgcolor');
	if( !empty($bgcolor) ){
		$return .= ' thsn-bg-color-'.$bgcolor;
	}
	$background = thsn_get_base_option('titlebar-background');
	if( !empty($background['background-image']) ){
		$return .= ' thsn-bg-image-yes';
	}
	$style = thsn_get_base_option('titlebar-style');
	if( !empty($style) ){
		$return .= ' thsn-titlebar-style-'.$style;
	}
	echo esc_attr($return);
}
}

if( !function_exists('thsn_pagination') ){
function thsn_pagination(){
	$pagination = get_the_posts_pagination( array(
		'mid_size'	=> 5,
		'prev_text'	=> thsn_esc_kses('<i class="thsn-base-icon-left-open"></i>'),
		'next_text'	=> thsn_esc_kses('<i class="thsn-base-icon-right-open"></i>'),
	) );
	$return = '<div class="thsn-pagination">'.$pagination.'</div>';
	echo thsn_esc_kses($return);
}
}

if( !function_exists('thsn_meta_author') ){
function thsn_meta_author(){
	return '<span class="thsn-meta thsn-meta-author"><a class="thsn-author-link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><i class="thsn-base-icon-user-1"></i>' . get_the_author() . '</a></span>';
}
}

if( !function_exists('thsn_meta_date') ){
function thsn_meta_date( $date_format='', $optional=false ){
	$return = '';
	if( $optional==false || ( $optional==true && !defined('ITINC_ADDON_VERSION') ) ){
		if( empty($date_format) ){
			$date_format = get_option('date_format');
		}
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = sprintf( '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated thsn-hide" datetime="%3$s">%4$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				get_the_date( $date_format ),
				esc_attr( get_the_modified_date( 'c' ) ),
				get_the_modified_date( $date_format )
			);
		} else {
			$time_string = sprintf( '<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				get_the_date( $date_format ) // ,
			);
		}
		$return = '<span class="thsn-meta thsn-meta-date"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><i class="thsn-base-icon-calendar-2"></i>' . thsn_esc_kses($time_string) . '</a></span>';
	}
	return $return;
}
}

if( !function_exists('thsn_meta_category') ){
function thsn_meta_category( $separator = ', ' ){
	$return = '';
	$categories_list = get_the_category_list( $separator );
	if ( !empty($categories_list) ){
		$return = '<span class="thsn-meta thsn-meta-cat"><i class="thsn-base-icon-folder-open-empty"></i>' . thsn_esc_kses($categories_list) . '</span>';
	}
	return $return;
}
}

if( !function_exists('thsn_meta_tag') ){
function thsn_meta_tag( $separator = ', ', $title='' ){
	$return		= '';
	$tags_list	= get_the_tag_list( $title.' ' , $separator );
	if ( !empty($tags_list) ) {
		$return = '<span class="thsn-meta thsn-meta-tags"> ' . thsn_esc_kses($tags_list) . '</span>';
	}
	return $return;
}
}

if( !function_exists('thsn_meta_comment') ){
function thsn_meta_comment( $hide_zero=false ){
	$return = '';
	$comments_number = get_comments_number();
	if ( !post_password_required() && comments_open() ) {
		$return = '<span class="thsn-meta thsn-meta-comments thsn-comment-bigger-than-zero"><i class="thsn-base-icon-chat-2"></i> ' . get_comments_number_text( esc_attr__('No Comments','itinc'), esc_attr__('One Comment','itinc'), esc_attr__('% Comments','itinc') ) . '</span>';
	}
	return $return;
}
}

if( !function_exists('thsn_author_social_links') ){
function thsn_author_social_links(){
	$return = '';
	$social_list = array(
		'twitter'	=>	array(
			'name'			=> esc_attr('Twitter'),
			'link'			=> get_the_author_meta( 'twitter' ),
		),
		'facebook'	=>	array(
			'name'			=> esc_attr('Facebook'),
			'link'			=> get_the_author_meta( 'facebook' ),
		),
		'linkedin'	=>	array(
			'name'			=> esc_attr('LinkedIn'),
			'link'			=> get_the_author_meta( 'linkedin' ),
		),
		'google_plus'	=>	array(
			'name'			=> esc_attr('Google +'),
			'link'			=> get_the_author_meta( 'gplus' ),
		),
	);
	foreach( $social_list as $social_id => $social_data ){
		if( !empty($social_data['link']) ){
			$return .= '<li class="thsn-author-social-li thsn-author-social-'.esc_attr($social_id).'"><a href="'. esc_url($social_data['link']) .'" target="_blank"><i class="themesion-base-icon-twitter"></i><span class="themesion-hide">'. esc_attr($social_data['name']) .'</span></a></li>';
		}
	}
	if( !empty($return) ){
		$return = '<div class="thsn-author-social-icons"><ul class="thsn-author-social-ul">' . $return . '</ul> <!-- .thsn-author-social-ul -->  </div> <!-- .thsn-author-social-icons -->';
	}
	// Return data
	return thsn_esc_kses($return);
}
}

if( !function_exists('thsn_comments_list_template') ){
function thsn_comments_list_template($comment, $args, $depth) {
    if ( 'div' === $args['style'] ) {
        $tag		= 'div';
        $add_below	= 'comment';
    } else {
        $tag		= 'li';
        $add_below	= 'div-comment';
    }?>
    <<?php echo esc_attr($tag); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>"><?php
    if ( 'div' != $args['style'] ) { ?>
        <div id="div-comment-<?php comment_ID() ?>" class="thsn-comment"><?php
    } ?>
		<div class="thsn-comment-avatar"><?php
            if ( $args['avatar_size'] != 0 ) {
                echo get_avatar( $comment, $args['avatar_size'] );
            } ?>
        </div>
		<div class="thsn-comment-content">
			<div class="thsn-comment-meta">
				<span class="thsn-comment-author"><?php echo get_comment_author_link(); ?></span>
				<span class="thsn-comment-date">
					<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php printf( esc_attr_x( '%1$s ago', '%1$s = human-readable time difference', 'itinc' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?>
					</a>
					<?php edit_comment_link( esc_attr__( '(Edit)', 'itinc' ), '  ', '' ); ?>
				</span>
			</div>
			<?php
			if ( $comment->comment_approved == '0' ) { ?>
				<em class="thsn-comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'itinc' ); ?></em><br/><?php
			} ?>
			<?php comment_text(); ?>
			<div class="reply"><?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => $add_below,
								'depth'     => $depth,
								'max_depth' => $args['max_depth']
							)
						)
					); ?>
			</div>
		</div>
	<?php
    if ( 'div' != $args['style'] ) : ?>
        </div><?php
    endif;
	?>
	<?php
}
}

if( !function_exists('thsn_portfolio_details_list') ){
function thsn_portfolio_details_list() {
	$return = '';
	$lines = thsn_get_base_option('portfolio-details');
	$title = thsn_get_base_option('portfolio-details-title');
	if( !empty($lines) ){
		foreach( $lines as $line ){
			$line_id = trim($line['line_title']);
			$line_id = str_replace( ' ', '_', $line_id );
			$line_id = sanitize_html_class( strtolower( $line_id ) ) ;
			// Data
			if( $line['line_type']=='category-link' ){
				$line_data = get_the_term_list( get_the_ID(), 'thsn-portfolio-category', '', ', ' );
			} else if( $line['line_type']=='category' ){
				$line_data = strip_tags( get_the_term_list( get_the_ID(), 'thsn-portfolio-category', '', ', ' ) );
			} else {
				$line_data = get_post_meta( get_the_ID(), 'thsn-portfolio-details_'.$line_id, true );
			}
			if( !empty($line_data) ){
				$return .= '<li class="thsn-portfolio-line-li"> <span class="thsn-portfolio-line-title">' . esc_attr($line['line_title']) . ': </span> <span class="thsn-portfolio-line-value">' . thsn_esc_kses($line_data) . '</span></li>';
			}
		}
	}
	if( !empty($return) ){
		$return = '<div class="thsn-portfolio-lines-wrapper"><ul class="thsn-portfolio-lines-ul">' . $return . '</ul></div>';
	}
	if( !empty($title) ){
		$return = '<h3>' . esc_html($title) . '</h3> ' . $return;
	}
	echo thsn_esc_kses($return);
}
}

if( !function_exists('thsn_related_portfolio') ){
function thsn_related_portfolio() {
	$return			= '';
	$related_title	= thsn_get_base_option('portfolio-show-related');
	if($related_title==true){
		$related_title	= thsn_get_base_option('portfolio-related-title');
		$show			= thsn_get_base_option('portfolio-related-count');
		$column			= thsn_get_base_option('portfolio-related-column');
		$style			= thsn_get_base_option('portfolio-related-style');
		// Title
		if( !empty($related_title) ){
			$related_title = '<h3 class="thsn-related-title">'.$related_title.'</h3>';
		}
		$terms = wp_get_post_terms( get_the_ID(), 'thsn-portfolio-category' );
		$term_list = array();
		if( !empty($terms) ){
			foreach( $terms as $term ){
				$term_list[] = $term->slug;
			}
		}
		// Preparing $args
		$args = array(
			'post_type'				=> 'thsn-portfolio',
			'orderby'				=> 'rand',
			'posts_per_page'		=> $show,
			'ignore_sticky_posts'	=> true,
			'post__not_in'			=> array( get_the_ID() ),
			'tax_query'				=> array(
				array(
					'taxonomy' => 'thsn-portfolio-category',
					'field'    => 'slug',
					'terms'    => $term_list,
				),
			),
		);
		// Wp query to fetch posts
		$posts = new WP_Query( $args );
		$i = 1;
		if ( $posts->have_posts() ) {
			$return .= '<div class="thsn-element-posts-wrapper row multi-columns-row">';
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$class = $i%2 ? 'thsn-odd':'thsn-even';
				// Template
				if( file_exists( locate_template( '/theme-parts/portfolio/portfolio-style-'.esc_attr($style).'.php', false, false ) ) ){
					$return .= thsn_element_block_container( array(
						'position'	=> 'start',
						'column'	=> $column,
						'cpt'		=> 'portfolio',
					) );
					ob_start();
					include( locate_template( '/theme-parts/portfolio/portfolio-style-'.esc_attr($style).'.php', false, false ) );
					$return .= ob_get_contents();
					ob_end_clean();
					$return .= thsn_element_block_container( array(
						'position'	=> 'end',
					) );
				}
				$i++;
			}
			$return .= '</div>';
		}
		/* Restore original Post Data */
		wp_reset_postdata();
	}
	// Output
	if( !empty($return) ){
		echo '<div class="thsn-portfolio-related">';
			echo thsn_esc_kses($related_title);
			echo thsn_esc_kses($return);
		echo '</div>';
	}
}
}

if( !function_exists('thsn_related_service') ){
function thsn_related_service() {
	$return			= '';
	$related_title	= thsn_get_base_option('service-show-related');

	if($related_title==true){

		$related_title	= thsn_get_base_option('service-related-title');
		$show			= thsn_get_base_option('service-related-count');
		$column			= thsn_get_base_option('service-related-column');
		$style			= thsn_get_base_option('service-related-style');
		// Title
		if( !empty($related_title) ){
			$related_title = '<h3 class="thsn-related-title">'.$related_title.'</h3>';
		}

		$terms = wp_get_post_terms( get_the_ID(), 'thsn-service-category' );
		$term_list = array();
		if( !empty($terms) ){
			foreach( $terms as $term ){
				$term_list[] = $term->slug;
			}
		}

		// Preparing $args
		$args = array(
			'post_type'				=> 'thsn-service',
			'orderby'				=> 'rand',
			'posts_per_page'		=> $show,
			'ignore_sticky_posts'	=> true,
			'post__not_in'			=> array( get_the_ID() ),
			'tax_query'				=> array(
				array(
					'taxonomy' => 'thsn-service-category',
					'field'    => 'slug',
					'terms'    => $term_list,
				),
			),
		);

		// Wp query to fetch posts
		$posts = new WP_Query( $args );
		$i = 1;
		if ( $posts->have_posts() ) {

			$return .= '<div class="thsn-element-posts-wrapper row multi-columns-row">';

			while ( $posts->have_posts() ) {
				$posts->the_post();
				$class = $i%2 ? 'thsn-odd':'thsn-even';

				// Template
				if( file_exists( locate_template( '/theme-parts/service/service-style-'.esc_attr($style).'.php', false, false ) ) ){

					$return .= thsn_element_block_container( array(
						'position'	=> 'start',
						'column'	=> $column,
						'cpt'		=> 'service',
					) );

					ob_start();
					include( locate_template( '/theme-parts/service/service-style-'.esc_attr($style).'.php', false, false ) );
					$return .= ob_get_contents();
					ob_end_clean();

					$return .= thsn_element_block_container( array(
						'position'	=> 'end',
					) );

				}
				$i++;
			}

			$return .= '</div>';
		}

		/* Restore original Post Data */
		wp_reset_postdata();

	}

	// Output
	if( !empty($return) ){
		echo '<div class="thsn-service-related">';
			echo thsn_esc_kses($related_title);
			echo thsn_esc_kses($return);
		echo '</div>';
	}
}
}

if( !function_exists('thsn_related_post') ){
function thsn_related_post() {
	$return			= '';
	$related_title	= thsn_get_base_option('blog-show-related');

	if($related_title==true){

		$related_title	= thsn_get_base_option('blog-related-title');
		$show			= thsn_get_base_option('blog-related-count');
		$column			= thsn_get_base_option('blog-related-column');
		$style			= thsn_get_base_option('blog-related-style');

		// Title
		if( !empty($related_title) ){
			$related_title = '<h3 class="thsn-related-title">'.$related_title.'</h3>';
		}

		$terms = wp_get_post_terms( get_the_ID(), 'category' );
		$term_list = array();
		if( !empty($terms) ){
			foreach( $terms as $term ){
				$term_list[] = $term->slug;
			}
		}

		// Preparing $args
		$args = array(
			'post_type'				=> 'post',
			'orderby'				=> 'rand',
			'posts_per_page'		=> $show,
			'ignore_sticky_posts'	=> true,
			'post__not_in'			=> array( get_the_ID() ),
			'tax_query'				=> array(
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $term_list,
				),
			),
		);

		// Wp query to fetch posts
		$posts = new WP_Query( $args );
		$i = 1;
		if ( $posts->have_posts() ) {

			$return .= '<div class="thsn-element-posts-wrapper row multi-columns-row">';

			while ( $posts->have_posts() ) {
				$posts->the_post();
				$class = $i%2 ? 'thsn-odd':'thsn-even';

				// Template
				if( file_exists( locate_template( '/theme-parts/blog/blog-style-'.esc_attr($style).'.php', false, false ) ) ){

					$return .= thsn_element_block_container( array(
						'position'	=> 'start',
						'column'	=> $column,
						'cpt'		=> 'post',
					) );

					ob_start();
					include( locate_template( '/theme-parts/blog/blog-style-'.esc_attr($style).'.php', false, false ) );
					$return .= ob_get_contents();
					ob_end_clean();

					$return .= thsn_element_block_container( array(
						'position'	=> 'end',
					) );

				}
				$i++;
			}

			$return .= '</div>';
		}

		/* Restore original Post Data */
		wp_reset_postdata();

	}

	// Output
	if( !empty($return) ){
		echo '<div class="thsn-post-related">';
			echo thsn_esc_kses($related_title);
			echo thsn_esc_kses($return);
		echo '</div>';
	}
}
}

if( !function_exists('thsn_testimonial_star_ratings') ){
function thsn_testimonial_star_ratings() {
	$return = '';
	$ratings = get_post_meta( get_the_ID(), 'thsn-star-ratings', true );
	if( !empty($ratings) && $ratings!='no' && $ratings>0 ){
		for($x = 1; $x <= 5; $x++) {
			$active_class = ( $x<=$ratings ) ? ' thsn-active' : '' ;
			$return .= '<i class="thsn-base-icon-star'.esc_attr($active_class).'"></i>';
		}
	}
	if( !empty($return) ){
		$return = '<div class="themesion-box-star-ratings">'.$return.'</div>';
	}
	echo thsn_esc_kses($return);
}
}

if( !function_exists('thsn_testimonial_details') ){
function thsn_testimonial_details() {
	$return = '';
	$details = get_post_meta( get_the_ID(), 'thsn-testimonial-details', true );
	if( !empty($details) ){
		$return = '<div class="themesion-testimonial-detail">'.$details.'</div>';
	}
	echo thsn_esc_kses($return);
}
}

if( !function_exists('thsn_check_widget_exists') ){
function thsn_check_widget_exists( $sidebar_position='' ) {
	$return = '';
	$sidebar	= 'thsn-sidebar-post';
	if( is_page() ){
		// page sidebar
		$sidebar	= 'thsn-sidebar-page';
		if( function_exists('is_woocommerce') && is_woocommerce() ){
			$sidebar = 'thsn-sidebar-wc-shop';
		}
	} else if( function_exists('is_woocommerce') && is_woocommerce() && !is_product() ){
		$sidebar = 'thsn-sidebar-wc-shop';
	} else if( function_exists('is_product') && is_product() ){
		$sidebar = 'thsn-sidebar-wc-single';
	} else if( is_search() ){
		$sidebar	= 'thsn-sidebar-search';
	} else if( is_singular('thsn-portfolio') ){
		$sidebar		= 'thsn-sidebar-portfolio';
	} else if( is_tax('thsn-portfolio-category') ){
		$sidebar		= 'thsn-sidebar-portfolio-cat';
	} else if( is_singular('thsn-service') ){
		$sidebar		= 'thsn-sidebar-service';
	} else if( is_tax('thsn-service-category') ){
		$sidebar		= 'thsn-sidebar-service-cat';
	} else if( is_singular('thsn-team-member') ){
		$sidebar		= 'thsn-sidebar-team';
	} else if( is_tax('thsn-team-group') ){
		$sidebar		= 'thsn-sidebar-team-group';
	} else if( is_search() ){
		$sidebar		= 'thsn-sidebar-search';
	}
	if ( !is_active_sidebar( $sidebar ) ){
		$return = 'thsn-empty-sidebar';
	}
	return esc_attr($return);
}
}

/*
 *  Body Class
 */
if( !function_exists('thsn_check_sidebar') ){
function thsn_check_sidebar() {
	$return = false;
	// sidebar class
	$sidebar = thsn_get_base_option('sidebar-post');
	if( is_page() ){
		$sidebar = thsn_get_base_option('sidebar-page');
		$page_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
		if( !empty($page_meta) && $page_meta!='global' ){
			$sidebar = $page_meta;
		}
		if( function_exists('is_woocommerce') && is_woocommerce() ){
			$sidebar = thsn_get_base_option('sidebar-wc-shop');
		}
	} else if( function_exists('is_woocommerce') && is_woocommerce() && !is_product() ){
		$sidebar = thsn_get_base_option('sidebar-wc-shop');
	} else if( function_exists('is_product') && is_product() ){
		$sidebar = thsn_get_base_option('sidebar-wc-single');
	} else if( is_singular('thsn-portfolio') ){
		$sidebar = thsn_get_base_option('sidebar-portfolio');
		$page_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
		if( !empty($page_meta) && $page_meta!='global' ){
			$sidebar = $page_meta;
		}
	} else if( is_singular('thsn-service') ){
		$sidebar = thsn_get_base_option('sidebar-service');
		$page_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
		if( !empty($page_meta) && $page_meta!='global' ){
			$sidebar = $page_meta;
		}
	} else if( is_singular('thsn-team-member') ){
		$sidebar = thsn_get_base_option('sidebar-team-member');
		$page_meta = get_post_meta( get_the_ID(), 'thsn-sidebar', true );
		if( !empty($page_meta) && $page_meta!='global' ){
			$sidebar = $page_meta;
		}
	} else if( is_tax('thsn-team-group') ){
		$sidebar = thsn_get_base_option('sidebar-team-group');
	} else if( is_tax('thsn-portfolio-category') ){
		$sidebar = thsn_get_base_option('sidebar-portfolio-category');
	} else if( is_tax('thsn-service-category') ){
		$sidebar = thsn_get_base_option('sidebar-service-category');
	} else if( is_search() ){
		$sidebar = thsn_get_base_option('sidebar-search');
	}
	if( $sidebar!='' && $sidebar!='no' ){
		$return = true;
	}
	if( !empty( thsn_check_widget_exists() ) ){
		$return = false;
	}
	return $return;
}
}

if( !function_exists('thsn_sortable_category') ){
function thsn_sortable_category( $atts=array(), $taxonomy='' ){
	$return = '';
	$list = '';
	if( !empty($atts['sortable']) && $atts['sortable']=='yes' ){
		$list .= '<li><a href="#" class="thsn-sortable-link thsn-selected" data-sortby="*">' . esc_html__('All','itinc') . '</a></li>';
		if( !empty($atts['from_category']) ){
			// selected category
			$from_category = explode(',',$atts['from_category']);
			foreach( $from_category as $catslug ){
				$term = get_term_by( 'slug', $catslug, $taxonomy );
				$list .= '<li><a href="#" class="thsn-sortable-link" data-sortby="' . esc_attr($catslug) . '">' . esc_html($term->name) . '</a></li>';
			}
		} else {
			// all category
			$all_terms = get_terms( array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
			) );
			foreach( $all_terms as $term ){
				$list .= '<li><a href="#" class="thsn-sortable-link" data-sortby="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</a></li>';
			}
		}
		$return = '<div class="thsn-sortable-list"><ul class="thsn-sortable-list-ul">
			'.$list.'
		</ul></div>';
		return thsn_esc_kses($return);
	}
}
}

if( !function_exists('thsn_cart_icon') ){
function thsn_cart_icon( $style='1' ){
	$show_cart = thsn_get_base_option('wc-show-cart-icon');
	if( function_exists('is_woocommerce') && $show_cart==true ){
		$show_cart_amount = thsn_get_base_option('wc-show-cart-amount');
		global $woocommerce;
		$cart_icon		= ( $style=='2' ) ? 'thsn-base-icon-supermarket-2' : 'thsn-base-icon-shopping-bag-1' ;
		$cart_number	= (string) $woocommerce->cart->cart_contents_count;
		$cart_number	= ( $cart_number>0 ) ? $cart_number : '0' ;
		?>
		<div class="thsn-cart-wrapper thsn-cart-style-<?php echo esc_attr($style); ?>">
			<a href="<?php echo wc_get_cart_url(); ?>" class="thsn-cart-link">
				<span class="thsn-cart-details">
					<span class="thsn-cart-icon"><i class="<?php echo esc_attr($cart_icon); ?>"></i></span>
					<span class="thsn-cart-count">
						<?php echo esc_html($cart_number);?> 
					</span>
				</span>
				<?php if( $show_cart_amount==true ) : ?>
				<?php echo thsn_esc_kses( $woocommerce->cart->get_cart_total() ); ?>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}
}
}

if( !function_exists('thsn_site_content_class') ){
function thsn_site_content_class(){
	$return = '';
	if( is_404() ){
		$bgcolor = thsn_get_base_option('e404-bgcolor');
		if( !empty($bgcolor) ){
			$return .= ' thsn-bg-color-'.$bgcolor;
		}
		$background = thsn_get_base_option('e404-background');
		if( !empty($background['background-image']) ){
			$return .= ' thsn-bg-image-yes';
		}
		$text_color = thsn_get_base_option('e404-text-color');
		if( !empty($text_color) ){
			$return .= ' thsn-text-color-'.$text_color;
		}
	}
	if( !empty($return) ){
		echo esc_attr($return);
	}
}
}

if( !function_exists('thsn_ordinal') ){
function thsn_ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
}

if( !function_exists('thsn_icon_heading_box') ){
function thsn_icon_heading_box( $settings = array() ){
	extract($settings);

	$icon_html = $title_html = $subtitle_html = $desc_html = $nav_html = $button_html = $box_number_html = '';

	if( !empty($box_number) ){
		$box_number_html = '<div class="thsn-ihbox-box-number">'.esc_attr($box_number).'</div>';
	}

	if( file_exists( locate_template( '/theme-parts/icon-heading/icon-heading-style-'.esc_attr($style).'.php', false, false ) ) ){

		if( !empty($settings['icon_type']) ){

			if( $settings['icon_type']=='text' ){
				$icon_html = '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper thsn-ihbox-icon-type-text">' . $settings['icon_text'] . '</div></div>';

			} else if( $settings['icon_type']=='image' ){
				$icon_alt	= (!empty($settings['title'])) ? trim($settings['title']) : esc_attr__('Icon', 'itinc') ;
				$icon_image = '<img src="'.esc_url($settings['icon_image']['url']).'" alt="'.esc_attr($icon_alt).'" />';
				$icon_html	= '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper thsn-ihbox-icon-type-image">' . $icon_image . '</div></div>';
			} else if( $settings['icon_type']=='none' ){
				$icon_html = '';
			} else {

				// This is real icon html code
				if( !empty($settings['icon']['value']) ){
					//wp_enqueue_style($i_type);
					$icon_html = '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper thsn-ihbox-icon-type-icon"><i class="' . $settings['icon']['value'] . '"></i></div></div>';
				}

			}
		}

		// Title
		if( !empty($settings['title']) ) {
			$title_tag	= ( !empty($settings['title_tag']) ) ? $settings['title_tag'] : 'h2' ;
			$title_html	= '<'. thsn_esc_kses($title_tag) . ' class="thsn-element-title">
				'.thsn_link_render($settings['title_link'], 'start' ).'
					'.thsn_esc_kses($settings['title']).'
				'.thsn_link_render($settings['title_link'], 'end' ).'
				</'. thsn_esc_kses($title_tag) . '>
			';
		}

		// SubTitle
		if( !empty($settings['subtitle']) ) {
			$subtitle_tag	= ( !empty($settings['subtitle_tag']) ) ? $settings['subtitle_tag'] : 'h4' ;
			$subtitle_html	= '<'. thsn_esc_kses($subtitle_tag) . ' class="thsn-element-subtitle">
				'.thsn_link_render($settings['subtitle_link'], 'start' ).'
					'.thsn_esc_kses($settings['subtitle']).'
				'.thsn_link_render($settings['subtitle_link'], 'end' ).'
				</'. thsn_esc_kses($subtitle_tag) . '>
			';
		}

		// Description text
		if( !empty($settings['desc']) ){
			$desc_html = '<div class="thsn-heading-desc">'.thsn_esc_kses($settings['desc']).'</div>';
		}

		// Button
		if( !empty($settings['btn_title']) && !empty($settings['btn_link']['url']) ){
			$button_html = '<div class="thsn-ihbox-btn">' . thsn_link_render($settings['btn_link'], 'start' ) . thsn_esc_kses($settings['btn_title']) . thsn_link_render($settings['btn_link'], 'end' ) . '</div>';
		}

		echo '<div class="thsn-ihbox thsn-ihbox-style-'.esc_attr($style).'">';

			include( locate_template( '/theme-parts/icon-heading/icon-heading-style-'.esc_attr($style).'.php', false, false ) );

		echo '</div>';

	}

}
}