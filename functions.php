<?php
/**
 * Binary Bootstrap functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Binary_Bootstrap
 * @since Binary Bootstrap 1.0
 */

/**
 * Sets up the content width value based on the theme's design.
 * @see binarybootstrap_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 940;

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
 * Bootstrap gallery shortcode
 */
require_once locate_template('/inc/binarybootstrap-gallery.php');

/**
 * Binary Bootstrap only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6-alpha', '<' ) )
	require get_template_directory() . '/inc/back-compat.php';

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Binary Bootstrap supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Binary Bootstrap 1.0
 *
 * @return void
 */
function binarybootstrap_setup() {
	/*
	 * Makes Binary Bootstrap available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Binary Bootstrap, use a find and
	 * replace to change 'binarybootstrap' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'binarybootstrap', get_template_directory() . '/languages' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	// add_editor_style( array( 'css/editor-style.css', 'fonts/genericons.css' ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Switches default core markup for search form to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	) );

	/**
	 * This theme uses wp_nav_menu() in two locations.
	 */
	register_nav_menus(array(
		'top_nav' => __('Top Navbar', 'binarybootstrap'),
		'primary' => __('Primary Menu', 'binarybootstrap'),
	));

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	
	/**
	 * Use Bootstrap thumbnails and grid for [gallery]
	 */
	add_theme_support( 'bootstrap-gallery' );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'binarybootstrap_setup' );

/**
 * Loads our special font CSS file.
 *
 * To disable in a child theme, use wp_dequeue_style()
 * function mytheme_dequeue_fonts() {
 *     wp_dequeue_style( 'binarybootstrap-fonts' );
 * }
 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
 *
 * Also used in the Appearance > Header admin panel:
 * @see binarybootstrap_custom_header_setup()
 *
 * @since Binary Bootstrap 1.0
 *
 * @return void
 */
function binarybootstrap_fonts() {
	$fonts_url = binarybootstrap_fonts_url();
	if ( ! empty( $fonts_url ) )
		wp_enqueue_style( 'binarybootstrap-fonts', esc_url_raw( $fonts_url ), array(), null );

	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/fonts/genericons.css', array(), '2.09' );
}
add_action( 'wp_enqueue_scripts', 'binarybootstrap_fonts' );

/**
 * Enqueues scripts and styles for front end.
 *
 * @since Binary Bootstrap 1.0
 *
 * @return void
 */
function binarybootstrap_scripts_styles() {
	/* Stylesheets */
	wp_enqueue_style( 'binarybootstrap', get_template_directory_uri() . '/css/binarybootstrap.css', false, null );
	
	// Loads our main stylesheet.
	// wp_enqueue_style( 'binarybootstrap-style', get_stylesheet_uri() );
	
	/* Javascripts */
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), null, true );
	
	/*
	 * Adds JavaScript to pages with the comment form to support sites with
	 * threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds Masonry to handle vertical alignment of footer widgets.
	// if ( is_active_sidebar( 'sidebar-1' ) )
		// wp_enqueue_script( 'jquery-masonry' );

	// Loads JavaScript file with functionality specific to Binary Bootstrap.
	// wp_enqueue_script( 'binarybootstrap-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20130625a', true );
}
add_action( 'wp_enqueue_scripts', 'binarybootstrap_scripts_styles' );

/**
 * Registers two widget areas.
 *
 * @since Binary Bootstrap 1.0
 *
 * @return void
 */
function binarybootstrap_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Widget Area', 'binarybootstrap' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears in the footer section of the site.', 'binarybootstrap' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s ' . binarybootstrap_secondary_widget_class() . '">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Secondary Widget Area', 'binarybootstrap' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears on posts and pages in the sidebar.', 'binarybootstrap' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s ' . binarybootstrap_tertiary_widget_class() . '">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'binarybootstrap_widgets_init' );

/**
 * Adjusts content_width value for video post formats and attachment templates.
 *
 * @since Binary Bootstrap 1.0
 *
 * @return void
 */
function binarybootstrap_content_width() {
	global $content_width;
	
	if ( is_active_sidebar( 'sidebar-2' ) )
		$content_width = 870;
	elseif ( is_attachment() )
		$content_width = 940;
	elseif ( has_post_format( 'audio' ) )
		$content_width = 484;
}
add_action( 'template_redirect', 'binarybootstrap_content_width' );

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Binary Bootstrap 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function binarybootstrap_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	$wp_customize->add_setting( 'display_site_title', array( 'default' => 1 ) );
	
	$wp_customize->add_control( 'display_site_title', array(
		'settings' => 'display_site_title',
		'label' => __( 'Display site title', 'binarybootstrap' ),
		'section' => 'title_tagline',
		'type' => 'checkbox',
	) );

}
add_action( 'customize_register', 'binarybootstrap_customize_register' );

/**
 * Binds JavaScript handlers to make Customizer preview reload changes
 * asynchronously.
 *
 * @since Binary Bootstrap 1.0
 */
function binarybootstrap_customize_preview_js() {
	wp_enqueue_script( 'binarybootstrap-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130226', true );
}
add_action( 'customize_preview_init', 'binarybootstrap_customize_preview_js' );

/**
 * Replace [gallery] shortcode
 * 
 */
function binarybootstrap_gallery_support() {		
	if ( current_theme_supports( 'bootstrap-gallery' ) ) {
		remove_shortcode( 'gallery' );
		add_shortcode( 'gallery', 'binarybootstrap_gallery_shortcode' );
	}
}
add_action( 'template_redirect', 'binarybootstrap_gallery_support' );
