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
 * Gets the results of all checks
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
 * Gets the results from a type of check
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
