<?php
//////////////////////////////////////////////////////
// Create User
//////////////////////////////////////////////////////

function social_mentions_create_user() {
	if ( ! username_exists( 'SocialMentions' ) ) {
		wp_insert_user(
			array(
				'user_pass' => wp_generate_password( 100, true, true ),
				'user_login' => 'SocialMentions',
				'user_email' => 'social@ment.ions',
				'user_role' => 'Subscriber',
			)
		);
	}
}// end social_mentions_create_user

add_action( 'init', 'social_mentions_create_user' );
