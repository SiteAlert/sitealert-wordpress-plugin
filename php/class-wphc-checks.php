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
	 * @return array The results of all the checks
	 */
	public function plugins_checks() {
		$checks   = array();
		$checks[] = $this->update_plugins_check();
		$checks[] = $this->inactive_plugins_check();
		$checks[] = $this->supported_plugin_check();
		$checks[] = $this->vulnerable_plugins_check();
		return apply_filters( 'wphc_plugins_checks', $checks );
	}

	/**
	 * Prepares the JSON array
	 *
	 * @since 1.3.0
	 * @param string $message The message to be displayed.
	 * @param string $type The results of the check. Options are 'good', 'okay', or 'bad'.
	 * @return array The array of the message and type
	 */
	public function prepare_array( $message, $type ) {
		return array(
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
			return $this->prepare_array( 'The file editor on this site has been disabled. Great!', 'good' );
		} else {
			return $this->prepare_array( 'The file editor on this site has not been disabled. Right now, an admin user can edit plugins and themes from within the WordPress admin. It is recommended to disable file editing within the WordPress dashboard. To do so requires editing the wp-config file as <a href="https://codex.wordpress.org/Hardening_WordPress#Disable_File_Editing" target="_blank">shown here</a>.', 'okay' );
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
			return $this->prepare_array( 'Your WordPress is up to date. Great Job!', 'good' );
		} elseif ( ! $core_update ) {
			return $this->prepare_array( 'Encountered an error. WordPress version not checked. Please check again later.', 'okay' );
		} else {
			return $this->prepare_array( 'Your WordPress is not up to date. Your site has not received the latest security fixes and is less secure from hackers. Please consider updating.', 'bad' );
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
			return $this->prepare_array( "You are not using the latest version of these plugins: $plugin_list. These updates could contain important security updates. Please update your plugins to ensure your site is secure and safe.", 'bad' );
		} else {
			return $this->prepare_array( 'All of your WordPress plugins are up to date. Great Job!', 'good' );
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
		if ( function_exists( 'get_plugins' ) ) {
			$plugins = get_plugins();
		} else {
			$plugins = $this->get_plugins();
		}

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
			return $this->prepare_array( 'These plugins are not active: ' . implode( ', ', $inactive_plugins ) . '. Inactive plugins can still be compromised by hackers. If you are not using them, please uninstall them.', 'bad' );
		} else {
			return $this->prepare_array( 'All of your plugins installed on the site are in use. Great job!', 'good' );
		}
	}

	/**
	 * Checks For Unsupported Plugins
	 *
	 * Checks the installed plugins to see there is a plugin that hasn't been updated in over 2 years
	 *
	 * @since 1.0.0
	 * @return array The array of the message and type
	 */
	public function supported_plugin_check() {
		$plugin_list = get_transient( 'wphc_supported_plugin_check' );
		if ( false === $plugin_list ) {
			$slugs = array();

			$unsupported_plugins = array();

			$plugin_info = get_site_transient( 'update_plugins' );
			if ( isset( $plugin_info->no_update ) ) {
				foreach ( $plugin_info->no_update as $plugin ) {
					$slugs[] = $plugin->slug;
				}
			}

			if ( isset( $plugin_info->response ) ) {
				foreach ( $plugin_info->response as $plugin ) {
					$slugs[] = $plugin->slug;
				}
			}
			foreach ( $slugs as $plugin ) {
				$response    = wp_remote_get( "http://api.wordpress.org/plugins/info/1.0/$plugin" );
				$plugin_info = unserialize( $response['body'] );
				if ( is_object( $plugin_info ) ) {
					if ( time() - ( 60 * 60 * 24 * 365 * 2 ) > strtotime( $plugin_info->last_updated ) ) {
						$unsupported_plugins[] = $plugin_info->name;
					}
				}
			}
			$plugin_list = implode( ',', $unsupported_plugins );
			set_transient( 'wphc_supported_plugin_check', $plugin_list, 1 * DAY_IN_SECONDS );
		}
		if ( empty( $plugin_list ) ) {
			return $this->prepare_array( 'All of your plugins are currently supported. Great Job!', 'good' );
		} else {
			return $this->prepare_array( "The following plugins have not been updated in over two years which indicate that they are no longer supported by their developer: $plugin_list. There could be security issues that will not be fixed! Please reach out to the developers to ensure these plugins are still supported or look for alternatives and uninstall these plugins.", 'bad' );
		}
	}

	/**
	 * Checks for vunlerable plugins using wpvulndb.com's api
	 *
	 * @since 1.2.0
	 * @return array The array of the message and type
	 */
	public function vulnerable_plugins_check() {
		$vulnerable_plugins = array();

		// Makes sure the plugin functions are active.
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		// Gets our list of plugins.
		$plugins = array_keys( get_plugins() );

		// Cycles through the plugins.
		foreach ( $plugins as $key => $plugin ) {
			$slug        = explode( '/', $plugin );
			$plugin_data = get_transient( 'wphc_vunlerability_check_' . $slug[0] );

			// Checks if our transient existed already. If not, get data from the API and store it in a transient.
			if ( false === $plugin_data ) {
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
		}
		if ( ! empty( $vulnerable_plugins ) ) {
			$plugin_list = implode( ',', $vulnerable_plugins );
			return $this->prepare_array( "The following plugins have known security vulnerabilities that have not been fixed in an update: $plugin_list. Please reach out to the developer immediately to ensure these vulnerabilities are being patched. If not, you must find alternatives to these plugins.", 'bad' );
		} else {
			return $this->prepare_array( 'Great! None of your plugins have known security vulnerabilities!', 'good' );
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
			return $this->prepare_array( "Your site does not have a user 'admin'. Great job!", 'good' );
		} else {
			return $this->prepare_array( "There is a user 'admin' on your site. Hackers use this username when trying to gain access to your site. Please change this username to something else.", 'good' );
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
			return $this->prepare_array( "You are not using the latest version of these themes: $updates. These updates could contain important security updates. Please update your themes to ensure your site is secure and safe.", 'bad' );
		} else {
			return $this->prepare_array( 'All of your WordPress themes are up to date. Great Job!', 'good' );
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

		if ( $mariadb ) {
			switch ( intval( $version_array[0] ) ) {
				case 5:
					return $this->prepare_array( sprintf( $maria_version, $version ) . ' This is below the recommended version of 10.0. You should consider updating your MariaDB or contacting your host right away.', 'bad' );
					break;
				case 10:
					return $this->prepare_array( sprintf( $maria_version, $version ) . ' Good job! This is the recommended version.', 'good' );
					break;

				default:
					return $this->prepare_array( $error, 'bad' );
					break;
			}
		} else {
			switch ( intval( $version_array[0] ) ) {
				case 4:
					return $this->prepare_array( sprintf( $mysql_version, $version ) . " This has not been supported in over 5 years and is below the required 5.0. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You must update your MySQL or contact your host immediately!", 'bad' );
					break;

				case 5:
					switch ( intval( $version_array[1] ) ) {
						case 0:
							return $this->prepare_array( sprintf( $mysql_version, $version ) . " This version has not been supported in 2 years and is below the recommended 5.6. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host right away.", 'bad' );
							break;

						case 1:
							return $this->prepare_array( sprintf( $mysql_version, $version ) . " This version is no longer supported and below the recommended 5.6. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host.", 'bad' );
							break;

						case 5:
							return $this->prepare_array( sprintf( $mysql_version, $version ) . " This version is below the recommended 5.6. You should consider updating your MySQL or contacting your host.", 'bad' );
							break;

						case 6:
							return $this->prepare_array( sprintf( $mysql_version, $version ) . " Good job! This is the recommended version.", 'good' );
							break;

						case 7:
							return $this->prepare_array( sprintf( $mysql_version, $version ) . " Good job! This is a supported version and is above the recommended version.", 'good' );
							break;

						default:
							return $this->prepare_array( $error, 'bad' );
							break;
						}
					break;

				case 8:
					return $this->prepare_array( sprintf( $mysql_version, $version ) . " This is the latest version!", 'good' );
					break;

				default:
					return $this->prepare_array( $error, 'bad' );
					break;
			}
		}
	}

	/**
	 * Checks if the site is using SSL
	 *
	 * @since 1.4.0
	 */
	public function ssl_check() {
		if ( is_ssl() ) {
			return $this->prepare_array( 'Great! You are using SSL on your site.', 'good' );
		} else {
			return $this->prepare_array( 'Your site is not using SSL. This is insecure and is hurting your SEO ranking too. Contact your host about SSL.', 'bad' );
		}
	}

	/**
	 * Checks if using latest version of php
	 *
	 * @since 0.1.0
	 */
	public function php_check() {
		$version          = explode( '.', PHP_VERSION );
		$php_check_health = 'good';
		$message          = '';

		$your_version_message = 'You server is running PHP version ' . PHP_VERSION;
		$unsupported_message  = 'Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. Also, newer versions are faster which makes your site load faster. You must update your PHP or contact your host immediately!';
		switch ( intval( $version[0] ) ) {
			case 4:
				return $this->prepare_array( "$your_version_message which has not been supported since Aug 2008 and is below the required 5.2. $unsupported_message", 'bad' );
				break;

			case 5:
				switch ( intval( $version[1] ) ) {
					case 0:
						return $this->prepare_array( "$your_version_message which has not been supported since Sep 2005 and is below the required 5.2. $unsupported_message", 'bad' );
						break;

					case 1:
						return $this->prepare_array( "$your_version_message which has not been supported since Aug 2006 and is below the required 5.2. $unsupported_message", 'bad' );
						break;

					case 2:
						return $this->prepare_array( "$your_version_message which has not been supported since Jan 2011 and is below the recommended 7.2. $unsupported_message", 'bad' );
						break;

					case 3:
						return $this->prepare_array( "$your_version_message which has not been supported since Aug 2014 and is below the recommended 7.2. $unsupported_message", 'bad' );
						break;

					case 4:
						return $this->prepare_array( "$your_version_message which has not been supported since Sep 2015 and is below the recommended 7.2. $unsupported_message", 'bad' );
						break;

					case 5:
						return $this->prepare_array( "$your_version_message which has not been supported since Jul 2016 and is below the recommended 7.2. $unsupported_message", 'bad' );
						break;

					case 6:
						return $this->prepare_array( "$your_version_message which has not been actively supported since Jan 2017 and is below the recommended 7.2. $unsupported_message", 'okay' );
						break;

					default:
						return $this->prepare_array( "Error checking PHP health.", 'bad' );
						break;
				}
				break;

			case 7:
				switch ( intval( $version[1] ) ) {
					case 0:
						return $this->prepare_array( "$your_version_message which has not been actively supported since Dec 2017 and is below the recommended 7.2", 'okay' );
						break;

					case 1:
						return $this->prepare_array( "$your_version_message. Good job! While this is not the recommended 7.2, this version is still actively supported until Dec 2018. Be sure to check with your host to make sure they have a plan to update to 7.2.", 'good' );
						break;

					case 2:
						return $this->prepare_array( "$your_version_message. Good job! This is the latest version.", 'good' );
						break;

					default:
						return $this->prepare_array( 'Error checking PHP health.', 'bad' );
						break;
				}
				break;

			default:
				return $this->prepare_array( 'Error checking PHP health.', 'bad' );
				break;
		}
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
