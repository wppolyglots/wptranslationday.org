<?php

/*
Plugin Name: GWTD Livedata
Plugin URI: https://wptranslationday.org
Description: API Calls for livedata stuff.
Version: 0.1
Author: audrasjb
Author URI: https://jeanbaptisteaudras.com
License: GPL2
*/

function gwtd3_livedata_scripts() {
	if ( get_page_template_slug() == 'livedata-page.php' ) {
		// Styles first
		wp_enqueue_style( 'gwtd-livedata', plugin_dir_url( __FILE__ ) . '/css/style.css', array(), '0.1' );
		// Then scripts
		wp_register_script( 'gwtd-livedata', plugin_dir_url( __FILE__ ) . '/js/scripts.js', array('jquery'), '0.1', true );
        wp_enqueue_script( 'gwtd-livedata' );
	}
}
add_action( 'wp_enqueue_scripts', 'gwtd3_livedata_scripts' );

/*
* Get data from http://wp-info.org/api/history/translated-sites
* @return json array of translated sites
*/
function gwtd3_get_translated_sites() {
//	$transient = get_transient('gwtd_livedata_translated_sites');
//	if ( ! empty($transient) ) :
//		$object_response = json_decode($transient, true);
//	else : 
//
		$response = wp_remote_get('http://wp-info.org/api/history/translated-sites');
		if( is_wp_error( $response ) ) :
			return false; // Bail early
		endif;
		$object_response = json_decode($response['body']);	
		set_transient('gwtd_livedata_translated_sites', wp_json_encode($object_response), HOUR_IN_SECONDS);
//	endif;*/

	return $object_response;			
}