<?php
if(!class_exists('flowplayer5_Settings'))
{
	class flowplayer5_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			// register actions
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct

		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init() {
			// register your plugin's settings
			register_setting('flowplayer5-group', 'key');
			register_setting('flowplayer5-group', 'logo');

			// add your settings section
			add_settings_section(
				'flowplayer5-section', 
				'Commercial version', 
				array(&$this, 'settings_section_flowplayer5'), 
				'flowplayer5'
			);
			
			// add your setting's fields
			add_settings_field(
				'flowplayer5-key', 
				'License key', 
				array(&$this, 'settings_field_input_text'), 
				'flowplayer5', 
				'flowplayer5-section',
				array(
					'field' => 'key'
				)
			);
			add_settings_field(
				'flowplayer5-logo', 
				'Logo', 
				array(&$this, 'settings_field_input_upload2'), 
				'flowplayer5', 
				'flowplayer5-section',
				array(
					'field' => 'logo'
				)
			);
			// Possibly do additional admin_init tasks
		} // END public static function activate
		
		public function settings_section_flowplayer5() {
			// Think of this as help text for the section.
			printf( __( 'Commercial version removes the Flowplayer logo and allows you to use your own logo image. You can obtain and purchase a license key at <a%s>flowplayer.org</a>.' ),
				' href="http://flowplayer.org/download/" target="_blank" '
			);
		}

		/**
		 * This function provides text inputs for settings fields
		 */
		public function settings_field_input_text($args) {
			// Get the field name from the $args array
			$field = $args['field'];
			// Get the value of this setting
			$value = get_option($field);
			// echo a proper input type="text"
			echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
		} // END public function settings_field_input_text($args)

		/**
		 * This function provides upload input for settings fields
		 */
		public function settings_field_input_upload($args) {
			// Get the field name from the $args array
			$field = $args['field'];
			// Get the value of this setting
			$value = get_option($field);
			// echo a proper input type="text"
			echo sprintf('<div class="uploader"><input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
			// echo a proper input type="button"
			echo sprintf('<input type="button" name="%s_button" id="%s_button" value="Add Logo" /></div>', $field, $field);
		} // END public function settings_field_input_upload($args)

		public function settings_field_input_upload2() {

			echo '<div id="tgm-new-media-settings">';
				echo '<input type="text" id="tgm-new-media-image" value="" /><a href="#" class="tgm-open-media button button-primary" title="' . esc_attr__( 'Add Logo', 'tgm-nmp' ) . '">' . __( 'Add Logo', 'tgm-nmp' ) . '</a>';
			echo '</div>';

		}

		/**
		 * add a menu
		 */
		public function add_menu() {
			// Add a page to manage this plugin's settings
			add_options_page(
				'Flowplayer5', 
				'Flowplayer5', 
				'manage_options', 
				'flowplayer5', 
				array(&$this, 'plugin_settings_page')
			);
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */
		public function plugin_settings_page() {
			if(!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}

			// Render the settings template
			include( FP5_PLUGIN_DIR . 'includes/display-settings.php' );

		} // END public function plugin_settings_page()
	} // END class flowplayer5_Settings
} // END if(!class_exists('flowplayer5_Settings'))