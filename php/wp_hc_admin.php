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
    ///None
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
       <hr />
       <h3>Server Check</h3>
       <?php
       MLWWpHcAdmin::php_check();
       MLWWpHcAdmin::mysql_check();
       ?>
       <h3>WordPress Check</h3>
       <?php
       MLWWpHcAdmin::wordpress_version_check();
       ?>
    </div>
    <?php
  }

  public function wordpress_version_check()
  {
    if (get_bloginfo( 'version' ) == '4.1')
    {
      echo "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span>Your WordPress is up to date. Great Job!</div>";
    }
    else
    {
      echo "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span>Your WordPress is not up to date. Your site has not received the latest security fixes and is less secure from hackers. Please consider updating.</div>";
    }
  }

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
            $message = "You server is running PHP version ".PHP_VERSION.". This is the bare minimum requirement of WordPress. However, this version has not been supported in almost 5 years and is below the recommended 5.4. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You should consider updating your PHP or contact your host.";
            break;

          case 3:
            $php_check_health = 'okay';
            $message = "You server is running PHP version ".PHP_VERSION.". This is the above the bare minimum requirement of WordPress. However, this version has not been supported in almost 6 months and is below the recommended 5.4. Using an unsupported version of PHP means that you are using a version that no longer receives important security updates and fixes. You should consider updating your PHP or contact your host.";
            break;

          case 4:
            $php_check_health = 'okay';
            $message = "You server is running PHP version ".PHP_VERSION.". This is the minimum recommended version. Check with your host to ensure they plan on updating before this version is no longer supported in September 2015.";
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
?>
