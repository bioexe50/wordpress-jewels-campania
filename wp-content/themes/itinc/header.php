<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if( !function_exists('btc_get_color') ){ ?>
<?php $global_color = thsn_get_base_option('global-color'); ?>
<!-- browser-theme-color for WordPress -->
<meta name="theme-color" content="<?php echo esc_attr($global_color); ?>">
<meta name="msapplication-navbutton-color" content="<?php echo esc_attr($global_color); ?>">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<?php } ?>
<link rel="profile" href="<?php echo esc_url('https://gmpg.org/xfn/11') ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php thsn_preloader(); ?>
<?php 
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}
?>
<div id="page" class="site thsn-parent-header-style-<?php echo esc_attr(thsn_get_base_option('header-style')); ?>">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'itinc' ); ?></a>
	<header id="masthead" class="site-header thsn-header-style-<?php echo esc_attr(thsn_get_base_option('header-style')); ?>">
		<?php get_template_part( 'theme-parts/header/header-style',	thsn_get_base_option('header-style') ); ?>
		<?php thsn_header_slider(); ?>
		<?php get_template_part( 'theme-parts/header/title-bar',	thsn_get_base_option('header-style') ); ?>
	</header><!-- #masthead -->
	<div class="site-content-contain <?php thsn_site_content_class(); ?>">
		<div class="site-content-wrap">
			<div id="content" class="site-content container">
				<?php if( thsn_check_sidebar() == true ){ ?>
					<div class="row multi-columns-row">
				<?php } ?>

				<?php
				$unique_id		= esc_attr( uniqid( 'search-form-' ) ); 
				$placeholder	= thsn_get_base_option('header-search-placeholder');
				$btn_text		= thsn_get_base_option('header-search-btn-text');
				?>
				<div class="thsn-header-search-form-wrapper">
					<div class="thsn-search-close"><i class="thsn-base-icon-cancel"></i></div>
					<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>
					<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" aria-label="<?php echo esc_attr($placeholder); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s" />
						<button type="submit" title="<?php echo esc_html_x( 'Search', 'submit button', 'itinc' ); ?>" class="search-submit"><?php echo esc_html($btn_text); ?></button>
					</form>
				</div>