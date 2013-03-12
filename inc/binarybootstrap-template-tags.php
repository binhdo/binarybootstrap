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
function binarybootstrap_primary_class( $has_sidebar = true ) {
	if ( $has_sidebar && is_active_sidebar( 'sidebar-1' ) ) {
		$class = 'span8';
	} else {
		$class = 'span12';
	}

	return $class;
}

/**
 * #secondary .widget-area class
 *
 * @return string
 */
function binarybootstrap_secondary_class() {
	return 'span4';

}

/**
 * #tertiary .widget-area class
 *
 * @return string
 */
function binarybootstrap_footer_widgets_class() {
	$widgets = wp_get_sidebars_widgets();
	$num_widgets = count( $widgets['sidebar-2'] );

	$a = floor(12 / $num_widgets);

	$class = 'span' . $a;

	return $class;

}

/**
 * Displays a fully fledged responsive Bootstrap Navbar
 *
 * @param string $location
 * @param string $class
 * @param string $brand_text
 * @param string $brand_url
 */
function binarybootstrap_nav_menu( $location, $class, $brand_text = null, $brand_url = null ) {
	if ( has_nav_menu( $location ) ) {
		$args = array(
				'theme_location' => $location,
				'container' => false,
				'depth' => 2,
				'walker' => new BinaryBootstrap_Walker_Nav_Menu(),
				'items_wrap' => '<ul id="%1$s" class="nav %2$s">%3$s</ul>'
		);
		if ( ! $brand_url )
			$brand_url = home_url( '/' );
		?>
		<nav class="<?php echo $class; ?>" role="navigation">
			<div class="container">
				<a class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<?php if ( $brand_text ) : ?>
				<a class="navbar-brand" href="<?php echo esc_url( $brand_url ); ?>"><?php echo esc_attr( $brand_text ); ?> </a>
				<?php endif; ?>
				<div class="nav-collapse collapse">
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

	if ( ! is_single() ) {
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

	} else {
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
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<i class="glyphicon glyphicon-fire"></i> <span class="featured-post">' . __( 'Sticky', 'binarybootstrap' ) . '</span> ';

	if ( ! has_post_format( 'aside' ) && ! has_post_format( 'link' ) && 'post' == get_post_type() )
		binarybootstrap_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'binarybootstrap' ) );
	if ( $categories_list ) {
		echo '<i class="glyphicon glyphicon-paperclip"></i> <span class="categories-links">' . $categories_list . '</span> ';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'binarybootstrap' ) );
	if ( $tag_list ) {
		echo '<i class="glyphicon glyphicon-tags"></i> <span class="tags-links">' . $tag_list . '</span> ';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<i class="glyphicon glyphicon-user"></i> <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span> ',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'binarybootstrap' ), get_the_author() ) ),
		get_the_author()
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
function binarybootstrap_entry_date( $echo = true ) {
	$format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'binarybootstrap' ): '%2$s';

	$date = sprintf( '<i class="glyphicon glyphicon-time"></i> <span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span> ',
			esc_url( get_permalink() ),
			esc_attr( sprintf( __( 'Permalink to %s', 'binarybootstrap' ), the_title_attribute( 'echo=0' ) ) ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}
