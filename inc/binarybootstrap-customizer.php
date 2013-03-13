<?php

/**
 * Binary Bootstrap Theme Customizer
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.2
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since Binary Bootstrap 1.2
 */
function binarybootstrap_customize_register($wp_customize) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_setting( 'site_header', array(
		'default' => 'full_header',
	) );

	$wp_customize->add_control( 'site_header', array(
		'settings' => 'site_header',
		'label' => 'Header configuration',
		'section' => 'title_tagline',
		'type' => 'radio',
		'choices' => array(
			'full_header' => __( 'Site title and description', 'binarybootstrap' ),
			'title_only' => __( 'Site title only', 'binarybootstrap' ),
			'no_header' => __( 'No header text', 'binarybootstrap' ),
		),
	) );
}

add_action( 'customize_register', 'binarybootstrap_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Binary Bootstrap 1.2
 */
function binarybootstrap_customize_preview_js() {
	wp_enqueue_script( 'binarybootstrap_customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), '20130304', true );
}

add_action( 'customize_preview_init', 'binarybootstrap_customize_preview_js' );
