<?php
/**
 * Generates the main checks page
 *
 * @package WPHC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates the admin page
 *
 * @since 1.6.0
 */
function wphc_generate_checks_page() {
	if ( ! current_user_can( 'moderate_comments' ) ) {
		return;
	}
	global $my_wp_health_check;
	wp_enqueue_style( 'wphc-style', plugins_url( '../../css/main.css', __FILE__ ), array(), $my_wp_health_check->version );
	wp_enqueue_script( 'wphc-admin-script', plugins_url( '../../js/wphc-admin.js', __FILE__ ), array( 'backbone', 'underscore', 'wp-util' ), $my_wp_health_check->version );

	$settings = (array) get_option( 'wphc-settings' );
	?>
	<div class="wrap">
		<h2>SiteAlert</h2>
		<div class="admin-messages"></div>
		<hr />
		<h2 class="nav-tab-wrapper">
			<a href="#" data-tab='1' class="nav-tab nav-tab-active wphc-tab"><?php esc_html_e( 'Checks', 'my-wp-health-check' ); ?></a>
			<a href="#" data-tab='2' class="nav-tab wphc-tab"><?php esc_html_e( 'Settings', 'my-wp-health-check' ); ?></a>
		</h2>
		<main>
			<div id="tab-1" class="wphc-tab-content">
				<div class="wphc-flex">
					<div class="wphc-flex-item">
						<h3><?php esc_html_e( 'Server Checks', 'my-wp-health-check' ); ?></h3>
						<div class="server-checks">
							<?php do_action( 'wphc_server_check' ); ?>
						</div>
						<h3><?php esc_html_e( 'WordPress Checks', 'my-wp-health-check' ); ?></h3>
						<div class="WordPress-checks">
							<?php do_action( 'wphc_wordpress_check' ); ?>
						</div>
						<h3><?php esc_html_e( 'Plugin Checks', 'my-wp-health-check' ); ?></h3>
						<div class="plugin-checks">
							<?php do_action( 'wphc_plugin_check' ); ?>
						</div>
						<?php
                        if ( ! WPHC_Upsells::is_maybe_premium_user() ) {
							?>
                            <h3><?php esc_html_e( 'Premium Checks', 'my-wp-health-check' ); ?></h3>
                            <p><?php esc_html_e( 'Check out our premium plans that include uptime monitoring and a central dashboard!', 'my-wp-health-check' ); ?> <a href="https://sitealert.io/upgrade?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=premium-checks-info" target="_blank"><?php esc_html_e( 'Learn more!', 'my-wp-health-check' ); ?></a></p>
                            <div class="premium-checks"></div>
							<?php
						}
						?>
					</div>
					<div class="wphc-flex-item">
						<div class="wphc-info-section">
							<h3 class="wphc-info-section-title">SiteAlert News</h3>
							<div class="wphc-info-box">
								<h4><?php esc_html_e( 'Learn how to maintain your WordPress site!', 'my-wp-health-check' ); ?></h4>
								<p><?php esc_html_e( 'Get our 5-day "Maintaining Your WordPress Site" email course. Sign up to receive our email course that will discuss:', 'my-wp-health-check' ); ?></p>
								<ul>
									<li><?php esc_html_e( 'Hosting', 'my-wp-health-check' ); ?></li>
									<li><?php esc_html_e( 'Updates', 'my-wp-health-check' ); ?></li>
									<li><?php esc_html_e( 'Backups', 'my-wp-health-check' ); ?></li>
									<li><?php esc_html_e( 'Security', 'my-wp-health-check' ); ?></li>
									<li><?php esc_html_e( 'and more!', 'my-wp-health-check' ); ?></li>
								</ul>
								<div id="wphc-subscribe">
									<?php
									$current_user = wp_get_current_user();

									$name  = '';
									$email = '';
									if ( $current_user instanceof WP_User ) {
										$name  = $current_user->user_firstname;
										$email = $current_user->user_email;
									}
									?>
									<label>First Name</label>
									<input type="text" id="wphc-subscribe-name" value="<?php echo esc_attr( $name ); ?>">
									<label>Email</label>
									<input type="email" id="wphc-subscribe-email" value="<?php echo esc_attr( $email ); ?>">
									<button id="wphc-subscribe-button" class="button-primary">Subscribe</button>
								</div>
							</div>
							<?php
							$wphc_rss  = array();
							$wphc_feed = fetch_feed( 'https://sitealert.io/feed/' );
							if ( ! is_wp_error( $wphc_feed ) ) {
								$wphc_feed_items = $wphc_feed->get_items( 0, 3 );
								foreach ( $wphc_feed_items as $feed_item ) {
									$wphc_rss[] = array(
										'link'        => $feed_item->get_link(),
										'title'       => $feed_item->get_title(),
										'description' => $feed_item->get_description(),
										'date'        => $feed_item->get_date( 'F j Y' ),
										'author'      => $feed_item->get_author()->get_name(),
									);
								}
							}
							foreach ( $wphc_rss as $item ) {
								?>
								<div class="wphc-info-box">
									<h4><?php echo esc_html( $item['title'] ); ?></h4>
									<p>By <?php echo esc_html( $item['author'] ); ?></p>
									<div>
										<?php echo esc_html( $item['description'] ); ?>
									</div>
									<a target='_blank' href="<?php echo esc_attr( $item['link'] ); ?>?utm_source=checks-page&utm_medium=plugin&utm_campaign=health-plugin" class="button-primary"><?php esc_html_e( 'Read More', 'my-wp-health-check' ); ?></a>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div id="tab-2" class="wphc-tab-content">
				<h2><?php esc_html_e( 'Main Settings', 'my-wp-health-check' ); ?></h2>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row" class="checks-settings-th">
								<label for="api_key"><?php esc_html_e( 'Your SiteAlert Premium API Key', 'my-wp-health-check' ); ?></label>
								<p><?php esc_html_e( 'Enter your API Key to add this site to your SiteAlert Premium account.', 'my-wp-health-check' ); ?></p>
								<p class="settings-explainers"><?php esc_html_e( 'If you are a developer wanting to use the REST API: By default the REST API is disabled for this plugin. You can add your own custom API Key to enable it so you can build custom scripts around the REST API.', 'my-wp-health-check' ); ?></p>
							</th>
							<td>
								<?php
								$api_key  = '';
								if ( isset( $settings['api_key'] ) ) {
									$api_key = $settings['api_key'];
								}
								?>
								<input type='text' name='api_key' id='api_key' value='<?php echo esc_attr( $api_key ); ?>' />
							</td>
						</tr>
						<tr>
							<th scope="row" class="checks-settings-th">
								<label for="tracking_allowed"><?php esc_html_e( 'Allow Usage Tracking?', 'my-wp-health-check' ); ?></label>
								<p class="settings-explainers"><?php esc_html_e( "Allows SiteAlert to collect data about this plugin's usage and help us make this plugin better.", 'my-wp-health-check' ); ?></p>
								<p class="settings-explainers"><a href="https://sitealert.io/what-the-plugin-tracks/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=tracking-notice" target="_blank"><?php esc_html_e( 'Click here to learn more', 'my-wp-health-check' ); ?></a></p>
							</th>
							<td>
								<?php
								$tracking_allowed = '0';
								if ( isset( $settings['tracking_allowed'] ) ) {
									$tracking_allowed = esc_attr( $settings['tracking_allowed'] );
								}
								?>
								<input type='checkbox' name='tracking_allowed' id='tracking_allowed' value='2' <?php checked( '2', $tracking_allowed, true ); ?>>
							</td>
						</tr>
					</tbody>
				</table>
				<button class="btn button button-primary" id="wphc-settings-save"><?php esc_html_e( 'Save Changes', 'my-wp-health-check' ); ?></button>
			</div>
		</main>
	</div>
	<!-- View for Notices -->
	<script type="text/template" id="tmpl-notice">
		<div class="notice notice-{{data.type}}">
			<p>{{data.message}}</p>
		</div>
	</script>
	<?php
}
