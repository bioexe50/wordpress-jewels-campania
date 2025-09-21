<?php
/**
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
?>
<?php get_template_part( 'theme-parts/header/pre-header',	thsn_get_base_option('header-style') ); ?>
<div class="thsn-header-height-wrapper" style="min-height:<?php echo thsn_get_base_option('header-height'); ?>px;">
	<div class="<?php thsn_header_class(); ?> <?php thsn_header_bg_class(); ?> <?php thsn_sticky_class(); ?>">
		<div class="container">
			<div class="d-flex justify-content-between align-items-center">
				<div class="thsn-logo-menuarea">
					<div class="site-branding thsn-logo-area">
						<div class="wrap">
							<?php echo thsn_logo(); ?><!-- Logo area -->
						</div><!-- .wrap -->
					</div><!-- .site-branding -->
					<!-- Top Navigation Menu -->
					<div class="navigation-top">
						<div class="thsn-mobile-search">
							<?php thsn_header_search(); ?>
						</div>
						<button id="menu-toggle" class="nav-menu-toggle">
							<i class="thsn-base-icon-menu-1"></i>
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
				</div>
				<div class="thsn-right-box">
					<?php thsn_header_contactinfo(); ?>
					<div class="thsn-search-cart-box">
						<?php thsn_cart_icon(); ?>
						<?php thsn_header_search(); ?>
					</div>
					<?php thsn_header_button(); ?>
				</div>
			</div><!-- .justify-content-between -->
		</div><!-- .container -->
	</div><!-- .thsn-header-wrapper -->
</div><!-- .thsn-header-height-wrapper -->
