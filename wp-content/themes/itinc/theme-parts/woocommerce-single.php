<?php
/**
 * Template part for displaying WooCommerce Single product view
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.2
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="thsn-wc-single-classic">
		<?php thsn_get_featured_data(); ?>
		<div class="thsn-wc-single-classic-inner">			
			<div class="thsn-entry-content">
				<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					'',
					get_the_title()
				) );
				?>
			</div><!-- .entry-content -->
		</div>
	</div>
</article><!-- #post-## -->