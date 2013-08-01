<?php
/**
 * Custom template tags for this theme.
 * 
 * @package Binary Bootstrap
 * @since Binary Bootstrap 0.1
 */

/**
 * Full width column class
 * 
 * @return string
 */
function binarybootstrap_full_width_class() {
	return 'col-12 col-sm-12 col-lg-12';
}

/**
 * #primary .content-area
 *
 * @param string $has_sidebar
 * @return string
 */
function binarybootstrap_primary_class($has_sidebar = true) {
	if ( $has_sidebar && is_active_sidebar( 'sidebar-2' ) ) {
		$class = 'col-12 col-sm-9 col-lg-9';
	} else {
		$class = 'col-12 col-sm-12 col-lg-12';
	}

	return $class;
}

/**
 * #secondary .widget-area class (Appears in the footer section of the site)
 *
 * @return string
 */
function binarybootstrap_secondary_widget_class() {
	$widgets = wp_get_sidebars_widgets();
	$num_widgets = count( $widgets['sidebar-1'] );

	$a = $num_widgets > 0 ? floor( 12 / $num_widgets ) : 1;

	$class = $a < 3 ? 'col-6 col-sm-4 col-lg-3' : 'col-6 col-sm-4 col-lg-' . $a;

	return $class;
}

/**
 * #tertiary .widget-area class (Appears on posts and pages in the sidebar)
 *
 * @return string
 */
function binarybootstrap_tertiary_class() {
	return 'col-12 col-sm-3 col-lg-3';
}

function binarybootstrap_tertiary_widget_class() {
	return 'col-6 col-sm-12 col-lg-12';
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
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since Binary Bootstrap 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function binarybootstrap_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Noto Sans, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$noto_sans = _x( 'on', 'Noto Sans font: on or off', 'binarybootstrap' );

	if ( 'off' !== $noto_sans ) {
		$font_families = array();

		if ( 'off' !== $noto_sans )
			$font_families[] = 'Noto+Sans:400,400italic,700,700italic';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => implode( '|', $font_families ),
			'subset' => 'latin,latin-ext',
		);
		$fonts_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

if ( !function_exists( 'binarybootstrap_paging_nav' ) ) :

	/**
	 * Displays navigation to next/previous set of posts when applicable.
	 *
	 * @since Binary Bootstrap 1.0
	 *
	 * @return void
	 */
	function binarybootstrap_paging_nav() {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 )
			return;
		?>
		<nav class="navigation paging-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'binarybootstrap' ); ?></h1>
			<ul class="nav-links pager">

				<?php if ( get_next_posts_link() ) : ?>
					<li class="nav-previous previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'binarybootstrap' ) ); ?></li>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
					<li class="nav-next next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'binarybootstrap' ) ); ?></li>
				<?php endif; ?>

			</ul><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}

endif;

if ( !function_exists( 'binarybootstrap_post_nav' ) ) :

	/**
	 * Displays navigation to next/previous post when applicable.
	 *
	 * @since Binary Bootstrap 1.0
	 *
	 * @return void
	 */
	function binarybootstrap_post_nav() {
		global $post;

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( !$next && !$previous )
			return;
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'binarybootstrap' ); ?></h1>
			<ul class="nav-links pager">

				<li class="previous"><?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'binarybootstrap' ) ); ?></li>
				<li class="next"><?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'binarybootstrap' ) ); ?></li>

			</ul><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}

endif;

if ( !function_exists( 'binarybootstrap_entry_meta' ) ) :

	/**
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * Create your own binarybootstrap_entry_meta() to override in a child theme.
	 *
	 * @since Binary Bootstrap 1.0
	 *
	 * @return void
	 */
	function binarybootstrap_entry_meta() {
		if ( is_sticky() && is_home() && !is_paged() )
			echo '<span class="glyphicon glyphicon-fire"></span><span class="featured-post">' . __( 'Sticky', 'binarybootstrap' ) . '</span>';

		if ( !has_post_format( 'link' ) && 'post' == get_post_type() )
			binarybootstrap_entry_date();

		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list( __( ', ', 'binarybootstrap' ) );
		if ( $categories_list ) {
			echo '<span class="glyphicon glyphicon-paperclip"></span><span class="categories-links">' . $categories_list . '</span>';
		}

		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', __( ', ', 'binarybootstrap' ) );
		if ( $tag_list ) {
			echo '<span class="glyphicon glyphicon-tags"></span><span class="tags-links">' . $tag_list . '</span>';
		}

		// Post author
		if ( 'post' == get_post_type() ) {
			printf( '<span class="glyphicon glyphicon-user"></span><span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_attr( sprintf( __( 'View all posts by %s', 'binarybootstrap' ), get_the_author() ) ), get_the_author()
			);
		}
	}

endif;

if ( !function_exists( 'binarybootstrap_entry_date' ) ) :

	/**
	 * Prints HTML with date information for current post.
	 *
	 * Create your own binarybootstrap_entry_date() to override in a child theme.
	 *
	 * @since Binary Bootstrap 1.0
	 *
	 * @param boolean $echo Whether to echo the date. Default true.
	 * @return string The HTML-formatted post date.
	 */
	function binarybootstrap_entry_date($echo = true) {
		if ( has_post_format( array('chat', 'status') ) )
			$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'binarybootstrap' );
		else
			$format_prefix = '%2$s';

		$date = sprintf( '<span class="glyphicon glyphicon-calendar"></span><span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', esc_url( get_permalink() ), esc_attr( sprintf( __( 'Permalink to %s', 'binarybootstrap' ), the_title_attribute( 'echo=0' ) ) ), esc_attr( get_the_date( 'c' ) ), esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
		);

		if ( $echo )
			echo $date;

		return $date;
	}

endif;

if ( !function_exists( 'binarybootstrap_the_attached_image' ) ) :

	/**
	 * Prints the attached image with a link to the next attached image.
	 *
	 * @since Binary Bootstrap 1.0
	 *
	 * @return void
	 */
	function binarybootstrap_the_attached_image() {
		$post = get_post();
		$attachment_size = apply_filters( 'binarybootstrap_attachment_size', array(1170, 9999) );
		$next_attachment_url = wp_get_attachment_url();

		/**
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts( array(
			'post_parent' => $post->post_parent,
			'fields' => 'ids',
			'numberposts' => -1,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => 'ASC',
			'orderby' => 'menu_order ID'
				) );

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( $next_id )
				$next_attachment_url = get_attachment_link( $next_id );

			// or get the URL of the first image attachment.
			else
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}

		printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>', esc_url( $next_attachment_url ), the_title_attribute( array(
			'echo' => false) ), wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}

endif;

/**
 * Returns the URL from the post.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Binary Bootstrap 1.0
 *
 * @return string The Link format URL.
 */
function binarybootstrap_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
