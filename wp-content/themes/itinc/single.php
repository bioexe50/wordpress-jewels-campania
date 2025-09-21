<?php
/**
 * The template for displaying all single posts
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
		if( is_singular('thsn-portfolio') ){
			$style			= thsn_get_base_option('portfolio-single-style');
			$single_style	= get_post_meta( get_the_ID(), 'thsn-portfolio-single-view', true );
			if( !empty($single_style) ){ $style = $single_style; }
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'theme-parts/portfolio-single-style', $style );
			endwhile; // End of the loop.
		} else if( is_singular('thsn-service') ){
			$style			= thsn_get_base_option('service-single-style');
			$single_style	= get_post_meta( get_the_ID(), 'thsn-service-single-view', true );
			if( !empty($single_style) ){ $style = $single_style; }
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'theme-parts/service-single-style', $style );
			endwhile; // End of the loop.
		} else if( is_singular('thsn-team-member') ){
			$style = thsn_get_base_option('team-single-style');
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'theme-parts/team-single-style', $style );
			endwhile; // End of the loop.
		} else if( is_singular('product') ){
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'theme-parts/woocommerce-single', 'style' );
			endwhile; // End of the loop.
		} else if( is_singular('job_listing') ){
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'theme-parts/job-listing-single', 'style' );
			endwhile; // End of the loop.
		} else {
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				include( locate_template( 'theme-parts/post-classic.php', false, false ) ); 
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endwhile; // End of the loop.
		}
		?>
	</main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer();