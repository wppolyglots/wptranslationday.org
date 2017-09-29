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

/*
* UNUSED
* Get data from http://wp-info.org/api/polyglots/teams
* @return json array of polyglot teams
*/

function gwtd3_get_polyglot_teams() {
	$transient = get_transient('gwtd_livedata_polyglot_teams');
	if ( ! empty($transient) ) :
		$object_response = json_decode($transient, true);
	else : 
		$response = wp_remote_get('http://wp-info.org/api/polyglots/teams');
		if( is_wp_error( $response ) ) :
			return false; // Bail early
		endif;
		$object_response = json_decode($response['body']);	
		set_transient('gwtd_livedata_polyglot_teams', wp_json_encode($object_response), HOUR_IN_SECONDS);
	endif;

	return $object_response;			
}


/*
*
* GET TRANSLATORS
* Get data from http://wp-info.org/api/polyglots/translators
* @return $array_translators array, with :
* 	total_gte		int
* 	total_pte		int
* 	total_contrib	int
* 	base_gte		int
* 	base_pte		int
* 	base_contrib	int
* 	new_gte			int
* 	new_pte			int
* 	new_contrib		int
*	last_modified	timestamp
*	base_modified	timestamp
*
*/

function gwtd3_get_translators() {
	
	// Get transient or new data if exists.
	$transient = get_transient( 'gwtd_livedata_translators_tr' );
	
	// If the transient EXISTS so let'sget already stored data
	if ( ! empty( $transient ) ) :

		$object_response = json_decode($transient, true);

		// If we already have data saved, just get the vars.
		if ( get_option( 'gwtd_livedata_translators' ) !== false ) :

			$array_translators = json_decode( get_option( 'gwtd_livedata_translators' ) );

		// Else, let's create the vars.
		else :
			$array_translators = array(
				'total_gte' 		=> 		$object_response->total_gte,
				'total_pte' 		=> 		$object_response->total_pte,
				'total_contrib' 	=> 		$object_response->total_contrib,
				'base_gte'			=>		$object_response->base_gte,
				'base_pte'			=>		$object_response->base_pte,
				'base_contrib'		=>		$object_response->base_contrib,
				'new_gte'			=>		$object_response->new_gte,
				'new_pte'			=>		$object_response->new_pte,
				'new_contrib'		=>		$object_response->new_contrib,
				'last_modified'		=> 		time(),
				'base_modified'		=>		$object_response->base_modified,
			);
			// Update the option
			update_option( 'gwtd_livedata_translators', json_encode( $array_translators ) );
		
		endif;

	// If the transient does NOT EXISTS so let's get the remote data
	else : 
		$response = wp_remote_get( 'http://wp-info.org/api/polyglots/translators' );

		// IF there is no error
		if( !is_wp_error( $response ) ) :
			$object_response = json_decode( $response['body'] );	
			
			// If old data exists
			if ( get_option( 'gwtd_livedata_translators' ) !== false ) :
				$old_data = json_decode( get_option( 'gwtd_livedata_translators' ) );
				$array_translators = array(
					'total_gte' 		=> 		intval( $object_response->total_gte ),
					'total_pte' 		=> 		intval( $object_response->total_pte ),
					'total_contrib' 	=> 		intval( $object_response->total_contrib ),
					'base_gte'			=>		intval(	$old_data->base_gte ),
					'base_pte'			=>		intval(	$old_data->base_pte ),
					'base_contrib'		=>		intval( $old_data->base_contrib ),
					'new_gte'			=>		intval( $object_response->total_gte )			-	intval(	$old_data->base_gte ),
					'new_pte'			=>		intval( $object_response->total_pte )			-	intval(	$old_data->base_pte ),
					'new_contrib'		=>		intval( $object_response->total_contrib )		-	intval( $old_data->base_contrib ),
					'last_modified'		=> 		time(),
					'base_modified'		=>		$old_data->base_modified,
				);
				// Update the option
				update_option( 'gwtd_livedata_translators', json_encode( $array_translators ) );
			
			// Else, if old data DONT exists
			else : 
				$array_translators = array(
					'total_gte' 		=> 		intval( $object_response->total_gte ),
					'total_pte' 		=> 		intval( $object_response->total_pte ),
					'total_contrib' 	=> 		intval( $object_response->total_contrib ),
					'base_gte'			=>		intval( $object_response->total_gte ),
					'base_pte'			=>		intval( $object_response->total_pte ),
					'base_contrib'		=>		intval( $object_response->total_contrib ),
					'new_gte'			=>		0,
					'new_pte'			=>		0,
					'new_contrib'		=>		0,
					'last_modified'		=> 		time(),
					'base_modified'		=>		time(),
				);
				// Update the option
				update_option( 'gwtd_livedata_translators', json_encode( $array_translators ) );
			
			endif;
			// Set the new transient for the next hour
			set_transient( 'gwtd_livedata_translators_tr', wp_json_encode( $array_translators ), HOUR_IN_SECONDS );

		// ELSE, if there is an error, return old data and dont update anything
		else : 
			$old_data = json_decode( get_option( 'gwtd_livedata_translators' ) );
			$array_translators = array(
				'total_gte' 		=> 		intval( $old_data->total_gte ),
				'total_pte' 		=> 		intval( $old_data->total_pte ),
				'total_contrib' 	=> 		intval( $old_data->total_contrib ),
				'base_gte'			=>		intval(	$old_data->base_gte ),
				'base_pte'			=>		intval(	$old_data->base_pte ),
				'base_contrib'		=>		intval( $old_data->base_contrib ),
				'new_gte'			=>		intval(	$old_data->new_gte ),
				'new_pte'			=>		intval(	$old_data->new_pte ),
				'new_contrib'		=>		intval(	$old_data->new_contrib ),
				'last_modified'		=>		$old_data->last_modified,
				'base_modified'		=>		$old_data->base_modified,
			);
		endif;
	endif;
	
	// Return an array of both translators stats and new translators
	return $array_translators;			
}



/*
*
* GET TOP120
* Get data from http://wp-info.org/api/polyglots/top120-plugins
* @return $array_top120 array, with :
* 	total_translated_strings	int
* 	base_translated_strings		int
* 	new_translated_strings		int
*	last_modified				timestamp
*	base_modified				timestamp
*
*/

function gwtd3_get_top120() {

	// Get transient or new data if exists.
	$transient = get_transient( 'gwtd_livedata_top120_tr' );
	
	// If the transient EXISTS so let'sget already stored data
	if ( ! empty( $transient ) ) :

		$object_response = json_decode($transient, true);

		// If we already have data saved, just get the vars.
		if ( get_option( 'gwtd_livedata_top120' ) !== false ) :

			$array_top120 = json_decode( get_option( 'gwtd_livedata_top120' ) );

		// Else, let's create the vars.
		else :
		
			$array_top120 = array(
				'total_translated_strings' 		=> 		intval( $object_response->total_translated_strings ),
				'base_translated_strings' 		=> 		intval( $object_response->base_translated_strings ),
				'new_translated_strings' 		=> 		intval( $object_response->total_translated_strings ) - intval( $old_data->base_translated_strings ),
				'last_modified'					=> 		time(),
				'base_modified'					=>		$object_response->base_modified,
			);
			// Update the option
			update_option( 'gwtd_livedata_top120', json_encode( $array_top120 ) );
		
		endif;

	// If the transient does NOT EXISTS so let's get the remote data
	else : 
		$response = wp_remote_get( 'http://wp-info.org/api/polyglots/top120-plugins' );

		// IF there is no error
		if( !is_wp_error( $response ) ) :
			$object_response = json_decode( $response['body'] );	
			$total_strings = 0;
			foreach ( $object_response->plugins as $plugin_name => $plugin_infos ) :
				foreach ( $plugin_infos->locale as $plugin_locale ) : 
					$plugin_dev_s = $plugin_locale->dev_s;
					$plugin_stable_s = $plugin_locale->stable_s;
					$plugin_waiting_s = $plugin_locale->waiting_s;
					$plugin_s = $plugin_dev_s + $plugin_stable_s;
					$total_strings = $total_strings + $plugin_s;
				endforeach;
			endforeach;
			
			// If old data exists
			if ( get_option( 'gwtd_livedata_top120' ) !== false ) :
				$old_data = json_decode( get_option( 'gwtd_livedata_top120' ) );
				$array_top120 = array(
					'total_translated_strings' 		=> 		intval( $total_strings ),
					'base_translated_strings' 		=> 		intval( $old_data->base_translated_strings ),
					'new_translated_strings' 		=> 		intval( $total_strings ) - intval( $old_data->base_translated_strings ),
					'last_modified'					=> 		time(),
					'base_modified'					=>		$old_data->base_modified,
				);
				// Update the option
				update_option( 'gwtd_livedata_top120', json_encode( $array_top120 ) );
			
			// Else, if old data DONT exists
			else : 
				$array_top120 = array(
					'total_translated_strings' 		=> 		intval( $total_strings ),
					'base_translated_strings' 		=> 		intval( $total_strings ),
					'new_translated_strings' 		=> 		0,
					'last_modified'					=> 		time(),
					'base_modified'					=>		time(),
				);
				// Update the option
				update_option( 'gwtd_livedata_top120', json_encode( $array_top120 ) );
			
			endif;
			// Set the new transient for the next hour
			set_transient( 'gwtd_livedata_top120_tr', wp_json_encode( $array_top120 ), HOUR_IN_SECONDS );

		// ELSE, if there is an error, return old data and dont update anything
		else : 
			$old_data = json_decode( get_option( 'gwtd_livedata_top120' ) );
			$array_top120 = array(
					'total_translated_strings' 		=> 		intval( $old_data->total_translated_strings ),
					'base_translated_strings' 		=> 		intval( $old_data->base_translated_strings ),
					'new_translated_strings' 		=> 		intval( $old_data->new_translated_strings ),
					'last_modified'					=> 		time(),
					'base_modified'					=>		time(),
			);
		endif;
	endif;
	
	// Return an array of both translators stats and new translators
	return $array_top120;			
}




/*
*
* GET WP TRANSLATIONS
* Get data from https://api.wordpress.org/translations/core/1.0/?version=4.8.2
* @return $array_top120 array, with :
* 	total_translated_wp		int
* 	base_translated_wp		int
* 	new_translated_wp		int
*	last_modified			timestamp
*	base_modified			timestamp
*
*/

function gwtd3_get_wp_translations() {
	
	// Get transient or new data if exists.
	$transient = get_transient( 'gwtd_livedata_wp_translations_tr' );
	
	// If the transient EXISTS so let'sget already stored data
	if ( ! empty( $transient ) ) :

		$object_response = json_decode($transient, true);

		// If we already have data saved, just get the vars.
		if ( get_option( 'gwtd_livedata_wp_translations' ) !== false ) :

			$array_wptranslations = json_decode( get_option( 'gwtd_livedata_wp_translations' ) );

		// Else, let's create the vars.
		else :
		
			$array_wptranslations = array(
				'total_translated_wp' 		=> 		intval( $object_response->total_translated_strings ),
				'base_translated_wp' 		=> 		intval( $object_response->base_translated_strings ),
				'new_translated_wp' 		=> 		intval( $object_response->total_translated_strings ) - intval( $old_data->base_translated_strings ),
				'last_modified'				=> 		time(),
				'base_modified'				=>		$object_response->base_modified,
			);
			// Update the option
			update_option( 'gwtd_livedata_wp_translations', json_encode( $array_wptranslations ) );
		
		endif;

	// If the transient does NOT EXISTS so let's get the remote data
	else : 
		$response = wp_remote_get( 'https://api.wordpress.org/translations/core/1.0/?version=4.8.2' );

		// IF there is no error
		if( !is_wp_error( $response ) ) :
			$object_response = json_decode( $response['body'] );	
			
			// If old data exists
			if ( get_option( 'gwtd_livedata_wp_translations' ) !== false ) :
				$old_data = json_decode( get_option( 'gwtd_livedata_wp_translations' ) );
				$array_wptranslations = array(
					'total_translated_wp' 	=> 		intval( count($object_response->translations ) ),
					'base_translated_wp' 	=> 		intval( $old_data->base_translated_wp ),
					'new_translated_wp' 	=> 		intval( count($object_response->translations ) ) - intval( $old_data->base_translated_wp ),
					'last_modified'			=> 		time(),
					'base_modified'			=>		$old_data->base_modified,
				);
				// Update the option
				update_option( 'gwtd_livedata_wp_translations', json_encode( $array_wptranslations ) );
			
			// Else, if old data DONT exists
			else : 
				$array_wptranslations = array(
					'total_translated_wp' 	=> 		intval( count($object_response->translations ) ),
					'base_translated_wp' 	=> 		intval( count($object_response->translations ) ),
					'new_translated_wp' 	=> 		0,
					'last_modified'			=> 		time(),
					'base_modified'			=>		time(),
				);
				// Update the option
				update_option( 'gwtd_livedata_wp_translations', json_encode( $array_wptranslations ) );
			
			endif;
			// Set the new transient for the next hour
			set_transient( 'gwtd_livedata_wp_translations_tr', wp_json_encode( $array_wptranslations ), HOUR_IN_SECONDS );

		// ELSE, if there is an error, return old data and dont update anything
		else : 
			$old_data = json_decode( get_option( 'gwtd_livedata_wp_translations' ) );
			$array_wptranslations = array(
					'total_translated_wp'	=> 		intval( $old_data->total_translated_strings ),
					'base_translated_wp' 	=> 		intval( $old_data->base_translated_strings ),
					'new_translated_wp'		=> 		intval( $old_data->new_translated_strings ),
					'last_modified'			=> 		time(),
					'base_modified'			=>		time(),
			);
		endif;
	endif;
	
	// Return an array of both translators stats and new translators
	return $array_wptranslations;
}
