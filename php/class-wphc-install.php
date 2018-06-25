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
		if ( get_option( 'wphc_current_version' ) != $version ) {
			// Performs updates.

			// Updates current version option.
			update_option( 'wphc_current_version', $version );
		}
	}
}

$wphc_install = new WPHC_Install();


?>
