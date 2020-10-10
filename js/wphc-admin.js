/**
 * WPHC Admin
 */

var WPHCAdmin;
(function ($) {
	WPHCAdmin = {
		subscribe: function( name, email ) {
			WPHCAdmin.displaySpinner( $( '#wphc-subscribe' ) );
			var data = {
				action: 'wphc_subscribe',
				name: name,
				email: email,
			};
			$.post( ajaxurl, data, function( response ) {
				$( '#wphc-subscribe' ).html( 'Awesome! You have been subscribed.' );
			});
		},
		/**
		 * Sends the settings to our AJAX endpoint
		 *
		 * @since 1.6.4
		 */
		saveSettings: function() {
			$( '#wphc-settings-save' ).prop( 'disabled', true );
			document.querySelector('.admin-messages').scrollIntoView({behavior: "smooth", block: "end", inline: "start"})
			WPHCAdmin.displayAlert( 'Saving settings...', 'info' );
			var tracking_allowed = 0;
			if ( $( '#tracking_allowed' ).prop( 'checked' ) ) {
				tracking_allowed = 2;
			}
			var data = {
				action: 'wphc_save_plugin_settings',
				tracking_allowed: tracking_allowed,
				api_key: $( '#api_key' ).val(),
			};
			$.post( ajaxurl, data, function( response ) {
				response =  JSON.parse( response );
				if ( response.success ) {
					WPHCAdmin.displayAlert( response.msg, 'success' );
				} else {
					WPHCAdmin.displayAlert( 'Error when saving! ' + response.msg, 'error' );
				}
				$( '#wphc-settings-save' ).prop( 'disabled', false );
			});
		},
		selectTab: function( tab ) {
			$( '.wphc-tab' ).removeClass( 'nav-tab-active' );
			$( '.wphc-tab-content' ).hide();
			tab.addClass( 'nav-tab-active' );
			var tabID = tab.data( 'tab' );
			$( '#tab-' + tabID ).show();
		},
		// Loads all checks
		loadAllChecks: function() {
			WPHCAdmin.loadServerChecks();
			WPHCAdmin.loadWordPressChecks();
			WPHCAdmin.loadPluginChecks();
			WPHCAdmin.loadPremiumAlerts();
		},
		// Shows potential premium checks
		loadPremiumAlerts: function() {
			if ( $( '.premium-checks' ).length > 0 ) {
				WPHCAdmin.displaySpinner( $( '.premium-checks' ) );
				setTimeout( function() {
					var checks = [
						{'message': 'You are not receiving emails with the failed checks from this site.', 'type':'okay'},
						{'message': 'Your site is not being monitored to make sure it is up.', 'type':'okay'},
						{'message': 'Your site is not being monitored for broken links and images.', 'type':'okay'},
						{'message': 'Your site is not being monitored for site speed.', 'type':'okay'},
						{'message': 'Your site is not being monitored for being listed on blacklists. <a href="https://sitealert.io/what-happens-when-your-site-is-blacklisted/?utm_campaign=health-plugin&utm_medium=plugin&utm_source=checks-page&utm_content=blacklist-premium-upsell" target="_blank">Learn more about what blacklists are!</a>', 'type':'okay'},
					];
					WPHCAdmin.displaySection( $( ".premium-checks" ), checks );
				}, 1500 );
			}
		},
		// Loads the server checks
		loadServerChecks: function() {
			WPHCAdmin.displaySpinner( $( ".server-checks" ) );
			var data = {
				action: 'wphc_load_server_checks'
			};

			jQuery.post( ajaxurl, data, function( response ) {
				WPHCAdmin.displaySection( $( ".server-checks" ), JSON.parse( response ) );
			});
		},
		// Loads the WP checks
		loadWordPressChecks: function() {
			WPHCAdmin.displaySpinner( $( ".WordPress-checks" ) );
			var data = {
				action: 'wphc_load_WordPress_checks'
			};

			jQuery.post( ajaxurl, data, function( response ) {
				WPHCAdmin.displaySection( $( ".WordPress-checks" ), JSON.parse( response ) );
			});
		},
		// Loads the plugin checks
		loadPluginChecks: function() {
			WPHCAdmin.displaySpinner( $( ".plugin-checks" ) );
			var data = {
				action: 'wphc_load_plugin_checks'
			};

			jQuery.post( ajaxurl, data, function( response ) {
				WPHCAdmin.displaySection( $( ".plugin-checks" ), JSON.parse( response ) );
			});
		},
		// Displays the results in the section, with bad checks shown first.
		displaySection: function( $section, checks ) {
			$section.empty();
			var okayChecks = [];
			var goodChecks = [];
			checks.forEach(function(check) {
				switch ( check.type ) {
					case 'good':
						goodChecks.push( check );
						break;

					case 'okay':
						okayChecks.push( check );
						break

					case 'bad':
						$section.append( WPHCAdmin.printMessage( check.message, check.type ) );
						break
				
					default:
						break;
				}
			});
			okayChecks.forEach(function(check){
				$section.append( WPHCAdmin.printMessage( check.message, check.type ) );
			});
			goodChecks.forEach(function(check){
				$section.append( WPHCAdmin.printMessage( check.message, check.type ) );
			});
		},
		// Displays a loading spinner in the $section
		displaySpinner: function( $section ) {
			$section.empty();
			$section.append( '<div class="wphc-spinner-loader"></div>' );
		},
		// Prepares the div for the results of a check
		printMessage: function( message, type ) {
			switch ( type ) {
				case 'good':
					return "<div class='wphc-alert-box wphc-good-box'><span class='dashicons dashicons-flag'></span> " + message + "</div>";
				case 'okay':
					return "<div class='wphc-alert-box wphc-okay-box'><span class='dashicons dashicons-lightbulb'></span> " + message + "</div>";
				case 'bad':
					return "<div class='wphc-alert-box wphc-bad-box'><span class='dashicons dashicons-dismiss'></span> " + message + "</div>";
				default:
					return "<div class='wphc-alert-box wphc-bad-box'><span class='dashicons dashicons-dismiss'></span> " + message + "</div>";
			}
		},
		displayAlert: function( message, type ) {
			WPHCAdmin.clearAlerts();
			var template = wp.template( 'notice' );
			var data = {
				message: message,
				type: type
			};
			$( '.admin-messages' ).append( template( data ) );
		},
		clearAlerts: function() {
			$( '.admin-messages' ).empty();
		},
	};

	// Code to run after DOM is loaded
	$(function() {
		WPHCAdmin.loadAllChecks();
		$( '#wphc-subscribe-button' ).on( 'click', function( event ) {
			event.preventDefault();
			WPHCAdmin.subscribe( $( '#wphc-subscribe-name' ).val(), $( '#wphc-subscribe-email' ).val() );
		});
		$( '.wphc-tab' ).on( 'click', function( event ) {
			event.preventDefault();
			WPHCAdmin.selectTab( $( this ) );
		});
		$( '#wphc-settings-save' ).on( 'click', function( event ) {
			event.preventDefault();
			WPHCAdmin.saveSettings();
		});
		$( '#tab-1' ).show();
	});
}(jQuery));
