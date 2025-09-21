<?php
/**
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
$header_phone = thsn_get_base_option('header-phone');
?>
<div class="thsn-header-height-wrapper">
	<div class="thsn-header-inner <?php thsn_header_class(); ?> <?php thsn_header_bg_class(); ?> <?php thsn_sticky_class(); ?>">
		<div class="d-flex justify-content-between thsn-header-content">
			<div class="site-branding thsn-logo-area">
				<div class="wrap">
					<!-- Logo area -->
					<?php echo thsn_logo(); ?>
				</div><!-- .wrap -->
			</div><!-- .site-branding -->						
			<!-- Top Navigation Menu -->
			<div class="thsn-menu-topbararea">
				<?php get_template_part( 'theme-parts/header/pre-header',	thsn_get_base_option('header-style') ); ?>
				<div class="thsn-menu-inner d-flex justify-content-between align-items-center">
					<div class="navigation-top">
						<button id="menu-toggle" class="nav-menu-toggle">
							<i class="thsn-base-icon-menu"></i>
						</button>
						<div class="wrap">
							<nav id="site-navigation" class="main-navigation thsn-navbar <?php thsn_nav_class(); ?>" aria-label="<?php esc_attr_e( 'Top Menu', 'itinc' ); ?>">
								<?php wp_nav_menu( array(
									'theme_location' => 'themesion-top',
									'menu_id'        => 'thsn-top-menu',
									'menu_class'     => 'menu',
								) ); ?>
							</nav><!-- #site-navigation -->
						</div><!-- .wrap -->
					</div><!-- .navigation-top -->
					<div class="thsn-right-box">
						<div class="thsn-header-social-wrapper">
							<?php echo do_shortcode('[thsn-social-links]'); ?>
						</div>
						<div class="thsn-search-cart-box">
							<?php thsn_header_search(); ?>
							<?php thsn_cart_icon("2"); ?>
						</div>
						<?php thsn_header_button(); ?>
					</div>
				</div>
			</div>
		</div><!-- .justify-content-between -->
	</div><!-- .thsn-header-inner -->
</div><!-- .thsn-header-height-wrapper -->
