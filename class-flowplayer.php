<?php
/**
 * Flowplayer 5 for Wordpress
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Flowplayer Ltd
 */

/**
 * Plugin class.
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Flowplayer5
 * @author  Your Name <email@example.com>
 */
class Flowplayer5 {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0-beta';

	function get_version() {
		return $this->version;
	}


	/**
	 * Player version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $player_version = '5.4.1';

	function get_player_version() {
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

	function get_plugin_slug() {
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
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action( 'init', array( $this, 'add_fp5_videos' ) );
		add_filter( 'upload_mimes', array( $this, 'flowplayer_custom_mimes' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		// set the options for the shortcode - pulled from the display-settings.php
		$options = get_option('fp5_options');
		$cdn = $options['cdn'];

		$screen = get_current_screen();
		//if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( '/assets/css/admin.css', __FILE__ ), $this->version );
			if( $cdn == 'true' ) {
				wp_enqueue_style( $this->plugin_slug .'-skins' , 'http://releases.flowplayer.org/' . $this->player_version . '/skin/all-skins.css' );
			} else {
				wp_enqueue_style( $this->plugin_slug .'-skins', plugins_url( '/assets/flowplayer/skin/all-skins.css', __FILE__ ), $this->player_version );
			}
		//}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		// set the options for the shortcode - pulled from the display-settings.php
		$options = get_option('fp5_options');
		$key = $options['key'];
		$cdn = $options['cdn'];

		$screen = get_current_screen();
		//if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			//wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( '/assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
			if( $cdn == 'true' ) {
				wp_enqueue_script( $this->plugin_slug . '-script', 'http://releases.flowplayer.org/' . $this->player_version . '/'.($key != '' ? 'commercial/' : '') . 'flowplayer.min.js', array( 'jquery' ), $this->player_version, false );
			} else {
				wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( '/assets/flowplayer/'.($key != '' ? "commercial/" : "").'flowplayer.min.js', __FILE__ ), array( 'jquery' ), $this->version, false );
			}
		//}

		wp_enqueue_script( $this->plugin_slug . '-media', plugins_url( '/assets/js/media.js', __FILE__ ), array(), $this->version, false );
		wp_localize_script( $this->plugin_slug . '-media', 'splash_image',
			array(
				'title'  => __( 'Upload or choose a splash image', 'flowplayer5' ), // This will be used as the default title
				'button' => __( 'Insert Splash Image', 'flowplayer5' )            // This will be used as the default button text
			)
		);
		wp_localize_script( $this->plugin_slug . '-media', 'mp4_video',
			array(
				'title'  => __( 'Upload or choose a mp4 video file', 'flowplayer5' ), // This will be used as the default title
				'button' => __( 'Insert mp4 Video', 'flowplayer5' )            // This will be used as the default button text
			)
		);
		wp_localize_script( $this->plugin_slug . '-media', 'webm_video',
			array(
				'title'  => __( 'Upload or choose a webm video file', 'flowplayer5' ), // This will be used as the default title
				'button' => __( 'Insert webm Video', 'flowplayer5' )            // This will be used as the default button text
			)
		);
		wp_localize_script( $this->plugin_slug . '-media', 'ogg_video',
			array(
				'title'  => __( 'Upload or choose a ogg video file', 'flowplayer5' ), // This will be used as the default title
				'button' => __( 'Insert ogg Video', 'flowplayer5' )            // This will be used as the default button text
			)
		);
		wp_localize_script( $this->plugin_slug . '-media', 'webvtt',
			array(
				'title'  => __( 'Upload or choose a webvtt file', 'flowplayer5' ), // This will be used as the default title
				'button' => __( 'Insert webvtt', 'flowplayer5' )                   // This will be used as the default button text
			)
		);
		wp_localize_script( $this->plugin_slug . '-media', 'logo',
			array(
				'title'  => __( 'Upload or choose a logo', 'flowplayer5' ), // This will be used as the default title
				'button' => __( 'Insert Logo', 'flowplayer5' )                   // This will be used as the default button text
			)
		);
		wp_enqueue_media();
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'plugin-name' to the name of your plugin
		 */
		$this->plugin_screen_hook_suffix = add_submenu_page(
			'edit.php?post_type=flowplayer5video',
			__( 'Flowplayer Settings', $this->plugin_slug ),
			__( 'Settings', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'includes/display-settings.php' );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function add_fp5_videos() {
		$labels = array(
			'name'                => _x( 'Videos', 'Post Type General Name', 'flowplayer5' ),
			'singular_name'       => _x( 'Video', 'Post Type Singular Name', 'flowplayer5' ),
			'menu_name'           => __( 'Video', 'flowplayer5' ),
			'parent_item_colon'   => __( 'Parent Video', 'flowplayer5' ),
			'all_items'           => __( 'All Videos', 'flowplayer5' ),
			'view_item'           => __( 'View Video', 'flowplayer5' ),
			'add_new_item'        => __( 'Add New Video', 'flowplayer5' ),
			'add_new'             => __( 'New Video', 'flowplayer5' ),
			'edit_item'           => __( 'Edit Video', 'flowplayer5' ),
			'update_item'         => __( 'Update Video', 'flowplayer5' ),
			'search_items'        => __( 'Search videos', 'flowplayer5' ),
			'not_found'           => __( 'No videos found', 'flowplayer5' ),
			'not_found_in_trash'  => __( 'No videos found in Trash', 'flowplayer5' ),
		);

		$args = array(
			'label'               => __( 'Video', 'flowplayer5' ),
			'description'         => __( 'Flowplayer videos', 'flowplayer5' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 15,
			'menu_icon'           => '',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'query_var'           => 'video',
			'rewrite'             => false,
			'capability_type'     => 'page',
		);

		register_post_type( 'FlowPlayer5Video', $args );
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function flowplayer_custom_mimes( $mimes ){
			$mimes['webm'] = 'video/webm';
			$mimes['vtt'] = 'text/vtt';
		return $mimes;
	}

}