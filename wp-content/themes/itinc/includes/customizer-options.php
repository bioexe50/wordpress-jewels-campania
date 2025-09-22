<?php
// Default titles
$portfolio_cpt_singular_title	= esc_attr__('Portfolio','itinc');
$portfolio_cat_singular_title	= esc_attr__('Portfolio Category','itinc');
$service_cpt_singular_title	= esc_attr__('Service','itinc');
$service_cat_singular_title	= esc_attr__('Service Category','itinc');
$team_cpt_singular_title	= esc_attr__('Team Member','itinc');
$team_group_singular_title	= esc_attr__('Team Group','itinc');
$testimonial_cpt_singular_title		= esc_attr__('Testimonial','itinc');
$testimonial_cat_singular_title	= esc_attr__('Testimonial Category','itinc');
if( class_exists('Kirki') ){
	// Portfolio
	$portfolio_cpt_singular_title2	= Kirki::get_option( 'portfolio-cpt-singular-title' );
	$portfolio_cpt_singular_title	= ( !empty($portfolio_cpt_singular_title2) ) ? $portfolio_cpt_singular_title2 : $portfolio_cpt_singular_title ;
	// Portfolio Category
	$portfolio_cat_singular_title2	= Kirki::get_option( 'portfolio-cat-singular-title' );
	$portfolio_cat_singular_title	= ( !empty($portfolio_cat_singular_title2) ) ? $portfolio_cat_singular_title2 : $portfolio_cat_singular_title ;
	// Service
	$service_cpt_singular_title2	= Kirki::get_option( 'service-cpt-singular-title' );
	$service_cpt_singular_title	= ( !empty($service_cpt_singular_title2) ) ? $service_cpt_singular_title2 : $service_cpt_singular_title ;
	// Service Category
	$service_cat_singular_title2	= Kirki::get_option( 'service-cat-singular-title' );
	$service_cat_singular_title	= ( !empty($service_cat_singular_title2) ) ? $service_cat_singular_title2 : $service_cat_singular_title ;
	// Team
	$team_cpt_singular_title2	= Kirki::get_option( 'team-cpt-singular-title' );
	$team_cpt_singular_title	= ( !empty($team_cpt_singular_title2) ) ? $team_cpt_singular_title2 : $team_cpt_singular_title ;
	// Team Group
	$team_group_singular_title2	= Kirki::get_option( 'team-group-singular-title' );
	$team_group_singular_title	= ( !empty($team_group_singular_title2) ) ? $team_group_singular_title2 : $team_group_singular_title ;
	// Testimonial
	$testimonial_cpt_singular_title2	= Kirki::get_option( 'testimonial-cpt-singular-title' );
	$testimonial_cpt_singular_title	= ( !empty($testimonial_cpt_singular_title2) ) ? $testimonial_cpt_singular_title2 : $testimonial_cpt_singular_title ;
	// Testimonial Category
	$testimonial_cat_singular_title2	= Kirki::get_option( 'testimonial-cat-singular-title' );
	$testimonial_cat_singular_title	= ( !empty($testimonial_cat_singular_title2) ) ? $testimonial_cat_singular_title2 : $testimonial_cat_singular_title ;
}
$pre_color_list = array(
	'transparent'		=> get_template_directory_uri() . '/includes/images/precolor-transparent.png',
	'white'				=> get_template_directory_uri() . '/includes/images/precolor-white.png',
	'light'				=> get_template_directory_uri() . '/includes/images/precolor-light.png',
	'blackish'			=> get_template_directory_uri() . '/includes/images/precolor-blackish.png',
	'globalcolor'		=> get_template_directory_uri() . '/includes/images/precolor-globalcolor.png',
	'secondarycolor'	=> get_template_directory_uri() . '/includes/images/precolor-secondarycolor.png',
	'custom'			=> get_template_directory_uri() . '/includes/images/precolor-custom.png',
);
$pre_two_color_list = array(
	''					=> get_template_directory_uri() . '/includes/images/precolor-default.png',
	'white'				=> get_template_directory_uri() . '/includes/images/precolor-white.png',
	'blackish'			=> get_template_directory_uri() . '/includes/images/precolor-blackish.png',
	'globalcolor'		=> get_template_directory_uri() . '/includes/images/precolor-globalcolor.png',
);
$pre_text_color_list = array(
	'white'				=> get_template_directory_uri() . '/includes/images/precolor-white.png',
	'blackish'			=> get_template_directory_uri() . '/includes/images/precolor-blackish.png',
	'globalcolor'		=> get_template_directory_uri() . '/includes/images/precolor-globalcolor.png',
	'secondarycolor'	=> get_template_directory_uri() . '/includes/images/precolor-secondarycolor.png',
);
$pre_text_color_2_list = array(
	'white'				=> get_template_directory_uri() . '/includes/images/precolor-white.png',
	'blackish'			=> get_template_directory_uri() . '/includes/images/precolor-blackish.png',
);
$column_list = array(
	'1'	=> get_template_directory_uri() . '/includes/images/column-1.png',
	'2'	=> get_template_directory_uri() . '/includes/images/column-2.png',
	'3'	=> get_template_directory_uri() . '/includes/images/column-3.png',
	'4'	=> get_template_directory_uri() . '/includes/images/column-4.png',
	'5'	=> get_template_directory_uri() . '/includes/images/column-5.png',
	'6'	=> get_template_directory_uri() . '/includes/images/column-6.png',
);
// Total Header Styles
$header_style_array = array(
	'1'	=> get_template_directory_uri() . '/includes/images/header-style-1.jpg',
	'2'	=> get_template_directory_uri() . '/includes/images/header-style-2.jpg',
	'3'	=> get_template_directory_uri() . '/includes/images/header-style-3.jpg',
	'4'	=> get_template_directory_uri() . '/includes/images/header-style-4.jpg',
	'5'	=> get_template_directory_uri() . '/includes/images/header-style-5.jpg',
	'6'	=> get_template_directory_uri() . '/includes/images/header-style-6.jpg',
);
// Total Single Portfolio Styles
$portfolio_single_style_array = array(
	'1'	=> get_template_directory_uri() . '/includes/images/portfolio-single-style-1.jpg',
	'2'	=> get_template_directory_uri() . '/includes/images/portfolio-single-style-2.jpg',
);
// Total Single Service Styles
$service_single_style_array = array(
	'1'	=> get_template_directory_uri() . '/includes/images/service-single-style-1.jpg',
	'2'	=> get_template_directory_uri() . '/includes/images/service-single-style-2.jpg',
);
// Total Single Portfolio Styles
$team_single_style_array = array(
	'1'	=> get_template_directory_uri() . '/includes/images/team-single-style-1.jpg',
	'2'	=> get_template_directory_uri() . '/includes/images/team-single-style-2.jpg',
);
// Social links
$social_options_array = array();
if( function_exists('thsn_social_links_list') ){
	$social_list = thsn_social_links_list();
	foreach( $social_list as $social ){
		$social_options_array[] = array(
			'type'			=> 'text',
			'settings'		=> esc_attr( $social['id'] ),
			'label'			=> esc_attr( $social['label'] ),
			'description'	=> esc_attr__( 'Write Social URL.', 'itinc' ),
			'default'		=> '',
		);
	}
}
$footer_col_width_array = array(
	'hide'	=> esc_attr__( 'Hide this column', 'itinc' ),
	'1'		=> esc_attr__( '1%', 'itinc' ),
	'2'		=> esc_attr__( '2%', 'itinc' ),
	'3'		=> esc_attr__( '3%', 'itinc' ),
	'4'		=> esc_attr__( '4%', 'itinc' ),
	'5'		=> esc_attr__( '5%', 'itinc' ),
	'6'		=> esc_attr__( '6%', 'itinc' ),
	'7'		=> esc_attr__( '7%', 'itinc' ),
	'8'		=> esc_attr__( '8%', 'itinc' ),
	'9'		=> esc_attr__( '9%', 'itinc' ),
	'10'	=> esc_attr__( '10%', 'itinc' ),
	'11'	=> esc_attr__( '11%', 'itinc' ),
	'12'	=> esc_attr__( '12%', 'itinc' ),
	'13'	=> esc_attr__( '13%', 'itinc' ),
	'14'	=> esc_attr__( '14%', 'itinc' ),
	'15'	=> esc_attr__( '15%', 'itinc' ),
	'16'	=> esc_attr__( '16%', 'itinc' ),
	'17'	=> esc_attr__( '17%', 'itinc' ),
	'18'	=> esc_attr__( '18%', 'itinc' ),
	'19'	=> esc_attr__( '19%', 'itinc' ),
	'20'	=> esc_attr__( '20%', 'itinc' ),
	'21'	=> esc_attr__( '21%', 'itinc' ),
	'22'	=> esc_attr__( '22%', 'itinc' ),
	'23'	=> esc_attr__( '23%', 'itinc' ),
	'24'	=> esc_attr__( '24%', 'itinc' ),
	'25'	=> esc_attr__( '25%', 'itinc' ),
	'26'	=> esc_attr__( '26%', 'itinc' ),
	'27'	=> esc_attr__( '27%', 'itinc' ),
	'28'	=> esc_attr__( '28%', 'itinc' ),
	'29'	=> esc_attr__( '29%', 'itinc' ),
	'30'	=> esc_attr__( '30%', 'itinc' ),
	'31'	=> esc_attr__( '31%', 'itinc' ),
	'32'	=> esc_attr__( '32%', 'itinc' ),
	'33'	=> esc_attr__( '33%', 'itinc' ),
	'34'	=> esc_attr__( '34%', 'itinc' ),
	'35'	=> esc_attr__( '35%', 'itinc' ),
	'36'	=> esc_attr__( '36%', 'itinc' ),
	'37'	=> esc_attr__( '37%', 'itinc' ),
	'38'	=> esc_attr__( '38%', 'itinc' ),
	'39'	=> esc_attr__( '39%', 'itinc' ),
	'40'	=> esc_attr__( '40%', 'itinc' ),
	'41'	=> esc_attr__( '41%', 'itinc' ),
	'42'	=> esc_attr__( '42%', 'itinc' ),
	'43'	=> esc_attr__( '43%', 'itinc' ),
	'44'	=> esc_attr__( '44%', 'itinc' ),
	'45'	=> esc_attr__( '45%', 'itinc' ),
	'46'	=> esc_attr__( '46%', 'itinc' ),
	'47'	=> esc_attr__( '47%', 'itinc' ),
	'48'	=> esc_attr__( '48%', 'itinc' ),
	'49'	=> esc_attr__( '49%', 'itinc' ),
	'50'	=> esc_attr__( '50%', 'itinc' ),
	'51'	=> esc_attr__( '51%', 'itinc' ),
	'52'	=> esc_attr__( '52%', 'itinc' ),
	'53'	=> esc_attr__( '53%', 'itinc' ),
	'54'	=> esc_attr__( '54%', 'itinc' ),
	'55'	=> esc_attr__( '55%', 'itinc' ),
	'56'	=> esc_attr__( '56%', 'itinc' ),
	'57'	=> esc_attr__( '57%', 'itinc' ),
	'58'	=> esc_attr__( '58%', 'itinc' ),
	'59'	=> esc_attr__( '59%', 'itinc' ),
	'60'	=> esc_attr__( '60%', 'itinc' ),
	'61'	=> esc_attr__( '61%', 'itinc' ),
	'62'	=> esc_attr__( '62%', 'itinc' ),
	'63'	=> esc_attr__( '63%', 'itinc' ),
	'64'	=> esc_attr__( '64%', 'itinc' ),
	'65'	=> esc_attr__( '65%', 'itinc' ),
	'66'	=> esc_attr__( '66%', 'itinc' ),
	'67'	=> esc_attr__( '67%', 'itinc' ),
	'68'	=> esc_attr__( '68%', 'itinc' ),
	'69'	=> esc_attr__( '69%', 'itinc' ),
	'70'	=> esc_attr__( '70%', 'itinc' ),
	'71'	=> esc_attr__( '71%', 'itinc' ),
	'72'	=> esc_attr__( '72%', 'itinc' ),
	'73'	=> esc_attr__( '73%', 'itinc' ),
	'74'	=> esc_attr__( '74%', 'itinc' ),
	'75'	=> esc_attr__( '75%', 'itinc' ),
	'76'	=> esc_attr__( '76%', 'itinc' ),
	'77'	=> esc_attr__( '77%', 'itinc' ),
	'78'	=> esc_attr__( '78%', 'itinc' ),
	'79'	=> esc_attr__( '79%', 'itinc' ),
	'80'	=> esc_attr__( '80%', 'itinc' ),
	'81'	=> esc_attr__( '81%', 'itinc' ),
	'82'	=> esc_attr__( '82%', 'itinc' ),
	'83'	=> esc_attr__( '83%', 'itinc' ),
	'84'	=> esc_attr__( '84%', 'itinc' ),
	'85'	=> esc_attr__( '85%', 'itinc' ),
	'86'	=> esc_attr__( '86%', 'itinc' ),
	'87'	=> esc_attr__( '87%', 'itinc' ),
	'88'	=> esc_attr__( '88%', 'itinc' ),
	'89'	=> esc_attr__( '89%', 'itinc' ),
	'90'	=> esc_attr__( '90%', 'itinc' ),
	'91'	=> esc_attr__( '91%', 'itinc' ),
	'92'	=> esc_attr__( '92%', 'itinc' ),
	'93'	=> esc_attr__( '93%', 'itinc' ),
	'94'	=> esc_attr__( '94%', 'itinc' ),
	'95'	=> esc_attr__( '95%', 'itinc' ),
	'96'	=> esc_attr__( '96%', 'itinc' ),
	'97'	=> esc_attr__( '97%', 'itinc' ),
	'98'	=> esc_attr__( '98%', 'itinc' ),
	'99'	=> esc_attr__( '99%', 'itinc' ),
	'100'	=> esc_attr__( '100%', 'itinc' ),
);

$blog_styles = thsn_element_template_list('blog', 'customizer');
unset($blog_styles['classic'], $blog_styles['3']);

/*** Options array ***/
$kirki_options_array = array(
	// General Settings
	'general_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'General Options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'color',
				'settings'		=> 'global-color',
				'label'			=> esc_attr__( 'Global Color', 'itinc' ),
				'description'	=> esc_attr__( 'This color will be globally applied to most of elements parts and special texts', 'itinc' ),
				'default'		=> '#1e40af',
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'secondary-color',
				'label'			=> esc_attr__( 'Secondary Color', 'itinc' ),
				'description'	=> esc_attr__( 'This color will be used on some elements. Sometimes with Global Color. This should match with Global Color to look good.', 'itinc' ),
				'default'		=> '#202426',
			),
			array(
				'type'		=> 'multicolor',
				'settings'	=> 'gradient-color',
				'label'		=> esc_attr__( 'Gradient Color', 'itinc' ),
				'choices'		=> array(
					'first'		=> esc_attr__( 'Starting Color', 'itinc' ),
					'last'		=> esc_attr__( 'Ending Color', 'itinc' ),
				),
				'default'	=> array(
				  'first'		=> '#c19d07',
				  'last'		=> '#c19d07',
				),
			),
			array(
				'type'				=> 'image',
				'settings'			=> 'logo',
				'label'				=> esc_attr__( 'Logo', 'itinc' ),
				'description'		=> esc_attr__( 'Main logo', 'itinc' ),
				'default'			=> get_template_directory_uri() . '/images/logo.png',
				'partial_refresh'	=> array(
					'logo'				=> array(
						'selector'			=> '.site-title',
						'render_callback'	=> function() {
							return thsn_logo( 'yes' );
						},
					)
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'logo-height',
				'label'			=> esc_attr__( 'Logo Max Height', 'itinc' ),
				'default'		=> 55,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 1000,
					'step'			=> 1,
				),
			),
			array(
				'type'			=> 'image',
				'settings'		=> 'sticky-logo',
				'label'			=> esc_attr__( 'Sticky Logo', 'itinc' ),
				'description'	=> esc_attr__( 'Sticky logo', 'itinc' ),
				'default'		=> '',
				'active_callback'=> array( array(
					'setting' => 'sticky-header',
					'operator' => '==',
					'value' => '1',
				) ),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'sticky-logo-height',
				'label'			=> esc_attr__( 'Sticky Logo Max Height', 'itinc' ),
				'default'		=> 45,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 1000,
					'step'			=> 1,
				),
				'active_callback'=> array( array(
					'setting' => 'sticky-header',
					'operator' => '==',
					'value' => '1',
				) ),
			),
			array(
				'type'			=> 'image',
				'settings'		=> 'responsive-logo',
				'label'			=> esc_attr__( 'Responsive Logo', 'itinc' ),
				'description'	=> esc_attr__( 'This logo appear in small devices like mobile/tablet etc', 'itinc' ),
				'default'		=> '',
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'responsive-logo-height',
				'label'			=> esc_attr__( 'Responsive Logo Max Height', 'itinc' ),
				'default'		=> 50,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 1000,
					'step'			=> 1,
				),
			),
			array(
				'type'		=> 'multicolor',
				'settings'	=> 'link-color',
				'label'		=> esc_attr__( 'Link Color', 'itinc' ),
				'choices'		=> array(
					'normal'	=> esc_attr__( 'Normal Color', 'itinc' ),
					'hover'		=> esc_attr__( 'Mouse-Over (Hover) Color', 'itinc' ),
				),
				'default'	=> array(
					'normal'	=> '#202426',
					'hover'		=> '#c19d07',
				),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'preloader',
				'label'			=> esc_attr__( 'Show Preloader?', 'itinc' ),
				'description'	=> esc_attr__( 'Show or hide preloader', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'preloader-image',
				'label'			=> esc_html__( 'Select preloader image', 'itinc' ),
				'default'		=> '1',
				'choices'		=> array(
					'1'   => get_template_directory_uri() . '/images/loader1.svg',
					'2'   => get_template_directory_uri() . '/images/loader2.svg',
					'3'   => get_template_directory_uri() . '/images/loader3.svg',
					'4'   => get_template_directory_uri() . '/images/loader4.svg',
					'5'   => get_template_directory_uri() . '/images/loader5.svg',
					'6'   => get_template_directory_uri() . '/images/loader6.svg',
					'7'   => get_template_directory_uri() . '/images/loader7.svg',
					'8'   => get_template_directory_uri() . '/images/loader8.svg',
					'9'   => get_template_directory_uri() . '/images/loader9.svg',
				),
				'active_callback'=> array( array(
					'setting' => 'preloader',
					'operator' => '==',
					'value' => '1',
				) ),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'responsive-breakpoint',
				'label'			=> esc_attr__( 'Responsive Breakpoint', 'itinc' ),
				'description'	=> esc_attr__( 'Select screen size to make the menu burger menu (responsive menu) below the selected screen size and also other settings too. Preferred Sizes: 1200, 1024, 992 and 768', 'itinc' ),
				'default'		=> 1200,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 2000,
					'step'			=> 1,
				),
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-page',
				'label'		=> esc_html__( 'Page Sidebar', 'itinc' ),
				'default'	=> 'right',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
			// Advanced Settings
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-advanced-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Advanced Settings', 'itinc' ) . '</h2> <span>' . esc_html__( 'Special advanced options', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'min',
				'label'			=> esc_attr__( 'Load Minified CSS and JS Files?', 'itinc' ),
				'description'	=> esc_attr__( 'Load minified files for CSS and JS code files. Select YES to reduce page load time.', 'itinc' ),
				'default'		=> '1',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'hide_totop_button',
				'label'			=> esc_attr__( 'Hide Scroll to Top Button', 'itinc' ),
				'description'	=> esc_attr__( 'Show or hide Scroll to Top ( Totop ) Button.', 'itinc' ),
				'default'		=> '0',
				'choices'     => array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'dynamic-css-file',
				'label'			=> esc_attr__( 'Load Static CSS file for Dynamic Code?', 'itinc' ),
				'description' => esc_attr__( 'Keep this YES to make your site load faster. Select NO if you are modifying any file via child theme or any other way.', 'itinc' ),
				'default'		=> '1',
				'choices'     => array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'load-merged-css',
				'label'			=> esc_attr__( 'Load merged file?', 'itinc' ),
				'description'	=> esc_attr__( 'Keep this YES to load merged file so only one CSS file will load instead of multiple files. This will effect theme\'s CSS files only and not other plugin related files. This will reduce load time.', 'itinc' ),
				'default'		=> '1',
				'choices'     => array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'white-color',
				'label'			=> esc_attr__( 'White Color', 'itinc' ),
				'description'	=> esc_attr__( 'This is default white color for text.', 'itinc' ),
				'default'		=> '#ffffff',
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'blackish-color',
				'label'			=> esc_attr__( 'Blackish Color', 'itinc' ),
				'description'	=> esc_attr__( 'This is default blackish color.', 'itinc' ),
				'default'		=> '#202426',
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'light-bg-color',
				'label'			=> esc_attr__( 'Light Background Color', 'itinc' ),
				'description'	=> esc_attr__( 'This is default grey background color.', 'itinc' ),
				'default'		=> '#f7f9fa',
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'blackish-bg-color',
				'label'			=> esc_attr__( 'Blackish Background Color', 'itinc' ),
				'description'	=> esc_attr__( 'This is default blackish background color.', 'itinc' ),
				'default'		=> '#202426',
			),
		)
	),
	// Typography Settings
	'typography_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Typography Options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'typography',
				'settings'		=> 'global-typography',
				'label'			=> esc_attr__( 'Global Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array (
					'font-family'		=> 'Roboto',
					'variant'			=> 'regular',
					'font-size'			=> '15px',
					'line-height'		=> '1.7',
					'letter-spacing'	=> '',
					'color'				=> '#666666',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				  ),
				'priority'			=> 10,
				'thsn-output'		=> 'body',
				'thsn-all-variants'	=> true,
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'h1-typography',
				'label'			=> esc_attr__( 'H1 Typography', 'itinc' ),
				'tooltip'     => esc_attr__( 'This is tooltip', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> 'regular',
					'font-size'			=> '34px',
					'line-height'		=> '44px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> 'h1',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'h2-typography',
				'label'			=> esc_attr__( 'H2 Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> 'regular',
					'font-size'			=> '30px',
					'line-height'		=> '40px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> 'h2',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'h3-typography',
				'label'			=> esc_attr__( 'H3 Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> 'regular',
					'font-size'			=> '26px',
					'line-height'		=> '36px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> 'h3',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'h4-typography',
				'label'			=> esc_attr__( 'H4 Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> 'regular',
					'font-size'			=> '22px',
					'line-height'		=> '32px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> 'h4',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'h5-typography',
				'label'			=> esc_attr__( 'H5 Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> 'regular',
					'font-size'			=> '18px',
					'line-height'		=> '28px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> 'h5',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'h6-typography',
				'label'			=> esc_attr__( 'H6 Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> 'regular',
					'font-size'			=> '16px',
					'line-height'		=> '26px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> 'h6',
			),
			// Heading Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Special Heading Typography', 'itinc' ) . '</h2> <span>' . esc_html__( 'Heading typography options', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'heading-typography',
				'label'			=> esc_attr__( 'Heading Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> 'regular',
					'font-size'			=> '40px',
					'line-height'		=> '50px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.thsn-heading-subheading .thsn-element-title',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'subheading-typography',
				'label'			=> esc_attr__( 'Sub-heading Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '600',
					'font-size'			=> '15px',
					'line-height'		=> '25px',
					'letter-spacing'	=> '0.5px',
					'color'				=> '#c19d07',
					'text-transform'	=> 'uppercase',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.thsn-heading-subheading .thsn-element-subtitle',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'content-typography',
				'label'			=> esc_attr__( 'Content Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Roboto',
					'variant'			=> 'regular',
					'font-size'			=> '16px',
					'line-height'		=> '1.7',
					'letter-spacing'	=> '0px',
					'color'				=> '#666666',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.thsn-heading-subheading .thsn-heading-desc',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'widget-heading-typography',
				'label'			=> esc_attr__( 'Widget Heading Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '700',
					'font-size'			=> '22px',
					'line-height'		=> '32px',
					'letter-spacing'	=> '-0.5px',
					'color'				=> '#202426',
					'text-transform'	=> 'uppercase',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.themesion-sidebar .widget_search .wp-block-search__label, .themesion-sidebar .widget_block .wp-block-group h2, .widget-title',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'buttons-typography',
				'label'			=> esc_attr__( 'Button Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '600',
					'font-size'			=> '13px',
					'line-height'		=> '13px',
					'letter-spacing'	=> '1px',
					'text-transform'	=> 'uppercase',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'thsn-output'	=> '.thsn-ptable-btn a, .elementor-element .elementor-widget-button .elementor-button, .thsn-header-button a, .thsn-service-btn, .thsn-ihbox-btn, .woocommerce .woocommerce-message .button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, button, html input[type=button], input[type=reset], input[type=submit]',
			),
			// Extra Load Fonts Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'css-only-custom-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'CSS only Typography', 'itinc' ) . '</h2> <span>' . esc_html__( 'This will not apply to any font style but this font will be loaded so we can use anywhere.', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'css-only-1-typography',
				'label'			=> esc_attr__( 'First Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '600',
					'font-style'		=> 'normal',
					'font-backup'		=> '',
				),
				'thsn-output'	=> '.woocommerce div.product .woocommerce-tabs ul.tabs li a, .thsn-ihbox-style-15 .thsn-ihbox-box-number, .thsn-footer-big-area-wrapper .thsn-label, .themesion-sidebar .widget ul a, .thsn-ourhistory .label,.site-content .thsn_widget_list_all_posts ul > li a, .widget .download .item-download a, .thsn-search-results-right .thsn-read-more-link a, .thsn-service-style-3 .thsn-service-btn-a, .thsn-service-style-2 .thsn-service-btn-a, .thsn-blog-classic-inner .thsn-read-more-link a, .thsn-blog-style-2 .thsn-read-more-link a, .thsn-blog-style-1 .thsn-read-more-link a, .thsn-pricing-table-box .themesion-ptable-price, .elementor-widget-progress .elementor-title, .thsn-header-style-2 .thsn-right-box .thsn-header-contactinfo .thsn-header-button-text-1, .themesion-ele-fid-style-2 .thsn-fid-title, .themesion-ele-fid-style-2 .thsn-fid-title, .thsn-testimonial-style-1 .themesion-box-title, .elementor-tab-title a, .thsn-pricing-table-box .themesion-ptable-heading, .thsn-col-Quotes.thsn-col-stretched-yes .thsn-stretched-div:after, .thsn-ihbox-style-12 .thsn-element-title, .thsn-blog-style-1 .thsn-meta-category',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'css-only-2-typography',
				'label'			=> esc_attr__( 'Second Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'font-size'			=> '22px',
					'line-height'		=> '32px',
					'variant'			=> '500',
					'font-style'		=> 'normal',
					'text-transform'	=> 'uppercase',
					'letter-spacing'	=> '-0.5px',
					'font-backup'		=> '',
				),
				'thsn-output'	=> '.thsn-ihbox-style-15 .thsn-element-title, .thsn-ihbox-style-18 .thsn-ihbox-box-number, .thsn-portfolio-style-2 h3.thsn-portfolio-title, .thsn-service-style-2 .thsn-service-title, .thsn-ihbox-style-5 .thsn-element-title, .thsn-tabs .thsn-tabs-heading li, .thsn-blog-style-2 .themesion-box-content .thsn-post-title,.thsn-team-style-2 .themesion-box-content .thsn-team-title, .thsn-team-style-1 .themesion-box-content .thsn-team-title, .thsn-service-style-4 .thsn-service-title, .themesion-ele-fid-style-5 .thsn-fid-title, .thsn-ihbox-style-14 .thsn-element-title, .thsn-ihbox-style-1.thsn-ihbox .thsn-element-title, .thsn-ihbox-style-9 .thsn-ihbox-box .thsn-element-title, .thsn-blog-style-1 .themesion-box-content .thsn-post-title, .thsn-ihbox-style-2.thsn-ihbox h2, .thsn-ihbox-style-3 .thsn-element-title, .thsn-service-style-3 .thsn-service-title',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'css-only-3-typography',
				'label'			=> esc_attr__( 'Third Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rubik',
					'variant'			=> 'regular',
					'font-style'		=> 'normal',
					'font-backup'		=> '',
				),
				'thsn-output'	=> 'blockquote',
			),
		)
	),
	// Pre-Header Options
	'preheader_options'	=> array(
		'section_settings'	=> array(
			'title'				=> esc_attr__( 'Pre-Header Options', 'itinc' ),
			'panel'				=> 'itinc_base_options',
			'priority'			=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'switch',
				'settings'		=> 'preheader-enable',
				'label'			=> esc_attr__( 'Show or hide Pre-header', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'			=> esc_attr__( 'Show', 'itinc' ),
					'off'			=> esc_attr__( 'Hide', 'itinc' ),
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'preheader-text-color',
				'label'				=> esc_attr__( 'Select pre-header text color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> $pre_text_color_list,
				'active_callback'	=> array(
					array(
						'setting'		=> 'preheader-enable',
						'operator'		=> '==',
						'value'			=> '1',
					)
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'preheader-bgcolor',
				'label'				=> esc_html__( 'Select pre-header background color', 'itinc' ),
				'default'			=> 'blackish',
				'choices'			=> $pre_color_list,
				'active_callback'	=> array( array(
					'setting'			=> 'preheader-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'preheader-bgcolor-custom',
				'label'			=> esc_attr__( 'Select pre-header background custom color', 'itinc' ),
				'description'	=> esc_attr__( 'Select custom color for pre-header background', 'itinc' ),
				'default'		=> '#ff5e15',
				'active_callback'=> array(
					array(
						'setting'	=> 'preheader-bgcolor',
						'operator'	=> '==',
						'value'		=> 'custom',
					),
					array(
						'setting'			=> 'preheader-enable',
						'operator'			=> '==',
						'value'				=> '1',
					)
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'preheader-responsive',
				'label'			=> esc_attr__( 'Hide in screen size', 'itinc' ),
				'description'	=> esc_attr__( 'Select screen size to hide this pre-header below the selected screen size. Preferred Sizes: 1200, 1024, 992 and 768', 'itinc' ),
				'default'		=> 1200,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 2000,
					'step'			=> 1,
				),
				'active_callback'	=> array( array(
					'setting'			=> 'preheader-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'preheader-content-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Preheader content', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Manage preheader content from here', 'itinc' ) , $portfolio_cpt_singular_title ) . '</span></div>',
				'active_callback'	=> array( array(
					'setting'			=> 'preheader-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'textarea',
				'settings'		=> 'preheader-left',
				'label'			=> esc_attr__( 'Pre-header Left Content', 'itinc' ),
				'default'		=> thsn_esc_kses('<i class="thsn-base-icon-marker"></i> Los Angeles Gournadi, 1230  Bariasl'),
				'active_callback'	=> array( array(
					'setting'			=> 'preheader-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
				'partial_refresh'	=> array(
					'preheader-left'		=> array(
						'selector'			=> '.thsn-pre-header-left',
						'render_callback'	=> function() {
							return get_theme_mod('preheader-left');
						},
					)
				),
			),
			array(
				'type'			=> 'textarea',
				'settings'		=> 'preheader-right',
				'label'			=> esc_attr__( 'Pre-header Right Content', 'itinc' ),
				'default'		=> thsn_esc_kses('<ul class="thsn-contact-info"><li><i class="thsn-base-icon-contact"></i> Make a call  : +1 (212) 255-5511</li><li>[thsn-social-links]</li></ul>'),
				'active_callback'	=> array( array(
					'setting'			=> 'preheader-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
				'partial_refresh'	=> array(
					'preheader-right'		=> array(
						'selector'			=> '.thsn-pre-header-right',
						'render_callback'	=> function() {
							return get_theme_mod('preheader-right');
						},
					)
				),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'preheader-search',
				'label'			=> esc_attr__( 'Show Search Icon in Pre-header Right Area?', 'itinc' ),
				'description'	=> esc_attr__( 'Select YES to show search icon in pre-header right side.', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
				'active_callback'	=> array( array(
					'setting'			=> 'preheader-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
		),
	),
	// Header Options
	'header_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Header Options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'header-style',
				'label'		=> esc_html__( 'Header Style', 'itinc' ),
				'description'	=> '<div class="thsn-alert-message">'.esc_html__( 'NOTE: This will also change other options (like background color, menu color, logo etc) to set it with this header.', 'itinc' ).'</div>',
				'default'	=> '1',
				'choices'		=> $header_style_array,
			),
			// - Infostack contents
			// 1st Box
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-header-box1-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Header 1st Box Contents', 'itinc' ) . '</h2> <span>' . esc_html__( 'Add or modify content for 1st box in header area.', 'itinc' ) . '</span></div>',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),		
					)
				),
			),
			array(
				'type'			=> 'themesion_icon_picker',
				'settings'		=> 'header-box1-icon',
				'label'			=> esc_attr__( '1st box - Icon', 'itinc' ),
				'description'	=> esc_attr__( 'Select icon for 1st box', 'itinc' ),
				'default'		=> esc_attr('thsn-itinc-icon thsn-itinc-icon-email;fa fa-map-marker;sgicon sgicon-Pointer'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-box1-title',
				'label'		=> esc_attr__( '1st Box - Title', 'itinc' ),
				'default'	=> esc_attr('Call us for any question'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
				'partial_refresh'	=> array(
					'header-box1-title'		=> array(
						'selector'			=> '.thsn-header-box-1 .thsn-header-box-title',
						'render_callback'	=> function() {
							return get_theme_mod('header-box1-title');
						},
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-box1-content',
				'label'		=> esc_attr__( '1st Box - Content', 'itinc' ),
				'default'	=> esc_attr('(+00)888.666.88'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'		=> 'link',
				'settings'	=> 'header-box1-link',
				'label'		=> esc_attr__( '1st Box - Link', 'itinc' ),
				'default'	=> '',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			// 2nd Box
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-header-box2-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Header 2nd Box Contents', 'itinc' ) . '</h2> <span>' . esc_html__( 'Add or modify content for 2nd box in header area.', 'itinc' ) . '</span></div>',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),

					)
				),
			),
			array(
				'type'			=> 'themesion_icon_picker',
				'settings'		=> 'header-box2-icon',
				'label'			=> esc_attr__( '2nd box - Icon', 'itinc' ),
				'description'	=> esc_attr__( 'Select icon for 2nd box', 'itinc' ),
				'default'		=> esc_attr('thsn-itinc-icon thsn-itinc-icon-mail;fa fa-info-circle;sgicon sgicon-Mail;'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),

					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-box2-title',
				'label'		=> esc_attr__( '2nd Box - Title', 'itinc' ),
				'default'	=> esc_attr('Request on'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
				'partial_refresh'	=> array(
					'header-box2-title'		=> array(
						'selector'			=> '.thsn-header-box-2 .thsn-header-box-title',
						'render_callback'	=> function() {
							return get_theme_mod('header-box2-title');
						},
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-box2-content',
				'label'		=> esc_attr__( '2nd Box - Content', 'itinc' ),
				'default'	=> esc_attr('Get Appointment'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'		=> 'link',
				'settings'	=> 'header-box2-link',
				'label'		=> esc_attr__( '2nd Box - Link', 'itinc' ),
				'default'	=> '',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			// 3rd Box
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-header-box3-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Header 3rd Box Contents', 'itinc' ) . '</h2> <span>' . esc_html__( 'Add or modify content for 3rd box in header area.', 'itinc' ) . '</span></div>',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'			=> 'themesion_icon_picker',
				'settings'		=> 'header-box3-icon',
				'label'			=> esc_attr__( '3rd box - Icon', 'itinc' ),
				'description'	=> esc_attr__( 'Select icon for 3rd box', 'itinc' ),
				'default'		=> esc_attr('thsn-itinc-icon thsn-itinc-icon-;fa fa-info-circle;sgicon sgicon-Phone2;'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-box3-title',
				'label'		=> esc_attr__( '3rd Box - Title', 'itinc' ),
				'default'	=> esc_attr('000 8888 999'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
				'partial_refresh'	=> array(
					'header-box3-title'		=> array(
						'selector'			=> '.thsn-header-box-3 .thsn-header-box-title',
						'render_callback'	=> function() {
							return get_theme_mod('header-box3-title');
						},
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-box3-content',
				'label'		=> esc_attr__( '3rd Box - Content', 'itinc' ),
				'default'	=> esc_attr('Free Call'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'		=> 'link',
				'settings'	=> 'header-box3-link',
				'label'		=> esc_attr__( '3rd Box - Link', 'itinc' ),
				'default'	=> '',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-header-box-typography',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Header Box Typography', 'itinc' ) . '</h2> <span>' . esc_html__( 'Select or change header box typography', 'itinc' ) . '</span></div>',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'header-box-title-typography',
				'label'			=> esc_attr__( 'Header Typography - Box Title', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '800',
					'font-size'			=> '17px',
					'line-height'		=> '27px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'thsn-output'	=> '.thsn-header-box-title',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'header-box-content-typography',
				'label'			=> esc_attr__( 'Header Typography - Box Content', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '700',
					'font-size'			=> '15px',
					'line-height'		=> '25px',
					'letter-spacing'	=> '1px',
					'color'				=> '#b0b6bf',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'thsn-output'	=> '.thsn-header-box-content',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),

			// Header contact info
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-header-contactinfo-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Header Contact Info', 'itinc' ) . '</h2> <span>' . esc_html__( 'Add or modify contact information.', 'itinc' ) . '</span></div>',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '2',
						),		
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-contactinfo-line1',
				'label'		=> esc_attr__( 'Contact Info - 1st line', 'itinc' ),
				'default'	=> esc_attr('+0 123 456 78000'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '2',
						),
					)
				),
				'partial_refresh'	=> array(
					'header-box1-title'		=> array(
						'selector'			=> '.thsn-right-box .thsn-header-contactinfo .thsn-header-button-text-1',
						'render_callback'	=> function() {
							return get_theme_mod('header-contactinfor-line1');
						},
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'header-contactinfo-line2',
				'label'		=> esc_attr__( 'Contact Info - 2nd line', 'itinc' ),
				'default'	=> esc_attr('Mon - Fri: 09:00 am to 6:00 pm'),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '2',
						),
					)
				),
			),
			array(
				'type'		=> 'link',
				'settings'	=> 'header-bcontactinfo-link',
				'label'		=> esc_attr__( 'Contact Info Link', 'itinc' ),
				'default'	=> '',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '2',
						),
					)
				),
			),

			// Header button
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-header-button-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Header Button', 'itinc' ) . '</h2> <span>' . esc_html__( 'Set header button title and link', 'itinc' ) . '</span></div>',
			),
			array(
				'type'				=> 'text',
				'settings'			=> 'header-btn-text',
				'label'				=> esc_attr__( 'Header Button Text', 'itinc' ),
				'default'			=> '',
				'partial_refresh'	=> array(
					'header-btn-text'	=> array(
						'selector'			=> '.thsn-header-button',
						'render_callback'	=> function() {
							return thsn_header_button( array('inneronly'=>'yes') );
						},
					)
				),
			),
			array(
				'type'				=> 'text',
				'settings'			=> 'header-btn-url',
				'label'				=> esc_attr__( 'Header Button Link (URL)', 'itinc' ),
				'default'			=> '',
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-header-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'General Options', 'itinc' ) . '</h2> <span>' . esc_html__( 'Common options that apply to all header styles', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'header-height',
				'label'			=> esc_attr__( 'Header Height (in pixel)', 'itinc' ),
				'description'	=> esc_attr__( 'Select header height', 'itinc' ),
				'default'		=> 120,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 900,
					'step'			=> 1,
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'header-bgcolor',
				'label'				=> esc_html__( 'Select header background color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> $pre_color_list,
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'header-background-color',
				'label'			=> esc_attr__( 'Header Background Color', 'itinc' ),
				'description'	=> esc_attr__( 'Select custom color for header background', 'itinc' ),
				'default'		=> '#ffffff',
				'active_callback'=> array(
					array(
						'setting'	=> 'header-bgcolor',
						'operator'	=> '==',
						'value'		=> 'custom',
					)
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'menu-bgcolor',
				'label'				=> esc_html__( 'Select menu area background color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> $pre_color_list,
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'menu-background-color',
				'label'			=> esc_attr__( 'Menu Area Background Color', 'itinc' ),
				'description'	=> esc_attr__( 'Select custom color for Menu area background', 'itinc' ),
				'default'		=> '#ffffff',
				'active_callback'=> array(
					array(
						'setting'	=> 'menu-bgcolor',
						'operator'	=> '==',
						'value'		=> 'custom',
					)
				),
			),
			// Search in Header
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-search-header-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Search in Header', 'itinc' ) . '</h2> <span>' . esc_html__( 'Options for search in header area', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'header-search',
				'label'			=> esc_attr__( 'Show Search Icon in Header Area?', 'itinc' ),
				'description'	=> esc_attr__( 'Select YES to show search icon in header area.', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'header-search-placeholder',
				'label'			=> esc_attr__( 'Search input placeholder text', 'itinc' ),
				'description'	=> esc_attr__( 'Search input placeholder text', 'itinc' ),
				'default'		=> esc_attr__( 'Write Search Keyword & Press Enter', 'itinc' ),
				'active_callback' => array(
					array(
						'setting'	=> 'header-search',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'header-search-btn-text',
				'label'			=> esc_attr__( 'Search button text', 'itinc' ),
				'description'	=> esc_attr__( 'Search button text', 'itinc' ),
				'default'		=> esc_attr__( 'Search', 'itinc' ),
				'active_callback' => array(
					array(
						'setting'	=> 'header-search',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			// Sticky Header
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-sticky-header-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Sticky Header Options', 'itinc' ) . '</h2> <span>' . esc_html__( 'Options for sticky header area', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'sticky-header',
				'label'			=> esc_attr__( 'Sticky Header on Scroll?', 'itinc' ),
				'description'	=> esc_attr__( 'Select YES to make header sticky on scroll.', 'itinc' ),
				'default'		=> '1',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'sticky-header-height',
				'label'			=> esc_attr__( 'Sticky Area Height (in pixel)', 'itinc' ),
				'description'	=> esc_attr__( 'Select Area height for sticky header', 'itinc' ),
				'default'		=> 90,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 300,
					'step'			=> 1,
				),
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '1',
						),
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '3',
						),
						array(
							'setting'	=> 'header-style',
							'operator'	=> '==',
							'value'		=> '4',
						),
					)
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'sticky-header-bgcolor',
				'label'				=> esc_html__( 'Sticky Area Background Color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> $pre_color_list,
				'active_callback'	=> array(
					array(
						'setting'	=> 'sticky-header',
						'operator'	=> '==',
						'value'		=> '1',
					)
				),
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'sticky-header-background-color',
				'label'			=> esc_attr__( 'Sticky Header Background Color', 'itinc' ),
				'description'	=> esc_attr__( 'Select custom color for sticky header background', 'itinc' ),
				'default'		=> '#ffffff',
				'active_callback'=> array(
					array(
						'setting'	=> 'sticky-header',
						'operator'	=> '==',
						'value'		=> '1',
					),
					array(
						'setting'	=> 'sticky-header-bgcolor',
						'operator'	=> '==',
						'value'		=> 'custom',
					)
				),
			),
			// Responsive Header
			array(
				'type'			=> 'custom',
				'settings'		=> 'responsive-header-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Responsive Header Options', 'itinc' ) . '</h2> <span>' . esc_html__( 'Options for responsive (mobile or tablet mode) header area', 'itinc' ) . '</span></div>',
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'responsive-header-bgcolor',
				'label'				=> esc_html__( 'Select header background color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> $pre_two_color_list,
			),
		),
	),
	// Menu Options
	'menu_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Menu Options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			// Main Menu Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'main-menu-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Main Menu Options', 'itinc' ) . '</h2> <span>' . esc_html__( 'Set Main Menu font settings', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'main-menu-typography',
				'label'			=> esc_attr__( 'Main Menu Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '700',
					'font-size'			=> '16px',
					'line-height'		=> '20px',
					'letter-spacing'	=> '0.5px',
					'color'				=> '#202426',
					'text-transform'	=> 'uppercase',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.thsn-navbar div > ul > li > a',
			),
			array(
				'type'			=> 'color',
				'settings'		=> 'main-menu-sticky-color',
				'label'			=> esc_attr__( 'Main Menu Text Color for Sticky Header', 'itinc' ),
				'description'	=> esc_attr__( 'This color will be applied to main menu text when header is sticky', 'itinc' ),
				'default'		=> '#09162a',
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'main-menu-active-color',
				'label'			=> esc_attr__( 'Main Menu Active Link Color', 'itinc' ),
				'description'	=> esc_attr__( 'This color will be applied to main menu when the menu link is active', 'itinc' ),
				'default'		=> 'globalcolor',
				'choices'		=> $pre_text_color_list,
			),
			// Dropdown Menu Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'drop-down-menu-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Dropdown Menu Options', 'itinc' ) . '</h2> <span>' . esc_html__( 'Set Dropdown font settings', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'dropdown-menu-typography',
				'label'			=> esc_attr__( 'Dropdown Menu Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '700',
					'font-size'			=> '14px',
					'line-height'		=> '1.5',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.thsn-navbar ul ul a',
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'drop-down-menu-active-color',
				'label'				=> esc_html__( 'Dropdown Menu Active Color', 'itinc' ),
				'default'			=> 'globalcolor',
				'choices'			=> $pre_text_color_list,
			),
			array(
				'type'			=> 'background',
				'settings'		=> 'dropdown_background',
				'label'			=> esc_attr__( 'Dropdown Menu Background', 'itinc' ),
				'description'	=> esc_attr__( 'Background settings for Dropdown Menu', 'itinc' ),
				'default'		=> array(
					'background-color'		=> '#f6f6f6',
					'background-image'		=> '',
					'background-repeat'		=> 'repeat',
					'background-position'	=> 'center center',
					'background-size'		=> 'cover',
					'background-attachment'	=> 'scroll',
				),
				'thsn-output'	=> '.thsn-navbar ul ul,.thsn-navbar ul ul:before',
			),
		)
	),
	// Titlebar Options
	'titlebar_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Titlebar Options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'switch',
				'settings'		=> 'titlebar-enable',
				'label'			=> esc_attr__( 'Show Titlebar?', 'itinc' ),
				'description'	=> esc_attr__( 'Show or hide Titlebar', 'itinc' ),
				'default'		=> '1',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'titlebar-height',
				'label'			=> esc_attr__( 'Titlebar Height', 'itinc' ),
				'default'		=> 200,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 1000,
					'step'			=> 1,
				),
				'active_callback'	=> array( array(
					'setting'			=> 'titlebar-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'select',
				'settings'		=> 'titlebar-style',
				'label'			=> esc_attr__( 'Titlebar Style', 'itinc' ),
				'description'	=> esc_attr__( 'Select style for Titlebar', 'itinc' ),
				'default'		=> 'left',
				'choices'		=>  array(
					'left'			=> esc_attr__( 'All Left Aligned', 'itinc' ),
					'center'		=> esc_attr__( 'All Center Aligned', 'itinc' ),
				),
				'active_callback'	=> array( array(
					'setting'			=> 'titlebar-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'titlebar-hide-breadcrumb',
				'label'			=> esc_attr__( 'Hide Breadcrumb?', 'itinc' ),
				'description'	=> esc_attr__( 'Show or hide breadcrumb in Titlebar', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
				'active_callback'	=> array( array(
					'setting'			=> 'titlebar-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'multicheck',
				'settings'		=> 'titlebar-bg-featured',
				'label'			=> esc_attr__( 'Featured Image as Titlebar Background', 'itinc' ),
				'description'	=> esc_attr__( 'Select which section (CPT) will show featured image as background image in Titlebar. NOTE: This will work for Single view only.', 'itinc' ),
				'default'		=> array(),
				'choices'		=> array(
					'post'				=> sprintf( esc_attr__('For %1$s', 'itinc') , '"Post"' ),
					'page'				=> sprintf( esc_attr__('For %1$s', 'itinc') , '"Page"' ),
					'thsn-portfolio'	=> sprintf( esc_attr__('For %1$s', 'itinc') , '"'.$portfolio_cpt_singular_title.'"' ),
					'thsn-team-member'	=> sprintf( esc_attr__('For %1$s', 'itinc') , '"'.$team_cpt_singular_title.'"' ),
				),
				'active_callback'	=> array( array(
					'setting'			=> 'titlebar-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'titlebar-bgcolor',
				'label'				=> esc_html__( 'Select Titlebar background color', 'itinc' ),
				'default'			=> 'custom',
				'choices'			=> array_merge( array('gradientcolor'	=> get_template_directory_uri() . '/includes/images/precolor-gradientcolor.png',), $pre_color_list),
				'active_callback'	=> array( array(
					'setting'			=> 'titlebar-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'background',
				'settings'		=> 'titlebar-background',
				'label'			=> esc_attr__( 'Background', 'itinc' ),
				'description'	=> esc_attr__( 'Background Settings', 'itinc' ),
				'default'		=> array(
					'background-color'      => '#f6f6f6',
					'background-repeat'     => 'no-repeat',
					'background-position'   => 'center center',
					'background-size'       => 'cover',
					'background-attachment' => 'scroll',
				),
				'thsn-output'	=> '.thsn-title-bar-wrapper, .thsn-title-bar-wrapper.thsn-bg-color-custom:before',
				'active_callback' => array( array(
					'setting'		=> 'titlebar-enable',
					'operator'		=> '==',
					'value'			=> '1',
				) ),
			),
			array(
				'type'		=> 'typography',
				'settings'	=> 'titlebar-heading-typography',
				'label'		=> esc_attr__( 'Titlebar Heading Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '700',
					'font-size'			=> '42px',
					'line-height'		=> '52px',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.thsn-tbar-title',
				'active_callback'	=> array( array(
					'setting'			=> 'titlebar-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'titlebar-subheading-typography',
				'label'			=> esc_attr__( 'Titlebar Sub-heading Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '700',
					'font-size'			=> '16px',
					'line-height'		=> '1.5',
					'letter-spacing'	=> '0px',
					'color'				=> '#202426',
					'text-transform'	=> 'none',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
				'priority'		=> 10,
				'thsn-output'	=> '.thsn-tbar-subtitle',
				'active_callback'	=> array( array(
					'setting'			=> 'titlebar-enable',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'			=> 'typography',
				'settings'		=> 'titlebar-breadcrumb-typography',
				'label'			=> esc_attr__( 'Titlebar Breadcrumb Typography', 'itinc' ),
				'choices'		=> [ 'fonts' => [ 'google' => [ 'popularity', 600 ], ], ],
				'default'		=> array(
					'font-family'		=> 'Rajdhani',
					'variant'			=> '700',
					'font-size'			=> '12px',
					'line-height'		=> '1.5',
					'letter-spacing'	=> '1px',
					'color'				=> '#6d7a8c',
					'text-transform'	=> 'uppercase',
					'font-backup'		=> '',
					'font-style'		=> 'normal',
				),
			'priority'				=> 10,
				'thsn-output'		=> '.thsn-breadcrumb, .thsn-breadcrumb a',
				'active_callback'	=> array(
					array(
						'setting'			=> 'titlebar-enable',
						'operator'			=> '==',
						'value'				=> '1',
					),
					array(
						'setting'			=> 'titlebar-hide-breadcrumb',
						'operator'			=> '==',
						'value'				=> '0',
					)
				),
			),
		),
	),
	// Footer Options
	'footer_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Footer Options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			// Footer Background settings
			array(
				'type'			=> 'custom',
				'settings'		=> 'footer-background-settings-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Footer Background Settings', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Manage footer background settings from here', 'itinc' ) , $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'footer-bgcolor',
				'label'			=> esc_html__( 'Select Footer background color', 'itinc' ),
				'default'		=> 'blackish',
				'choices'		=> array_merge( array('gradientcolor'	=> get_template_directory_uri() . '/includes/images/precolor-gradientcolor.png',), $pre_color_list),
			),
			array(
				'type'			=> 'background',
				'settings'		=> 'footer-background',
				'label'			=> esc_attr__( 'Background', 'itinc' ),
				'description'	=> esc_attr__( 'Background Settings', 'itinc' ),
				'default'		=> array(
					'background-color'		=> '#202426',
					'background-image'		=> '',
					'background-repeat'		=> 'repeat',
					'background-position'	=> 'center center',
					'background-size'		=> 'cover',
					'background-attachment'	=> 'scroll',
				),

				'thsn-output'	=> '.site-footer, .site-footer.thsn-bg-color-custom:before',
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'footer-text-color',
				'label'				=> esc_attr__( 'Select Footer Text Color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> $pre_text_color_list,
			),

			// Footer Boxes Area
			array(
				'type'			=> 'custom',
				'settings'		=> 'footer-boxes-area-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Footer Boxes Area', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Manage footer boxes from here', 'itinc' ) , $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'footer-boxes-area',
				'label'			=> esc_attr__( 'Show footer boxes?', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			// 1st box
			array(
				'type'			=> 'themesion_icon_picker',
				'settings'		=> 'footer-box-1-icon',
				'label'			=> esc_attr__( '1st Footer box - Icon', 'itinc' ),
				'description'	=> esc_attr__( 'Select icon for 1st box', 'itinc' ),
				'default'		=> esc_attr('thsn-itinc-icon thsn-itinc-icon-email;fa fa-map-marker;sgicon sgicon-Pointer'),
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'footer-box-1-title',
				'label'		=> esc_attr__( '1st Footer Box - Title', 'itinc' ),
				'default'	=> esc_attr('mail@itinc.com'),
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
				'partial_refresh'	=> array(
					'footer-box-1-title'		=> array(
						'selector'			=> '.thsn-label-1',
						'render_callback'	=> function() {
							return get_theme_mod('footer-box-1-title');
						},
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'footer-box-1-content',
				'label'		=> esc_attr__( '1st Footer Box - Content', 'itinc' ),
				'default'	=> '',
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'		=> 'link',
				'settings'	=> 'footer-box-1-link',
				'label'		=> esc_attr__( '1st Footer Box - Link', 'itinc' ),
				'default'	=> '',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'footer-boxes-area',
							'operator'	=> '==',
							'value'		=> '1',
						),
					)
				),
			),
			// 2nd box
			array(
				'type'			=> 'themesion_icon_picker',
				'settings'		=> 'footer-box-2-icon',
				'label'			=> esc_attr__( '2nd Footer box - Icon', 'itinc' ),
				'description'	=> esc_attr__( 'Select icon for 2nd box', 'itinc' ),
				'default'		=> esc_attr('thsn-itinc-icon thsn-itinc-icon-phone-call;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer'),
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'footer-box-2-title',
				'label'		=> esc_attr__( '2nd Footer Box - Title', 'itinc' ),
				'default'	=> esc_attr('(+00)888.666.88'),
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
				'partial_refresh'	=> array(
					'footer-box-2-title'		=> array(
						'selector'			=> '.thsn-label-2',
						'render_callback'	=> function() {
							return get_theme_mod('footer-box-2-title');
						},
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'footer-box-2-content',
				'label'		=> esc_attr__( '2nd Footer Box - Content', 'itinc' ),
				'default'	=> '',
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'		=> 'link',
				'settings'	=> 'footer-box-2-link',
				'label'		=> esc_attr__( '2nd Footer Box - Link', 'itinc' ),
				'default'	=> '',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'footer-boxes-area',
							'operator'	=> '==',
							'value'		=> '1',
						),
					)
				),
			),
			// 3rd
			array(
				'type'			=> 'themesion_icon_picker',
				'settings'		=> 'footer-box-3-icon',
				'label'			=> esc_attr__( '3rd Footer box - Icon', 'itinc' ),
				'description'	=> esc_attr__( 'Select icon for 3rd box', 'itinc' ),
				'default'		=> esc_attr('thsn-itinc-icon thsn-itinc-icon-pin;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer;far fa-address-book;fas fa-star;fab fa-facebook-square;mdi mdi-group;sgicon sgicon-Pointer'),
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'footer-box-3-title',
				'label'		=> esc_attr__( '3rd Footer Box - Title', 'itinc' ),
				'default'	=> esc_attr('456, New York 33454, NY.'),
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
				'partial_refresh'	=> array(
					'footer-box-3-title'		=> array(
						'selector'			=> '.thsn-label-3',
						'render_callback'	=> function() {
							return get_theme_mod('footer-box-3-title');
						},
					)
				),
			),
			array(
				'type'		=> 'text',
				'settings'	=> 'footer-box-3-content',
				'label'		=> esc_attr__( '3rd Footer Box - Content', 'itinc' ),
				'default'	=> '',
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),
			array(
				'type'		=> 'link',
				'settings'	=> 'footer-box-3-link',
				'label'		=> esc_attr__( '3rd Footer Box - Link', 'itinc' ),
				'default'	=> '',
				'active_callback'=> array(
					array(
						array(
							'setting'	=> 'footer-boxes-area',
							'operator'	=> '==',
							'value'		=> '1',
						),
					)
				),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'footer-boxes-social',
				'label'			=> esc_attr__( 'Show social at footer with boxes?', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
				'active_callback'	=> array( array(
					'setting'			=> 'footer-boxes-area',
					'operator'			=> '==',
					'value'				=> '1',
				) ),
			),

			// Footer Widget Area
			array(
				'type'			=> 'custom',
				'settings'		=> 'footer-widget-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Footer Widget Area', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Manage widget area settings', 'itinc' ) , $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'footer-column',
				'label'		=> esc_html__( 'Footer Widget Column Type', 'itinc' ),
				'description'	=> esc_html__( 'This will show widgets. You can manage it from "Admin > Appearance > Widgets" section.', 'itinc' ),
				'default'	=> '3-3-3-3',
				'choices'		=> array(
					'12'		=> get_template_directory_uri() . '/includes/images/footer-12.png',
					'6-6'		=> get_template_directory_uri() . '/includes/images/footer-6-6.png',
					'4-4-4'		=> get_template_directory_uri() . '/includes/images/footer-4-4-4.png',
					'3-3-3-3'	=> get_template_directory_uri() . '/includes/images/footer-3-3-3-3.png',
					'2-2-2-6'	=> get_template_directory_uri() . '/includes/images/footer-2-2-2-6.png',
					'6-2-2-2'	=> get_template_directory_uri() . '/includes/images/footer-6-2-2-2.png',
					'8-4'		=> get_template_directory_uri() . '/includes/images/footer-8-4.png',
					'4-8'		=> get_template_directory_uri() . '/includes/images/footer-4-8.png',
					'custom'	=> get_template_directory_uri() . '/includes/images/footer-col-custom.png',
				),
			),
			array(
				'type'			=> 'select',
				'settings'		=> 'footer-1-col-width',
				'label'			=> esc_attr__( 'Footer Widget Width - 1st Column', 'itinc' ),
				'description'	=> esc_attr__( 'Set custom width of the 1st column in footer widget area', 'itinc' ),
				'default'		=> '30',
				'choices'		=> $footer_col_width_array,
				'active_callback'	=> array(
					array(
						'setting'			=> 'footer-column',
						'operator'			=> '==',
						'value'				=> 'custom',
					)
				),
			),
			array(
				'type'			=> 'select',
				'settings'		=> 'footer-2-col-width',
				'label'			=> esc_attr__( 'Footer Widget Width - 2nd Column', 'itinc' ),
				'description'	=> esc_attr__( 'Set custom width of the 2nd column in footer widget area', 'itinc' ),
				'default'		=> '20',
				'choices'		=> $footer_col_width_array,
				'active_callback'	=> array(
					array(
						'setting'			=> 'footer-column',
						'operator'			=> '==',
						'value'				=> 'custom',
					)
				),
			),
			array(
				'type'			=> 'select',
				'settings'		=> 'footer-3-col-width',
				'label'			=> esc_attr__( 'Footer Widget Width - 3rd Column', 'itinc' ),
				'description'	=> esc_attr__( 'Set custom width of the 3rd column in footer widget area', 'itinc' ),
				'default'		=> '20',
				'choices'		=> $footer_col_width_array,
				'active_callback'	=> array(
					array(
						'setting'			=> 'footer-column',
						'operator'			=> '==',
						'value'				=> 'custom',
					)
				),
			),
			array(
				'type'			=> 'select',
				'settings'		=> 'footer-4-col-width',
				'label'			=> esc_attr__( 'Footer Widget Width - 4th Column', 'itinc' ),
				'description'	=> esc_attr__( 'Set custom width of the 4th column in footer widget area', 'itinc' ),
				'default'		=> '30',
				'choices'		=> $footer_col_width_array,
				'active_callback'	=> array(
					array(
						'setting'			=> 'footer-column',
						'operator'			=> '==',
						'value'				=> 'custom',
					)
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'footer-widget-bgcolor',
				'label'				=> esc_html__( 'Select Footer Widget Area background color', 'itinc' ),
				'default'			=> 'transparent',
				'choices'			=> array_merge( array('gradientcolor'	=> get_template_directory_uri() . '/includes/images/precolor-gradientcolor.png',), $pre_color_list),
			),
			array(
				'type'			=> 'background',
				'settings'		=> 'footer-widget-background',
				'label'			=> esc_attr__( 'Footer Widget Area Background', 'itinc' ),
				'description'	=> esc_attr__( 'Background Settings for footer widget area', 'itinc' ),
				'default'		=> array(
					'background-color'		=> '',
				    'background-image'		=> '',
				    'background-repeat'		=> 'repeat',
				    'background-position'	=> 'center center',
				    'background-size'		=> 'cover',
				    'background-attachment'	=> 'scroll',
				),
				'thsn-output'	=> '.thsn-footer-widget-area, .thsn-footer-widget-area.thsn-bg-color-custom:before',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'footer-widget-color-switch',
				'label'			=> esc_attr__( 'Set Custom Text Color for Widget Area?', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'footer-widget-text-color',
				'label'				=> esc_attr__( 'Footer Widget Area Text Color', 'itinc' ),
				'default'			=> 'transparent',
				'choices'			=> $pre_text_color_list,
				'active_callback'	=> array(
					array(
						'setting'			=> 'footer-widget-color-switch',
						'operator'			=> '==',
						'value'				=> '1',
					)
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'footer-copyright-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Footer Copyright Text Area', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Manage bottom footer area from here', 'itinc' ) , $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'footer-copyright-bgcolor',
				'label'				=> esc_html__( 'Select Footer Copyright Area background color', 'itinc' ),
				'default'			=> 'transparent',
				'choices'			=> array_merge( array('gradientcolor'	=> get_template_directory_uri() . '/includes/images/precolor-gradientcolor.png',), $pre_color_list),
			),
			array(
				'type'			=> 'background',
				'settings'		=> 'footer-copyright-background',
				'label'			=> esc_attr__( 'Footer Copyright Area Background', 'itinc' ),
				'description'	=> esc_attr__( 'Background Settings for footer copyright area', 'itinc' ),
				'default'		=> array(
					'background-color'		=> '',
				    'background-image'		=> '',
				    'background-repeat'		=> 'repeat',
				    'background-position'	=> 'center center',
				    'background-size'		=> 'cover',
				    'background-attachment'	=> 'scroll',
				),
				'thsn-output'	=> '.thsn-footer-text-area, .thsn-footer-text-area.thsn-bg-color-custom:before',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'footer-copyright-color-switch',
				'label'			=> esc_attr__( 'Set Custom Text Color for Copyright Area?', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'footer-copyright-text-color',
				'label'				=> esc_attr__( 'Footer Copyright Area Text Color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> array_merge( array('inherit' => get_template_directory_uri() . '/includes/images/precolor-inherit.png'), $pre_text_color_list ),
				'active_callback'	=> array(
					array(
						'setting'		=> 'footer-copyright-color-switch',
						'operator'		=> '==',
						'value'			=> '1',
					)
				),
			),
			array(
				'type'			=> 'editor',
				'settings'		=> 'copyright-text',
				'label'			=> esc_attr__( 'Footer Copyright Text', 'itinc' ),
				'default'		=> sprintf( esc_attr__( 'Copyright &copy; %1$s %2$s, All Rights Reserved.', 'itinc' ), date('Y'), '<a href="' . esc_url( home_url( '/' ) ) . '">' . get_bloginfo('name') . '</a>' ) ,
				'priority'		=> 10,
				'partial_refresh'	=> array(
					'copyright-text'		=> array(
						'selector'			=> '.thsn-footer-copyright-text-area',
						'render_callback'	=> function() {
							return get_theme_mod('copyright-text');
						},
					)
				),
			),
			array(
				'type'				=> 'image',
				'settings'			=> 'footer-logo',
				'label'				=> esc_attr__( 'Footer Logo', 'itinc' ),
				'description'		=> esc_attr__( 'This will appear after Copyright text', 'itinc' ),
				'default'			=> '',
			),
			array(
				'type'			=> 'select',
				'settings'		=> 'footer-copyright-right-content',
				'label'			=> esc_attr__( 'Footer Right Area', 'itinc' ),
				'description'	=> esc_attr__( 'What you like to show at right side or copyright text', 'itinc' ),
				'default'		=> 'social',
				'choices'		=> array(
					'social'		=> esc_attr__( 'Show Social Links', 'itinc' ),
					'menu'			=> esc_attr__( 'Show Footer Menu', 'itinc' ),
				),
			),
		)
	),
	// Social Links Options
	'social_links_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Social Links Options', 'itinc' ),
			'description'	=> esc_attr__( 'You can use [thsn-social-links] shortcode for social list with icon.', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => $social_options_array
	),
	// Blog Settings
	'blog_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Blog Options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-blog-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Blog Settings', 'itinc' ) . '</h2> <span>' . esc_html__( 'Settings for Blogroll, Category, Tag, Archives etc section.', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'blogroll-view',
				'label'			=> esc_html__( 'Blogroll view', 'itinc' ),
				'default'		=> 'classic',
				'choices'		=> thsn_element_template_list('blog', 'customizer'),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'blogroll-column',
				'label'			=> esc_html__( 'Blogroll column', 'itinc' ),
				'default'		=> '3',
				'choices'		=> $column_list,
				'active_callback'	=> array(
					array(
						'setting'		=> 'blogroll-view',
						'operator'		=> '!=',
						'value'			=> 'classic',
					)
				),
			),
			array(
			'type'			=> 'switch',
			'settings'		=> 'blog-show-related',
			'label'			=> esc_attr__( 'Show Related Post?', 'itinc' ),
			'default'		=> '0',
			'choices'     => array(
				'on'  => esc_attr__( 'Yes', 'itinc' ),
				'off' => esc_attr__( 'No', 'itinc' ),
			),
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'blog-related-title',
				'label'			=> esc_attr__( 'Related Post Section Title', 'itinc' ), 
				'description'	=> esc_attr__( 'Related Area Title', 'itinc' ),
				'default'		=> esc_attr__( 'Related Post', 'itinc' ),
				'active_callback' => array(
					array(
						'setting'	=> 'blog-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'blog-related-count',
				'label'			=> esc_attr__( 'How many post you like to show', 'itinc' ),
				'default'		=> 3,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 50,
					'step'			=> 1,
				),
				'active_callback' => array(
					array(
						'setting'	=> 'blog-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'blog-related-column',
				'label'			=>  esc_html__('Related Post Column', 'itinc' ),
				'default'		=> '3',
				'choices'     => $column_list,
				'active_callback' => array(
					array(
						'setting'	=> 'blog-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'blog-related-style',
				'label'			=> esc_html__( 'Related Post View', 'itinc' ),
				'default'		=> '1',
				'choices'     => $blog_styles,
				'active_callback' => array(
					array(
						'setting'	=> 'blog-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'multicheck',
				'settings'		=> 'blog-social-share',
				'label'			=> esc_attr__( 'Social share for Blog', 'itinc' ),
				'description'	=> esc_attr__( 'Select which social share buttons will appear on blog post.', 'itinc' ),
				'default'		=> array(),
				'choices'		=> thsn_social_share_list('customizer'),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-blog-classic-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Blog Classic Settings', 'itinc' ) . '</h2> <span>' . esc_html__( 'Settings for Blog Classic view.', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'blog-classic-limit-switch',
				'label'			=> esc_attr__( 'Limit Content Words for Blog Classic view?', 'itinc' ),
				'default'		=> '0',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'blog-classic-limit',
				'label'			=> esc_attr__( 'Set Word Limit for Blog Classic view', 'itinc' ),
				'description'	=> esc_attr__( 'This will add limited words before "Read More" link. This is useful if you didn\'t added Read More link in posts.', 'itinc' ),
				'default'		=> 15,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 900,
					'step'			=> 1,
				),
				'active_callback' => array(
					array(
						'setting'	=> 'blog-classic-limit-switch',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-blog-element-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Blog Style Elements (boxes) Settings', 'itinc' ) . '</h2> <span>' . esc_html__( 'Settings for Blog Style Elements.', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'blog-element-limit-switch',
				'label'			=> esc_attr__( 'Limit Content Words for Blog Element view?', 'itinc' ),
				'default'		=> '1',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'blog-element-limit',
				'label'			=> esc_attr__( 'Limit Words for Blog Element view', 'itinc' ),
				'description'	=> esc_attr__( 'This will add limited words before "Read More" link.', 'itinc' ),
				'default'		=> 30,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 900,
					'step'			=> 1,
				),
				'active_callback' => array(
					array(
						'setting'	=> 'blog-element-limit-switch',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-blog-sidebar-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Sidebar Settings', 'itinc' ) . '</h2> <span>' . esc_html__( 'Select sidebar position Page and Blog section.', 'itinc' ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-post',
				'label'		=> esc_html__( 'Blog Sidebar', 'itinc' ),
				'default'	=> 'right',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
		)
	),
	// Portfolio Settings
	'portfolio_options' => array(
		'section_settings' => array(
			'title'			=> sprintf( esc_attr__( '%1$s options', 'itinc' ) , $portfolio_cpt_singular_title ) ,
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-single-portfolio-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Single %1$s Options', 'itinc' ), $portfolio_cpt_singular_title ) . '</h2> <span>' . sprintf( esc_attr__( 'Options for Single %1$s Section', 'itinc' ), $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'portfolio-single-style',
				'label'		=> sprintf( esc_html__( '%1$s Single View Style', 'itinc' ), $portfolio_cpt_singular_title ),
				'default'	=> '2',
				'choices'		=> $portfolio_single_style_array,
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-single-portfolio-detailsbox-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Single %1$s Details Box Options', 'itinc' ), $portfolio_cpt_singular_title ) . '</h2> <span>' . esc_attr__( 'Details Box Settings', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-details-title',
				'label'			=> sprintf( esc_attr__( 'Single %1$s Details Box Title', 'itinc' ), $portfolio_cpt_singular_title ),
				'description'	=> esc_attr__( 'Details Box Title', 'itinc' ),
				'default'		=> esc_attr__( 'Project info', 'itinc' ),
			),
			array(
				'type'			=> 'repeater',
				'label'			=> sprintf( esc_attr__( 'Single %1$s Details Box', 'itinc' ), $portfolio_cpt_singular_title ),
				'row_label'		=> array(
					'type'			=> 'field',
					'value'			=> esc_attr__('Line', 'itinc' ),
					'field'			=> 'line_title',
				),
				'button_label'	=> esc_attr__('Add New Line', 'itinc' ),
				'settings'		=> 'portfolio-details',
				'fields'		=> array(
					'line_title'	=> array(
						'type'			=> 'text',
						'label'			=> esc_attr__( 'Line Title', 'itinc' ),
						'description'	=> esc_attr__( 'This will be the label for the line', 'itinc' ),
						'default'		=> '',
					),
					'line_type'		=> array(
						'type'			=> 'select',
						'label'			=> esc_attr__( 'Line Type', 'itinc' ),
						'description'	=> esc_attr__( 'This will be type for the line', 'itinc' ),
						'default'		=> 'text',
						'choices'		=> array(
							'text'			=> esc_attr__( 'Normal Text', 'itinc' ),
							'category'		=> esc_attr__( 'Category List (without link)', 'itinc' ),
							'category-link'	=> esc_attr__( 'Category List (with link)', 'itinc' ),
						)
					),
				),
				'default'		=> array(
					array(
						'line_title'	=> esc_attr__('Date', 'itinc'),
						'line_type'		=> 'text',
					),
					array(
						'line_title'	=> esc_attr__('Client', 'itinc'),
						'line_type'		=> 'text',
					),
					array(
						'line_title'	=> esc_attr__('Category', 'itinc'),
						'line_type'		=> 'category-link',
					),
					array(
						'line_title'	=> esc_attr__('Address', 'itinc'),
						'line_type'		=> 'text',
					),
    			),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-single-portfolio-related-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Related %1$s Options', 'itinc' ), $portfolio_cpt_singular_title ) . '</h2> <span>' . sprintf( esc_html__( 'Options for Related %1$s', 'itinc' ), $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'portfolio-show-related',
				'label'			=> sprintf( esc_attr__( 'Show Related %1$s?', 'itinc' ), $portfolio_cpt_singular_title ),
				'default'		=> '1',
				'choices'		=> array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-related-title',
				'label'			=> sprintf( esc_attr__( 'Related %1$s Section Title', 'itinc' ), $portfolio_cpt_singular_title ),
				'description'	=> esc_attr__( 'Related Area Title', 'itinc' ),
				'default'		=> sprintf( esc_attr__( 'Related %1$s', 'itinc' ), $portfolio_cpt_singular_title ),
				'active_callback' => array(
					array(
						'setting'	=> 'portfolio-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'portfolio-related-count',
				'label'			=> sprintf( esc_attr__( 'How many %1$s you like to show', 'itinc' ), $portfolio_cpt_singular_title ),
				'default'		=> 3,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 50,
					'step'			=> 1,
				),
				'active_callback' => array(
					array(
						'setting'	=> 'portfolio-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'portfolio-related-column',
				'label'			=> sprintf( esc_html__( 'Related %1$s Column', 'itinc' ), $portfolio_cpt_singular_title ),
				'default'		=> '3',
				'choices'		=> $column_list,
				'active_callback' => array(
					array(
						'setting'	=> 'portfolio-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'portfolio-related-style',
				'label'			=> sprintf( esc_html__( 'Related %1$s View', 'itinc' ), $portfolio_cpt_singular_title ),
				'default'		=> '2',
				'choices'		=> thsn_element_template_list('portfolio', true),
				'active_callback' => array(
					array(
						'setting'	=> 'portfolio-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-portfolio-cat-view',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Element View Style for %1$s', 'itinc' ), $portfolio_cat_singular_title ) . '</h2> <span>' . sprintf( esc_attr__( 'Select view style for elements on %1$s', 'itinc' ) , $portfolio_cat_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'portfolio-cat-style',
				'label'			=> sprintf( esc_html__( 'Element View on %1$s', 'itinc' ), $portfolio_cat_singular_title ),
				'default'		=> '1',
				'choices'		=> thsn_element_template_list('portfolio', true),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'portfolio-cat-column',
				'label'			=> sprintf( esc_html__( '%1$s View Column', 'itinc' ), $portfolio_cat_singular_title ),
				'default'		=> '3',
				'choices'		=> $column_list,
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'portfolio-cat-count',
				'label'			=> sprintf( esc_attr__( 'How many %1$s you like to show on single %2$s page', 'itinc' ), $portfolio_cpt_singular_title, $portfolio_cat_singular_title ),
				'default'		=> 9,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 50,
					'step'			=> 1,
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-portfolio-sidebar-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Sidebar Options', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Sidebar options for %1$s Section', 'itinc' ) , $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-portfolio',
				'label'		=> sprintf( esc_html__( '%1$s Sidebar', 'itinc' ), $portfolio_cpt_singular_title ),
				'default'	=> 'no',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-portfolio-category',
				'label'		=> sprintf( esc_html__( '%1$s Sidebar', 'itinc' ), $portfolio_cat_singular_title ),
				'default'	=> 'right',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
			// Advanced Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'portfolio-advanced-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Advanced Options', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Advanced Options for %1$s Section', 'itinc' ) , $portfolio_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-cpt-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title', 'itinc' ) , $portfolio_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Title', 'itinc' ),
				'default'		=> esc_attr__( 'Portfolio', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-cpt-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title (Singular)', 'itinc' ) , $portfolio_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Portfolio', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-cpt-slug',
				'label'			=> sprintf( esc_attr__( '%1$s Section URl Slug', 'itinc' ) , $portfolio_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT URL slug.', 'itinc' ) . '<br />' . '<strong>' . esc_attr__( 'NOTE:', 'itinc' ) . '</strong> ' . sprintf( esc_attr__( 'After changing this, please go to %1$s section and save it once.', 'itinc' ), thsn_esc_kses('<a href="' . esc_url( get_admin_url().'options-permalink.php' ) . '" target="_blank"><strong>Settings > Permalinks</strong></a>') ) . '<br /><br />',
				'default'		=> esc_attr( 'portfolio' ),
				'priority'		=> 10,
			),
			// Portfolio Category
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-cat-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title', 'itinc' ) , $portfolio_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Title', 'itinc' ),
				'default'		=> esc_attr__( 'Portfolio Categories', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-cat-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title (Singular)', 'itinc' ) , $portfolio_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Portfolio Category', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'portfolio-cat-slug',
				'label'			=> sprintf( esc_attr__( '%1$s URl Slug', 'itinc' ) , $portfolio_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy URL slug', 'itinc' ),
				'description'	=> esc_attr__( 'Taxonomy URL slug.', 'itinc' ) . '<br />' . '<strong>' . esc_attr__( 'NOTE:', 'itinc' ) . '</strong> ' . sprintf( esc_attr__( 'After changing this, please go to %1$s section and save it once.', 'itinc' ), thsn_esc_kses('<a href="' . esc_url( get_admin_url().'options-permalink.php' ) . '" target="_blank"><strong>Settings > Permalinks</strong></a>') ) . '<br /><br />',
				'priority'		=> 10,
			),
		)
	),
	// Service Settings
	'service_options' => array(
		'section_settings' => array(
			'title'			=> sprintf( esc_attr__( '%1$s options', 'itinc' ) , $service_cpt_singular_title ) ,
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-single-service-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Single %1$s Options', 'itinc' ), $service_cpt_singular_title ) . '</h2> <span>' . sprintf( esc_attr__( 'Sidebar options for %1$s Section', 'itinc' ), $service_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'service-single-style',
				'label'		=> sprintf( esc_html__( '%1$s Single View Style', 'itinc' ), $service_cpt_singular_title ),
				'default'	=> '1',
				'choices'		=> $service_single_style_array,
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'service-single-image-hide',
				'label'			=> sprintf( esc_attr__( 'Hide Featured Image on Single %1$s page? ', 'itinc' ), $service_cpt_singular_title ),
				'default'		=> '0',
				'choices'     => array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
				'active_callback' => array(
					array(
						'setting'	=> 'service-single-style',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'service-show-related',
				'label'			=> sprintf( esc_attr__( 'Show Related %1$s', 'itinc' ), $service_cpt_singular_title ),
				'default'		=> '1',
				'choices'     => array(
					'on'  => esc_attr__( 'Yes', 'itinc' ),
					'off' => esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
			'type'			=> 'text',
			'settings'		=> 'service-related-title',
			'label'			=> sprintf( esc_attr__( 'Related %1$s Section Title', 'itinc' ), $service_cpt_singular_title ),
			'description'	=> esc_attr__( 'Related Area Title', 'itinc' ),
			'default'		=> sprintf( esc_attr__( 'Related %1$s', 'itinc' ), $service_cpt_singular_title ),
			'active_callback' => array(
				array(
					'setting'	=> 'service-show-related',
					'operator'	=> '==',
					'value'		=> '1',
				),
			),
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'service-related-count',
				'label'			=> sprintf( esc_attr__( 'How many %1$s you like to show', 'itinc' ), $service_cpt_singular_title ),
				'default'		=> 3,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 50,
					'step'			=> 1,
				),
				'active_callback' => array(
					array(
						'setting'	=> 'service-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'service-related-column',
				'label'			=> sprintf( esc_html__( 'Related %1$s Column', 'itinc' ), $service_cpt_singular_title ),
				'default'		=> '3',
				'choices'     => $column_list,
				'active_callback' => array(
					array(
						'setting'	=> 'service-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'service-related-style',
				'label'			=> sprintf( esc_html__( 'Related %1$s View', 'itinc' ), $service_cpt_singular_title ),
				'default'		=> '2',
				'choices'     => thsn_element_template_list('service', true),
				'active_callback' => array(
					array(
						'setting'	=> 'service-show-related',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-service-cat-view',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Element View Style for %1$s', 'itinc' ), $service_cat_singular_title ) . '</h2> <span>' . sprintf( esc_attr__( 'Select view style for elements on %1$s', 'itinc' ) , $service_cat_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'service-cat-style',
				'label'			=> sprintf( esc_html__( 'Element View on %1$s', 'itinc' ), $service_cat_singular_title ),
				'default'		=> '1',
				'choices'		=> thsn_element_template_list('service', true),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'service-cat-column',
				'label'			=> sprintf( esc_html__( '%1$s View Column', 'itinc' ), $service_cat_singular_title ),
				'default'		=> '3',
				'choices'		=> $column_list,
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'service-cat-count',
				'label'			=> sprintf( esc_attr__( 'How many %1$s you like to show on single %2$s page', 'itinc' ), $service_cpt_singular_title, $service_cat_singular_title ),
				'default'		=> 9,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 50,
					'step'			=> 1,
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-service-sidebar-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Sidebar Options', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Sidebar options for %1$s Section', 'itinc' ) , $service_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-service',
				'label'		=> sprintf( esc_html__( '%1$s Sidebar', 'itinc' ), $service_cpt_singular_title ),
				'default'	=> 'left',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-service-category',
				'label'		=> sprintf( esc_html__( '%1$s Sidebar', 'itinc' ), $service_cat_singular_title ),
				'default'	=> 'no',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
			// Advanced - Heading Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'service-advanced-heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Advanced Options', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Advanced Options for %1$s Section', 'itinc' ) , $service_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'service-cpt-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title', 'itinc' ) , $service_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Title', 'itinc' ),
				'default'		=> esc_attr__( 'Service', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'service-cpt-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title (Singular)', 'itinc' ) , $service_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Service', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'service-cpt-slug',
				'label'			=> sprintf( esc_attr__( '%1$s Section URl Slug', 'itinc' ) , $service_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT URL slug.', 'itinc' ) . '<br />' . '<strong>' . esc_attr__( 'NOTE:', 'itinc' ) . '</strong> ' . sprintf( esc_attr__( 'After changing this, please go to %1$s section and save it once.', 'itinc' ), thsn_esc_kses('<a href="' . esc_url( get_admin_url().'options-permalink.php' ) . '" target="_blank"><strong>Settings > Permalinks</strong></a>') ) . '<br /><br />',
				'default'		=> esc_attr( 'service' ),
				'priority'		=> 10,
			),
			// Service Category
			array(
				'type'			=> 'text',
				'settings'		=> 'service-cat-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title', 'itinc' ) , $service_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Title', 'itinc' ),
				'default'		=> esc_attr__( 'Service Categories', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'service-cat-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title (Singular)', 'itinc' ) , $service_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Service Category', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'service-cat-slug',
				'label'			=> sprintf( esc_attr__( '%1$s URl Slug', 'itinc' ) , $service_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy URL slug.', 'itinc' ) . '<br />' . '<strong>' . esc_attr__( 'NOTE:', 'itinc' ) . '</strong> ' . sprintf( esc_attr__( 'After changing this, please go to %1$s section and save it once.', 'itinc' ), thsn_esc_kses('<a href="' . esc_url( get_admin_url().'options-permalink.php' ) . '" target="_blank"><strong>Settings > Permalinks</strong></a>') ) . '<br /><br />',
				'default'		=> esc_attr( 'service-category' ),
				'priority'		=> 10,
			),
		)
	),
	// Team Member Settings
	'team_options' => array(
		'section_settings' => array(
			'title'			=> sprintf( esc_attr__( '%1$s options', 'itinc' ) , $team_cpt_singular_title ) ,
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-single-team-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Single %1$s Options', 'itinc' ), $team_cpt_singular_title ) . '</h2> <span>' . sprintf( esc_attr__( 'Sidebar options for %1$s Section', 'itinc' ), $team_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'team-single-style',
				'label'		=> sprintf( esc_html__( '%1$s Single View Style', 'itinc' ), $team_cpt_singular_title ),
				'default'	=> '1',
				'choices'		=> $team_single_style_array,
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-team-group-view',
				'default'		=> '<div class="themesion-option-heading"><h2>' . sprintf( esc_html__( 'Element View Style for %1$s', 'itinc' ), $team_group_singular_title ) . '</h2> <span>' . sprintf( esc_attr__( 'Select view style for elements on %1$s', 'itinc' ) , $team_group_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'team-group-style',
				'label'			=> sprintf( esc_html__( 'Element View on %1$s', 'itinc' ), $team_group_singular_title ),
				'default'		=> '1',
				'choices'		=> thsn_element_template_list('team', true),
			),
			array(
				'type'			=> 'radio-image',
				'settings'		=> 'team-group-column',
				'label'			=> sprintf( esc_html__( '%1$s View Column', 'itinc' ), $team_group_singular_title ),
				'default'		=> '3',
				'choices'		=> $column_list,
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'team-group-count',
				'label'			=> sprintf( esc_attr__( 'How many %1$s you like to show on single %2$s page', 'itinc' ), $team_cpt_singular_title, $team_group_singular_title ),
				'default'		=> 9,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 50,
					'step'			=> 1,
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'custom-team-member-sidebar-settings',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Sidebar Options', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Sidebar options for %1$s Section', 'itinc' ) , $team_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-team-member',
				'label'		=> sprintf( esc_html__( '%1$s Sidebar', 'itinc' ), $team_cpt_singular_title ),
				'default'	=> 'no',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-team-group',
				'label'		=> sprintf( esc_html__( '%1$s Sidebar', 'itinc' ), $team_group_singular_title ),
				'default'	=> 'no',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
			// Heading Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'team_advanced_heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Advanced Options', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Advanced Options for %1$s Section', 'itinc' ) , $team_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'team-cpt-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title', 'itinc' ) , $team_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Title', 'itinc' ),
				'default'		=> esc_attr__( 'Team Members', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'team-cpt-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title (Singular)', 'itinc' ) , $team_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Team Member', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'team-cpt-slug',
				'label'			=> sprintf( esc_attr__( '%1$s Section URl Slug', 'itinc' ) , $team_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT URL slug.', 'itinc' ) . '<br />' . '<strong>' . esc_attr__( 'NOTE:', 'itinc' ) . '</strong> ' . sprintf( esc_attr__( 'After changing this, please go to %1$s section and save it once.', 'itinc' ), thsn_esc_kses('<a href="' . esc_url( get_admin_url().'options-permalink.php' ) . '" target="_blank"><strong>Settings > Permalinks</strong></a>') ) . '<br /><br />',
				'default'		=> esc_attr( 'team' ),
				'priority'		=> 10,
			),
			// Team Member group
			array(
				'type'			=> 'text',
				'settings'		=> 'team-group-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title', 'itinc' ) , $team_group_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Title', 'itinc' ),
				'default'		=> esc_attr__( 'Team Groups', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'team-group-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title (Singular)', 'itinc' ) , $team_group_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Team Group', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'team-group-slug',
				'label'			=> sprintf( esc_attr__( '%1$s URl Slug', 'itinc' ) , $team_group_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy URL slug.', 'itinc' ) . '<br />' . '<strong>' . esc_attr__( 'NOTE:', 'itinc' ) . '</strong> ' . sprintf( esc_attr__( 'After changing this, please go to %1$s section and save it once.', 'itinc' ), thsn_esc_kses('<a href="' . esc_url( get_admin_url().'options-permalink.php' ) . '" target="_blank"><strong>Settings > Permalinks</strong></a>') ) . '<br /><br />',
				'default'		=> esc_attr( 'team-group' ),
				'priority'		=> 10,
			),
		)
	),
	// Testimonial Settings
	'testimonial_options' => array(
		'section_settings' => array(
			'title'			=> sprintf( esc_attr__( '%1$s options', 'itinc' ) , $testimonial_cpt_singular_title ) ,
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			// Heading Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'testimonial_advanced_heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Advanced Options', 'itinc' ) . '</h2> <span>' . sprintf( esc_attr__( 'Advanced Options for %1$s Section', 'itinc' ) , $testimonial_cpt_singular_title ) . '</span></div>',
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'testimonial-cpt-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title', 'itinc' ) , $testimonial_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Title', 'itinc' ),
				'default'		=> esc_attr__( 'Testimonials', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'testimonial-cpt-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Section Title (Singular)', 'itinc' ) , $testimonial_cpt_singular_title ) ,
				'description'	=> esc_attr__( 'CPT Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Testimonial', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'testimonial-cat-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title', 'itinc' ) , $testimonial_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Title', 'itinc' ),
				'default'		=> esc_attr__( 'Testimonial Categories', 'itinc' ),
				'priority'		=> 10,
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'testimonial-cat-singular-title',
				'label'			=> sprintf( esc_attr__( '%1$s Title (Singular)', 'itinc' ) , $testimonial_cat_singular_title ) ,
				'description'	=> esc_attr__( 'Taxonomy Singular Title', 'itinc' ),
				'default'		=> esc_attr__( 'Testimonial Category', 'itinc' ),
				'priority'		=> 10,
			),
		)
	),
	// Search Settings
	'search_results_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Search Results options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			// Heading Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'search_results_heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Search Results Settings', 'itinc' ) . '</h2> <span>' . esc_attr__( 'Settings for Search Results page', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'number',
				'settings'		=> 'search-results-text-limit',
				'label'			=> esc_attr__( 'Search Results Content Text Limit', 'itinc' ),
				'description'	=> esc_attr__( 'Show limited text for content of the results page/post etc.', 'itinc' ),
				'default'		=> 25,
				'choices'		=> array(
					'min'			=> 1,
					'max'			=> 900,
					'step'			=> 1,
				),
				'active_callback' => array(
					array(
						'setting'	=> 'blog-classic-limit-switch',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'no-results-title',
				'label'			=> esc_attr__( 'Title for "No Search Results" page', 'itinc' ),
				'description'	=> esc_attr__( 'Title to show when there is no search results', 'itinc' ),
				'default'		=> esc_attr__( 'No Results Found', 'itinc' ),
			),
			array(
				'type'			=> 'textarea',
				'settings'		=> 'no-results-text',
				'label'			=> esc_attr__( 'Text for "No Search Results" page', 'itinc' ),
				'description'	=> esc_attr__( 'Text to show when there is no search results', 'itinc' ),
				'default'		=> esc_attr__('Sorry, but nothing matched your search terms. Please try again with some different keywords.','itinc'),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'search-sidebar-options',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Sidebar Settings', 'itinc' ) . '</h2> <span>' . esc_html__( 'Select sidebar position for search results page.', 'itinc' ) . '</span></div>',
			),
			array(
				'type'		=> 'radio-image',
				'settings'	=> 'sidebar-search',
				'label'		=> esc_html__( 'Search Results Sidebar', 'itinc' ),
				'default'	=> 'no',
				'choices'		=> array(
					'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
					'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
					'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
				),
			),
		)
	),
	// Error 404 Settings
	'error_404_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'Error 404 options', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			// Heading Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'error_404_heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Error 404 Settings', 'itinc' ) . '</h2> <span>' . esc_attr__( 'Settings for error 404 page', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'text',
				'settings'		=> 'error-404-heading',
				'label'			=> esc_attr__( 'Error 404 Heading', 'itinc' ),
				'description'	=> esc_attr__( 'This is heading for 404 page', 'itinc' ),
				'default'		=> esc_attr__( '404', 'itinc' ),
			),
			array(
				'type'			=> 'textarea',
				'settings'		=> 'error-404-text',
				'label'			=> esc_attr__( 'Error 404 Text', 'itinc' ),
				'description'	=> esc_attr__( 'This is text for 404 page', 'itinc' ),
				'default'		=> esc_attr__( 'OOPS! THE PAGE YOU WERE LOOKING FOR, COULDN\'T BE FOUND.', 'itinc' ),
			),
			array(
				'type'			=> 'switch',
				'settings'		=> 'error-404-show-search',
				'label'			=> esc_attr__( 'Show search form on 404 page', 'itinc' ),
				'default'		=> '1',
				'priority'		=> 10,
				'choices'		=> array(
					'on'			=> esc_attr__( 'Yes', 'itinc' ),
					'off'			=> esc_attr__( 'No', 'itinc' ),
				),
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'error_404_text_custom',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Error 404 Text Color', 'itinc' ) . '</h2> <span>' . esc_attr__( 'Settings for text color for 404 error page', 'itinc' ) . '</span></div>',
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'e404-text-color',
				'label'				=> esc_attr__( 'Select 404 page text color', 'itinc' ),
				'default'			=> 'white',
				'choices'			=> $pre_text_color_2_list,
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'error_404_bg_custom',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Error 404 Background Option', 'itinc' ) . '</h2> <span>' . esc_attr__( 'Settings for background color/image for 404 error page', 'itinc' ) . '</span></div>',
			),
			array(
				'type'				=> 'radio-image',
				'settings'			=> 'e404-bgcolor',
				'label'				=> esc_html__( 'Select 404 page background color', 'itinc' ),
				'default'			=> 'custom',
				'choices'			=> $pre_color_list,
			),
			array(
				'type'			=> 'background',
				'settings'		=> 'e404-background',
				'label'			=> esc_attr__( 'Background', 'itinc' ),
				'description'	=> esc_attr__( 'Background Settings', 'itinc' ),
				'default'		=> array(
					'background-image'      => get_template_directory_uri() . '/images/404-bg.jpg'  ,
					'background-color'      => 'rgba(0,0,0,0.5)',
					'background-repeat'     => 'no-repeat',
					'background-position'   => 'center top',
					'background-size'       => 'cover',
					'background-attachment' => 'scroll',
				),
				'thsn-output'	=> '.error404 .site-content-wrap, .error404 .thsn-bg-color-custom > .site-content-wrap:before',
			),
		)
	),
	// Custom CSS/JS Options
	'custom_code_options' => array(
		'section_settings' => array(
			'title'			=> esc_attr__( 'CSS/JS Code', 'itinc' ),
			'panel'			=> 'itinc_base_options',
			'priority'		=> 160,
		),
		'section_fields' => array(
			// Heading Options
			array(
				'type'			=> 'custom',
				'settings'		=> 'tracking_js_heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Tracking Code', 'itinc' ) . '</h2> <span>' . esc_attr__( 'Code for Google Tracking or other ', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'textarea',
				'settings'		=> 'tracking-code',
				'label'			=> esc_attr__( 'Tracking Code', 'itinc' ),
				'description'	=> esc_attr__( 'This code will be added to HEAD element on your all pages.', 'itinc' ),
				'default'		=> '',
			),
			array(
				'type'			=> 'custom',
				'settings'		=> 'cust_css_heading',
				'default'		=> '<div class="themesion-option-heading"><h2>' . esc_html__( 'Custom CSS Code', 'itinc' ) . '</h2> <span>' . esc_attr__( 'Custom CSS Code', 'itinc' ) . '</span></div>',
			),
			array(
				'type'			=> 'textarea',
				'settings'		=> 'css-code',
				'label'			=> esc_attr__( 'Custom CSS Code', 'itinc' ),
				'description'	=> esc_attr__( 'Add your custom CSS code here.', 'itinc' ),
				'default'		=> '',
			),
			array(
				'type'			=> 'textarea',
				'settings'		=> 'js-code',
				'label'			=> esc_attr__( 'Custom JS Code', 'itinc' ),
				'description'	=> esc_attr__( 'Add your custom JS code here.', 'itinc' ),
				'default'		=> '',
			),
		)
	),
);
// adding WooCommerce options
if( function_exists('is_woocommerce') ){
	$kirki_options_array2 = array();
	foreach( $kirki_options_array as $sections=>$settings ){
		$kirki_options_array2[$sections] = $settings;
		if( $sections == 'portfolio_options' ){
			$kirki_options_array2['woocommerce_options'] = array(
				'section_settings' => array(
					'title'			=> esc_attr__( 'WooCommerce Options', 'itinc' ),
					'panel'			=> 'itinc_base_options',
					'priority'		=> 160,
				),
				'section_fields' => array(
					// Heading Options
					array(
						'type'		=> 'radio-image',
						'settings'	=> 'sidebar-wc-shop',
						'label'		=> esc_html__( 'WooCommerce Shop Sidebar', 'itinc' ),
						'default'	=> 'right',
						'choices'		=> array(
							'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
							'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
							'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
						),
					),
					array(
						'type'		=> 'radio-image',
						'settings'	=> 'sidebar-wc-single',
						'label'		=> esc_html__( 'WooCommerce Single Product Sidebar', 'itinc' ),
						'default'	=> 'no',
						'choices'		=> array(
							'left'		=> get_template_directory_uri() . '/includes/images/sidebar-left.png',
							'right'		=> get_template_directory_uri() . '/includes/images/sidebar-right.png',
							'no'		=> get_template_directory_uri() . '/includes/images/sidebar-no.png',
						),
					),
					array(
						'type'		=> 'text',
						'settings'	=> 'wc-title',
						'label'		=> esc_attr__( 'WooCommerce Shop Page Title', 'itinc' ),
						'description'	=> esc_attr__( 'This will appear in Titlebar on Shop page.', 'itinc' ),
						'default'	=> esc_attr('Shop'),
					),
					array(
						'type'			=> 'select',
						'settings'		=> 'wc-related-count',
						'label'			=> esc_attr__( 'How many related products will be shown?', 'itinc' ),
						'description'	=> esc_attr__( 'How many related products will be shown on single product page?', 'itinc' ),
						'default'		=> '4',
						'choices'		=> array(
							'1'		=> esc_attr__( '1 product', 'itinc' ),
							'2'		=> esc_attr__( '2 products', 'itinc' ),
							'3'		=> esc_attr__( '3 products', 'itinc' ),
							'4'		=> esc_attr__( '4 products', 'itinc' ),
						),
					),
					array(
						'type'			=> 'switch',
						'settings'		=> 'wc-show-cart-icon',
						'label'			=> esc_attr__( 'Show Cart Icon in Header?', 'itinc' ),
						'description'	=> esc_attr__( 'Show or hide cart icon in header area. The icon will appear only if WooCommerce plugin is active.', 'itinc' ),
						'default'		=> '1',
						'choices'		=> array(
							'on'		=> esc_attr__( 'Yes', 'itinc' ),
							'off'		=> esc_attr__( 'No', 'itinc' ),
						),
					),
					array(
						'type'			=> 'switch',
						'settings'		=> 'wc-show-cart-amount',
						'label'			=> esc_attr__( 'Show Amount with Cart Icon in Header?', 'itinc' ),
						'description'	=> esc_attr__( 'Show or hide cart amount with cart icon in header area. The icon will appear only if WooCommerce plugin is active.', 'itinc' ),
						'default'		=> '1',
						'choices'		=> array(
							'on'		=> esc_attr__( 'Yes', 'itinc' ),
							'off'		=> esc_attr__( 'No', 'itinc' ),
						),
						'active_callback' => array( array(
							'setting'		=> 'wc-show-cart-icon',
							'operator'		=> '==',
							'value'			=> '1',
						) ),
					),
				)
			);
		}
	} // foreach
	$kirki_options_array = $kirki_options_array2;
}
