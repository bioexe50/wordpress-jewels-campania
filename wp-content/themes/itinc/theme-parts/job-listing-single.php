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
		<div class="thsn-blog-classic-inner">
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