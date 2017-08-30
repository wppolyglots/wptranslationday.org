<?php
//////////////////////////////////////////////////////
// Create MetaBoxes
//////////////////////////////////////////////////////

function social_mentions_add_metaboxes() {
	global $wp_meta_boxes;
	add_meta_box(
		'socment_meta',
		'Tag Meta',
		'social_mentions_metaboxes',
		array(
			'socment-twitter',
			'socment-instagram',
			'socment-googleplus',
			'socment-flickr',
		),
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'social_mentions_add_metaboxes' );

function social_mentions_metaboxes() {
	global $post;
	$custom = get_post_custom( $post->ID );
	$arr = array(
		'socment_meta_id' => 'ID',
		'socment_meta_name' => 'Name',
		'socment_meta_username' => 'Username',
		'socment_meta_profile_url' => 'Profile URL',
		'socment_meta_profile_img' => 'Profile Image',
		'socment_meta_img' => 'Image',
		'socment_meta_url' => 'Url',
	);
	?>
	<table id="socment-meta">
		<?php
		foreach ( $arr as $key => $item ) {
			$value = isset( $custom[ $key ][0] ) ? $custom[ $key ][0] : '';
			?>
			<tr><td><?php echo $item . ':'; ?></td><td><input name="<?php echo $key; ?>" value="<?php echo $value; ?>"></td></tr>
			<?php
		}
		?>
	</table>
	<?php
}