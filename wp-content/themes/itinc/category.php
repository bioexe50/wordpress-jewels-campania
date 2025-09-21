<?php
/**
 * The template for displaying category pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
get_header(); ?>
<div id="primary" class="content-area <?php if( thsn_check_sidebar() ) { ?>col-md-9 col-lg-9<?php } ?>">
	<main id="main" class="site-main">
		<?php
		if ( have_posts() ) :
			$blogroll_view		= thsn_get_base_option('blogroll-view');
			$blogroll_column	= thsn_get_base_option('blogroll-column');
			$style				= str_replace('style-','', $blogroll_view );
			if($style!='classic') { ?>
				<div class="thsn-element-posts-wrapper row multi-columns-row">
			<?php }
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				if( $style=='classic' ){
					get_template_part( 'theme-parts/post', 'classic' );
				} else {
					if( file_exists( locate_template( '/theme-parts/blog/blog-style-'.esc_attr($style).'.php', false, false ) ) ){
						echo thsn_element_block_container( array(
							'position'	=> 'start',
							'column'	=> $blogroll_column,
							'style'     => $style,
						) );
						// calling template
						include( locate_template( '/theme-parts/blog/blog-style-'.esc_attr($style).'.php', false, false ) );
						echo thsn_element_block_container( array(
							'position'	=> 'end',
						) );
					} else {
						echo '<!-- Template file not found -->';
					}
				} // else
			endwhile;
			if($style!='classic') { ?>
				</div><!-- .thsn-element-posts-wrapper -->
			<?php }
			// Pagination
			thsn_pagination();
		else :
			get_template_part( 'template-parts/post/content', 'none' );
		endif;
		?>
	</main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer();
