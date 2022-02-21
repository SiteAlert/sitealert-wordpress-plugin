# SiteAlert WordPress Plugin Changelog

## 1.9.7 (Feb 20, 2022)

* Tested on WordPress 5.9

## 1.9.6 (Jan 8, 2022)

* Design improvements to checks and settings tabs
* Tested on WordPress 5.8.3

## 1.9.5 (October 26, 2021)

* Minor design tweaks

## 1.9.4 (October 20, 2021)

* Bump tested to up to WordPress 5.8

## 1.9.3 (March 14, 2021)

* Bump tested to up to WordPress 5.7
* Minor design tweaks

## 1.9.2 (December 21, 2020)

* Minor text changes
* Adds second screenshot

## 1.9.1 (December 7, 2020)

* Rewrite database version check to be easier to add new versions as they are released
* Add the database app name (MySQL or MariaDB) to REST API endpoint
* Add PHP 8.0 to PHP check
* Add new continuous deployment for plugin readme
* Increases spam comment threshold to 150 for check to fail

## 1.9.0 (Nov 23, 2020)

* Adds a new endpoint for upcoming email deliverability monitor
* Bump minimum WordPress version to 5.3, which is 12 months old
* Bump tested to up to WordPress 5.6
* Bump minimum PHP version to 5.6 to match WordPress

## 1.8.17 (Oct 16, 2020)

* Changes name to SiteAlert

## 1.8.16 (Oct 4, 2020)

* Makes minor improvements to settings tab JavaScript
* Changes API endpoint for when adding sites to premium account

## 1.8.15 (Sept 22, 2020)

* Bump minimum WordPress version to 5.1, which is 18 months old
* Fix bug in telemetry opt-in causing multiple requests on sites that opted-in

## 1.8.14 (Sept 6, 2020)

* Fix error notice relating to permission callback in REST API
* Move all api key authentication in REST API to new permission callback
* Added new upsell message

## 1.8.13 (August 31, 2020)

* Modify telemtry opt-in
* Add info about email summary premium feature

## 1.8.12 (August 5, 2020)

* Ensures WP Health works with WordPress 5.5

## 1.8.11 (April 27, 2020)

* Ensures WP Health works with the latest WordPress version

## 1.8.10 (February 28 , 2020)

* Temporarily disables vulnerability checker to due changes with 3rd party API
* Bumps tested WordPress version to recent version

## 1.8.9 (Sept 10, 2019)

* Bumps minimum WordPress version to 4.9 which is 2 years old
* Makes minor styling changes
* Adjusts review message

## 1.8.8 (July 25, 2019)

* Adds link for more information about blacklists

## 1.8.7 (July 16, 2019)

* Temporarily turns off review message

## 1.8.6 (June 21, 2019)

* Sorts the checks with failed checks at top of each section

## 1.8.5 (June 1, 2019)

* Changes text info for the API Key setting
* Makes minor design changes

## 1.8.4 (May 25, 2019)

* Increases spam comment threshold to 50 for check to fail
* Fixes minor capitalization and grammar mistakes
* Reduces refresh time for admin bar count from 1 hour to 45 minutes

## 1.8.3 (May 16, 2019)

* Closed Bug: Failed admin username check shows green ([Issue #112](https://github.com/fpcorso/wordpress-health-check/issues/112))

## 1.8.2 (May 8, 2019)

* Ensures compatibility with WordPress 5.2

## 1.8.1 (April 16, 2019)

* Increases number of spam messages needed to trigger check to fail

## 1.8.0 (April 1, 2019)

* Closed Enhancement: Check if site is discouraging search engines ([Issue #105](https://github.com/fpcorso/wordpress-health-check/issues/105))

## 1.7.9 (March 22, 2019)

* Makes minor changes to text

## 1.7.8 (March 14, 2019)

* Fixes bug causing ads to still show for premium users

## 1.7.7 (March 9, 2019)

* Makes minor changes to text

## 1.7.6 (February 22, 2019)

* Ensures compatibility with WordPress 5.1

## 1.7.5 (February 6, 2019)

* Improves internationalization

## 1.7.4 (January 22, 2019)

* Adds info on new blacklist monitoring feature to readme

## 1.7.3 (December 28, 2018)

* Modifies text for PHP check since two versions are ending support this month.

## 1.7.2 (December 8, 2018)

* Ensures plugin is compatible with WordPress 5.0

## 1.7.1 (November 26, 2018)

* Adds link to new article on why to update your site to relevant checks
* Adds info regarding new broken image monitoring in premium version to readme

## 1.7.0 (October 30, 2018)

* Adds new spam comments check to advise users to delete spam comments
* Adds value key to JSON object in the REST API

## 1.6.11 (October 18, 2018)

* Fixes plugin update check in REST API

## 1.6.10 (October 14, 2018)

* Makes minor text changes

## 1.6.9 (September 13, 2018)

* Enhances WordPress version check to work on hosts modifying the get_core_updates() function

## 1.6.8 (September 7, 2018)

* Adds link to checks from plugins page
* Makes minor text changes
* Improves internationalization

## 1.6.7 (August 31, 2018)

* Adds info about new premium features

## 1.6.6 (August 23, 2018)

* Fixes undefined variable bug in check for MariaDB

## 1.6.5 (August 13, 2018)

* Makes minor text changes
* Changes permission level to administrator instead of editor to see the checks

## 1.6.4 (July 30, 2018)

* Makes minor text changes

## 1.6.3 (July 7, 2018)

* Makes minor design and text changes
* Improves internationalization

## 1.6.2 (July 2, 2018)

* Changes name of plugin to WP Health
* Fixes incorrect offest error
* Makes minor design and text changes

## 1.6.1 (June 25, 2018)

* Fixes style issue with new settings tab

## 1.6.0 (June 24, 2018)

* Closed Bug: Plugin page not showing all unsupported plugins ([Issue #59](https://github.com/fpcorso/wordpress-health-check/issues/59))
* Closed Enhancement: External endpoint to run check ([Issue #50](https://github.com/fpcorso/wordpress-health-check/issues/50))

## 1.5.1 (June 21, 2018)

* Makes minor performance changes for sites with many plugins

## 1.5.0 (June 5, 2018)

* Closed enhancement: Add alerts on plugins page ([Issue #26](https://github.com/fpcorso/wordpress-health-check/issues/26))
* Closed bug: Isn't compatible with MariaDB ([Issue #44](https://github.com/fpcorso/wordpress-health-check/issues/44))

## 1.4.4 (May 24, 2018)

* Fixes bug causes undefined function errors on the frontend when logged in

## 1.4.3 (October 29, 2017)

* Fixes "Call to undefined function get_core_updates()" error
* Fixes bug that occurs when a plugin is not in the WordPress plugin directory

## 1.4.2 (May 25, 2017)

* Minor code changes

## 1.4.0 (January 27, 2017)

* Adds SSL check
* Adds disabled file editor check
* Updates text information for PHP and MySQL checks

## 1.3.3 (December 9, 2016)

* Ensures stability in WordPress 4.7

## 1.3.2 (November 2, 2016)

* Updates PHP check information
* Minor design changes

## 1.3.1 (June 3, 2016)

* Fixes major bug causing admin bar to error when viewed from front-end of website

## 1.3.0 (May 25, 2016)

* Adds new icon to admin bar when there are failed checks
* Moves the checks into their own class
* Moves the call for checks into AJAX to speed up the Health Check page
* Adds new filters for extending the plugin with your own checks
* Adds uninstall.php file for uninstall routine

## 1.2.1 (January 30, 2016)

* Minor design changes
* Adds new data tracking
* Adds new security email course optin

## 1.2.0 (December 31, 2015)

* Adds new check for vulnerable plugins
* Updates text for PHP and MySQL checks
* Abstracts messages to own function
* Adds transient for supported plugin check
* Minor design changes

## 1.1.2 (December 17, 2015)

* Minor design changes
* Fixes bug in review message

## 1.1.1 (December 5, 2015)

* Adds check for inactive plugins
* Adds check for 'admin' username
* Added dev hooks for adding checks
* Minor style changes
* Minor text changes

## 1.0.1 (August 19, 2015)

* Ensure compatibility with 4.3
* Minor design changes

## 1.0.0 (June 29, 2015)

* Out of Beta!
* Adds check to see if installed plugins are still supported by their developer [GitHub Issue #3](https://github.com/fpcorso/wordpress-health-check/issues/3)
* Bug Fix: fixes bug that prevented the WordPress update check from working on select sites
* Design: Updated PHP check text

## 0.2.1 (April 22, 2015)

* Updated version number to reflect compatibility with WordPress version 4.2

## 0.2.0 (February 26, 2015)

* Added Ability To Check If Plugins Need Updates [Issue #2](https://github.com/fpcorso/wordpress-health-check/issues/2)
* Added Ability To Check If Themes Need Updates [Issue #4](https://github.com/fpcorso/wordpress-health-check/issues/4)
* In Code: Fixed WordPress Version Check To Automatically Know The Latest Version [Issue #5](https://github.com/fpcorso/wordpress-health-check/issues/5)

## 0.1.0 (February 11, 2015)

* Begun Development
