<?php
/*
Plugin Name: GWTD Speakers List
Plugin URI: https://wptranslationday.org
Description: This plugin will deal with the list of.
Version: 0.1
Author: Xenos (xkon) Konstantinos
Author URI: https://xkon.gr
License: GPL2
*/

function gwtd_speakers_list_admin_menu() {
	add_menu_page(
		'GWTD Speakers',
		'GWTD Speakers',
		'manage_options',
		'gwtd-speakers-list',
		'gwtd_speakers_list_page',
		'dashicons-format-status',
		'30'
	);
}// end bloginfotest_admin_menu
add_action( 'admin_menu', 'gwtd_speakers_list_admin_menu' );

function gwtd_speakers_list_page() {
	echo '<h1>Speakers List</h1>';
	echo '<h4>This list is gathered from the form submissions</h4>';

	require_once( CFCORE_PATH . 'classes/admin.php' );

	$form_id = 'CF5978d3104ee98';
	$data = Caldera_Forms_Admin::get_entries( $form_id, 1, 9999999 );
	$entries = $data['entries'];
	$counter = 0;
	echo '<div class="postbox-group">';
	foreach ( $entries as $entry ) {
	?>
	<div class="postbox">
		<div class="inside">
			<div class="main">
				<div id="minor-publishing-actions" style="text-align:left;">
					<?php
					echo '<strong>Name: </strong> ' . $entry['data']['name'] . ' ' . $entry['data']['last_name'] . '<br/>';
					echo '<strong>Slack: </strong> ' . $entry['data']['your_wordpress_slack_username'] . '<br/>';
					echo '<strong>Country: </strong> ' . $entry['data']['country'] . '<br/>';
					echo '<strong>Presentation Languages</strong> ' . $entry['data']['presentation_languages'] . '<br/><br/>';
					echo '<strong>Talk Title: </strong> ' . $entry['data']['title_what_would_you_like_to_talk_about'] . '<br/><br/>';
					echo '<strong>Talk Summary: </strong><br/>' . $entry['data']['talk_summary'] . '<br/><br/>';
					echo '<strong>Talk Format: </strong> ' . $entry['data']['talk_format'] . '<br/>';
					echo '<strong>Talk Length: </strong> ' . $entry['data']['presentation_length'] . '<br/>';
					echo '<strong>Talk Audience: </strong> ' . $entry['data']['my_topics_primary_audience_is_one_of_the_following'];
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	if ( 0 == ++$counter % 3 ) {
		echo '</div><div class="postbox-group">';
	}
	}
	echo '</div>';
	?>
	<style>
		.postbox {
			width: 30%;
			float:left;
			margin: 8px;
		}
		.postbox-group {
			display: block;
			width: 100%;
			clear: both;
		}
	</style>
<?php
}// end gwtd_speakers_list_page
