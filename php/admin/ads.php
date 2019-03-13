<?php
/**
 * Generates the ads in the plugin
 *
 * @package WPHC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints the ads in the plugin.
 *
 * @since 1.7.7
 * @param array $settings The settings for the plugin.
 */
function wphc_generate_ads( $settings = false ) {
	if ( false === $settings || ! is_array( $settings ) ) {
		$settings = (array) get_option( 'wphc-settings' );
	}
	if ( ! isset( $settings['api_key'] ) || empty( $settings['api_key'] ) ) {
		$ad_number = rand( 0, 4 );
		switch ( $ad_number ) {
			case 0:
				// Ad 1.
				$ad = array(
					'msg' => __( 'Monitor your WordPress sites to ensure they stay up, healthy, and secure. Check out our premium plans that include uptime monitoring and a central dashboard!', 'my-wp-health-check' ),
					'url' => 'http://bit.ly/2oqLaoR',
				);
				break;

			case 1:
				// Ad 2.
				$ad = array(
					'msg' => __( 'Do not lose time and money! Be notified as soon as your site goes down with uptime monitoring. Check out our premium plan for more details!', 'my-wp-health-check' ),
					'url' => 'http://bit.ly/2Cwjskt',
				);
				break;

			case 2:
				// Ad 3.
				$ad = array(
					'msg' => __( 'Receive these checks in an email by upgrading to our premium version.', 'my-wp-health-check' ),
					'url' => 'http://bit.ly/2IVBLzh',
				);
				break;

			case 3:
				// Ad 4.
				$ad = array(
					'msg' => __( 'Do not leave your visitors confused! Be notified of any broken images and links on your site by upgrading to our premium version.', 'my-wp-health-check' ),
					'url' => 'http://bit.ly/2RdvAto',
				);
				break;

			case 4:
				// Ad 5.
				$ad = array(
					'msg' => __( 'Get Slack messages with alerts when something is wrong with your site by upgrading to our premium version.', 'my-wp-health-check' ),
					'url' => 'http://bit.ly/2EQKLno',
				);
				break;

			default:
				$ad = array(
					'msg' => __( 'Monitor your WordPress sites to ensure they stay up, healthy, and secure. Check out our premium plans that include uptime monitoring and a central dashboard!', 'my-wp-health-check' ),
					'url' => 'http://bit.ly/2oqLaoR',
				);
				break;
		}
		?>
		<div class="wphc-ad">
			<?php echo esc_html( $ad['msg'] ); ?> <a href="<?php echo esc_attr( $ad['url'] ); ?>"><?php esc_html_e( 'Learn more!', 'my-wp-health-check' ); ?></a>
		</div>
		<?php
	}
}


?>
