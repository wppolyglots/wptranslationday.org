<?php
//////////////////////////////////////////////////////
// Register Options
//////////////////////////////////////////////////////

function social_mentions_options() {
	register_setting(
		'social_mentions_options',
		'social_mentions_options',
		'social_mentions_options_validate'
	);

	// general settings
	add_settings_section(
		'social_mentions_hashtag',
		'General Settings',
		'',
		'social_mentions_hashtag'
	);
	add_settings_field(
		'social_mentions_hashtag_key',
		'Hashtags',
		'social_mentions_hashtag_key',
		'social_mentions_hashtag',
		'social_mentions_hashtag'
	);
	add_settings_field(
		'social_mentions_hashtag_css',
		'CSS Classes',
		'social_mentions_hashtag_css',
		'social_mentions_hashtag',
		'social_mentions_hashtag'
	);

	// twitter settings
	add_settings_section(
		'social_mentions_twitter',
		'Twitter Settings',
		'',
		'social_mentions_twitter'
	);
	add_settings_field(
		'social_mentions_twitter_key',
		'Consumer Key',
		'social_mentions_twitter_key',
		'social_mentions_twitter',
		'social_mentions_twitter'
	);
	add_settings_field(
		'social_mentions_twitter_secret',
		'Consumer Secret',
		'social_mentions_twitter_secret',
		'social_mentions_twitter',
		'social_mentions_twitter'
	);
	add_settings_field(
		'social_mentions_twitter_enabled',
		'Enable',
		'social_mentions_twitter_enabled',
		'social_mentions_twitter',
		'social_mentions_twitter'
	);

	// instagram settings
	add_settings_section(
		'social_mentions_instagram',
		'Instagram Settings',
		'',
		'social_mentions_instagram'
	);
	add_settings_field(
		'social_mentions_instagram_client_id',
		'Client Id',
		'social_mentions_instagram_client_id',
		'social_mentions_instagram',
		'social_mentions_instagram'
	);
	add_settings_field(
		'social_mentions_instagram_client_secret',
		'Client Secret',
		'social_mentions_instagram_client_secret',
		'social_mentions_instagram',
		'social_mentions_instagram'
	);
	add_settings_field(
		'social_mentions_instagram_redirect_url',
		'Redirect URL',
		'social_mentions_instagram_redirect_url',
		'social_mentions_instagram',
		'social_mentions_instagram'
	);
	add_settings_field(
		'social_mentions_instagram_access_token',
		'Access Token',
		'social_mentions_instagram_access_token',
		'social_mentions_instagram',
		'social_mentions_instagram'
	);
	add_settings_field(
		'social_mentions_instagram_sandbox',
		'Sandbox',
		'social_mentions_instagram_sandbox',
		'social_mentions_instagram',
		'social_mentions_instagram'
	);
	add_settings_field(
		'social_mentions_instagram_enabled',
		'Enable',
		'social_mentions_instagram_enabled',
		'social_mentions_instagram',
		'social_mentions_instagram'
	);

	// google+ settings
	add_settings_section(
		'social_mentions_googleplus',
		'Google+ Settings',
		'',
		'social_mentions_googleplus'
	);
	add_settings_field(
		'social_mentions_googleplus_key',
		'Google+ Key',
		'social_mentions_googleplus_key',
		'social_mentions_googleplus',
		'social_mentions_googleplus'
	);
	add_settings_field(
		'social_mentions_googleplus_enabled',
		'Enable',
		'social_mentions_googleplus_enabled',
		'social_mentions_googleplus',
		'social_mentions_googleplus'
	);

	// flickr settings
	add_settings_section(
		'social_mentions_flickr',
		'Flickr Settings',
		'',
		'social_mentions_flickr'
	);
	add_settings_field(
		'social_mentions_flickr_key',
		'Flickr Key',
		'social_mentions_flickr_key',
		'social_mentions_flickr',
		'social_mentions_flickr'
	);
	add_settings_field(
		'social_mentions_flickr_enabled',
		'Enable',
		'social_mentions_flickr_enabled',
		'social_mentions_flickr',
		'social_mentions_flickr'
	);
}// end social_mentions_options

add_action( 'admin_init', 'social_mentions_options' );

//////////////////////////////////////////////////////
// Options Output
//////////////////////////////////////////////////////

function social_mentions_options_validate( $options ) {
	foreach ( $options as $key => $option ) {
		$options[ $key ] = sanitize_text_field( $option );
	}
	return $options;
}// end social_mentions_options_validate

function social_mentions_hashtag_key() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_hashtag_key' name='social_mentions_options[social_mentions_hashtag_key]' size='40' type='text' value='{$tr_options['social_mentions_hashtag_key']}' />";
}// end social_mentions_hashtag_key

function social_mentions_hashtag_css() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_hashtag_css' name='social_mentions_options[social_mentions_hashtag_css]' size='40' type='text' value='{$tr_options['social_mentions_hashtag_css']}' />";
}// end social_mentions_hashtag_key

function social_mentions_twitter_key() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_twitter_key' name='social_mentions_options[social_mentions_twitter_key]' size='40' type='text' value='{$tr_options['social_mentions_twitter_key']}' />";
}// end social_mentions_twitter_key

function social_mentions_twitter_secret() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_twitter_secret' name='social_mentions_options[social_mentions_twitter_secret]' size='40' type='text' value='{$tr_options['social_mentions_twitter_secret']}' />";
}// end social_mentions_twitter_secret

function social_mentions_twitter_enabled() {
	$tr_options = get_option( 'social_mentions_options' );
	if ( empty( $tr_options['social_mentions_twitter_enabled'] ) ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} elseif ( 'no' == $tr_options['social_mentions_twitter_enabled'] ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} else {
		$yes_selected = 'selected="selected"';
		$no_selected = '';
	}
	echo '<select id="social_mentions_twitter_enabled" name="social_mentions_options[social_mentions_twitter_enabled]">';
	echo '<option value="no" ' . $no_selected . '>No</option>';
	echo '<option value="yes" ' . $yes_selected . '>Yes</option>';
	echo '</select>';
}// end social_mentions_twitter_enabled

function social_mentions_instagram_client_id() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_instagram_client_id' name='social_mentions_options[social_mentions_instagram_client_id]' size='40' type='text' value='{$tr_options['social_mentions_instagram_client_id']}' />";
}// end social_mentions_instagram_client_id

function social_mentions_instagram_client_secret() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_instagram_client_secret' name='social_mentions_options[social_mentions_instagram_client_secret]' size='40' type='text' value='{$tr_options['social_mentions_instagram_client_secret']}' />";
}// end social_mentions_instagram_client_secret

function social_mentions_instagram_redirect_url() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_instagram_redirect_url' name='social_mentions_options[social_mentions_instagram_redirect_url]' size='40' type='text' value='{$tr_options['social_mentions_instagram_redirect_url']}' />";
}// end social_mentions_instagram_redirect_url

function social_mentions_instagram_access_token() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_instagram_access_token' name='social_mentions_options[social_mentions_instagram_access_token]' size='40' type='text' value='{$tr_options['social_mentions_instagram_access_token']}' />";
}// end social_mentions_instagram_access_token

function social_mentions_instagram_sandbox() {
	$tr_options = get_option( 'social_mentions_options' );
	if ( empty( $tr_options['social_mentions_instagram_sandbox'] ) ) {
		$yes_selected = 'selected="selected"';
		$no_selected = '';
	} elseif ( 'yes' == $tr_options['social_mentions_instagram_sandbox'] ) {
		$yes_selected = 'selected="selected"';
		$no_selected = '';
	} else {
		$yes_selected = '';
		$no_selected = 'selected="selected"';
	}
	echo '<select id="social_mentions_instagram_sandbox" name="social_mentions_options[social_mentions_instagram_sandbox]">';
	echo '<option value="yes" ' . $yes_selected . '>Yes</option>';
	echo '<option value="no" ' . $no_selected . '>No</option>';
	echo '</select>';
}// end social_mentions_instagram_sandbox

function social_mentions_instagram_enabled() {
	$tr_options = get_option( 'social_mentions_options' );
	if ( empty( $tr_options['social_mentions_instagram_enabled'] ) ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} elseif ( 'no' == $tr_options['social_mentions_instagram_enabled'] ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} else {
		$yes_selected = 'selected="selected"';
		$no_selected = '';
	}
	echo '<select id="social_mentions_instagram_enabled" name="social_mentions_options[social_mentions_instagram_enabled]">';
	echo '<option value="no" ' . $no_selected . '>No</option>';
	echo '<option value="yes" ' . $yes_selected . '>Yes</option>';
	echo '</select>';
}// end social_mentions_instagram_enabled

function social_mentions_flickr_key() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_flickr_key' name='social_mentions_options[social_mentions_flickr_key]' size='40' type='text' value='{$tr_options['social_mentions_flickr_key']}' />";
}// end social_mentions_flickr_key

function social_mentions_flickr_enabled() {
	$tr_options = get_option( 'social_mentions_options' );
	if ( empty( $tr_options['social_mentions_flickr_enabled'] ) ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} elseif ( 'no' == $tr_options['social_mentions_flickr_enabled'] ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} else {
		$yes_selected = 'selected="selected"';
		$no_selected = '';
	}
	echo '<select id="social_mentions_flickr_enabled" name="social_mentions_options[social_mentions_flickr_enabled]">';
	echo '<option value="no" ' . $no_selected . '>No</option>';
	echo '<option value="yes" ' . $yes_selected . '>Yes</option>';
	echo '</select>';
}// end social_mentions_flickr_enabled

function social_mentions_googleplus_key() {
	$tr_options = get_option( 'social_mentions_options' );
	echo "<input id='social_mentions_googleplus_key' name='social_mentions_options[social_mentions_googleplus_key]' size='40' type='text' value='{$tr_options['social_mentions_googleplus_key']}' />";
}// end social_mentions_googleplus_key

function social_mentions_googleplus_enabled() {
	$tr_options = get_option( 'social_mentions_options' );
	if ( empty( $tr_options['social_mentions_googleplus_enabled'] ) ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} elseif ( 'no' == $tr_options['social_mentions_googleplus_enabled'] ) {
		$no_selected = 'selected="selected"';
		$yes_selected = '';
	} else {
		$yes_selected = 'selected="selected"';
		$no_selected = '';
	}
	echo '<select id="social_mentions_googleplus_enabled" name="social_mentions_options[social_mentions_googleplus_enabled]">';
	echo '<option value="no" ' . $no_selected . '>No</option>';
	echo '<option value="yes" ' . $yes_selected . '>Yes</option>';
	echo '</select>';
}// end social_mentions_googleplus_enabled