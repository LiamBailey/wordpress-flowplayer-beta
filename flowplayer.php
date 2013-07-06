<?php
/**
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 *
 * @wordpress-plugin
 * Plugin Name: Flowplayer 5 for Wordpress - beta
 * Plugin URI:  http://wordpress.org/extend/plugins/flowplayer5/
 * Description: A Flowplayer plugin for showing videos in WordPress. Integrates Flowplayer 5. Supports all three default Flowplayer skins, subtitles, tracking with Google Analytics, splash images. You can use your own watermark logo if you own a Commercial Flowplayer license. Without a license this plugin uses the Free version that includes a Flowplayer watermark. Visit the <a href="/wp-admin/options-general.php?page=fp5_options">configuration page</a> and set your Google Analytics ID and Flowplayer license key.
 * Version:     1.0.0-beta
 * Author:      Flowplayer ltd. Anssi Piirainen, Ulrich Pogson
 * Author URI:  http://flowplayer.org/
 * Text Domain: flowplayer5
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $fp5_options;

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer-meta-box.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/register-settings.php' );
$fp5_options = fp5_get_settings();
require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcode.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Flowplayer5', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Flowplayer5', 'deactivate' ) );

Flowplayer5::get_instance();
video_meta_box::get_instance();