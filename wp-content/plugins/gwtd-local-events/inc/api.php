<?php
/*
 * Register routes
*/

add_action( 'rest_api_init', 'le_api_routes' );
function le_api_routes() {
	$args = array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'le_api_events',
	);
	register_rest_route( 'local-events/v1', '/events/', $args );
}

/*
 * API callback routines
*/

function le_api_events() {
	$all_data = array();
	
	$query = new WP_Query( array('post_type' => 'local-event', 'posts_per_page' => -1, 'meta_key' => 'full_place', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
	$all_data['count'] = $query->post_count;
	//$i = 0;
	while ( $query->have_posts() ) : $query->the_post();
		$post_id = get_the_ID();
		$full_place = get_post_meta($post_id, 'full_place', true);
		$all_data['events'][$full_place]['full_place'] = $full_place;
		$all_data['events'][$full_place]['continent'] = get_post_meta($post_id, 'continent', true);
		$all_data['events'][$full_place]['country'] = get_post_meta($post_id, 'country', true);
		$all_data['events'][$full_place]['city'] = get_post_meta($post_id, 'city', true);
		$all_data['events'][$full_place]['utc_start'] = get_post_meta($post_id, 'utc_start', true);
		$all_data['events'][$full_place]['locale'] = get_post_meta($post_id, 'locale', true);
		$all_data['events'][$full_place]['latitude'] = get_post_meta($post_id, 'latitude', true);
		$all_data['events'][$full_place]['longitude'] = get_post_meta($post_id, 'longitude', true);
		//$i = $i + 1;
	endwhile;
	wp_reset_postdata();
	
	return $all_data;
}