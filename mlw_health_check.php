<?php
/**
* Plugin Name: My WordPress Health Check
* Description: This plugin checks the health of your WordPress installation.
* Version: 1.1.2
* Author: Frank Corso
* Author URI: http://www.mylocalwebstop.com/
* Plugin URI: http://www.mylocalwebstop.com/
* Text Domain: my-wp-health-check
* Domain Path: /languages
*
* @author Frank Corso
* @version 1.1.2
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main class of plugin
 *
 * @since 0.1.0
 */
class My_WP_Health_Check {
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
    if ( is_admin() ) {
      include( "php/class-wphc-admin.php" );
      include( "php/class-wphc-review-manager.php" );
    }
  }

  /**
   * Adds Plugin's Functions To WordPress Hooks
   */
  private function load_hooks() {
    add_action( 'plugins_loaded',  array( $this, 'setup_translations') );
  }

  /**
	  * Loads the plugin language files
	  *
	  * @since 0.1.0
	  */
	public function setup_translations() {
		load_plugin_textdomain( 'my-wp-health-check', false, dirname( plugin_basename( __FILE__ ) ) . "/languages/" );
	}
}
$my_wp_health_check = new My_WP_Health_Check();
?>
