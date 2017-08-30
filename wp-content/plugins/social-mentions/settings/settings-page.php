<?php
//////////////////////////////////////////////////////
// Settings Page
//////////////////////////////////////////////////////

function social_mentions_settings_page() {
	?>
	<div class="wrap">
		<form method="post" action="options.php">
			<?php
			settings_fields( 'social_mentions_options' );
			?>
			<h1>Social Mentions</h1>
			<div id="dashboard-widgets-wrap">
				<div id="dashboard-widgets" class="metabox-holder">
					<div id="twitter-settings" class="postbox">
						<div class="inside">
							<div class="main">
								<div id="minor-publishing-actions" style="text-align:left;">
									<?php
									do_settings_sections( 'social_mentions_hashtag' );
									?>
									<p>Input the hashtags that you want to grab here separating them with commas
										i.e.
										<span style="color:blue;">#WordPress, #something</span></p>
									<p>You can then add the shortcode i.e.
										<span style="color:blue;">[social-mentions show="#WordPress"]</span>
										or
										<span style="color:blue;">[social-mentions show="#WordPress, #something"]</span>
										in your page to show the shortcodes you want.</p>
									<p>Add the css classes for each of the shortcode card holder separated with
										spaces i.e. <span style="color:blue;">col-md-4 text-center</span></p>
								</div>
							</div>
						</div>
					</div>
					<div id="twitter-settings" class="postbox">
						<div class="inside">
							<div class="main">
								<div id="minor-publishing-actions" style="text-align:left;">
									<?php
									do_settings_sections( 'social_mentions_twitter' );
									?>
									<p>Get your API Key & Secret at
										<a href="<?php echo SOCIAL_MENTIONS_TWITTER_DEV_PORTAL; ?>">Twitter's
											developer portal</a>.</p>
								</div>
							</div>
						</div>
					</div>
					<div id="flickr-settings" class="postbox">
						<div class="inside">
							<div class="main">
								<div id="minor-publishing-actions" style="text-align:left;">
									<?php
									do_settings_sections( 'social_mentions_instagram' );
									?>
									<p>Instructions:</p>
									<p>1. You can obtain the Client ID &amp; Secret by logging into
										<a href="https://www.instagram.com/developer/">Instagram's developer
											portal</a>, and registering a new client. Copy them to the
										fields above and click <strong>'Save'</strong>.</p>
									<p></p>
									<p>2. Copy the Redirect URL from the field above and paste it in your
										<strong>Valid redirect URIs</strong> field in your Instagram API Client
										Settings.</p>
									<p></p>
									<p>3. <a href="" id="get_access_token">Click here to get your Access
											Token!</a> - After the Access Token is in the field please click
										<strong>'Save'</strong>.</p>
									<p></p>
									<p><strong>Note:</strong> Sandbox mode will retrieve your account's
										posts ignoring the #hashtag. Non-sandbox will retrieve the latest
										hashtags posts from all instagram as long as there is permission for
										'public_content' in your client.</p>
								</div>
							</div>
						</div>
					</div>
					<div id="flickr-settings" class="postbox">
						<div class="inside">
							<div class="main">
								<div id="minor-publishing-actions" style="text-align:left;">
									<?php
									do_settings_sections( 'social_mentions_googleplus' );
									?>
									<p>Get your API Key at
										<a href="<?php echo SOCIAL_MENTIONS_GOOGLEPLUS_DEV_PORTAL; ?>">Google
											Developers Console</a>.</p>
								</div>
							</div>
						</div>
					</div>
					<div id="flickr-settings" class="postbox">
						<div class="inside">
							<div class="main">
								<div id="minor-publishing-actions" style="text-align:left;">
									<?php
									do_settings_sections( 'social_mentions_flickr' );
									?>
									<p>Get your API Key at
										<a href="<?php echo SOCIAL_MENTIONS_FLICKR_DEV_PORTAL; ?>">the App
											Garden</a>.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="submit" name="save" id="save" class="button button-primary button-large" value="Save"/>
		</form>
	</div>
	<?php

	$tr_options = get_option( 'social_mentions_options', array() );
	// create terms if they don't exist
	if ( ! empty( $tr_options['social_mentions_hashtag_key'] ) ) {
		// create array from hashtags
		$hashtags = explode( ',', $tr_options['social_mentions_hashtag_key'] );

		// create terms if they don't exist
		foreach ( $hashtags as $hashtag ) {
			$remove_hashtag_spaces = str_replace( ' ', '', $hashtag );
			$cleantag = str_replace( '#', '', $remove_hashtag_spaces );
			$term = term_exists( $cleantag, 'socment-hashtags' );
			if ( 0 == $term && null == $term ) {
				wp_insert_term(
					$cleantag,
					'socment-hashtags',
					array(
						'description' => '#' . $cleantag,
						'slug' => $cleantag,
					)
				);
			}
		}// end foreach ( $hashtags as $hashtag )
	}
	// end create terms if they don't exist

	$access_token = '';
	$insta_client_id = '';

	if ( ! empty( $tr_options ) ) {
		$insta_client_id = $tr_options['social_mentions_instagram_client_id'];
		$insta_client_secret = $tr_options['social_mentions_instagram_client_secret'];
		$insta_redirect_url = $tr_options['social_mentions_instagram_redirect_url'];
		$insta_access_token = $tr_options['social_mentions_instagram_access_token'];
	}

	if ( ! empty( $_GET['code'] ) ) {
		$instagram_code = sanitize_text_field( $_GET['code'] );
	} else {
		$instagram_code = '';
	}

	if ( '' != $instagram_code && '' == $insta_access_token ) {
		$response = wp_remote_post(
			SOCIAL_MENTIONS_INSTAGRAM_API_TOKEN,
			array(
				'method' => 'POST',
				'timeout' => 45,
				'body' => array(
					'client_id' => $insta_client_id,
					'client_secret' => $insta_client_secret,
					'grant_type' => 'authorization_code',
					'redirect_uri' => $insta_redirect_url,
					'code' => $instagram_code,
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "Something went wrong: $error_message";
		} else {
			$decode_response = json_decode( $response['body'], true );
			$access_token = $decode_response['access_token'];
		}

		if ( '' != $access_token ) {
			?>
			<script>
				(function ( $ ) {
					$( document ).ready( function () {
						$( '#social_mentions_instagram_access_token' ).val( '<?php echo $access_token; ?>' );
					} );
				})( jQuery );
			</script>
			<?php
		}
	}
	?>
	<script>
		(function ( $ ) {
			$( document ).ready( function () {
				$( '#social_mentions_instagram_redirect_url' ).val( window.location.href );
				<?php if ( '' != $insta_client_id ) { ?>
				$( '#get_access_token' ).attr( 'href', 'https://www.instagram.com/oauth/authorize/?client_id=<?php echo $insta_client_id; ?>&redirect_uri=<?php echo $insta_redirect_url; ?>&response_type=code' );
				<?php } ?>
			} );
		})( jQuery );
	</script>
	<?php
} // end social_mentions_settings_page
?>
