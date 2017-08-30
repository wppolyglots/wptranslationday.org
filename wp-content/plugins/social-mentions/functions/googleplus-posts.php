<?php
//////////////////////////////////////////////////////
// Insert Google+ Posts
//////////////////////////////////////////////////////

function social_mentions_get_googleplus_posts( $the_term ) {
	$tr_options = get_option( 'social_mentions_options' );
	if ( 'yes' == $tr_options['social_mentions_googleplus_enabled'] ) {
		if ( empty( $tr_options['social_mentions_googleplus_key'] ) ) {
			return;
		}

		$url = SOCIAL_MENTIONS_GOOGLEPLUS_API . 'v1/activities?maxResults=20&query=%23' . $the_term . '&key=' . $tr_options['social_mentions_googleplus_key'];

		$response = wp_remote_get( $url );
		$the_result = wp_remote_retrieve_body( $response );
		$body = json_decode( $the_result, true );

		$tag_user = get_user_by( 'login', 'SocialMentions' );

		if ( ! empty( $body['items'] ) ) {
			foreach ( $body['items'] as $gplus_post ) {

				$socment_post_title = sanitize_text_field( $gplus_post['title'] );
				$socment_post_title = wp_trim_words( $socment_post_title, 5, '...' );
				$socment_post_content = sanitize_text_field( $gplus_post['object']['content'] );
				$post_date = sanitize_text_field( $gplus_post['updated'] );
				$post_date = str_replace( 'T', ' ', $post_date );
				$socment_post_date = substr( $post_date, 0, -5 );
				$socment_post_id = sanitize_text_field( $gplus_post['id'] );
				$socment_post_user = sanitize_text_field( $gplus_post['actor']['displayName'] );
				$socment_post_username = sanitize_text_field( $gplus_post['actor']['displayName'] );
				$socment_post_profile_url = esc_url( $gplus_post['actor']['url'] );
				$socment_post_profile_img = esc_url( $gplus_post['actor']['image']['url'] );
				if ( ! empty( $gplus_post['object']['attachments']['0']['image']['url'] ) ) {
					$socment_post_content_img = esc_url( $gplus_post['object']['attachments']['0']['image']['url'] );
				} else {
					$socment_post_content_img = '';
				}
				$socment_post_url = esc_url( $gplus_post['url'] );

				// Create post object
				$my_post = array(
					'post_title' => $socment_post_title,
					'post_content' => $socment_post_content,
					'post_date' => $socment_post_date,
					'post_status' => 'publish',
					'post_author' => $tag_user->ID,
					'post_type' => 'socment-googleplus',
					'meta_input' => array(
						'socment_meta_id' => $socment_post_id,
						'socment_meta_name' => $socment_post_user,
						'socment_meta_username' => $socment_post_username,
						'socment_meta_profile_url' => $socment_post_profile_url,
						'socment_meta_profile_img' => $socment_post_profile_img,
						'socment_meta_img' => $socment_post_content_img,
						'socment_meta_url' => $socment_post_url,
					),
				);

				// Check if post exists in database
				$find_post = array(
					'post_type' => 'socment-googleplus',
					'meta_query' => array(
						array(
							'key' => 'socment_meta_id',
							'value' => $socment_post_id,
						),
					),
				);

				$post_exists = new WP_Query( $find_post );

				if ( ! $post_exists->have_posts() ) {
					// Insert the post into the database
					$my_post_id = wp_insert_post( $my_post );
					// Add term to post
					if ( $my_post_id ) {
						wp_set_object_terms( $my_post_id, $the_term, 'socment-hashtags', true );
					}
				}
			}// end foreach ( $body['items'] as $gplus_post )
		}
	}
}// end social_mentions_get_googleplus_posts
