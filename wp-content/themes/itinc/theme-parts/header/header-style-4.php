<?php
/**
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
?>
<div class="<?php thsn_header_class(); ?>">
	<?php get_template_part( 'theme-parts/header/pre-header',	thsn_get_base_option('header-style') ); ?>
	<div class="thsn-header-top-area <?php thsn_header_class(); ?> <?php thsn_header_bg_class(); ?> ">
		<div class="container">
			<div class="d-flex align-items-center">
				<div class="site-branding thsn-logo-area">
					<div class="wrap"><?php echo thsn_logo(); ?></div><!-- .wrap -->
				</div><!-- .site-branding -->
				<div class="thsn-header-info ml-auto d-flex">
					<div class="thsn-header-info-inner">
						<?php thsn_header_box_contents( array( 'icon'=>'yes' ) ); ?>
					</div>
					<?php thsn_header_button(); ?>				
				</div>
				<div class="thsn-mobile-search">
					<?php thsn_header_search(); ?>
				</div>
				<button id="menu-toggle" class="nav-menu-toggle">
					<i class="thsn-base-icon-menu"></i>
				</button>
			</div>
		</div>
		<div class="thsn-header-menu">
			<div class="thsn-header-menu-area-wrapper">
				<div class="thsn-header-menu-area <?php thsn_sticky_class(); ?> thsn-bg-color-<?php echo thsn_get_base_option('menu-bgcolor'); ?>">	
					<div class="container">						
						<div class="thsn-header-menu-area-inner d-flex align-items-center justify-content-between">
							<?php if ( has_nav_menu( 'themesion-top' ) ) : ?>
							<div class="navigation-top">
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
							<?php endif; ?>
							<div class="thsn-right-box">
								<div class="thsn-search-cart-box">
									<?php thsn_header_search(); ?>
									<?php thsn_cart_icon("2"); ?>
								</div>
								<div class="thsn-header-social-wrapper">
									<?php echo do_shortcode('[thsn-social-links]'); ?>
								</div>
							</div>
						</div>
					</div>
				</div><!-- .thsn-header-menu-area -->
			</div><!-- .thsn-header-menu-area-wrapper -->
		</div>
	</div>
</div><!-- .thsn-header-wrapper -->
