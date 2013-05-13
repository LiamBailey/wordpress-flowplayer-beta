<?php
/*
Plugin Name: Flowplayer 5 for Wordpress - beta 2
Plugin URI: http://wordpress.org/extend/plugins/flowplayer5/
Description: A Flowplayer plugin for showing videos in WordPress. Integrates Flowplayer 5. Supports all three default Flowplayer skins, subtitles, tracking with Google Analytics, splash images. You can use your own watermark logo if you own a Commercial Flowplayer license. Without a license this plugin uses the Free version that includes a Flowplayer watermark. Visit the <a href="/wp-admin/options-general.php?page=fp5_options">configuration page</a> and set your Google Analytics ID and Flowplayer license key.
Version: 0.5
Author: Flowplayer ltd. Anssi Piirainen
Author URI: http://flowplayer.org/
Text Domain: flowplayer5
Domain Path: /lang/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Copyright 2013 Flowplayer Ltd
*/

/**
 * If this file is attempted to be accessed directly, we'll exit.
 *
 * The following check provides a level of security from other files
 * that request data directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-flowplayer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer-meta-box.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'bcm_code/kojichanges.php' );

// TODO:    Update the instantiation call of your plugin to the name given at the class definition
Flowplayer5::get_instance();