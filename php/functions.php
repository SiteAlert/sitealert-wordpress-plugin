<?php
/**
 * Our misc. functions
 *
 * @package WPHC
 */

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the total amount of checks that failed
 *
 * @since 1.3.0
 * @return int The amount of checks that failed
 */
function wphc_get_total_checks() {
	$totals = get_transient( 'wphc_total_checks' );
	if ( false === $totals ) {
		$totals = 0;
		$wphc   = new WPHC_Checks();
		$checks = $wphc->all_checks();
		foreach ( $checks as $check ) {
			if ( 'good' !== $check['type'] ) {
				$totals++;
			}
		}

		// Sets transient to expire in 45 minutes.
		set_transient( 'wphc_total_checks', $totals, 45 * MINUTE_IN_SECONDS );
	}
	return intval( $totals );
}
