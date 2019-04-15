<?php
/**
 * Uninstallation script.
 *
 * @package WPHC
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Deletes plugin's settings.
delete_option( 'wphc_review_message_trigger' );
delete_option( 'wphc-settings' );
delete_option( 'wphc_tracker_last_time' );
delete_option( 'wphc-tracking-notice' );

// Deletes transients from checks (the ones we can delete). The others will self-delete once expired.
delete_transient( 'wphc_supported_plugin_check' );
delete_transient( 'wphc_total_checks' );

?>
