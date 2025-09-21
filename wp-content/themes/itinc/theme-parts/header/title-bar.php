<?php
/**
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
$titlebar_enable = thsn_get_base_option('titlebar-enable');
if( is_page() || is_singular()  || is_home() ){
	$page_id = get_the_ID();
	if( is_home() ){
		$page_id = get_option( 'page_for_posts');
	}
	$post_meta = get_post_meta( $page_id, 'thsn-titlebar-hide', true );
	if( $post_meta=='1' ){
		$titlebar_enable = 0;
	}
}
if( $titlebar_enable==1 ) :
	?>
	<div class="thsn-title-bar-wrapper <?php thsn_titlebar_class(); ?>">
		<div class="container">
			<div class="thsn-title-bar-content">
				<div class="thsn-title-bar-content-inner">
					<?php echo thsn_titlebar_headings(); ?>
					<?php echo thsn_titlebar_breadcrumb(); ?>
				</div>
			</div><!-- .thsn-title-bar-content -->
		</div><!-- .container -->
	</div><!-- .thsn-title-bar-wrapper -->
<?php endif; ?>