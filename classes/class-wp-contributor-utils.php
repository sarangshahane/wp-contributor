<?php
/**
 * Utility functions for the plugin.
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
if ( ! class_exists( 'Wp_Contributor_Utils' ) ) {
	/**
	 * Class Wp_Contributor_Utils
	 *
	 * @since  1.0.0
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
				self::$instance = new self();
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
			 * Filter `wpc_show_metabox_for_posts`.
			 *
			 * You can use this filter to add a custom support of your own custom post type to display this setting.
			 */
			return apply_filters( 'wpc_show_metabox_for_posts', array( 'post', 'page' ) );
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

		/**
		 * Check the current page is allowed page in frontend.
		 *
		 * @since 1.0.0
		 * @return bool
		 */
		public function is_allowed_frontend_page() {
			global $post;

			if ( $post && ! is_archive() && is_singular() && 'post' === get_post_type( $post ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Get the saved contributor's data.
		 *
		 * @param POST $post_id Current page's post ID.
		 * @since 1.0.0
		 * @return array $contributors The list of contributors.
		 */
		public function get_contributors_list( $post_id = '' ) {
			global $post;

			if ( ! in_array( $post->post_type, $this->get_supported_post_types(), true ) ) {
				return;
			}

			if ( empty( $post_id ) ) {
				$post_id = ! empty( $post ) ? $post->ID : '';
			}

			$contributors = get_post_meta( $post_id, 'wp_contributors_list', true );

			if ( ! is_array( $contributors ) ) {
				$contributors = json_decode( $contributors, true );
			}

			return $contributors;
		}
	}
}
