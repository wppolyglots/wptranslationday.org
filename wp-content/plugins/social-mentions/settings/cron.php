<?php
//////////////////////////////////////////////////////
// Create cron job function
//////////////////////////////////////////////////////

function social_mentions_get_api_hashtags() {
	$terms = get_terms( array(
		'taxonomy' => 'socment-hashtags',
		'hide_empty' => false,
	) );
	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$the_term = $term->name;
			social_mentions_get_twitter_posts( $the_term );
			social_mentions_get_instagram_posts( $the_term );
			social_mentions_get_googleplus_posts( $the_term );
			social_mentions_get_flickr_posts( $the_term );
		}
	}
}// end social_mentions_get_api_hashtags

add_action( 'social_mentions_do_api_calls', 'social_mentions_get_api_hashtags' );
