<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'TGGRMediaSource' ) ) {
	/**
	 * Abstract class to define/implement base methods for all module classes
	 * @package Tagregator
	 */
	abstract class TGGRMediaSource extends TGGRModule {
		protected static $post_author_id;

		const TAXONOMY_HASHTAG_NAME_SINGULAR    = 'Hashtag';
		const TAXONOMY_HASHTAG_NAME_PLURAL      = 'Hashtags';
		const TAXONOMY_HASHTAG_SLUG             = 'hashtag';
		const POST_AUTHOR_USERNAME              = 'tagregator';
		const POST_CONTENT_LENGTH_DISPLAY_LIMIT = 200;


		/**
		 * Registers the custom post type
		 * @mvc Controller
		 */
		protected static function register_post_type( $slug, $params ) {
			if ( ! post_type_exists( $slug ) ) {
				$post_type = register_post_type( $slug, $params );

				if ( ! is_wp_error( $post_type ) ) {
					self::register_taxonomies( $slug );
				}
			}
		}

		/**
		 * Defines the parameters for the custom post type
		 * @mvc Model
		 *
		 * @return array
		 */
		protected static function get_post_type_params( $slug, $singular_name, $plural_name ) {
			$labels = array(
				'name'               => $plural_name,
				'singular_name'      => $singular_name,
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New ' . $singular_name,
				'edit'               => 'Edit',
				'edit_item'          => 'Edit ' .    $singular_name,
				'new_item'           => 'New ' .     $singular_name,
				'view'               => 'View ' .    $plural_name,
				'view_item'          => 'View ' .    $singular_name,
				'search_items'       => 'Search ' .  $plural_name,
				'not_found'          => 'No ' .      $plural_name . ' found',
				'not_found_in_trash' => 'No ' .      $plural_name . ' found in Trash',
				'parent'             => 'Parent ' .  $singular_name
			);

			$post_type_params = array(
				'labels'              => $labels,
				'singular_label'      => $singular_name,
				'public'              => true,  // todo should this be public? don't want showing up in search results or json api, don't want front-end singular posts. why was it set to true? maybe leave public but set publicly_queryable to false?
				'show_in_menu'        => TGGRSettings::MENU_SLUG,
				'hierarchical'        => true,
				'exclude_from_search' => true,
				'capability_type'     => 'post',
				'has_archive'         => true,
				'rewrite'             => array( 'slug' => $slug, 'with_front' => false ),
				'query_var'           => true,
				'supports'            => array( 'title', 'editor', 'thumbnail' )
			);

			return apply_filters( Tagregator::PREFIX . 'post-type-params', $post_type_params );
		}

		/**
		 * Remove our post types from search results in the `Insert/Edit Link` dialog.
		 *
		 * The posts aren't intended to be used in this way, and their presense makes the list cluttered.
		 *
		 * @param WP_Query $query
		 *
		 * @return WP_Query
		 */
		public static function exclude_from_insert_link_results( $query ) {
			$excluded_types = array();

			foreach( Tagregator::get_instance()->media_sources as $source ) {
				$excluded_types[] = $source::POST_TYPE_SLUG;
			}

			$query['post_type'] = array_diff( $query['post_type'], $excluded_types );

			return $query;
		}

		/**
		 * Registers the custom taxonomies
		 * @mvc Controller
		 */
		protected static function register_taxonomies( $post_type_slug ) {
			if ( taxonomy_exists( self::TAXONOMY_HASHTAG_SLUG ) ) {
				register_taxonomy_for_object_type( self::TAXONOMY_HASHTAG_SLUG, $post_type_slug );
			} else {
				register_taxonomy( self::TAXONOMY_HASHTAG_SLUG, $post_type_slug, self::get_taxonomy_hashtag_params() );
			}
		}

		/**
		 * Defines the parameters for the Hashtag taxonomy
		 * @mvc Model
		 *
		 * @return array
		 */
		protected static function get_taxonomy_hashtag_params() {
			$labels = array(
				'name'          => self::TAXONOMY_HASHTAG_NAME_PLURAL,
				'singular_name' => self::TAXONOMY_HASHTAG_NAME_SINGULAR,
				'all_items'     => 'All '     . self::TAXONOMY_HASHTAG_NAME_PLURAL,
				'edit_item'     => 'Edit '    . self::TAXONOMY_HASHTAG_NAME_SINGULAR,
				'view_item'     => 'View '    . self::TAXONOMY_HASHTAG_NAME_SINGULAR,
				'update_item'   => 'Update '  . self::TAXONOMY_HASHTAG_NAME_SINGULAR,
				'add_new_item'  => 'Add New ' . self::TAXONOMY_HASHTAG_NAME_SINGULAR,
				'new_item_name' => 'New '     . self::TAXONOMY_HASHTAG_NAME_SINGULAR . ' Name',
				'search_items'  => 'Search '  . self::TAXONOMY_HASHTAG_NAME_PLURAL,
				'popular_items' => 'Popular ' . self::TAXONOMY_HASHTAG_NAME_PLURAL,
			);

			$params = array(
				'label'                 => self::TAXONOMY_HASHTAG_NAME_PLURAL,
				'labels'                => $labels,
				'hierarchical'          => false,
				'rewrite'               => array( 'slug' => self::TAXONOMY_HASHTAG_SLUG ),
				'update_count_callback' => '_update_post_term_count'
			);

			return apply_filters( Tagregator::PREFIX . 'taxonomy_hashtag_params', $params );
		}

		/**
		 * Add extra columns to the list table on each All Posts screen
		 *
		 * @param array
		 *
		 * @return array
		 */
		public static function add_columns( $columns ) {
			$columns['media-author'] = 'Author';

			return $columns;
		}

		/**
		 * Sort the Author column on each All Posts screen
		 *
		 * @param $query_vars
		 *
		 * @return mixed
		 */
		public static function sort_by_author( $query_vars ) {
			$class = get_called_class();

			if ( isset( $query_vars['orderby'], $query_vars['post_type'] ) &&
			     $class::POST_TYPE_SLUG == $query_vars['post_type'] &&
			     'Author' == $query_vars['orderby'] ) {

				$query_vars['orderby']  = 'meta_value';
				$query_vars['meta_key'] = self::get_author_username_key( $class );
			}

			return $query_vars;
		}

		/**
		 * Output the value for extra columns on each All Posts screen
		 *
		 * @param string $column
		 * @param int    $post_id
		 */
		public static function display_columns( $column, $post_id ) {
			if ( 'media-author' != $column ) {
				return;
			}

			echo esc_html(
				get_post_meta( $post_id, self::get_author_username_key( get_called_class() ), true )
			);
		}

		/**
		 * Get the key for where the field where the author's username is stored.
		 *
		 * This is always `author_username`, except for with Google+, because they just don't have separate fields
		 * for the name and username, so that class doesn't store a `author_username` field.
		 *
		 * @param string $class
		 *
		 * @return string
		 */
		protected static function get_author_username_key( $class ) {
			return 'TGGRSourceGoogle' == $class ? 'author_name' : 'author_username';
		}

		/**
		 * Registers default settings with TGGRSettings
		 * @mvc Model
		 *
		 * @param array $tggr_default_settings
		 * @return array
		 */
		public static function register_default_settings( $tggr_default_settings ) {
			$class = get_called_class();
			$tggr_default_settings[ $class ] = $class::get_instance()->default_settings;

			return $tggr_default_settings;
		}

		/**
		 * Registers settings sections, fields and settings
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function register_settings() {
			$class = get_called_class();

			add_settings_section(
				$class::SETTINGS_PREFIX . 'section',
				$class::SETTINGS_TITLE,
				$class . '::markup_settings_section_header',
				Tagregator::PREFIX . 'settings'
			);

			foreach ( $class::get_instance()->setting_names as $setting ) {
				$slug = strtolower( str_replace( ' ', '_', $setting ) );

				if ( self::is_public_setting( $setting ) ) {
					add_settings_field(
						$class::SETTINGS_PREFIX . $slug,
						$setting,
						array( $this, 'markup_settings_fields' ),
						Tagregator::PREFIX . 'settings',
						$class::SETTINGS_PREFIX . 'section',
						array( 'label_for' => $class::SETTINGS_PREFIX . $slug )
					);
				}
			}
		}

		/**
		 * Adds the section introduction text to the Settings page
		 * @mvc Controller
		 *
		 * @param array $section
		 */
		public static function markup_settings_section_header( $section ) {
			$class = get_called_class();

			require( $class::get_instance()->view_folder .'/page-settings-section-header.php' );
		}

		/**
		 * Delivers the markup for settings fields
		 * @mvc Controller
		 *
		 * @param array $field
		 */
		public function markup_settings_fields( $field ) {
			$class = get_called_class();
			$setting = str_replace( $class::SETTINGS_PREFIX, '', $field['label_for'] );
			$textarea_settings = array( 'highlighted_accounts', 'banned_accounts' );
			$checkbox_settings = array( 'sandbox_mode' );
			require( dirname( __DIR__ ) . '/views/media-sources-common/page-settings-fields.php' );
		}

		/**
		 * Determines if a setting is intended to be public/visible or not
		 * @mvc Controller
		 *
		 * @param string $setting_name
		 * @return bool
		 */
		public function is_public_setting( $setting_name ) {
			return '_' != substr( $setting_name, 0, 1 ) ? true : false;
		}

		/**
		 * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
		 * @mvc Model
		 *
		 * @param array $new_settings
		 * @return array
		 */
		abstract public function validate_settings( $new_settings );

		/**
		 * Creates a user to assign automatically generated posts to
		 * @mvc Model
		 */
		public static function create_post_author() {
			if ( ! username_exists( self::POST_AUTHOR_USERNAME ) ) {
				$domain = parse_url( site_url() );

				wp_insert_user( array(
					'user_pass' => wp_generate_password( 100, true, true ),
					'user_login' => self::POST_AUTHOR_USERNAME,
					'user_email' => self::POST_AUTHOR_USERNAME . '@' . ( isset( $domain['host'] ) ? $domain['host'] : 'localhost' ),
					'user_role' => 'Subscriber',
				) );

				self::get_post_author_user_id();
			}
		}

		/**
		 * Retrieves the ID of the user we assign posts to
		 * @mvc Model
		 *
		 * @return int
		 */
		public static function get_post_author_user_id() {
			$user = get_user_by( 'login', self::POST_AUTHOR_USERNAME );
			self::$post_author_id = isset ( $user->ID ) ? $user->ID : false;
		}

		/**
		 * Imports items from external source into the local database as posts
		 * @mvc Controller
		 *
		 * @param array $posts
		 */
		protected function import_new_posts( $posts ) {
			global $wpdb;

			if ( $posts ) {
				$class = get_called_class();
				$existing_unique_ids = self::get_existing_unique_ids( $class::POST_TYPE_SLUG );

				foreach ( $posts as $post ) {
					if ( ! in_array( $post['post_meta']['source_id'], $existing_unique_ids ) ) {
						$post_id = wp_insert_post( $post['post'] );

						if ( $post_id ) {
							foreach ( $post['post_meta'] as $key => $value ) {
								update_post_meta( $post_id, $key, $value );
							}

							$term = get_term_by( 'name', $post['term_name'], self::TAXONOMY_HASHTAG_SLUG );
							if ( ! $term ) {
								$term = wp_insert_term( $post['term_name'], self::TAXONOMY_HASHTAG_SLUG );
								$term = get_term( $term['term_id'], self::TAXONOMY_HASHTAG_SLUG );
							}
							wp_set_object_terms( $post_id, $term->slug, self::TAXONOMY_HASHTAG_SLUG );
						}
					}
				}
			}
		}

		/**
		 * Remove items from banned users before they're imported.
		 *
		 * @param array  $items
		 * @param string $username_property_1 The name of the first-level property;            e.g., 'foo' to reference $item->foo
		 * @param string $username_property_2 Optional. The name of the second-level property; e.g., 'bar' to reference $item->foo->bar
		 *
		 * @return array
		 */
		public function remove_banned_items( $items, $username_property_1, $username_property_2 = false ) {
			if ( ! $items ) {
				return $items;
			}

			$banned_accounts = self::get_banned_users();

			foreach ( $items as $key => $item ) {
				// The username is stored in a different property by each source API, so it must be fetched dynamically
				$username = strtolower(
					$username_property_2 ? $item->{$username_property_1}->{$username_property_2} : $item->{$username_property_1}
				);

				if ( in_array( $username, $banned_accounts, true ) ) {
					self::log( __METHOD__, 'Ignored item from banned account', $item );
					unset( $items[ $key ] );
				}
			}

			return $items;
		}

		/**
		 * Pull the banned accounts from the settings
		 *
		 * @return array
		 */
		public static function get_banned_users() {
			return self::clean_usernames(
				TGGRSettings::get_instance()->settings[ get_called_class() ]['banned_accounts']
			);
		}

		/*
		 * Cleans a comma-separated list of usernames
		 *
		 * @param string $csv_usernames
		 * @param string $case          'lower' to make returned usernames all lowercase
		 *
		 * @return array
		 */
		protected static function clean_usernames( $csv_usernames, $case = 'lower' ) {
			if ( 'lower' === $case ) {
				$csv_usernames = strtolower( $csv_usernames );
			}

			$clean_usernames = explode( ',', $csv_usernames );
			$clean_usernames = array_map( 'trim', $clean_usernames );
			$clean_usernames = array_map( 'ltrim', $clean_usernames, array_fill( 0, count( $clean_usernames ), '@' ) ); // remove any appended @ characters

			return $clean_usernames;
		}

		/**
		 * Gets all of the unique IDs for existing posts from a given source
		 *
		 * This is used to prevent inserting duplicate items into the database. It's expensive, but necessary because of race conditions.
		 * It only happens during the REST API calls to get new content, so the user won't notice much anyway.
		 *
		 * @mvc Model
		 *
		 * @param string $post_type
		 *
		 * @return array
		 */
		public static function get_existing_unique_ids( $post_type ) {
			global $wpdb;

			$existing_posts_unique_ids = $wpdb->get_col( $wpdb->prepare( "
				SELECT pm.meta_value
				FROM {$wpdb->postmeta} pm
					JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE
					pm.meta_key = 'source_id' AND
					p.post_type = %s
				",
				$post_type
			) );

			// todo This needs a sanity limit for performance. Only really need to check against most recent ~400, but need to order by latest.

			return is_array( $existing_posts_unique_ids ) ? $existing_posts_unique_ids : array();
		}

		/**
		 * Fetches new items from an external sources and saves them as posts in the local database
		 * @mvc Model
		 *
		 * @param string $hashtag
		 */
		abstract public function import_new_items( $hashtag );

		/**
		 * Converts data from external source into a post/postmeta format so it can be saved in the local database
		 * @mvc Model
		 *
		 * @param array $items
		 * @param string $term
		 * @return array
		 */
		abstract public function convert_items_to_posts( $items, $term );

		/**
		 * Gathers the data that the media-item view will need
		 *
		 * @mvc Model
		 *
		 * @param array $item
		 *
		 * @return array
		 */
		abstract public function add_item_meta_data( $item );

		/**
		 * Creates a title for a post based on the content
		 * @mvc Model
		 *
		 * @param string $content
		 * @return string
		 */
		protected static function get_title_from_content( $content ) {
			return substr( sanitize_text_field( $content ), 0, 50 );
		}

		/**
		 * Converts a timestamp in GMT to the local timezone
		 * @mvc Model
		 *
		 * @param int $post_timestamp_gmt
		 * @return int
		 */
		public static function convert_gmt_timestamp_to_local( $post_timestamp_gmt ) {
			$post_timestamp_local = $post_timestamp_gmt + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

			return $post_timestamp_local;
		}

		/**
		 * Retrieves the latest post mapped to a given hashtag
		 * @mvc Model
		 *
		 * @param $hashtag
		 * @return object|false
		 */
		protected static function get_latest_hashtagged_post( $post_type, $hashtag ) {
			$latest_post = false;
			$term        = get_term_by( 'name', $hashtag, self::TAXONOMY_HASHTAG_SLUG );

			if ( isset ( $term->term_id ) ) {
				$latest_post = get_posts( array(
					'posts_per_page'   => 1,
					'order_by'         => 'date',
					'post_type'        => $post_type,
					'tax_query'        => array(
						array(
							'taxonomy' => TGGRMediaSource::TAXONOMY_HASHTAG_SLUG,
							'field'    => 'id',
							'terms'    => $term->term_id,
						),
					)
				) );

				if ( isset( $latest_post[0]->ID ) ) {
					$latest_post = $latest_post[0];
				}
			}

			return $latest_post;
		}

		/**
		 * Determine the relevant CSS classes for a media item container
		 * @mvc Model
		 *
		 * @param int $item_id
		 * @param string $author_username
		 * @param array $classes Extra classes to add the defaults
		 * @return array
		 */
		public static function get_css_classes( $item_id, $author_username, $classes = array() ) {
			array_unshift( $classes, get_post_type( $item_id ) );
			array_unshift( $classes, Tagregator::CSS_PREFIX . 'media-item' );

			$highlighted_accounts = self::clean_usernames(
				TGGRSettings::get_instance()->settings[ get_called_class() ]['highlighted_accounts']
			);

			if ( in_array( strtolower( $author_username ), $highlighted_accounts ) ) {
				$classes[] = Tagregator::CSS_PREFIX . 'highlighted-account';
			}

			return implode( ' ', apply_filters( Tagregator::PREFIX . 'item_css_classes', $classes, $item_id, $author_username ) );
		}

		/**
		 * Converts URLs inside a block of text into hyperlinks
		 * @mvc Model
		 *
		 * @param string $content
		 * @return string
		 */
		public static function convert_urls_to_links( $content ) {
			$content = make_clickable(  $content );
			$content = wp_rel_nofollow( $content );
			$content = wp_unslash(      $content );

			return $content;
		}

		/**
		 * Return the length of excerpts in words
		 *
		 * @param $number_words
		 *
		 * @return int
		 */
		public static function get_excerpt_length( $number_words ) {
			global $post;

			$class               = get_called_class();
			$average_word_length = 5; // in the most common western languages

			if ( ! empty( $post->post_type ) && $class::POST_TYPE_SLUG == $post->post_type ) {
				$number_words = self::POST_CONTENT_LENGTH_DISPLAY_LIMIT / $average_word_length;
			}

			return $number_words;
		}

		/**
		 * Determine whether we should display the full post or an excerpt.
		 *
		 * mb_strlen() is used when available because strlen() is not multibyte-aware. Passing in a 140-character
		 * message in Cyrillic, for example, will return 280.
		 *
		 * @param string $content
		 *
		 * @return bool
		 */
		public static function show_excerpt( $content ) {
			$content = strip_tags( $content );
			$length  = function_exists( 'mb_strlen' ) ? mb_strlen( $content ) : strlen( $content );

			return $length > self::POST_CONTENT_LENGTH_DISPLAY_LIMIT;
		}
	} // end TGGRModule
}
