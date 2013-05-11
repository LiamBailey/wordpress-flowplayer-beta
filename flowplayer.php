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

Copyright 2013 TODO (email@domain.com)

*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*
 * The following constant is used to define a constant for this plugin to make it
 * easier to provide cache-busting functionality on loading stylesheets
 * and JavaScript.
 *
 * After you've defined these constants, do a find/replace on the constants
 * used throughout the rest of this file.
 */
// Plugin version
if( ! defined( 'FP5_PLUGIN_VERSION' ) )
	define( 'FP5_PLUGIN_VERSION', '0.5' );

// Flowplayer version
if( ! defined( 'FP5_FLOWPLAYER_VERSION' ) )
	define( 'FP5_FLOWPLAYER_VERSION', '5.4.1' );

// Plugin Folder URL
if( ! defined( 'FP5_PLUGIN_URL' ) )
	define( 'FP5_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Folder Path
if( ! defined( 'FP5_PLUGIN_DIR' ) )
	define( 'FP5_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Plugin Root File
if( ! defined( 'FP5_PLUGIN_FILE' ) )
	define( 'FP5_PLUGIN_FILE', __FILE__ );

/**
 * TODO: 
 *
 * Rename this class to a proper name for your plugin. Give a proper description of
 * the plugin, it's purpose, and any dependencies it has.
 *
 * Use PHPDoc tags if you wish to be able to document the code using a documentation
 * generator.
 *
 * @package	Flowplayer5
 * @version	1.0.0
 */
//if ( !class_exists( 'Flowplayer5' ) ) :
class Flowplayer5 {
    
    /**
     * Refers to a single instance of this class. 
     *
     * @var		object
     */
    protected static $instance = null;
    
    /** 
     * Refers to the slug of the plugin screen.
     *
     * @var		string
     */
    protected $plugin_screen_slug = null;
    
    /**
     * Creates or returns an instance of this class.
     *
     * @since	1.0.0
     * @return	PluginName	A single instance of this class.
     */
    public function get_instance() {
        
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        
        return self::$instance;
        
    }
    
    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     *
     * @since	1.0.0
     */
    private function __construct() {
        
        // Load plugin text domain
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        
        //Add the options page and menu item.
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        
        // Register admin styles and scripts        
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

        // Register site stylesheets and JavaScript
        //add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        //add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
        
        // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
        register_activation_hook(__FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        
        /*
         * TODO:
         * 
         * Define the custom functionality for your plugin. The first parameter of the
         * add_action/add_filter calls are the hooks into which your code should fire.
         *
         * The second parameter is the function name located within this class. See the stubs
         * later in the file.
         *
         * For more information:
         * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         */
        add_action( 'init', array( $this, 'add_fp5_videos' ) );
        add_filter(' TODO', array( $this, 'filter_method_name' ) );
        
    }
    
    /**
     * Fired when the plugin is activated.
     *
     * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function activate( $network_wide ) {
        // TODO:	Define activation functionality here
    }
    
    /**
     * Fired when the plugin is deactivated.
     *
     * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     * @since	1.0.0
     */
    public function deactivate( $network_wide ) {
        // TODO:	Define deactivation functionality here
    }
    
    /**
     * Loads the plugin text domain for translation
     */
    public function load_plugin_textdomain() {
        
        // TODO: replace "plugin-name-locale" with a unique value for your plugin
        $domain = 'flowplayer5';
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        
        load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
        
    }
    
    /**
     * Registers and enqueues admin-specific styles.
     *
     * @since	1.0.0
     */
    public function register_admin_styles() {
        
        /*
         * Check if the plugin has registered a settings page
         * and if it has, make sure only to enqueue the scripts on the relevant screens
         */
        
        if ( isset( $this->plugin_screen_slug ) ) {
            
            /*
             * Check if current screen is the admin page for this plugin
             * Don't enqueue stylesheet or JavaScript if it's not
             */
            
            $screen = get_current_screen();
            if ( $screen->id == $this->plugin_screen_slug ) {
                wp_enqueue_style( 'flowplayer5-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), FP5_PLUGIN_VERSION );
				if( $fp5_cdn == 'true' ) {
					wp_enqueue_style( 'fp5_skins' , 'http://releases.flowplayer.org/' . FP5_FLOWPLAYER_VERSION . '/skin/' . $skin . '.css' );
				} else {
					wp_enqueue_style( 'fp5_skins' , plugins_url( '/assets/skin/' . $skin . '.css', dirname(__FILE__) ) );
				}
            }
            
        }
        
    }
    
    /**
     * Registers and enqueues admin-specific JavaScript.
     *
     * @since	1.0.0
     */
    public function register_admin_scripts() {
        
        /*
         * Check if the plugin has registered a settings page
         * and if it has, make sure only to enqueue the scripts on the relevant screens
         */
        
        if ( isset( $this->plugin_screen_slug ) ) {
            
            /*
             * Check if current screen is the admin page for this plugin
             * Don't enqueue stylesheet or JavaScript if it's not
             */
            
            $screen = get_current_screen();
            if ( $screen->id == $this->plugin_screen_slug ) {
                wp_enqueue_script( 'flowplayer5-admin-script', plugins_url('assets/js/admin.js', __FILE__), array( 'jquery' ), FP5_PLUGIN_VERSION );

				if( $fp5_cdn == 'true' ) {
					wp_enqueue_script( 'fp5_embedder', 'http://releases.flowplayer.org/' . FP5_FLOWPLAYER_VERSION . '/'.($key != '' ? 'commercial/' : '') . 'flowplayer.min.js', array('jquery'), null, false);
				} else {
					wp_enqueue_script('fp5_embedder', plugins_url( '/assets/flowplayer/'.($key != '' ? "commercial/" : "").'flowplayer.min.js', dirname(__FILE__) ), array('jquery'), null, false);
				}
            }
            
        }
        
    }
    
    /**
     * Registers and enqueues public-facing stylesheets.
     *
     * @since	1.0.0
     */
    public function register_plugin_styles() {
        wp_enqueue_style( 'plugin-name-plugin-styles', plugins_url( 'css/display.css', __FILE__ ), FP5_PLUGIN_VERSION );
    }
    
    /**
     * Registers and enqueues public-facing JavaScript.
     *
     * @since	1.0.0
     */
    public function register_plugin_scripts() {
        wp_enqueue_script( 'plugin-name-plugin-script', plugins_url( 'js/display.js', __FILE__ ), array( 'jquery' ), FP5_PLUGIN_VERSION );
    }
    
    /**
     * Registers the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since	1.0.0
     */
    public function add_plugin_admin_menu() {
        
        /*
         * TODO:	
         *
         * Change 'Page Title' to the title of your plugin admin page
         * Change 'Menu Text' to the text for menu item for the plugin settings page 
         * Change 'plugin-name' to the name of your plugin
         */
        $this->plugin_screen_slug = add_submenu_page(
			'edit.php?post_type=video',
        	__('Flowplayer Settings', 'flowplayer5'), 
        	__('Settings', 'flowplayer5'), 
        	'read',
        	'settings',
        	array( $this, 'display_plugin_admin_page' )
        );
        
    }
    
    /**
     * Renders the options page for this plugin.
     *
     * @since	1.0.0
     */
    public function display_plugin_admin_page() {
        include_once('includes/admin.php');
		include_once('includes/shortcode.php');
    }
    
    /*
     * NOTE:  Actions are points in the execution of a page or process
     *        lifecycle that WordPress fires.
     *
     *		  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
     *		  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     * @since	1.0.0
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
			'label'               => __( 'video', 'flowplayer5' ),
			'description'         => __( 'Flowplayer videos', 'flowplayer5' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', ),
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
    
    /*
     * NOTE:  Filters are points of execution in which WordPress modifies data
     *        before saving it or sending it to the browser.
     *
     *		  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
     *		  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     * @since	1.0.0
     */
    public function filter_method_name() {
        // TODO:	Define your filter method here
    }
    
}

// TODO:	Update the instantiation call of your plugin to the name given at the class definition
Flowplayer5::get_instance();