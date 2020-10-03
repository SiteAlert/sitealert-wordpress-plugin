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
delete_option( 'wphc_dismissed_three_week_upsell' );
delete_option( 'wphc_install_timestamp' );
delete_option( 'wphc-premium' );

// Deletes transients from checks (the ones we can delete). The others will self-delete once expired.
delete_transient( 'wphc_supported_plugin_check' );
delete_transient( 'wphc_total_checks' );

// Deletes our daily cron event.
$timestamp = wp_next_scheduled( 'wphc_daily_scheduled_action' );
wp_unschedule_event( $timestamp, 'wphc_daily_scheduled_action' );

?>
