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
				<a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<?php if ( $brand_text ) : ?>
					<a class="navbar-brand" href="<?php echo esc_url( $brand_url ); ?>"><?php echo esc_attr( $brand_text ); ?></a>
				<?php endif; ?>
				<div class="nav-collapse collapse navbar-responsive-collapse">
					<?php wp_nav_menu( $args ); ?>
				</div>
			</div>
		</nav>
	<?php
	}
}

if ( ! function_exists( 'binarybootstrap_content_nav' ) ) :
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
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'binarybootstrap' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'binarybootstrap' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'binarybootstrap' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'binarybootstrap' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'binarybootstrap' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // binarybootstrap_content_nav

if ( ! function_exists( 'binarybootstrap_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_posted_on() {
	printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'binarybootstrap' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'binarybootstrap' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;
/**
 * Returns true if a blog has more than 1 category
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so binarybootstrap_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so binarybootstrap_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in binarybootstrap_categorized_blog
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'binarybootstrap_category_transient_flusher' );
add_action( 'save_post', 'binarybootstrap_category_transient_flusher' );