<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.2
 */

$footer_widget_columns	= thsn_footer_widget_columns(); // array
$widget_exists			= $footer_widget_columns[0];
$footer_columns			= $footer_widget_columns[1];
$footer_column			= $footer_widget_columns[2];
?>
				<?php if( thsn_check_sidebar() == true ){ ?>
					</div><!-- .row -->
				<?php } ?>
				</div><!-- #content -->
			</div><!-- .site-content-wrap -->
		<footer id="colophon" class="thsn-footer-section site-footer <?php thsn_footer_classes(); ?>">
			<?php thsn_footer_boxes_area(); ?>
			<?php if( $widget_exists==true ) : ?>
			<div class="thsn-footer-section footer-wrap thsn-footer-widget-area <?php thsn_footer_widget_classes(); ?>">
				<div class="container">
					<div class="row">
						<?php 
						$col = 1;
						foreach( $footer_columns as $column ){
							$class = ( $footer_column == '3-3-3-3' ) ? 'col-md-6 col-lg-3' : 'col-md-'.$column ;
							if ( is_active_sidebar( 'thsn-footer-'.$col ) ) { ?>
								<div class="thsn-footer-widget thsn-footer-widget-col-<?php echo esc_attr($col); ?> <?php echo esc_attr($class); ?>">
									<?php dynamic_sidebar( 'thsn-footer-'.$col ); ?>
								</div><!-- .thsn-footer-widget -->
							<?php };
							$col++;
						} // end foreach
						?>
					</div><!-- .row -->
				</div>	
			</div>
			<?php endif; ?>
			<div class="thsn-footer-section thsn-footer-text-area <?php thsn_footer_copyright_classes(); ?>">
				<div class="container">
					<div class="thsn-footer-text-inner">
						<div class="row">
							<?php thsn_footer_copyright_area(); ?>
						</div>
					</div>	

				</div>
			</div>
		</footer><!-- #colophon -->
	</div><!-- .site-content-contain -->
</div><!-- #page -->
<?php 
// Hide Totop Button
$hide_totop_button  = thsn_get_base_option('hide_totop_button');
if($hide_totop_button != 1 ){ ?>
<a href="#" title="<?php echo esc_html_e( 'Back to Top', 'itinc' ); ?>" class="scroll-to-top"><i class="thsn-base-icon-up-open-big"></i></a>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>
