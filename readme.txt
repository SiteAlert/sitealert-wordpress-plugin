=== WP Health (Formerly My WP Health Check) ===
Contributors: fpcorso
Tags: php, mysql, plugin, version, security, vulnerable, vulnerability, inactive, update
Requires at least: 4.9
Tested up to: 5.4
Stable tag: 1.8.11
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

**Accessibility Monitoring**
WP Health will scan your site an alert you of potential accessibility concerns.

**Email Notifications**
Get notified when a vulnerability is found in a plugin you have installed. Receive summaries for all of your sites.

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

= 1.8.11 (April 27, 2020) =
* Ensures WP Health works with the latest WordPress version

= 1.8.10 (February 28 , 2020) =
* Temporarily disables vulnerability checker to due changes with 3rd party API
* Bumps tested WordPress version to recent version

= 1.8.9 (Sept 10, 2019) =
* Bumps minimum WordPress version to 4.9 which is 2 years old
* Makes minor styling changes
* Adjusts review message

= 1.8.8 (July 25, 2019) =
* Adds link for more information about blacklists

= 1.8.7 (July 16, 2019) =
* Temporarily turns off review message

= 1.8.6 (June 21, 2019) =
* Sorts the checks with failed checks at top of each section

= 1.8.5 (June 1, 2019) =
* Changes text info for the API Key setting
* Makes minor design changes

= 1.8.4 (May 25, 2019) =
* Increases spam comment threshold to 50 for check to fail
* Fixes minor capitalization and grammar mistakes
* Reduces refresh time for admin bar count from 1 hour to 45 minutes

= 1.8.3 (May 16, 2019) =
* Closed Bug: Failed admin username check shows green ([Issue #112](https://github.com/fpcorso/wordpress-health-check/issues/112))

= 1.8.2 (May 8, 2019) =
* Ensures compatibility with WordPress 5.2

= 1.8.1 (April 16, 2019) =
* Increases number of spam messages needed to trigger check to fail

= 1.8.0 (April 1, 2019) =
* Closed Enhancement: Check if site is discouraging search engines ([Issue #105](https://github.com/fpcorso/wordpress-health-check/issues/105))

For the rest of the changelog, [review our CHANGELOG.MD](https://github.com/fpcorso/wordpress-health-check/blob/master/CHANGELOG.MD)!

== Upgrade Notice ==

= 1.8.0 =
This update adds a new check!