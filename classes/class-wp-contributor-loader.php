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
		 * Member Variable
		 *
		 * @var Wp_Contributor_Utils
		 */
		public $utils = null;

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

			define( 'WPC_BASE', plugin_basename( WPC_FILE ) );
			define( 'WPC_DIR', plugin_dir_path( WPC_FILE ) );
			define( 'WPC_URL', plugins_url( '/', WPC_FILE ) );

			define( 'WPC_VER', '1.11.1' );
			define( 'WPC_SLUG', 'wp-contributor' );
			define( 'WPC_NAME', 'WP Contributor' );

		}

		/**
		 * Include required files.
		 *
		 * @since 1.0.0
		 */
		public function load_plugin_files() {

			$this->load_utility_files();

			if ( is_admin() ) {
				$this->load_admin_files();
			}
		}

		/**
		 * Load admin area related files.
		 *
		 * @since 1.0.0
		 */
		public function load_admin_files() {

			require_once WPC_DIR . 'classes/class-wp-contributor-admin.php';
		}

		/**
		 * Load utility/helper files.
		 *
		 * @since 1.0.0
		 */
		public function load_utility_files() {
			require_once WPC_DIR . 'classes/class-wp-contributor-utils.php';

			$this->utils = Wp_Contributor_Utils::instance();
		}

	}

	Wp_Contributor_Loader::instance();
}

/**
 * Get loader class instance.
 *
 * This instance will contains most of the public functions to access OR to use.
 *
 * @return object
 */
function wpc_loader() {
	return Wp_Contributor_Loader::instance();
}
