<?php

// [thsn-social-links tooltip="left|right|top|bottom|up|down" colorful="yes|no"]
if( !function_exists('themesion_sc_social_links') ){
function themesion_sc_social_links( $atts, $content = "" ) {

	$return = '';

	//
	//$atts['tooltip'] = array('left-right-top-bottom-up-down');
	$data = '';
	if( !empty($atts['tooltip']) && $atts['tooltip'] == 'bottom' ){
		$atts['tooltip'] = 'down';
	}
	if( !empty($atts['tooltip']) && $atts['tooltip'] == 'top' ){
		$atts['tooltip'] = 'up';
	}
	if( !empty($atts['tooltip']) && in_array( $atts['tooltip'], array( 'left', 'right', 'top', 'bottom', 'down', 'up' ) ) ){
		$data .= 'data-balloon-pos="'.$atts['tooltip'].'"';
	}

	$colorful = '';
	if( !empty($atts['colorful']) && $atts['colorful']=='yes' ){
		$colorful = ' thsn-colorful';
	}

	if( function_exists('thsn_social_links_list') ){
		$social_list = thsn_social_links_list();
		if( is_array($social_list) ){
			foreach( $social_list as $social ){
				$data_balloon = '';

				// Tooltip
				if( !empty($data) ){ $data_balloon = ' data-balloon="'.$social['label'].'"'; }

				$link = thsn_get_base_option( $social['id'] );
				if( !empty($link) ){
					$return .= '<li class="thsn-social-li thsn-social-'.$social['id'].''.$colorful.'"><a title="'.$social['label'].'"  '.$data.''.$data_balloon.' href="'.esc_url($link).'" target="_blank"><span><i class="'.$social['icon_class'].'"></i></span></a></li>';
				}
			}
		}
	}

	if( !empty($return) ){
		$return = '<ul class="thsn-social-links">'.$return.'</ul>';
	}

	return $return;

}
}
add_shortcode( 'thsn-social-links', 'themesion_sc_social_links' );
