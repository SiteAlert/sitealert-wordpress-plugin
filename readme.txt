=== WP Health (Formerly My WP Health Check) ===
Contributors: fpcorso
Tags: php, uptime, plugin, version, security, update
Requires at least: 5.1
Tested up to: 5.5
Stable tag: 1.8.15
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

**Moz Domain Authority Monitoring**
Monitor changes in your site's Moz Domain Authority.

**Broken Link Monitoring**
Discover any broken links on your site before your visitors do.

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
Receive summaries for all your sites including any warnings, broken links, and more!

[Learn more about our premium plan!](https://wphealth.app)

= Make Suggestions Or Contribute =
WP Health [is on GitHub](https://github.com/fpcorso/wordpress-health-check)!

== Frequently Asked Questions ==

= Why do I need an uptime monitor? =
If your site were to go down right now, how many visitors and customers would you miss out on until you discovered your site was down? With uptime monitoring, you are immediately notified when your site goes down so you can look into and keep track of how often your site goes down.

Also, if you host guarantees 99% uptime, that could still mean 35 hours where your site is down each year. At 99.9%, that's still over 3 hours your site is down each year. Making sure your hosting provider is staying within their guarantee is important.

== Screenshots ==

1. Admin Page

== Changelog ==

= 1.8.15 (Sept 22, 2020) =
* Bump minimum WordPress version to 5.1, which is 18 months old
* Fix bug in telemetry opt-in causing multiple requests on sites that opted-in

= 1.8.14 (Sept 6, 2020) =
* Fix error notice relating to permission callback in REST API
* Move all api key authentication in REST API to new permission callback
* Added new upsell message

= 1.8.13 (August 31, 2020) =
* Modify telemtry opt-in
* Add info about email summary premium feature

= 1.8.12 (August 5, 2020) =
* Ensures WP Health works with WordPress 5.5

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

For the rest of the changelog, [review our CHANGELOG.MD](https://github.com/fpcorso/wordpress-health-check/blob/master/CHANGELOG.MD)!

== Upgrade Notice ==

= 1.8.15 =
Keep WP Health up to date to ensure all of your checks are running smoothly.