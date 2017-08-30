<?php
//////////////////////////////////////////////////////
// Admin Menu
//////////////////////////////////////////////////////

function social_mentions_admin_menu() {
	add_menu_page(
		'Social Mentions',
		'Social Mentions',
		'manage_options',
		'social-mentions',
		'social_mentions_settings_page',
		'dashicons-format-status',
		'80'
	);
	add_submenu_page(
		'social-mentions',
		'Hashtags',
		'Hashtags',
		'manage_options',
		'edit-tags.php?taxonomy=socment-hashtags'
	);
	add_submenu_page(
		'social-mentions',
		'Settings',
		'Settings',
		'manage_options',
		'social-mentions-settings',
		'social_mentions_settings_page'
	);
	remove_submenu_page( 'social-mentions', 'social-mentions' );
}// end social_mentions_admin_menu

add_action( 'admin_menu', 'social_mentions_admin_menu' );
