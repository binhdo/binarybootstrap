<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php binarybootstrap_nav_menu( 'top_nav', 'navbar navbar-fixed-top', __( 'Home', 'binarybootstrap' ) ); ?>
	<div id="page" class="container hfeed site">
		<?php do_action( 'before' ); ?>
		<?php
		$site_title = get_theme_mod( 'display_site_title' );
		$site_desc = get_theme_mod( 'display_site_description' );
		?>
		<?php if ( $site_title || $site_desc || has_nav_menu( 'primary' ) ) : ?>
			<header id="masthead" class="site-header" role="banner">
				<?php if ( $site_title || $site_desc ) : ?>
					<hgroup>
						<?php if ( $site_title ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php endif; ?>
						<?php if ( $site_desc ) : ?>
							<h2 class="lead site-description"><?php bloginfo( 'description' ); ?></h2>
						<?php endif; ?>
					</hgroup>
				<?php endif; ?>
				<?php binarybootstrap_nav_menu( 'primary', 'navbar', __( 'Home', 'binarybootstrap' ) ); ?>
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'binarybootstrap' ); ?>"><?php _e( 'Skip to content', 'binarybootstrap' ); ?></a></div>
				<?php endif; ?>
			</header><!-- #masthead -->
		<?php endif; ?>

		<div id="main" class="row site-main">
