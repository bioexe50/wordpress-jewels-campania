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
// Class list
$style			= thsn_get_base_option('portfolio-single-style');
$single_style	= get_post_meta( get_the_ID(), 'thsn-portfolio-single-view', true );
if( !empty($single_style) ){ $style = $single_style; }
$class_list		= 'thsn-portfolio-single-style-'.$style;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $class_list ); ?>>
	<div class="thsn-portfolio-single">

		<div class="thsn-single-project-content-wrapper">
			<?php thsn_get_featured_data( array( 'featured_img_only' => false, 'size' => 'full' ) ); ?>
			<div  class="thsn-single-project-details-list">
				<?php thsn_portfolio_details_list(); ?>	
			</div>

		</div>

		<div class="row">
			<div class="col-md-12">				
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
		<?php
		// Prev Next Post Link
		$cpt_name = thsn_get_base_option('portfolio-cpt-singular-title');
		the_post_navigation( array(
			'prev_text' => thsn_esc_kses( '<span class="thsn-portfolio-nav-icon"><i class="thsn-base-icon-left-open"></i></span> <span class="thsn-portfolio-nav-wrapper"><span class="thsn-portfolio-nav-head">' . sprintf( esc_attr__('Previous %1$s', 'itinc') , $cpt_name ) . '</span>' ) . thsn_esc_kses( '<span class="thsn-portfolio-nav nav-title">%title</span> </span>' ),
			'next_text' => thsn_esc_kses( '<span class="thsn-portfolio-nav-wrapper"><span class="thsn-portfolio-nav-head">' . sprintf( esc_attr__('Next %1$s', 'itinc') , $cpt_name ) . '</span>' ) . thsn_esc_kses( '<span class="thsn-portfolio-nav nav-title">%title</span> </span> <span class="thsn-portfolio-nav-icon"><i class="thsn-base-icon-right-open"></i></span>' ),
		) );
		?>
	</div>
</article><!-- #post-## -->
<?php thsn_related_portfolio(); ?>
<?php thsn_edit_link(); ?>