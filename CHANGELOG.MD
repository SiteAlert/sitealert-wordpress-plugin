= 1.7.9 (March 22, 2019) =
* Makes minor changes to text

= 1.7.8 (March 14, 2019) =
* Fixes bug causing ads to still show for premium users

= 1.7.7 (March 9, 2019) =
* Makes minor changes to text

= 1.7.6 (February 22, 2019) =
* Ensures compatibility with WordPress 5.1

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

= 1.6.11 (October 18, 2018) =
* Fixes plugin update check in REST API

= 1.6.10 (October 14, 2018) =
* Makes minor text changes

= 1.6.9 (September 13, 2018) =
* Enhances WordPress version check to work on hosts modifying the get_core_updates() function

= 1.6.8 (September 7, 2018) =
* Adds link to checks from plugins page
* Makes minor text changes
* Improves internationalization

= 1.6.7 (August 31, 2018) =
* Adds info about new premium features

= 1.6.6 (August 23, 2018) =
* Fixes undefined variable bug in check for MariaDB

= 1.6.5 (August 13, 2018) =
* Makes minor text changes
* Changes permission level to administrator instead of editor to see the checks

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

= 1.4.4 (May 24, 2018) =
* Fixes bug causes undefined function errors on the frontend when logged in

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