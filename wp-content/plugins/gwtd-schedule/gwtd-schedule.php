<?php

/*
Plugin Name: GWTD Schedule
Plugin URI: https://wptranslationday.org
Description: Custom Admin CPT for the event schedule combined with a front-end page.
Version: 0.1
Author: Xenos (xkon) Konstantinos
Author URI: https://xkon.gr
License: GPL2
*/

/*
 * Load CSS
*/

function gwtd_schedule_load_scripts() {
	wp_enqueue_style( 'gwtd-schedule', plugin_dir_url( __FILE__ ) . '/style.css', array(), '0.1' );
}

add_action( 'admin_enqueue_scripts', 'gwtd_schedule_load_scripts' );

/*
 * Create CPT
*/

function schedule_post_type() {

	$labels = array(
		'name'                  => _x( 'Schedule', 'Post Type General Name', 'gwtd' ),
		'singular_name'         => _x( 'Schedule', 'Post Type Singular Name', 'gwtd' ),
		'menu_name'             => __( 'Schedule', 'gwtd' ),
		'name_admin_bar'        => __( 'Schedule', 'gwtd' ),
		'archives'              => __( 'Item Archives', 'gwtd' ),
		'attributes'            => __( 'Item Attributes', 'gwtd' ),
		'parent_item_colon'     => __( 'Parent Item:', 'gwtd' ),
		'all_items'             => __( 'All Items', 'gwtd' ),
		'add_new_item'          => __( 'Add New Item', 'gwtd' ),
		'add_new'               => __( 'Add New', 'gwtd' ),
		'new_item'              => __( 'New Item', 'gwtd' ),
		'edit_item'             => __( 'Edit Item', 'gwtd' ),
		'update_item'           => __( 'Update Item', 'gwtd' ),
		'view_item'             => __( 'View Item', 'gwtd' ),
		'view_items'            => __( 'View Items', 'gwtd' ),
		'search_items'          => __( 'Search Item', 'gwtd' ),
		'not_found'             => __( 'Not found', 'gwtd' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gwtd' ),
		'featured_image'        => __( 'Featured Image', 'gwtd' ),
		'set_featured_image'    => __( 'Set featured image', 'gwtd' ),
		'remove_featured_image' => __( 'Remove featured image', 'gwtd' ),
		'use_featured_image'    => __( 'Use as featured image', 'gwtd' ),
		'insert_into_item'      => __( 'Insert into item', 'gwtd' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'gwtd' ),
		'items_list'            => __( 'Items list', 'gwtd' ),
		'items_list_navigation' => __( 'Items list navigation', 'gwtd' ),
		'filter_items_list'     => __( 'Filter items list', 'gwtd' ),
	);
	$args = array(
		'label'                 => __( 'Schedule', 'gwtd' ),
		'description'           => __( 'Schedule', 'gwtd' ),
		'labels'                => $labels,
		'supports'              => array( 'thumbnail', 'title', 'editor' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite'            => array( 'slug' => 'schedule' ),
	);
	register_post_type( 'gwtd_schedule', $args );

}
add_action( 'init', 'schedule_post_type', 0 );

/*
 * Add metaboxes to Schedule
 */

function schedule_metaboxes() {
	global $wp_meta_boxes;
	add_meta_box(
		'mbox_schedule',
		__( 'Fields' ),
		'gwtds_metaboxes_metaboxes_html',
		'gwtd_schedule',
		'normal',
		'high'
	);
}// end gwtdle_metaboxes

add_action( 'add_meta_boxes_gwtd_schedule', 'schedule_metaboxes' );

/**
 * Setup metaboxes
 */
function gwtds_metaboxes_metaboxes_html() {
	global $post;
	$custom = get_post_custom( $post->ID );
	$arr = array(
		't_speaker' => __( 'Speaker' ),
		't_time' => __( 'Talk Time UTC' ),
		't_duration' => __( 'Talk Duration' ),
		't_type' => __( 'Talk Type' ),
		't_live' => __( 'Talk Live' ),
		't_audience' => __( 'Target Audience' ),
		't_language' => __( 'Target Language' ),
		't_recording_link' => __( 'Recording URL' ),
	);
	?>
	<table id="schedule-custom-fields">
		<?php
		foreach ( $arr as $key => $item ) {
			$value = isset( $custom[ $key ][0] ) ? $custom[ $key ][0] : '';
			if ( 't_speaker' == $key ) {

				$speaker = new WP_Query( array(
					'post_type' => 'gwtd_speakers',
					'order' => 'ASC',
					'posts_per_page' => -1,
					'order' => 'ASC',
				) );
				echo '<tr><td>Speaker: </td><td>';
				echo '<select name="' . $key . '">';
				while ( $speaker->have_posts() ) :
					$speaker->the_post();
					if ( get_the_ID() == $value ) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					echo '<option value="' . get_the_ID() . '" ' . $selected . '>' . get_the_title() . '</option>';
				endwhile;
				echo '</select></td></tr>';
			} else {
		?>
			<tr>
				<td>
					<?php
					echo $item . ':';
					?>
				</td>
				<td>
					<input name="<?php echo $key; ?>" value="<?php echo $value; ?>">
				</td>
			</tr>
		<?php
			}
		}
		?>
	</table>
<?php
}// end gwtds_metaboxes_html

/**
 * Save metaboxes
 */

function schedule_save_metaboxes() {
	global $post;
	$arr = array(
		't_speaker' => __( 'Speaker' ),
		't_time' => __( 'Talk Time UTC' ),
		't_duration' => __( 'Talk Duration' ),
		't_type' => __( 'Talk Type' ),
		't_live' => __( 'Talk Live' ),
		't_audience' => __( 'Target Audience' ),
		't_language' => __( 'Target Language' ),
		't_recording_link' => __( 'Recording URL' ),
	);
	foreach ( $arr as $key => $item ) {
		update_post_meta( $post->ID, $key, $_POST[ $key ] );
	}
}
add_action( 'save_post_gwtd_schedule', 'schedule_save_metaboxes' );
