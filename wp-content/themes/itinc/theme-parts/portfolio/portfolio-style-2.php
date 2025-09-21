<div class="themesion-post-item">	
	<div class="thsn-image-wrapper">
		<?php thsn_get_featured_data( array( 'featured_img_only' => true, 'size' => 'thsn-img-770x770' ) ); ?>
	</div>			
	<div class="thsn-content-wrapper">
		<div class="thsn-port-cat"><?php echo get_the_term_list( get_the_ID(), 'thsn-portfolio-category', '', ', ' ); ?></div>
		<h3 class="thsn-portfolio-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>	
		<div class="thsn-link-icon">
			<a href="<?php the_permalink(); ?>"><i class="thsn-base-icon-right-arrow"></i></a>
		</div>
	</div>
</div>