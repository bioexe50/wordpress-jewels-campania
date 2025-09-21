<?php

use Elementor\Icons_Manager;

// Icon
$icon_html = $icon_array = '';
$custom_icon_enabled = get_post_meta( get_the_ID(), 'thsn-custom-icon-enabled', true );
if( $custom_icon_enabled=='1' ){
	$img_src = '';
	$custom_icon_url = get_post_meta( get_the_ID(), 'thsn-custom-icon', true );
	if( !empty($custom_icon_url) ){
		$img_src = wp_get_attachment_image_src($custom_icon_url, 'full');
		if( !empty($img_src[0]) ){ $custom_icon_url = $img_src[0]; }
	}
	$icon_html = '<img src="'.$custom_icon_url.'"/>';
}else{
	$icon_lib = get_post_meta( get_the_ID(), 'thsn-service-icon-library', true );
	wp_enqueue_style($icon_lib);
	$icon_class = get_post_meta( get_the_ID(), 'thsn-service-icon-'.$icon_lib, true );

	// icon library name for the function
	$icon_lib2 = $icon_lib;
	if( $icon_lib == 'elementor-icons-fa-regular' ){
		$icon_lib2 = 'fa-regular';
	} else if( $icon_lib == 'elementor-icons-fa-solid' ){
		$icon_lib2 = 'fa-solid';
	} else if( $icon_lib == 'elementor-icons-fa-brands' ){
		$icon_lib2 = 'fa-brands';
	}
	$icon_array = array(
		'value' => $icon_class,
		'library' => $icon_lib2,
	);
}

// Read More text
if( !isset($more_text) ){
	$more_text = esc_attr__('Read More','itinc');
}
?>
<div class="themesion-post-item themesion-overlay-box">
	<?php thsn_get_featured_data( array( 'featured_img_only' => true, 'size' => 'thsn-img-700x750' ) ); ?>
	<div class="thsn-service-icon-wrapper">
		<?php if( !empty($icon_html)){
			echo thsn_esc_kses ( $icon_html );
		} else {
			Icons_Manager::render_icon( $icon_array, [ 'aria-hidden' => 'true' ] );
		} ?>
	</div>
	<div class="themesion-box-content">
		<div class="themesion-box-content-inner">
			<div class="thsn-service-cat"><?php echo get_the_term_list( get_the_ID(), 'thsn-service-category', '', ', ' ); ?></div>
			<h3 class="thsn-service-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
		</div>
	</div>
</div>