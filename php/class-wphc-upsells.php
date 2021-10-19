<?php
/**
 * Handles our upsells
 *
 * @package WPHC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates and enqueues any current upsells.
 *
 * @since 1.8.14
 */
class WPHC_Upsells {

	/**
	 * Initializes any current upsells
     *
     * @since 1.8.14
	 */
    public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'check_upsell_notice_button_clicks' ) );
        //add_action( 'admin_notices', array( __CLASS__, 'three_week_upsell_notice' ) );
    }

	/**
	 * Adds upsell notice to plugins page after the plugin has been installed for three weeks
     *
     * @since 1.8.14
	 */
    public static function three_week_upsell_notice() {
        if ( self::should_show_three_week_upsell() ) {
			$screen       = get_current_screen();
			$screenparent = $screen->parent_file;
			$screen_id    = $screen->id;
			if ( $screenparent == 'plugins.php' && $screen_id == 'plugins' && current_user_can( 'install_plugins' ) ) {
				$upsell_url = 'https://sitealert.io/?utm_source=upsell-three-weeks-notice&utm_medium=plugin&utm_campaign=health-plugin';
				$nope_url   = esc_url( add_query_arg( 'wphc_three_week_upsell_notice_check', 'remove_message' ) );;
				?>
                <style>
                    .wphc-upsell-message ul {
                        list-style: none;
                        margin-left: 2em;
                    }
                    .wphc-upsell-message ul li:before {
                        content: "\f12a";
                        display: inline-block;
                        -webkit-font-smoothing: antialiased;
                        font: normal 16px/1 'dashicons';
                        color: #173f9c;
                        margin-right: 10px;
                        word-wrap: break-word;
                    }
                    .wphc-upsell-message strong {
                        display: block;
                    }
                    .wphc-upsell-message .wphc-upsell-message-action-links {
                        margin-top: 15px;
                    }
                </style>
                <div class="updated wphc-upsell-message">
                    <p><?php esc_html_e( 'Hey there! Sorry to bother you. I noticed that you have been using SiteAlert for three weeks now. SiteAlert Premium has 
                              even more enhanced monitoring for your site including:', 'my-wp-health-check' ); ?></p>
                    <ul>
                        <li><?php esc_html_e( 'Broken Image Monitor', 'my-wp-health-check' ); ?></li>
                        <li><?php esc_html_e( 'Broken Link Monitor', 'my-wp-health-check' ); ?></li>
                        <li><?php esc_html_e( 'Pagespeed Monitor', 'my-wp-health-check' ); ?></li>
                        <li><?php esc_html_e( 'Blacklist Monitor', 'my-wp-health-check' ); ?></li>
                        <li><?php esc_html_e( 'Uptime Monitor', 'my-wp-health-check' ); ?></li>
                        <li><?php esc_html_e( 'Accessibility Monitor', 'my-wp-health-check' ); ?></li>
                    </ul>
                    <p><?php esc_html_e( 'Since you have already been using the free version, you can use coupon code THREEWEEKS to get 50% off your first three months!', 'my-wp-health-check' ); ?></p>
                    <p><?php esc_html_e( 'We truly believe SiteAlert makes a difference for people maintaining their WordPress sites. We hope you utilize this coupon code. Feel free to reach out if we can ever assist with anything.', 'my-wp-health-check' ); ?><strong><em>~ The SiteAlert Team</em></strong></p>
                    <p class="wphc-upsell-message-action-links">
                        <a target="_blank" href="<?php echo esc_url( $upsell_url ); ?>" class="button-primary"><?php esc_html_e( 'Yeah, I want to upgrade!', 'my-wp-health-check' ); ?></a>
                        <a href="<?php echo esc_url( $nope_url ); ?>" class="button-secondary"><?php esc_html_e( 'Dismiss this alert', 'my-wp-health-check' ); ?></a>
                    </p>
                </div>
				<?php
			}
        }
    }

	/**
	 * Checks if we should dimiss the upsell notice.
     *
     * @since 1.8.14
	 */
    public static function check_upsell_notice_button_clicks() {
		if ( isset( $_GET['wphc_three_week_upsell_notice_check'] ) && 'remove_message' == $_GET['wphc_three_week_upsell_notice_check'] ) {
			update_option( 'wphc_dismissed_three_week_upsell', true );
		}
	}

	/**
	 * Prints the ads in the plugin if no REST API Key is entered.w
	 *
	 * @since 1.8.14
	 */
    public static function print_upsell_ad() {
        if ( ! self::is_maybe_premium_user() ) {
			$ad = self::retrieve_upsell_ad();
			?>
            <div class="wphc-ad">
				<?php echo esc_html( $ad['msg'] ); ?> <a href="<?php echo esc_attr( $ad['url'] ); ?>"><?php esc_html_e( 'Learn more!', 'my-wp-health-check' ); ?></a>
            </div>
			<?php
        }
    }

	/**
	 * Returns a random upsell ad
	 *
	 * @since 1.8.14
     * @return array The ad
	 */
    public static function retrieve_upsell_ad() {
        $ad_number = rand( 0, 4 );
        switch ( $ad_number ) {
            case 0:
                // Ad 1.
                $ad = array(
                    'msg' => __( 'Monitor your WordPress sites to ensure they stay up, healthy, and secure. Check out our premium plans that include uptime monitoring and a central dashboard!', 'my-wp-health-check' ),
                    'url' => 'https://sitealert.io/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=wphc-ad&utm_content=ad-1',
                );
                break;

            case 1:
                // Ad 2.
                $ad = array(
                    'msg' => __( 'Do not lose time and money! Be notified as soon as your site goes down with uptime monitoring. Check out our premium plan for more details!', 'my-wp-health-check' ),
                    'url' => 'https://sitealert.io/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=wphc-ad&utm_content=ad-2',
                );
                break;

            case 2:
                // Ad 3.
                $ad = array(
                    'msg' => __( 'Receive these checks in an email by upgrading to our premium version.', 'my-wp-health-check' ),
                    'url' => 'https://sitealert.io/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=wphc-ad&utm_content=ad-3',
                );
                break;

            case 3:
                // Ad 4.
                $ad = array(
                    'msg' => __( 'Do not leave your visitors confused! Be notified of any broken images and links on your site by upgrading to our premium version.', 'my-wp-health-check' ),
                    'url' => 'https://sitealert.io/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=wphc-ad&utm_content=ad-4',
                );
                break;

            case 4:
                // Ad 5.
                $ad = array(
                    'msg' => __( 'Get Slack messages with alerts when something is wrong with your site by upgrading to our premium version.', 'my-wp-health-check' ),
                    'url' => 'https://sitealert.io/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=wphc-ad&utm_content=ad-5',
                );
                break;

            default:
                $ad = array(
                    'msg' => __( 'Monitor your WordPress sites to ensure they stay up, healthy, and secure. Check out our premium plans that include uptime monitoring and a central dashboard!', 'my-wp-health-check' ),
                    'url' => 'https://sitealert.io/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=wphc-ad&utm_content=ad-1',
                );
                break;
        }
        return $ad;
    }

	/**
     * Should we show the three week upsell?
     *
     * @since 1.8.14
	 * @return bool True if it should be shown
	 */
    private static function should_show_three_week_upsell() {
        if ( ! self::is_maybe_premium_user() && self::is_installed_for_three_weeks() && ! self::has_dismissed_three_week_upsell() ) {
            return true;
		}
        return false;
	}

	/**
	 * If the REST API is active, consider them a premium user.
     *
     * @since 1.8.14
     * @return bool True if probably a premium user
	 */
	public static function is_maybe_premium_user() {
		$settings = (array) get_option( 'wphc-settings' );
		if ( isset( $settings['api_key'] ) & ! empty( $settings['api_key'] ) ) {
		    return true;
		}
		return false;
	}

	/**
     * Determines if the plugin has been installed for at least three weeks
     *
     * @since 1.8.14
	 * @return bool True if more than two weeks has passed since installation
	 */
    private static function is_installed_for_three_weeks() {
		$install_time = get_option( 'wphc_install_timestamp', time() );
		if ( 0 !== intval( $install_time ) && $install_time < strtotime( '-3 weeks' ) ) {
		    return true;
		}
		return false;
	}

	/**
     * Has this site dismissed the three week upsell?
     *
     * @since 1.8.14
	 * @return bool True if the site has already dismissed the upsell
	 */
	private static function has_dismissed_three_week_upsell() {
        $dismissed = get_option( 'wphc_dismissed_three_week_upsell', false );
        if ( true == $dismissed ) {
            return true;
		} else {
            return false;
		}
	}
}
?>
