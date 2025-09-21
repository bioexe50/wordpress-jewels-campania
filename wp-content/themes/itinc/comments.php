<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ITinc
 * @since 1.0
 * @version 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( _x( 'One Reply to &ldquo;%1$s&rdquo;', 'one comment title', 'itinc' ), get_the_title() );
			} else if ( '1' < $comments_number ) {
				printf( _x( '%2$s Replies to &ldquo;%1$s&rdquo;', 'multiple comments title', 'itinc' ), get_the_title(), $comments_number );
			}
			?>
		</h2>
		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'avatar_size' => 100,
					'style'       => 'ol',
					'short_ping'  => true,
					'reply_text'  => thsn_esc_kses( '<i class=="themesion-base-icon-reply"></i> ' ) . esc_attr__( 'Reply', 'itinc' ),
					'callback'    => 'thsn_comments_list_template',
				) );
			?>
		</ol>
		<?php the_comments_pagination( array(
			'mid_size'	=> 5,
			'prev_text'	=> thsn_esc_kses('<i class="thsn-base-icon-left-open"></i>'),
			'next_text'	=> thsn_esc_kses('<i class="thsn-base-icon-right-open"></i>'),
		) );
	endif; // Check for have_comments().
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'itinc' ); ?></p>
	<?php
	endif;
	comment_form();
	?>
</div><!-- #comments -->
