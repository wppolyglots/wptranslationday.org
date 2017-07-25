<?php

/**
 * Enqueue scripts and styles.
 */

function gwtd3_scripts() {
	//	normalize css
	wp_enqueue_style( 'gwtd3-normalize', get_template_directory_uri() . '/css/normalize.css', array(), '20170724' );
	// skeleton base css
	wp_enqueue_style( 'gwtd3-skeleton', get_template_directory_uri() . '/css/skeleton.css', array(), '20170724' );
	// theme css
	wp_enqueue_style( 'gwtd3-custom', get_stylesheet_uri() );
	// fonts
	wp_enqueue_style( 'gwtd3-fonts', 'https://fonts.googleapis.com/css?family=Changa:400,700|Open+Sans:400,400i,600,700' );
	// scripts
	wp_enqueue_script( 'gwtd3-scripts', get_template_directory_uri() . '/js/scripts.js', array(), '20170724', true );

}
add_action( 'wp_enqueue_scripts', 'gwtd3_scripts' );