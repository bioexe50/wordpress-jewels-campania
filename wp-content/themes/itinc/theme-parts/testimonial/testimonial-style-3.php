<div class="themesion-post-item">
	<div class="themesion-box-content">
	<?php thsn_testimonial_star_ratings(); ?>
		<div class="themesion-box-desc">
			<blockquote class="themesion-testimonial-text"><?php the_content(''); ?></blockquote>
		</div>
		<div class="themesion-box-img d-flex align-items-center">
			<?php thsn_get_featured_data( array( 'size' => 'thumbnail' ) ); ?>
			<div class="themesion-box-author d-flex align-items-center">
				<h3 class="themesion-box-title"><?php echo get_the_title(); ?></h3>
				<?php thsn_testimonial_details(); ?>
			</div>
		</div>
	</div>	
</div>