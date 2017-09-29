<?php
/**
 * Add a new Debug Bar Panel.
 */
class DS_Debug_Bar_Transients extends Debug_Bar_Panel {

	/**
	 * Holds all of the transients.
	 *
	 * @var array
	 */
	private $_transients = array();

	/**
	 * Holds all of the site transients.
	 *
	 * @var array
	 */
	private $_site_transients = array();

	/**
	 * Holds only the core site transients.
	 *
	 * @var array
	 */
	private $_core_transients = array();

	/**
	 * Holds only the core site transients..
	 *
	 * @var array
	 */
	private $_core_site_transients = array();

	/**
	 * Holds only the transients created by plugins or themes.
	 *
	 * @var array
	 */
	private $_user_transients = array();

	/**
	 * Holds only the site transients created by plugins or themes.
	 *
	 * @var array
	 */
	private $_user_site_transients = array();

	/**
	 * Total number of transients
	 *
	 * @var int
	 */
	private $_total_transients = 0;

	/**
	 * Total number of invalid transients
	 *
	 * @var int
	 */
	private $_invalid_transients = 0;

	/**
	 * Total number of core transients.
	 *
	 * @var int
	 */
	private $_total_core_transients = 0;

	/**
	 * Total number of core site transients..
	 *
	 * @var int
	 */
	private $_total_core_site_transients = 0;

	/**
	 * Total number of transients created by plugins or themes.
	 *
	 * @var int
	 */
	private $_total_user_transients = 0;

	/**
	 * Total number of site transients created by plugins or themes.
	 *
	 * @var int
	 */
	private $_total_user_site_transients = 0;


	/**
	 * Give the panel a title and set the enqueues.
	 */
	public function init() {
		$this->title( __( 'Transients', 'debug-bar-transients' ) );

		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );
		add_action( 'admin_print_styles', array( $this, 'print_styles' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'print_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'print_scripts' ) );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load the textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'debug-bar-transients' );
	}

	/**
	 * Enqueue styles.
	 */
	public function print_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'debug-bar-transients',
			plugins_url( "css/debug-bar-transients$suffix.css", __FILE__ ),
			array(),
			'10042012'
		);
	}

	/**
	 * Enqueue scripts.
	 */
	public function print_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'debug-bar-transients',
			plugins_url( "js/debug-bar-transients$suffix.js", __FILE__ ),
			array( 'jquery' ),
			'16042012'
		);
	}

	/**
	 * Show the menu item in Debug Bar.
	 */
	public function prerender() {
		$this->set_visible( true );
	}

	/**
	 * Show the contents of the page.
	 */
	public function render() {
		global $_wp_using_ext_object_cache;

		if ( $_wp_using_ext_object_cache ) {
			echo '<p class="invalid">' . __( 'You are using an unsupported external object cache.', 'debug-bar-transients' ) . '</p>';
			return;
		}

		$this->get_total_transients();

		printf(
			'<h2><span>%s</span>%s</h2>',
			__( 'Total Transients:', 'debug-bar-transients' ),
			number_format( $this->_total_transients )
		);

		printf(
			'<h2><span>%s</span>%s</h2>',
			__( 'Invalid Transients:', 'debug-bar-transients' ),
			number_format( $this->_invalid_transients )
		);

		printf(
			'<h2><a href="#core-transients"><span>%s:</span>%s</a></h2>',
			__( 'Core Transients', 'debug-bar-transients' ),
			number_format( $this->_total_core_transients )
		);

		printf(
			'<h2><a href="#core-site-transients"><span>%s:</span>%s</a></h2>',
			__( 'Core Site Transients', 'debug-bar-transients' ),
			number_format( $this->_total_core_site_transients )
		);

		printf(
			'<h2><a href="#custom-transients"><span>%s:</span>%s</a></h2>',
			__( 'Custom Transients', 'debug-bar-transients' ),
			number_format( $this->_total_user_transients )
		);

		printf(
			'<h2><a href="#custom-site-transients"><span>%s:</span>%s</a></h2>',
			__( 'Custom Site Transients', 'debug-bar-transients' ),
			number_format( $this->_total_user_site_transients )
		);

		wp_nonce_field( 'ds-delete-transient', '_ds-delete-transient-nonce' );

		echo '<h3 id="custom-transients">' . __( 'Custom Transients', 'debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_user_transients ) ) {
			echo __( 'No transients found.', 'debug-bar-transients' );
		} else {
			$this->display_transients( $this->_user_transients );
		}

		echo '<h3 id="custom-site-transients">' . __( 'Custom Site Transients', 'debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_user_site_transients ) ) {
			echo __( 'No transients found.', 'debug-bar-transients' );
		} else {
			$this->display_transients( $this->_user_site_transients, true );
		}

		echo '<h3 id="core-transients">' . __( 'Core Transients', 'debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_core_transients ) ) {
			echo __( 'No transients found.', 'debug-bar-transients' );
		} else {
			$this->display_transients( $this->_core_transients );
		}

		echo '<h3 id="core-site-transients">' . __( 'Core Site Transients', 'debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_core_site_transients ) ) {
			echo __( 'No transients found.', 'debug-bar-transients' );
		} else {
			$this->display_transients( $this->_core_site_transients, true );
		}
	}

	/**
	 * Retrieve all the transients.
	 *
	 * @return int Total number of transients.
	 */
	private function get_total_transients() {
		$this->get_transients();

		foreach ( $this->_transients as $transient => $data ) {
			$this->_total_transients++;

			if ( $this->_wildcard_search( $transient, $this->get_core_transient_names() ) ) {
				$this->_total_core_transients++;
				$this->_core_transients[ $transient ] = $data;
			} else {
				$this->_total_user_transients++;
				$this->_user_transients[ $transient ] = $data;
			}
		}

		$this->get_site_transients();

		foreach ( $this->_site_transients as $transient => $data ) {
			$this->_total_transients++;

			if ( $this->_wildcard_search( $transient, $this->get_core_site_transient_names() ) ) {
				$this->_total_core_site_transients++;
				$this->_core_site_transients[ $transient ] = $data;
			} else {
				$this->_total_user_transients++;
				$this->_user_site_transients[ $transient ] = $data;
			}
		}

		return $this->_total_transients;
	}

	/**
	 * Check if a string does exist in an array.
	 *
	 * @param  string  $needle   String to search.
	 * @param  array   $haystack Array in which string should be searched.
	 * @return boolean           True if founded, false if not.
	 */
	private function _wildcard_search( $needle, $haystack ) {
		foreach ( $haystack as $h ) {
			if ( 0 === strpos( $needle, $h ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Retrieve the transients (for a site).
	 *
	 * @return array The transients.
	 */
	private function get_transients() {
		if ( ! empty( $this->_transients ) ) {
			return $this->_transients;
		}

		global $wpdb;

		$transients = $wpdb->get_results(
			"SELECT option_name AS name, option_value AS value FROM $wpdb->options WHERE option_name LIKE '_transient_%'"
		);

		array_walk( $transients, array( $this, '_format_transient' ) );
		unset( $transients );

		return $this->_transients;
	}

	/**
	 * Format the transients.
	 *
	 * @param  object $value One transient value from the database.
	 */
	private function _format_transient( $value ) {
		if ( false === strpos( $value->name, '_transient_timeout_' ) ) {
			$this->_transients[ str_replace( '_transient_', '', $value->name ) ]['value'] = $value->value;
		} else {
			$this->_transients[ str_replace( '_transient_timeout_', '', $value->name ) ]['timeout'] = $value->value;

			if ( $value->value < time() ) {
				$this->_invalid_transients++;
			}
		}
	}

	/**
	 * Retrieve the transients (for a network).
	 *
	 * @return array The site transients.
	 */
	private function get_site_transients() {
		if ( ! empty( $this->_site_transients ) ) {
			return $this->_site_transients;
		}

		global $wpdb;

		if ( is_multisite() ) {
			$transients = $wpdb->get_results(
				"SELECT meta_key AS name, meta_value AS value FROM $wpdb->sitemeta WHERE meta_key LIKE '_site_transient_%' AND site_id = $wpdb->siteid"
			);
		} else {
			$transients = $wpdb->get_results(
				"SELECT option_name AS name, option_value AS value FROM $wpdb->options WHERE option_name LIKE '_site_transient_%'"
			);
		}

		array_walk( $transients, array( $this, '_format_site_transient' ) );
		unset( $transients );

		return $this->_site_transients;
	}

	/**
	 * Format the site transients.
	 *
	 * @param  object $value One transient value from the database.
	 */
	private function _format_site_transient( $value ) {
		if ( false === strpos( $value->name, '_site_transient_timeout_' ) ) {
			$this->_site_transients[ str_replace( '_site_transient_', '', $value->name ) ]['value'] = $value->value;
		} else {
			$this->_site_transients[ str_replace( '_site_transient_timeout_', '', $value->name ) ]['timeout'] = $value->value;

			if ( $value->value < time() ) {
				$this->_invalid_transients++;
			}
		}
	}

	/**
	 * Display the transients in a table.
	 *
	 * @param  array  $transients      The transients in an array.
	 * @param  boolean $site_transient If it's a site transient or not. Default: false.
	 */
	private function display_transients( $transients, $site_transient = false ) {
		if ( empty( $transients ) )
			return;

		echo '<table cellspacing="0">';
		echo '<thead><tr>';
		echo '<th class="transient-name">' . __( 'Name', 'debug-bar-transients' ) . '</th>';
		echo '<th class="transient-value">' . __( 'Value', 'debug-bar-transients' ) . '</th>';
		echo '<th class="transient-timeout">' . __( 'Expiration', 'debug-bar-transients' ) . '</th>';
		echo '</tr></thead>';


		$delete_link = sprintf(
			'<span><a class="delete" data-transient-type="%s" data-transient-name="$" title="%s" href="#">%s</a></span>',
			( $site_transient ? 'site' : '' ),
			__( 'Delete this transient (No undo!)', 'debug-bar-transients' ),
			__( 'Delete', 'debug-bar-transients')
		);

		$switch_link = sprintf(
			'<span class="switch-value"><a title="%s" href="#">%s</a></span>',
			__( 'Switch between serialized and unserialized view', 'debug-bar-transients' ),
			__( 'Switch value view', 'debug-bar-transients' )
		);

		foreach( $transients as $transient => $data ) {
			if ( isset( $data['value'] ) ) {
				echo '<tr>';
			} else {
				echo '<tr class="transient-error">';
			}
			echo '<td>' . $transient . '<div class="row-actions">' . str_replace( '$', $transient, $delete_link ) . ( isset( $data['value'] ) ? ' | ' . $switch_link : '' ) . '</div></td>';
			if ( isset( $data['value'] ) ) {
				echo '<td><pre class="serialized" title="' .  __( 'Click to expand', 'debug-bar-transients' ) . '">' . esc_html( $data['value'] ) . '</pre><pre class="unserialized" title="' .  __( 'Click to expand' ) . '">' . esc_html( print_r( maybe_unserialize( $data['value'] ), true ) ) . '</pre></td>';
			} else {
				echo '<td><p>' . __( 'Invalid transient - the transient name was probably truncated. Limit is 64 characters.', 'debug-bar-transients' ) . '</p></td>';
			}
			echo '<td>' . $this->_print_timeout( $data )  . '</td>';
			echo '</tr>';
		}

		echo '</table>';
	}

	/**
	 * Prepare the timeout value of a transient.
	 *
	 * @param  array   $data One transient value from the database.
	 * @return string        The prepared time.
	 */
	private function _print_timeout( $data ) {
		if ( empty( $data['timeout'] ) ) {
			return __( 'Unknown', 'debug-bar-transients' );
		} else {
			$time = $data['timeout'];
		}

		return sprintf(
			'%s<br />%s<br />%s',
			sprintf(
				__( 'Unix-Timestamp: %d', 'debug-bar-transients' ),
				$time
			),
			date_i18n(
				__( 'M j, Y @ G:i' ),
				$time
			),
			( $time > time() ) ?
				sprintf(
					__( '(in %s)', 'debug-bar-transients' ),
					human_time_diff( $time )
				)
				:
				sprintf(
					'<span class="invalid">' . __( '(invalid since %s)', 'debug-bar-transients'  ) . '</span>',
					human_time_diff( $time )
				)
		);
	}

	/**
	 * Returns the transient names which are used by core.
	 */
	private function get_core_transient_names() {
		return array(
			'random_seed',
			'wporg_theme_feature_list',
			'settings_errors',
			'doing_cron',
			'plugin_slugs',
			'mailserver_last_checked',
			'dirsize_cache',
			'dash_',
			'rss_',
			'feed_',
			'feed_mod_',
			'plugins_delete_result_',
			'is_multi_author',
			'wp_generating_att_',
		);
	}

	/**
	 * Returns the site transient names which are used by core.
	 */
	private function get_core_site_transient_names() {
		return array(
			'update_core',
			'update_plugins',
			'update_themes',
			'wporg_theme_feature_list',
			'browser_',
			'poptags_',
			'wordpress_credits_',
			'theme_roots',
			'popular_importers_',
			'available_translations',
		);
	}
}
