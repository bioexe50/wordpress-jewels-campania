<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
?>
<?php
$sidebar	= 'thsn-sidebar-post';
$aria_label	= esc_attr__( 'Blog Sidebar', 'itinc' );
if( is_page() ){
	// page sidebar
	$sidebar	= 'thsn-sidebar-page';
	$aria_label	= esc_attr__( 'Page Sidebar', 'itinc' );
	if( function_exists('is_woocommerce') && is_woocommerce() ){
		$sidebar	= 'thsn-sidebar-wc-shop';
		$aria_label	= esc_attr__( 'WooCommerce Sidebar', 'itinc' );
	}
} else if( is_search() ){
	$sidebar	= 'thsn-sidebar-search';
	$aria_label	= esc_attr__( 'Search Results Sidebar', 'itinc' );
} else if( function_exists('is_woocommerce') && is_woocommerce() && !is_product() ){
	$sidebar	= 'thsn-sidebar-wc-shop';
	$aria_label	= esc_attr__( 'WooCommerce Sidebar', 'itinc' );
} else if( function_exists('is_product') && is_product() ){
	$sidebar	= 'thsn-sidebar-wc-single';
	$aria_label	= esc_attr__( 'WooCommerce Sidebar', 'itinc' );
} else if( is_singular('thsn-portfolio') ){
	$sidebar		= 'thsn-sidebar-portfolio';
	$aria_label		= esc_attr__( 'Portfolio Sidebar', 'itinc' );
} else if( is_tax('thsn-portfolio-category') || is_post_type_archive('thsn-portfolio') ){
	$sidebar		= 'thsn-sidebar-portfolio-cat';
	$aria_label		= esc_attr__( 'Portfolio Category Sidebar', 'itinc' );
} else if( is_singular('thsn-service') ){
	$sidebar		= 'thsn-sidebar-service';
	$aria_label		= esc_attr__( 'Service Sidebar', 'itinc' );
} else if( is_tax('thsn-service-category') || is_post_type_archive('thsn-service') ){
	$sidebar		= 'thsn-sidebar-service-cat';
	$aria_label		= esc_attr__( 'Service Category Sidebar', 'itinc' );
} else if( is_singular('thsn-team-member') ){
	$sidebar		= 'thsn-sidebar-team';
	$aria_label		= esc_attr__( 'Team Member Sidebar', 'itinc' );
} else if( is_tax('thsn-team-group') || is_post_type_archive('thsn-team-member') ){
	$sidebar		= 'thsn-sidebar-team-group';
	$aria_label		= esc_attr__( 'Team Group Sidebar', 'itinc' );
}
?>
<?php if ( is_active_sidebar( $sidebar ) && thsn_check_sidebar()==true ) : ?>
<aside id="secondary" class="widget-area themesion-sidebar col-md-3 col-lg-3" aria-label="<?php echo esc_attr( $aria_label ); ?>">
	<?php dynamic_sidebar( $sidebar ); ?>
</aside><!-- #secondary -->
<?php endif; ?>