<?php
/**
 * Contains all functions relevant to installing or updating the plugin
 *
 * @package WPHC
 */

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the installation/updating functions
 *
 * @since 1.3.2
 */
class WPHC_Install {

	/**
	 * Main constructor
	 *
	 * @since 1.3.2
	 * @uses WPHC::add_hooks
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Adds the various functions to hooks and filters
	 *
	 * @since 1.3.2
	 */
	public function add_hooks() {
		add_action( 'admin_init', array( $this, 'update' ) );
		add_filter( 'plugin_action_links_' . WPHC_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
	}

	/**
	 * Adds a link to the checks from the plugins page.
	 *
	 * @since 1.6.8
	 * @param array $links The current links in the action links.
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 */
	public function plugin_action_links( $links ) {
		$action_links = array(
			'<a href="' . admin_url( 'admin.php?page=sitealert-checks' ) . '">' . __( 'View Checks', 'my-wp-health-check' ) . '</a>',
		);
		return array_merge( $action_links, $links );
	}

	/**
	 * Handles the updating of the plugin when updated
	 *
	 * @since 1.3.2
	 */
	public function update() {
		global $my_wp_health_check;
		$version = $my_wp_health_check->version;
		if ( ! get_option( 'wphc_original_version' ) ) {
			add_option( 'wphc_original_version', $version );
		}
		if ( ! get_option( 'wphc_install_timestamp' ) ) {
			add_option( 'wphc_install_timestamp', time() );
		}
		if ( get_option( 'wphc_current_version' ) != $version ) {
			// Updates current version option.
			update_option( 'wphc_current_version', $version );
		}
	}
}

$wphc_install = new WPHC_Install();
