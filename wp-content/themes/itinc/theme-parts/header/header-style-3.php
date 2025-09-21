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
<div class="thsn-header-overlay">
	<div class="thsn-header-height-wrapper">
		<div class="thsn-header-inner <?php thsn_header_class(); ?> <?php thsn_header_bg_class(); ?> <?php thsn_sticky_class(); ?>">
			<?php get_template_part( 'theme-parts/header/pre-header',	thsn_get_base_option('header-style') ); ?>
			<div class="container">
				<div class="d-flex justify-content-between align-items-center thsn-header-content">
					<div class="thsn-logo-menuarea">
						<div class="site-branding thsn-logo-area">
							<div class="wrap">
								<!-- Logo area -->
								<?php echo thsn_logo(); ?>
							</div><!-- .wrap -->
						</div><!-- .site-branding -->						
						<!-- Top Navigation Menu -->
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
					</div>
					<div class="thsn-right-box">
						<div class="thsn-search-cart-box">
							<?php thsn_cart_icon("2"); ?>
							<?php thsn_header_search(); ?>
						</div>
						<?php thsn_header_button(); ?>
					</div>
				</div><!-- .justify-content-between -->
			</div>
		</div><!-- .thsn-header-inner -->
	</div><!-- .thsn-header-height-wrapper -->
</div>
