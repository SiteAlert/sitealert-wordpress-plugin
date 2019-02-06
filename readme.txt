=== WP Health (Formerly My WP Health Check) ===
Contributors: fpcorso
Tags: php, mysql, plugin, version, security, vulnerable, vulnerability, inactive, update
Requires at least: 4.7
Tested up to: 5.0
Stable tag: 1.7.5
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

**Spam Comments**
Checks to make sure you don't have too many spam comments.

**Optional REST API**
If you are a developer, this plugin integrates with the REST API. The endpoints can be enabled from the settings tab of the "WP Health" page in the tools menu.

= Need more? =
Check out our premium plans that includes:

**Central Dashboard**
See all of your checks from all of your sites in one place!

**Broken Images Monitoring**
Get notified if you have any broken images on your site.

**Uptime Monitoring**
Get notified immediately if your site goes down.

**Page Speed Monitoring**
Keep track of how fast your site is loading.

**Blacklist Monitoring**
WP Health will monitor dozens of lists and alert you if your site is blacklisted.

**Email Notifications**
Get notified when a vulnerability is found in a plugin you have installed. Receive weekly summaries for all of your sites.

[Learn more about our premium plan!](https://wphealth.app)

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

= 1.7.5 (February 6, 2019) =
* Improves internationalization

= 1.7.4 (January 22, 2019) =
* Adds info on new blacklist monitoring feature to readme

= 1.7.3 (December 28, 2018) =
* Modifies text for PHP check since two versions are ending support this month.

= 1.7.2 (December 8, 2018) =
* Ensures plugin is compatible with WordPress 5.0

= 1.7.1 (November 26, 2018) =
* Adds link to new article on why to update your site to relevant checks
* Adds info regarding new broken image monitoring in premium version to readme

= 1.7.0 (October 30, 2018) =
* Adds new spam comments check to advise users to delete spam comments
* Adds value key to JSON object in the REST API

For the rest of the changelog, [review our CHANGELOG.MD](https://github.com/fpcorso/wordpress-health-check/blob/master/CHANGELOG.MD)!

== Upgrade Notice ==

= 1.7.0 =
This update adds a new check and adds a value key to the REST API endpoints