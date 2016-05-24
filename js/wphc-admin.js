/**
 * WPHC Admin
 */

var WPHCAdmin;
(function ($) {
  WPHCAdmin = {
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
          return "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span> " + message + "</div>";
        case 'okay':
          return "<div class='wp-hc-okay-box'><span class='dashicons dashicons-lightbulb'></span> " + message + "</div>";
        case 'bad':
          return "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span> " + message + "</div>";
        default:
          return "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span> " + message + "</div>";
      }
    }
  };

  // Code to run after DOM is loaded
  $(function() {
    WPHCAdmin.loadAllChecks();
  });
}(jQuery));
