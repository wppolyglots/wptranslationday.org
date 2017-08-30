<?php
/*
 * @package Social Mentions
 * @version 1.0.1
 *
 * Plugin Name:       Social Mentions
 * Plugin URI:        https://xkon.gr/social-mentions/
 * Description:       Gathers posts with certain #hashtags from various social media sources.
 * Version:           1.0.1
 * Author:            Xenos (xkon) Konstantinos
 * Author URI:        https://xkon.gr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       social-mentions
 * Domain Path:       /languages
 *
*/

//////////////////////////////////////////////////////
// If this file is called directly, abort
//////////////////////////////////////////////////////
if ( ! defined( 'WPINC' ) ) {
	die;
}

//////////////////////////////////////////////////////
// Settings
//////////////////////////////////////////////////////

define( 'SOCIAL_MENTIONS_VER' , '1.0.1');

define( 'SOCIAL_MENTIONS_TWITTER_DEV_PORTAL', 'https://apps.twitter.com/' );
define( 'SOCIAL_MENTIONS_TWITTER_API', 'https://api.twitter.com/' );

define( 'SOCIAL_MENTIONS_INSTAGRAM_DEV_PORTAL', 'https://www.instagram.com/developer/' );
define( 'SOCIAL_MENTIONS_INSTAGRAM_API', 'https://api.instagram.com/' );
define( 'SOCIAL_MENTIONS_INSTAGRAM_API_TOKEN', 'https://api.instagram.com/oauth/access_token/' );

define( 'SOCIAL_MENTIONS_FLICKR_DEV_PORTAL', 'https://www.flickr.com/services/' );
define( 'SOCIAL_MENTIONS_FLICKR_API', 'https://secure.flickr.com/services/rest/' );

define( 'SOCIAL_MENTIONS_GOOGLEPLUS_DEV_PORTAL', 'https://console.developers.google.com/' );
define( 'SOCIAL_MENTIONS_GOOGLEPLUS_API', 'https://www.googleapis.com/plus/' );

//////////////////////////////////////////////////////
// Create cron job
//////////////////////////////////////////////////////

// Custom Cron Recurrences
function social_mentions_do_api_calls( $schedules ) {
	$schedules['socment30'] = array(
		'display' => '30 minutes',
		'interval' => 1800,
	);
	return $schedules;
}

add_filter( 'cron_schedules', 'social_mentions_do_api_calls' );

/////////////////////////////// register cron every 5 minutes
function social_mentions_activation() {
	if ( ! wp_next_scheduled( 'social_mentions_do_api_calls' ) ) {
		wp_schedule_event( time(), 'socment30', 'social_mentions_do_api_calls' );
	}
}// end social_mentions_activation

register_activation_hook( __FILE__, 'social_mentions_activation' );


function social_mentions_deactivation() {
	wp_clear_scheduled_hook( 'social_mentions_do_api_calls' );
}// end social_mentions_deactivation

register_deactivation_hook( __FILE__, 'social_mentions_deactivation' );

function social_mentions_load_scripts()
{
	wp_enqueue_style( 'social-mentions', plugin_dir_url( __FILE__ ) . 'css/front-end.css', array(), SOCIAL_MENTIONS_VER );
}
add_action( 'wp_enqueue_scripts', 'social_mentions_load_scripts' );

//////////////////////////////////////////////////////
// Load core files
//////////////////////////////////////////////////////

require_once( plugin_dir_path( __FILE__ ) . 'settings/create-user.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/custom-posts.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/meta-boxes.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/options.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/admin-menus.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/settings-page.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/shortcode.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/cron.php' );
require_once( plugin_dir_path( __FILE__ ) . 'functions/twitter-posts.php' );
require_once( plugin_dir_path( __FILE__ ) . 'functions/instagram-posts.php' );
require_once( plugin_dir_path( __FILE__ ) . 'functions/googleplus-posts.php' );
require_once( plugin_dir_path( __FILE__ ) . 'functions/flickr-posts.php' );

