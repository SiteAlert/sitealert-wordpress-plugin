<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class To Display Admin Page
 *
 * @since 0.1.0
 */
class WPHC_Admin {
  /**
   * Main construct
   *
   * @since 0.1.0
   */
  function __construct() {
    $this->load_dependencies();
    $this->load_hooks();
  }

  /**
   * Load File Dependencies
   *
   * @since 0.1.0
   */
  private function load_dependencies() {
    ///None yet
  }

  /**
   * Adds Plugin's Functions To WordPress Hooks
   */
  private function load_hooks() {
    add_action( 'admin_menu', array( $this, 'setup_admin_page' ) );
  }

  /**
   * Creates the menu for the plugin
   *
   * @since 0.1.0
   */
  public function setup_admin_page() {
    add_management_page( 'WordPress Health Check', __( 'Health Check', 'my-wp-health-check' ), 'moderate_comments', 'wp-health-check', array( $this, 'settings_page' ) );
  }

  /**
   * Function to display the admin page
   *
   * @since 0.1.0
   */
  public function settings_page() {
    if ( ! current_user_can('moderate_comments') ) {
  		return;
  	}
    wp_enqueue_style( 'wp-hc-style', plugins_url( "../css/main.css", __FILE__ ) );
    wp_enqueue_script( 'wphc-admin-script', plugins_url( "../js/wphc-admin.js", __FILE__ ) );
    ?>
    <div class="wrap">
      <h2>WordPress Health Check</h2>
      <hr />
      <div class=" wphc-wrap">
        <div class="wphc-main-content">
         <h3>Server Check</h3>
         <div class="server-checks">
           <?php do_action( 'wphc_server_check' ); ?>
         </div>
         <h3>WordPress Check</h3>
         <div class="WordPress-checks">
           <?php do_action( 'wphc_wordpress_check' ); ?>
         </div>
         <h3>Plugin Check</h3>
         <div class="plugin-checks">
           <?php do_action( 'wphc_plugin_check' ); ?>
         </div>
       </div>
     </div>
    </div>
    <?php
  }
}

$wp_hc_admin = new WPHC_Admin();
?>
