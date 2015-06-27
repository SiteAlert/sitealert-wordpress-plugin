<?php
/**
* Plugin Name: My WordPress Health Check
* Description: This plugin checks the health of your WordPress installation.
* Version: 1.0.0
* Author: Frank Corso
* Author URI: http://www.mylocalwebstop.com/
* Plugin URI: http://www.mylocalwebstop.com/
* Text Domain: my-wp-health-check
* Domain Path: /languages
*
* @author Frank Corso
* @version 1.0.0
*/

/**
 * Main class of plugin
 *
 * @since 0.1.0
 */
class MLWWpHealthCheck
{
  /**
   * Main construct
   *
   * @since 0.1.0
   */
  function __construct()
  {
    $this->load_dependencies();
    $this->load_hooks();
  }

  /**
   * Load File Dependencies
   *
   * @since 0.1.0
   */
  private function load_dependencies()
  {
    include("php/wp_hc_admin.php");
  }

  /**
   * Adds Plugin's Functions To WordPress Hooks
   */
  private function load_hooks()
  {
    add_action('plugins_loaded',  array( $this, 'setup_translations'));
  }

  /**
	  * Loads the plugin language files
	  *
	  * @since 0.1.0
	  */
	public function setup_translations()
	{
		load_plugin_textdomain( 'my-wp-health-check', false, dirname( plugin_basename( __FILE__ ) ) . "/languages/" );
	}
}
$mlwWPHealthCheck = new MLWWpHealthCheck();
?>
