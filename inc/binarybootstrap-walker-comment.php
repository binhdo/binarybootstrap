<?php
/**
 * HTML comment list class.
 *
 * @package WordPress
 * @uses Walker
 * @since 2.7.0
 */
class BinaryBootstrap_Walker_Comment extends Walker_Comment {
	/**
	 * @see Walker::start_lvl()
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of comment.
	 * @param array $args Uses 'style' argument for type of HTML list.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		echo "<ul class='media list-unstyled comment-" . get_comment_ID() . " children'>\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of comment.
	 * @param array $args Will only append content if style argument value is 'ol' or 'ul'.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		echo "</ul>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $comment Comment data object.
	 * @param int $depth Depth of comment in reference to parents.
	 * @param array $args
	 */
	function start_el( &$output, $comment, $depth, $args, $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty($args['callback']) ) {
			call_user_func($args['callback'], $comment, $args, $depth);
			return;
		}

		extract($args, EXTR_SKIP);

		?>
<li class="media comment-<?php comment_ID() ?>"><?php echo get_avatar( $comment, $size = '64' ); ?>
	<div class="media-body">
		<h4 class="media-heading">
			<?php echo get_comment_author_link(); ?>
		</h4>
		<time datetime="<?php echo comment_date( 'c' ); ?>">
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s', 'binarybootstrap' ), get_comment_date(),  get_comment_time() ); ?></a>
		</time>
		<?php edit_comment_link(__('(Edit)', 'binarybootstrap'), '', ''); ?>

		<?php if ($comment->comment_approved == '0') : ?>
		<div class="alert">
			<?php _e('Your comment is awaiting moderation.', 'binarybootstrap'); ?>
		</div>
		<?php endif; ?>

		<?php comment_text() ?>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
		</div>
		<?php
	}

	/**
	 * @see Walker::end_el()
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $comment
	 * @param int $depth Depth of comment.
	 * @param array $args
	 */
	function end_el(&$output, $comment, $depth = 0, $args = array() ) {
		if ( !empty($args['end-callback']) ) {
			call_user_func($args['end-callback'], $comment, $args, $depth);
			return;
		}
		echo "</li>\n";
	}

}
