<?php

/*
[thsn-icon-buttons button1_link="#" button1_icon="thsn-itinc-icon thsn-itinc-icon-pdf-1" button1_text1="Download our Brochures" button1_text2="DOWNLOAD" button2_link="#" button2_icon="thsn-itinc-icon thsn-itinc-icon-document-1" button2_text1="Our company details" button2_text2="DOWNLOAD"]
*/

if( !function_exists('themesion_sc_icon') ){
function themesion_sc_icon_buttons( $atts, $content="" ) {

	$return = '';


	// button 1
	if( !empty($atts['button1_link']) ) {

		$text = $icon = '';

		$text .= ( !empty($atts['button1_text1']) ) ? trim($atts['button1_text1']) : '' ;
		$text .= ( !empty($atts['button1_text2']) ) ? '<span>'.trim($atts['button1_text2']).'</span>' : '' ;

		if( !empty($atts['button1_icon']) ){
			$icon = themesion_sc_icon_buttons_icon($atts['button1_icon']);
		}

		$return .= '<div class="item-download">
			<a href="' . trim($atts['button1_link']) . '" rel="noopener noreferrer">
        		' . $icon . '
				' . $text . '
			</a>
    	</div>';

	}

	// button 2
	if( !empty($atts['button2_link']) ) {

		$text = $icon = '';

		$text .= ( !empty($atts['button2_text1']) ) ? trim($atts['button2_text1']) : '' ;
		$text .= ( !empty($atts['button2_text2']) ) ? '<span>'.trim($atts['button2_text2']).'</span>' : '' ;

		if( !empty($atts['button2_icon']) ){
			$icon = themesion_sc_icon_buttons_icon($atts['button2_icon']);
		}

		$return .= '<div class="item-download">
			<a href="' . trim($atts['button2_link']) . '" rel="noopener noreferrer">
        		' . $icon . '
				' . $text . '
			</a>
    	</div>';

	}

	// button 3
	if( !empty($atts['button3_link']) ) {

		$text = $icon = '';

		$text .= ( !empty($atts['button3_text1']) ) ? trim($atts['button3_text1']) : '' ;
		$text .= ( !empty($atts['button3_text2']) ) ? '<span>'.trim($atts['button3_text2']).'</span>' : '' ;

		if( !empty($atts['button3_icon']) ){
			$icon = themesion_sc_icon_buttons_icon($atts['button3_icon']);
		}

		$return .= '<div class="item-download">
			<a href="' . trim($atts['button3_link']) . '" rel="noopener noreferrer">
        		' . $icon . '
				' . $text . '
			</a>
    	</div>';

	}

	if( !empty($return) ){
		$return = '<div class="download">' . $return . '</div>';
	}
	
	return $return;
}
}
add_shortcode( 'thsn-icon-buttons', 'themesion_sc_icon_buttons' );





function themesion_sc_icon_buttons_icon($icon){
	$return = '';
	$icon_class = '';
	if( !empty($icon) && ( substr($icon,0, 14 ) == 'thsn-base-icon' || substr($icon,0, 15 ) == 'thsn-itinc-icon' ) ){
		$icon_class = trim($icon);
		if( substr($icon,0, 15 ) == 'thsn-itinc-icon' && wp_style_is('thsn-itinc-icon', 'registered') ){
			wp_enqueue_style('thsn-itinc-icon');
		}
	}
	if( !empty($icon_class) ){
		$return = '<i class="'.$icon_class.'"></i>';
	}
	return $return;
}