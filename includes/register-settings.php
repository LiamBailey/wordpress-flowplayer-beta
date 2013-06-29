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
					'name' => '<h4>' . __('Commercial Version', 'fp5') . '</h4>',
					'desc' => __('Commercial version removes the Flowplayer logo and allows you to use your own logo image. You can purchase a license and obtain a license key in flowplayer.org.', 'fp5'),
					'type' => 'header'
				),
				'key' => array(
					'id' => 'key',
					'name' => __('License Key', 'fp5'),
					'desc' => __('Specify your License Key here.', 'fp5'),
					'type' => 'text',
					'size' => 'medium'
				),
				'logo' => array(
					'id' => 'logo',
					'name' => __( 'Logo', 'fp5'),
					'type' => 'upload',
					'size' => 'medium',
					'desc' => __('Uncheck this and the logo is only shown in externally embedded players.', 'fp5')
				),
				'logo_origin' => array(
					'id' => 'logo_origin',
					'name' => __('Show Logo on this site', 'fp5'),
					'desc' => __('Uncheck this and the logo is only shown in externally embedded players.', 'fp5'),
					'type' => 'checkbox'
				),
				'video_tracking' => array(
					'id' => 'video_tracking',
					'name' => '<strong>' . __('Video Tracking', 'fp5') . '</strong>',
					'desc' => __('You can track video traffic using Google Analytics (GA). Visit flowplayer.org for more info', 'fp5'),
					'type' => 'header'
				),
				'ga_accountId' => array(
					'id' => 'ga_accountId',
					'name' => __('Goofle Analytics account ID', 'fp5'),
					'desc' => __('Specify your GA account ID here.', 'fp5'),
					'type' => 'text',
					'size' => 'medium'
				),
				'cdn_options' => array(
					'id' => 'cdn_options',
					'name' => '<strong>' . __('CDN Options', 'fp5') . '</strong>',
					'desc' => __('If you want to use a self-hosted copy of Flowplayer instead of the CDN hosted version uncheck Use CDN hosted version? Using the CDN hosted version is preferable in most situations.', 'fp5'),
					'type' => 'header'
				),
				'cdn_option' => array(
					'id' => 'cdn_option',
					'name' => __('CDN Hosted files', 'fp5'),
					'desc' => __('Uncheck this to load the files from this site', 'fp5'),
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
		__( 'General Settings', 'fp5' ),
		'__return_false',
		'flowplayer5'
	);


	foreach ( $fp5_settings['general'] as $option ) {
		add_settings_field(
			'fp5_settings_general[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'fp5_' . $option['type'] . '_callback' ) ? 'fp5_' . $option['type'] . '_callback' : 'fp5_missing_callback',
			'flowplayer5',
			'fp5_settings_general',
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
		$value = $fp5_options[$args['id']];
	else
		$value = isset($args['std']) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $args['size'] . '-text fp5_upload_field" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<a href="#" type="button" class="fp5_settings_upload_button button-secondary" title="' . __( 'Upload Logo', 'fp5' ) . '"/>' . __( 'Upload Logo', 'fp5' ) . '</a>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

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
 * Settings General Description Callback
 *
 * Renders the general section description.
 *
 * @since 1.3.3
 * @return void
 */
function fp5_settings_general_description_callback() {
	echo __('Commercial version removes the Flowplayer logo and allows you to use your own logo image. You can purchase a license and obtain a license key in flowplayer.org.', 'fp5');
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
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'fp5' ), $args['id'] );
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
	add_settings_error( 'fp5-notices', '', __('Settings Updated', 'fp5'), 'updated' );
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
	$commercial_settings = is_array( get_option( 'fp5_settings_commercial' ) ) ? get_option( 'fp5_settings_commercial' ) : array();

	return array_merge( $commercial_settings );
}