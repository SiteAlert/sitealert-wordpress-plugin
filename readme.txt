=== WP Health (Formerly My WP Health Check) ===
Contributors: fpcorso
Tags: php, mysql, plugin, version, security, vulnerable, vulnerability, inactive, update
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.6.5
Requires PHP: 5.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Monitor the health and security of your WordPress site!

== Description ==

WP Health will check your WordPress installation to ensure that it is healthy, up to date, and secure. Use the WP Health page to quickly see the results of these checks using the simple color-coded sections.

Once installed, there will be a new **WP Health** page added to the Tools menu and heart icon to your admin bar if the results of these checks need attention.

= Currently Checks =

**WordPress Version**
This plugin checks to make sure that your site is using the latest version of WordPress.

**MySQL or MariaDB Version**
This plugin checks to make sure that your server is using a recent version of the database software.

**PHP Version**
This plugin checks to ensure that your server is running a version of PHP that is still receiving security updates.

**Plugin Updates**
This plugin checks to make sure all your plugins are up to date.

**Inactive Plugins**
This will check to ensure that you do not have any inactive plugins.

**Admin Username**
This check ensures that you do not have a user with the username of "admin" on your site.

**Plugins No Longer Being Supported**
This plugin checks to see if you have any plugins installed that are no longer supported by the developer.

**Plugins With Known Vulnerabilities**
This will check your plugins to see if you have a plugin installed with a known vulnerability that has not been fixed.

**Theme Updates**
This plugin checks to make sure all of your themes are up to date.

**SSL**
Checks to see if you have SSL on your site.

**File Editor**
Checks if your site has disabled the file editor.

**Optional REST API**
If you are a developer, this plugin integrates with the REST API. The endpoints can be enabled from the settings tab of the "WP Health" page in the tools menu.

= Make Suggestions Or Contribute =
WP Health [is on GitHub](https://github.com/fpcorso/wordpress-health-check)!

== Installation ==

* Navigate to Add New Plugin page within your WordPress
* Search for WP Health
* Click Install Now link on the plugin and follow the prompts
* Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Admin Page

== Changelog ==

= 1.6.5 (August 13, 2018) =
* Makes minor text changes

= 1.6.4 (July 30, 2018) =
* Makes minor text changes

= 1.6.3 (July 7, 2018) =
* Makes minor design and text changes
* Improves internationalization

= 1.6.2 (July 2, 2018) =
* Changes name of plugin to WP Health
* Fixes incorrect offest error
* Makes minor design and text changes

= 1.6.1 (June 25, 2018) =
* Fixes style issue with new settings tab

= 1.6.0 (June 24, 2018) =
* Closed Bug: Plugin page not showing all unsupported plugins ([Issue #59](https://github.com/fpcorso/wordpress-health-check/issues/59))
* Closed Enhancement: External endpoint to run check ([Issue #50](https://github.com/fpcorso/wordpress-health-check/issues/50))

= 1.5.1 (June 21, 2018) =
* Makes minor performance changes for sites with many plugins

= 1.5.0 (June 5, 2018) =
* Closed enhancement: Add alerts on plugins page ([Issue #26](https://github.com/fpcorso/wordpress-health-check/issues/26))
* Closed bug: Isn't compatible with MariaDB ([Issue #44](https://github.com/fpcorso/wordpress-health-check/issues/44))

For the rest of the changelog, [review our CHANGELOG.MD](https://github.com/fpcorso/wordpress-health-check/blob/master/CHANGELOG.MD)!

== Upgrade Notice ==

= 1.6.0 =
This update fixes a few bugs and adds an optional REST API endpoint