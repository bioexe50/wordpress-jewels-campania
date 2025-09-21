<?php
/**
 *  Common Meta Boxes for all CPT
 */
if( !function_exists('thsn_set_metabox') ){
function thsn_set_metabox(){
	//  Check if ACF is enabled
	if( function_exists('acf_add_local_field_group') ){
		if (class_exists('RevSlider')) {
			$rev_slider_list_array = array();
			$slider			= new RevSlider();
			$allArrSliders	= $slider->get_sliders();
			if( is_array($allArrSliders) && count($allArrSliders)>0 ){
				foreach ($allArrSliders as $revSlider) {
					// Getting thumb of slider
					$params = $revSlider->get_overview_data();
					$first_slide_image_thumb = ( !empty($params['bg']['src']) ) ? $params['bg']['src'] : get_template_directory_uri() . '/includes/images/sr-no-thumb.png' ;
					$rev_slider_list_array[ $revSlider->getAlias() ] = '<div data-balloon="' . esc_attr( $revSlider->getTitle() ) . ' (' . esc_attr( $revSlider->getAlias() ) . ')" data-balloon-pos="down"><img class="thsn-revslider-thumb" src="'.esc_url($first_slide_image_thumb).'" /></div>';
				}
				$rev_slider_option_array = array(
					'key'				=> 'thsn-revolution-slider',
					'label'				=> esc_attr__('Select Revolution Slider', 'itinc'),
					'name'				=> 'thsn-revolution-slider',
					'type'				=> 'radio',
					'instructions'		=> esc_attr__('Select that appear in header area', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => array(
						array(
							array(
								'field'		=> 'thsn-slider-type',
								'operator'	=> '==',
								'value'		=> 'revolution-slider',
							),
						),
					),
					'wrapper'			=> array(
						'width'				=> '60',
						'class'				=> 'thsn-radio-image-selector',
						'id'				=> '',
					),
					'choices'			=> $rev_slider_list_array,
					'allow_null'		=> 0,
					'other_choice'		=> 0,
					'default_value'		=> '',
					'layout'			=> 'horizontal',
					'return_format'		=> 'value',
					'save_other_choice' => 0,
				);
			} else {
				$rev_slider_option_array = array(
					'key'				=> 'thsn-message-no-slider-in-revslider',
					'label'				=> esc_attr__('No Slider Found', 'itinc'),
					'name'				=> 'thsn-message-no-slider-in-revslider',
					'type'				=> 'message',
					'instructions'		=> '',
					'required'			=> 0,
					'conditional_logic' => array(
						array(
							array(
								'field'		=> 'thsn-slider-type',
								'operator'	=> '==',
								'value'		=> 'revolution-slider',
							),
						),
					),
					'wrapper'			=> array(
						'width'				=> '60',
						'class'				=> '',
						'id'				=> '',
					),
					'message'			=> esc_attr__('No slider found in Revolution Slider. Please create some slider from Admin > Slider Revolution section.', 'itinc'),
					'new_lines'			=> '',
					'esc_html'			=> 0,
				);
			}
		} else {
			$rev_slider_option_array = array(
				'key'				=> 'thsn-message-no-revslider-plugin',
				'label'				=> esc_attr__('Revolution Slider plugin not installed', 'itinc'),
				'name'				=> 'thsn-message-no-revslider-plugin',
				'type'				=> 'message',
				'instructions'		=> '',
				'required'			=> 0,
				'conditional_logic' => array(
					array(
						array(
							'field'		=> 'thsn-slider-type',
							'operator'	=> '==',
							'value'		=> 'revolution-slider',
						),
					),
				),
				'wrapper'			=> array(
					'width'				=> '60',
					'class'				=> '',
					'id'				=> '',
				),
				'message'			=> esc_attr__('Revolution Slider plugin not installed. Please install it from Admin > Appearance > Install Plugins section.', 'itinc'),
				'new_lines'			=> '',
				'esc_html'			=> 0,
			);
		}
		acf_add_local_field_group(array(
			'key'		=> 'thsn-general-settings',
			'title'		=> esc_attr__('ITinc - General Settings', 'itinc'),
			'fields'	=> array_merge(
				array(
					array(  // Tab - Slider Options
						'key'				=> 'thsn-tab-slider-options',
						'label'				=> esc_attr__('Header Slider Options', 'itinc'),
						'name'				=> 'thsn-tab-slider-options',
						'type'				=> 'tab',
						'instructions'		=> '',
						'required'			=> 0,
						'conditional_logic' => 0,
						'wrapper'			=> array(
							'width'				=> '',
							'class'				=> '',
							'id'				=> '',
						),
						'placement'			=> 'top',
						'endpoint'			=> 0,
					),
					array(
						'key'				=> 'thsn-slider-type',
						'label'				=> esc_attr__('Slider', 'itinc'),
						'name'				=> 'thsn-slider-type',
						'type'				=> 'radio',
						'instructions'		=> esc_attr__('Select Slider which appear in header area', 'itinc'),
						'required'			=> 0,
						'conditional_logic' => 0,
						'wrapper'			=> array(
							'width'				=> '20',
							'class'				=> '',
							'id'				=> '',
						),
						'choices'			=> array(
							''					=> esc_attr__('No Slider', 'itinc'),
							'revolution-slider' => esc_attr__('Revolution Slider', 'itinc'),
							'custom-code'		=> esc_attr__('Custom Code for Slider', 'itinc'),
						),
						'allow_null'		=> 0,
						'other_choice'		=> 0,
						'default_value'		=> '',
						'layout'			=> 'vertical',
						'return_format'		=> 'value',
						'save_other_choice' => 0,
					),
				),
				array($rev_slider_option_array),
				array(
					array(
						'key'				=> 'thsn-custom-slider-code',
						'label'				=> esc_attr__('Custom Slider Code', 'itinc'),
						'name'				=> 'thsn-custom-slider-code',
						'type'				=> 'textarea',
						'instructions'		=> '',
						'required'			=> 0,
						'conditional_logic'	=> array(
							array(
								array(
									'field'		=> 'thsn-slider-type',
									'operator'	=> '==',
									'value'		=> 'custom-code',
								),
							),
						),
						'wrapper'			=> array(
							'width'				=> '60',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> '',
						'placeholder'		=> '',
						'maxlength'			=> '',
						'rows'				=> '',
						'new_lines'			=> '',
					),
				),
				array(
					array(
						'key'				=> 'thsn-slider-curved-style',
						'label'				=> esc_attr__('Add Curved style at slider bottom', 'itinc'),
						'name'				=> 'thsn-slider-curved-style',
						'type'				=> 'true_false',
						'instructions'		=> esc_attr__('Select YES to to show curved effect at slider bottom.', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> array(
							array(
								array(
									'field'		=> 'thsn-slider-type',
									'operator'	=> '!=',
									'value'		=> '',
								),
							),
						),
						'wrapper'			=> array(
							'width'				=> '20',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> 0,
						'ui'				=> 1,
						'ui_on_text'		=> '',
						'ui_off_text'		=> '',
					),
				),

				// TAB - Titlebar Options
				array(
					array(
						'key'				=> 'thsn-tab-titlebar-options',
						'label'				=> esc_attr__('Titlebar Options', 'itinc'),
						'name'				=> 'thsn-tab-titlebar-options',
						'type'				=> 'tab',
						'instructions'		=> '',
						'required'			=> 0,
						'conditional_logic'	=> 0,
						'wrapper'			=> array(
							'width'				=> '',
							'class'				=> '',
							'id'				=> '',
						),
						'placement'			=> 'top',
						'endpoint'			=> 0,
					),
					array(
						'key'				=> 'thsn-titlebar-hide',
						'label'				=> esc_attr__('Hide Titlebar?', 'itinc'),
						'name'				=> 'thsn-titlebar-hide',
						'type'				=> 'true_false',
						'instructions'		=> esc_attr__('Select YES to hide Titlebar.', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> 0,
						'wrapper'			=> array(
							'width'				=> '20',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> 0,
						'ui'				=> 1,
						'ui_on_text'		=> '',
						'ui_off_text'		=> '',
					),
					array(
						'key'				=> 'thsn-titlebar-title',
						'label'				=> esc_attr__('Custom title to show in Titlebar', 'itinc'),
						'name'				=> 'thsn-titlebar-title',
						'type'				=> 'text',
						'instructions'		=> esc_attr__('(Optional) This text will be available as Title in Titlebar. Leave blank for default title', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> array(
							array(
								array(
									'field'		=> 'thsn-titlebar-hide',
									'operator'	=> '!=',
									'value'		=> '1',
								),
							),
						),
						'wrapper'			=> array(
							'width'				=> '40',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> '',
						'maxlength'			=> '',
					),
					array(
						'key'				=> 'thsn-titlebar-subtitle',
						'label'				=> esc_attr__('Custom Sub-title to show in Titlebar', 'itinc'),
						'name'				=> 'thsn-titlebar-subtitle',
						'type'				=> 'text',
						'instructions'		=> esc_attr__('(Optional) This text will be available as Sub-title in Titlebar. Leave blank for default title', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> array(
							array(
								array(
									'field'		=> 'thsn-titlebar-hide',
									'operator'	=> '!=',
									'value'		=> '1',
								),
							),
						),
						'wrapper'			=> array(
							'width'				=> '40',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> '',
						'maxlength'			=> '',
					),
					array(
						'key'				=> 'thsn-titlebar-bg-img',
						'label'				=> esc_attr__('Titlebar BG image', 'itinc'),
						'name'				=> 'thsn-titlebar-bg-img',
						'type'				=> 'image',
						'instructions'		=> esc_attr__('(Optional) Set titlebar background image for this page/post only.', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> array(
							array(
								array(
									'field'		=> 'thsn-titlebar-hide',
									'operator'	=> '!=',
									'value'		=> '1',
								),
							),
						),
						'wrapper'			=> array(
							'width'				=> '33',
							'class'				=> '',
							'id'				=> '',
						),
						'return_format'		=> 'url',
						'preview_size'		=> 'thumbnail',
						'library'			=> 'all',
						'min_width'			=> '',
						'min_height'		=> '',
						'min_size'			=> '',
						'max_width'			=> '',
						'max_height'		=> '',
						'max_size'			=> '',
						'mime_types'		=> '',
					),
					array(
						'key'				=> 'thsn-titlebar-bg-color',
						'label'				=> esc_attr__('Titlebar BG Color', 'itinc'),
						'name'				=> 'thsn-titlebar-bg-color',
						'type'				=> 'color_picker',
						'instructions'		=> esc_attr__('(Optional) Set background color for Titlebar.', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> 0,
						'conditional_logic'	=> array(
							array(
								array(
									'field'		=> 'thsn-titlebar-hide',
									'operator'	=> '!=',
									'value'		=> '1',
								),
							),
						),
						'wrapper'			=> array(
							'width'				=> '33',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> '',
					),
					array(
						'key'				=> 'thsn-titlebar-bg-color-opacity',
						'label'				=> esc_attr__('Titlebar BG Color Opacity', 'itinc'),
						'name'				=> 'thsn-titlebar-bg-color-opacity',
						'type'				=> 'range',
						'instructions'		=> esc_attr__('(Optional) Set opacity for background color set for Titlebar.', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> 0,
						'conditional_logic'	=> array(
							array(
								array(
									'field'		=> 'thsn-titlebar-hide',
									'operator'	=> '!=',
									'value'		=> '1',
								),
							),
						),
						'wrapper'			=> array(
							'width'				=> '33',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> '0.5',
						'min'				=> 0,
						'max'				=> 1,
						'step'				=> '0.01',
						'prepend'			=> '',
						'append'			=> '',
					),
				),

				// TAB - Background Options
				array(
					array(
						'key'				=> 'thsn-tab-background-options',
						'label'				=> esc_attr__('Background Options', 'itinc'),
						'name'				=> 'thsn-tab-background-options',
						'type'				=> 'tab',
						'instructions'		=> '',
						'required'			=> 0,
						'conditional_logic'	=> 0,
						'wrapper'			=> array(
							'width'				=> '',
							'class'				=> '',
							'id'				=> '',
						),
						'placement'			=> 'top',
						'endpoint'			=> 0,
					),
					array(
						'key'				=> 'thsn-bg-img',
						'label'				=> esc_attr__('BG image', 'itinc'),
						'name'				=> 'thsn-bg-img',
						'type'				=> 'image',
						'instructions'		=> esc_attr__('(Optional) Set background image for this page/post only.', 'itinc'),
						'required'			=> 0,
						'wrapper'			=> array(
							'width'				=> '33',
							'class'				=> '',
							'id'				=> '',
						),
						'return_format'		=> 'url',
						'preview_size'		=> 'thumbnail',
						'library'			=> 'all',
						'min_width'			=> '',
						'min_height'		=> '',
						'min_size'			=> '',
						'max_width'			=> '',
						'max_height'		=> '',
						'max_size'			=> '',
						'mime_types'		=> '',
					),
					array(
						'key'				=> 'thsn-bg-color',
						'label'				=> esc_attr__('BG Color', 'itinc'),
						'name'				=> 'thsn-bg-color',
						'type'				=> 'color_picker',
						'instructions'		=> esc_attr__('(Optional) Set background color.', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> 0,
						'wrapper'			=> array(
							'width'				=> '33',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> '',
					),
					array(
						'key'				=> 'thsn-bg-color-opacity',
						'label'				=> esc_attr__('BG Color Opacity', 'itinc'),
						'name'				=> 'thsn-bg-color-opacity',
						'type'				=> 'range',
						'instructions'		=> esc_attr__('(Optional) Set opacity for background color.', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> 0,
						'wrapper'			=> array(
							'width'				=> '33',
							'class'				=> '',
							'id'				=> '',
						),
						'default_value'		=> '0.5',
						'min'				=> 0,
						'max'				=> 1,
						'step'				=> '0.01',
						'prepend'			=> '',
						'append'			=> '',
					),
				)
			),
			'location' => array(
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'post',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'page',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-team-member',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-portfolio',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-service',
					),
				),
			),
			'menu_order'		=> 0,
			'position'			=> 'normal',
			'style'				=> 'default',
			'label_placement'	=> 'top',
			'instruction_placement'	=> 'label',
		));

		// Common Metabox - Sidebar
		acf_add_local_field_group(array(
			'key'		=> 'thsn-sidebar-settings',
			'title'		=> 'ITinc - Sidebar Settings',
			'fields'	=> array(
				array(
					'key'				=> 'thsn-sidebar',
					'label'				=> esc_attr__('Select Sidebar', 'itinc'),
					'name'				=> 'thsn-sidebar',
					'type'				=> 'radio',
					'instructions'		=> esc_attr__('Select sidebar for this page/post only', 'itinc'),
					'required'			=> 0,
					'conditional_logic'	=> 0,
					'wrapper'           => array(
						'width'				=> '',
						'class'				=> 'thsn-radio-image-selector',
						'id'				=> '',
					),
					'choices'          => array(
						'global'			=> thsn_esc_kses('<img src="' . get_template_directory_uri() . '/includes/images/sidebar-global.png" />'),
						'left'				=> thsn_esc_kses('<img src="' . get_template_directory_uri() . '/includes/images/sidebar-left.png" />'),
						'right'				=> thsn_esc_kses('<img src="' . get_template_directory_uri() . '/includes/images/sidebar-right.png" />'),
						'no'				=> thsn_esc_kses('<img src="' . get_template_directory_uri() . '/includes/images/sidebar-no.png" />'),
					),
					'allow_null'		=> 0,
					'other_choice'		=> 0,
					'default_value'		=> '',
					'layout'			=> 'horizontal',
					'return_format'		=> 'value',
					'save_other_choice' => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'post',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'page',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-team-member',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-portfolio',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-service',
					),
				),
			),
			'menu_order'		=> 0,
			'position'			=> 'side',
			'style'				=> 'default',
			'label_placement'	=> 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'	=> '',
			'active'			=> 1,
			'description'		=> '',
		));
	};
}
}
add_action( 'init', 'thsn_set_metabox', 21 );
/**
 *  Team Member Meta Box
 */
if( !function_exists('thsn_set_team_metabox') ){
function thsn_set_team_metabox(){
	// Social share options list
	$social_options_array = array();
	$social_list = thsn_social_links_list();
	foreach( $social_list as $social ){
		$social_options_array[] = array(
			'key'			=> esc_attr( $social['id'] ),
			'label'			=> esc_attr( $social['label'] ),
			'name'			=> esc_attr( $social['id'] ),
			'type'			=> 'text',
			'instructions'	=> '',
			'required'		=> 0,
			'conditional_logic'	=> 0,
			'wrapper'		=> array(
				'width'			=> '',
				'class'			=> '',
				'id'			=> '',
			),
			'default_value'	=> '',
			'placeholder'	=> '',
			'prepend'		=> '',
			'append'		=> '',
			'maxlength'		=> '',
		);
	}
	if( function_exists('acf_add_local_field_group') ){
		acf_add_local_field_group(array(
			'key'				=> 'thsn-tab-team-details',
			'title'				=> esc_attr__('ITinc - Member\'s Details', 'itinc'),
			'fields'			=> array(
				array(
					'key'				=> 'thsn-tab-team-details',
					'label'				=> esc_attr__('General Details', 'itinc'),
					'name'				=> 'thsn-tab-team-details',
					'type'				=> 'tab',
					'instructions'		=> '',
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'placement'			=> 'top',
					'endpoint'			=> 0,
				),
				array(
					'key'				=> 'thsn-team-details',
					'label'				=> esc_attr__('Team Member\'s details', 'itinc'),
					'name'				=> 'thsn-team-details',
					'type'				=> 'group',
					'instructions'		=> esc_attr__('Team Member details', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'layout'			=> 'row',
					'sub_fields' => array(
						array(
							'key'				=> 'designation',
							'label'				=> esc_attr__('Designation', 'itinc'),
							'name'				=> 'designation',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'wrapper'			=> array(
								'width'				=> '33',
								'class'				=> '',
								'id'				=> '',
							),
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key'				=> 'phone',
							'label'				=> esc_attr__('Phone', 'itinc'),
							'name'				=> 'phone',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'wrapper'			=> array(
								'width'				=> '33',
								'class'				=> '',
								'id'				=> '',
							),
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key'				=> 'email',
							'label'				=> esc_attr__('Email', 'itinc'),
							'name'				=> 'email',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'wrapper'			=> array(
								'width'				=> '33',
								'class'				=> '',
								'id'				=> '',
							),
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
						array(
							'key'				=> 'sitetitle',
							'label'				=> esc_attr__('Website Title', 'itinc'),
							'name'				=> 'sitetitle',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
						array(
							'key'				=> 'siteurl',
							'label'				=> esc_attr__('Website URL', 'itinc'),
							'name'				=> 'siteurl',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'wrapper'			=> array(
								'width'				=> '33',
								'class'				=> '',
								'id'				=> '',
							),
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
						array(
							'key'				=> 'fax',
							'label'				=> esc_attr__('Fax', 'itinc'),
							'name'				=> 'fax',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'wrapper'			=> array(
								'width'				=> '33',
								'class'				=> '',
								'id'				=> '',
							),
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
						array(
							'key'		=> 'short-info',
							'label'		=> esc_attr__('One Line Info', 'itinc'),
							'name'		=> 'short-info',
							'type'		=> 'text',
							'instructions' => esc_attr__('This will appear on "Themesion Team Box" Style-3 only. This will not appear on single view.', 'itinc'),
						),
						array(
							'key'		=> 'short-description',
							'label'		=> esc_attr__('Short Description', 'itinc'),
							'name'		=> 'short-description',
							'type'		=> 'wysiwyg',
							'instructions' => esc_attr__('This will appear on single view only.', 'itinc'),
						),
						array(
							'key'				=> 'progress_1_label',
							'label'				=> esc_attr__('Progress Bar 1 - Label', 'itinc'),
							'name'				=> 'progress_1_label',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
						array(
							'key'				=> 'progress_1_number',
							'label'				=> esc_attr__('Progress Bar 1 - Number', 'itinc'),
							'name'				=> 'progress_1_number',
							'type'				=> 'text',
							'instructions'		=> esc_attr__('Add number between 1 to 100', 'itinc'),
							'required'			=> 0,
							'conditional_logic' => 0,
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
						array(
							'key'				=> 'progress_2_label',
							'label'				=> esc_attr__('Progress Bar 2 - Label', 'itinc'),
							'name'				=> 'progress_2_label',
							'type'				=> 'text',
							'instructions'		=> '',
							'required'			=> 0,
							'conditional_logic' => 0,
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
						array(
							'key'				=> 'progress_2_number',
							'label'				=> esc_attr__('Progress Bar 2 - Number', 'itinc'),
							'name'				=> 'progress_2_number',
							'type'				=> 'text',
							'instructions'		=> esc_attr__('Add number between 1 to 100', 'itinc'),
							'required'			=> 0,
							'conditional_logic' => 0,
							'default_value'		=> '',
							'placeholder'		=> '',
							'prepend'			=> '',
							'append'			=> '',
							'maxlength'			=> '',
						),
					),
				),
				array(
					'key'				=> 'thsn-tab-social-links',
					'label'				=> esc_attr__('Social Links', 'itinc'),
					'name'				=> 'thsn-tab-social-links',
					'type'				=> 'tab',
					'instructions'		=> '',
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'placement'			=> 'top',
					'endpoint'			=> 0,
				),
				array(
					'key'			=> 'thsn-social-links',
					'label'			=> esc_attr__('Social Links', 'itinc'),
					'name'			=> 'thsn-social-links',
					'type'			=> 'group',
					'instructions'	=> esc_attr__('Select Social links for this Team Member', 'itinc'),
					'required'		=> 0,
					'conditional_logic'	=> 0,
					'wrapper'		=> array(
						'width'			=> '',
						'class'			=> '',
						'id'			=> '',
					),
					'layout'		=> 'row',
					'sub_fields'	=> $social_options_array,
				),
			),
			'location' => array(
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-team-member',
					),
				),
			),
			'menu_order'		=> 0,
			'position'			=> 'normal',
			'style'				=> 'default',
			'label_placement'	=> 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'	=> '',
			'active'			=> 1,
			'description'		=> '',
		));
	};
}
}
add_action( 'init', 'thsn_set_team_metabox', 22 );
if( !function_exists('thsn_set_client_metabox') ){
function thsn_set_client_metabox(){
	if( function_exists('acf_add_local_field_group') ){
	acf_add_local_field_group(array(
		'key'		=> 'thsn-client-logo',
		'title'		=> esc_attr__('Client Logo Hover', 'itinc'),
		'fields'	=> array(
			array(
				'key'				=> 'thsn-logo-image-for-hover',
				'label'				=> esc_attr__('Select Logo for Hover effect. This logo will appear on mouse over.', 'itinc'),
				'name'				=> 'thsn-logo-image-for-hover',
				'type'				=> 'image',
				'required'			=> 0,
				'conditional_logic'	=> 0,
				'return_format'		=> 'id',
				'preview_size'		=> 'thumbnail',
				'library'			=> 'all',
			),
		),
		'location'	=> array(
			array(
				array(
					'param'		=> 'post_type',
					'operator'	=> '==',
					'value'		=> 'thsn-client',
				),
			),
		),
		'menu_order'		=> 0,
		'position'			=> 'normal',
		'style'				=> 'default',
		'label_placement'	=> 'top',
		'instruction_placement'	=> 'label',
		'hide_on_screen'	=> '',
		'active'			=> 1,
		'description'		=> esc_attr__('Hover image of client logo', 'itinc'),
	));
	}
	if( function_exists('acf_add_local_field_group') ){
		acf_add_local_field_group(array(
			'key'		=> 'thsn-client-logo-link',
			'title'		=> esc_attr__('Client Logo Link', 'itinc'),
			'fields'	=> array(
				array(
					'key'				=> 'thsn-logo-link',
					'label'				=> esc_attr__('Set Link for the logo', 'itinc'),
					'name'				=> 'thsn-logo-link',
					'type'				=> 'link',
					'return_format'		=> 'url',
				),
			),
			'location'	=> array(
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-client',
					),
				),
			),
			'menu_order'		=> 0,
			'position'			=> 'side',
			'style'				=> 'default',
			'label_placement'	=> 'top',
			'instruction_placement'	=> 'label',
			'hide_on_screen'	=> '',
			'active'			=> 1,
			'description'		=> esc_attr__('Hover image of client logo', 'itinc'),
		));
		}
	}
}
add_action( 'init', 'thsn_set_client_metabox', 23 );

if( !function_exists('thsn_post_format_metaboxes') ){
function thsn_post_format_metaboxes(){

	if( function_exists('acf_add_local_field_group') ){

		// Post Format - Video
		acf_add_local_field_group(array(
			'key'					=> 'thsn-pformat-video-metabox',
			'title'					=> esc_attr__('ITinc - Post Format Video Options', 'itinc'),
			'fields'				=> array(
				array(
					'key'				=> 'thsn-pformat-video',
					'label'				=> esc_attr__('Video URL (like Youtube or Vimeo) OR Embed Code', 'itinc'),
					'name'				=> 'thsn-pformat-video',
					'type'				=> 'textarea',
					'instructions'		=> esc_attr__('Add Youtube or Vimeo URL here. Also you can paste embed code here.', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'default_value'		=> '',
					'placeholder'		=> '',
					'maxlength'			=> '',
					'rows'				=> '',
					'new_lines'			=> '',
				),
			),
			'location'				=> array(
				array(
					array(
						'param'			=> 'post_format',
						'operator'		=> '==',
						'value'			=> 'video',
					),
				),
			),
			'menu_order'			=> 0,
			'position'				=> 'acf_after_title',
			'style'					=> 'default',
			'label_placement'		=> 'left',
			'instruction_placement'	=> 'label',
			'hide_on_screen'		=> '',
			'active'				=> true,
			'description'			=> '',
		));

		// Post Format - Quote
		acf_add_local_field_group(array(
			'key'					=> 'thsn-pformat-quote-metabox',
			'title'					=> esc_attr__('ITinc - Post Format Quote Options', 'itinc'),
			'fields'				=> array(
				array(
					'key'				=> 'thsn-pformat-quote-source-name',
					'label'				=> esc_attr__('Source Name', 'itinc'),
					'name'				=> 'thsn-pformat-quote-source-name',
					'type'				=> 'text',
					'instructions'		=> esc_attr__('Add source name of the quote.', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'default_value'		=> '',
					'placeholder'		=> '',
					'maxlength'			=> '',
					'rows'				=> '',
					'new_lines'			=> '',
				),
				array(
					'key'				=> 'thsn-pformat-quote-source-url',
					'label'				=> esc_attr__('Source URL', 'itinc'),
					'name'				=> 'thsn-pformat-quote-source-url',
					'type'				=> 'text',
					'instructions'		=> esc_attr__('Add source link of the quote.', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'default_value'		=> '',
					'placeholder'		=> '',
					'maxlength'			=> '',
					'rows'				=> '',
					'new_lines'			=> '',
				),
			),
			'location'				=> array(
				array(
					array(
						'param'			=> 'post_format',
						'operator'		=> '==',
						'value'			=> 'quote',
					),
				),
			),
			'menu_order'			=> 0,
			'position'				=> 'acf_after_title',
			'style'					=> 'default',
			'label_placement'		=> 'left',
			'instruction_placement'	=> 'label',
			'hide_on_screen'		=> '',
			'active'				=> true,
			'description'			=> '',
		));

		// Post Format - Link
		acf_add_local_field_group(array(
			'key'					=> 'thsn-pformat-link-metabox',
			'title'					=> esc_attr__('ITinc - Post Format Link Options', 'itinc'),
			'fields'				=> array(
				array(
					'key'				=> 'thsn-pformat-link-title',
					'label'				=> esc_attr__('Link Title', 'itinc'),
					'name'				=> 'thsn-pformat-link-title',
					'type'				=> 'text',
					'instructions'		=> esc_attr__('Add link title.', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'default_value'		=> '',
					'placeholder'		=> '',
					'maxlength'			=> '',
					'rows'				=> '',
					'new_lines'			=> '',
				),
				array(
					'key'				=> 'thsn-pformat-link-url',
					'label'				=> esc_attr__('Link URL', 'itinc'),
					'name'				=> 'thsn-pformat-link-url',
					'type'				=> 'text',
					'instructions'		=> esc_attr__('Add link URL.', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'default_value'		=> '',
					'placeholder'		=> '',
					'maxlength'			=> '',
					'rows'				=> '',
					'new_lines'			=> '',
				),
			),
			'location'				=> array(
				array(
					array(
						'param'			=> 'post_format',
						'operator'		=> '==',
						'value'			=> 'link',
					),
				),
			),
			'menu_order'			=> 0,
			'position'				=> 'acf_after_title',
			'style'					=> 'default',
			'label_placement'		=> 'left',
			'instruction_placement'	=> 'label',
			'hide_on_screen'		=> '',
			'active'				=> true,
			'description'			=> '',
		));

		// Post Format - Gallery
		if( class_exists('acf_plugin_photo_gallery') ){
			acf_add_local_field_group(array(
				'key'					=> 'thsn-pformat-gallery-metabox',
				'title'					=> esc_attr__('Image Gallery', 'itinc'),
				'fields'				=> array(
					array(
						'key'				=> 'thsn-pformat-gallery',
						'label'				=> esc_attr__('Image Gallery', 'itinc'),
						'name'				=> 'thsn-pformat-gallery',
						'type'				=> 'photo_gallery',
						'instructions'		=> esc_attr__('Select image for slider', 'itinc'),
						'required'			=> 0,
						'conditional_logic'	=> 0,
					),
				),
				'location'				=> array(
					array(
						array(
							'param'			=> 'post_format',
							'operator'		=> '==',
							'value'			=> 'gallery',
						),
					),
				),
				'position'				=> 'acf_after_title',
				'label_placement'		=> 'left',
				'instruction_placement' => 'label',
				'hide_on_screen'		=> '',
				'description'			=> esc_attr__('Description', 'itinc'),
			));
		}

		// Post Format - Audio
		acf_add_local_field_group(array(
			'key'					=> 'thsn-pformat-audio-metabox',
			'title'					=> esc_attr__('ITinc - Post Format Audio Options', 'itinc'),
			'fields'				=> array(
				array(
					'key'				=> 'thsn-pformat-audio',
					'label'				=> esc_attr__('Audio URL (like SoundCloud) OR Embed Code', 'itinc'),
					'name'				=> 'thsn-pformat-audio',
					'type'				=> 'textarea',
					'instructions'		=> esc_attr__('Add Youtube or Vimeo URL here. Also you can paste embed code here.', 'itinc'),
					'required'			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width'				=> '',
						'class'				=> '',
						'id'				=> '',
					),
					'default_value'		=> '',
					'placeholder'		=> '',
					'maxlength'			=> '',
					'rows'				=> '',
					'new_lines'			=> '',
				),
			),
			'location'				=> array(
				array(
					array(
						'param'			=> 'post_format',
						'operator'		=> '==',
						'value'			=> 'audio',
					),
				),
			),
			'menu_order'			=> 0,
			'position'				=> 'acf_after_title',
			'style'					=> 'default',
			'label_placement'		=> 'left',
			'instruction_placement'	=> 'label',
			'hide_on_screen'		=> '',
			'active'				=> true,
			'description'			=> '',
		));

	};
}
}
add_action( 'init', 'thsn_post_format_metaboxes', 24 );

if( !function_exists('thsn_portfolio_featured_metabox') ){
function thsn_portfolio_featured_metabox(){
	if( function_exists('acf_add_local_field_group') ):
	acf_add_local_field_group(array(
		'key'		=> 'thsn-featured-data-type',
		'title'		=> esc_attr__('ITinc - Featured Data Type', 'itinc'),
		'fields'	=> array(
			array(
				'key'			=> 'thsn-featured-type',
				'label'			=> esc_attr__('Featured Data Type', 'itinc'),
				'name'			=> 'thsn-featured-type',
				'type'			=> 'radio',
				'instructions'	=> esc_attr__('Select type of featured content', 'itinc'),
				'required'		=> 0,
				'conditional_logic'	=> 0,
				'wrapper'		=> array(
					'width'			=> '25',
					'class'			=> '',
					'id'			=> '',
				),
				'choices'		=> array(
					'featured'		=> esc_attr__('Featured Image (default)', 'itinc'),
					'slider'		=> esc_attr__('Image Slider', 'itinc'),
					'video'			=> esc_attr__('Video', 'itinc'),
					'audio'			=> esc_attr__('Audio', 'itinc'),
				),
				'allow_null'	=> 0,
				'other_choice'	=> 0,
				'default_value'	=> '',
				'layout'		=> 'vertical',
				'return_format'	=> 'value',
				'save_other_choice' => 0,
			),
			array(
				'key'				=> 'thsn-photo-gallery',
				'label'				=> esc_attr__('Slider Images', 'itinc'),
				'name'				=> 'thsn-photo-gallery',
				'type'				=> 'photo_gallery',
				'instructions'		=> esc_attr__('Select images for slider', 'itinc'),
				'required'			=> 0,
				'conditional_logic' => array(
					array(
						array(
							'field'		=> 'thsn-featured-type',
							'operator'	=> '==',
							'value'		=> 'slider',
						),
					),
				),
				'wrapper'			=> array(
					'width'				=> '75',
					'class'				=> '',
					'id'				=> '',
				),
				'fields[slider_images' => array(
					'edit_modal'			=> 'Default',
				),
				'edit_modal' => 'Default',
			),
			array(
				'key'				=> 'thsn-video-url',
				'label'				=> esc_attr__('Video URL', 'itinc'),
				'name'				=> 'thsn-video-url',
				'type'				=> 'text',
				'instructions'		=> esc_attr__('Add video URL from YouTube or Vimeo', 'itinc'),
				'required'			=> 0,
				'conditional_logic' => array(
					array(
						array(
							'field'		=> 'thsn-featured-type',
							'operator'	=> '==',
							'value'		=> 'video',
						),
					),
				),
				'wrapper'			=> array(
					'width'				=> '75',
					'class'				=> '',
					'id'				=> '',
				),
				'default_value'		=> '',
				'placeholder'		=> '',
				'prepend'			=> '',
				'append'			=> '',
				'maxlength'			=> '',
			),
			array(
				'key'				=> 'thsn-audio-url',
				'label'				=> esc_attr__('Audio URL', 'itinc'),
				'name'				=> 'thsn-audio-url',
				'type'				=> 'text',
				'instructions'		=> esc_attr__('Add audio URL from SoundCloud or MP3', 'itinc'),
				'required'			=> 0,
				'conditional_logic' => array(
					array(
						array(
							'field'		=> 'thsn-featured-type',
							'operator'	=> '==',
							'value'		=> 'audio',
						),
					),
				),
				'wrapper'			=> array(
					'width'				=> '75',
					'class'				=> '',
					'id'				=> '',
				),
				'default_value'		=> '',
				'placeholder'		=> '',
				'prepend'			=> '',
				'append'			=> '',
				'maxlength'			=> '',
			),
		),
		'location'			=> array(
			array(
				array(
					'param'		=> 'post_type',
					'operator'	=> '==',
					'value'		=> 'thsn-portfolio',
				),
			),
		),
		'menu_order'		=> 1,
		'position'			=> 'normal',
		'style'				=> 'default',
		'label_placement'	=> 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'	=> '',
		'active'			=> 1,
		'description'		=> '',
	));
	endif;
}
}
add_action( 'init', 'thsn_portfolio_featured_metabox', 25 );
if( !function_exists('thsn_portfolio_details_metabox') ){
function thsn_portfolio_details_metabox(){
	$line_list = array();
	$portfolio_details = thsn_get_base_option('portfolio-details');
	if( !empty($portfolio_details) && is_array($portfolio_details) ){
		foreach( $portfolio_details as $line ){
			if( !empty($line['line_title']) ){
				$line_id = thsn_generateClassName(trim($line['line_title']));
				$line_id = str_replace( ' ', '_', $line_id );
				$line_id = sanitize_html_class( strtolower( $line_id ) ) ;
				if( $line['line_type']=='text' ){
					$line_list[] = array(
						'key'			=> $line_id,
						'label'			=> sprintf( esc_attr__('%1$s ', 'itinc'), $line['line_title'] ),
						'name'			=> $line_id,
						'type'			=> 'text',
					);
				} else {
					$desc = esc_attr__('(Category with link)','itinc');
					if( $line['line_type']=='category' ){
						$desc = esc_attr__('(Category without link)','itinc');
					}
					$line_list[] = array(
						'type'		=> 'generic',
						'key'		=> $line_id,
						'label'		=> sprintf( esc_attr__('%1$s ', 'itinc'), $line['line_title'] ) . $desc,
						'default'	=> '',
						'choices'	=> array(
							'element'	=> 'input',
							'type'		=> 'text',
							'disabled'	=> 'disabled',
						),
					);
				}
			}
		}
	}
	if( function_exists('acf_add_local_field_group') ){
	acf_add_local_field_group(array(
		'key'		=> 'thsn-portfolio-details-group',
		'title'		=> esc_attr__('ITinc - Portfolio Details', 'itinc'),
		'fields'	=> array(
			array(
				'key'			=> 'thsn-portfolio-details',
				'label'			=> esc_attr__('Portfolio Details', 'itinc'),
				'name'			=> 'thsn-portfolio-details',
				'type'			=> 'group',
				'instructions'	=> esc_attr__('Fill the values for each option that applies. Leave blank to hide it.', 'itinc'),
				'required'		=> 0,
				'conditional_logic'	=> 0,
				'wrapper'		=> array(
					'width'			=> '',
					'class'			=> '',
					'id'			=> '',
				),
				'layout'		=> 'block',
				'sub_fields'	=> $line_list,
			),
		),
		'location'	=> array(
			array(
				array(
					'param'		=> 'post_type',
					'operator'	=> '==',
					'value'		=> 'thsn-portfolio',
				),
			),
		),
		'menu_order'		=> 2,
		'position'			=> 'normal',
		'style'				=> 'default',
		'label_placement'	=> 'left',
		'instruction_placement' => 'label',
		'hide_on_screen'	=> '',
		'active'			=> 1,
		'description'		=> '',
	));
	};
}
}
add_action( 'init', 'thsn_portfolio_details_metabox', 26 );
if( !function_exists('thsn_testimonial_details_metabox') ){
function thsn_testimonial_details_metabox(){
	if( function_exists('acf_add_local_field_group') ):
	acf_add_local_field_group(array(
		'key'		=> 'thsn-testimonial-details-box',
		'title'		=> esc_attr__('ITinc - Testimonial Details', 'itinc'),
		'fields'	=> array(
			array(
				'key'			=> 'thsn-testimonial-details',
				'label'			=> esc_attr__('Details', 'itinc'),
				'name'			=> 'thsn-testimonial-details',
				'type'			=> 'text',
				'instructions'	=> esc_attr__('(optional) Add details like Company name, designation etc', 'itinc'),
				'required'		=> 0,
				'conditional_logic'	=> 0,
				'wrapper'		=> array(
					'width'			=> '50',
					'class'			=> '',
					'id'			=> '',
				),
				'default_value'	=> '',
				'placeholder'	=> '',
				'prepend'		=> '',
				'append'		=> '',
				'maxlength'		=> '',
			),
			array(
				'key'			=> 'thsn-star-ratings',
				'label'			=> esc_attr__('Star Ratings', 'itinc'),
				'name'			=> 'thsn-star-ratings',
				'type'			=> 'select',
				'instructions'	=> esc_attr__('Select star ratings.', 'itinc'),
				'required'		=> 0,
				'conditional_logic' => 0,
				'wrapper'		=> array(
					'width'			=> '50',
					'class'			=> '',
					'id'			=> '',
				),
				'choices'		=> array(
					'no'			=> esc_attr__('No ratings', 'itinc'),
					'1'				=> esc_attr__('1 star', 'itinc'),
					'2'				=> esc_attr__('2 stars', 'itinc'),
					'3'				=> esc_attr__('3 stars', 'itinc'),
					'4'				=> esc_attr__('4 stars', 'itinc'),
					'5'				=> esc_attr__('5 stars', 'itinc'),
				),
				'default_value'	=> 'no',
				'allow_null'	=> 0,
				'multiple'		=> 0,
				'ui'			=> 1,
				'ajax'			=> 0,
				'return_format'	=> 'value',
				'placeholder'	=> '',
			),
		),
		'location'			=> array(
			array(
				array(
					'param'		=> 'post_type',
					'operator'	=> '==',
					'value'		=> 'thsn-testimonial',
				),
			),
		),
		'menu_order'		=> 0,
		'position'			=> 'normal',
		'style'				=> 'default',
		'label_placement'	=> 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'	=> '',
		'description'		=> '',
	));
	endif;
}
}
add_action( 'init', 'thsn_testimonial_details_metabox', 27 );
if( !function_exists('thsn_service_short_desc_metabox') ){
function thsn_service_short_desc_metabox(){
	if( function_exists('acf_add_local_field_group') ):
		$icon_picker_options = array();
		// Icon library
		$library = array();
		$lib_list = thsn_icon_library_list();
		foreach( $lib_list as $lib_id=>$lib_data ){
			$library[$lib_id] = $lib_data['name'];
		}
		$icon_picker_options[] = array(
			'key'			=> 'thsn-icon-library',
			'label'			=> esc_attr__('Select Icon Library', 'itinc'),
			'name'			=> 'thsn-icon-library',
			'type'			=> 'select',
			'instructions'	=> esc_attr__('Select Icon Library.', 'itinc'),
			'required'		=> 0,
			'conditional_logic' => 0,
			'wrapper'		=> array(
				'width'			=> '33',
				'class'			=> '',
				'id'			=> '',
			),
			'choices'		=> $library,
			'default_value'	=> 'no',
			'allow_null'	=> 0,
			'multiple'		=> 0,
			'ui'			=> 1,
			'ajax'			=> 0,
			'return_format'	=> 'value',
			'placeholder'	=> '',
		);
		foreach( $lib_list as $lib_id=>$lib_data ){
			$icon_picker_options[] = array(
				'key'		=> 'thsn-service-icon-' . $lib_id ,
				'label'		=> $lib_data['name'],
				'name'		=> 'thsn-service-icon-' . $lib_id,
				'type'		=> 'thsn_fonticonpicker',
				'library'	=> $lib_id,
				'instructions' => esc_attr__('Select icon from here', 'itinc'),
				'required'	=> 0,
				'conditional_logic' => 0,
				'wrapper'	=> array(
					'width'		=> '66',
					'class'		=> '',
					'id'		=> '',
				),
			);
		}
		acf_add_local_field_group(array(
			'key'		=> 'thsn-group-service-short-desc',
			'title'		=> esc_attr__('ITinc - Short Description', 'itinc'),
			'fields'	=> array(
				array(
					'key'		=> 'thsn-short-description',
					'label'		=> esc_attr__('Short Description', 'itinc'),
					'name'		=> 'thsn-short-description',
					'type'		=> 'textarea',
					'instructions' => esc_attr__('This will appear on single view', 'itinc'),
				),
			),
			'location' => array(
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-service',
					),
				),
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-portfolio',
					),
				),
			),
			'menu_order'		=> 2,
			'position'			=> 'normal',
			'style'				=> 'default',
			'label_placement'	=> 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'	=> '',
			'description'		=> '',
		));
	endif;
}
}
add_action( 'init', 'thsn_service_short_desc_metabox', 28 );

if( !function_exists('thsn_single_icon_metabox') ){
function thsn_single_icon_metabox(){
	if( function_exists('acf_add_local_field_group') ){
		$icon_picker_options = array();

		// Icon library
		$library = array();
		// Custom icon option
		$icon_picker_options[] = array(
			'key'				=> 'thsn-custom-icon-enabled',
			'label'				=> esc_attr__('Select Custom Icon?', 'itinc'),
			'name'				=> 'thsn-custom-icon-enabled',
			'type'				=> 'true_false',
			'instructions'		=> '',
			'required'			=> 0,
			'conditional_logic'	=> 0,
			'wrapper'			=> array(
				'width'				=> '',
				'class'				=> '',
				'id'				=> '',
			),
			'message'			=> '',
			'default_value'		=> 0,
			'ui'				=> 1,
			'ui_on_text'		=> '',
			'ui_off_text'		=> '',
		);


		$icon_picker_options[] = array(
			'key'				=> 'thsn-custom-icon',
			'label'				=> esc_attr__('Select Custom Icon', 'itinc'),
			'name'				=> 'thsn-custom-icon',
			'type'				=> 'image',
			'instructions'		=> esc_attr__('You can select SVG, JPG, PNG or GIF image here', 'itinc') . ( ( !defined('BODHI_SVGS_PLUGIN_PATH') ) ? thsn_esc_kses('<br><strong>')  . esc_attr__('NOTE:', 'itinc') . thsn_esc_kses('</strong>') . ' ' . sprintf( esc_attr__('For SVG selection, make sure you installed and activated the %1$s plugin.', 'itinc'), '<a href="https://wordpress.org/plugins/svg-support/" target="_blank">SVG Support</a>' ) : '' ),
			'required'			=> 0,
			'conditional_logic'	=> array(
				array(
					array(
						'field'		=> 'thsn-custom-icon-enabled',
						'operator'	=> '==',
						'value'		=> '1',
					),
				),
			),
			'wrapper'			=> array(
				'width'				=> '',
				'class'				=> '',
				'id'				=> '',
			),
			'return_format'		=> 'array',
			'library'			=> 'all',
			'min_size'			=> '',
			'max_size'			=> '',
			'mime_types'		=> 'jpg, jpeg, png, gif, svg',
		);
		$lib_list = thsn_icon_library_list();
		foreach( $lib_list as $lib_id=>$lib_data ){
			$library[$lib_id] = $lib_data['name'];
		}
		$icon_picker_options[] = array(
			'key'			=> 'thsn-service-icon-library',
			'label'			=> esc_attr__('Select Icon Library', 'itinc'),
			'name'			=> 'thsn-service-icon-library',
			'type'			=> 'select',
			'instructions'	=> esc_attr__('Select Icon Library.', 'itinc'),
			'required'		=> 0,
			'conditional_logic' =>  array(
				array(
					array(
						'field'		=> 'thsn-custom-icon-enabled',
						'operator'	=> '==',
						'value'		=> '0',
					),
				),
			),
			'wrapper'		=> array(
				'width'			=> '33',
				'class'			=> '',
				'id'			=> '',
			),
			'choices'		=> $library,
			'default_value'	=> 'no',
			'allow_null'	=> 0,
			'multiple'		=> 0,
			'ui'			=> 1,
			'ajax'			=> 0,
			'return_format'	=> 'value',
			'placeholder'	=> '',
		);
		foreach( $lib_list as $lib_id=>$lib_data ){
			$icon_picker_options[] = array(
				'key'		=> 'thsn-service-icon-' . $lib_id ,
				'label'		=> $lib_data['name'],
				'name'		=> 'thsn-service-icon-' . $lib_id,
				'type'		=> 'thsn_fonticonpicker',
				'library'	=> $lib_id,
				'instructions' => esc_attr__('Select icon from here', 'itinc'),
				'required'	=> 0,
				'conditional_logic'	=> array(
					array(
						array(
							'field'		=> 'thsn-service-icon-library',
							'operator'	=> '==',
							'value'		=> $lib_id,
						),
					),
				),
				'wrapper'	=> array(
					'width'		=> '66',
					'class'		=> '',
					'id'		=> '',
				),
			);
		}
		acf_add_local_field_group(array(
			'key'		=> 'thsn-group-single-icon',
			'title'		=> esc_attr__('ITinc - Icon for Single', 'itinc'),
			'fields'	=> $icon_picker_options,
			'location' => array(
				array(
					array(
						'param'		=> 'post_type',
						'operator'	=> '==',
						'value'		=> 'thsn-service',
					),
				),
			),
			'menu_order'		=> 2,
			'position'			=> 'normal',
			'style'				=> 'default',
			'label_placement'	=> 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'	=> '',
			'description'		=> '',
		));
	};
}
}
add_action( 'init', 'thsn_single_icon_metabox', 28 );

if( !function_exists('thsn_portfolio_single_view_metabox') ){
function thsn_portfolio_single_view_metabox(){
	if( function_exists('acf_add_local_field_group') ):
	// Total Single Portfolio Styles
	$portfolio_single_style_array = array(
		'0' => '<img src="'.get_template_directory_uri() . '/includes/images/portfolio-single-style-global.jpg" />',
		'1'	=> '<img src="'.get_template_directory_uri() . '/includes/images/portfolio-single-style-1.jpg" />',
		'2'	=> '<img src="'.get_template_directory_uri() . '/includes/images/portfolio-single-style-2.jpg" />',
	);
	// Single Title
	$portfolio_cpt_singular_title	= esc_attr__('Portfolio','itinc');
	if( class_exists('Kirki') ){
		// Portfolio
		$portfolio_cpt_singular_title2	= Kirki::get_option( 'portfolio-cpt-singular-title' );
		$portfolio_cpt_singular_title	= ( !empty($portfolio_cpt_singular_title2) ) ? $portfolio_cpt_singular_title2 : $portfolio_cpt_singular_title ;
	}
	acf_add_local_field_group(array(
		'key'		=> 'thsn-group-portfolio-single-view',
		'title'		=> sprintf( esc_attr__('ITinc - %1$s Single View Option', 'itinc'), $portfolio_cpt_singular_title ),
		'fields'	=> array(
			array(
				'key'		=> 'thsn-portfolio-single-view',
				'label'		=> sprintf( esc_attr__('%1$s Single View', 'itinc'), $portfolio_cpt_singular_title ),
				'name'		=> 'thsn-portfolio-single-view',
				'type'		=> 'radio',
				'instructions' => sprintf( esc_attr__('Select %1$s Single View', 'itinc'), $portfolio_cpt_singular_title ),
				'required'			=> 0,
				'choices'			=> $portfolio_single_style_array,
				'wrapper'			=> array(
					'class'				=> 'thsn-radio-image-selector',
					'id'				=> '',
				),
				'allow_null'		=> 0,
				'other_choice'		=> 0,
				'default_value'		=> '',
				'layout'			=> 'horizontal',
				'return_format'		=> 'value',
				'save_other_choice' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param'		=> 'post_type',
					'operator'	=> '==',
					'value'		=> 'thsn-portfolio',
				),
			),
		),
		'menu_order'		=> 3,
		'position'			=> 'normal',
		'style'				=> 'default',
		'label_placement'	=> 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'	=> '',
		'description'		=> '',
	));
	endif;
}
}
add_action( 'init', 'thsn_portfolio_single_view_metabox', 25 );

if( !function_exists('thsn_service_single_view_metabox') ){
function thsn_service_single_view_metabox(){
	if( function_exists('acf_add_local_field_group') ):
	// Total Single Portfolio Styles
	$service_single_style_array = array(
		'0' => '<img src="'.get_template_directory_uri() . '/includes/images/service-single-style-global.jpg" />',
		'1'	=> '<img src="'.get_template_directory_uri() . '/includes/images/service-single-style-1.jpg" />',
		'2'	=> '<img src="'.get_template_directory_uri() . '/includes/images/service-single-style-2.jpg" />',
	);
	// Single Title
	$service_cpt_singular_title	= esc_attr__('Portfolio','itinc');
	if( class_exists('Kirki') ){
		// Portfolio
		$service_cpt_singular_title2	= Kirki::get_option( 'service-cpt-singular-title' );
		$service_cpt_singular_title	= ( !empty($service_cpt_singular_title2) ) ? $service_cpt_singular_title2 : $service_cpt_singular_title ;
	}
	acf_add_local_field_group(array(
		'key'		=> 'thsn-group-service-single-view',
		'title'		=> sprintf( esc_attr__('ITinc - %1$s Single View Option', 'itinc'), $service_cpt_singular_title ),
		'fields'	=> array(
			array(
				'key'		=> 'thsn-service-single-view',
				'label'		=> sprintf( esc_attr__('%1$s Single View', 'itinc'), $service_cpt_singular_title ),
				'name'		=> 'thsn-service-single-view',
				'type'		=> 'radio',
				'instructions' => sprintf( esc_attr__('Select %1$s Single View', 'itinc'), $service_cpt_singular_title ),
				'required'			=> 0,
				'choices'			=> $service_single_style_array,
				'wrapper'			=> array(
					'class'				=> 'thsn-radio-image-selector',
					'id'				=> '',
				),
				'allow_null'		=> 0,
				'other_choice'		=> 0,
				'default_value'		=> '',
				'layout'			=> 'horizontal',
				'return_format'		=> 'value',
				'save_other_choice' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param'		=> 'post_type',
					'operator'	=> '==',
					'value'		=> 'thsn-service',
				),
			),
		),
		'menu_order'		=> 3,
		'position'			=> 'normal',
		'style'				=> 'default',
		'label_placement'	=> 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'	=> '',
		'description'		=> '',
	));
	endif;
}
}
add_action( 'init', 'thsn_service_single_view_metabox', 25 );