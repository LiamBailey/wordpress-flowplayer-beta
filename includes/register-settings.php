<?php
/**
 * Flowplayer 5 for Wordpress
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function fp5_register_settings() {

	/**
	 * 'Whitelisted' fp5 settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 */
	$fp5_settings = array(
		'general' => apply_filters('fp5_settings_general',
			array(
				'commercial_version' => array(
					'id' => 'commercial_version',
					'name' => '<strong>' . __('Commercial Version', 'flowplayer5') . '</strong>',
					'desc' => __('Commercial version removes the Flowplayer logo and allows you to use your own logo image. You can purchase a license and obtain a license key in flowplayer.org.', 'fp5'),
					'type' => 'header'
				),
				'key' => array(
					'id' => 'key',
					'name' => __('License Key', 'fp5'),
					'desc' => __('Specify your License Key here.', 'flowplayer5'),
					'type' => 'text',
					'size' => 'medium'
				),
				'logo' => array(
					'id' => 'logo',
					'name' => __( 'Logo', 'flowplayer5'),
					'type' => 'upload',
					'size' => 'large',
					'desc' => __('Uncheck this and the logo is only shown in externally embedded players.', 'flowplayer5'),
					'preview' => 'true'
				),
				'logo_origin' => array(
					'id' => 'logo_origin',
					'name' => __('Show Logo on this site', 'fp5'),
					'desc' => __('Uncheck this and the logo is only shown in externally embedded players.', 'flowplayer5'),
					'type' => 'checkbox'
				),
				'video_tracking' => array(
					'id' => 'video_tracking',
					'name' => '<strong>' . __('Video Tracking', 'fp5') . '</strong>',
					'desc' => __('You can track video traffic using Google Analytics (GA). Visit flowplayer.org for more info', 'flowplayer5'),
					'type' => 'header'
				),
				'ga_account_id' => array(
					'id' => 'ga_account_id',
					'name' => __('Goofle Analytics account ID', 'flowplayer5'),
					'desc' => __('Specify your GA account ID here.', 'flowplayer5'),
					'type' => 'text',
					'size' => 'medium'
				),
				'cdn_options' => array(
					'id' => 'cdn_options',
					'name' => '<strong>' . __('CDN Options', 'fp5') . '</strong>',
					'desc' => __('If you want to use a self-hosted copy of Flowplayer instead of the CDN hosted version uncheck Use CDN hosted version? Using the CDN hosted version is preferable in most situations.', 'flowplayer5'),
					'type' => 'header'
				),
				'cdn_option' => array(
					'id' => 'cdn_option',
					'name' => __('CDN Hosted files', 'flowplayer5'),
					'desc' => __('Uncheck this to load the files from this site', 'flowplayer5'),
					'type' => 'checkbox'
				)
			)
		)
	);

	if ( false == get_option( 'fp5_settings_general' ) ) {
		add_option( 'fp5_settings_general' );
	}

	add_settings_section(
		'fp5_settings_general',
		__( 'General Settings', 'flowplayer5' ),
		'__return_false',
		'flowplayer5_settings'
	);

	foreach ( $fp5_settings['general'] as $option ) {
		add_settings_field(
			'fp5_settings_general[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'fp5_' . $option['type'] . '_callback' ) ? 'fp5_' . $option['type'] . '_callback' : 'fp5_missing_callback',
			'flowplayer5_settings',
			'fp5_settings_general',
			array(
				'id' => $option['id'],
				'desc' => $option['desc'],
				'name' => $option['name'],
				'section' => 'general',
				'preview' => isset( $option['preview'] ) ? $option['preview'] : null,
				'size' => isset( $option['size'] ) ? $option['size'] : null,
				'options' => isset( $option['options'] ) ? $option['options'] : '',
				'std' => isset( $option['std'] ) ? $option['std'] : ''
			)
		);
	}

	// Creates our settings in the options table
	register_setting( 'fp5_settings_group', 'fp5_settings_general', 'fp5_settings_sanitize' );

}

add_action( 'admin_init', 'fp5_register_settings' );

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
	echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the fp5 Options
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
 * Upload Callback
 *
 * Renders upload fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the fp5 Options
 * @return void
 */
function fp5_upload_callback($args) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $args['size'] . '-text fp5_upload_field" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<a href="#" type="button" class="fp5_settings_upload_button button-secondary" title="' . __( 'Upload Logo', 'fp5' ) . '"/>' . __( 'Upload Logo', 'fp5' ) . '</a>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';
	$html .= isset( $args['preview'] ) && !is_null( $args['preview'] ) ? '<img style="max-width: 300px; display:block" src="' . esc_attr( $value ) . '" class="fp5_settings_upload_preview"/>' : '';

	echo $html;
}

/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $fp5_options Array of all the fp5 Options
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
 * Missing Callback
 *
 * If a function is missing for settings callbacks alert the user.
 *
 * @since 1.3.1
 * @param array $args Arguments passed by the setting
 * @return void
 */
function fp5_missing_callback($args) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'flowplayer5' ), $args['id'] );
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
	add_settings_error( 'fp5-notices', '', __('Settings Updated', 'flowplayer5'), 'updated' );
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
	$general_settings = is_array( get_option( 'fp5_settings_general' ) ) ? get_option( 'fp5_settings_general' ) : array();

	return array_merge( $general_settings );
}