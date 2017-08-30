<?php
//////////////////////////////////////////////////////
// Insert Flickr Posts
//////////////////////////////////////////////////////

function social_mentions_get_flickr_posts( $the_term ) {
	$tr_options = get_option( 'social_mentions_options' );
	if ( 'yes' == $tr_options['social_mentions_flickr_enabled'] ) {
		if ( empty( $tr_options['social_mentions_flickr_key'] ) ) {
			return;
		}

		$latest_flickr_date = new WP_Query(
			array(
				'post_type' => 'socment-flickr',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'orderby' => 'modified',
				'order' => 'DESC',
			)
		);

		if ( $latest_flickr_date->have_posts() ) {
			$min_date = $latest_flickr_date->posts[0]->post_modified;
		} else {
			$today = date( 'Y-m-d 0:00:00' );
			$min_date = date( 'Y-m-d 0:00:00', strtotime( $today . '-30 days' ) );
		}

		$url = SOCIAL_MENTIONS_FLICKR_API . '?method=flickr.photos.search&tags=' . $the_term . '&per_page=20&min_upload_date=' . $min_date . '&extras=date_upload,description,owner_name,url_n,url_l,icon_farm,icon_server&format=json&nojsoncallback=1&api_key=' . $tr_options['social_mentions_flickr_key'];

		$response = wp_remote_get( $url );
		$the_result = wp_remote_retrieve_body( $response );
		$body = json_decode( $the_result, true );
		$tag_user = get_user_by( 'login', 'SocialMentions' );

		if ( ! empty( $body['photos']['photo'] ) ) {
			foreach ( $body['photos']['photo'] as $flickr_post ) {

				$flickr_owner = sanitize_text_field( $flickr_post['owner'] );
				$flickr_iconfarm = sanitize_text_field( $flickr_post['iconfarm'] );
				$flickr_iconserver = sanitize_text_field( $flickr_post['iconserver'] );
				$flickr_smallimg = esc_url( $flickr_post['url_n'] );
				$flickr_bigimg = esc_url( $flickr_post['url_l'] );

				$socment_post_title = sanitize_text_field( $flickr_post['title'] );
				$socment_post_title = wp_trim_words( $socment_post_title, 5, '...' );
				$socment_post_content = sanitize_text_field( $flickr_post['description']['_content'] );
				$post_date = sanitize_text_field( $flickr_post['dateupload'] );
				$socment_post_date = date( 'Y-m-d H:i:s', $post_date );
				$socment_post_id = sanitize_text_field( $flickr_post['id'] );
				$socment_post_user = sanitize_text_field( $flickr_post['ownername'] );
				$socment_post_username = sanitize_text_field( $flickr_post['ownername'] );
				$socment_post_profile_url = 'https://www.flickr.com/photos/' . $flickr_owner . '/';
				$socment_post_profile_img = 'http://farm' . $flickr_iconfarm . '.staticflickr.com/' . $flickr_iconserver . '/buddyicons/' . $flickr_owner . '.jpg';

				if ( null != $flickr_bigimg || null != $flickr_smallimg ) {
					$socment_post_content_img = $flickr_bigimg;
				} elseif ( null != $flickr_bigimg || null == $flickr_smallimg ) {
					$socment_post_content_img = $flickr_bigimg;
				} elseif ( null == $flickr_bigimg || null != $flickr_smallimg ) {
					$socment_post_content_img = $flickr_smallimg;
				} else {
					$socment_post_content_img = '';
				}

				$socment_post_url = 'https://www.flickr.com/photos/' . $flickr_owner . '/' . $socment_post_id;

				// Create post object
				$my_post = array(
					'post_title' => $socment_post_title,
					'post_content' => $socment_post_content,
					'post_date' => $socment_post_date,
					'post_status' => 'publish',
					'post_author' => $tag_user->ID,
					'post_type' => 'socment-flickr',
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
					'post_type' => 'socment-flickr',
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
			}// end foreach ( $body['photos']['photo'] as $flickr_post )
		}
	}
}// end social_mentions_get_flickr_posts
