<?php
/**
 * Plugin's main file.
 *
 * @package  wp_contributor_Loader
 * @since  1.0.0
 */

/**
 * Wp_Contributor_Loader setup
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Wp_Contributor_Loader' ) ) {

	/**
	 * Class Wp_Contributor_Loader
	 *
	 * @since  1.0.0
	 */
	class Wp_Contributor_Loader {

		/**
		 * Instance of Wp_Contributor_Loader
		 *
		 * @since  1.0.0
		 * @var Wp_Contributor_Loader
		 */
		private static $instance = null;

		/**
		 * Instance of Wp_Contributor_Loader
		 *
		 * @since  1.0.0
		 * @return Wp_Contributor_Loader Instance of Wp_Contributor_Loader
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 */
		private function __construct() {
			$this->define_plugin_constants();
			
			// Load the plugins files as soon as the plugin is activated.
			add_action( 'plugins_loaded', array( $this, 'load_plugin_files' ) );
		}

		/**
		 * Define the constants of the plugin.
		 *
		 * @since  1.0.0
		 */
		public function define_plugin_constants() {
			
			define( 'WP_CONTRIBUTOR_BASE', plugin_basename( WP_CONTRIBUTOR_FILE ) );
			define( 'WP_CONTRIBUTOR_DIR', plugin_dir_path( WP_CONTRIBUTOR_FILE ) );
			define( 'WP_CONTRIBUTOR_URL', plugins_url( '/', WP_CONTRIBUTOR_FILE ) );

			define( 'WP_CONTRIBUTOR_VER', '1.11.1' );
			define( 'WP_CONTRIBUTOR_SLUG', 'wp-contributor' );
			define( 'WP_CONTRIBUTOR_NAME', 'WP Contributor' );

		}

		/**
		 * Include required files.
		 *
		 * @since 1.0.0
		 */
		public function load_plugin_files() {

			if ( is_admin() ) {
				require_once WP_CONTRIBUTOR_DIR . 'classes/class-wp-contributor-admin.php';
			}

		}

	}

	Wp_Contributor_Loader::instance();
}