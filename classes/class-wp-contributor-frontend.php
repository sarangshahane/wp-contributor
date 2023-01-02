<?php
/**
 * Frontend Functions for the plugin.
 *
 * @package  Wp_Contributor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wp_Contributor_Frontend setup.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Wp_Contributor_Frontend' ) ) {

	/**
	 * Class Wp_Contributor_Frontend
	 *
	 * @since  1.0.0
	 */
	class Wp_Contributor_Frontend {

		/**
		 * Instance of Wp_Contributor_Frontend
		 *
		 * @var Wp_Contributor_Frontend
		 * @since 1.0.0
		 */
		private static $instance = null;

		/**
		 * Retrieve the Instance of Wp_Contributor_Frontend Class
		 *
		 * @return Wp_Contributor_Frontend Instance of Wp_Contributor_Frontend class
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
			add_filter( 'the_content', array( $this, 'print_contributor_list' ), 9999, 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ) );
			add_shortcode( 'wpc_contributor_list', array( $this, 'contributor_shortcode_markup' ), 10, 1 );
		}

		/**
		 * Load frontend Styles & scripts
		 *
		 * @return Void
		 * @since 1.0.0
		 */
		public function load_frontend_scripts() {
			if ( ! wpc_loader()->utils->is_allowed_frontend_page() ) {
				return;
			}

			wp_enqueue_style( 'wpc-frontend-css', WPC_URL . 'assets/css/frontend.css', array(), WPC_VER );
			wp_style_add_data( 'wpc-frontend-css', 'rtl', 'replace' );
		}

		/**
		 * Load frontend Styles & scripts
		 *
		 * @param string $content The actual post content.
		 * @return string $content The updated post content.
		 * @since 1.0.0
		 */
		public function print_contributor_list( $content ) {
			global $post;

			if ( $post && wpc_loader()->utils->is_allowed_frontend_page() ) {
				return $this->generate_contributor_html_markup( $post->ID, $content );
			}

			return $content;
		}

		/**
		 * Generate contributor HTML markup.
		 *
		 * @param  POST   $post_id Current post ID which is being displayed.
		 * @param  string $content The updated post content.
		 *
		 * @return Void
		 * @since 1.0.0
		 */
		public function generate_contributor_html_markup( $post_id, $content ) {
			$content .= '</div></div></article>'; // Closing the first section to add our own designed section.
			$content .= "<article class='wpc-contributor-wrapper'>";
			$content .= "<div class='article-single'>";
			$content .= "<div class='entry-content clear'>";
			$content .= $this->get_contributor_html_section( $post_id );

			return $content;
		}

		/**
		 * Generate contributor HTML markup.
		 *
		 * @param  POST $post_id Current post ID which is being displayed.
		 *
		 * @return Void
		 * @since 1.0.0
		 */
		public function get_contributor_html_section( $post_id ) {
			$contributors = wpc_loader()->utils->get_contributors_list( $post_id );

			$output  = '';
			$output .= "<h1 class='entry-title' itemprop='headline'>" . __( 'Contributors', 'wpc' ) . '</h1>';
			$output .= "<div class='wpc-contributor-list'>";
			$output .= '<table>';
			$output .= '<tbody>';

			foreach ( $contributors as $id => $data ) {
				$output .= '<tr>';
				$output .= "<td><img class='wpc-contributor--user-icon' src='" . $data['user_avatar_url'] . "'></td>";
				$output .= "<td><a class='wpc-contributor--user-link' href='" . get_author_posts_url( $data['id'] ) . "' target='_blank'> <span class='wpc-contributor--user-name'>" . $data['first_name'] . ' ' . $data['last_name'] . '</span></a></td>';
				$output .= '</tr>';
			}

			$output .= '</tbody>';
			$output .= '</table>';
			$output .= '</div>'; // Contributor list wrapper close.

			return $output;
		}

		/**
		 * Display the contributor using shortcode.
		 *
		 * @param  array $attrs The shortcode attributes.
		 *
		 * @return string $output the Shortcode Markup.
		 * @since 1.0.0
		 */
		public function contributor_shortcode_markup( $attrs ) {
			$attrs = shortcode_atts(
				array(
					'id' => 0,
				),
				$attrs
			);

			$post_id = intval( $attrs['id'] );

			// Bail out if no post ID is found or empty.
			if ( empty( $post_id ) ) {
				global $post;

				$post_id = intval( $post->ID );
			}

			// Bail out if not our desired page.
			if ( ! wpc_loader()->utils->is_allowed_frontend_page() ) {
				return;
			}

			$output = '';

			ob_start();

			$output = $this->get_contributor_html_section( $post_id );

			$output .= ob_get_clean();

			return $output;
		}

	}

	/**
	 *  Prepare if class 'Wp_Contributor_Frontend' exist.
	 *  Kicking this off by calling 'instance()' method.
	 */
	Wp_Contributor_Frontend::instance();
}
