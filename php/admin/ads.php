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
 */
function wphc_generate_ads() {
	if ( ! isset( $settings['api_key'] ) || empty( $settings['api_key'] ) ) {
		$ad_message = 'Monitor your WordPress sites to ensure they stay up, healthy, and secure. Check out our premium plans that include uptime monitoring and a central dashboard! <a target="_blank" href="http://bit.ly/2oqLaoR">Learn more!</a>';
		$ad_number  = rand( 0, 4 );
		switch ( $ad_number ) {
			case 0:
				// Ad 1.
				$ad_message = 'Monitor your WordPress sites to ensure they stay up, healthy, and secure. Check out our premium plans that include uptime monitoring and a central dashboard! <a target="_blank" href="http://bit.ly/2oqLaoR">Learn more!</a>';
				break;

			case 1:
				// Ad 2.
				$ad_message = 'Do not lose time and money! Be notified as soon as your site goes down with uptime monitoring. Check out our premium plan for more details! <a target="_blank" href="http://bit.ly/2Cwjskt">Learn more!</a>';
				break;

			case 2:
				// Ad 3.
				$ad_message = 'Receive these checks in an email by upgrading to our premium version. <a target="_blank" href="http://bit.ly/2IVBLzh">Learn more!</a>';
				break;

			case 3:
				// Ad 4.
				$ad_message = 'Do not leave your visitors confused! Be notified of any broken images and links on your site by upgrading to our premium version. <a target="_blank" href="http://bit.ly/2RdvAto">Learn more!</a>';
				break;

			case 4:
				// Ad 5.
				$ad_message = 'Get Slack messages with alerts when something is wrong with your site by upgrading to our premium version. <a target="_blank" href="http://bit.ly/2EQKLno">Learn more!</a>';
				break;

			default:
				$ad_message = 'Monitor your WordPress sites to ensure they stay up, healthy, and secure. Check out our premium plans that include uptime monitoring and a central dashboard! <a target="_blank" href="http://bit.ly/2oqLaoR">Learn more!</a>';
				break;
		}
		?>
		<div class="wphc-ad">
			<?php echo $ad_message; ?>
		</div>
		<?php
	}
}


?>