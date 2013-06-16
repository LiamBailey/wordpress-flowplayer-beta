<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * Use PHPDoc tags if you wish to be able to document the code using a documentation
 * generator.
 *
 * @package PluginName
 * @author  Your Name <email@example.com>
 * @license GPL-2.0+
 * @link    TODO
 * @version 1.0.0
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

class wctest{
	public function __construct(){
		if(is_admin()){
		add_action('admin_menu', array($this, 'add_plugin_page'));
		add_action('admin_init', array($this, 'page_init'));
	}
	}

	public function add_plugin_page(){
	// This page will be under "Settings"
	add_options_page('Settings Admin', 'Settings', 'manage_options', 'test-setting-admin', array($this, 'create_admin_page'));
	add_submenu_page('edit.php?post_type=flowplayer5video', 'Settings', 'Settings', 'edit_posts', basename(__FILE__), 'service_settings');
	}

	public function create_admin_page(){
		?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Settings</h2>
		<form method="post" action="options.php">
			<?php
			// This prints out all hidden setting fields
			settings_fields('test_option_group');
			do_settings_sections('test-setting-admin');
		?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
	}

	public function page_init(){
	register_setting('test_option_group', 'array_key', array($this, 'check_ID'));
		
		add_settings_section(
		'setting_section_id',
		'Setting',
		array($this, 'print_section_info'),
		'test-setting-admin'
	);

	add_settings_field(
		'some_id', 
		'Some ID(Title)', 
		array($this, 'create_an_id_field'), 
		'test-setting-admin',
		'setting_section_id'
	);
	}

	public function check_ID($input){
		if(is_numeric($input['some_id'])){
		$mid = $input['some_id'];
		if(get_option('test_some_id') === FALSE){
		add_option('test_some_id', $mid);
		}else{
		update_option('test_some_id', $mid);
		}
	}else{
		$mid = '';
	}
	return $mid;
	}

	public function print_section_info(){
	print 'Enter your setting below:';
	}

	public function create_an_id_field(){
		?><input type="text" id="input_whatever_unique_id_I_want" name="array_key[some_id]" value="<?=get_option('test_some_id');?>" /><?php
	}
}

$wctest = new wctest();