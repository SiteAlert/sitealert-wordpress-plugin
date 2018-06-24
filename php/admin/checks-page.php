<?php
/**
 * Generates the main checks page
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
	wp_enqueue_style( 'wp-hc-style', plugins_url( '../../css/main.css', __FILE__ ) );
	wp_enqueue_script( 'wphc-admin-script', plugins_url( '../../js/wphc-admin.js', __FILE__ ) );
	?>
	<div class="wrap">
		<h2>WordPress Health Check</h2>
		<hr />
		<h2 class="nav-tab-wrapper">
			<a href="#" data-tab='1' class="nav-tab nav-tab-active wphc-tab"><?php esc_html_e( 'Checks', 'my-wp-health-check' ); ?></a>
			<a href="#" data-tab='2' class="nav-tab wphc-tab"><?php esc_html_e( 'Settings', 'my-wp-health-check' ); ?></a>
		</h2>
		<main>
			<div id="tab-1" class="wphc-tab-content">
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
			<div id="tab-2" class="wphc-tab-content">
				<?php WPHC_Settings_Page::display_page(); ?>
			</div>
		</main>
	</div>
	<?php
}