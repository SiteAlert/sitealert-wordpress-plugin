<?php
/**
 * Handles all AJAX endpoints
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_wphc_subscribe', 'wphc_subscribe_ajax' );
add_action( 'wp_ajax_nopriv_wphc_subscribe', 'wphc_subscribe_ajax' );

/**
 * Subscribes the user to our mailing list.
 *
 * @since 1.6.10
 */
function wphc_subscribe_ajax() {
	$name  = sanitize_text_field( $_POST['name'] );
	$email = sanitize_email( $_POST['email'] );
	$json  = array(
		'success' => true,
	);

	if ( is_email( $email ) ) {
		$response = wp_remote_post( 'https://sitealert.io/quiz/maintenance-course/', array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
			'body'        => array(
				'total_questions'   => 0,
				'complete_quiz'     => 'confirmation',
				'qmn_quiz_id'       => 5,
				'timer'             => 0,
				'qsm_drip_checkbox' => 1,
				'contact_field_0'   => $name,
				'contact_field_1'   => $email,
			),
		));
	} else {
		$json['success'] = false;
	}
	echo json_encode( $json );
	wp_die();
}

add_action( 'wp_ajax_wphc_save_plugin_settings', 'wphc_save_settings' );
add_action( 'wp_ajax_nopriv_wphc_save_plugin_settings', 'wphc_save_settings' );

/**
 * Saves the settings from the "Settings" tab of the plugin
 */
function wphc_save_settings() {
	$tracking = sanitize_text_field( $_POST['tracking_allowed'] );
	$api_key  = sanitize_text_field( $_POST['api_key'] );
	$settings = array(
		'tracking_allowed' => $tracking,
		'api_key'          => $api_key,
	);
	update_option( 'wphc-settings', $settings );
	$json = array(
		'success' => true,
		'msg'     => 'Settings have been saved!',
	);
	if ( 'yvqks' === substr( $api_key, 0, 5 ) ) {
		$body_json = array(
			'site_name' => get_bloginfo( 'name' ),
			'url'       => site_url(),
			'api_key'   => substr( $api_key, 5 ),
		);
		$response = wp_remote_post( 'https://api.sitealert.io/api/v1/sites', array(
			'timeout'     => 45,
			'body'        => json_encode( $body_json ),
			'user-agent'  => 'Site Alert Plugin',
			'headers'     => array(
				'Content-type' => 'application/json',
			),
		));
		if ( is_wp_error( $response ) ) {
			$json['success'] = false;
			$error_message   = $response->get_error_message();
			$json['msg']     = "Something went wrong when sending data to SiteAlert API: $error_message";
		} else {
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			if ( ! isset( $body->success ) || false === $body->success ) {
				$json['success'] = false;
				$json['msg']     = "Something went wrong when sending data to SiteAlert API: {$body->msg}";
			} else {
				update_option('wphc-premium', '1' );
				$json['msg'] = esc_html( $body->msg );
			}
		}
	} else {
		update_option('wphc-premium', '0' );
	}
	echo json_encode( $json );
	wp_die();
}

add_action( 'wp_ajax_wphc_load_server_checks', 'wphc_server_checks_ajax' );
add_action( 'wp_ajax_nopriv_wphc_load_server_checks', 'wphc_server_checks_ajax' );

/**
 * Handles the AJAX call for the server checks
 *
 * @since 1.3.0
 */
function wphc_server_checks_ajax() {
	$check  = new WPHC_Checks();
	$checks = $check->server_checks();
	echo json_encode( $checks );
	wp_die();
}

add_action( 'wp_ajax_wphc_load_WordPress_checks', 'wphc_wp_checks_ajax' );
add_action( 'wp_ajax_nopriv_wphc_load_WordPress_checks', 'wphc_wp_checks_ajax' );

/**
 * Handles the AJAX call for the WP checks
 *
 * @since 1.3.0
 */
function wphc_wp_checks_ajax() {
	$check  = new WPHC_Checks();
	$checks = $check->wp_checks();
	echo json_encode( $checks );
	wp_die();
}

add_action( 'wp_ajax_wphc_load_plugin_checks', 'wphc_plugin_checks_ajax' );
add_action( 'wp_ajax_nopriv_wphc_load_plugin_checks', 'wphc_plugin_checks_ajax' );

/**
 * Handles the AJAX call for the plugin checks
 *
 * @since 1.3.0
 */
function wphc_plugin_checks_ajax() {
	$check  = new WPHC_Checks();
	$checks = $check->plugins_checks();
	echo json_encode( $checks );
	wp_die();
}
