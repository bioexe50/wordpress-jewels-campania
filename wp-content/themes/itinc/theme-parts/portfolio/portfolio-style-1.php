<div class="themesion-post-content">
	<?php thsn_get_featured_data( array( 'featured_img_only' => true, 'size' => 'thsn-img-770x770' ) ); ?>
	<div class="themesion-icon-box themesion-media-link">			  	
		<?php if( has_post_thumbnail() ): ?>
		<a class="thsn-lightbox" title="<?php the_title_attribute(); ?>" href="<?php echo get_the_post_thumbnail_url(); ?>"><i class="thsn-base-icon-plus"></i></a>
		<?php endif; ?>
	</div>
	<div class="themesion-box-content">
		<div class="themesion-titlebox">
			<div class="thsn-port-cat"><?php echo get_the_term_list( get_the_ID(), 'thsn-portfolio-category', '', ', ' ); ?></div>
			<h3 class="thsn-portfolio-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
		</div>
	</div>
</div>
