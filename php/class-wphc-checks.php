<?php
/**
 * File contains the class that handles all the checks.
 *
 * @package WPHC
 */

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main class of the plugin. This handles all the checks
 *
 * @since 1.3.0
 */
class WPHC_Checks {

	/**
	 * Runs all of the checks
	 *
	 * @since 1.3.0
	 * @return array The results of all the checks
	 */
	public function all_checks() {
		$checks         = array();
		$wp_checks      = $this->wp_checks();
		$server_checks  = $this->server_checks();
		$plugins_checks = $this->plugins_checks();
		return array_merge( $checks, $wp_checks, $server_checks, $plugins_checks );
	}

	/**
	 * Runs all the server checks
	 *
	 * @since 1.3.0
	 * @return array The results of all the checks
	 */
	public function server_checks() {
		$checks   = array();
		$checks[] = $this->php_check();
		$checks[] = $this->mysql_check();
		$checks[] = $this->ssl_check();
		return apply_filters( 'wphc_server_checks', $checks );
	}

	/**
	 * Returns all the WP checks
	 *
	 * @since 1.3.0
	 * @return array The results of all the checks
	 */
	public function wp_checks() {
		$checks   = array();
		$checks[] = $this->wordpress_version_check();
		$checks[] = $this->file_editor_check();
		$checks[] = $this->admin_user_check();
		$checks[] = $this->themes_check();
		$checks[] = $this->comments_check();
		$checks[] = $this->public_check();
		return apply_filters( 'wphc_wp_checks', $checks );
	}

	/**
	 * Returns all the plugin checks
	 *
	 * @since 1.3.0
	 * @param bool $force If passed true, will ignore all transients.
	 * @param bool $ignore_limit If passed true, this function will ignore the default 2 seconds limit.
	 * @return array The results of all the checks
	 */
	public function plugins_checks( $force = false, $ignore_limit = false ) {
		$checks   = array();
		$checks[] = $this->update_plugins_check();
		$checks[] = $this->inactive_plugins_check();
		$checks[] = $this->supported_plugin_check( $force, $ignore_limit );
		//$checks[] = $this->vulnerable_plugins_check( $force, $ignore_limit );
		return apply_filters( 'wphc_plugins_checks', $checks );
	}

	/**
	 * Prepares the JSON array
	 *
	 * @since 1.3.0
	 * @param string $message The message to be displayed.
	 * @param string $type The results of the check. Options are 'good', 'okay', or 'bad'.
	 * @param string $check The check that was performed.
	 * @param string $value The result from the check.
	 * @return array The array of the message and type
	 */
	public function prepare_array( $message, $type, $check = '', $value = 'unknown' ) {
		return array(
			'check'   => $check,
			'message' => $message,
			'type'    => $type,
			'value'   => $value,
		);
	}

	/**
	 * Checks if the discourage search engine option is still enabled.
	 *
	 * @since 1.8.0
	 * @return array The array of the message and type
	 */
	public function public_check() {

		// Prepares our messages.
		$good_message = esc_html__( 'Your WordPress is allowing search engines to index your site. Great!', 'my-wp-health-check' );
		$bad_message  = esc_html__( 'Your WordPress site is currently discouraging search engines from indexing your site. If you want search engines to index your site, you can disable this option by unchecking the "Search Engine Visibility" on the "Reading" page of the "Settings" menu.', 'my-wp-health-check' );

		$public = intval( get_option( 'blog_public' ) );
		if ( 1 === $public ) {
			return $this->prepare_array( $good_message, 'good', 'search_engine', true );
		} else {
			return $this->prepare_array( $bad_message, 'okay', 'search_engine', false );
		}
	}

	/**
	 * Checks if there are too many spam comments
	 *
	 * @since 1.7.0
	 * @return array The array of the message and type
	 */
	public function comments_check() {
		// Sets the args to get the count of spam comments.
		$args = array(
			'status' => 'spam',
			'count'  => true,
		);

		// Gets the count.
		$spam_count = get_comments( $args );

		// Prepares our messages.
		$good_message = esc_html__( 'Your WordPress does not have many spam comments. Great!', 'my-wp-health-check' );
		$bad_message  = esc_html__( 'Your WordPress has a lot of spam comments which can affect the speed of your site. You should delete your spam comments.', 'my-wp-health-check' );

		// Checks if the spam count is over 150.
		if ( 150 < $spam_count ) {
			return $this->prepare_array( $bad_message, 'bad', 'comments', $spam_count );
		} else {
			return $this->prepare_array( $good_message, 'good', 'comments', $spam_count );
		}
	}

	/**
	 * Checks if the file editor is disabled
	 *
	 * @since 1.4.0
	 * @return array The array of the message and type
	 */
	public function file_editor_check() {
		if ( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT ) {
			return $this->prepare_array( __( 'The file editor on this site has been disabled. Great!', 'my-wp-health-check' ), 'good', 'file_editor', true );
		} else {
			return $this->prepare_array( __( 'The file editor on this site has not been disabled. Right now, an admin user can edit plugins and themes from within the WordPress admin. It is recommended to disable file editing within the WordPress dashboard. The recommended solution is using a security plugin, such as iThemes Security, that has features to disable the file editor. Alternatively, you can edit the wp-config file', 'my-wp-health-check' ) . ' <a href="https://codex.wordpress.org/Hardening_WordPress#Disable_File_Editing" target="_blank">as shown here</a>.', 'okay', 'file_editor', false );
		}
	}

	/**
	 * Checks if using latest version of WordPress
	 *
	 * @since 0.1.0
	 * @return array The array of the message and type
	 */
	public function wordpress_version_check() {
		$core_update = false;

		$learn_more = '<a href="https://sitealert.io/keeping-your-wordpress-site-updated/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=updated-wordpress-check" target="_blank">' . __( 'Learn more about keeping your site updated.', 'my-wp-health-check' ) . '</a>';

		// Prepares messages.
		$good  = esc_html__( 'Your WordPress is up to date. Great job!' );
		$error = esc_html__( 'Encountered an error. WordPress version not checked. Please check again later.' );
		$bad   = esc_html__( 'Your WordPress is not up to date. Your site has not received the latest security fixes and is less secure from hackers. Please consider updating.' );

		$from_api = get_site_option( '_site_transient_update_core' );
		if ( isset( $from_api->updates ) && is_array( $from_api->updates ) ) {
			$core_update = $from_api->updates;
		}

		if ( $this->is_wp_current( $core_update ) ) {
			return $this->prepare_array( $good . ' ' . $learn_more, 'good', 'wordpress_version', true );
		} elseif ( ! $core_update ) {
			return $this->prepare_array( $error . ' ' . $learn_more, 'okay', 'wordpress_version', false );
		} else {
			return $this->prepare_array( $bad . ' ' . $learn_more, 'bad', 'wordpress_version', false );
		}
	}

	/**
	 * Checks if using latest version of plugins
	 *
	 * @since 0.2.0
	 * @return array The array of the message and type
	 */
	public function update_plugins_check() {

		$learn_more = '<a href="https://sitealert.io/keeping-your-wordpress-site-updated/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=updated-plugin-check" target="_blank">' . __( 'Learn more about keeping your site updated.', 'my-wp-health-check' ) . '</a>';

		// Loads the available plugin updates.
		$plugin_updates = array();
		if ( ! function_exists( 'get_plugin_updates' ) || ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . '/wp-admin/includes/plugin.php';
			include_once ABSPATH . '/wp-admin/includes/update.php';
		}
		$plugin_updates = get_plugin_updates();

		if ( ! empty( $plugin_updates ) ) {
			$plugins = array();
			foreach ( $plugin_updates as $plugin ) {
				$plugins[] = $plugin->Name;
			}
			$plugin_list = implode( ', ', $plugins );
			return $this->prepare_array( "You are not using the latest version of these plugins: $plugin_list. These updates could contain important security updates. Please update your plugins to ensure your site is secure and safe. $learn_more", 'bad', 'plugin_updates', $plugins );
		} else {
			return $this->prepare_array( __( 'All of your WordPress plugins are up to date. Great job!', 'my-wp-health-check' ) . " $learn_more", 'good', 'plugin_updates', array() );
		}
	}

	/**
	 * Checks for inactive plugins
	 *
	 * @since 1.1.0
	 * @return array The array of the message and type
	 */
	public function inactive_plugins_check() {

		// Gets all the plugins.
		$plugins = array();

		// Makes sure the plugin functions are active.
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}
		$plugins = get_plugins();

		// Looks for any inactive plugins.
		$inactive_plugins = array();
		if ( ! empty( $plugins ) ) {
			foreach ( $plugins as $key => $plugin ) {
				if ( is_plugin_inactive( $key ) ) {
					$inactive_plugins[] = $plugin['Name'];
				}
			}
		}

		// If any plugins are inactive, display error message. If not, display success message.
		if ( ! empty( $inactive_plugins ) ) {
			return $this->prepare_array( 'These plugins are not active: ' . implode( ', ', $inactive_plugins ) . '. Inactive plugins can still be compromised by hackers. If you are not using them, please uninstall them.', 'bad', 'inactive_plugins', $inactive_plugins );
		} else {
			return $this->prepare_array( __( 'All of your plugins installed on the site are in use. Great job!', 'my-wp-health-check' ), 'good', 'inactive_plugins', array() );
		}
	}

	/**
	 * Checks For Unsupported Plugins
	 *
	 * Checks the installed plugins to see there is a plugin that hasn't been updated in over 2 years.
	 * For efficiency, we only do the check for up to 2 seconds. So, the main transient is set for every hour
	 * but the individual plugin checks are set for every day. This way, if the 2 seconds is up, we will scan
	 * the rest in the next hour but the actual plugins will only be checked once per day
	 *
	 * @since 1.0.0
	 * @param bool $force If passed true, will ignore all transients.
	 * @param bool $ignore_limit If passed true, this function will ignore the default 2 seconds limit.
	 * @return array The array of the message and type
	 */
	public function supported_plugin_check( $force = false, $ignore_limit = false ) {
		$plugin_list = get_transient( 'wphc_supported_plugin_check' );
		$learn_more  = '<a href="https://sitealert.io/finding-quality-wordpress-plugins/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=supported-plugin-check" target="_blank">' . __( 'Learn more about finding quality plugins.', 'my-wp-health-check' ) . '</a>';
		if ( false === $plugin_list || $force ) {
			$unsupported_plugins = array();

			// Makes sure the plugin functions are active.
			if ( ! function_exists( 'get_plugins' ) ) {
				include ABSPATH . '/wp-admin/includes/plugin.php';
			}

			// Gets our list of plugins.
			$plugins = get_plugins();

			// Cycle through all plugins.
			$now = time();
			foreach ( $plugins as $plugin => $plugin_data ) {
				$slug           = explode( '/', $plugin );
				$plugin_updated = get_transient( 'wphc_supported_check_' . $slug[0] );

				// If the transient doesn't exists, is old, or we are forcing a refresh, refresh the date.
				if ( false === $plugin_updated || $force ) {
					$response    = wp_remote_get( "http://api.wordpress.org/plugins/info/1.0/{$slug[0]}.json" );
					$api_data    = wp_remote_retrieve_body( $response );
					$plugin_info = json_decode( $api_data, true );

					if ( is_array( $plugin_info ) && isset( $plugin_info['last_updated'] ) ) {
						$plugin_updated = $plugin_info['last_updated'];
					} else {
						// If the plugin isn't from wordpress.org or there was an error, just add today's date as we have no way of checking when last updated.
						$plugin_updated = date( 'Y-m-d' );
					}
					set_transient( 'wphc_supported_check_' . $slug[0], $plugin_updated, 1 * DAY_IN_SECONDS );
				}

				// If the plugin hasn't been updated in two years, add to our list.
				if ( time() - ( 60 * 60 * 24 * 365 * 2 ) > strtotime( $plugin_updated ) ) {
					$unsupported_plugins[] = $plugin_data['Name'];
				}

				// If we have been doing this for at least two seconds, move on.
				if ( time() - $now >= 2 && ! $ignore_limit ) {
					break;
				}
			}

			// Creates our list and stores as transient.
			$plugin_list = implode( ', ', $unsupported_plugins );
			set_transient( 'wphc_supported_plugin_check', $plugin_list, 1 * HOUR_IN_SECONDS );
		} else {
			$unsupported_plugins = explode( ', ', $plugin_list );
		}
		if ( empty( $plugin_list ) ) {
			return $this->prepare_array( "All of your plugins are currently supported. Great job! $learn_more", 'good', 'supported_plugins', array() );
		} else {
			return $this->prepare_array( "The following plugins have not been updated in over two years which indicate that they are no longer supported by their developer: $plugin_list. There could be security issues that will not be fixed! Please reach out to the developers to ensure these plugins are still supported or look for alternatives and uninstall these plugins. $learn_more", 'bad', 'supported_plugins', $unsupported_plugins );
		}
	}

	/**
	 * Checks for vunlerable plugins using wpvulndb.com's api
	 *
	 * For efficiency, we only do the check for up to 2 seconds. So, the main transient is set for every hour
	 * but the individual plugin checks are set for every day. This way, if the 2 seconds is up, we will scan
	 * the rest in the next hour but the actual plugins will only be checked once per day.
	 *
	 * @since 1.2.0
	 * @param bool $force If passed true, will ignore all transients.
	 * @param bool $ignore_limit If passed true, this function will ignore the default 2 seconds limit.
	 * @return array The array of the message and type
	 */
	public function vulnerable_plugins_check( $force = false, $ignore_limit = false ) {
		$vulnerable_plugins = array();

		// Makes sure the plugin functions are active.
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		// Gets our list of plugins.
		$plugins = array_keys( get_plugins() );

		// Cycles through the plugins.
		$now = time();
		foreach ( $plugins as $key => $plugin ) {
			$slug        = explode( '/', $plugin );
			$plugin_data = get_transient( 'wphc_vunlerability_check_' . $slug[0] );

			// Checks if our transient existed already. If not, get data from the API and store it in a transient.
			if ( false === $plugin_data || $force ) {
				$response = wp_remote_get( 'https://wpvulndb.com/api/v2/plugins/' . $slug[0] );
				if ( ! is_wp_error( $response ) ) {
					$data = wp_remote_retrieve_body( $response );
					if ( ! is_wp_error( $data ) ) {
						$plugin_data = $data;
						set_transient( 'wphc_vunlerability_check_' . $slug[0], $plugin_data, 1 * DAY_IN_SECONDS );
					}
				}
			}

			// Checks to make sure the data has been retreived from transient or the api. Then, decodes the JSON data.
			if ( false !== $plugin_data ) {
				$plugin_data = json_decode( $plugin_data, true );
				if ( is_array( $plugin_data ) ) {
					foreach ( $plugin_data as $plugin_name => $plugin_info ) {
						if ( ! empty( $plugin_info['vulnerabilities'] ) ) {

							// Cycles through the vulnerabilities checking to see if the vulnerability has not been fixed yet.
							foreach ( $plugin_info['vulnerabilities'] as $vulnerability ) {
								if ( null === $vulnerability['fixed_in'] ) {
									$vunlerable_plugins[] = $plugin_name;
								}
							}
						}
					}
				}
			}

			// If we have been doing this for at least two seconds, move on.
			if ( time() - $now >= 2 && ! $ignore_limit ) {
				break;
			}
		}
		if ( ! empty( $vulnerable_plugins ) ) {
			$plugin_list = implode( ',', $vulnerable_plugins );
			return $this->prepare_array( "The following plugins have known security vulnerabilities that have not been fixed in an update: $plugin_list. Please reach out to the developer immediately to ensure these vulnerabilities are being patched. If not, you must find alternatives to these plugins.", 'bad', 'vulnerable_plugins', $vulnerable_plugins );
		} else {
			return $this->prepare_array( 'Great! None of your plugins have known security vulnerabilities!', 'good', 'vulnerable_plugins', array() );
		}
	}

	/**
	 * Checks if there is a user called admin
	 *
	 * @since 1.0.0
	 */
	public function admin_user_check() {
		$user = get_user_by( 'login', 'admin' );
		if ( false === $user ) {
			return $this->prepare_array( "Your site does not have a user 'admin'. Great job!", 'good', 'admin_user', true );
		} else {
			return $this->prepare_array( "There is a user 'admin' on your site. Hackers use this username when trying to gain access to your site. Please change this username to something else.", 'bad', 'admin_user', false );
		}
	}

	/**
	 * Checks if using latest version of themes
	 *
	 * @since 0.2.0
	 */
	public function themes_check() {

		$learn_more = '<a href="https://sitealert.io/keeping-your-wordpress-site-updated/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=updated-theme-check" target="_blank">' . __( 'Learn more about keeping your site updated.', 'my-wp-health-check' ) . '</a>';

		// Load the available theme updates.
		$theme_updates = array();
		if ( ! function_exists( 'get_theme_updates' ) ) {
			include ABSPATH . '/wp-admin/includes/update.php';
		}
		$theme_updates = get_theme_updates();

		// If we have theme updates, show them. If not, say all clear.
		if ( ! empty( $theme_updates ) ) {
			// Get the themes with available updates.
			$updates = implode( ', ', array_keys( $theme_updates ) );
			return $this->prepare_array( "You are not using the latest version of these themes: $updates. These updates could contain important security updates. Please update your themes to ensure your site is secure and safe. $learn_more", 'bad', 'theme_updates', array_keys( $theme_updates ) );
		} else {
			return $this->prepare_array( __( 'All of your WordPress themes are up to date. Great job!', 'my-wp-health-check' ) . " $learn_more", 'good', 'theme_updates', array() );
		}
	}

	/**
	 * Checks if using latest version of mysql
	 *
	 * @since 0.1.0
	 */
	public function mysql_check() {
		global $wpdb;

		// Sets up main variables.
		$version             = '0.0';
		$mariadb             = false;
		$mysql_recommended   = '5.6';
		$mariadb_recommended = '10.1;';
		$error               = __( 'Error checking database health.', 'my-wp-health-check' );
		$values              = array(
			'app'     => 'MySQL',
			'version' => $version,
		);

		// Sets up mysql versions and dates.
		$mysql_versions = array(
			'5.0' => array(
				'release' => 'October 1, 2005',
				'eol'     => 'December 1, 2011',
			),
			'5.1' => array(
				'release' => 'December 1, 2008',
				'eol'     => 'December 1, 2013',
			),
			'5.5' => array(
				'release' => 'December 1, 2010',
				'eol'     => 'December 1, 2018',
			),
			'5.6' => array(
				'release' => 'February 1, 2013',
				'eol'     => 'February 1, 2021',
			),
			'5.7' => array(
				'release' => 'October 1, 2015',
				'eol'     => 'October 1, 2023',
			),
			'8.0' => array(
				'release' => 'April 1, 2018',
				'eol'     => 'April 1, 2026',
			),
		);

		// Sets up mariadb versions and dates.
		$mariadb_versions = array(
			'5.1' => array(
				'release' => 'February 1, 2010',
				'eol'     => 'February 1, 2015',
			),
			'5.2' => array(
				'release' => 'November 10, 2010',
				'eol'     => 'November 10, 2015',
			),
			'5.3' => array(
				'release' => 'February 29, 2012',
				'eol'     => 'March 1, 2017',
			),
			'5.5' => array(
				'release' => 'April 11, 2012',
				'eol'     => 'April 11, 2020',
			),
			'10.0' => array(
				'release' => 'March 31, 2014',
				'eol'     => 'March 31, 2019',
			),
			'10.1' => array(
				'release' => 'October 17, 2015',
				'eol'     => 'October 17, 2020',
			),
			'10.2' => array(
				'release' => 'May 23 1, 2017',
				'eol'     => 'May 23, 2022',
			),
			'10.3' => array(
				'release' => 'May 25, 2018',
				'eol'     => 'May 25, 2023',
			),
			'10.4' => array(
				'release' => 'June 18, 2019',
				'eol'     => 'June 18, 2024',
			),
			'10.5' => array(
				'release' => 'June 24, 2020',
				'eol'     => 'June 24, 2025',
			),
		);

		// Learned better way from the Health Check plugin: https://github.com/WordPress/health-check/blob/5ad8b4dcc3806f4be4a4c1a169ed03bdcbd69d4a/src/pages/health-check.php#L21.
		if ( method_exists( $wpdb, 'db_version' ) ) {
			if ( $wpdb->use_mysqli ) {
				// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_get_server_info
				$mysql_server_type = mysqli_get_server_info( $wpdb->dbh );
			} elseif ( function_exists( 'mysql_get_server_info' ) ) {
				// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysql_get_server_info
				$mysql_server_type = mysql_get_server_info( $wpdb->dbh );
			}
			$version = $wpdb->get_var( 'SELECT VERSION()' );
		}

		// Tests if database is maria or mysql.
		if ( stristr( $mysql_server_type, 'mariadb' ) ) {
			$mariadb       = true;
			$values['app'] = 'MariaDB';
		}

		/**
		 * Do some quick checks to make sure everything will work.
		 */
		$version_array = explode( '.', $version );
		if ( ! is_array( $version_array ) || count( $version_array ) < 2 ) {
			return $this->prepare_array( $error, 'bad', 'db_version', array() );
		}
		$db_version        = $version_array[0] . '.' . $version_array[1];
		$values['version'] = $db_version;
		if ( ! $mariadb && ! isset( $mysql_versions[ $db_version ] ) ) {
			return $this->prepare_array( $error, 'bad', 'db_version', $values );
		} elseif( $mariadb && ! isset( $mariadb_versions[ $db_version ] ) ) {
			return $this->prepare_array( $error, 'bad', 'db_version', $values );
		}

		/**
		 * Sets up strings with translations.
		 */
		/* translators: %1$s: MySQL or MariaDB, %2$s Version of MySQL/MariaDB, %3$s: the date the version of MySQL/MariaDB stopped receiving security updates. */
		$unsupported_version_message = sprintf( __( 'Your server is running %1$s version %2$s which has not been supported since %3$s.', 'my-wp-health-check' ), $mariadb ? 'MariaDB' : 'MySQL', $db_version, $mariadb ? $mariadb_versions[ $db_version ]['eol'] : $mysql_versions[ $db_version ]['eol'] );
		/* translators: %1$s: MySQL or MariaDB, %2$s Version of MySQL/MariaDB, %3$s: the date the version of MySQL/MariaDB will stop receiving security updates. */
		$supported_version_message = sprintf( __( 'Good job! Your server is running %1$s version %2$s which will receive security updates until %3$s.', 'my-wp-health-check' ), $mariadb ? 'MariaDB' : 'MySQL', $db_version, $mariadb ? $mariadb_versions[ $db_version ]['eol'] : $mysql_versions[ $db_version ]['eol'] );
		$unsupported_message       = __( 'Using an unsupported version means that you are using a version that no longer receives important security updates and fixes. You must update or contact your host immediately!', 'my-wp-health-check' );
		$security_ending_message   = __( 'Be sure to check with your host to make sure they have a plan to update before the security support ends.', 'my-wp-health-check' );
		/* translators: %1$s: The recommended version of MySQL or MariaDB. */
		$below_recommended         = sprintf( __( 'This is below the recommended %1$s.', 'my-wp-health-check' ), $mariadb ? $mariadb_recommended : $mysql_recommended );

		$eol_time = $mariadb ? strtotime( $mariadb_versions[ $db_version ]['eol'] ) : strtotime( $mysql_versions[ $db_version ]['eol'] );
		$today    = time();
		if ( $eol_time <= $today ) {
			// If EOL is passed, show unsupported message.
			$msg = $unsupported_version_message . ' ' . $unsupported_message;
		} elseif ( $eol_time - ( 120 * DAY_IN_SECONDS ) < $today ) {
			// If EOL is coming up within the next 120 days, show expiring soon message.
			$msg    = $supported_version_message . ' ' . $security_ending_message;
			$status = 'okay';
		} else {
			// If EOL is farther than 120 days out, show good message.
			$msg    = $supported_version_message;
			$status = 'good';
		}

		if ( $mariadb && version_compare( $db_version, $mariadb_recommended, '<' ) ) {
			$msg .= ' ' . $below_recommended;
		} elseif ( ! $mariadb && version_compare( $db_version, $mysql_recommended, '<' ) ) {
			$msg .= ' ' . $below_recommended;
		}

		return $this->prepare_array( $msg, $status, 'db_version', $values );
	}

	/**
	 * Checks if the site is using SSL
	 *
	 * @since 1.4.0
	 */
	public function ssl_check() {
		$learn_more = '<a href="https://sitealert.io/does-my-site-need-ssl/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=ssl-check" target="_blank">' . __( 'Learn more about what SSL is.', 'my-wp-health-check' ) . '</a>';
		$success    = __( 'Great! You are using SSL on your site.', 'my-wp-health-check' );
		$fail       = __( 'Your site is not using SSL. This is not secure and is hurting your SEO ranking too. Certain browsers are starting to label sites without SSL as "Not Secure" which may cause users to not trust your site. Contact your host about SSL.', 'my-wp-health-check' );
		if ( is_ssl() ) {
			return $this->prepare_array( "$success $learn_more", 'good', 'ssl', true );
		} else {
			return $this->prepare_array( "$fail $learn_more", 'bad', 'ssl', false );
		}
	}

	/**
	 * Checks if using latest version of php
	 *
	 * @since 0.1.0
	 */
	public function php_check() {
		$version = explode( '.', PHP_VERSION );
		$msg     = '';
		$status  = 'bad';

		// Sets up PHP versions and dates.
		$php_versions = array(
			'5.0' => array(
				'release' => 'July 13, 2004',
				'eol'     => 'September 5, 2005',
			),
			'5.1' => array(
				'release' => 'November 24, 2005',
				'eol'     => 'August 24, 2006',
			),
			'5.2' => array(
				'release' => 'November 2, 2006',
				'eol'     => 'January 6, 2011',
			),
			'5.3' => array(
				'release' => 'June 30, 2009',
				'eol'     => 'August 14, 2014',
			),
			'5.4' => array(
				'release' => 'March 1, 2012',
				'eol'     => 'September 3, 2015',
			),
			'5.5' => array(
				'release' => 'June 20, 2013',
				'eol'     => 'July 21, 2016',
			),
			'5.6' => array(
				'release' => 'August 28, 2014',
				'eol'     => 'December 31, 2018',
			),
			'7.0' => array(
				'release' => 'December 3, 2015',
				'eol'     => 'December 2, 2018',
			),
			'7.1' => array(
				'release' => 'December 1, 2016',
				'eol'     => 'December 1, 2019',
			),
			'7.2' => array(
				'release' => 'November 30, 2017',
				'eol'     => 'November 30, 2020',
			),
			'7.3' => array(
				'release' => 'December 6, 2018',
				'eol'     => 'December 6, 2021',
			),
			'7.4' => array(
				'release' => 'November 28, 2019',
				'eol'     => 'November 28, 2022',
			),
			'8.0' => array(
				'release' => 'November 26, 2020',
				'eol'     => 'November 26, 2023',
			),
		);

		/**
		 * Do some quick checks to make sure everything will work.
		 */
		$error = __( 'Error checking PHP health.', 'my-wp-health-check' );
		if ( ! is_array( $version ) || count( $version ) < 2 ) {
			return $this->prepare_array( $error, $status, 'php_version', PHP_VERSION );
		}
		$site_version = $version[0] . '.' . $version[1];
		if ( ! isset( $php_versions[ $site_version ] ) ) {
			return $this->prepare_array( $error, $status, 'php_version', PHP_VERSION );
		}

		/**
		 * Sets up strings with translations.
		 */
		/* translators: %s: Version of PHP and the date the version of PHP stops receiving security updates */
		$unsupported_version_message = sprintf( __( 'Your server is running PHP version %1$s which has not been supported since %2$s.', 'my-wp-health-check' ), $site_version, $php_versions[ $site_version ]['eol'] );
		/* translators: %s: Version of PHP and the date the version of PHP stops receiving security updates */
		$supported_version_message = sprintf( __( 'Good job! Your server is running PHP version %1$s which will receive security updates until %2$s.', 'my-wp-health-check' ), $site_version, $php_versions[ $site_version ]['eol'] );
		$unsupported_message       = __( 'Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. Also, newer versions are faster which makes your site load faster. You must update your PHP or contact your host immediately!', 'my-wp-health-check' );
		$security_ending_message   = __( 'Be sure to check with your host to make sure they have a plan to update before the security support ends.', 'my-wp-health-check' );
		$below_recommended         = __( 'This is below the recommended 7.2.', 'my-wp-health-check' );
		$learn_more                = '<a href="https://sitealert.io/what-is-php-and-what-version-should-my-site-use/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=php-check" target="_blank">' . __( 'Learn more about what PHP is.', 'my-wp-health-check' ) . '</a>';

		$eol_time = strtotime( $php_versions[ $site_version ]['eol'] );
		$today    = time();
		if ( $eol_time <= $today ) {
			// If EOL is passed, show unsupported message.
			$msg = $unsupported_version_message . ' ' . $unsupported_message;
		} elseif ( $eol_time - ( 120 * DAY_IN_SECONDS ) < $today ) {
			// If EOL is coming up within the next 120 days, show expiring soon message.
			$msg    = $supported_version_message . ' ' . $security_ending_message;
			$status = 'okay';
		} else {
			// If EOL is farther than 120 days out, show good message.
			$msg    = $supported_version_message;
			$status = 'good';
		}

		if ( version_compare( $site_version, '7.2', '<' ) ) {
			$msg .= ' ' . $below_recommended;
		}

		$msg .= ' ' . $learn_more;
		return $this->prepare_array( $msg, $status, 'php_version', PHP_VERSION );
	}

	/**
	 * Loads the plugins if we are not in admin
	 *
	 * @since 1.4.4
	 */
	private function get_plugins() {
		$cache_plugins = wp_cache_get( 'plugins', 'plugins' );
		if ( $cache_plugins ) {
			return $cache_plugins[''];
		}
		return array();
	}

	/**
	 * Determines if WordPress version is current
	 *
	 * @since 1.6.4
	 * @param array $updates The updates available, if any.
	 * @return bool True if the version is current
	 */
	private function is_wp_current( $updates ) {
		return $updates && ( ! isset( $updates[0]->response ) || 'latest' == $updates[0]->response );
	}
}
