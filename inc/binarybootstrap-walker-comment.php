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
		
		echo '<ul class="children media list-unstyled">' . "\n";
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
		
		echo "</ul><!-- .children .media -->\n";
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
	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty( $args['callback'] ) ) {
			call_user_func( $args['callback'], $comment, $args, $depth );
			return;
		}

		if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
			$this->ping( $comment, $depth, $args );
		} else {
			$this->html5_comment( $comment, $depth, $args );
		}
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
	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( !empty( $args['end-callback'] ) ) {
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			return;
		}
		
		echo "</div></li><!-- #comment-## -->\n";
	}

	/**
	 * @since 3.6
	 * @access protected
	 *
	 * @param object $comment
	 * @param int $depth Depth of comment.
	 * @param array $args
	 */
	protected function ping( $comment, $depth, $args ) {
?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'media list-unstyled' ); ?>>
			<div class="media-body">
				<?php _e( 'Pingback:' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
<?php
	}

	/**
	 * @since 3.6
	 * @access protected
	 *
	 * @param object $comment Comment to display.
	 * @param int $depth Depth of comment.
	 * @param array $args Optional args.
	 */
	protected function html5_comment( $comment, $depth, $args ) {
?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? 'media' : 'parent media' ); ?>>
		<?php echo get_avatar($comment, $size = '64'); ?>
		<div class="media-body">
			<h4 class="media-heading"><?php echo get_comment_author_link(); ?></h4>
			<time datetime="<?php comment_time( 'c' ); ?>">
				<?php printf( _x( '%1$s at %2$s', '1: date, 2: time' ), get_comment_date(), get_comment_time() ); ?>
			</time>
			<?php if ( '0' == $comment->comment_approved ) : ?>
			<div class="comment-awaiting-moderation alert">
				<?php _e( 'Your comment is awaiting moderation.' ); ?>
			</div>
			<?php endif; ?>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
			<?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>

<?php
	}
}
