<?php
/**
* Plugin Name: My WordPress Health Check
* Description: This plugin checks the health of your WordPress installation.
* Version: 1.4.4
* Author: Frank Corso
* Author URI: https://frankcorso.me/
* Plugin URI: https://frankcorso.me/
* Text Domain: my-wp-health-check
*
* @author Frank Corso
* @version 1.4.4
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
	public $version = '1.4.4';

	/**
	 * Main construct
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->load_hooks();
	}

	/**
	 * Load File Dependencies
	 *
	 * @since 0.1.0
	 */
	private function load_dependencies() {
		include 'php/class-wphc-install.php';
		include 'php/class-wphc-checks.php';
		include 'php/class-wphc-admin.php';
		include 'php/class-wphc-review-manager.php';
		include 'php/class-wphc-tracking.php';
		include 'php/functions.php';
		include 'php/ajax.php';
	}

	/**
	 * Adds Plugin's Functions To WordPress Hooks
	 *
	 * @since 0.1.0
	 */
	private function load_hooks() {
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 65 ); // Between Updates, Comments and New Content menu
		add_action( 'after_plugin_row', array( $this, 'plugin_row_notice' ), 10, 3 );
	}

	/**
	 * Adds an icon and number of issues to the admin bar, if issues exist
	 *
	 * @param object $wp_admin_bar WP Admin Bar instance.
	 */
	public function admin_bar( $wp_admin_bar ) {
		$total = wphc_get_total_checks();
		if ( ! empty( $total ) && $total > 0 ) {
			$args = array(
				'id'    => 'wphc_admin_node',
				'title' => '<span class="ab-icon dashicons dashicons-shield"></span>' . $total,
				'href'  => admin_url( 'tools.php?page=wp-health-check' ),
			);
			$wp_admin_bar->add_node( $args );
		}
	}

	/**
	 * Adds notices to plugins with issues
	 *
	 * @since 1.5.0
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 * @param string $status Status of the plugin.
	 */
	public function plugin_row_notice( $plugin_file, $plugin_data, $status ) {
		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
		$name = $plugin_data['Name'];
		?>
		<tr style="background-color: lightyellow;">
			<td colspan="<?php echo esc_attr( $wp_list_table->get_column_count() ); ?>"><?php echo esc_html( $name ); ?> This is a test!</td>
		</tr>
		<?php
	}
}
$my_wp_health_check = new My_WP_Health_Check();
?>
