<?php
/**
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
$preheader_enable = thsn_get_base_option('preheader-enable');
if( $preheader_enable==1 ) :
	?>
	<div class="thsn-pre-header-wrapper <?php thsn_preheader_class(); ?>">
		<div class="container">
			<div class="d-flex justify-content-between">
				<?php
				$preheader_left = thsn_get_base_option('preheader-left');
				if( !empty($preheader_left) ){ ?>
					<div class="thsn-pre-header-left"><?php echo thsn_esc_kses( do_shortcode($preheader_left) ); ?></div><!-- .thsn-pre-header-left -->
				<?php } ?>
				<?php
				$preheader_right = thsn_get_base_option('preheader-right');
				$preheader_search = thsn_get_base_option('preheader-search');
				if( !empty($preheader_right) || $preheader_search==true ){ ?>
					<div class="thsn-pre-header-right">
						<?php if( !empty($preheader_right) ) { echo thsn_esc_kses( do_shortcode($preheader_right) ); } ?>
						<?php if( $preheader_search==true ) { echo thsn_esc_kses( '<div class="thsn-header-search-btn"><a title="'.esc_attr__( 'Search', 'itinc' ).'" href="#"><i class="thsn-base-icon-search"></i></a></div>' ); } ?>
					</div><!-- .thsn-pre-header-right -->
				<?php } ?>
			</div><!-- .justify-content-between -->
		</div><!-- .container -->
	</div><!-- .thsn-pre-header-wrapper -->
<?php endif; ?>