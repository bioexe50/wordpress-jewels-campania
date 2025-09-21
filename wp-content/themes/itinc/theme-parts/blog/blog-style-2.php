<div class="post-item">
	<div class="thsn-featured-container">
		<?php thsn_get_featured_data( array( 'size' => 'thsn-img-770x635' ) ); ?>	
		<div class="thsn-meta-container">
			<div class="thsn-meta-date-wrapper thsn-meta-line">					
					<i class="thsn-base-icon-calendar-2"></i> <?php echo get_the_date( 'M d, Y' ); ?><span></span>
				</div>	
				<div class="thsn-meta-author-wrapper thsn-meta-line">				
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php printf( esc_attr__('Posted by %1$s','itinc'), get_the_author() ); ?>" rel="author"><i class="thsn-base-icon-user-1"></i> <?php printf( __( 'by %1$s', 'itinc' ), get_the_author() ); ?></a>
				</div>			
		</div>		
	</div>
	<div class="themesion-box-content">	
		<h3 class="thsn-post-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
		<div class="themesion-box-desc">
		<div class="thsn-read-more-link"><a href="<?php echo esc_url( get_permalink() ) ?>"><span><?php esc_html_e('Read More', 'itinc'); ?></span></a></div>					
		</div>
	</div>
</div>