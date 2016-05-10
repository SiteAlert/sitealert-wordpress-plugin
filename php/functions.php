<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function wphc_get_total_checks() {
  $totals = get_transient( 'wphc_total_checks' );
  if ( false === $totals ) {
    $check = new WPHC_Checks();
    $totals = count( $checks->all_checks() );
    set_transient( 'wphc_total_checks', $totals, HOUR_IN_SECONDS );
  }
  return $totals;
}

?>
