<?php
/**
* Plugin Name: My WordPress Health Check
* Description: This plugin checks the health of your WordPress installation.
* Version: 0.2.1
* Author: Frank Corso
* Author URI: http://www.mylocalwebstop.com/
* Plugin URI: http://www.mylocalwebstop.com/
* Text Domain: my-wp-health-check
* Domain Path: /languages
*
* Disclaimer of Warranties
* The plugin is provided "as is". My Local Webstop and its suppliers and licensors hereby disclaim all warranties of any kind,
* express or implied, including, without limitation, the warranties of merchantability, fitness for a particular purpose and non-infringement.
* Neither My Local Webstop nor its suppliers and licensors, makes any warranty that the plugin will be error free or that access thereto will be continuous or uninterrupted.
* You understand that you install, operate, and unistall the plugin at your own discretion and risk.
*
* @author Frank Corso
* @version 0.2.1
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
    add_action('admin_menu', array($this, 'setup_admin_page'));
    add_action('plugins_loaded',  array( $this, 'setup_translations'));
  }

  /**
   * Creates the menu for the plugin
   *
   * @since 0.1.0
   */
  public function setup_admin_page()
  {
    add_management_page('WordPress Health Check', __('Health Check', 'my-wp-health-check'), 'moderate_comments', 'wp-health-check', array('MLWWpHcAdmin', 'settings_page'));
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
