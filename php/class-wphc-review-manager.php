<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class that handles displaying of review notice
 *
 * @since 1.1.0
 */
class WPHC_Review_Manager {

	/**
	 * Variable to hold how many results needed to show message
	 *
	 * @since 1.1.0
	 */
	public $trigger = -1;

	/**
	 * Main Construct Function
	 *
	 * Adds the notice check to init and then check to display message
	 *
	 * @since 1.1.0
	 */
	function __construct() {
		$this->check_message_display();
	}

	/**
	 * Checks if message should be displayed
	 *
	 * @since 1.1.0
	 */
	public function check_message_display() {
		$this->admin_notice_check();
		$this->trigger = $this->check_message_trigger();
		if ( -1 !== $this->trigger ) {
			if ( ! empty( $this->trigger ) && $this->trigger < strtotime( '-1 week' ) ) {
				add_action( 'admin_notices', array( $this, 'display_admin_message' ) );
			}
		}
	}


	/**
	 * Retrieves what the next trigger value is
	 *
	 * @since 1.1.0
	 * @return int The amount of results needed to display message
	 */
	public function check_message_trigger() {
		$trigger = get_option( 'wphc_review_message_trigger' );
		if ( empty( $trigger ) || is_null( $trigger ) || false === $trigger ) {
			add_option('wphc_review_message_trigger', time() );
			return time();
		}
		return intval( $trigger );
	}

	/**
	 * Displays the message
	 *
	 * Displays the message asking for review
	 *
	 * @since 1.1.0
	 */
	public function display_admin_message() {
		$already_url  = esc_url( add_query_arg( 'wphc_review_notice_check', 'already_did' ) );
		$nope_url  = esc_url( add_query_arg( 'wphc_review_notice_check', 'remove_message' ) );
		echo "<div class='updated'><br />";
		echo sprintf( __('Greetings! I just noticed that you have been using the My WordPress Health Check plugin for over a week now. That is
		awesome! Could you please help me out by giving this plugin a 5-star rating on WordPress? This
		will help me by helping other users discover this plugin. %s', 'my-wp-health-check'),
			'<br /><strong><em>~ Frank Corso</em></strong><br /><br />'
		);
		echo '&nbsp;<a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/my-wp-health-check?rate=5#postform" class="button-primary">' . __( 'Yeah, you deserve it!', 'my-wp-health-check' ) . '</a>';
		echo '&nbsp;<a href="' . esc_url( $already_url ) . '" class="button-secondary">' . __( 'I already did!', 'my-wp-health-check' ) . '</a>';
  		echo '&nbsp;<a href="' . esc_url( $nope_url ) . '" class="button-secondary">' . __( 'No, this plugin is not good enough', 'my-wp-health-check' ) . '</a>';
		echo "<br /><br /></div>";
	}

	/**
	 * Checks if a link in the admin message has been clicked
	 *
	 * @since 1.1.0
	 */
	public function admin_notice_check() {
		if ( isset( $_GET["wphc_review_notice_check"] ) && 'remove_message' == $_GET["wphc_review_notice_check"] ) {
			update_option( 'wphc_review_message_trigger', -1 );
		}
		if ( isset( $_GET["wphc_review_notice_check"] ) && 'already_did' == $_GET["wphc_review_notice_check"] ) {
			update_option( 'wphc_review_message_trigger', -1 );
		}
	}
}

$wphc_review_manager = new WPHC_Review_Manager();
?>
