<?php
/*
Plugin Name: Flowplayer 5 for Wordpress - beta 2
Plugin URI: http://wordpress.org/extend/plugins/flowplayer5/
Description: A Flowplayer plugin for showing videos in WordPress. Integrates Flowplayer 5. Supports all three default Flowplayer skins, subtitles, tracking with Google Analytics, splash images. You can use your own watermark logo if you own a Commercial Flowplayer license. Without a license this plugin uses the Free version that includes a Flowplayer watermark. Visit the <a href="/wp-admin/options-general.php?page=fp5_options">configuration page</a> and set your Google Analytics ID and Flowplayer license key.
Version: 0.5
Author: Flowplayer ltd. Anssi Piirainen
Author URI: http://flowplayer.org/
License:
Text Domain: flowplayer5
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Flowplayer5' ) ) :

	// TODO: rename this class to a proper name for your plugin
	class Flowplayer5 {

		/*--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		function __construct() {

			// Load plugin text domain
			add_action( 'init', array( $this, 'plugin_textdomain' ) );

			// Register admin styles and scripts
			add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

			// Register site styles and scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

			// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			/*
			 * TODO:
			 * Define the custom functionality for your plugin. The first parameter of the
			 * add_action/add_filter calls are the hooks into which your code should fire.
			 *
			 * The second parameter is the function name located within this class. See the stubs
			 * later in the file.
			 *
			 * For more information: 
			 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
			 */
			add_action( 'init', array( $this, 'add_flowplayer_videos' ) );
			add_filter( 'TODO', array( $this, 'filter_method_name' ) );

		} // end constructor

		/**
		 * @var Easy_Digital_Downloads The one true Easy_Digital_Downloads
		 */
		private static $instance;

		/**
		 * Main Easy_Digital_Downloads Instance
		 *
		 * Insures that only one instance of Easy_Digital_Downloads exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new Flowplayer5;
				self::$instance->setup_constants();
				self::$instance->includes();
			}
			return self::$instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @since 0.6
		 * @uses plugin_dir_path() To generate FP5 plugin path
		 * @uses plugin_dir_url() To generate FP5 plugin url
		 */
		private function setup_constants() {

			// Plugin version
			if( ! defined( 'FP5_PLUGIN_VERSION' ) )
				define( 'FP5_PLUGIN_VERSION', '0.5' );

			// Flowplayer version
			if( ! defined( 'FP5_FLOWPLAYER_VERSION' ) )
				define( 'FP5_FLOWPLAYER_VERSION', '5.3.2' );

			// Plugin Folder URL
			if( ! defined( 'FP5_PLUGIN_URL' ) )
				define( 'FP5_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			// Plugin Folder Path
			if( ! defined( 'FP5_PLUGIN_DIR' ) )
				define( 'FP5_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Root File
			if( ! defined( 'FP5_PLUGIN_FILE' ) )
				define( 'FP5_PLUGIN_FILE', __FILE__ );
		}

		/**
		 * Include required files
		 *
		 * @since 1.4
		 * @access private
		 * @uses is_admin() If in WordPress admin, load additional file
		 */
		private function includes() {

			require_once FP5_PLUGIN_DIR . 'includes/register-settings.php';
			$flowplayer5_Settings = new flowplayer5_Settings();

			//require_once FP5_PLUGIN_DIR . 'includes/core.php';

			if( is_admin() ) {
				//require_once FP5_PLUGIN_DIR . 'includes/admin.php';
			} else {
				//require_once FP5_PLUGIN_DIR . 'includes/shortcodes.php';
			}
		}

		/**
		 * Fired when the plugin is activated.
		 *
		 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
		 */
		public function activate( $network_wide ) {
			// TODO:	Define activation functionality here
		} // end activate

		/**
		 * Fired when the plugin is deactivated.
		 *
		 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
		 */
		public function deactivate( $network_wide ) {
			// TODO:	Define deactivation functionality here
		} // end deactivate

		/**
		 * Loads the plugin text domain for translation
		 */
		public function plugin_textdomain() {
		
			// TODO: replace "plugin-name-locale" with a unique value for your plugin
			$domain = 'flowplayer5';
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
			load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
			load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		} // end plugin_textdomain

		/**
		 * Registers and enqueues admin-specific styles.
		 */
		public function register_admin_styles() {

			wp_enqueue_style( 'flowplayer5-admin-styles', FP5_PLUGIN_URL . 'assets/css/admin.css' );
			wp_enqueue_style( 'flowplayer5-skin', FP5_PLUGIN_URL . 'assets/skin/all-skins.css' );

		} // end register_admin_styles

		/**
		 * Registers and enqueues admin-specific JavaScript.
		 */	
		public function register_admin_scripts() {

			wp_enqueue_script( 'flowplayer5-admin-script', FP5_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), '1.0.0' );
			wp_enqueue_script( 'flowplayer5-player', FP5_PLUGIN_URL . 'assets/flowplayer/flowplayer.min.js' );
			// This function loads in the required media files for the media manager.
			wp_enqueue_media();
			// Register, localize and enqueue our custom JS.
			wp_register_script( 'tgm-nmp-media', FP5_PLUGIN_URL . 'assets/js/media.js', array('jquery'), '1.0.0', true );
			wp_localize_script( 'tgm-nmp-media', 'tgm_nmp_media',
				array(
					'title'     => __( 'Upload or Choose the Logo', 'tgm-nmp' ), // This will be used as the default title
					'button'    => __( 'Insert Logo', 'tgm-nmp' )            // This will be used as the default button text
				)
			);
			wp_enqueue_script( 'tgm-nmp-media' );
		} // end register_admin_scripts

		/**
		 * Registers and enqueues plugin-specific styles.
		 */
		public function register_plugin_styles() {

			wp_enqueue_style( 'flowplayer5-skin' );

		} // end register_plugin_styles

		/**
		 * Registers and enqueues plugin-specific scripts.
		 */

		// Register Custom Post Type
		function add_flowplayer_videos() {
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
				'label'               => __( 'video', 'flowplayer5' ),
				'description'         => __( 'Flowplayer videos', 'flowplayer5' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'thumbnail', 'custom-fields', ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 15,
				'menu_icon'           => 'http://flowplayer.org/favicon.ico',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'query_var'           => 'video',
				'rewrite'             => false,
				'capability_type'     => 'page',
			);

			register_post_type( 'video', $args );
		}

		/**
		 * NOTE:  Filters are points of execution in which WordPress modifies data
		 *        before saving it or sending it to the browser.
		 *
		 *		  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
		 *		  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
		 *
		 */
		function filter_method_name() {
			// TODO:	Define your filter method here
		} // end filter_method_name

	} // end class

endif; // End if class_exists check

// TODO:	Update the instantiation call of your plugin to the name given at the class definition
$plugin_name = new Flowplayer5();

/**
 * The main function responsible for returning the one true Easy_Digital_Downloads
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $edd = EDD(); ?>
 *
 * @since 1.4
 * @return The one true Easy_Digital_Downloads Instance
 */

function fp5() {
	return Flowplayer5::instance();
}

fp5();