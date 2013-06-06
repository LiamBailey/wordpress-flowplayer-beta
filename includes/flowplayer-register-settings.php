<?php
/**
 * Register Settings
 *
 * @package     EDD
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2013, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Registers all of the required EDD settings and provides hooks for extensions
 * to add their own settings to either the General, Gateways, Emails, Styles
 * or Misc Settings Pages
 *
 * @since 1.0
 * @return void
*/
function fp5_register_settings() {
	// Setup some default option sets
	$pages = get_pages();
	$pages_options = array( 0 => '' ); // Blank option
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$pages_options[ $page->ID ] = $page->post_title;
		}
	}

	/**
	 * 'Whitelisted' EDD settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 */
	$fp5_settings = array(
		/** General Settings */
		'meta-box' => apply_filters('fp5_settings_meta_box',
			array(
				'test_mode' => array(
					'id' => 'test_mode',
					'name' => __('Test Mode', 'edd'),
					'desc' => __('While in test mode no live transactions are processed. To fully use test mode, you must have a sandbox (test) account for the payment gateway you are testing.', 'edd'),
					'type' => 'checkbox'
				),
				'purchase_page' => array(
					'id' => 'purchase_page',
					'name' => __('Checkout Page', 'edd'),
					'desc' => __('This is the checkout page where buyers will complete their purchases. The [download_checkout] short code must be on this page.', 'edd'),
					'type' => 'select',
					'options' => $pages_options
				),
				'failure_page' => array(
					'id' => 'failure_page',
					'name' => __('Failed Transaction Page', 'edd'),
					'desc' => __('This is the page buyers are sent to if their transaction is cancelled or fails', 'edd'),
					'type' => 'upload',
				),
				'currency_settings' => array(
					'id' => 'currency_settings',
					'name' => '<strong>' . __('Currency Settings', 'edd') . '</strong>',
					'desc' => __('Configure the currency options', 'edd'),
					'type' => 'header'
				),
				'currency' => array(
					'id' => 'currency',
					'name' => __('Currency', 'edd'),
					'desc' => __('Choose your currency. Note that some payment gateways have currency restrictions.', 'edd'),
					'type' => 'multicheck',
				),
				'thousands_separator' => array(
					'id' => 'thousands_separator',
					'name' => __('Thousands Separator', 'edd'),
					'desc' => __('The symbol (usually , or .) to separate thousands', 'edd'),
					'type' => 'text',
					'size' => 'small',
					'std' => ','
				),
				'api_settings' => array(
					'id' => 'api_settings',
					'name' => '<strong>' . __('API Settings', 'edd') . '</strong>',
					'desc' => '',
					'type' => 'radio'
				),
				'api_allow_user_keys' => array(
					'id' => 'api_allow_user_keys',
					'name' => __('Allow User Keys', 'edd'),
					'desc' => __('Check this box to allow all users to generate API keys. Users with the \'manage_shop_settings\' capability are always allowed to generate keys.', 'edd'),
					'type' => 'checkbox'
				)
			)
		)
	);

	if ( false == get_option( 'fp5_settings_meta_box' ) ) {
		add_option( 'fp5_settings_meta_box' );
	}

	add_settings_section(
		'fp5_settings_meta_box',
		__( 'General Settings', 'edd' ),
		'__return_false',
		'fp5_settings_meta_box'
	);

	foreach ( $fp5_settings['general'] as $option ) {
		add_settings_field(
			'fp5_settings_meta_box[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'fp5_' . $option['type'] . '_callback' ) ? 'fp5_' . $option['type'] . '_callback' : 'fp5_missing_callback',
			'fp5_settings_meta_box',
			'fp5_settings_meta_box',
			array(
				'id' => $option['id'],
				'desc' => $option['desc'],
				'name' => $option['name'],
				'section' => 'general',
				'size' => isset( $option['size'] ) ? $option['size'] : null,
				'options' => isset( $option['options'] ) ? $option['options'] : '',
				'std' => isset( $option['std'] ) ? $option['std'] : ''
			)
		);
	}

	// Creates our settings in the options table
	register_setting( 'fp5_settings_meta_box', 'fp5_settings_meta_box', 'fp5_settings_sanitize' );
}
add_action('admin_init', 'fp5_register_settings');

/**
 * Header Callback
 *
 * Renders the header.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function fp5_header_callback( $args ) {
	echo '';
}

/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_checkbox_callback( $args ) {
	global $fp5_options;

	$checked = isset($fp5_options[$args['id']]) ? checked(1, $fp5_options[$args['id']], false) : '';
	$html = '<input type="checkbox" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked . '/>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_multicheck_callback( $args ) {
	global $fp5_options;

	foreach( $args['options'] as $key => $option ):
		if( isset( $fp5_options[$args['id']][$key] ) ) { $enabled = $option; } else { $enabled = NULL; }
		echo '<input name="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']"" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
		echo '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
	endforeach;
	echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Radio Callback
 *
 * Renders radio boxes.
 *
 * @since 1.3.3
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_radio_callback( $args ) {
	global $fp5_options;

	foreach ( $args['options'] as $key => $option ) :
		$checked = false;

		if ( isset( $fp5_options[ $args['id'] ] ) && $fp5_options[ $args['id'] ] == $key )
			$checked = true;
		elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $fp5_options[ $args['id'] ] ) )
			$checked = true;

		echo '<input name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(true, $checked, false) . '/>&nbsp;';
		echo '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
	endforeach;

	echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Gateways Callback
 *
 * Renders gateways fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_gateways_callback( $args ) {
	global $fp5_options;

	foreach ( $args['options'] as $key => $option ) :
		if ( isset( $fp5_options['gateways'][ $key ] ) )
			$enabled = '1';
		else
			$enabled = null;

		echo '<input name="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']"" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="checkbox" value="1" ' . checked('1', $enabled, false) . '/>&nbsp;';
		echo '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option['admin_label'] . '</label><br/>';
	endforeach;
}

/**
 * Gateways Callback (drop down)
 *
 * Renders gateways select menu
 *
 * @since 1.5
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_gateway_select_callback($args) {
	global $fp5_options;

	echo '<select name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']">';

	foreach ( $args['options'] as $key => $option ) :
		$selected = isset( $fp5_options[ $args['id'] ] ) ? selected( $key, $fp5_options[$args['id']], false ) : '';
		echo '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $option['admin_label'] ) . '</option>';
	endforeach;

	echo '</select>';
	echo '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';
}

/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_text_callback( $args ) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $args['size'] . '-text" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Textarea Callback
 *
 * Renders textarea fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_textarea_callback( $args ) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<textarea class="large-text" cols="50" rows="5" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']">' . esc_textarea( $value ) . '</textarea>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Password Callback
 *
 * Renders password fields.
 *
 * @since 1.3
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_password_callback( $args ) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<input type="password" class="' . $args['size'] . '-text" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Missing Callback
 *
 * If a function is missing for settings callbacks alert the user.
 *
 * @since 1.3.1
 * @param array $args Arguments passed by the setting
 * @return void
 */
function fp5_missing_callback($args) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'edd' ), $args['id'] );
}

/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_select_callback($args) {
	global $fp5_options;

	$html = '<select id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';

	foreach ( $args['options'] as $option => $name ) :
		$selected = isset( $fp5_options[ $args['id'] ] ) ? selected( $option, $fp5_options[$args['id']], false ) : '';
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Rich Editor Callback
 *
 * Renders rich editor fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @global $wp_version WordPress Version
 */
function fp5_rich_editor_callback( $args ) {
	global $fp5_options, $wp_version;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	if ( $wp_version >= 3.3 && function_exists( 'wp_editor' ) ) {
		$html = wp_editor( $value, 'fp5_settings_' . $args['section'] . '[' . $args['id'] . ']', array( 'textarea_name' => 'fp5_settings_' . $args['section'] . '[' . $args['id'] . ']' ) );
	} else {
		$html = '<textarea class="large-text" rows="10" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']">' . esc_textarea( $value ) . '</textarea>';
	}

	$html .= '<br/><label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Upload Callback
 *
 * Renders upload fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_upload_callback($args) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[$args['id']];
	else
		$value = isset($args['std']) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $args['size'] . '-text fp5_upload_field" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<span>&nbsp;<input type="button" class="fp5_settings_upload_button button-secondary" value="' . __( 'Upload File', 'edd' ) . '"/></span>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Color picker Callback
 *
 * Renders color picker fields.
 *
 * @since 1.6
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_color_callback( $args ) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$default = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<input type="text" class="edd-color-picker" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Shop States Callback
 *
 * Renders states drop down based on the currently selected country
 *
 * @since 1.6
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
function fp5_shop_states_callback($args) {
	global $fp5_options;

	$html = '<select id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';

	$states = fp5_get_shop_states();

	foreach ( $states as $option => $name ) :
		$selected = isset( $fp5_options[ $args['id'] ] ) ? selected( $option, $fp5_options[$args['id']], false ) : '';
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Registers the license field callback for Software Licensing
 *
 * @since 1.5
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the EDD Options
 * @return void
 */
if ( ! function_exists( 'fp5_license_key_callback' ) ) {
	function fp5_license_key_callback( $args ) {
		global $fp5_options;

		if ( isset( $fp5_options[ $args['id'] ] ) )
			$value = $fp5_options[ $args['id'] ];
		else
			$value = isset( $args['std'] ) ? $args['std'] : '';

		$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';

		$html = '<input type="text" class="' . $args['size'] . '-text" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';

		if ( 'valid' == get_option( $args['options']['is_valid_license_option'] ) ) {
			$html .= wp_nonce_field( $args['id'] . '_nonce', $args['id'] . '_nonce', false );
			$html .= '<input type="submit" class="button-secondary" name="' . $args['id'] . '_deactivate" value="' . __( 'Deactivate License',  'edd' ) . '"/>';
		}

		$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

		echo $html;
	}
}

/**
 * Hook Callback
 *
 * Adds a do_action() hook in place of the field
 *
 * @since 1.0.8.2
 * @param array $args Arguments passed by the setting
 * @return void
 */
function fp5_hook_callback( $args ) {
	do_action( 'fp5_' . $args['id'] );
}

/**
 * Settings Sanitization
 *
 * Adds a settings error (for the updated message)
 * At some point this will validate input
 *
 * @since 1.0.8.2
 * @param array $input The value inputted in the field
 * @return string $input Sanitizied value
 */
function fp5_settings_sanitize( $input ) {
	add_settings_error( 'edd-notices', '', __('Settings Updated', 'edd'), 'updated' );
	return $input;
}

/**
 * Get Settings
 *
 * Retrieves all plugin settings and returns them as a combined array.
 *
 * @since 1.0
 * @return array Merged array of all the EDD settings
 */
function fp5_get_settings() {
	$general_settings = is_array( get_option( 'fp5_settings_meta_box' ) )    ? get_option( 'fp5_settings_meta_box' )  : array();

	return array_merge( $general_settings, $gateway_settings, $email_settings, $style_settings, $tax_settings, $ext_settings, $misc_settings );
}