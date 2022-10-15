<?php
/**
 * Admin functions for the plugin.
 *
 * @package  Wp_Contributor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wp_Contributor_Utils setup.
 *
 * @since 1.0.0
 */
class Wp_Contributor_Utils {

	/**
	 * Instance of Wp_Contributor_Utils
	 *
	 * @var Wp_Contributor_Utils
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Retrieve the Instance of Wp_Contributor_Utils Class
	 *
	 * @return Wp_Contributor_Utils Instance of Wp_Contributor_Utils class
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	private function __construct() {
		// Will be using this if in case need to initialize any data while loading the plugin.
	}

	/**
	 * List of the post types which are supported by the plugin.
	 *
	 * @return array Array of supported post types of the plugin.
	 * @since 1.0.0
	 */
	public function get_supported_post_types() {

		/**
		 * Filter `wp_contributor_show_metabox_for_posts`.
		 *
		 * You can use this filter to add a custom support of your own custom post type to display this setting.
		 */
		return apply_filters( 'wp_contributor_show_metabox_for_posts', array( 'post', 'page' ) );
	}

	/**
	 * Check allowed screen to load the plugin's assets.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_allowed_screen() {

		$screen    = get_current_screen();
		$post_type = $screen ? $screen->post_type : '';

		if ( ! $post_type ) {
			return false;
		}

		if ( in_array( $post_type, $this->get_supported_post_types(), true ) ) {
			return true;
		}

		return false;
	}
}
