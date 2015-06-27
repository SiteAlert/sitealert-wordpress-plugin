<?php
/**
 * Class To Display Admin Page
 *
 * @since 0.1.0
 */
class MLWWpHcAdmin
{
  /**
   * Main construct
   *
   * @since 0.1.0
   */
  function __construct()
  {
    $this->load_dependencies();
    $this->load_hooks();
  }

  /**
   * Load File Dependencies
   *
   * @since 0.1.0
   */
  private function load_dependencies()
  {
    ///None yet
  }

  /**
   * Adds Plugin's Functions To WordPress Hooks
   */
  private function load_hooks()
  {
    add_action('admin_menu', array($this, 'setup_admin_page'));
  }

  /**
   * Creates the menu for the plugin
   *
   * @since 0.1.0
   */
  public function setup_admin_page()
  {
    add_management_page('WordPress Health Check', __('Health Check', 'my-wp-health-check'), 'moderate_comments', 'wp-health-check', array($this, 'settings_page'));
  }

  /**
   * Function to display the admin page
   *
   * @since 0.1.0
   */
  public function settings_page()
  {
    wp_enqueue_style('wp-hc-style', plugins_url("../css/main.css", __FILE__));
    ?>
    <div class="wrap">
  	   <h2>WordPress Health Check</h2>
       <p>If you feel that your website has benefited from this plugin, please help other users find this plugin by <a href="https://wordpress.org/support/view/plugin-reviews/my-wp-health-check">leaving a review</a>.</p>
       <hr />
       <h3>Server Check</h3>
       <?php
       $this->php_check();
       $this->mysql_check();
       ?>
       <h3>WordPress Check</h3>
       <?php
       $this->wordpress_version_check();
       $this->plugins_check();
       $this->supported_plugin_check();
       $this->themes_check();
       ?>
    </div>
    <?php
  }

  /**
   * Checks if using latest version of WordPress
   *
   * @since 0.1.0
   */
  public function wordpress_version_check()
  {
    $core_update = get_core_updates();
    if ( !isset($updates[0]->response) || 'latest' == $updates[0]->response )
    {
      echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span>Your WordPress is up to date. Great Job!</div>";
    }
    else
    {
      echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>Your WordPress is not up to date. Your site has not received the latest security fixes and is less secure from hackers. Please consider updating.</div>";
    }
  }

  /**
   * Checks if using latest version of plugins
   *
   * @since 0.2.0
   */
  public function plugins_check()
  {
    $plugin_updates = get_plugin_updates();
    if(!empty($plugin_updates))
    {
      $plugins = array();
      foreach ($plugin_updates as $plugin)
      {
        $plugins[] = $plugin->Name;
      }
      $plugin_list = implode(",", $plugins);
      echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>You are not using the latest version of these plugins: $plugin_list. These updates could contain important security updates. Please update your plugins to ensure your site is secure and safe.</div>";
    }
    else
    {
      echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span>All of your WordPress plugins are up to date. Great Job!</div>";
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
      if ( time() - ( 60*60*24*365*2 ) > strtotime($plugin_info->last_updated) ) {
        $unsupported_plugins[] = $plugin_info->name;
      }
    }

    $plugin_list = implode(",", $unsupported_plugins);
    if ( empty( $unsupported_plugins ) ) {
      echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span>All of your plugins are currently supported. Great Job!</div>";
    } else {
      echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>The following plugins are no longer supported by their developer: $plugin_list. There could be security issues that will not be fixed! Please look for alternatives and uninstall these plugins.</div>";
    }
  }

  /**
   * Checks if using latest version of themes
   *
   * @since 0.2.0
   */
  public function themes_check()
  {
    $theme_updates = get_theme_updates();
    if(!empty($theme_updates))
    {
      $themes = array();
      echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>One or more of your themes have updates available. These updates could contain important security updates. Please update your plugins to ensure your site is secure and safe.</div>";
    }
    else
    {
      echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span>All of your WordPress themes are up to date. Great Job!</div>";
    }
  }

  /**
   * Checks if using latest version of mysql
   *
   * @since 0.1.0
   */
  public function mysql_check()
  {
    //Check for MySQL
    global $wpdb;
    $version = explode('.', $wpdb->db_version());
    $sql_check_health = 'good';
    $message = '';
    switch (intval($version[0])) {
      case 4:
        $sql_check_health = 'bad';
        $message = "You server is running MySQL version ".$wpdb->db_version()." which has not been supported in over 5 years and is below the required 5.0. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You must update your MySQL or contact your host immediately!";
        break;

      case 5:
        switch (intval($version[1])) {
          case 0:
            $sql_check_health = 'okay';
            $message = "You server is running MySQL version ".$wpdb->db_version().". This is the bare minimum that WordPress requires. However, this version has not been supported in 2 years and is below the recommended 5.5. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host.";
            break;

          case 1:
            $sql_check_health = 'okay';
            $message = "You server is running MySQL version ".$wpdb->db_version().". This is above the bare minimum that WordPress requires. However, this version is not longer supported and below the recommended 5.5. Using an unsupported version of MySQL means that you are using a version that no longer receives important security updates and fixes. You should consider updating your MySQL or contacting your host.";
            break;

          case 5:
            $sql_check_health = 'good';
            $message = "You server is running MySQL version ".$wpdb->db_version().". Good job! This is the recommended version.";
            break;

          case 6:
            $sql_check_health = 'good';
            $message = "You server is running MySQL version ".$wpdb->db_version().". Good job! This is above the recommended version.";
            break;

          case 7:
            $sql_check_health = 'good';
            $message = "You server is running MySQL version ".$wpdb->db_version().". Good job! This is the latest version.";
            break;


          default:
            $sql_check_health = 'bad';
            $message = "Error checking MySQL health.";
            break;
        }
        break;

      default:
        $sql_check_health = 'bad';
        $message = "Error checking MySQL health.";
        break;
    }
    switch ($sql_check_health) {
      case 'good':
        echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span>$message</div>";
        break;

      case 'okay':
        echo "<div class='wp-hc-okay-box'><span class='dashicons dashicons-lightbulb'></span>$message</div>";
        break;

      case 'bad':
        echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>$message</div>";
        break;

      default:
        echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>Error checking PHP health.</div>";
        break;
    }
  }

  /**
   * Checks if using latest version of php
   *
   * @since 0.1.0
   */
  public function php_check()
  {
    $version = explode('.', PHP_VERSION);
    $php_check_health = 'good';
    $message = '';
    switch (intval($version[0])) {
      case 4:
        $php_check_health = 'bad';
        $message = "You server is running PHP version ".PHP_VERSION." which has not been supported in over 5 years and is below the required 5.2. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You must update your PHP or contact your host immediately!";
        break;

      case 5:
        switch (intval($version[1])) {
          case 0:
            $php_check_health = 'bad';
            $message = "You server is running PHP version ".PHP_VERSION." which has not been supported in almost 10 years and is below the required 5.2. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You must update your PHP or contact your host immediately!";
            break;

          case 1:
            $php_check_health = 'bad';
            $message = "You server is running PHP version ".PHP_VERSION." which has not been supported in almost 10 years and is below the required 5.2. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You must update your PHP or contact your host immediately!";
            break;

          case 2:
            $php_check_health = 'okay';
            $message = "You server is running PHP version ".PHP_VERSION.". This is the bare minimum requirement of WordPress. However, this version has not been supported in almost 5 years and is below the recommended 5.5. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You should consider updating your PHP or contact your host.";
            break;

          case 3:
            $php_check_health = 'okay';
            $message = "You server is running PHP version ".PHP_VERSION.". This is the above the bare minimum requirement of WordPress. However, this version has not been supported in almost 12 months and is below the recommended 5.5. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You should consider updating your PHP or contact your host.";
            break;

          case 4:
            $php_check_health = 'okay';
            $message = "You server is running PHP version ".PHP_VERSION.". This is the above the bare minimum requirement of WordPress. However, this version will no longer be supported as of September 2015. Check with your host to ensure they plan on updating before this version is no longer supported.";
            break;

          case 5:
            $php_check_health = 'good';
            $message = "You server is running PHP version ".PHP_VERSION.". Good job! This is the recommended version.";
            break;

          case 6:
            $php_check_health = 'good';
            $message = "You server is running PHP version ".PHP_VERSION.". Good job! This is the latest version.";
            break;

          default:
            $php_check_health = 'bad';
            $message = "Error checking PHP health.";
            break;
        }
        break;

      default:
        $php_check_health = 'bad';
        $message = "Error checking PHP health.";
        break;
    }
    switch ($php_check_health) {
      case 'good':
        echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span>$message</div>";
        break;

      case 'okay':
        echo "<div class='wp-hc-okay-box'><span class='dashicons dashicons-lightbulb'></span>$message</div>";
        break;

      case 'bad':
        echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>$message</div>";
        break;

      default:
        echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>Error checking PHP health.</div>";
        break;
    }
  }
}

$mlw_wp_hc_admin = new MLWWpHcAdmin();
?>
