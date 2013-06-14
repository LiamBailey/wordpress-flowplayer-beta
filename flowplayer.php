<?php
/**
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 *
 * @wordpress-plugin
 * Plugin Name: Flowplayer 5 for Wordpress - beta
 * Plugin URI:  http://wordpress.org/extend/plugins/flowplayer5/
 * Description: A Flowplayer plugin for showing videos in WordPress. Integrates Flowplayer 5. Supports all three default Flowplayer skins, subtitles, tracking with Google Analytics, splash images. You can use your own watermark logo if you own a Commercial Flowplayer license. Without a license this plugin uses the Free version that includes a Flowplayer watermark. Visit the <a href="/wp-admin/options-general.php?page=fp5_options">configuration page</a> and set your Google Analytics ID and Flowplayer license key.
 * Version:     1.0.0-beta
 * Author:      Flowplayer ltd. Anssi Piirainen
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

// TODO: replace `class-plugin-name.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-flowplayer.php' );
//require_once( plugin_dir_path( __FILE__ ) . 'includes/bcm-flowplayer-meta-box.php' );
//require_once( plugin_dir_path( __FILE__ ) . 'includes/flowplayer-meta-box.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer-meta-box.php' );
//require_once( plugin_dir_path( __FILE__ ) . 'includes/flowplayer-register-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcode.php' );

//$fp5_options = fp5_get_settings();

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace PluginName with the name of the plugin defined in `class-plugin-name.php`
register_activation_hook( __FILE__, array( 'PluginName', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PluginName', 'deactivate' ) );

// TODO: replace PluginName with the name of the plugin defined in `class-plugin-name.php`
Flowplayer5::get_instance();
$fp5_metabox = new fp5_metabox();