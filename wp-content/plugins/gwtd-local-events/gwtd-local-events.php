<?php
/*
Plugin Name: GWTD Local Events
Plugin URI: https://wptranslationday.org
Description: This plugin will deal with local events.
Version: 0.3
Author: Pascal CASIER
Author URI: http://www.facebook.com/pascal.casier
License: GPL2
*/
include( dirname(__FILE__) . '/inc/setup.php');
include( dirname(__FILE__) . '/inc/api.php');

function gwtd_local_events_load_scripts()
{
	wp_enqueue_style( 'gwtd-local-events', plugin_dir_url( __FILE__ ) . 'inc/style.css', array(), '0.3' );
}
add_action( 'admin_enqueue_scripts', 'gwtd_local_events_load_scripts' );
?>