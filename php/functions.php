<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function wphc_get_total_checks() {
  $totals = get_transient( 'wphc_total_checks' );
  if ( false === $totals ) {
    $totals = 0;
    $wphc = new WPHC_Checks();
    $checks = $wphc->all_checks();
    foreach ( $checks as $check ) {
      if ( 'good' !== $check["type"] ) {
        $totals += 1;
      }
    }
    set_transient( 'wphc_total_checks', $totals, HOUR_IN_SECONDS );
  }
  return $totals;
}

?>
