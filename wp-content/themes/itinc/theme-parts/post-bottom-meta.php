<?php
/**
 * Template part for displaying post meta
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
$meta_array = array();
ob_start();
thsn_blog_social_share();
$social = ob_get_contents();
ob_end_clean();
$social = trim($social);
?>
<?php if ( is_single() && ( !empty($social) || has_tag() ) ) { ?>
	<div class="thsn-blog-meta thsn-blog-meta-bottom <?php if( empty($social) ) : ?> thsn-blog-meta-no-social<?php endif; ?>">
		<?php if( has_tag() ) : ?>
		<div class="thsn-blog-meta-bottom-left">
			<?php echo thsn_esc_kses( thsn_meta_tag(', ', '<span class="thsn-meta-title">' . esc_html__('Tags:', 'itinc') . '</span>' ) ); // Tag Meta ?>
		</div>
		<?php endif; ?>
		<?php if( !empty($social) ) : ?>
		<div class="thsn-blog-meta-bottom-right">
			<?php thsn_blog_social_share(); ?>
		</div>
		<?php endif; ?>
	</div>
<?php } ?>