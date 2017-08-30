<?php
//////////////////////////////////////////////////////
// Insert Instagram Posts
//////////////////////////////////////////////////////

function social_mentions_get_instagram_posts( $the_term ) {
	$tr_options = get_option( 'social_mentions_options' );
	if ( 'yes' == $tr_options['social_mentions_instagram_enabled'] ) {
		if ( empty( $tr_options['social_mentions_instagram_access_token'] ) ) {
			return;
		}

		if ( 'no' == $tr_options['social_mentions_instagram_sandbox'] ) {
			// url for PUBLIC tags // https://api.instagram.com/v1/tags/XXXX/media/recent/?access_token=XXXX
			$url = SOCIAL_MENTIONS_INSTAGRAM_API . 'v1/tags/' . $the_term . '/media/recent?access_token=' . $tr_options['social_mentions_instagram_access_token'];
		} else {
			// url for SELF posts https://api.instagram.com/v1/users/self/media/recent/?access_token=XXXX
			$url = SOCIAL_MENTIONS_INSTAGRAM_API . 'v1/users/self/media/recent?access_token=' . $tr_options['social_mentions_instagram_access_token'];
		}

		$response = wp_remote_get( $url );
		$the_result = wp_remote_retrieve_body( $response );
		$body = json_decode( $the_result, true );
		$tag_user = get_user_by( 'login', 'SocialMentions' );

		if ( ! empty( $body['data'] ) ) {
			foreach ( $body['data'] as $insta_post ) {

				$socment_post_title = sanitize_text_field( $insta_post['caption']['text'] );
				$socment_post_title = wp_trim_words( $socment_post_title, 5, '...' );
				$socment_post_content = sanitize_text_field( $insta_post['caption']['text'] );
				$post_date = date( 'Y-m-d H:i:s', $insta_post['created_time'] );
				$post_date = sanitize_text_field( $post_date );
				$socment_post_date = date( 'Y-m-d H:i:s', strtotime( $post_date ) );
				$socment_post_id = sanitize_text_field( $insta_post['id'] );
				$socment_post_user = sanitize_text_field( $insta_post['user']['full_name'] );
				$socment_post_username = sanitize_text_field( $insta_post['user']['username'] );
				$socment_post_profile_url = 'https://instagram.com/' . $socment_post_username;
				$socment_post_profile_img = esc_url( $insta_post['user']['profile_picture'] );
				$socment_post_content_img = esc_url( $insta_post['images']['standard_resolution']['url'] );
				$socment_post_url = esc_url( $insta_post['link'] );

				// Create post object
				$my_post = array(
					'post_title' => $socment_post_title,
					'post_content' => $socment_post_content,
					'post_date' => $socment_post_date,
					'post_status' => 'publish',
					'post_author' => $tag_user->ID,
					'post_type' => 'socment-instagram',
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
					'post_type' => 'socment-instagram',
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
			}// end foreach ( $body['data'] as $insta_post )
		}
	}
}// end social_mentions_get_instagram_posts
