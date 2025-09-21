<?php
namespace Elementor; // Custom widgets must be defined in the Elementor namespace
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly (security measure)

/**
 * Widget Name: Section Heading 
 */
class THSN_IconHeading extends Widget_Base{

 	// The get_name() method is a simple one, you just need to return a widget name that will be used in the code.
	public function get_name() {
		return 'thsn_icon_heading';
	}

	// The get_title() method, which again, is a very simple one, you need to return the widget title that will be displayed as the widget label.
	public function get_title() {
		return esc_attr__( 'ITinc Icon Heading Element', 'itinc' );
	}

	// The get_icon() method, is an optional but recommended method, it lets you set the widget icon. you can use any of the eicon or font-awesome icons, simply return the class name as a string.
	public function get_icon() {
		return 'fas fa-star';
	}

	// The get_categories method, lets you set the category of the widget, return the category name as a string.
	public function get_categories() {
		return [ 'itinc_category' ];
	}

	protected function register_controls() {

		//Content Service box
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_attr__( 'Content', 'itinc' ),
			]
		);
        $this->add_control(
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
        $this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'itinc' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				/*
				'default' => [
					'value' => 'fas fa-stroopwafel',
					'library' => 'solid',
				],
				*/
                'condition' => [
                    'icon_type' => 'icon',
                ]
            ]

		);
		$this->add_control(
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

        $this->add_control(
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
        $this->add_control(
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
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_attr__( 'Type your description here', 'itinc' ),
			]
		);

		$this->add_control(
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
		$this->add_control(
			'btn_link',
			[
				'label' => esc_attr__( 'Button Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
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

		// Style
		$this->start_controls_section(
			'select_style_section',
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

		//Style
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_attr__( 'Typo Settings', 'itinc' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			//Title
			$this->add_control(
				'heading_title',
				[
					'label' => esc_attr__( 'Title', 'itinc' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'title_color',
				[
					'label' => esc_attr__( 'Color', 'itinc' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .thsn-element-heading' => 'color: {{VALUE}};',
						'{{WRAPPER}} .thsn-element-heading > a' => 'color: {{VALUE}};',
					]
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .thsn-element-heading',
				]
			);
			$this->add_responsive_control(
				'title_bottom_space',
				[
					'label' => esc_attr__( 'Spacing', 'itinc' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .thsn-element-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			//Subtitle
			$this->add_control(
				'heading_stitle',
				[
					'label' => esc_attr__( 'Subtitle', 'itinc' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'stitle_color',
				[
					'label' => esc_attr__( 'Color', 'itinc' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .thsn-element-subheading' => 'color: {{VALUE}};',
						'{{WRAPPER}} .thsn-element-subheading > a' => 'color: {{VALUE}};',
					]
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'stitle_typography',
					'selector' => '{{WRAPPER}} .thsn-element-subheading',
				]
			);
			$this->add_responsive_control(
				'stitle_bottom_space',
				[
					'label' => esc_attr__( 'Spacing', 'itinc' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .thsn-element-subheading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			// Description
			$this->add_control(
				'heading_desc',
				[
					'label' => esc_attr__( 'Description', 'itinc' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'desc_color',
				[
					'label' => esc_attr__( 'Color', 'itinc' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .thsn-heading-desc' => 'color: {{VALUE}};',
					]
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'desc_typography',
					'selector' => '{{WRAPPER}} .thsn-heading-desc',
				]
			);
			$this->add_responsive_control(
				'desc_bottom_space',
				[
					'label' => esc_attr__( 'Spacing', 'itinc' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .thsn-heading-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		extract($settings);

		$icon_html = $title_html = $subtitle_html = $desc_html = $nav_html = $button_html = $box_number_html = '';

		if( !empty($box_number) ){
			$box_number_html = '<div class="thsn-ihbox-box-number">'.esc_attr($box_number).'</div>';
		}

		if( file_exists( locate_template( '/theme-parts/icon-heading/icon-heading-style-'.esc_attr($style).'.php', false, false ) ) ){
			$icon_type_class = '';

			if( !empty($settings['icon_type']) ){

				if( $settings['icon_type']=='text' ){
					$icon_html = '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper thsn-ihbox-icon-type-text">' . $settings['icon_text'] . '</div></div>';
					$icon_type_class = 'text';

				} else if( $settings['icon_type']=='image' ){
					$icon_alt	= (!empty($settings['title'])) ? trim($settings['title']) : esc_attr__('Icon', 'itinc') ;
					$icon_image = '<img src="'.esc_url($settings['icon_image']['url']).'" alt="'.esc_attr($icon_alt).'" />';
					$icon_html	= '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper thsn-ihbox-icon-type-image">' . $icon_image . '</div></div>';
					$icon_type_class = 'image';
				} else if( $settings['icon_type']=='none' ){
					$icon_html = '';
					$icon_type_class = 'none';
				} else {
					if($icon['library']=='svg'){
						ob_start();
						Icons_Manager::render_icon( $icon , [ 'aria-hidden' => 'true' ] );
						$icon_html = ob_get_contents();
						ob_end_clean();
						$icon_html	   = '<div class="thsn-ihbox-svg"><div class="thsn-ihbox-svg-wrapper">' . $icon_html . '</div></div>';
						$icon_type_class = 'icon';
					} else {
						if(!empty($settings['icon'])){
							ob_start();
							Icons_Manager::render_icon( $icon , [ 'aria-hidden' => 'true' ] );
							$icon_html_code = ob_get_contents();
							ob_end_clean();
							
							$icon_html	   = '<div class="thsn-ihbox-icon"><div class="thsn-ihbox-icon-wrapper">' . thsn_esc_kses( $icon_html_code ) . '</div></div>';
							$icon_type_class = 'icon';
							wp_enqueue_style( 'elementor-icons-'.$settings['icon']['library']);
						}
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
				$subtitle_html	= '<'. thsn_esc_kses($subtitle_tag) . ' class="thsn-element-heading">
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

	protected function content_template() {}
}
// After the Schedule class is defined, I must register the new widget class with Elementor:
Plugin::instance()->widgets_manager->register( new THSN_IconHeading() );