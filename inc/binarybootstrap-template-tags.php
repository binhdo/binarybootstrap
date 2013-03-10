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
