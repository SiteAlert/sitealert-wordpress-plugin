=== My WordPress Health Check ===
Contributors: fpcorso
Tags: php, mysql, plugin, theme, version, recommended, security, vulnerable, vulnerability, inactive, update
Requires at least: 4.5
Tested up to: 4.8
Stable tag: 1.4.3
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin checks the health of your WordPress installation.

== Description ==

My WordPress Health Check will check your WordPress installation to ensure that it is healthy, up to date, and secure. Use the Health Check page to quickly see the results of these checks using the simple color-coded sections.

Once installed, there will be a new **Health Check** page added to the Tools menu and an icon to your admin bar if the results of these checks need attention.

= Currently Checks =

**WordPress Version**
This plugin checks to make sure that your site is using the latest version of WordPress.

**MySQL Version**
This plugin checks to make sure that your server is using a recent version of MySQL.

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

= Make Suggestions Or Contribute =
My WordPress Health Check is on [GitHub](https://github.com/fpcorso/wordpress-health-check)!

== Installation ==

* Navigate to Add New Plugin page within your WordPress
* Search for My WordPress Health Check
* Click Install Now link on the plugin and follow the prompts
* Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Admin Page

== Changelog ==

= 1.4.3 (October 29, 2017) =
* Fixes "Call to undefined function get_core_updates()" error
* Fixes bug that occurs when a plugin is not in the WordPress plugin directory

= 1.4.2 (May 25, 2017) =
* Minor code changes

= 1.4.0 (January 27, 2017) =
* Adds SSL check
* Adds disabled file editor check
* Updates text information for PHP and MySQL checks

= 1.3.3 (December 9, 2016) =
* Ensures stability in WordPress 4.7

= 1.3.2 (November 2, 2016) =
* Updates PHP check information
* Minor design changes

= 1.3.1 (June 3, 2016) =
* Fixes major bug causing admin bar to error when viewed from front-end of website

= 1.3.0 (May 25, 2016) =
* Adds new icon to admin bar when there are failed checks
* Moves the checks into their own class
* Moves the call for checks into AJAX to speed up the Health Check page
* Adds new filters for extending the plugin with your own checks
* Adds uninstall.php file for uninstall routine

= 1.2.1 (January 30, 2016) =
* Minor design changes
* Adds new data tracking
* Adds new security email course optin

= 1.2.0 (December 31, 2015) =
* Adds new check for vulnerable plugins
* Updates text for PHP and MySQL checks
* Abstracts messages to own function
* Adds transient for supported plugin check
* Minor design changes

= 1.1.2 (December 17, 2015) =
* Minor design changes
* Fixes bug in review message

= 1.1.1 (December 5, 2015) =
* Adds check for inactive plugins
* Adds check for 'admin' username
* Added dev hooks for adding checks
* Minor style changes
* Minor text changes

= 1.0.1 (August 19, 2015) =
* Ensure compatibility with 4.3
* Minor design changes

= 1.0.0 (June 29, 2015) =
* Out of Beta!
* Adds check to see if installed plugins are still supported by their developer [GitHub Issue #3](https://github.com/fpcorso/wordpress-health-check/issues/3)
* Bug Fix: fixes bug that prevented the WordPress update check from working on select sites
* Design: Updated PHP check text

= 0.2.1 (April 22, 2015) =
* Updated version number to reflect compatibility with WordPress version 4.2

= 0.2.0 (February 26, 2015) =
* Added Ability To Check If Plugins Need Updates [Issue #2](https://github.com/fpcorso/wordpress-health-check/issues/2)
* Added Ability To Check If Themes Need Updates [Issue #4](https://github.com/fpcorso/wordpress-health-check/issues/4)
* In Code: Fixed WordPress Version Check To Automatically Know The Latest Version [Issue #5](https://github.com/fpcorso/wordpress-health-check/issues/5)

= 0.1.0 (February 11, 2015) =
* Begun Development

== Upgrade Notice ==

= 1.4.0 =
This update adds two new checks and updates two others
