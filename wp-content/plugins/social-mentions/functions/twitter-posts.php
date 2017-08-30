<?php
//////////////////////////////////////////////////////
// Insert Twitter Posts
//////////////////////////////////////////////////////

function social_mentions_get_twitter_posts( $the_term ) {
	$tr_options = get_option( 'social_mentions_options' );
	if ( 'yes' == $tr_options['social_mentions_twitter_enabled'] ) {
		if ( empty( $tr_options['social_mentions_twitter_key'] ) || empty( $tr_options['social_mentions_twitter_secret'] ) ) {
			return;
		}

		$twitter_key = $tr_options['social_mentions_twitter_key'];
		$twitter_secret = $tr_options['social_mentions_twitter_secret'];

		$latest_tweet = new WP_Query(
			array(
				'post_type' => 'socment-twitter',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'orderby' => 'modified',
				'order' => 'DESC',
			)
		);

		if ( $latest_tweet->have_posts() ) {
			$since_id = $latest_tweet->posts[0]->ID;
			$since_id = get_post_meta( $since_id, 'socment_meta_id', true );
		} else {
			$since_id = '';
		}

		$url = SOCIAL_MENTIONS_TWITTER_API . '1.1/search/tweets.json?q=' . $the_term . '&count=20&since_id=' . $since_id;

		function twitter_call_the_api( $the_term, $url, $twitter_access_token ) {
			$response = wp_remote_get(
				$url,
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $twitter_access_token,
					),
				)
			);

			$the_result = json_decode( wp_remote_retrieve_body( $response ), true );
			$tag_user = get_user_by( 'login', 'SocialMentions' );

			if ( ! empty( $the_result['statuses'] ) ) {
				foreach ( $the_result['statuses'] as $twitter_post ) {

					$socment_post_title = sanitize_text_field( $twitter_post['text'] );
					$socment_post_title = wp_trim_words( $socment_post_title, 5, '...' );
					$socment_post_content = sanitize_text_field( $twitter_post['text'] );
					$post_date = sanitize_text_field( $twitter_post['created_at'] );
					$socment_post_date = date( 'Y-m-d H:i:s', strtotime( $post_date ) );
					$socment_post_id = sanitize_text_field( $twitter_post['id_str'] );
					$socment_post_user = sanitize_text_field( $twitter_post['user']['name'] );
					$socment_post_username = sanitize_text_field( $twitter_post['user']['screen_name'] );
					$socment_post_profile_url = 'https://www.twitter.com/' . $socment_post_username;
					$socment_post_profile_img = esc_url( $twitter_post['user']['profile_image_url_https'] );
					if ( ! empty( $twitter_post['entities']['media'][0]['media_url_https'] ) ) {
						$socment_post_content_img = esc_url( $twitter_post['entities']['media'][0]['media_url_https'] );
					} else {
						$socment_post_content_img = '';
					}
					$socment_post_url = 'https://www.twitter.com/' . $socment_post_username . '/status/' . $socment_post_id;

					// Create post object
					$my_post = array(
						'post_title' => $socment_post_title,
						'post_content' => $socment_post_content,
						'post_date' => $socment_post_date,
						'post_status' => 'publish',
						'post_author' => $tag_user->ID,
						'post_type' => 'socment-twitter',
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
						'post_type' => 'socment-twitter',
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
				}// end foreach ( $the_result['statuses'] as $twitter_post )
			}
		}

		function get_bearer_token( $the_term, $url, $credentials ) {
			$response = wp_remote_post(
				SOCIAL_MENTIONS_TWITTER_API . 'oauth2/token',
				array(
					'headers' => array(
						'Authorization' => 'Basic ' . $credentials,
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
					),
					'body' => 'grant_type=client_credentials',
				)
			);

			$twitter_access_token = json_decode( wp_remote_retrieve_body( $response ) );
			if ( isset( $twitter_access_token->token_type ) && 'bearer' == $twitter_access_token->token_type ) {
				$twitter_access_token = $twitter_access_token->access_token;
			} else {
				$twitter_access_token = false;
			}
			twitter_call_the_api( $the_term, $url, $twitter_access_token );
		}

		function get_bearer_credentials( $the_term, $url, $twitter_key, $twitter_secret ) {
			$credentials = $twitter_key . ':' . $twitter_secret;
			$credentials = base64_encode( $credentials );
			get_bearer_token( $the_term, $url, $credentials );
		}

		get_bearer_credentials( $the_term, $url, $twitter_key, $twitter_secret );
	}
}// end social_mentions_get_twitter_posts
