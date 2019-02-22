<?php
/**
 * Sends data to tracking server, if opted in
 */

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class To Send Tracking Information
 *
 * @since 1.2.1
 */
class WPHC_Tracking {

	/**
	 * Date To Send Home
	 *
	 * @var array
	 * @since 1.2.1
	 */
	private $data;

	/**
	 * Main Construct Function
	 *
	 * Call functions within class
	 *
	 * @since 1.2.1
	 * @uses WPHC_Tracking::add_hooks() Adds actions to hooks and filters
	 * @return void
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Add Hooks
	 *
	 * Adds functions to relavent hooks and filters
	 *
	 * @since 1.2.1
	 * @return void
	 */
	private function add_hooks() {
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'admin_init', array( $this, 'admin_notice_check' ) );
		add_action( 'shutdown', array( $this, 'track_check' ) );
	}

	/**
	 * Determines If Ready To Send Data Home
	 *
	 * Determines if the plugin has been authorized to send the data home in the settings page. Then checks if it has been at least a week since the last send.
	 *
	 * @since 1.2.1
	 * @uses WPHC_Tracking::load_data()
	 * @uses WPHC_Tracking::send_data()
	 * @uses WPHC_Tracking::is_time_to_send()
	 * @return void
	 */
	public function track_check() {
		$settings = (array) get_option( 'wphc-settings' );
		$tracking_allowed = '0';
		if ( isset( $settings['tracking_allowed'] ) ) {
			$tracking_allowed = $settings['tracking_allowed'];
		}
		$last_time = get_option( 'wphc_tracker_last_time' );
		if ( $this->is_time_to_send( $tracking_allowed, $last_time ) ) {
			$this->load_data( $tracking_allowed );
			$this->send_data();
			update_option( 'wphc_tracker_last_time', time() );
		}
	}

	/**
	 * Sends The Data Home
	 *
	 * @since 1.2.1
	 * @return void
	 */
	private function send_data() {
		$response = wp_remote_post( 'https://wphc.frankcorso.me/?usage_track=confirmation', array(
			'method'      => 'POST',
			'timeout'     => 10,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'body'        => $this->data,
			'user-agent'  => 'WPHC Usage Tracker',
		));
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "Something went wrong with WPHC Usage Tracker: $error_message";
		}
	}

	/**
	 * Prepares The Data To Be Sent
	 *
	 * @since 1.2.1
	 * @param string $tracking The value of the tracking setting.
	 * @return void
	 */
	private function load_data( $tracking ) {

		global $wpdb;

		// Sets basic set up info.
		$data                  = array();
		$data['plugin']        = 'WPHC';
		$data['url']           = home_url();
		$data['wp_version']    = get_bloginfo( 'version' );
		$data['php_version']   = PHP_VERSION;
		$data['mysql_version'] = $wpdb->db_version();
		$data['server_app']    = $_SERVER['SERVER_SOFTWARE'];

		// Retrieves current plugin information.
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}
		$plugins        = array_keys( get_plugins() );
		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $plugins as $key => $plugin ) {
			if ( in_array( $plugin, $active_plugins ) ) {
				// Removes active plugins from list so we can show active and inactive separately.
				unset( $plugins[ $key ] );
			}
		}
		$data['active_plugins']   = $active_plugins;
		$data['inactive_plugins'] = $plugins;

		// Retrieves current theme information.
		$theme_data            = wp_get_theme();
		$data['theme']         = $theme_data->Name;
		$data['theme_version'] = $theme_data->Version;

		// Retrieves site information.
		$data['site_title']   = get_bloginfo( 'name' );
		$data['site_desc']    = get_bloginfo( 'description' );
		$data['site_charset'] = get_bloginfo( 'charset' );
		$data['lang']         = get_bloginfo( 'language' );

		// Retrieves WP Health specific data.
		$data['original_version']    = get_option( 'wphc_original_version' );
		$data['current_version']     = get_option( 'wphc_current_version' );
		$data['failed_tests']        = wphc_get_total_checks();
		$data['plugin_updates']      = get_plugin_updates();
		$data['theme_updates']       = get_theme_updates();
		$data['unsupported_plugins'] = get_transient( 'wphc_supported_plugin_check' );
		$data['admin_check']         = get_user_by( 'login', 'admin' ) ? 'yes' : 'no';

		$this->data = $data;
	}

	/**
	 * Adds Admin Notice To Dashboard
	 *
	 * Adds an admin notice asking for authorization to send data home
	 *
	 * @since 1.2.1
	 * @return void
	 */
	public function admin_notice() {
		$show_notice = get_option( 'wphc-tracking-notice' );
		$settings    = (array) get_option( 'wphc-settings' );

		if ( $show_notice ) {
			return;
		}

		if ( isset( $settings['tracking_allowed'] ) && '1' == $settings['tracking_allowed'] ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( false !== stristr( network_site_url( '/' ), 'dev' ) || false !== stristr( network_site_url( '/' ), 'localhost' ) || false !== stristr( network_site_url( '/' ), ':8888' ) ) {
			update_option( 'wphc-tracking-notice', '1' );
		} else {
			$optin_url  = esc_url( add_query_arg( 'wphc_track_check', 'opt_into_tracking' ) );
			$optout_url = esc_url( add_query_arg( 'wphc_track_check', 'opt_out_of_tracking' ) );
			?>
			<div class="updated">
				<p><?php esc_html_e( "Allow WP Health to track this plugin's usage and help us make this plugin better? No user data is sent to our servers. No sensitive data is tracked.", 'my-wp-health-check' ); ?></p>
				<p><a href="http://bit.ly/2MpT2Rd" target="_blank"><?php esc_html_e( 'Click here to learn more', 'my-wp-health-check' ); ?></a></p>
				<p>
					<a href="<?php echo esc_url( $optin_url ); ?>" class="button-secondary"><?php esc_html_e( 'Allow', 'my-wp-health-check' ); ?></a>
					<a href="<?php echo esc_url( $optout_url ); ?>" class="button-secondary"><?php esc_html_e( 'Do not allow', 'my-wp-health-check' ); ?></a>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Checks If User Has Clicked On Notice
	 *
	 * @since 1.2.1
	 * @return void
	 */
	public function admin_notice_check() {
		if ( isset( $_GET['wphc_track_check'] ) ) {
			if ( 'opt_into_tracking' == $_GET['wphc_track_check'] ) {
				$settings = (array) get_option( 'wphc-settings' );
				$settings['tracking_allowed'] = '2';
				update_option( 'wphc-settings', $settings );
			} else {
				$settings = (array) get_option( 'wphc-settings' );
				$settings['tracking_allowed'] = '0';
				update_option( 'wphc-settings', $settings );
			}
			update_option( 'wphc-tracking-notice', '1' );
		}
	}

	/**
	 * Determines if it is time to send data
	 *
	 * @since 1.6.4
	 * @param string $allowed 1 or 2 if allowed.
	 * @param string $last_time The last time the data was sent.
	 * @return bool True if it is time
	 */
	private function is_time_to_send( $allowed, $last_time ) {
		return ( '1' == $allowed || '2' == $allowed ) && ( ( $last_time && $last_time < strtotime( '-1 week' ) ) || ! $last_time );
	}
}
$wphc_tracking = new WPHC_Tracking();

?>
