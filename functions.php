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
require_once locate_template( '/inc/binarybootstrap-template-tags.php' );

/**
 * Custom functions that act independently of the theme templates
 */
require_once locate_template( '/inc/binarybootstrap-filters.php' );

/**
 * Custom Comment Walker
 */
require_once locate_template( '/inc/binarybootstrap-walker-comment.php' );

/**
 * Custom Menu Walker
 */
require_once locate_template( '/inc/binarybootstrap-walker-menu.php' );

/**
 * Customizer additions
 */
require_once locate_template( '/inc/binarybootstrap-customizer.php' );

/**
 * Implement the Custom Header feature
 */
// require_once locate_template( '/inc/binarybootstrap-custom-header.php' );

/*
 * Load Jetpack compatibility file.
 */
require_once locate_template( '/inc/jetpack.php' );

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Binary Bootstrap 1.0
 */
if ( !isset( $content_width ) )
	$content_width = 940; /* pixels */

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
	 * This theme uses wp_nav_menu() in two locations.
	 */
	register_nav_menus( array(
		'top_nav' => __( 'Top Navbar', 'binarybootstrap' ),
		'primary' => __( 'Primary Menu', 'binarybootstrap' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array('aside', 'image', 'video', 'quote', 'link') );
}

add_action( 'after_setup_theme', 'binarybootstrap_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'binarybootstrap' ),
		'id' => 'sidebar-1',
		'description' => __( 'The Sidebar containing the main widget areas.', 'binarybootstrap' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Sidebar', 'binarybootstrap' ),
		'id' => 'sidebar-2',
		'description' => __( 'The Sidebar containing the footer widget area.', 'binarybootstrap' ),
		'before_widget' => '<aside id="%1$s" class="' . binarybootstrap_footer_widgets_class() . ' widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

add_action( 'widgets_init', 'binarybootstrap_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function binarybootstrap_scripts_styles() {
	// Load Bootstrap stylesheet
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', false, null );

	// Load style.css
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	// Load Bootstrap javascripts
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'binarybootstrap_scripts_styles' );

/**
 * Adjust $content_width
 * 
 */
function binarybootstrap_content_width() {
	global $content_width;
	
	if ( is_active_sidebar( 'sidebar-1' ) )
		$content_width = 770;
}

add_action( 'template_redirect', 'binarybootstrap_content_width' );