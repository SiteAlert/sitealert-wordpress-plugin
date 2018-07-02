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
	wp_enqueue_script( 'wphc-admin-script', plugins_url( '../../js/wphc-admin.js', __FILE__ ), array(), $my_wp_health_check->version );
	?>
	<div class="wrap">
		<h2>WP Health</h2>
		<hr />
		<h2 class="nav-tab-wrapper">
			<a href="#" data-tab='1' class="nav-tab nav-tab-active wphc-tab"><?php esc_html_e( 'Checks', 'my-wp-health-check' ); ?></a>
			<a href="#" data-tab='2' class="nav-tab wphc-tab"><?php esc_html_e( 'Settings', 'my-wp-health-check' ); ?></a>
		</h2>
		<main>
			<div id="tab-1" class="wphc-tab-content">
				<div class="wphc-flex">
					<div class="wphc-flex-item">
						<h3><?php esc_html_e( 'Server Check', 'my-wp-health-check' ); ?></h3>
						<div class="server-checks">
							<?php do_action( 'wphc_server_check' ); ?>
						</div>
						<h3><?php esc_html_e( 'WordPress Check', 'my-wp-health-check' ); ?></h3>
						<div class="WordPress-checks">
							<?php do_action( 'wphc_wordpress_check' ); ?>
						</div>
						<h3><?php esc_html_e( 'Plugin Check', 'my-wp-health-check' ); ?></h3>
						<div class="plugin-checks">
							<?php do_action( 'wphc_plugin_check' ); ?>
						</div>
					</div>
					<div class="wphc-flex-item">
						<div class="wphc-info-section">
							<h3 class="wphc-info-section-title">WP Health News</h3>
							<div class="wphc-info-box">
								<h4>Subscribe to our newsletter!</h4>
								<p>Learn about our newest features, receive tips and guides, and more!</p>
								<a href="http://bit.ly/2KGTxpK" target="_blank" class="button-primary">Subscribe Now!</a>
							</div>
							<?php
							$wphc_rss  = array();
							$wphc_feed = fetch_feed( 'https://wphealth.app/feed/' );
							if ( ! is_wp_error( $wphc_feed ) ) {
								$wphc_feed_items = $wphc_feed->get_items( 0, 5 );
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
									<a target='_blank' href="<?php echo esc_attr( $item['link'] ); ?>?utm_source=checks-page&utm_medium=plugin&utm_campaign=health-plugin" class="button-primary"><?php _e( 'Read More', 'my-wp-health-check' ); ?></a>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div id="tab-2" class="wphc-tab-content">
				<?php WPHC_Settings_Page::display_page(); ?>
			</div>
		</main>
	</div>
	<?php
}
