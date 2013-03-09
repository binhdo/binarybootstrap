<?php
/**
 * Binary Bootstrap functions and definitions
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */

/**
 * Custom template tags for this theme.
 */
require_once locate_template('/inc/binarybootstrap-template-tags.php');

/**
 * Custom functions that act independently of the theme templates
*/
require_once locate_template('/inc/binarybootstrap-filters.php');

/**
 * Custom Comment Walker
*/
require_once locate_template('/inc/binarybootstrap-walker-comment.php');

/**
 * Custom Menu Walker
*/
require_once locate_template('/inc/binarybootstrap-walker-menu.php');

/**
 * Customizer additions
*/
require_once locate_template('/inc/binarybootstrap-customizer.php');

/**
 * Implement the Custom Header feature
*/
require_once locate_template('/inc/binarybootstrap-custom-header.php');

/*
 * Load Jetpack compatibility file.
*/
require_once locate_template('/inc/jetpack.php');

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Binary Bootstrap 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_setup() {

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Binary Bootstrap, use a find and replace
	 * to change 'binarybootstrap' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'binarybootstrap', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'binarybootstrap' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
}
add_action( 'after_setup_theme', 'binarybootstrap_setup' );

/**
 * Setup the WordPress core custom background feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for WordPress 3.3
 * using feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @todo Remove the 3.3 support when WordPress 3.6 is released.
 *
 * Hooks into the after_setup_theme action.
 */
function binarybootstrap_register_custom_background() {
	$args = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);

	$args = apply_filters( 'binarybootstrap_custom_background_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-background', $args );
	} else {
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		if ( ! empty( $args['default-image'] ) )
			define( 'BACKGROUND_IMAGE', $args['default-image'] );
		add_custom_background();
	}
}
add_action( 'after_setup_theme', 'binarybootstrap_register_custom_background' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'binarybootstrap' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'binarybootstrap_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function binarybootstrap_scripts_styles() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'binarybootstrap_scripts_styles' );
