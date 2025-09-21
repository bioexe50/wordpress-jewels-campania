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
$style		= thsn_get_base_option('service-single-style');
$single_style	= get_post_meta( get_the_ID(), 'thsn-service-single-view', true );
if( !empty($single_style) ){ $style = $single_style; }
$class_list	= 'thsn-service-single-style-'.$style;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $class_list ); ?>>
	<div class="thsn-service-single">
		<div class="thsn-service-feature-image">
			<?php thsn_get_featured_data( array( 'featured_img_only' => false, 'size' => 'full' ) ); ?>
		</div>			
		<div class="thsn-entry-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				'',
				get_the_title()
			) );
			?>
		</div><!-- .entry-content -->
		<?php
		// Prev Next Post Link
		$cpt_name = thsn_get_base_option('service-cpt-singular-title');
		the_post_navigation( array(
			'prev_text' => thsn_esc_kses( '<span class="thsn-service-nav-icon"><i class="thsn-base-icon-left-open"></i></span> <span class="thsn-service-nav-wrapper"><span class="thsn-service-nav-head">' . sprintf( esc_attr__('Previous %1$s', 'itinc') , $cpt_name ) . '</span>' ) . thsn_esc_kses( '<span class="thsn-service-nav nav-title">%title</span> </span>' ),
			'next_text' => thsn_esc_kses( '<span class="thsn-service-nav-wrapper"><span class="thsn-service-nav-head">' . sprintf( esc_attr__('Next %1$s', 'itinc') , $cpt_name ) . '</span>' ) . thsn_esc_kses( '<span class="thsn-service-nav nav-title">%title</span> </span> <span class="thsn-service-nav-icon"><i class="thsn-base-icon-right-open"></i></span>' ),
		) );
		?>
	</div>
</div><!-- #post-## -->
<?php thsn_related_service() ?>
<?php thsn_edit_link(); ?>
