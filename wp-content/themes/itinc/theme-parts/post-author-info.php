<?php
$bio = get_the_author_meta( 'description' );
if( !empty($bio) ) :
?>
	<div class="thsn-author-box">
		<?php if(get_avatar(get_the_author_meta('ID')) !== FALSE) { ?>
			<div class="thsn-author-image">
				<?php echo thsn_esc_kses( get_avatar(get_the_author_meta('ID')) ); ?>
			</div>
		<?php } ?>
		<div class="thsn-author-content">
			<span class="thsn-author-name">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php printf( esc_attr__('Posted by %1$s','itinc'), get_the_author() ); ?>" rel="author"><?php echo get_the_author(); ?></a>
			</span>
			<p class="thsn-text thsn-author-bio"><?php the_author_meta( 'description' ); ?></p>
			<?php thsn_author_social_links(); ?>
		</div>
	</div>
<?php endif; ?>