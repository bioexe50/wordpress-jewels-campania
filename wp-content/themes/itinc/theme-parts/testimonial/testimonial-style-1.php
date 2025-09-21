<div class="themesion-post-item">
	<div class="themesion-box-content d-md-flex">
	<div class="themesion-box-img">
			<?php thsn_get_featured_data( array( 'size' => 'thumbnail' ) ); ?>
			<div class="themesion-box-author">
				<h3 class="themesion-box-title"><?php echo get_the_title(); ?></h3>
				<?php thsn_testimonial_details(); ?>
			</div>
		</div>
		<div class="themesion-box-desc">
			<blockquote class="themesion-testimonial-text"><?php the_content(''); ?></blockquote>
			<div class="themesion-box-star-ratings">
				<?php thsn_testimonial_star_ratings(); ?>
			</div>
		</div>		 
	</div>	
</div>