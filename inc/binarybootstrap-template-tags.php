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
 * #primary .content-area class
 */
function binarybootstrap_primary_class() {
	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$class = 'span8';
	} else {
		$class = 'span12';
	}

	return $class;
}

/**
 * #secondary .widget-area class
 */
function binarybootstrap_secondary_class() {
	return 'span4';

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
				<a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
				</a>
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
function binarybootstrap_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text">
			<?php _e( 'Post navigation', 'binarybootstrap' ); ?>
		</h1>
	
		<?php if ( is_single() ) : // navigation links for single posts ?>
	
		<?php previous_post_link( '<div class="previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'binarybootstrap' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'binarybootstrap' ) . '</span>' ); ?>
	
		<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
	
		<?php if ( get_next_posts_link() ) : ?>
		<div class="previous">
			<?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'binarybootstrap' ) ); ?>
		</div>
		<?php endif; ?>
	
		<?php if ( get_previous_posts_link() ) : ?>
		<div class="next">
			<?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'binarybootstrap' ) ); ?>
		</div>
		<?php endif; ?>
	
		<?php endif; ?>
	
	</nav>
	<!-- #<?php echo esc_html( $nav_id ); ?> -->
<?php
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
