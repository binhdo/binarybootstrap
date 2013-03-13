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

	$wp_customize->add_section( 'binarybootstrap_header', array(
		'title' => __( 'Header Settings', 'binarybootstrap' ),
		'priority' => 35,
	) );

	$wp_customize->add_setting( 'display_site_title', array(
		'default' => 1,
	) );

	$wp_customize->add_control( 'display_site_title', array(
		'settings' => 'display_site_title',
		'label' => __( 'Display Site Title' ),
		'section' => 'binarybootstrap_header',
		'type' => 'checkbox',
	) );

	$wp_customize->add_setting( 'display_site_description', array(
		'default' => 1,
	) );

	$wp_customize->add_control( 'display_site_description', array(
		'settings' => 'display_site_description',
		'label' => __( 'Display Site Description' ),
		'section' => 'binarybootstrap_header',
		'type' => 'checkbox',
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
