<p>Instructions:</p>
<p>1. You can obtain the Client ID &amp; Client Secret by logging into <a href="https://www.instagram.com/developer/">Instagram's developer portal</a>, and then registering a new client. Insert them to the fields bellow and click <strong>'Save Changes'</strong>.</p>
<p></p>
<p>2. Copy the Redirect URL from the field below and paste it in your <strong>Valid redirect URIs</strong> field in your Instagram API Client Settings.</p>
<p></p>
<p>3. <a href="" id="get_access_token">Click here to get your Access Token!</a> - After the Access Token is in the field please click <strong>'Save Changes'</strong>.</p>
<p></p>
<p><strong>Note:</strong> Sandbox mode will retrieve your account's 9 latest posts ignoring the #hashtag. Non-sandbox will retrieve the latest hashtags posts from all instagram as long as there is permission for 'public_content' in your client.</p>
<?php

$tggroptions = get_option( 'tggr_settings', array() );

$cid = $tggroptions['TGGRSourceInstagram']['client_id'];
$cse = $tggroptions['TGGRSourceInstagram']['client_secret'];
$cre = $tggroptions['TGGRSourceInstagram']['redirect_url'];
$fat = $tggroptions['TGGRSourceInstagram']['access_token'];

if ( ! empty( $_GET['code'] ) ) {
	$icode = sanitize_text_field( $_GET['code'] );
} else {
	$icode = '';
}

if ( $icode !== '' && $fat === '' ) {

	$uri = 'https://api.instagram.com/oauth/access_token';
	$data = [
		'client_id' => $cid,
		'client_secret' => $cse,
		'grant_type' => 'authorization_code',
		'redirect_uri' => $cre,
		'code' => $icode,
	];

	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_URL, $uri ); // uri
	curl_setopt( $ch, CURLOPT_POST, true ); // POST
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $data ); // POST DATA
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); // RETURN RESULT true
	curl_setopt( $ch, CURLOPT_HEADER, 0 ); // RETURN HEADER false
	curl_setopt( $ch, CURLOPT_NOBODY, 0 ); // NO RETURN BODY false / we need the body to return
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 ); // VERIFY SSL HOST false
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // VERIFY SSL PEER false

	$result = json_decode( curl_exec( $ch ), true ); // execute curl

	$at = $result['access_token'];

	if ( $at !== '' ) {
		?>
		<script>
			(function( $ ) {
				$( document ).ready( function() {
					$( '#tggr_instagram_access_token' ).val( '<?php echo $at; ?>' );
				});
			})( jQuery );
		</script>
		<?php
	}
}
?>
<script>
(function( $ ) {
	$( document ).ready( function() {
		$( '#tggr_instagram_redirect_url' ).val(window.location.href);
		$( '#get_access_token' ).attr('href', 'https://www.instagram.com/oauth/authorize/?client_id=' + $('#tggr_instagram_client_id').val() + '&redirect_uri=' + $( '#tggr_instagram_redirect_url' ).val() + '&response_type=code');
	});
})( jQuery );
</script>