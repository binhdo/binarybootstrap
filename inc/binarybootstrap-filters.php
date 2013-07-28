<?php

/**
 * Custom functions that act independently of the theme templates
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 0.1
 */

/**
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses binarybootstrap_fonts_url() to get the Google Font stylesheet URL.
 *
 * @since Binary Bootstrap 1.0
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string The filtered CSS paths list.
 */
function binarybootstrap_mce_css($mce_css) {
	$fonts_url = binarybootstrap_fonts_url();

	if ( empty( $fonts_url ) )
		return $mce_css;

	if ( !empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $fonts_url ) );

	return $mce_css;
}

add_filter( 'mce_css', 'binarybootstrap_mce_css' );

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Binary Bootstrap 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function binarybootstrap_wp_title($title, $sep) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'binarybootstrap' ), max( $paged, $page ) );

	return $title;
}

add_filter( 'wp_title', 'binarybootstrap_wp_title', 10, 2 );

/**
 * Extends the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since Binary Bootstrap 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function binarybootstrap_body_class($classes) {
	if ( !is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && !is_attachment() && !is_404() )
		$classes[] = 'sidebar';

	if ( !get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	if ( has_nav_menu( 'top_nav' ) )
		$classes[] = 'top-nav-fixed';

	return $classes;
}

add_filter( 'body_class', 'binarybootstrap_body_class' );

/**
 * Add Bootstrap classes to Gravatar
 * 
 * @param type $avatar
 * @return type
 */
function binarybootstrap_get_avatar($avatar) {
	$avatar = str_replace( "class='avatar", "class='avatar pull-left media-object", $avatar );
	return $avatar;
}

add_filter( 'get_avatar', 'binarybootstrap_get_avatar' );

function binarybootstrap_comment_form_default_fields($fields) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	return wp_parse_args( array(
		'author' => '<div class="comment-form-author form-group">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
		'email' => '<div class="comment-form-email form-group"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input class="form-control" id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
		'url' => '<div class="comment-form-url form-group"><label for="url">' . __( 'Website' ) . '</label> ' .
		'<input class="form-control" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',), $fields );
}

add_filter( 'comment_form_default_fields', 'binarybootstrap_comment_form_default_fields' );

function binarybootstrap_comment_form_defaults($defaults) {
	$post_id = get_the_ID();
	$user_identity = wp_get_current_user()->exists() ? wp_get_current_user()->display_name : '';
	$req = get_option( 'require_name_email' );
	$required_text = sprintf( ' ' . __( 'Required fields are marked %s' ), '<span class="required">*</span>' );

	return wp_parse_args( array(
		'comment_field' => '<div class="comment-form-comment form-group"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label> <textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>',
		'must_log_in' => '<div class="must-log-in alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</div>',
		'logged_in_as' => '<div class="logged-in-as alert alert-info fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</div>',
		'comment_notes_before' => '<div class="comment-notes alert alert-info fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</div>',
		'comment_notes_after' => '<div class="form-allowed-tags alert alert-info fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <pre>' . allowed_tags() . '</pre>' ) . '</div>'), $defaults );
}

add_filter( 'comment_form_defaults', 'binarybootstrap_comment_form_defaults' );
