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

	if(is_admin()){
	add_action( 'admin_init', 'edd_register_settings' );
	}


function edd_register_settings() {

/**
	 * 'Whitelisted' EDD settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 */
	$edd_settings = array(
		/** General Settings */
		'general' => apply_filters('edd_settings_general',
			array(
				'logo_origin' => array(
					'id' => 'logo_origin',
					'name' => __('Show Logo on this site', 'edd'),
					'desc' => __('Uncheck this and the logo is only shown in externally embedded players.', 'edd'),
					'type' => 'checkbox'
				),
				'commercial_version' => array(
					'id' => 'commercial_version',
					'name' => '<h4>' . __('Commercial Version', 'edd') . '</h4>',
					'desc' => __('Commercial version removes the Flowplayer logo and allows you to use your own logo image. You can purchase a license and obtain a license key in flowplayer.org.', 'edd'),
					'type' => 'header'
				),
				'select_skin' => array(
					'id' => 'select_skin',
					'name' => __('Select skin', 'edd'),
					'desc' => __('This is the checkout page where buyers will complete their purchases. The [download_checkout] short code must be on this page.', 'edd'),
					'type' => 'select',
					'std' => '1',
					'options' => array(
						'1' => __('Minimalist', 'edd'),
						'2' => __('Functional', 'edd'),
						'3'  => __('Playful', 'edd')
					)
				),
				'video_tracking' => array(
					'id' => 'video_tracking',
					'name' => '<strong>' . __('Video Tracking', 'edd') . '</strong>',
					'desc' => __('Configure the video attribYou can track video traffic using Google Analytics (GA). Visit flowplayer.org for more info.utes', 'edd'),
					'type' => 'header'
				),
				'ga_accountId' => array(
					'id' => 'ga_accountId',
					'name' => __('GA account ID', 'edd'),
					'desc' => __('Specify your GA account ID here.', 'edd'),
					'type' => 'text',
					'size' => 'small',
				),
				'autoplay' => array(
					'id' => 'autoplay',
					'name' => __('Autoplay', 'edd'),
					'desc' => __('Check the box is enable autoplay.', 'edd'),
					'type' => 'checkbox'
				),
				'loop' => array(
					'id' => 'loop',
					'name' => __('Loop', 'edd'),
					'desc' => __('Check the box is enable video loop.', 'edd'),
					'type' => 'checkbox'
				),
				'splash' => array(
					'id' => 'splash',
					'name' => '<strong>' . __('Splash Image', 'edd') . '</strong>',
					'desc' => __('Configure the video attributes', 'edd'),
					'type' => 'header'
				),
				'splash_image' => array(
					'id' => 'splash_image',
					'name' => __( 'Splash Image', 'edd'),
					'desc' => __('This is the page buyers are sent to if their transaction is cancelled or fails', 'edd'),
					'type' => 'upload',
				),
				'video_files' => array(
					'id' => 'video_files',
					'name' => '<strong>' . __('Add Videos', 'edd') . '</strong>',
					'desc' => __('URLs for videos, at least one is needed. You need a video format supported by your web browser, otherwise the preview below does not work. About video formats.', 'edd'),
					'type' => 'header'
				),
				'mp4' => array(
					'id' => 'mp4',
					'name' => __( 'mp4 format', 'edd'),
					'desc' => __('This is the page buyers are sent to if their transaction is cancelled or fails', 'edd'),
					'type' => 'upload',
					'size' => 'large',
				),
				'webm' => array(
					'id' => 'webm',
					'name' => __( 'webm format', 'edd'),
					'desc' => __('This is the page buyers are sent to if their transaction is cancelled or fails', 'edd'),
					'type' => 'upload',
				),
				'ogg' => array(
					'id' => 'ogg',
					'name' => __( 'ogg format', 'edd'),
					'desc' => __('This is the page buyers are sent to if their transaction is cancelled or fails', 'edd'),
					'type' => 'upload',
				),
				'video_dimentions' => array(
					'id' => 'video_dimentions',
					'name' => '<strong>' . __('Video Dimentions', 'edd') . '</strong>',
					'desc' => __('Configure the video dimensions', 'edd'),
					'type' => 'header'
				),
				'max_width' => array(
					'id' => 'max_width',
					'name' => __('Max Width', 'edd'),
					'desc' => __('The symbol (usually , or .) to separate thousands', 'edd'),
					'type' => 'text',
					'size' => 'small',
				),
				'max_height' => array(
					'id' => 'max_height',
					'name' => __('Max Height', 'edd'),
					'desc' => __('The symbol (usually , or .) to separate thousands', 'edd'),
					'type' => 'text',
					'size' => 'small',
				),
				'aspect_ratio' => array(
					'id' => 'aspect_ratio',
					'name' => __('Use videos aspect ratio', 'edd'),
					'desc' => __('Check the box is enable autoplay.', 'edd'),
					'type' => 'checkbox'
				),
				'fixed_width' => array(
					'id' => 'fixed_width',
					'name' => __('Fixed video', 'edd'),
					'desc' => __('Check the box is enable autoplay.', 'edd'),
					'type' => 'checkbox'
				),
				'subtitles' => array(
					'id' => 'subtitles',
					'name' => '<strong>' . __('Subtitles', 'edd') . '</strong>',
					'desc' => __('Configure the video attributes', 'edd'),
					'type' => 'header'
				),
				'subtitles_file' => array(
					'id' => 'subtitles_file',
					'name' => __( 'Subtitles', 'edd'),
					'desc' => __('This is the page buyers are sent to if their transaction is cancelled or fails', 'edd'),
					'type' => 'upload',
				),
				'currency' => array(
					'id' => 'currency',
					'name' => __('Currency', 'edd'),
					'desc' => __('Choose your currency. Note that some payment gateways have currency restrictions.', 'edd'),
					'type' => 'multicheck',
					'options' => apply_filters('edd_accepted_payment_icons', array(
							'mastercard' => 'Mastercard',
							'visa' => 'Visa',
							'americanexpress' => 'American Express',
							'discover' => 'Discover',
							'paypal' => 'PayPal'
						)
					)
				),
				'api_settings' => array(
					'id' => 'api_settings',
					'name' => '<strong>' . __('API Settings', 'edd') . '</strong>',
					'desc' => '',
					'type' => 'radio',
					'std' => 'no',
					'options' => array(
						'yes' => __('Yes, I will enter prices inclusive of tax', 'edd'),
						'no'  => __('No, I will enter prices exclusive of tax', 'edd')
					)
				)
			)
		)
	);

	if ( false == get_option( 'edd_settings_general' ) ) {
		add_option( 'edd_settings_general' );
	}

	add_settings_section(
		'edd_settings_general',
		__( 'General Settings', 'edd' ),
		'__return_false',
		'edd_settings_general'
	);

	foreach ( $edd_settings['general'] as $option ) {
		add_settings_field(
			'edd_settings_general[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'edd_' . $option['type'] . '_callback' ) ? 'edd_' . $option['type'] . '_callback' : 'edd_missing_callback',
			'edd_settings_general',
			'edd_settings_general',
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
	register_setting( 'edd_settings_general',    'edd_settings_general',    'edd_settings_sanitize' );

}

/**
 * Header Callback
 *
 * Renders the header.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function edd_header_callback( $args ) {
	echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_checkbox_callback( $args ) {
	global $edd_options;

	$checked = isset($edd_options[$args['id']]) ? checked(1, $edd_options[$args['id']], false) : '';
	$html = '<input type="checkbox" id="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked . '/>';
	$html .= '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_multicheck_callback( $args ) {
	global $edd_options;

	foreach( $args['options'] as $key => $option ):
		if( isset( $edd_options[$args['id']][$key] ) ) { $enabled = $option; } else { $enabled = NULL; }
		echo '<input name="edd_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']"" id="edd_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
		echo '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
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
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_radio_callback( $args ) {
	global $edd_options;

	foreach ( $args['options'] as $key => $option ) :
		$checked = false;

		if ( isset( $edd_options[ $args['id'] ] ) && $edd_options[ $args['id'] ] == $key )
			$checked = true;
		elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $edd_options[ $args['id'] ] ) )
			$checked = true;

		echo '<input name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"" id="edd_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(true, $checked, false) . '/>&nbsp;';
		echo '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
	endforeach;

	echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_text_callback( $args ) {
	global $edd_options;

	if ( isset( $edd_options[ $args['id'] ] ) )
		$value = $edd_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $args['size'] . '-text" id="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Textarea Callback
 *
 * Renders textarea fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_textarea_callback( $args ) {
	global $edd_options;

	if ( isset( $edd_options[ $args['id'] ] ) )
		$value = $edd_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<textarea class="large-text" cols="50" rows="5" id="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']">' . esc_textarea( $value ) . '</textarea>';
	$html .= '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Password Callback
 *
 * Renders password fields.
 *
 * @since 1.3
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_password_callback( $args ) {
	global $edd_options;

	if ( isset( $edd_options[ $args['id'] ] ) )
		$value = $edd_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<input type="password" class="' . $args['size'] . '-text" id="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

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
function edd_missing_callback($args) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'edd' ), $args['id'] );
}

/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_select_callback($args) {
	global $edd_options;

	$html = '<select id="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';

	foreach ( $args['options'] as $option => $name ) :
		$selected = isset( $edd_options[ $args['id'] ] ) ? selected( $option, $edd_options[$args['id']], false ) : '';
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Upload Callback
 *
 * Renders upload fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_upload_callback($args) {
	global $edd_options;

	if ( isset( $edd_options[ $args['id'] ] ) )
		$value = $edd_options[$args['id']];
	else
		$value = isset($args['std']) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $args['size'] . '-text edd_upload_field" id="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<span>&nbsp;<input type="button" class="edd_settings_upload_button button-secondary" value="' . __( 'Upload File', 'edd' ) . '"/></span>';
	$html .= '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Registers the license field callback for Software Licensing
 *
 * @since 1.5
 * @param array $args Arguments passed by the setting
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
if ( ! function_exists( 'edd_license_key_callback' ) ) {
	function edd_license_key_callback( $args ) {
		global $edd_options;

		if ( isset( $edd_options[ $args['id'] ] ) )
			$value = $edd_options[ $args['id'] ];
		else
			$value = isset( $args['std'] ) ? $args['std'] : '';

		$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';

		$html = '<input type="text" class="' . $args['size'] . '-text" id="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="edd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';

		if ( 'valid' == get_option( $args['options']['is_valid_license_option'] ) ) {
			$html .= wp_nonce_field( $args['id'] . '_nonce', $args['id'] . '_nonce', false );
			$html .= '<input type="submit" class="button-secondary" name="' . $args['id'] . '_deactivate" value="' . __( 'Deactivate License',  'edd' ) . '"/>';
		}

		$html .= '<label for="edd_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

		echo $html;
	}
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
function edd_settings_sanitize( $input ) {
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
function edd_get_settings() {
	$general_settings = is_array( get_option( 'edd_settings_general' ) )    ? get_option( 'edd_settings_general' )  : array();

	return array_merge( $general_settings );
}