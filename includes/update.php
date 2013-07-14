<?php
/**
 * Flowplayer 5 for Wordpress
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Runs the installer.
 *
 * @access public
 * @return void
 */
function do_update_flowplyer5() {
	global $woocommerce;

	// Do updates
	$current_db_version = get_option( 'woocommerce_db_version' );

	if ( version_compare( $current_db_version, '1.0.0', '<' ) ) {
		include( 'includes/updates/update-1.0.0.php' );
		update_option( 'woocommerce_db_version', '1.0.0' );
	}

	update_option( 'flowplyer_db_version', $woocommerce->version );
}