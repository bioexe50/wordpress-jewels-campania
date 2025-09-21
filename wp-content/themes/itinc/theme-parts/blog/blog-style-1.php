<div class="post-item">
	<div class="thsn-featured-container">
		<?php thsn_get_featured_data( array( 'size' => 'thsn-img-770x500' ) ); ?>	
					
	</div>
	<div class="themesion-box-content">	

		<div class="thsn-meta-date-wrapper thsn-meta-line">					
			<?php echo thsn_esc_kses( thsn_meta_date() ); ?><span></span>
		</div>
			
			
		<div class="thsn-meta-container">	

				<div class="thsn-meta-author-wrapper thsn-meta-line">				
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php printf( esc_attr__('Posted by %1$s','itinc'), get_the_author() ); ?>" rel="author"><i class="thsn-base-icon-user-1"></i> <?php printf( __( 'by %1$s', 'itinc' ), get_the_author() ); ?></a>
				</div>	

				<div class="thsn-meta-category-wrapper thsn-meta-line">		
					<div class="thsn-meta-category"><i class="thsn-base-icon-folder-open-empty"></i> <?php echo get_the_category_list( ', ' ); ?></div>
				</div>	

		</div>
		<h3 class="thsn-post-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
		<div class="themesion-box-desc">
		<div class="themesion-box-desc-text">
				<?php
				$limit			= thsn_get_base_option('blog-element-limit');
				$limit_switch	= thsn_get_base_option('blog-element-limit-switch');
				if ( has_excerpt() ){
					the_excerpt();
				} else if( $limit>0 && $limit_switch=='1' ){
					$content = get_the_content('',FALSE,'');
					$content = wp_strip_all_tags($content);
					$content = strip_shortcodes($content);
					echo thsn_esc_kses( wp_trim_words($content, $limit) );
				} else { 
					the_content( '' );
				}
				?>					
			</div>				
		</div>
	</div>
</div>