<?php
/**
 * Plugin Name: Debug Bar Transients
 * Version: 0.5
 * Description: Adds information about the WordPress Transient API to Debug Bar.
 * Author: Dominik Schilling
 * Author URI: https://wphelper.de/
 * Plugin URI: https://dominikschilling.de/wp-plugins/debug-bar-transients/en/
 *
 * Text Domain: debug-bar-transients
 *
 * License: GPLv2 or later
 *
 *	Copyright (C) 2011-2016 Dominik Schilling
 *
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Don't call this file directly.
 */
if ( ! class_exists( 'WP' ) ) {
	die();
}

/**
 * Adds panel, as defined in the included class, to Debug Bar.
 *
 * @param $panels array
 * @return array
 */
function ds_add_debug_bar_transients_panel( $panels ) {
	if ( ! class_exists( 'DS_Debug_Bar_Transients' ) ) {
		include( 'class-debug-bar-transients.php' );
		$panels[] = new DS_Debug_Bar_Transients();
	}

	return $panels;
}
add_filter( 'debug_bar_panels', 'ds_add_debug_bar_transients_panel' );

/**
 * Adds the AJAX callback function for deleting a transient from the Debug Bar.
 */
function ds_ajax_delete_transient() {
	if ( ! is_super_admin() ) {
		die( '-1' );
	}

	check_ajax_referer( 'ds-delete-transient' );

	if ( empty( $_POST['transient-type'] ) ) {
		$ret = delete_transient( $_POST['transient-name'] );
	} else {
		$ret = delete_site_transient( $_POST['transient-name'] );
	}

	$ret = $ret ? '1' : '0';

	die( $ret );
}
add_action( 'wp_ajax_ds_delete_transient', 'ds_ajax_delete_transient' );
