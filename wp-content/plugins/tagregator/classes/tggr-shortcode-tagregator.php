<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'TGGRShortcodeTagregator' ) ) {
	/**
	 * Handles the [tagregator] shortcode
	 *
	 * @package Tagregator
	 */
	class TGGRShortcodeTagregator extends TGGRModule {
		protected $refresh_interval, $post_types_to_class_names, $view_folder;		// $refresh_interval is in seconds
		protected static $readable_properties  = array( 'refresh_interval', 'view_folder' );
		protected static $writeable_properties = array( 'refresh_interval' );

		const SHORTCODE_NAME = 'tagregator';

		/**
		 * Constructor
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->register_hook_callbacks();
			$this->view_folder = dirname( __DIR__ ) . '/views/'. str_replace( '.php', '', basename( __FILE__ ) );
		}

		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
			$this->init();
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 */
		public function deactivate() {}

		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			add_action( 'init',          array( $this, 'init'                 )        );
			add_action( 'rest_api_init', array( $this, 'register_rest_routes' )        );
			add_action( 'save_post',     array( $this, 'prefetch_media_items' ), 10, 2 );
			add_filter( 'body_class',    array( $this, 'add_body_classes'     )        );

			add_shortcode( self::SHORTCODE_NAME, array( $this, 'shortcode_tagregator' ) );
		}

		/**
		 * Initializes variables
		 * @mvc Controller
		 */
		public function init() {
			foreach ( Tagregator::get_instance()->media_sources as $class_name => $object ) {
				$this->post_types_to_class_names[ $object::POST_TYPE_SLUG ] = $class_name;
			}

			$this->refresh_interval = apply_filters( Tagregator::PREFIX . 'refresh_interval', 30 );
		}

		/**
		 * Checks if the plugin was recently updated and upgrades if necessary
		 * @mvc Controller
		 *
		 * @param string $db_version
		 */
		public function upgrade( $db_version = 0 ) {}

		/**
		 * Register routes for the REST API
		 */
		public function register_rest_routes() {
			register_rest_route( 'tagregator/v1', '/items', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'rest_get_items' ),
			) );
		}

		/**
		 * Add a class to body if this page has the tagregator shortcode.
		 *
		 * @param array $classes
		 * @return array
		 */
		public function add_body_classes( $classes ) {
			if ( self::current_page_has_shortcode( self::SHORTCODE_NAME ) ) {
				$classes[] = self::SHORTCODE_NAME;
			}

			return $classes;
		}

		/**
		 * Check if the current page has a given shortcode.
		 *
		 * @param string $shortcode
		 * @return boolean
		 */
		protected static function current_page_has_shortcode( $shortcode ) {
			global $post;
			$has_shortcode = false;

			if ( is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $shortcode ) ) {
				$has_shortcode = true;
			}

			return $has_shortcode;
		}

		/**
		 * Controller for the [tagregator] shortcode
		 * @mvc Controller
		 *
		 * @return string
		 */
		public function shortcode_tagregator( $attributes ) {
			if ( empty( $attributes['hashtags'] ) && ! empty( $attributes['hashtag'] ) ) {
				$attributes['hashtags'] = $attributes['hashtag'];   // for backwards compatibility
			}

			$attributes = shortcode_atts( array(
				'hashtags' => '',
				'layout'   => 'three-column',
			), $attributes );

			if ( ! in_array( $attributes['layout'], array( 'one-column', 'two-column', 'three-column' ) ) ) {
				$attributes['layout'] = 'three-column';
			}

			$media_sources = array();
			foreach ( Tagregator::get_instance()->media_sources as $source ) {
				$media_sources[] = $source::POST_TYPE_SLUG;
			};

			$logos = array(
				'twitter'   => plugins_url( 'images/source-logos/twitter.png',     __DIR__ ),
				'instagram' => plugins_url( 'images/source-logos/instagram.png',   __DIR__ ),
				'flickr'    => plugins_url( 'images/source-logos/flickr.png',      __DIR__ ),
				'google'    => plugins_url( 'images/source-logos/google-plus.png', __DIR__ ),
			);

			ob_start();
			require_once( $this->view_folder . '/shortcode-tagregator.php' );
			return apply_filters( Tagregator::PREFIX . 'shortcode_output', ob_get_clean() );
		}

		/**
		 * Get recent items from all media sources
		 *
		 * @mvc Model
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return array
		 */
		public function rest_get_items( $request ) {
			$source_post_types    = array();
			$source_post_type_map = array();
			$hashtags             = (array) $request->get_param( 'hashtags' );
			$valid_fields         = array( 'ID', 'post_content', 'post_excerpt', 'post_title', 'post_type', 'post_date_gmt' );

			foreach ( Tagregator::get_instance()->media_sources as $source ) {
				$source_post_types[] = $source::POST_TYPE_SLUG;
				$source_post_type_map[ $source::POST_TYPE_SLUG ] = $source;
			}

			$hashtags = array_map( 'sanitize_text_field', $hashtags );
			self::import_new_items( $hashtags );

			// `tax_query` will return posts matching any term if some of the passed terms don't exist
			foreach ( $hashtags as $index => $hashtag ) {
				if ( ! term_exists( $hashtag, TGGRMediaSource::TAXONOMY_HASHTAG_SLUG ) ) {
					unset( $hashtags[ $index ] );
				}
			}

			if ( empty ( $hashtags ) ) {
				return array();
			}

			$GLOBALS['wpdb']->queries = array();

			$items = get_posts( array(
				'post_type'        => $source_post_types,
				'posts_per_page'   => $this->refresh_interval, // there's no point in giving them more they can read before the page refreshes
				'suppress_filters' => false,

				'tax_query' => array(
					array(
						'taxonomy' => TGGRMediaSource::TAXONOMY_HASHTAG_SLUG,
						'field'    => 'slug',
						'terms'    => $hashtags,
					)
				),
			) );

			// Prune unneeded fields to minimize the JSON response size, then add meta fields
			foreach ( $items as $index => $item ) {
				// Apply 'tagregator_content' before wp_texturize() to avoid malformed links. See https://core.trac.wordpress.org/ticket/17097#comment:1
				$item->post_content = apply_filters( 'tagregator_content', $item->post_content );
				$item->post_content = apply_filters( 'the_content',        $item->post_content );
				$item->post_excerpt = self::get_the_excerpt( $item );

				$item = array_intersect_key( (array) $item, array_flip( $valid_fields ) );
				$item = $source_post_type_map[ $item['post_type'] ]->add_item_meta_data( $item );

				$items[ $index ] = $item;
			}

			return $items;
		}

		/**
		 * Temporary replacement for Core's get_the_excerpt()
		 *
		 * This is only necessary to work around the bug described in #36934-core and #37519-core. Once
		 * #36934-core is fixed, this can be removed, and any code calling it can just call get_the_excerpt().
		 * Maybe wait until that release has been widely adopted, though, or just bump TGGR_REQUIRED_WP_VERSION
		 *
		 * @param WP_Post $item
		 *
		 * @return string
		 */
		protected static function get_the_excerpt( $item ) {
			$excerpt_length = apply_filters( 'excerpt_length', 55 );

			$text = $item->post_content;
			$text = strip_shortcodes( $text );
			$text = apply_filters( 'tagregator_content', $text );
			$text = apply_filters( 'he_content',         $text );
			$text = str_replace( ']]>', ']]&gt;', $text );
			$text = wp_trim_words( $text, $excerpt_length, '' );

			return $text;
		}

		/**
		 * Imports the latest items from media sources
		 *
		 * @mvc Controller
		 *
		 * The semaphore is used to prevent importing the same post twice in a parallel request. The key is
		 * based on the `site_url()` in order to avoid blocking requests to other sites in the same multisite network,
		 * or other single-site installations on the same server. We could include the hashtag in the key as
		 * well in order to allow parallel requests for different hashtags, but that would require handling the case
		 * where multiple hashtags are used in one or both requests, which would complicate things without adding
		 * much benefit.
		 *
		 * @param array  $hashtags
		 * @param string $rate_limit 'respect' to enforce the rate limit, or 'ignore' to ignore it
		 */
		protected function import_new_items( $hashtags, $rate_limit = 'respect' ) {
			$semaphore_key = (int) base_convert( substr( md5( __METHOD__ . site_url() ), 0, 8 ), 16, 10 );
			$semaphore_id  = function_exists( 'sem_get' ) ? sem_get( $semaphore_key ) : false;

			if ( $semaphore_id ) {
				sem_acquire( $semaphore_id );
			}

			$last_fetch = get_transient( Tagregator::PREFIX . 'last_media_fetch' );

			if ( 'ignore' == $rate_limit || self::refresh_interval_elapsed( $last_fetch, $this->refresh_interval ) ) {
				set_transient( Tagregator::PREFIX . 'last_media_fetch', microtime( true ) );	// do this right away to minimize the chance of race conditions on systems that don't support the Semaphore module

				foreach ( Tagregator::get_instance()->media_sources as $source ) {
					foreach( $hashtags as $hashtag ) {
						$source->import_new_items( trim( $hashtag ) );
					}
				}
			}

			if ( $semaphore_id ) {
				sem_release( $semaphore_id );
			}
		}

		/**
		 * Determines if the enough time has passed since the previous media fetch
		 *
		 * @param int $last_fetch The number of seconds between the Unix epoch and the last time the data was fetched, as a float (i.e., the recorded output of microtime( true ) during the last fetch).
		 * @param int $refresh_interval The minimum number of seconds that should elapse between refreshes
		 * @return bool
		 */
		protected static function refresh_interval_elapsed( $last_fetch, $refresh_interval ) {
			$current_time = microtime( true );
			$elapsed_time = $current_time - $last_fetch;

			return $elapsed_time > $refresh_interval;
		}

		/**
		 * Fetches media items for a given hashtag when a post is saved, so that they'll be available immediately when the shortcode is displayed for the first time
		 * Note that this works, even though it often appears to do nothing. The problem is that Twitter's search API often returns no results,
		 * even when matching tweets exist. See https://dev.twitter.com/docs/faq#8650 more for details.
		 *
		 * @Controller
		 *
		 * @param int $post_id
		 * @param WP_Post $post
		 */
		public function prefetch_media_items( $post_id, $post ) {
			$ignored_actions = array( 'trash', 'untrash', 'restore' );

			if ( 1 !== did_action( 'save_post' ) ) {
				return;
			}

			if ( isset( $_GET['action'] ) && in_array( $_GET['action'], $ignored_actions ) ) {
				return;
			}

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! $post || $post->post_status == 'auto-draft' ) {
				return;
			}

			preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $shortcodes, PREG_SET_ORDER );

			foreach ( $shortcodes as $shortcode ) {
				if ( self::SHORTCODE_NAME == $shortcode[2] ) {
					$attributes = shortcode_parse_atts( $shortcode[3] );

					// todo can replace all this with has_shortcode()

					if ( empty( $attributes['hashtags'] ) && ! empty( $attributes['hashtag'] ) ) {
						$attributes['hashtags'] = $attributes['hashtag'];   // for backwards compatibility
					}

					if ( isset( $attributes['hashtags'] ) ) {
						self::import_new_items( explode( ',', $attributes['hashtags'] ), 'ignore' );
					}
				}
			}
		}
	} // end TGGRShortcodeTagregator
}
