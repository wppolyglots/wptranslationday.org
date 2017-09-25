<?php

/*
Plugin Name: GWTD Speakers
Plugin URI: https://wptranslationday.org
Description: Custom Admin CPT for the Speakers.
Version: 0.1
Author: Xenos (xkon) Konstantinos
Author URI: https://xkon.gr
License: GPL2
*/

/*
 * Load CSS
*/

function gwtd_speakers_load_scripts() {
	wp_enqueue_style( 'gwtd-speakers', plugin_dir_url( __FILE__ ) . '/style.css', array(), '0.1' );
}

add_action( 'admin_enqueue_scripts', 'gwtd_speakers_load_scripts' );

/*
 * Create CPT
*/

function speakers_post_type() {

	$labels = array(
		'name'                  => _x( 'Speakers', 'Post Type General Name', 'gwtd' ),
		'singular_name'         => _x( 'Speaker', 'Post Type Singular Name', 'gwtd' ),
		'menu_name'             => __( 'Speakers', 'gwtd' ),
		'name_admin_bar'        => __( 'Speakers', 'gwtd' ),
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
		'label'                 => __( 'Speakers', 'gwtd' ),
		'description'           => __( 'Speakers', 'gwtd' ),
		'labels'                => $labels,
		'supports'              => array(),
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
		'rewrite'            => array( 'slug' => 'speakers' ),
	);
	register_post_type( 'gwtd_speakers', $args );

}
add_action( 'init', 'speakers_post_type', 0 );

/*
 * Add metaboxes to Speakers
 */

function speakers_metaboxes() {
	global $wp_meta_boxes;
	add_meta_box(
		'mbox_schedule',
		__( 'Fields' ),
		'gwtdsp_metaboxes_metaboxes_html',
		'gwtd_speakers',
		'normal',
		'high'
	);
}// end gwtdle_metaboxes

add_action( 'add_meta_boxes_gwtd_speakers', 'speakers_metaboxes' );

/**
 * Setup metaboxes
 */
function gwtdsp_metaboxes_metaboxes_html() {
	global $post;
	$custom = get_post_custom( $post->ID );
	$arr = array(
		's_username' => __( 'Speaker Username' ),
	);
	?>
	<table id="schedule-custom-fields">
		<?php
		foreach ( $arr as $key => $item ) {
			$value = isset( $custom[ $key ][0] ) ? $custom[ $key ][0] : '';
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
		?>
	</table>
<?php
}// end gwtds_metaboxes_html

/**
 * Save metaboxes
 */

function speakers_save_metaboxes() {
	global $post;
	$arr = array(
		's_username' => __( 'Speaker Username' ),
	);
	foreach ( $arr as $key => $item ) {
		update_post_meta( $post->ID, $key, $_POST[ $key ] );
	}
}
add_action( 'save_post_gwtd_speakers', 'speakers_save_metaboxes' );