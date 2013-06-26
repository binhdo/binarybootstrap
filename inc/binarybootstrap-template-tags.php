<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */

/**
 * #primary .content-area
 *
 * @param string $has_sidebar
 * @return string
 */
function binarybootstrap_primary_class($has_sidebar = true) {
	if ( $has_sidebar && is_active_sidebar( 'sidebar-1' ) ) {
		$class = 'col-lg-8';
	} else {
		$class = 'col-lg-12';
	}

	return $class;
}

/**
 * #secondary .widget-area class
 *
 * @return string
 */
function binarybootstrap_secondary_class() {
	return 'col-lg-4';
}

/**
 * #tertiary .widget-area class
 *
 * @return string
 */
function binarybootstrap_tertiary_class() {
	$widgets = wp_get_sidebars_widgets();
	$num_widgets = count( $widgets['sidebar-2'] );

	$a = $num_widgets > 0 ? floor( 12 / $num_widgets ) : 1;

	$class = $a < 3 ? 'col-lg-3' : 'col-lg-' . $a;

	return $class;
}

/**
 * Outputs a complete commenting form for use within a template.
 * Most strings and form fields may be controlled through the $args array passed
 * into the function, while you may also choose to use the comment_form_default_fields
 * filter to modify the array of default fields if you'd just like to add a new
 * one or remove a single field. All fields are also individually passed through
 * a filter of the form comment_form_field_$name where $name is the key used
 * in the array of fields.
 *
 * @since 3.0.0
 * @param array $args Options for strings, fields etc in the form
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 * @return void
 */
function binarybootstrap_comment_form($args = array(), $post_id = null) {
	global $id;

	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields = array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' placeholder="' . __( 'Your Name', 'binarybootstrap' ) . '"></p>',
		'email' => '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . $aria_req . ' placeholder="' . __( 'Your Email address', 'binarybootstrap' ) . '"></p>',
		'url' => '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label>' .
		'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Your website', 'binarybootstrap' ) . '"></p>',
	);

	$required_text = sprintf( ' ' . __( 'Required fields are marked %s' ), '<span class="required">*</span>' );
	$defaults = array(
		'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" rows="6" aria-required="true"></textarea></p>',
		'must_log_in' => '<div class="alert alert-error fade in must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</div>',
		'logged_in_as' => '<div class="alert logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</div>',
		'comment_notes_before' => '<div class="alert alert-info fade in comment-notes"><a class="close" data-dismiss="alert" href="#">&times;</a>' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</div>',
		'comment_notes_after' => '<div class="alert alert-info fade in form-allowed-tags"><a class="close" data-dismiss="alert" href="#">&times;</a>' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <pre>' . allowed_tags() . '</pre>' ) . '</div>',
		'id_form' => 'commentform',
		'id_submit' => 'submit',
		'title_reply' => __( 'Leave a Reply' ),
		'title_reply_to' => __( 'Leave a Reply to %s' ),
		'cancel_reply_link' => __( 'Cancel reply' ),
		'label_submit' => __( 'Post Comment' ),
	);

	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );
	?>
	<?php if ( comments_open( $post_id ) ) : ?>
		<?php do_action( 'comment_form_before' ); ?>
		<div id="respond">
			<legend id="reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></legend>
			<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
				<?php echo $args['must_log_in']; ?>
				<?php do_action( 'comment_form_must_log_in_after' ); ?>
			<?php else : ?>
				<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
					<?php do_action( 'comment_form_top' ); ?>
					<?php if ( is_user_logged_in() ) : ?>
						<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
						<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
					<?php else : ?>
						<?php echo $args['comment_notes_before']; ?>
						<?php
						do_action( 'comment_form_before_fields' );
						foreach ( (array) $args['fields'] as $name => $field ) {
							echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
						}
						do_action( 'comment_form_after_fields' );
						?>
					<?php endif; ?>
					<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
					<?php echo $args['comment_notes_after']; ?>
					<p class="form-submit">
						<input class="btn btn-primary btn-large" name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>">
						<?php comment_id_fields( $post_id ); ?>
					</p>
					<?php do_action( 'comment_form', $post_id ); ?>
				</form>
			<?php endif; ?>
		</div><!-- #respond -->
		<?php do_action( 'comment_form_after' ); ?>
	<?php else : ?>
		<?php do_action( 'comment_form_comments_closed' ); ?>
	<?php endif; ?>
	<?php
}

/**
 * Displays a fully fledged responsive Bootstrap Navbar
 *
 * @param string $location
 * @param string $class
 * @param string $brand_text
 * @param string $brand_url
 */
function binarybootstrap_nav_menu($location, $class, $brand_text = null, $brand_url = null) {
	if ( has_nav_menu( $location ) ) {
		$args = array(
			'theme_location' => $location,
			'container' => false,
			'depth' => 2,
			'walker' => new BinaryBootstrap_Walker_Nav_Menu(),
			'items_wrap' => '<ul id="%1$s" class="nav navbar-nav %2$s">%3$s</ul>'
		);
		if ( !$brand_url )
			$brand_url = home_url( '/' );
		?>
		<nav class="<?php echo $class; ?>" role="navigation">
			<div class="container">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<?php if ( $brand_text ) : ?>
					<a class="navbar-brand" href="<?php echo esc_url( $brand_url ); ?>"><?php echo esc_attr( $brand_text ); ?> </a>
				<?php endif; ?>
				<div class="nav-collapse collapse navbar-responsive-collapse">
					<?php wp_nav_menu( $args ); ?>
				</div>
			</div>
		</nav>
		<?php
	}
}

/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_content_nav() {
	global $wp_query, $wp_rewrite;

	if ( ( is_home() || is_archive() || is_search() ) && $wp_query->max_num_pages > 1 ) {
		$paged = ( get_query_var( 'paged' )) ? intval( get_query_var( 'paged' ) ) : 1;

		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args = array();
		$url_parts = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}
		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format = ($wp_rewrite->using_index_permalinks() AND !strpos( $pagenum_link, 'index.php' )) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		$links = paginate_links( array(
			'base' => $pagenum_link,
			'format' => $format,
			'total' => $wp_query->max_num_pages,
			'current' => $paged,
			'mid_size' => 3,
			'type' => 'array',
			'prev_text' => __( '&laquo;' ),
			'next_text' => __( '&raquo;' ),
			'add_args' => array_map( 'urlencode', $query_args )
				) );

		if ( $links ) {
			$page_links = "<ul class='pagination page-numbers'>\n\t<li>";
			$page_links .= join( "</li>\n\t<li>", $links );
			$page_links .= "</li>\n</ul>\n";

			echo "<nav class=\"clearfix\">\n{$page_links}\n</nav>";
		}
	} elseif ( is_single() ) {
		echo '<ul class="pager">';
		previous_post_link( '<li class="previous nav-previous">%link</li>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'binarybootstrap' ) . '</span> %title' );
		next_post_link( '<li class="next nav-next">%link</ul>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'binarybootstrap' ) . '</span>' );
		echo '</ul>';
	}
}

/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own binarybootstrap_entry_meta() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function binarybootstrap_entry_meta() {
	if ( is_sticky() && is_home() && !is_paged() )
		echo '<span class="glyphicon glyphicon-fire"></span > <span class="featured-post">' . __( 'Sticky', 'binarybootstrap' ) . '</span> ';

	if ( !has_post_format( 'aside' ) && !has_post_format( 'link' ) && 'post' == get_post_type() )
		binarybootstrap_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'binarybootstrap' ) );
	if ( $categories_list ) {
		echo '<span class="glyphicon glyphicon-paperclip"></span > <span class="categories-links">' . $categories_list . '</span> ';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'binarybootstrap' ) );
	if ( $tag_list ) {
		echo '<span class="glyphicon glyphicon-tags"></span > <span class="tags-links">' . $tag_list . '</span> ';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="glyphicon glyphicon-user"></span > <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span> ', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_attr( sprintf( __( 'View all posts by %s', 'binarybootstrap' ), get_the_author() ) ), get_the_author()
		);
	}
}

/**
 * Prints HTML with date information for current post.
 *
 * Create your own binarybootstrap_entry_date() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param boolean $echo Whether to echo the date. Default true.
 * @return string
 */
function binarybootstrap_entry_date($echo = true) {
	$format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'binarybootstrap' ) : '%2$s';

	$date = sprintf( '<span class="glyphicon glyphicon-time"></span > <span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span> ', esc_url( get_permalink() ), esc_attr( sprintf( __( 'Permalink to %s', 'binarybootstrap' ), the_title_attribute( 'echo=0' ) ) ), esc_attr( get_the_date( 'c' ) ), esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}

/**
 * Prints the attached image with a link to the next attached image.
 */
function binarybootstrap_the_attached_image() {
	$post                = get_post();
	$attachment_size     = apply_filters( 'binarybootstrap_attachment_size', array( 1170, 9999 ) );
	$next_attachment_url = wp_get_attachment_url();

	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachments = array_values( get_children( array(
		'post_parent'    => $post->post_parent,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) ) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachments ) > 1 ) {
		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID )
				break;
		}
		$k++;

		// get the URL of the next image attachment...
		if ( isset( $attachments[ $k ] ) )
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( $attachments[0]->ID );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
