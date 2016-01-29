<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class To Display Admin Page
 *
 * @since 0.1.0
 */
class WPHC_Admin {
  /**
   * Main construct
   *
   * @since 0.1.0
   */
  function __construct() {
    $this->load_dependencies();
    $this->load_hooks();
  }

  /**
   * Load File Dependencies
   *
   * @since 0.1.0
   */
  private function load_dependencies() {
    ///None yet
  }

  /**
   * Adds Plugin's Functions To WordPress Hooks
   */
  private function load_hooks() {
    add_action( 'admin_menu', array( $this, 'setup_admin_page' ) );
  }

  /**
   * Creates the menu for the plugin
   *
   * @since 0.1.0
   */
  public function setup_admin_page() {
    add_management_page( 'WordPress Health Check', __( 'Health Check', 'my-wp-health-check' ), 'moderate_comments', 'wp-health-check', array( $this, 'settings_page' ) );
  }

  /**
   * Function to display the admin page
   *
   * @since 0.1.0
   */
  public function settings_page() {
    if ( ! current_user_can('moderate_comments') ) {
  		return;
  	}
    wp_enqueue_style( 'wp-hc-style', plugins_url( "../css/main.css", __FILE__ ) );
    ?>
    <div class="wrap wphc-wrap">
      <div class="wphc-main-content">
  	   <h2>WordPress Health Check</h2>
       <p>If you feel that your website has benefited from this plugin, please help other users find this plugin by <a href="https://wordpress.org/support/view/plugin-reviews/my-wp-health-check">leaving a review</a>.</p>
       <hr />
       <h3>Server Check</h3>
       <?php
       $this->php_check();
       $this->mysql_check();
       do_action( 'wphc_server_check' );
       ?>
       <h3>WordPress Check</h3>
       <?php
       $this->wordpress_version_check();
       $this->admin_user_check();
       $this->themes_check();
       do_action( 'wphc_wordpress_check' );
       ?>
       <h3>Plugin Check</h3>
       <?php
       $this->update_plugins_check();
       $this->inactive_plugins_check();
       $this->supported_plugin_check();
       $this->vulnerable_plugins_check();
       do_action( 'wphc_plugin_check' );
       ?>
     </div>
     <div class="wphc-news-ads">
       <h3 class="wphc-news-ads-title">My WordPress Health Check</h3>
       <div class="wphc-news-ads-widget">
         <h3>Sign up for our FREE WordPress security email course.</h3>
         <p>Sign up to our free 6 day WordPress security course to learn the basics of WordPress security.</p>
         <a target="_blank" href="http://mylocalwebstop.com/sign-up-for-our-free-security-course/" class="button-primary">Sign up Now</a>
       </div>
     </div>
    </div>
    <?php
  }

  /**
   * Echos out a message in the pre-defined template
   *
   * @since 1.2.0
   * @param string $message The message to be displayed in the message box
   * @param string $type The type of message box to be displayed. Available types are 'good', 'okay', and 'bad'
   * @return void
   */
  public function print_message( $message, $type = 'good' ) {
    switch ( $type ) {
      case 'good':
        echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span> $message</div>";
        break;

      case 'okay':
        echo "<div class='wp-hc-okay-box'><span class='dashicons dashicons-lightbulb'></span> $message</div>";
        break;

      case 'bad':
        echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span> $message</div>";
        break;

      default:
        echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span> $message</div>";
        break;
    }
  }

  /**
   * Checks if using latest version of WordPress
   *
   * @since 0.1.0
   */
  public function wordpress_version_check() {
    $core_update = get_core_updates();
    if ( ! isset( $updates[0]->response ) || 'latest' == $updates[0]->response ) {
      $this->print_message('Your WordPress is up to date. Great Job!', 'good');
    } else {
      $this->print_message('Your WordPress is not up to date. Your site has not received the latest security fixes and is less secure from hackers. Please consider updating.', 'bad');
    }
  }

  /**
   * Checks if using latest version of plugins
   *
   * @since 0.2.0
   */
  public function update_plugins_check() {
    $plugin_updates = get_plugin_updates();
    if( ! empty( $plugin_updates ) ) {
      $plugins = array();
      foreach ( $plugin_updates as $plugin ) {
        $plugins[] = $plugin->Name;
      }
      $plugin_list = implode( ",", $plugins );
      $this->print_message("You are not using the latest version of these plugins: $plugin_list. These updates could contain important security updates. Please update your plugins to ensure your site is secure and safe.", 'bad');
    } else {
      $this->print_message('All of your WordPress plugins are up to date. Great Job!', 'good');
    }
  }

  /**
   * Checks for inactive plugins
   *
   * @since 1.1.0
   */
  public function inactive_plugins_check() {

    // Gets all the plugins
    $plugins = get_plugins();
    $inactive_plugins = array();

    // Looks for any inactive plugins
    if ( ! empty( $plugins ) ) {
      foreach ( $plugins as $key => $plugin ) {
        if ( is_plugin_inactive( $key ) ) {
          $inactive_plugins[] = $plugin['Name'];
        }
      }
    }

    // If any plugins are inactive, display error message. If not, display success message
    if ( ! empty( $inactive_plugins ) ) {
      $this->print_message("These plugins are not active: " . implode( ', ', $inactive_plugins ) . ". Inactive plugins can still be compromised by hackers. If you are not using them, please uninstall them.", 'bad');
    } else {
      $this->print_message('All of your plugins installed on the site are in use. Great job!', 'good');
    }

  }

  /**
   * Checks For Unsupported Plugins
   *
   * Checks the installed plugins to see there is a plugin that hasn't been updated in over 2 years
   *
   * @since 1.0.0
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
      foreach ($slugs as $plugin) {
        $response = wp_remote_get( "http://api.wordpress.org/plugins/info/1.0/$plugin" );
        $plugin_info = unserialize( $response['body'] );
        if ( time() - ( 60 * 60 * 24 * 365 * 2 ) > strtotime( $plugin_info->last_updated ) ) {
          $unsupported_plugins[] = $plugin_info->name;
        }
      }
      $plugin_list = implode(",", $unsupported_plugins);
      set_transient( 'wphc_supported_plugin_check', $plugin_list, 1 * DAY_IN_SECONDS );
    }
    if ( empty( $plugin_list ) ) {
      $this->print_message('All of your plugins are currently supported. Great Job!', 'good');
    } else {
      $this->print_message("The following plugins have not been updated in over two years which indicate that they are no longer supported by their developer: $plugin_list. There could be security issues that will not be fixed! Please reach out to the developers to ensure these plugins are still supported or look for alternatives and uninstall these plugins.", 'bad');
    }
  }

  /**
   * Checks for vunlerable plugins using wpvulndb.com's api
   *
   * @since 1.2.0
   * @return void
   */
  public function vulnerable_plugins_check() {
    $vulnerable_plugins = array();

    // Makes sure the plugin functions are active
    if( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

    // Gets our list of plugins
		$plugins = array_keys( get_plugins() );

    // Cycles through the plugins
    foreach ( $plugins as $key => $plugin ) {
      $slug = explode( '/', $plugin );
      $plugin_data = get_transient( 'wphc_vunlerability_check_' . $slug[0] );

      // Checks if our transient existed already. If not, get data from the API and store it in a transient
      if ( false === $plugin_data ) {
        $response = wp_remote_get( "https://wpvulndb.com/api/v2/plugins/" . $slug[0] );
        if ( ! is_wp_error( $response ) ) {
          $data = wp_remote_retrieve_body( $response );
          if ( ! is_wp_error( $data ) ) {
            $plugin_data = $data;
            set_transient( 'wphc_vunlerability_check_'  .$slug[0], $plugin_data, 1 * DAY_IN_SECONDS );
          }
        }
      }

      // Checks to make sure the data has been retreived from transient or the api. Then, decodes the JSON data
      if ( false !== $plugin_data ) {
        $plugin_data = json_decode( $plugin_data, true );
        if ( is_array( $plugin_data ) ) {
          foreach ( $plugin_data as $plugin_name => $plugin_info ) {
            if ( ! empty( $plugin_info["vulnerabilities"] ) ) {

              // Cycles through the vulnerabilities checking to see if the vulnerability has not been fixed yet
              foreach ( $plugin_info["vulnerabilities"] as $vulnerability ) {
                if ( NULL === $vulnerability["fixed_in"] ) {
                  $vunlerable_plugins[] = $plugin_name;
                }
              }
            }
          }
        }
      }
    }
    if ( ! empty( $vulnerable_plugins ) ) {
      $plugin_list = implode(",", $vulnerable_plugins);
      $this->print_message("The following plugins have known security vulnerabilities that have not been fixed in an update: $plugin_list. Please reach out to the developer immediately to ensure these vulnerabilities are being patched. If not, you must find alternatives to these plugins.", 'bad');
    } else {
      $this->print_message("Great! None of your plugins have known security vulnerabilities!", 'good');
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
      $this->print_message("Your site does not have a user 'admin'. Great job!", 'good');
    } else {
      $this->print_message("There is a user 'admin' on your site. Hackers use this username when trying to gain access to your site. Please change this username to something else.", 'good');
    }
  }

  /**
   * Checks if using latest version of themes
   *
   * @since 0.2.0
   */
  public function themes_check() {
    $theme_updates = get_theme_updates();
    if( ! empty( $theme_updates ) ) {
      $this->print_message("One or more of your themes have updates available. These updates could contain important security updates. Please update your plugins to ensure your site is secure and safe.", 'bad');
    } else {
      $this->print_message("All of your WordPress themes are up to date. Great Job!", 'good');
    }
  }

  /**
   * Checks if using latest version of mysql
   *
   * @since 0.1.0
   */
  public function mysql_check() {
    //Check for MySQL
    global $wpdb;
    $version = explode( '.', $wpdb->db_version() );
    switch ( intval( $version[0] ) ) {
      case 4:
        $this->print_message("You server is running MySQL version " . $wpdb->db_version() . " which has not been supported in over 5 years and is below the required 5.0. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You must update your MySQL or contact your host immediately!", 'bad');
        break;

      case 5:
        switch ( intval( $version[1] ) ) {
          case 0:
            $this->print_message("You server is running MySQL version " . $wpdb->db_version() . ". This is the bare minimum that WordPress requires. However, this version has not been supported in 2 years and is below the recommended 5.6. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host right away.", 'bad');
            break;

          case 1:
            $this->print_message("You server is running MySQL version " . $wpdb->db_version() . ". This is above the bare minimum that WordPress requires. However, this version is no longer supported and below the recommended 5.6. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host.", 'bad');
            break;

          case 5:
            $this->print_message("You server is running MySQL version " . $wpdb->db_version() . ". This is above the bare minimum that WordPress requires. However, this version is below the recommended 5.6. You should consider updating your MySQL or contacting your host.", 'okay');
            break;

          case 6:
            $this->print_message("You server is running MySQL version " . $wpdb->db_version() . ". Good job! This is the recommended version.", 'good');
            break;

          case 7:
            $this->print_message("You server is running MySQL version " . $wpdb->db_version() . ". Good job! This is the latest version.", 'good');
            break;


          default:
            $this->print_message("Error checking MySQL health.", 'bad');
            break;
        }
        break;

      default:
        $this->print_message("Error checking MySQL health.", 'bad');
        break;
    }
  }

  /**
   * Checks if using latest version of php
   *
   * @since 0.1.0
   */
  public function php_check() {
    $version = explode( '.', PHP_VERSION );
    $php_check_health = 'good';
    $message = '';
    switch ( intval( $version[0] ) ) {
      case 4:
        $this->print_message("You server is running PHP version " . PHP_VERSION . " which has not been supported in over 5 years and is below the required 5.2. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You must update your PHP or contact your host immediately!", 'bad');
        break;

      case 5:
        switch ( intval( $version[1] ) ) {
          case 0:
            $this->print_message("You server is running PHP version " . PHP_VERSION . " which has not been supported in almost 10 years and is below the required 5.2. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You must update your PHP or contact your host immediately!", 'bad');
            break;

          case 1:
            $this->print_message("You server is running PHP version " . PHP_VERSION . " which has not been supported in almost 10 years and is below the required 5.2. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You must update your PHP or contact your host immediately!", 'bad');
            break;

          case 2:
            $this->print_message("You server is running PHP version " . PHP_VERSION . ". This is the bare minimum requirement of WordPress. However, this version has not been supported in almost 5 years and is below the recommended 5.5. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You should consider updating your PHP or contact your host.", 'bad');
            break;

          case 3:
            $this->print_message("You server is running PHP version " . PHP_VERSION . ". This is above the bare minimum requirement of WordPress. However, this version has not been supported in almost 12 months and is below the recommended 5.5. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You should consider updating your PHP or contact your host.", 'bad');
            break;

          case 4:
            $this->print_message("You server is running PHP version " . PHP_VERSION . ". This is above the bare minimum requirement of WordPress. However, this version has not been supported for almost 6 months and is below the recommeded 5.6. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You should consider updating your PHP or contact your host.", 'bad');
            break;

          case 5:
            $this->print_message("You server is running PHP version " . PHP_VERSION . ". This is above the bare minimum requirement of WordPress. However, this version has not had active support for 5 months and the security support will stop in July 2016. Check with your host to ensure they plan on updating before this version is no longer supported.", 'okay');
            break;

          case 6:
            $this->print_message("You server is running PHP version " . PHP_VERSION . ". Good job! This is the recommended version.", 'good');
            break;

          default:
            $this->print_message("Error checking PHP health.", 'bad');
            break;
        }
        break;

      case 7:
        $this->print_message("You server is running PHP version " . PHP_VERSION . ". Good job! This is the latest version.", 'good');
        break;

      default:
        $this->print_message("Error checking PHP health.", 'bad');
        break;
    }
  }
}

$wp_hc_admin = new WPHC_Admin();
?>
