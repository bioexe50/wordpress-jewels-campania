<?php $hover_img = thsn_client_hover_img(); ?>
<div class="thsn-client-wrapper<?php if(!empty($hover_img)){ ?> thsn-client-with-hover-img<?php } ?>">
	<h4 class="thsn-hide"><?php echo get_the_title(); ?></h4>
	<?php thsn_client_logo_link('start'); ?>
	<?php echo thsn_esc_kses(thsn_client_hover_img()); ?>
	<?php thsn_get_featured_data( array( 'size' => 'thsn-img-770x9999' ) ); ?>	
	<?php thsn_client_logo_link('end'); ?>
</div>