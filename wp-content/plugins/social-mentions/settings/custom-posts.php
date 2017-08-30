<?php
//////////////////////////////////////////////////////
// Register Custom Taxonomy
//////////////////////////////////////////////////////

function social_mentions_create_taxonomy() {

	$labels = array(
		'name' => 'Hashtags',
		'singular_name' => 'Hashtag',
		'menu_name' => 'Hashtags',
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => true,
	);
	register_taxonomy(
		'socment-hashtags',
		array(
			'socment-twitter',
			'socment-instagram',
			'socment-googleplus',
			'socment-flickr',
		),
		$args
	);

}// end social_mentions_create_taxonomy

add_action( 'init', 'social_mentions_create_taxonomy', 0 );


//////////////////////////////////////////////////////
// Register Custom Post Types
//////////////////////////////////////////////////////

function social_mentions_twitter_post_type() {

	$labels = array(
		'name' => 'Twitter Feed',
		'singular_name' => 'Twitter Feed',
		'menu_name' => 'Twitter Feed',
		'name_admin_bar' => 'Twitter Feed',
	);
	$args = array(
		'label' => 'Twitter',
		'labels' => $labels,
		'supports' => array( 'title', 'editor' ),
		'taxonomies' => array( 'socment-hashtags' ),
		'hierarchical' => false,
		'public' => true,
		'show_in_menu' => 'social-mentions',
		'capability_type' => 'post',
	);
	register_post_type( 'socment-twitter', $args );
}// end social_mentions_twitter_post_type

add_action( 'init', 'social_mentions_twitter_post_type', 0 );

function social_mentions_instagram_post_type() {

	$labels = array(
		'name' => 'Instagram Feed',
		'singular_name' => 'Instagram Feed',
		'menu_name' => 'Instagram Feed',
		'name_admin_bar' => 'Instagram Feed',
	);
	$args = array(
		'label' => 'Instagram',
		'labels' => $labels,
		'supports' => array( 'title', 'editor' ),
		'taxonomies' => array( 'socment-hashtags' ),
		'hierarchical' => false,
		'public' => true,
		'show_in_menu' => 'social-mentions',
		'capability_type' => 'post',
	);
	register_post_type( 'socment-instagram', $args );
}// end social_mentions_instagram_post_type

add_action( 'init', 'social_mentions_instagram_post_type', 0 );

function social_mentions_googleplus_post_type() {

	$labels = array(
		'name' => 'Google+ Feed',
		'singular_name' => 'Google+ Feed',
		'menu_name' => 'Google+ Feed',
		'name_admin_bar' => 'Google+ Feed',
	);
	$args = array(
		'label' => 'GooglePlus',
		'labels' => $labels,
		'supports' => array( 'title', 'editor' ),
		'taxonomies' => array( 'socment-hashtags' ),
		'hierarchical' => false,
		'public' => true,
		'show_in_menu' => 'social-mentions',
		'capability_type' => 'post',
	);
	register_post_type( 'socment-googleplus', $args );
}// end social_mentions_googleplus_post_type

add_action( 'init', 'social_mentions_googleplus_post_type', 0 );

function social_mentions_flickr_post_type() {

	$labels = array(
		'name' => 'Flickr Feed',
		'singular_name' => 'Flickr Feed',
		'menu_name' => 'Flickr Feed',
		'name_admin_bar' => 'Flickr Feed',
	);
	$args = array(
		'label' => 'Flickr',
		'labels' => $labels,
		'supports' => array( 'title', 'editor' ),
		'taxonomies' => array( 'socment-hashtags' ),
		'hierarchical' => false,
		'public' => true,
		'show_in_menu' => 'social-mentions',
		'capability_type' => 'post',
	);
	register_post_type( 'socment-flickr', $args );
}// end social_mentions_flickr_post_type

add_action( 'init', 'social_mentions_flickr_post_type', 0 );