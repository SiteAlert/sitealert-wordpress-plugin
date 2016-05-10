/**
 * WPHC Admin
 */

var WPHCAdmin;
(function ($) {
  WPHCAdmin = {
    loadAllChecks: function() {
      WPHCAdmin.loadServerChecks();
      WPHCAdmin.loadWordPressChecks();
      WPHCAdmin.loadPluginChecks();
    },
    loadServerChecks: function() {
      WPHCAdmin.displaySpinner( $( ".server-checks" ) );
      var data = {
      	action: 'wphc_load_server_checks'
      };

      jQuery.post( ajaxurl, data, function( response ) {
      	WPHCAdmin.displayServerChecks( JSON.parse( response ) );
      });
    },
    loadWordPressChecks: function() {
      WPHCAdmin.displaySpinner( $( ".WordPress-checks" ) );
      var data = {
      	action: 'wphc_load_WordPress_checks'
      };

      jQuery.post( ajaxurl, data, function( response ) {
      	WPHCAdmin.displayWordPressChecks( JSON.parse( response ) );
      });
    },
    loadPluginChecks: function() {
      WPHCAdmin.displaySpinner( $( ".plugin-checks" ) );
      var data = {
      	action: 'wphc_load_plugin_checks'
      };

      jQuery.post( ajaxurl, data, function( response ) {
      	WPHCAdmin.displayPluginChecks( JSON.parse( response ) );
      });
    },
    displayServerChecks: function( checks ) {
      var display = [];
      $.each( checks, function( i, val ) {
        display = WPHCAdmin.printMessage( val.message, val.type );
      });
      WPHCAdmin.displaySection( $( ".server-checks" ), display );
    },
    displayWordPressChecks: function( checks ) {
      var display = [];
      $.each( checks, function( i, val ) {
        display = WPHCAdmin.printMessage( val.message, val.type );
      });
      WPHCAdmin.displaySection( $( ".WordPress-checks" ), display );
    },
    displayPluginChecks: function( checks ) {
      var display = [];
      $.each( checks, function( i, val ) {
        display = WPHCAdmin.printMessage( val.message, val.type );
      });
      WPHCAdmin.displaySection( $( ".plugin-checks" ), display );
    },
    displaySection: function( $section, checks ) {
      $section.empty();
      for (var i = 0; i < checks.length; i++) {
        $section.append( checks[i] );
      }
    }
    displaySpinner: function( $section ) {
      $section.empty();
      $section.append( '<div class="wphc-spinner-loader"></div>' );
    },
    printMessage: function( message, type ) {
      switch ( type ) {
        case 'good':
          return "<div class='wp-hc-good-box'><span class='dashicons dashicons-flag'></span> " + message + "</div>";
          break;
        case 'okay':
          return "<div class='wp-hc-okay-box'><span class='dashicons dashicons-lightbulb'></span> " + message + "</div>";
          break;
        case 'bad':
          return "<div class='wp-hc-bad-box'><span class='dashicons dashicons-dismiss'></span> " + message + "</div>";
          break;
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
