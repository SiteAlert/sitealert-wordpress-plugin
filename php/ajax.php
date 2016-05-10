<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_wphc_load_server_checks', 'wphc_server_checks_ajax' );
add_action( 'wp_ajax_nopriv_wphc_load_server_checks', 'wphc_server_checks_ajax' );

function wphc_server_checks_ajax() {
  $check = new WPHC_Checks();
  $checks = $check->server_checks();
  echo json_encode( $checks );
  wp_die();
}

add_action( 'wp_ajax_wphc_load_WordPress_checks', 'wphc_wp_checks_ajax' );
add_action( 'wp_ajax_nopriv_wphc_load_WordPress_checks', 'wphc_wp_checks_ajax' );

function wphc_wp_checks_ajax() {
  $check = new WPHC_Checks();
  $checks = $check->wp_checks();
  echo json_encode( $checks );
  wp_die();
}

add_action( 'wp_ajax_wphc_load_plugin_checks', 'wphc_plugin_checks_ajax' );
add_action( 'wp_ajax_nopriv_wphc_load_plugin_checks', 'wphc_plugin_checks_ajax' );

function wphc_plugin_checks_ajax() {
  $check = new WPHC_Checks();
  $checks = $check->plugins_checks();
  echo json_encode( $checks );
  wp_die();
}
?>
