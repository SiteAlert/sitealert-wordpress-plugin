<?php
/**
 * This file creates the settings page
 *
 * @package WPHC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class handles the generation of settings fields on the settings page.
 *
 * @since 1.6.0
 */
class WPHC_Settings_Page {

	/**
	 * Registers setup hook
	 *
	 * @since 1.6.0
	 */
	public static function init() {
		add_action( 'admin_init', array( 'WPHC_Settings_Page', 'setup' ) );
	}

	/**
	 * Registers settings, sections, and fields
	 *
	 * @since 1.6.0
	 */
	public static function setup() {
		register_setting( 'wphc-settings-group', 'wphc-settings' );
		add_settings_section( 'wphc-global-section', __( 'Main Settings', 'my-wp-health-check' ), array( 'WPHC_Settings_Page', 'display_main_section' ), 'wphc_global_settings' );
		add_settings_field( 'usage-tracker', __( 'Allow Usage Tracking?', 'my-wp-health-check' ), array( 'WPHC_Settings_Page', 'usage_tracker_field' ), 'wphc_global_settings', 'wphc-global-section' );
		add_settings_field( 'api-key-field', __( 'API Key', 'my-wp-health-check' ) . '<p>By default the REST API is disabled for this plugin. Enter in an API Key to enable it so you can build custom scripts around the REST API.</p>', array( 'WPHC_Settings_Page', 'api_key_field' ), 'wphc_global_settings', 'wphc-global-section' );
	}

	/**
	 * Generates the main section info
	 *
	 * @since 1.6.0
	 */
	public static function display_main_section() {
		echo '<p></p>';
	}

	/**
	 * Generates Setting Field For Usage Tracker Authorization
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function usage_tracker_field() {
		$settings = (array) get_option( 'wphc-settings' );
		$tracking_allowed = '0';
		if ( isset( $settings['tracking_allowed'] ) ) {
			$tracking_allowed = esc_attr( $settings['tracking_allowed'] );
		}
		$checked = '';
		if ( '2' == $tracking_allowed ) {
			$checked = " checked='checked'";
		}
		echo "<input type='checkbox' name='wphc-settings[tracking_allowed]' id='wphc-settings[tracking_allowed]' value='2'$checked />";
		echo "<label for='wphc-settings[tracking_allowed]'>" . esc_html__( "Allows My WordPress Health Check to anonymously track this plugin's usage and help us make this plugin better.", 'my-wp-health-check' ) . '</label>';
	}

	/**
	 * Generates the api key field
	 *
	 * @since 1.6.0
	 */
	public static function api_key_field() {
		$settings = (array) get_option( 'wphc-settings', '' );
		$api_key  = '';
		if ( isset( $settings['api_key'] ) ) {
			$api_key = $settings['api_key'];
		}
		?>
		<input type='text' name='wphc-settings[api_key]' id='wphc-settings[api_key]' value='<?php echo esc_attr( $api_key ); ?>' />
		<?php
	}

	/**
	 * Generates the settings page
	 *
	 * @since 1.6.0
	 */
	public static function display_page() {
		?>
		<div class="wrap">
			<form action="options.php" method="POST">
				<?php settings_fields( 'wphc-settings-group' ); ?>
				<?php do_settings_sections( 'wphc_global_settings' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}