<?php
/**
 * This file handles all of the current REST API endpoints
 *
 * @since 1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'rest_api_init', 'wphc_register_rest_routes' );

/**
 * Registers REST API endpoints
 *
 * @since 1.6.0
 */
function wphc_register_rest_routes() {
	$settings = (array) get_option( 'wphc-settings', '' );
	if ( isset( $settings['api_key'] ) && ! empty( $settings['api_key'] ) ) {
		register_rest_route( 'wordpress-health-check/v1', '/checks/', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'wphc_rest_get_all_checks',
			'permission_callback' => 'wphc_rest_permission_callback'
		) );
		register_rest_route( 'wordpress-health-check/v1', '/checks/(?P<type>[a-zA-Z]+)', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'wphc_rest_get_check',
			'permission_callback' => 'wphc_rest_permission_callback'
		) );
		register_rest_route( 'wordpress-health-check/v1', '/emails/', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'wphc_rest_new_email_test',
			'permission_callback' => 'wphc_rest_permission_callback'
		) );
	}
}

/**
 * Tests the API Key provided to ensure this is a valid client
 *
 * @param WP_REST_Request $request
 * @return bool|WP_Error True if authenticated api key.
 */
function wphc_rest_permission_callback( WP_REST_Request $request ) {
	$settings = (array) get_option( 'wphc-settings', '' );
	if ( isset( $request['api_key'] ) ) {
		if ( isset( $settings['api_key'] ) && strtoupper( $request['api_key'] ) === strtoupper( $settings['api_key'] ) ) {
			return true;
		} else {
			return new WP_Error( 'unauthorized', 'The API Key supplied was not valid' );
		}
	} else {
		return new WP_Error( 'unauthorized', 'The API Key is missing' );
	}
}

/**
 * Gets the results of all checks. Requires valid API Key.
 *
 * @since 1.6.0
 * @param WP_REST_Request $request The request sent from WP REST API.
 * @return array The checks requested.
 */
function wphc_rest_get_all_checks( WP_REST_Request $request ) {
	$wphc   = new WPHC_Checks();
	return $wphc->all_checks();
}

/**
 * Gets the results from a type of check. Requires valid API Key.
 *
 * @since 1.6.0
 * @param WP_REST_Request $request The request sent from WP REST API.
 * @return array The checks requested.
 */
function wphc_rest_get_check( WP_REST_Request $request ) {
	$wphc = new WPHC_Checks();
	switch ( $request['type'] ) {
		case 'server':
			$checks = $wphc->server_checks();
			break;

		case 'wp':
			$checks = $wphc->wp_checks();
			break;

		case 'plugins':
			$checks = $wphc->plugins_checks( true, true );
			break;

		default:
			$checks = array();
			break;
	}
	return $checks;
}

/**
 * Creates a new email test. Requires valid API Key.
 *
 * @param WP_REST_Request $request The request sent from WP REST API.
 * @return array|WP_Error
 * @since 1.9.0
 */
function wphc_rest_new_email_test( WP_REST_Request $request ) {
	if ( ! isset( $request['test_email'] ) || ! is_email( $request['test_email'] ) ) {
		return new WP_Error( 'invalid', 'The test email address (key: test_email) supplied was invalid.' );
	}
	if ( ! isset( $request['test_key'] ) ) {
		return new WP_Error( 'invalid', 'The test email key (key: test_key) supplied was invalid.' );
	}

	// Prepare our transient for error catching.
	set_transient( 'wphc_wp_mail_failed_reason', '', 60 );

	// Prepare our email
	$to = sanitize_email( $request['test_email'] );
	$key = sanitize_text_field( $request['test_key'] );
	$subj = "SiteAlert Test: $key";

	// Add our function to catch any errors, send the email, and then immediately remove our function.
	add_action('wp_mail_failed', 'wphc_catch_email_errors');
	$success = wp_mail($to, $subj, '<p>Test email from SiteAlert.</p>');
	remove_action('wp_mail_failed', 'wphc_catch_email_errors');

	// See if our error reason was updated due to wp_mail_failed error.
	$reason = get_transient( 'wphc_wp_mail_failed_reason' );
	if ( ! empty( $reason ) ) {
		return array(
			'success' => false,
			'error'   => $reason
		);
	}

	// If not, determine success based on bool returned from wp_mail.
	if ( true === $success ) {
		return array(
			'success' => true
		);
	} else {
		return array(
			'success' => false,
			'error'   => 'Unknown error from wp_mail'
		);
	}
}

/**
 * Attempts to catch error from our test email
 *
 * @since 1.9.0
 * @param $wp_error
 */
function wphc_catch_email_errors( $wp_error ) {
	if ( ! is_wp_error( $wp_error ) ) {
		return;
	}
	$error = $wp_error->get_error_message('wp_mail_failed');
	set_transient( 'wphc_wp_mail_failed_reason', $error, 60 );
}
