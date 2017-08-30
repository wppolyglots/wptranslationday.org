/*
 * Register routes
*/

add_action( 'rest_api_init', function() {
	$args = array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'le_api_events',
	);
	register_rest_route( 'local-events/v1', '/', $args );
}

/*
 * API callback routines
*/

function le_api_events() {
	$all_data = array();
	
	$query = new WP_Query( array('post_type' => 'local-event', 'posts_per_page' => -1, 'meta_key' => 'full_place', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
	while ( $query->have_posts() ) : $query->the_post();
		$full_place = get_post_meta($post->ID, 'full_place', true);
		$all_data['events'][$full_place]['full_place'] = $full_place;
		$all_data['events'][$full_place]['continent'] = get_post_meta($post->ID, 'continent', true);
		$all_data['events'][$full_place]['country'] = get_post_meta($post->ID, 'country', true);
		$all_data['events'][$full_place]['city'] = get_post_meta($post->ID, 'city', true);
		$all_data['events'][$full_place]['utc_start'] = get_post_meta($post->ID, 'utc_start', true);
		$all_data['events'][$full_place]['locale'] = get_post_meta($post->ID, 'locale', true);
	endwhile;
	wp_reset_postdata();
	
	return $all_data;
}
