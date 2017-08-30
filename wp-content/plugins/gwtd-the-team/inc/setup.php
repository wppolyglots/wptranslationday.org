<?php
/**
 * Add metaboxes to new the-team CPT 
 */
add_action( 'add_meta_boxes_the-team', 'gwtdtt_metaboxes' );
function gwtdtt_metaboxes() {
   global $wp_meta_boxes;
   add_meta_box('mbox_the_team', __('Fields'), 'gwtdtt_metaboxes_metaboxes_html', 'the-team', 'normal', 'high');
}

function gwtdtt_metaboxes_metaboxes_html() {
    global $post;
    $custom = get_post_custom($post->ID);
	$arr = array(
		'tt_name' => __('Name', 'gwtdtt'),
		'tt_title' => __('Title', 'gwtdtt'),
		'tt_w_org' => __('w.org username', 'gwtdtt'),
		'tt_slack' => __('Slack username', 'gwtdtt'),
		'tt_twitter' => __('Twitter', 'gwtdtt'),
		'tt_website' => __('Website', 'gwtdtt'),
		'tt_bio' => __('Biography', 'gwtdtt'),
		'tt_order' => __('Order', 'gwtdtt'),
	);

	?>
	<table id="tt-custom-fields">
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
 * Save meta data for the-team CPT
 */
add_action( 'save_post_the-team', 'gwtdtt_save_post' ); 
function gwtdtt_save_post()
{
    if(empty($_POST)) return; //why is gwtdtt_save_post triggered by add new? 
    global $post;
	$arr = array(
		'tt_name' => __('Name', 'gwtdtt'),
		'tt_title' => __('Title', 'gwtdtt'),
		'tt_w_org' => __('w.org username', 'gwtdtt'),
		'tt_slack' => __('Slack username', 'gwtdtt'),
		'tt_twitter' => __('Twitter', 'gwtdtt'),
		'tt_website' => __('Website', 'gwtdtt'),
		'tt_bio' => __('Biography', 'gwtdtt'),
		'tt_order' => __('Order', 'gwtdtt'),
	);
	foreach ($arr as $key=>$item) {
		update_post_meta($post->ID, $key, $_POST[$key]);
	}
}   

/**
 * Add columns in overview
 */
add_filter('manage_the-team_posts_columns' , 'gwtdtt_add_columns');
function gwtdtt_add_columns($columns) {
    unset($columns['title']);
    unset($columns['date']);
    return array_merge($columns, 
				array(
					'tt_name' => __('Name', 'gwtdtt'),
					'tt_title' => __('Title', 'gwtdtt'),
					'tt_organizer' => __('Organizer', 'gwtdtt'),
					'tt_order' => __('Order', 'gwtdtt'),
				)
		);
}
/**
 * Render columns in overview
 */
add_filter( 'manage_the-team_posts_custom_column', 'gwtdtt_render_columns', 10, 2 );
function gwtdtt_render_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'tt_name' :
			echo get_post_meta( $post_id , 'tt_name' , true );
			break;
		case 'tt_title' :
			echo get_post_meta( $post_id , 'tt_title' , true );
			break;
        case 'tt_organizer' :
			$w_org = get_post_meta( $post_id , 'tt_w_org' , true );
			$slack = get_post_meta( $post_id , 'tt_slack' , true );
			$twitter = get_post_meta( $post_id , 'tt_twitter' , true );
            echo 'w.org: <a href="https://profiles.wordpress.org/' . $w_org . '">' . $w_org . '</a><br>' .
				'Slack: <a href="https://wordpress.slack.com/team/' . $slack . '">' . $slack . '</a><br>' .
				'Twitter: <a href="https://twitter.com/' . $twitter . '">@' . $twitter . '</a>'; 
            break;
		case 'tt_order' :
			echo get_post_meta( $post_id , 'tt_order' , true );
			break;
	}
}

/**
 * Allow to order columns by clicking the header 
 */
add_filter( 'manage_edit-the-team_sortable_columns', 'gwtdtt_table_sorting' );
function gwtdtt_table_sorting( $columns ) {
	$columns['tt_name'] = 'tt_name';
	$columns['tt_order'] = 'tt_order';
	return $columns;
}
add_action( 'pre_get_posts', 'gwtdtt_table_sorting_meta' );
function gwtdtt_table_sorting_meta( $query ) {
	if( ! is_admin() )
		return;

	if( ! $query->is_main_query() || 'the-team' != $query->get( 'post_type' )  )
        return;

	$orderby = $query->get( 'orderby' );

	switch ( $orderby ) {
		case '':
			// Default
			$query->set('order','ASC');
			$query->set('meta_key','tt_order');
			$query->set('orderby','meta_value');
			break;
		case 'tt_name':
			$query->set('meta_key','tt_name');
			$query->set('orderby','meta_value');
			break;
		case 'tt_order':
			$query->set('meta_key','tt_order');
			$query->set('orderby','meta_value');
			break;
		default:
			break;
	}
}

/**
 * Create the the-team CPT 
 */
add_action( 'init', 'gwtdtt_init' );
function gwtdtt_init() {
	$labels = array(
		'name'               => _x( 'Team Members', 'post type general name', 'gwtdtt' ),
		'singular_name'      => _x( 'Team Member', 'post type singular name', 'gwtdtt' ),
		'menu_name'          => _x( 'Team Members', 'admin menu', 'gwtdtt' ),
		'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'gwtdtt' ),
		'add_new'            => _x( 'Add New', 'the-team', 'gwtdtt' ),
		'add_new_item'       => __( 'Add New Team Member', 'gwtdtt' ),
		'new_item'           => __( 'New Team Member', 'gwtdtt' ),
		'edit_item'          => __( 'Edit Team Member', 'gwtdtt' ),
		'view_item'          => __( 'View Team Member', 'gwtdtt' ),
		'all_items'          => __( 'All Team Members', 'gwtdtt' ),
		'search_items'       => __( 'Search Team Members', 'gwtdtt' ),
		'parent_item_colon'  => __( 'Parent Team Members:', 'gwtdtt' ),
		'not_found'          => __( 'No Team Members found.', 'gwtdtt' ),
		'not_found_in_trash' => __( 'No Team Members found in Trash.', 'gwtdtt' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'gwtdtt' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'the-team' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'thumbnail' )
	);

	register_post_type( 'the-team', $args );
}

/**
 * Flush rewrite rules on plugin activation 
 */
register_activation_hook( __FILE__, 'gwtdtt_rewrite_flush' );
function gwtdtt_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    gwtdtt_init();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
