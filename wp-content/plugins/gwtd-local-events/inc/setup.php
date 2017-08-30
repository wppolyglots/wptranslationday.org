<?php
/**
 * Add metaboxes to new local event CPT 
 */
add_action( 'add_meta_boxes_local-event', 'gwtdle_metaboxes' );
function gwtdle_metaboxes() {
   global $wp_meta_boxes;
   add_meta_box('mbox_le_city', __('Fields'), 'gwtdle_metaboxes_metaboxes_html', 'local-event', 'normal', 'high');
}

/**
 * Add metaboxes to new local event CPT 
 */
function gwtdle_metaboxes_metaboxes_html() {
    global $post;
    $custom = get_post_custom($post->ID);
	$arr = array(
		'city' => __('City', 'gwtdle'),
		'country' => __('Country', 'gwtdle'),
		'continent' => __('Continent', 'gwtdle'),
		'locale' => __('Locale', 'gwtdle'),
		'organizer_name' => __('Organizer Name', 'gwtdle'),
		'organizer_w_org' => __('Organizer w.org username', 'gwtdle'),
		'organizer_slack' => __('Organizer slack username', 'gwtdle'),
		'coorganizers' => __('Co-organizers', 'gwtdle'),
		'utc_start' => __('UTC start time', 'gwtdle'),
		'utc_end' => __('UTC end time', 'gwtdle'),
		'announcement_url' => __('Announcement URL', 'gwtdle'),
		'latitude' => __('Latitude', 'gwtdle'),
		'longitude' => __('Longitude', 'gwtdle'),
	);

	?>
	<table id="le-custom-fields">
	<?php	
	foreach ($arr as $key=>$item) {
		$value = isset($custom[$key][0])?$custom[$key][0]:'';

		?>
		<tr><td><?php echo $item . ':'; ?></td><td><input name="<?php echo $key; ?>" value="<?php echo $value; ?>"></td></tr>
		<?php
	}
	?>
 	</table>
	<?php	
}

/**
 * Save meta data for local-event CPT
 */
add_action( 'save_post_local-event', 'gwtdle_save_post' ); 
function gwtdle_save_post()
{
    if(empty($_POST)) return; //why is gwtdle_save_post triggered by add new? 
    global $post;
	$arr = array(
		'city' => __('City', 'gwtdle'),
		'country' => __('Country', 'gwtdle'),
		'continent' => __('Continent', 'gwtdle'),
		'locale' => __('Locale', 'gwtdle'),
		'organizer_name' => __('Organizer Name', 'gwtdle'),
		'organizer_w_org' => __('Organizer w.org username', 'gwtdle'),
		'organizer_slack' => __('Organizer slack username', 'gwtdle'),
		'coorganizers' => __('Co-organizers', 'gwtdle'),
		'utc_start' => __('UTC start time', 'gwtdle'),
		'utc_end' => __('UTC end time', 'gwtdle'),
		'announcement_url' => __('Announcement URL', 'gwtdle')
		'latitude' => __('Latitude', 'gwtdle'),
		'longitude' => __('Longitude', 'gwtdle'),
	);
	foreach ($arr as $key=>$item) {
		update_post_meta($post->ID, $key, $_POST[$key]);
	}
	$full_place = $_POST['continent'] . '/' . $_POST['country'] . '/' . $_POST['city']; // needed for column sorting
	update_post_meta($post->ID, 'full_place', $full_place);
}   

/**
 * Add columns in overview
 */
add_filter('manage_local-event_posts_columns' , 'gwtdle_add_columns');
function gwtdle_add_columns($columns) {
    unset($columns['title']);
    unset($columns['date']);
    return array_merge($columns, 
				array(
					'Place' => __('Place', 'gwtdle'),
					'Locale' =>__( 'Locale', 'gwtdle'),
					'Organizer' =>__( 'Organizer', 'gwtdle'),
					'UTC time' =>__( 'UTC time', 'gwtdle'),
					'URL' =>__( 'URL', 'gwtdle'),
				)
		);
}
/**
 * Render columns in overview
 */
add_filter( 'manage_local-event_posts_custom_column', 'gwtdle_render_columns', 10, 2 );
function gwtdle_render_columns( $column, $post_id ) {
	switch ( $column ) {
        case 'Place' :
			$str = get_post_meta( $post_id , 'continent' , true ) . '/' . get_post_meta( $post_id , 'country' , true ) . '/' . get_post_meta( $post_id , 'city' , true );
            echo $str; 
            break;
		case 'Locale' :
			echo get_post_meta( $post_id , 'locale' , true );
			break;
        case 'Organizer' :
			$w_org = get_post_meta( $post_id , 'organizer_w_org' , true );
			$slack = get_post_meta( $post_id , 'organizer_slack' , true );
            echo 'WP: <a href="https://profiles.wordpress.org/' . $w_org . '">' . $w_org . '</a><br>Slack: <a href="https://wordpress.slack.com/team/' . $slack . '">@' . $slack . '</a>'; 
            break;
		case 'UTC time' :
			echo get_post_meta( $post_id , 'utc_start' , true ) . ' - ' . get_post_meta( $post_id , 'utc_end' , true );
			break;
		case 'URL' :
			echo '<a href="' . get_post_meta( $post_id , 'announcement_url' , true ) . '">Link</a>';
			break;
	}
}

/**
 * Allow to order columns by clicking the header 
 */
add_filter( 'manage_edit-local-event_sortable_columns', 'gwtdle_table_sorting' );
function gwtdle_table_sorting( $columns ) {
	$columns['Place'] = 'full_place';
	$columns['Locale'] = 'locale';
	$columns['UTC time'] = 'utc_start';
	return $columns;
}
add_action( 'pre_get_posts', 'gwtdle_table_sorting_meta' );
function gwtdle_table_sorting_meta( $query ) {
	if( ! is_admin() )
		return;

	if( ! $query->is_main_query() || 'local-event' != $query->get( 'post_type' )  )
        	return;
	
	$orderby = $query->get( 'orderby');
	
	switch ( $orderby ) {
		case '':
			$query->set('order','ASC');
			$query->set('meta_key','full_place');
			$query->set('orderby','meta_value');
			break;
		case 'full_place':
			$query->set('meta_key','full_place');
			$query->set('orderby','meta_value');
			break;
		case 'locale':
			$query->set('meta_key','locale');
			$query->set('orderby','meta_value');
			break;
		case 'utc_start':
			$query->set('meta_key','utc_start');
			$query->set('orderby','meta_value_num');
			break;
		default:
			break;
	}
}

/**
 * Create the local-event CPT 
 */
add_action( 'init', 'gwtdle_init' );
function gwtdle_init() {
	$labels = array(
		'name'               => _x( 'Local Events', 'post type general name', 'gwtdle' ),
		'singular_name'      => _x( 'Local Event', 'post type singular name', 'gwtdle' ),
		'menu_name'          => _x( 'Local Events', 'admin menu', 'gwtdle' ),
		'name_admin_bar'     => _x( 'Local Event', 'add new on admin bar', 'gwtdle' ),
		'add_new'            => _x( 'Add New', 'local-event', 'gwtdle' ),
		'add_new_item'       => __( 'Add New Local Event', 'gwtdle' ),
		'new_item'           => __( 'New Local Event', 'gwtdle' ),
		'edit_item'          => __( 'Edit Local Event', 'gwtdle' ),
		'view_item'          => __( 'View Local Event', 'gwtdle' ),
		'all_items'          => __( 'All Local Events', 'gwtdle' ),
		'search_items'       => __( 'Search Local Events', 'gwtdle' ),
		'parent_item_colon'  => __( 'Parent Local Events:', 'gwtdle' ),
		'not_found'          => __( 'No Local Events found.', 'gwtdle' ),
		'not_found_in_trash' => __( 'No Local Events found in Trash.', 'gwtdle' )
	);

	$args = array(
		'labels'             => $labels,
                'description'        => __( 'Description.', 'gwtdle' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'local-event' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'thumbnail' )
	);

	register_post_type( 'local-event', $args );
}

/**
 * Flush rewrite rules on plugin activation 
 */
register_activation_hook( __FILE__, 'gwtdle_rewrite_flush' );
function gwtdle_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    gwtdle_init();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
