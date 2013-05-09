<?php

/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */

/**
 * The Gallery shortcode.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
function binarybootstrap_gallery_shortcode($output, $attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( !empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	// $output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract( shortcode_atts( array(
		'order' => 'ASC',
		'orderby' => 'menu_order ID',
		'id' => $post->ID,
		'itemtag' => 'figure',
		'icontag' => 'div',
		'captiontag' => 'figcaption',
		'columns' => 3,
		'size' => 'thumbnail',
		'include' => '',
		'exclude' => ''
					), $attr, 'gallery' ) );

	$id = intval( $id );
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty( $include ) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image',
			'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty( $exclude ) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment',
			'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image',
			'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty( $attachments ) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
		return $output;
	}

	$itemtag = tag_escape( $itemtag );
	$captiontag = tag_escape( $captiontag );
	$icontag = tag_escape( $icontag );
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( !isset( $valid_tags[$itemtag] ) )
		$itemtag = 'dl';
	if ( !isset( $valid_tags[$captiontag] ) )
		$captiontag = 'dd';
	if ( !isset( $valid_tags[$icontag] ) )
		$icontag = 'dt';

	$columns = intval( $columns );
	$itemwidth = $columns > 0 ? floor( 100 / $columns ) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='row gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	switch ( $columns ) {
		case '0':
		case '1':
			$size = 'full';
			break;
		case '2':
			$size = 'large';
			break;
		case '3':
		case '4':
			$size = 'medium';
			break;
		default:
			$size = 'thumbnail';
			break;
	}

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset( $attr['link'] ) && 'file' == $attr['link'] ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );
		$image_meta = wp_get_attachment_metadata( $id );
		$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		$clear_class = (0 == $i++ % $columns) ? ' clear' : '';
		$span = 'col col-lg-' . floor( 12 / $columns );

		$output .= "<{$itemtag} class='{$span}{$clear_class} text-center gallery-item'>";
		$output .= "
		<{$icontag} class='gallery-icon {$orientation}'>
		$link
		</{$icontag}>";
		if ( $captiontag && trim( $attachment->post_excerpt ) ) {
			$output .= "
			<{$captiontag} class='text-center wp-caption-text gallery-caption'>
			" . wptexturize( $attachment->post_excerpt ) . "
			</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
	}

	$output .= "</div>\n";

	return $output;
}

add_filter( 'post_gallery', 'binarybootstrap_gallery_shortcode', 10, 2 );

/**
 * Adds a .thumbnail class to linked images in a gallery
 *
 * @param unknown $link
 * @param unknown $id
 * @param unknown $size
 * @param unknown $permalink
 * @param unknown $icon
 * @param unknown $text
 * @return Ambigous <unknown, mixed>
 */
function binarybootstrap_get_attachment_link($link, $id, $size, $permalink, $icon, $text) {
	return (!$text ) ? str_replace( '<a', '<a class="thumbnail" ', $link ) : $link;
}

add_filter( 'wp_get_attachment_link', 'binarybootstrap_get_attachment_link', 10, 6 );

/**
 * The Caption shortcode.
 *
 * Allows a plugin to replace the content that would otherwise be returned. The
 * filter is 'img_caption_shortcode' and passes an empty string, the attr
 * parameter and the content parameter values.
 *
 * The supported attributes for the shortcode are 'id', 'align', 'width', and
 * 'caption'.
 *
 * @since 2.6.0
 *
 * @param array $attr Attributes attributed to the shortcode.
 * @param string $content Optional. Shortcode content.
 * @return string
 */
function binarybootstrap_img_caption_shortcode($output, $attr, $content) {
	// New-style shortcode with the caption inside the shortcode with the link and image tags.
	if ( !isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}

	// Allow plugins/themes to override the default caption template.
	// $output = apply_filters('img_caption_shortcode', '', $attr, $content);
	if ( $output != '' )
		return $output;

	extract( shortcode_atts( array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
					), $attr, 'caption' ) );

	if ( 1 > (int) $width || empty( $caption ) )
		return $content;

	if ( $id )
		$id = 'id="' . esc_attr( $id ) . '" ';

	return '<figure ' . $id . 'class="text-center wp-caption ' . esc_attr( $align ) . '" style="width: ' . (int) $width . 'px">'
			. do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
}

add_filter( 'img_caption_shortcode', 'binarybootstrap_img_caption_shortcode', 10, 3 );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_body_classes($classes) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() )
		$classes[] = 'group-blog';
	if ( has_nav_menu( 'top_nav' ) )
		$classes[] = 'top-nav-fixed';

	return $classes;
}

add_filter( 'body_class', 'binarybootstrap_body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_enhanced_image_navigation($url, $id) {
	if ( !is_attachment() && !wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( !empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}

add_filter( 'attachment_link', 'binarybootstrap_enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Binary Bootstrap 1.1
 */
function binarybootstrap_wp_title($title, $sep) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'binarybootstrap' ), max( $paged, $page ) );

	return $title;
}

add_filter( 'wp_title', 'binarybootstrap_wp_title', 10, 2 );

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

/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
function binarybootstrap_embed_wrap($cache, $url, $attr = '', $post_ID = '') {
	return '<div class="text-center entry-content-asset">' . $cache . '</div>';
}

add_filter( 'embed_oembed_html', 'binarybootstrap_embed_wrap', 10, 4 );
add_filter( 'embed_googlevideo', 'binarybootstrap_embed_wrap', 10, 2 );