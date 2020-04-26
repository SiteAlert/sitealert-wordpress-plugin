<?php
/**
 * Plugin Name: WP Health (Formerly My WP Health Check)
 * Description: Keep your site healthy, secure, and performing well!
 * Version: 1.8.11
 * Author: Frank Corso
 * Author URI: https://wphealth.app/
 * Plugin URI: https://wphealth.app/
 * Text Domain: my-wp-health-check
 *
 * @author Frank Corso
 * @version 1.8.11
 * @package WPHC
 */

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Defines plugin constants.
define( 'WPHC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main class of plugin
 *
 * @since 0.1.0
 */
class My_WP_Health_Check {

	/**
	 * The version of the plugin
	 *
	 * @var string
	 * @since 1.6.0
	 */
	public $version = '1.8.11';

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
		if ( is_admin() ) {
			include 'php/admin/ads.php';
			include 'php/admin/checks-page.php';
			include 'php/class-wphc-review-manager.php';
			include 'php/class-wphc-tracking.php';
		}
		include 'php/class-wphc-install.php';
		include 'php/class-wphc-checks.php';
		include 'php/functions.php';
		include 'php/ajax.php';
		include 'php/rest-api.php';
	}

	/**
	 * Adds Plugin's Functions To WordPress Hooks
	 *
	 * @since 0.1.0
	 */
	private function load_hooks() {
		add_action( 'admin_menu', array( $this, 'setup_admin_menu' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 65 );
		add_action( 'after_plugin_row', array( $this, 'plugin_row_notice' ), 10, 3 );
	}

	/**
	 * Sets up the admin pages
	 *
	 * @since 1.6.0
	 */
	public function setup_admin_menu() {
		add_management_page( 'WP Health', 'WP Health', 'manage_options', 'wp-health-check', 'wphc_generate_checks_page' );
	}

	/**
	 * Adds an icon and number of issues to the admin bar, if issues exist
	 *
	 * @param object $wp_admin_bar WP Admin Bar instance.
	 */
	public function admin_bar( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$total = wphc_get_total_checks();
		if ( $total > 0 ) {
			$args = array(
				'id'    => 'wphc_admin_node',
				'title' => '<span class="ab-icon dashicons dashicons-heart"></span>' . $total,
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
		$plugin_list = get_transient( 'wphc_supported_plugin_check' );
		if ( $plugin_list && ! empty( $plugin_list ) ) {
			$plugins = explode( ', ', $plugin_list );
			$name    = $plugin_data['Name'];
			if ( in_array( $name, $plugins ) ) {
				$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
				?>
				<tr style="background-color: lightyellow;">
					<td colspan="<?php echo esc_attr( $wp_list_table->get_column_count() ); ?>">
						<div>
							<?php
							/* translators: %s is the name of the plugin. */
							echo sprintf( esc_html__( '%s has not been updated in over two years which indicates that it is no longer supported by the developer.
							There could be security issues that will not be fixed! Please reach out to the developers to ensure this is still supported or look for alternatives and uninstall this plugin.', 'my-wp-health-check' ), esc_html( $name ) );
							?>
						</div>
					</td>
				</tr>
				<?php
			}
		}
	}
}
global $my_wp_health_check;
$my_wp_health_check = new My_WP_Health_Check();
?>
