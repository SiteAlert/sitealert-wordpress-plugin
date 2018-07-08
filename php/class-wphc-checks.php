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
		$checks[] = $this->vulnerable_plugins_check( $force, $ignore_limit );
		return apply_filters( 'wphc_plugins_checks', $checks );
	}

	/**
	 * Prepares the JSON array
	 *
	 * @since 1.3.0
	 * @param string $message The message to be displayed.
	 * @param string $type The results of the check. Options are 'good', 'okay', or 'bad'.
	 * @param string $check The check that was performed.
	 * @return array The array of the message and type
	 */
	public function prepare_array( $message, $type, $check = '' ) {
		return array(
			'check'   => $check,
			'message' => $message,
			'type'    => $type,
		);
	}

	/**
	 * Checks if the file editor is disabled
	 *
	 * @since 1.4.0
	 * @return array The array of the message and type
	 */
	public function file_editor_check() {
		if ( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT ) {
			return $this->prepare_array( 'The file editor on this site has been disabled. Great!', 'good', 'file_editor' );
		} else {
			return $this->prepare_array( 'The file editor on this site has not been disabled. Right now, an admin user can edit plugins and themes from within the WordPress admin. It is recommended to disable file editing within the WordPress dashboard. Many security plugins, such as iThemes Security, has features to disable the file editor. Alternatively, you can edit the wp-config file <a href="https://codex.wordpress.org/Hardening_WordPress#Disable_File_Editing" target="_blank">as shown here</a>.', 'okay', 'file_editor' );
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
		if ( function_exists( 'get_core_updates' ) ) {
			$core_update = get_core_updates();
		}
		if ( $core_update && ( ! isset( $core_update[0]->response ) || 'latest' == $core_update[0]->response ) ) {
			return $this->prepare_array( 'Your WordPress is up to date. Great Job!', 'good', 'wordpress_version' );
		} elseif ( ! $core_update ) {
			return $this->prepare_array( 'Encountered an error. WordPress version not checked. Please check again later.', 'okay', 'wordpress_version' );
		} else {
			return $this->prepare_array( 'Your WordPress is not up to date. Your site has not received the latest security fixes and is less secure from hackers. Please consider updating.', 'bad', 'wordpress_version' );
		}
	}

	/**
	 * Checks if using latest version of plugins
	 *
	 * @since 0.2.0
	 * @return array The array of the message and type
	 */
	public function update_plugins_check() {

		// Loads the available plugin updates.
		$plugin_updates = array();
		if ( function_exists( 'get_plugin_updates' ) ) {
			$plugin_updates = get_plugin_updates();
		} else {
			$current     = get_site_transient( 'update_plugins' );
			$all_plugins = $this->get_plugins();
			foreach ( (array) $all_plugins as $plugin_file => $plugin_data ) {
				if ( isset( $current->response[ $plugin_file ] ) ) {
					$plugin_updates[ $plugin_file ]         = (object) $plugin_data;
					$plugin_updates[ $plugin_file ]->update = $current->response[ $plugin_file ];
				}
			}
		}

		if ( ! empty( $plugin_updates ) ) {
			$plugins = array();
			foreach ( $plugin_updates as $plugin ) {
				$plugins[] = $plugin->Name;
			}
			$plugin_list = implode( ',', $plugins );
			return $this->prepare_array( "You are not using the latest version of these plugins: $plugin_list. These updates could contain important security updates. Please update your plugins to ensure your site is secure and safe.", 'bad', 'plugin_updates' );
		} else {
			return $this->prepare_array( 'All of your WordPress plugins are up to date. Great Job!', 'good', 'plugin_updates' );
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
			return $this->prepare_array( 'These plugins are not active: ' . implode( ', ', $inactive_plugins ) . '. Inactive plugins can still be compromised by hackers. If you are not using them, please uninstall them.', 'bad', 'inactive_plugins' );
		} else {
			return $this->prepare_array( 'All of your plugins installed on the site are in use. Great job!', 'good', 'inactive_plugins' );
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
				$slug        = explode( '/', $plugin );
				$plugin_updated = get_transient( 'wphc_supported_check_' . $slug[0] );
				if ( false === $plugin_updated || $force ) {
					$response    = wp_remote_get( "http://api.wordpress.org/plugins/info/1.0/{$slug[0]}" );
					$plugin_info = @unserialize( $response['body'] );
					if ( is_object( $plugin_info ) && isset( $plugin_info->last_updated ) ) {
						$plugin_updated = $plugin_info->last_updated;
					} else {
						// If the plugin isn't from wordpress.org, just add today's date as we have no way of checking when last updated.
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
		}
		if ( empty( $plugin_list ) ) {
			return $this->prepare_array( 'All of your plugins are currently supported. Great Job!', 'good', 'supported_plugins' );
		} else {
			return $this->prepare_array( "The following plugins have not been updated in over two years which indicate that they are no longer supported by their developer: $plugin_list. There could be security issues that will not be fixed! Please reach out to the developers to ensure these plugins are still supported or look for alternatives and uninstall these plugins.", 'bad', 'supported_plugins' );
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
			return $this->prepare_array( "The following plugins have known security vulnerabilities that have not been fixed in an update: $plugin_list. Please reach out to the developer immediately to ensure these vulnerabilities are being patched. If not, you must find alternatives to these plugins.", 'bad', 'vulnerable_plugins' );
		} else {
			return $this->prepare_array( 'Great! None of your plugins have known security vulnerabilities!', 'good', 'vulnerable_plugins' );
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
			return $this->prepare_array( "Your site does not have a user 'admin'. Great job!", 'good', 'admin_user' );
		} else {
			return $this->prepare_array( "There is a user 'admin' on your site. Hackers use this username when trying to gain access to your site. Please change this username to something else.", 'good', 'admin_user' );
		}
	}

	/**
	 * Checks if using latest version of themes
	 *
	 * @since 0.2.0
	 */
	public function themes_check() {

		// Load the available theme updates.
		$theme_updates = array();
		if ( function_exists( 'get_theme_updates' ) ) {
			$theme_updates = get_theme_updates();
		} else {
			$current = get_site_transient( 'update_themes' );
			if ( isset( $current->response ) ) {
				foreach ( $current->response as $stylesheet => $data ) {
					$theme_updates[ $stylesheet ]         = wp_get_theme( $stylesheet );
					$theme_updates[ $stylesheet ]->update = $data;
				}
			}
		}

		// If we have theme updates, show them. If not, say all clear.
		if ( ! empty( $theme_updates ) ) {
			// Get the themes with available updates.
			$updates = implode( ',', array_keys( $theme_updates ) );
			return $this->prepare_array( "You are not using the latest version of these themes: $updates. These updates could contain important security updates. Please update your themes to ensure your site is secure and safe.", 'bad', 'theme_updates' );
		} else {
			return $this->prepare_array( 'All of your WordPress themes are up to date. Great Job!', 'good', 'theme_updates' );
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
		$version = '0.0';
		$maria   = false;

		// Sets up messages.
		$error = __( 'Error checking database health.', 'my-wp-health-check' );
		/* translators: %s: Version of MySQL the server is running */
		$mysql_version = __( 'Your server is running MySQL version %s.', 'my-wp-health-check' );
		/* translators: %s: Version of MariaDB the server is running */
		$maria_version = __( 'Your server is running MariaDB version %s.', 'my-wp-health-check' );

		// Learned better way from the Health Check plugin: https://github.com/WordPress/health-check/blob/5ad8b4dcc3806f4be4a4c1a169ed03bdcbd69d4a/src/pages/health-check.php#L21.
		if ( method_exists( $wpdb, 'db_version' ) ) {
			if ( $wpdb->use_mysqli ) {
				// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_get_server_info
				$mysql_server_type = mysqli_get_server_info( $wpdb->dbh );
			} else {
				// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysql_get_server_info
				$mysql_server_type = mysql_get_server_info( $wpdb->dbh );
			}
			$version = $wpdb->get_var( 'SELECT VERSION()' );
		}

		// Tests if database is maria or mysql.
		if ( stristr( $mysql_server_type, 'mariadb' ) ) {
			$mariadb = true;
		}

		$version_array = explode( '.', $version );

		$msg    = '';
		$status = 'bad';

		if ( $mariadb ) {
			switch ( intval( $version_array[0] ) ) {
				case 5:
					$msg = sprintf( $maria_version, $version ) . ' This is below the recommended version of 10.0. You should consider updating your MariaDB or contacting your host right away.';
					break;
				case 10:
					$msg    = sprintf( $maria_version, $version ) . ' Good job! This is the recommended version.';
					$status = 'good';
					break;

				default:
					$msg = $error;
					break;
			}
		} else {
			switch ( intval( $version_array[0] ) ) {
				case 4:
					$msg = sprintf( $mysql_version, $version ) . " This has not been supported in over 5 years and is below the required 5.0. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You must update your MySQL or contact your host immediately!";
					break;

				case 5:
					switch ( intval( $version_array[1] ) ) {
						case 0:
							$msg = sprintf( $mysql_version, $version ) . " This version has not been supported in 2 years and is below the recommended 5.6. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host right away.";
							break;

						case 1:
							$msg = sprintf( $mysql_version, $version ) . " This version is no longer supported and below the recommended 5.6. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host.";
							break;

						case 5:
							$msg = sprintf( $mysql_version, $version ) . " This version is below the recommended 5.6. You should consider updating your MySQL or contacting your host.";
							break;

						case 6:
							$msg    = sprintf( $mysql_version, $version ) . " Good job! This is the recommended version.";
							$status = 'good';
							break;

						case 7:
							$msg    = sprintf( $mysql_version, $version ) . " Good job! This is a supported version and is above the recommended version.";
							$status = 'good';
							break;

						default:
							$msg = $error;
							break;
						}
					break;

				case 8:
					$msg    = sprintf( $mysql_version, $version ) . " This is the latest version!";
					$status = 'good';
					break;

				default:
					$msg = $error;
					break;
			}
		}

		return $this->prepare_array( $msg, $status, 'mysql_version' );
	}

	/**
	 * Checks if the site is using SSL
	 *
	 * @since 1.4.0
	 */
	public function ssl_check() {
		$learn_more = '<a href="http://bit.ly/2J323Oc" target="_blank">' . __( 'Learn more about what SSL is.', 'my-wp-health-check' ) . '</a>';
		$success    = __( 'Great! You are using SSL on your site.', 'my-wp-health-check' );
		$fail       = __( 'Your site is not using SSL. This is insecure and is hurting your SEO ranking too. Certain browsers are starting to label sites without SSL as "Not Secure" which may cause users to not trust your site. Contact your host about SSL.', 'my-wp-health-check' );
		if ( is_ssl() ) {
			return $this->prepare_array( "$success $learn_more", 'good', 'ssl' );
		} else {
			return $this->prepare_array( "$fail $learn_more", 'bad', 'ssl' );
		}
	}

	/**
	 * Checks if using latest version of php
	 *
	 * @since 0.1.0
	 */
	public function php_check() {
		$version = explode( '.', PHP_VERSION );

		$msg    = '';
		$status = 'bad';

		// Sets up messages.
		$error                = __( 'Error checking PHP health.', 'my-wp-health-check' );
		$your_version_message = 'You server is running PHP version ' . PHP_VERSION;
		$unsupported_message  = 'Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. Also, newer versions are faster which makes your site load faster. You must update your PHP or contact your host immediately!';
		$learn_more           = '<a href="http://bit.ly/2KJs1b6" target="_blank">' . __( 'Learn more about what PHP is.', 'my-wp-health-check' ) . '</a>';
		switch ( intval( $version[0] ) ) {
			case 4:
				$msg = "$your_version_message which has not been supported since Aug 2008 and is below the required 5.2. $unsupported_message $learn_more";
				break;

			case 5:
				switch ( intval( $version[1] ) ) {
					case 0:
						$msg = "$your_version_message which has not been supported since Sep 2005 and is below the required 5.2. $unsupported_message $learn_more";
						break;

					case 1:
						$msg = "$your_version_message which has not been supported since Aug 2006 and is below the required 5.2. $unsupported_message $learn_more";
						break;

					case 2:
						$msg = "$your_version_message which has not been supported since Jan 2011 and is below the recommended 7.2. $unsupported_message $learn_more";
						break;

					case 3:
						$msg = "$your_version_message which has not been supported since Aug 2014 and is below the recommended 7.2. $unsupported_message $learn_more";
						break;

					case 4:
						$msg = "$your_version_message which has not been supported since Sep 2015 and is below the recommended 7.2. $unsupported_message $learn_more";
						break;

					case 5:
						$msg = "$your_version_message which has not been supported since Jul 2016 and is below the recommended 7.2. $unsupported_message $learn_more";
						break;

					case 6:
						$msg    = "$your_version_message which has not been actively supported since Jan 2017 and is below the recommended 7.2. $unsupported_message $learn_more";
						$status = 'okay';
						break;

					default:
						$msg = $error;
						break;
				}
				break;

			case 7:
				switch ( intval( $version[1] ) ) {
					case 0:
						$msg    = "$your_version_message which has not been actively supported since Dec 2017 and is below the recommended 7.2 $learn_more";
						$status = 'okay';
						break;

					case 1:
						$msg    = "$your_version_message. Good job! While this is not the recommended 7.2, this version is still actively supported until Dec 2018. Be sure to check with your host to make sure they have a plan to update to 7.2. $learn_more";
						$status = 'good';
						break;

					case 2:
						$msg    = "$your_version_message. Good job! This is the latest version. $learn_more";
						$status = 'good';
						break;

					default:
						$msg = $error;
						break;
				}
				break;

			default:
				$msg = $error;
				break;
		}
		return $this->prepare_array( $msg, $status, 'php_version' );
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
}


?>
