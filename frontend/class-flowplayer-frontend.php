<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer 5 for WordPress
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
 * Initial Flowplayer class
 *
 * @package Flowplayer5
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
class Flowplayer5_Frontend {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	public function get_version() {
		return $this->version;
	}


	/**
	 * Player version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $player_version = '5.4.3';

	public function get_player_version() {
		return $this->player_version;
	}


	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'flowplayer5';

	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_settings_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Load script for Flowplayer global configuration
		add_action( 'wp_head', array( $this, 'global_config_script' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @return   object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// set the options for the shortcode - pulled from the register-settings.php
		$options = get_option('fp5_settings_general');
		$cdn     = isset( $options['cdn_option'] );

		global $post;

		// Register shortcode stylesheets and JavaScript
		if( function_exists( 'has_shortcode' ) ) {
			if( has_shortcode( $post->post_content, 'flowplayer' ) ) {
				if( $cdn ) {
					wp_enqueue_style( $this->plugin_slug .'-skins' , '//releases.flowplayer.org/' . $this->player_version . '/skin/all-skins.css' );
				} else {
					wp_enqueue_style( $this->plugin_slug .'-skins', plugins_url( '/assets/flowplayer/skin/all-skins.css', __FILE__ ), $this->player_version );
				}
				wp_enqueue_style( $this->plugin_slug .'-logo-origin', plugins_url( '/assets/css/public.css', __FILE__ ), $this->player_version );
			}
		} else {
			if( $cdn ) {
				wp_enqueue_style( $this->plugin_slug .'-skins' , '//releases.flowplayer.org/' . $this->player_version . '/skin/all-skins.css' );
			} else {
				wp_enqueue_style( $this->plugin_slug .'-skins', plugins_url( '/assets/flowplayer/skin/all-skins.css', __FILE__ ), $this->player_version );
			}
			wp_enqueue_style( $this->plugin_slug .'-logo-origin', plugins_url( '/assets/css/public.css', __FILE__ ), $this->player_version );
		}

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// set the options for the shortcode - pulled from the register-settings.php
		$options = get_option('fp5_settings_general');
		$key     = ( ! empty ( $options['key'] ) ? $options['key'] : '' );
		$cdn     = isset( $options['cdn_option'] );

		global $post;

		// Register shortcode stylesheets and JavaScript
		if( function_exists( 'has_shortcode' ) ) {
			if( has_shortcode( $post->post_content, 'flowplayer' ) ) {
				if( $cdn ) {
					wp_enqueue_script( $this->plugin_slug . '-script', '//releases.flowplayer.org/' . $this->player_version . '/'. ( $key != '' ? 'commercial/' : '' ) . 'flowplayer.min.js', array( 'jquery' ), $this->player_version, false );
				} else {
					wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( '/assets/flowplayer/' . ( $key != '' ? "commercial/" : "" ) . 'flowplayer.min.js', __FILE__  ), array( 'jquery' ), $this->player_version, false );
				}
			}
		} else {
			if( $cdn ) {
				wp_enqueue_script( $this->plugin_slug . '-script', '//releases.flowplayer.org/' . $this->player_version . '/'. ( $key != '' ? 'commercial/' : '' ) . 'flowplayer.min.js', array( 'jquery' ), $this->player_version, false );
			} else {
				wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( '/assets/flowplayer/' . ( $key != '' ? "commercial/" : "" ) . 'flowplayer.min.js', __FILE__  ), array( 'jquery' ), $this->player_version, false );
			}
		}

	}

	public function global_config_script() {

		// set the options for the shortcode - pulled from the display-settings.php
		$options       = get_option('fp5_settings_general');
		$embed_library = ( ! empty ( $options['library'] ) ? $options['library'] : '' );
		$embed_script  = ( ! empty ( $options['script'] ) ? $options['script'] : '' );
		$embed_skin    = ( ! empty ( $options['skin'] ) ? $options['skin'] : '' );
		$embed_swf     = ( ! empty ( $options['swf'] ) ? $options['swf'] : '' );

		if ( $embed_library || $embed_script || $embed_skin || $embed_swf ) {

			$return = '<!-- flowplayer global options -->';
			$return .= '<script>';
			$return .= 'flowplayer.conf = {';
				$return .= 'embed: {';
					$return .= ( ! empty ( $embed_library ) ? 'library: "' . $embed_library . '",' : '' );
					$return .= ( ! empty ( $embed_script ) ? 'script: "' . $embed_script . '",' : '' );
					$return .= ( ! empty ( $embed_skin ) ? 'skin: "' . $embed_skin . '",' : '' );
					$return .= ( ! empty ( $embed_swf ) ? 'swf: "' . $embed_swf . '"' : '' );
				$return .= '}';
			$return .= '};';
			$return .= '</script>';

			echo $return;

		}

	}

}