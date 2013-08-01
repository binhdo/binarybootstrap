<?php

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
function binarybootstrap_gallery_shortcode($attr) {
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
	$output = apply_filters( 'post_gallery', '', $attr );
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
		'id' => $post ? $post->ID : 0,
		'itemtag' => 'div',
		'icontag' => 'figure',
		'captiontag' => 'figcaption',
		'columns' => 4,
		'size' => 'thumbnail',
		'include' => '',
		'exclude' => ''), $attr, 'gallery'
	) );

	$id = intval( $id );
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty( $include ) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty( $exclude ) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
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
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} row'>\n";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$col_size = floor( 12 / $columns );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( !empty( $attr['link'] ) && 'file' === $attr['link'] )
			$image_output = wp_get_attachment_link( $id, $size, false, false );
		elseif ( !empty( $attr['link'] ) && 'none' === $attr['link'] )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );

		$image_meta = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		$clear_class = (0 == $i++ % $columns) ? ' clear' : '';

		$output .= "<{$itemtag} class='gallery-item col-6 col-sm-4 col-lg-{$col_size}{$clear_class}'>\n";
		$output .= "\t<{$icontag} class='gallery-icon {$orientation}'>\n";
		$output .= "\t\t" . $image_output . "\n";

		if ( $captiontag && trim( $attachment->post_excerpt ) ) {
			$output .= "\t\t<{$captiontag} class='wp-caption-text gallery-caption'>\n";
			$output .= "\t\t\t" . wptexturize( $attachment->post_excerpt ) . "\n";
			$output .= "\t\t</{$captiontag}>\n";
		}
		$output .= "\t</{$icontag}>\n";
		$output .= "</{$itemtag}>\n";
	}

	$output .= "</div>\n";

	return $output;
}

if ( current_theme_supports( 'bootstrap-gallery' ) ) {
	remove_shortcode( 'gallery' );
	add_shortcode( 'gallery', 'binarybootstrap_gallery_shortcode' );
}
