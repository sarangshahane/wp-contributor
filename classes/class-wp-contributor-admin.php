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
 * Wp_Contributor_Admin setup.
 *
 * @since 1.0.0
 */
class Wp_Contributor_Admin {

	/**
	 * Instance of Wp_Contributor_Admin
	 *
	 * @var Wp_Contributor_Admin
	 */
	private static $instance = null;

	/**
	 * Retrieve the Instance of Wp_Contributor_Admin Class
	 *
	 * @return Wp_Contributor_Admin Instance of Wp_Contributor_Admin class
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_plugin_metaboxs' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );
	}

	/**
	 * Register meta box(es).
	 */
	function register_plugin_metaboxs() {

		$post_types = apply_filters( 'wp_contributor_show_metabox_for_posts', array( 'post', 'page' ) );

		// Here wpc stands for Wp_Contributor.
		add_meta_box(
			'wpc-meta-box',
			__( 'WP Contributors', 'wp-contributors' ),
			array(
				$this,
				'metabox_render_html',
			),
			$post_types,
			'side',
			'high'
		);
	}

	/**
	 * Render Meta field with minor HTML.
	 *
	 * @param  POST $post Current post object which is being displayed.
	 */
	function metabox_render_html( $post ) {

		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'wp_contributor_nounce', 'wp_contributor_nounce' );

		$users          = $this->prepare_users_data();
		$selected_users = get_post_meta( $post->ID, 'wp_contributors_list', true );

		if ( empty( $users ) ) {
			return $post;
		}

		?>
			<p class="description"><?php esc_html_e( 'Select the users who helped to create this post/page.', 'wp-contributor' ); ?> </p>			
			<div class="wpc-users-list" style="display: block; max-height: 120px; overflow-y: auto; overflow-x: hidden; height: 100%; padding: 0 0 10px 0;">
		<?php
		foreach ( $users as $user_id => $user_data ) {

			$user_first_name = $user_data['first_name'] ? $user_data['first_name'] : '';
			$user_last_name  = $user_data['last_name'] ? $user_data['last_name'] : '';

			$full_name = $user_first_name . ' ' . $user_last_name;

			$full_name = ! empty( $full_name ) ? $full_name : $user_data['display_name'];
			echo "<label class='checkbox-inline' style='display: block; margin-top: 10px;'>";
			echo "<input type='checkbox' name='wp_contributors[]' style='margin-right: 6px;' value='" . (int) $user_data['id'] . "'>" . $full_name;
			echo '</label>';
		}
		?>
			</div>
		<?php
	}

	/**
	 * Prepare WordPress user's data.
	 *
	 * @return  array $user_info WordPress user's info.
	 */
	public function prepare_users_data() {

		$wp_users  = get_users( array( 'fields' => array( 'ID', 'name', 'display_name', 'user_nicename' ) ) );
		$all_users = array();

		foreach ( $wp_users as $user ) {
			$user_id   = $user->ID;
			$user_info = get_userdata( $user_id );

			$all_users[ $user_id ] = array(
				'id'            => $user_id,
				'first_name'    => $user_info->first_name ? $user_info->first_name : '',
				'last_name'     => $user_info->last_name ? $user_info->last_name : '',
				'display_name'  => $user_info->display_name ? $user_info->display_name : '',
				'user_nicename' => $user_info->user_nicename ? $user_info->user_nicename : '',
			);
		}

		return $all_users;
	}

	/**
	 * Save meta field.
	 *
	 * @param  POST $post_id Current post object which is being displayed.
	 *
	 * @return Void
	 */
	public function save_meta( $post_id ) {

		// Return if doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// if nonce isn't there, or we can't verify it, return.
		if ( ! isset( $_POST['wp_contributor_nounce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wp_contributor_nounce'] ) ), 'wp_contributor_nounce' ) ) {
			return;
		}

		// if our current user can't edit this post, return.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		// Don't save these options for revisions.
		if ( false !== wp_is_post_revision( $post_id ) ) {
			return;
		}

		$selected_contributor = isset( $_POST['wp_contributors'] ) ? $_POST['wp_contributors'] : '';

		if ( empty( $selected_contributor ) ) {
			return;
		}

		update_post_meta( $post_id, 'wp_contributors_list', $selected_contributor, false );

	}
}

Wp_Contributor_Admin::instance();
