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
 * @package    Flowplayer5
 * @version    1.0.0
 */
class Flowplayer5 {

	/**
	 * Refers to a single instance of this class. 
	 *
	 * @var    object
	 */
	protected static $instance = null;

	/** 
	 * Refers to the slug of the plugin screen.
	 *
	 * @var    string
	 */
	protected $plugin_screen_slug = null;
	
	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since     1.0.0
	 * @return    PluginName    A single instance of this class.
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
	 * @since    1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		/*
		 * Add the options page and menu item.
		 * Uncomment the following line to enable the Settings Page for the plugin:
		 */
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		/*
		 * Register admin styles and scripts
		 * If the Settings page has been activated using the above hook, the scripts and styles
		 * will only be loaded on the settings page. If not, they will be loaded for all
		 * admin pages. 
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		

		// Register site stylesheets and JavaScript
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
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
		add_filter(' upload_mimes', array( $this, 'flowplayer_custom_mimes' ) );
		add_meta_box( 'flowplayer5', 'Add Flowplayer', 'fp5_meta_box', 'video', 'normal' );
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function activate( $network_wide ) {
		// TODO:    Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 * @since    1.0.0
	 */
	public function deactivate( $network_wide ) {
		// TODO:    Define deactivation functionality here
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
	 * @since    1.0.0
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
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function register_plugin_styles() {
		wp_enqueue_style( 'plugin-name-plugin-styles', plugins_url( 'css/display.css', __FILE__ ), PLUGIN_NAME_VERSION );
	}

	/**
	 * Registers and enqueues public-facing JavaScript.
	 *
	 * @since    1.0.0
	 */
	public function register_plugin_scripts() {
		wp_enqueue_script( 'plugin-name-plugin-script', plugins_url( 'js/display.js', __FILE__ ), array( 'jquery' ), PLUGIN_NAME_VERSION );
	}

	/**
	 * Registers the administration menu for this plugin into the WordPress Dashboard menu.
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
		$this->plugin_screen_slug = add_submenu_page(
			'edit.php?post_type=video',
			__('Flowplayer Settings', 'plugin-name-locale'), 
			__('Settings', 'plugin-name-locale'), 
			'read', 
			'settings',
			array( $this, 'display_plugin_admin_page' )
		);
		
	}

	/**
	 * Renders the options page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once('includes/admin.php');
		include_once('includes/shortcode.php');
	}

	/*
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
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since       1.0.0
	 */
	public function flowplayer_custom_mimes( $mimes ){
		$mimes['webm'] = 'video/webm';
	return $mimes;
	}

	public function fp5_meta_box() {
		?>
		<div class="options" xmlns="http://www.w3.org/1999/html">
			<div class="optgroup">
				<label for="fp5_selectSkin">
					<?php _e('Select skin')?>
				</label>
				<select id="fp5_selectSkin" class="option">
					<option class="fp5[skin]" id="fp5_minimalistSel" value="minimalist" selected="selected"><?php _e('Minimalist' ) ?></option>
					<option class="fp5[skin]" id="fp5_functionalSel" value="functional"><?php _e('Functional' ) ?></option>
					<option class="fp5[skin]" id="fp5_playfulSel" value="playful"><?php _e('Playful' ) ?></option>
				</select>
				<div class="option">
					<img id="fp5_minimalist" src="<?php print(FP5_PLUGIN_URL.'assets/img/minimalist.png')  ?>" />
					<img id="fp5_functional" src="<?php print(FP5_PLUGIN_URL.'assets/img/functional.png')  ?>" />
					<img id="fp5_playful" src="<?php print(FP5_PLUGIN_URL.'assets/img/playful.png')  ?>" />
				</div>
			</div>
			<div class="optgroup separated">
				<label for="fp5_videoAttributes">
					<?php _e('Video attributes')?> <a href="http://flowplayer.org/docs/index.html#video-attributes" target="_blank"><?php _e('(Info)')?></a>
				</label>
				<div class="wide"></div>
				<div id="fp5_videoAttributes" class="option">
					<label for="fp5_autoplay"><?php _e('Autoplay?')?></label>
					<input type="checkbox" name="fp5[autoplay]" id="fp5_autoplay" value="true" />
				</div>
				<div class="option">
					<label for="fp5_loop"><?php _e('Loop?')?></label>
					<input type="checkbox" name="fp5[loop]" id="fp5_loop" value="true" />
				</div>
			</div>

			<div class="optgroup">
				<div class="option wide">
					<label for="fp5_splash">
						<a href="http://flowplayer.org/docs/index.html#splash" target="_blank"><?php _e('Splash image')?></a><br/><?php _e('(optional)')?>
					</label>
					<input class="mediaUrl" type="text" name="fp5[splash]" id="fp5_splash" />
					<input id="fp5_chooseSplash" type="button" value="<?php _e('Media Library'); ?>" />
				</div>
			</div>

			<div class="optgroup separated">
				<div class="head" for="fp5_videos">
					<?php _e('URLs for videos, at least one is needed. You need a video format supported by your web browser, otherwise the preview below does not work.')?>
					<a href="http://flowplayer.org/docs/#video-formats" target="_blank"><?php _e('About video formats')?></a>.
				</div>
				<div class="option wide">
					<label for="fp5_mp4"><?php _e('mp4')?></label>
					<input class="mediaUrl" type="text" name="fp5[mp4]" id="fp5_mp4" />
				</div>
				<div id="fp5_videos" class="option wide">
					<label for="fp5_webm"><?php _e('webm')?></label>
					<input class="mediaUrl" type="text" name="fp5[webm]" id="fp5_webm" />
				</div>
				<div class="option wide">
					<label for="fp5_ogg"><?php _e('ogg')?></label>
					<input class="mediaUrl" type="text" name="fp5[ogg]" id="fp5_ogg" />
				</div>
					<input id="fp5_chooseMedia" type="button" value="<?php _e('Media Library'); ?>" />
			</div>

			<div class="optgroup">
				<div class="option">
					<div id="preview" class="preview"><?php _e( 'Preview' ) ?>
						<div class="flowplayer">
							<video id="fp5_videoPreview" width="320" height="240" controls="controls">
							</video>
						</div>
					</div>
				</div>
				<div class="details separated">
					<label for="fp5_width"><?php _e('Maximum dimensions for the player are determined from the provided video files. You can change this size below. Fixing the player size disables scaling for different screen sizes.')?></label>
					<div class="wide"></div>
					<div class="option">
						<label for="fp5_width"><?php _e('Max width')?></label>
						<input class="small" type="text" id="fp5_width" name="fp5[width]" />
					</div>
					<div class="option">
						<label class="checkbox" for="fp5_ratio"><?php _e('Use video\'s aspect ratio')?></label>
						<input class="checkbox" type="checkbox" id="fp5_ratio" name="fp5[ratio]" value="true" checked="checked"/>
					</div>
					<div class="option">
						<label for="fp5_height"><?php _e('Max height')?></label>
						<input class="small" type="text" id="fp5_height" name="fp5[height]" readonly="true"/>
					</div>
					<div class="option">
						<label class="checkbox" for="fp5_fixed"><?php _e('Use fixed player size') ?></label>
						<input class="checkbox" type="checkbox" id="fp5_fixed" name="fp5[fixed]" value="true" />
					</div>
				</div>
			</div>

			<div class="optgroup separated">
				<label class="head" for="fp5_subtitles">
					<?php _e('You can include subtitles by supplying an URL to a WEBVTT file')?>
					<a href="http://flowplayer.org/docs/subtitles.html" target="_blank"> <?php _e( 'Visit the subtitles documentation' ) ?></a>.
				</label>
				<div class="option wide">
					<label class="head" for="fp5_subtitles">
						<?php _e('WEBVTT URL')?>
					</label>
					<input class="mediaUrl" type="text" name="fp5[subtitles]" id="fp5_subtitles" />
				</div>
			</div>

			<div class="option wide">
				<input class="button-primary" id="fp5_sendToEditor" type="button" value="<?php _e('Send to Editor &raquo;'); ?>" />
			</div>
		</div>
	<?php
	}

}

// TODO:    Update the instantiation call of your plugin to the name given at the class definition
Flowplayer5::get_instance();