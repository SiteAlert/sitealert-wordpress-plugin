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
			jQuery.post( ajaxurl, data, function( response ) {
				response =  JSON.parse( response );
				if ( response.success ) {
					WPHCAdmin.displayAlert( 'Settings have been saved!', 'success' );
				} else {
					WPHCAdmin.displayAlert( 'Error when saving! ' + response.msg, 'error' );
				}
			});
		},
		selectTab: function( tab ) {
			$( '.wphc-tab' ).removeClass( 'nav-tab-active' );
			$( '.wphc-tab-content' ).hide();
			tab.addClass( 'nav-tab-active' );
			tabID = tab.data( 'tab' );
			$( '#tab-' + tabID ).show();
		},
		// Loads all checks
		loadAllChecks: function() {
			WPHCAdmin.loadServerChecks();
			WPHCAdmin.loadWordPressChecks();
			WPHCAdmin.loadPluginChecks();
		},
		// Loads the server checks
		loadServerChecks: function() {
			WPHCAdmin.displaySpinner( $( ".server-checks" ) );
			var data = {
				action: 'wphc_load_server_checks'
			};

			jQuery.post( ajaxurl, data, function( response ) {
				WPHCAdmin.displayServerChecks( JSON.parse( response ) );
			});
		},
		// Loads the WP checks
		loadWordPressChecks: function() {
			WPHCAdmin.displaySpinner( $( ".WordPress-checks" ) );
			var data = {
				action: 'wphc_load_WordPress_checks'
			};

			jQuery.post( ajaxurl, data, function( response ) {
				WPHCAdmin.displayWordPressChecks( JSON.parse( response ) );
			});
		},
		// Loads the plugin checks
		loadPluginChecks: function() {
			WPHCAdmin.displaySpinner( $( ".plugin-checks" ) );
			var data = {
				action: 'wphc_load_plugin_checks'
			};

			jQuery.post( ajaxurl, data, function( response ) {
				WPHCAdmin.displayPluginChecks( JSON.parse( response ) );
			});
		},
		// Prepares the server checks results
		displayServerChecks: function( checks ) {
			var display = [];
			$.each( checks, function( i, val ) {
				display.push( WPHCAdmin.printMessage( val.message, val.type ) );
			});
			WPHCAdmin.displaySection( $( ".server-checks" ), display );
		},
		// Prepares the WP checks results
		displayWordPressChecks: function( checks ) {
			var display = [];
			$.each( checks, function( i, val ) {
				display.push( WPHCAdmin.printMessage( val.message, val.type ) );
			});
			WPHCAdmin.displaySection( $( ".WordPress-checks" ), display );
		},
		// Prepares the plugin checks results
		displayPluginChecks: function( checks ) {
			var display = [];
			$.each( checks, function( i, val ) {
				display.push( WPHCAdmin.printMessage( val.message, val.type ) );
			});
			WPHCAdmin.displaySection( $( ".plugin-checks" ), display );
		},
		// Displays the results in the section
		displaySection: function( $section, checks ) {
			$section.empty();
			for (var i = 0; i < checks.length; i++) {
				$section.append( checks[i] );
			}
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
