<?php
namespace Elementor; // Custom widgets must be defined in the Elementor namespace
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly (security measure)

/**
 * Widget Name: Projects Carousel
 */
class THSN_MultipleIconHeading extends Widget_Base{

 	// The get_name() method is a simple one, you just need to return a widget name that will be used in the code.
	public function get_name() {
		return 'thsn_multiple_icon_heading';
	}

	// The get_title() method, which again, is a very simple one, you need to return the widget title that will be displayed as the widget label.
	public function get_title() {
		return esc_attr__( 'ITinc Multiple Icon Heading Element', 'itinc' );
	}

	// The get_icon() method, is an optional but recommended method, it lets you set the widget icon. you can use any of the eicon or font-awesome icons, simply return the class name as a string.
	public function get_icon() {
		return 'fas fa-grip-horizontal';
	}

	// The get_categories method, lets you set the category of the widget, return the category name as a string.
	public function get_categories() {
		return [ 'itinc_category' ];
	}

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-carousel' );
		wp_enqueue_style( 'owl-carousel-theme' );
	}

	protected function register_controls() {

		// Heading and Subheading
		$this->start_controls_section(
			'heading_section',
			[
				'label' => esc_attr__( 'Heading and Subheading', 'itinc' ),
			]
		);
		$this->add_control(
			'title',
			[
				'label' => esc_attr__( 'Title', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( 'Welcome to our site', 'itinc' ),
				'placeholder' => esc_attr__( 'Enter your title', 'itinc' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'title_link',
			[
				'label' => esc_attr__( 'Title Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$this->add_control(
			'subtitle',
			[
				'label' => esc_attr__( 'Subtitle', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( 'This is Subtitle', 'itinc' ),
				'placeholder' => esc_attr__( 'Enter your subtitle', 'itinc' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subtitle_link',
			[
				'label' => esc_attr__( 'Subtitle Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$this->add_control(
			'desc',
			[
				'label' => esc_attr__( 'Description', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_attr__( 'Type your description here', 'itinc' ),
			]
		);
		$this->add_control(
			'reverse_title',
			[
				'label' => esc_attr__( 'Reverse Title', 'itinc' ),
				'description' => esc_attr__( 'Show sub-title before title', 'itinc' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_attr__( 'Yes', 'itinc' ),
				'label_off' => esc_attr__( 'No', 'itinc' ),
				'return_value' => 'yes',
				'default'		=> '',
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_attr__( 'Alignment', 'itinc' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_attr__( 'Left', 'itinc' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_attr__( 'Center', 'itinc' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_attr__( 'Right', 'itinc' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'thsn-align-',
				'selectors' => [
					'{{WRAPPER}} .thsn-ele-header-area' => 'text-align: {{VALUE}};',
				],
				'dynamic' => [
					'active' => true,
				],
				'default' => 'left',
			]
		);

		// Tags
		$this->add_control(
			'tag_options',
			[
				'label'			=> esc_attr__( 'Tags for SEO', 'itinc' ),
				'type'			=> Controls_Manager::HEADING,
				'separator'		=> 'before',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label' => esc_attr__( 'Title Tag', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1'	=> esc_attr( 'H1' ),
					'h2'	=> esc_attr( 'H2' ),
					'h3'	=> esc_attr( 'H3' ),
					'h4'	=> esc_attr( 'H4' ),
					'h5'	=> esc_attr( 'H5' ),
					'h6'	=> esc_attr( 'H6' ),
					'div'	=> esc_attr( 'DIV' ),
				],
				'default' => esc_attr( 'h2' ),
			]
		);
		$this->add_control(
			'subtitle_tag',
			[
				'label' => esc_attr__( 'SubTitle Tag', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1'	=> esc_attr( 'H1' ),
					'h2'	=> esc_attr( 'H2' ),
					'h3'	=> esc_attr( 'H3' ),
					'h4'	=> esc_attr( 'H4' ),
					'h5'	=> esc_attr( 'H5' ),
					'h6'	=> esc_attr( 'H6' ),
					'div'	=> esc_attr( 'DIV' ),
				],
				'default' => esc_attr( 'h4' ),
			]
		);
		$this->end_controls_section();

		//Content
		$this->start_controls_section(
			'data_section',
			[
				'label' => esc_attr__( 'Data Options', 'itinc' ),
			]
        );

		$repeater = new Repeater();

		$repeater->add_control(
			'icon_type',
			[
				'label' => esc_attr__( 'Icon Type', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon'	=> esc_attr__( 'Icon', 'itinc' ),
					'image'	=> esc_attr__( 'Image', 'itinc' ),
					'text'	=> esc_attr__( 'Text', 'itinc' ),
				],
				'default' => esc_attr( 'icon' ),
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'itinc' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => [
                    'icon_type' => 'icon',
                ]
            ]
		);

		$repeater->add_control(
			'box_number',
			[
				'label' => esc_attr__( 'Box Number', 'itinc' ),
				'description' => esc_attr__( '(Optional) Add box number like "01". This will be shown as steps.', 'itinc' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => esc_attr__( 'Enter number', 'itinc' ),
                'label_block' => true,
				'condition' => [
                    'icon_type' => 'icon',
                ]
        	]
		);

		$repeater->add_control(
			'icon_image',
			[
				'label' => __( 'Select Image for Icon', 'itinc' ),
				'description' => __( 'This image will appear at icon position. Recommended size is 300x300 px transparent PNG file.', 'itinc' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image',
                ]
			]
		);
        $repeater->add_control(
			'icon_text',
			[
				'label' => esc_attr__( 'Text for Icon', 'itinc' ),
				'description' => esc_attr__( 'The text will appear at icon position. This should be small text like "01" or "PX"', 'itinc' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( '01', 'itinc' ),
				'placeholder' => esc_attr__( 'Enter text here', 'itinc' ),
                'label_block' => true,
                'condition' => [
                    'icon_type' => 'text',
                ]
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_attr__( 'Title', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( 'Welcome to our site', 'itinc' ),
				'placeholder' => esc_attr__( 'Enter your title', 'itinc' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'title_link',
			[
				'label' => esc_attr__( 'Title Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'subtitle',
			[
				'label' => esc_attr__( 'Subtitle', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( 'This is Subtitle', 'itinc' ),
				'placeholder' => esc_attr__( 'Enter your subtitle', 'itinc' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'subtitle_link',
			[
				'label' => esc_attr__( 'Subtitle Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'desc',
			[
				'label' => esc_attr__( 'Description', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_attr__( 'Type your description here', 'itinc' ),
			]
		);
		$repeater->add_control(
			'btn_title',
			[
				'label' => esc_attr__( 'Button Title', 'itinc' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( 'Read More', 'itinc' ),
				'separator'		=> 'before',
				'placeholder' => esc_attr__( 'Enter button title here', 'itinc' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'btn_link',
			[
				'label' => esc_attr__( 'Button Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'tag_options',
			[
				'label'			=> esc_attr__( 'Tags for SEO', 'itinc' ),
				'type'			=> Controls_Manager::HEADING,
				'separator'		=> 'before',
			]
		);
		$repeater->add_control(
			'title_tag',
			[
				'label' => esc_attr__( 'Title Tag', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1'	=> esc_attr( 'H1' ),
					'h2'	=> esc_attr( 'H2' ),
					'h3'	=> esc_attr( 'H3' ),
					'h4'	=> esc_attr( 'H4' ),
					'h5'	=> esc_attr( 'H5' ),
					'h6'	=> esc_attr( 'H6' ),
					'div'	=> esc_attr( 'DIV' ),
				],
				'default' => esc_attr( 'h2' ),
			]
		);
		$repeater->add_control(
			'subtitle_tag',
			[
				'label' => esc_attr__( 'SubTitle Tag', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1'	=> esc_attr( 'H1' ),
					'h2'	=> esc_attr( 'H2' ),
					'h3'	=> esc_attr( 'H3' ),
					'h4'	=> esc_attr( 'H4' ),
					'h5'	=> esc_attr( 'H5' ),
					'h6'	=> esc_attr( 'H6' ),
					'div'	=> esc_attr( 'DIV' ),
				],
				'default' => esc_attr( 'h4' ),
			]
		);
		$this->add_control(
			'ihboxes',
			[
				'label'			=> esc_attr__( 'Tabs Items', 'itinc' ),
				'type'			=> Controls_Manager::REPEATER,
				'fields'		=> $repeater->get_controls(),
				'title_field'	=> '{{{ title }}}',
				'default'		=> array (
					array (
						'_id'			=> rand(100,999) . rand(100,999) . 'd' ,
						'icon'			=> array (
							'value'			=> 'far fa-check-circle',
							'library'		=> 'fa-regular',
					  	),
						'title'			=> esc_attr__( 'Welcome to our site', 'itinc'),
						'subtitle'		=> esc_attr__( 'This is subtitle', 'itinc'),
						'btn_title'		=> esc_attr__( 'Read More', 'itinc'),
						'box_number'	=> '',
						'title_link'	=> array (
							'url'				=> '',
							'is_external'		=> '',
							'nofollow'			=> '',
							'custom_attributes'	=> '',
						),
						'subtitle_link' => array (
							'url'				=> '',
							'is_external'		=> '',
							'nofollow'			=> '',
							'custom_attributes'	=> '',
						),
						'desc'			=> '',
						'btn_link'		=> array (
							'url'				=> '',
							'is_external'		=> '',
							'nofollow'			=> '',
							'custom_attributes'	=> '',
						),
						'title_tag'		=> 'h2',
						'subtitle_tag'	=> 'h4',
					),
					array (
					  '_id'				=> rand(100,999) . rand(100,999) . 'd' ,
					  'icon'			=> array (
						'value'				=> 'far fa-calendar-minus',
						'library'			=> 'fa-regular',
					  ),
					  'title'			=> esc_attr__( 'Welcome to our site', 'itinc'),
					  'subtitle'		=> esc_attr__( 'This is subtitle', 'itinc'),
					  'btn_title'		=> esc_attr__( 'Read More', 'itinc'),
					  'box_number'	=> '',
					  'title_link'	=> array (
						  'url'				=> '',
						  'is_external'		=> '',
						  'nofollow'			=> '',
						  'custom_attributes'	=> '',
					  ),
					  'subtitle_link' => array (
						  'url'				=> '',
						  'is_external'		=> '',
						  'nofollow'			=> '',
						  'custom_attributes'	=> '',
					  ),
					  'desc'			=> '',
					  'btn_link'		=> array (
						  'url'				=> '',
						  'is_external'		=> '',
						  'nofollow'			=> '',
						  'custom_attributes'	=> '',
					  ),
					  'title_tag'		=> 'h2',
					  'subtitle_tag'	=> 'h4',
					),
					array (
					  '_id'				=> rand(100,999) . rand(100,999) . 'd' ,
					  'icon'			=> array (
						'value'				=> 'far fa-calendar-check',
						'library'			=> 'fa-regular',
					  ),
					  'title'			=> esc_attr__( 'Welcome to our site', 'itinc'),
						'subtitle'		=> esc_attr__( 'This is subtitle', 'itinc'),
						'btn_title'		=> esc_attr__( 'Read More', 'itinc'),
						'box_number'	=> '',
						'title_link'	=> array (
							'url'				=> '',
							'is_external'		=> '',
							'nofollow'			=> '',
							'custom_attributes'	=> '',
						),
						'subtitle_link' => array (
							'url'				=> '',
							'is_external'		=> '',
							'nofollow'			=> '',
							'custom_attributes'	=> '',
						),
						'desc'			=> '',
						'btn_link'		=> array (
							'url'				=> '',
							'is_external'		=> '',
							'nofollow'			=> '',
							'custom_attributes'	=> '',
						),
						'title_tag'		=> 'h2',
						'subtitle_tag'	=> 'h4',
					),
				),
			]
		);

        $this->end_controls_section();

		// Style
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_attr__( 'Select Style', 'itinc' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'style',
			[
				'label'			=> esc_attr__( 'Select Icon Heading View Style', 'itinc' ),
				'description'	=> esc_attr__( 'Slect Icon Heading View style.', 'itinc' ),
				'type'			=> 'thsn_imgselect',
				'label_block'	=> true,
				'thumb_width'	=> '110px',
				'default'		=> '1',
				'prefix'		=> 'thsn-ihbox thsn-ihbox-style-',
				'options'		=> thsn_element_template_list( 'icon-heading', true ),
			]
		);
		$this->end_controls_section();

		// Appearance
		$this->start_controls_section(
			'appearance_section',
			[
				'label' => esc_attr__( 'Column and Carousel Options', 'itinc' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'view-type',
			[
				'label'			=> esc_attr__( 'How you like to view each Post box?', 'itinc' ),
				'description'	=> esc_attr__( 'Show as carousel view or simple row-column view.', 'itinc' ),
				'type'			=> 'thsn_imgselect',
				'label_block'	=> true,
				'thumb_width'	=> '110px',
				'default'		=> 'row-column',
				'options'		=> [
					'row-column'	=> esc_url( get_template_directory_uri() . '/includes/images/row-column.png' ),
					'carousel'		=> esc_url( get_template_directory_uri() . '/includes/images/carousel.png' ),
				]
			]
		);

		// Row Column: Heading
		$this->add_control(
			'row_col_options',
			[
				'label' => esc_attr__( 'Row-Column Options', 'itinc' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'view-type' => 'row-column',
				]
			]
		);

		// Carousel: Heading
		$this->add_control(
			'carousel_options',
			[
				'label' => esc_attr__( 'Carousel Options', 'itinc' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'view-type' => 'carousel',
				]
			]
		);
		// Carousel : Loop
		$this->add_control(
			'carousel-loop',
			[
				'label'			=> esc_attr__( 'Carousel: Loop', 'itinc' ),
				'description'	=> esc_attr__( 'Infinity loop. Duplicate last and first items to get loop illusion.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Autoplay
		$this->add_control(
			'carousel-autoplay',
			[
				'label'			=> esc_attr__( 'Carousel: Autoplay', 'itinc' ),
				'description'	=> esc_attr__( 'Autoplay of carousel.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Center
		$this->add_control(
			'carousel-center',
			[
				'label'			=> esc_attr__( 'Carousel: Center', 'itinc' ),
				'description'	=> esc_attr__( 'Center item. Works well with even an odd number of items.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Nav
		$this->add_control(
			'carousel-nav',
			[
				'label'			=> esc_attr__( 'Carousel: Nav', 'itinc' ),
				'description'	=> esc_attr__( 'Show next/prev buttons.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Dots
		$this->add_control(
			'carousel-dots',
			[
				'label'			=> esc_attr__( 'Carousel: Dots', 'itinc' ),
				'description'	=> esc_attr__( 'Show dots navigation.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : autoplaySpeed
		$this->add_control(
			'carousel-autoplayspeed',
			[
				'label'			=> esc_attr__( 'Carousel: autoplaySpeed', 'itinc' ),
				'description'	=> esc_attr__( 'autoplay speed.', 'itinc' ),
				'type'			=> Controls_Manager::TEXT,
				'default'		=> '1000',
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);

		// Columns
		$this->add_control(
			'columns',
			[
				'label'			=> esc_attr__( 'View in Column', 'itinc' ),
				'description'	=> esc_attr__( 'Select how many column to show.', 'itinc' ),
				'type'			=> 'thsn_imgselect',
				'label_block'	=> true,
				'thumb_width'	=> '110px',
				'default'		=> '3',
				'options'		=> [
					'1'				=> esc_url( get_template_directory_uri() . '/includes/images/column-1.png' ),
					'2'				=> esc_url( get_template_directory_uri() . '/includes/images/column-2.png' ),
					'3'				=> esc_url( get_template_directory_uri() . '/includes/images/column-3.png' ),
					'4'				=> esc_url( get_template_directory_uri() . '/includes/images/column-4.png' ),
					'5'				=> esc_url( get_template_directory_uri() . '/includes/images/column-5.png' ),
					'6'				=> esc_url( get_template_directory_uri() . '/includes/images/column-6.png' ),
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings	= $this->get_settings_for_display();
		extract($settings);
		$return = '';

		// Starting container
		$start_div = themesion_element_container( array(
			'position'	=> 'start',
			'cpt'		=> 'miconheading',
			'data'		=> $settings
		) );

		echo thsn_esc_kses($start_div);
		?>

		<div class="thsn-ele-header-area">
			<?php thsn_heading_subheading($settings, true); ?>
		</div>

		<div class="thsn-element-posts-wrapper row multi-columns-row">

		<?php

		//var_export($ihboxes);

        foreach( $ihboxes as $box ){

			$box['style'] = $style;

			// Template
			if( file_exists( locate_template( '/theme-parts/icon-heading/icon-heading-style-'.esc_attr($style).'.php', false, false ) ) ){

				$return .= thsn_element_block_container( array(
					'position'	=> 'start',
					'column'	=> $columns,
					'cpt'		=> 'miconheading',
					'taxonomy'	=> 'category',
					'style'		=> $style,
				) );
				extract($box);
			
				$icon_html = $title_html = $subtitle_html = $desc_html = $nav_html = $button_html = $box_number_html = '';
			
				if( !empty($box_number) ){
					$box_number_html = '<div class="thsn-ihbox-box-number">'.esc_attr($box_number).'</div>';
				}
			
				if( file_exists( locate_template( '/theme-parts/icon-heading/icon-heading-style-'.esc_attr($style).'.php', false, false ) ) ){
					
					// icon
					if( !empty($box['icon_type']) ){
			
						if( $box['icon_type']=='text' ){
							$icon_html = '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper thsn-ihbox-icon-type-text">' . $box['icon_text'] . '</div></div>';
			
						} else if( $box['icon_type']=='image' ){
							$icon_alt	= (!empty($box['title'])) ? trim($box['title']) : esc_attr__('Icon', 'itinc') ;
							$icon_image = '<img src="'.esc_url($box['icon_image']['url']).'" alt="'.esc_attr($icon_alt).'" />';
							$icon_html	= '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper thsn-ihbox-icon-type-image">' . $icon_image . '</div></div>';
						} else if( $box['icon_type']=='none' ){
							$icon_html = '';
						} else {
							if($icon['library']=='svg'){
								ob_start();
								Icons_Manager::render_icon( $icon , [ 'aria-hidden' => 'true' ] );
								$icon_html = ob_get_contents();
								ob_end_clean();
								$icon_html	   = '<div class="thsn-ihbox-svg"><div class="thsn-ihbox-svg-wrapper">' . $icon_html . '</div></div>';
								$icon_type_class = 'icon';
							} else {
								if(!empty($box['icon'])){
									ob_start();
									Icons_Manager::render_icon( $icon , [ 'aria-hidden' => 'true' ] );
									$icon_html_code = ob_get_contents();
									ob_end_clean();
				
									$icon_html	   = '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper">' . thsn_esc_kses( $icon_html_code ) . '</div></div>';
									$icon_type_class = 'icon';
									wp_enqueue_style( 'elementor-icons-'.$box['icon']['library']);
								}
							}
			
						}
					}
			
					// Title
					if( !empty($box['title']) ) {
						$title_tag	= ( !empty($box['title_tag']) ) ? $box['title_tag'] : 'h2' ;
						$title_html	= '<'. thsn_esc_kses($title_tag) . ' class="thsn-element-title">
							'.thsn_link_render($box['title_link'], 'start' ).'
								'.thsn_esc_kses($box['title']).'
							'.thsn_link_render($box['title_link'], 'end' ).'
							</'. thsn_esc_kses($title_tag) . '>
						';
					}
			
					// SubTitle
					if( !empty($box['subtitle']) ) {
						$subtitle_tag	= ( !empty($box['subtitle_tag']) ) ? $box['subtitle_tag'] : 'h4' ;
						$subtitle_html	= '<'. thsn_esc_kses($subtitle_tag) . ' class="thsn-element-subtitle">
							'.thsn_link_render($box['subtitle_link'], 'start' ).'
								'.thsn_esc_kses($box['subtitle']).'
							'.thsn_link_render($box['subtitle_link'], 'end' ).'
							</'. thsn_esc_kses($subtitle_tag) . '>
						';
					}
			
					// Description text
					if( !empty($box['desc']) ){
						$desc_html = '<div class="thsn-heading-desc">'.thsn_esc_kses($box['desc']).'</div>';
					}
			
					// Button
					if( !empty($box['btn_title']) && !empty($box['btn_link']['url']) ){
						$button_html = '<div class="thsn-ihbox-btn">' . thsn_link_render($box['btn_link'], 'start' ) . thsn_esc_kses($box['btn_title']) . thsn_link_render($box['btn_link'], 'end' ) . '</div>';
					}
			
					$return .='<div class="thsn-ihbox thsn-ihbox-style-'.esc_attr($style).'">';
					ob_start();
					include( locate_template( '/theme-parts/icon-heading/icon-heading-style-'.esc_attr($style).'.php', false, false ) );
					$return .= ob_get_contents();
					ob_end_clean();
			
					$return .='</div>';
			
				}

				$return .= thsn_element_block_container( array(
					'position'	=> 'end',
				) );

			}

		} // foreach

		echo thsn_esc_kses($return);
		?>

		</div>

		<?php

		// Ending wrapper of the whole arear
		$end_div = themesion_element_container( array(
			'position'	=> 'end',
			'cpt'		=> 'miconheading',
			'data'		=> $settings
		) );
		echo thsn_esc_kses($end_div);

	}

	protected function content_template() {}

	protected function select_category() {
		$category = get_terms( array( 'taxonomy' => 'thsn-portfolio-category', 'hide_empty' => false ) );
	  	$cat = array();
	  	foreach( $category as $item ) {
			$cat_count = get_category( $item );

	     	if( $item ) {
	        	$cat[$item->slug] = $item->name . ' ('.$cat_count->count.')';
	     	}
	  	}
	  	return $cat;
	}
}
// After the Schedule class is defined, I must register the new widget class with Elementor:
Plugin::instance()->widgets_manager->register( new THSN_MultipleIconHeading() );