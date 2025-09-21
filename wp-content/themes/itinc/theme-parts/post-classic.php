<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.2
 */
ob_start();
thsn_get_featured_data();
$featured = ob_get_contents();
ob_end_clean();
$class = array();
if( empty($featured) ){
	$class[] = 'thsn-no-img';
}
if( !defined('ITINC_ADDON_VERSION') ){
	$class[] = 'thsn-default-view';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
	<div class="thsn-blog-classic">
		<?php if( defined('ITINC_ADDON_VERSION') ) : ?>

		<?php endif; ?> 
		<?php thsn_get_featured_data(); ?>
		<div class="thsn-blog-classic-inner">
			<?php if( function_exists('thsn_itinc_addons_init') && has_post_thumbnail() ) : ?>
			<div class="thsn-post-date"><?php echo thsn_esc_kses( thsn_meta_date() ); ?></div>
			<?php endif; ?>
			<?php
				// Meta
				$meta_html = '';
				if( !function_exists('thsn_itinc_addons_init') || !has_post_thumbnail() ) {
					$meta_html .= thsn_meta_date();
				}
				$meta_html .= thsn_meta_author();
				$meta_html .= thsn_meta_category();
				$meta_html .= thsn_meta_comment( true );
				if( get_post_format()!='status' && get_post_format()!='quote' && !is_single() ) : ?>

				<?php if( !empty($meta_html) ) : ?>
				<div class="thsn-blog-meta thsn-blog-meta-top">
					<?php echo thsn_esc_kses($meta_html); ?>
				</div>
				<?php endif; ?>
			<?php endif; ?>
			<h3 class="thsn-post-title">
				<?php if( is_single() ) : ?>
				<?php echo get_the_title(); ?>
				<?php else : ?>
				<a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
				<?php endif; ?>
			</h3>
			

			<div class="thsn-entry-content">
				<?php
				if( get_post_format()!='quote' ){
					if( is_single() ){
						/* translators: %s: Name of current post */
						the_content( sprintf(
							'',
							get_the_title()
						) );
					} else {
						$limit			= thsn_get_base_option('blog-classic-limit');
						$limit_switch	= thsn_get_base_option('blog-classic-limit-switch');
						if ( has_excerpt() ){
							the_excerpt();
							?>
							<div class="thsn-read-more-link"><a href="<?php echo esc_url( get_permalink() ) ?>"><span><?php esc_html_e('Read More', 'itinc'); ?></span></a></div>
							<?php
						} else if( $limit>0 && $limit_switch=='1' ){
							$content = get_the_content('',FALSE,'');
							$content = wp_strip_all_tags($content);
							$content = strip_shortcodes($content);
							echo thsn_esc_kses( wp_trim_words($content, $limit) );
							?>
							<div class="thsn-read-more-link"><a href="<?php echo esc_url( get_permalink() ) ?>"><span><?php esc_html_e('Read More', 'itinc'); ?></span></a></div>
							<?php
						} else {
							/* translators: %s: Name of current post */
							the_content( sprintf(
								'',
								get_the_title()
							) );
							if( !is_single() ){
								global $post;
								if( strpos( $post->post_content, '<!--more-->' ) ) {
									?>
									<div class="thsn-read-more-link">
										<a href="<?php echo esc_url( get_permalink() ) ?>"><span><?php esc_html_e('Read More','itinc') ?></span></a>
									</div>
									<?php
								}
							}
						}
						?>
						<?php
					}
				}
				wp_link_pages( array(
					'before'      => '<div class="page-links">' . esc_attr__( 'Pages:', 'itinc' ),
					'after'       => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				) );
				?>
			</div><!-- .entry-content -->

			<?php get_template_part( 'theme-parts/post', 'bottom-meta' ); ?>

		</div>

	</div>

	<?php if( is_single() ) : ?>
		<?php get_template_part( 'theme-parts/post', 'author-info' ); ?>
		<?php thsn_related_post() ?>
	<?php endif; ?>
</article><!-- #post-## -->