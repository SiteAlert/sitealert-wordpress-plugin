<?php
/**
* Plugin Name: My WordPress Health Check
* Description: This plugin checks the health of your WordPress installation.
* Version: 1.4.2
* Author: Frank Corso
* Author URI: https://www.frankcorso.me/
* Plugin URI: https://www.frankcorso.me/
* Text Domain: my-wp-health-check
* Domain Path: /languages
*
* @author Frank Corso
* @version 1.4.2
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
   * The version of the plugin
   */
  public $version = '1.4.2';

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
    include( 'php/class-wphc-install.php' );
    include( "php/class-wphc-checks.php" );
    include( "php/class-wphc-admin.php" );
    include( "php/class-wphc-review-manager.php" );
    include( "php/class-wphc-tracking.php" );
    include( "php/functions.php" );
    include( "php/ajax.php" );
  }

  /**
   * Adds Plugin's Functions To WordPress Hooks
   *
   * @since 0.1.0
   */
  private function load_hooks() {
    add_action( 'plugins_loaded',  array( $this, 'setup_translations') );
    add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 65 ); // Between Updates, Comments and New Content menu
  }

  /**
	  * Loads the plugin language files
	  *
	  * @since 0.1.0
	  */
	public function setup_translations() {
		load_plugin_textdomain( 'my-wp-health-check', false, dirname( plugin_basename( __FILE__ ) ) . "/languages/" );
	}

  public function admin_bar( $wp_admin_bar ) {
    $total = wphc_get_total_checks();
    if( ! empty( $total ) and $total > 0) {
			$args = array(
				'id' => 'wphc_admin_node',
				'title' => '<span class="ab-icon dashicons dashicons-shield"></span>' . $total,
				'href' => admin_url( 'tools.php?page=wp-health-check' )
			);
			$wp_admin_bar->add_node( $args );
		}
  }
}
$my_wp_health_check = new My_WP_Health_Check();
?>
