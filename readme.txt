=== SiteAlert (Formerly WP Health) ===
Contributors: fpcorso
Tags: php, uptime, plugin, version, security, update
Requires at least: 5.3
Tested up to: 5.6
Stable tag: 1.8.17
Requires PHP: 5.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Monitor the health and security of your WordPress site!

== Description ==

SiteAlert will check your WordPress installation to ensure that it is healthy, up to date, and secure. Use the SiteAlert page to quickly see the results of these checks using the simple color-coded sections.

Once installed, there will be a new **SiteAlert** page added to the Tools menu and heart icon to your admin bar if the results of these checks need attention.

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
If you are a developer, this plugin integrates with the REST API. The endpoints can be enabled from the settings tab of the "SiteAlert" page in the tools menu.

= Need more? =
Check out our premium plans that includes:

**Central Dashboard**
See all of your checks from all of your sites in one place!

**Moz Domain Authority Monitoring**
Monitor changes in your site's Moz Domain Authority.

**Alexa Rank Monitoring**
Monitor changes in your site's Alexa Rank.

**Broken Link Monitoring**
Discover any broken links on your site before your visitors do.

**Broken Images Monitoring**
Get notified if you have any broken images on your site.

**Uptime Monitoring**
Get notified immediately if your site goes down.

**Page Speed Monitoring**
Keep track of how fast your site is loading.

**Blacklist Monitoring**
SiteAlert will monitor dozens of lists and alert you if your site is blacklisted.

**Accessibility Monitoring**
SiteAlert will scan your site an alert you of potential accessibility concerns.

**Email Notifications**
Receive summaries for all your sites including any warnings, broken links, and more!

[Learn more about our premium plan!](https://sitealert.io)

= Make Suggestions Or Contribute =
SiteAlert [is on GitHub](https://github.com/fpcorso/wordpress-health-check)!

== Frequently Asked Questions ==

= Why do I need an uptime monitor? =
If your site were to go down right now, how many visitors and customers would you miss out on until you discovered your site was down? With uptime monitoring, you are immediately notified when your site goes down so you can look into and keep track of how often your site goes down.

Also, if your host guarantees 99% uptime, that could still mean 35 hours where your site is down each year. At 99.9%, that's still over 3 hours your site is down each year. Making sure your hosting provider is staying within their guarantee is important.

= How does your broken link checker work? =
You may have come across many plugins that can scan for broken links or broken images. However, most of these run the scan from your server which uses up a lot of our server's resources. In fact, many of these plugins are banned on several managed hosting providers.

Instead, all of our broken link and broken image scanners run on our servers and scan over your site. Not only does this prevent your server from being strained, this also allows SiteAlert to catch some links that are created within plugins, such as form builders, that broken link checker plugins would miss.

== Screenshots ==

1. Admin Page

== Changelog ==

= 1.8.18 (Nov 23, 2020) =
* Bump minimum WordPress version to 5.3, which is 12 months old
* Bump tested to up to WordPress 5.6

= 1.8.17 (Oct 16, 2020) =
* Changes name to SiteAlert

= 1.8.16 (Oct 4, 2020) =
* Makes minor improvements to settings tab JavaScript
* Changes API endpoint for when adding sites to premium account

= 1.8.15 (Sept 22, 2020) =
* Bump minimum WordPress version to 5.1, which is 18 months old
* Fix bug in telemetry opt-in causing multiple requests on sites that opted-in

For the rest of the changelog, [review our CHANGELOG.MD](https://github.com/fpcorso/wordpress-health-check/blob/master/CHANGELOG.MD)!

== Upgrade Notice ==

= 1.8.18 =
Keep SiteAlert (formerly WP Health) up to date to ensure all of your checks are running smoothly.