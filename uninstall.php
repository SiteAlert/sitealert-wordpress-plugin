<?php
//if uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'wphc_review_message_trigger' );
delete_option( 'wphc-settings' );
delete_option( 'wphc_tracker_last_time' );
delete_option( 'wphc-tracking-notice' );

?>
