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
		/** Commercial Settings */
		'commercial' => apply_filters('fp5_settings_commercial',
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
				)
			)
		)
	);

	if ( false == get_option( 'fp5_settings_commercial' ) ) {
		add_option( 'fp5_settings_commercial' );
	}

	add_settings_section(
		'fp5_settings_commercial',
		__( 'Commercial Version', 'fp5' ),
		'fp5_settings_commercial_description_callback',
		'flowplayer5'
	);

	foreach ( $fp5_settings['commercial'] as $option ) {
		add_settings_field(
			'fp5_settings_commercial[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'fp5_' . $option['type'] . '_callback' ) ? 'fp5_' . $option['type'] . '_callback' : 'fp5_missing_callback',
			'fp5_settings_commercial',
			'fp5_settings_commercial',
			array(
				'id' => $option['id'],
				'desc' => $option['desc'],
				'name' => $option['name'],
				'section' => 'commercial',
				'size' => isset( $option['size'] ) ? $option['size'] : null,
				'options' => isset( $option['options'] ) ? $option['options'] : '',
				'std' => isset( $option['std'] ) ? $option['std'] : ''
			)
		);
	}

	// Creates our settings in the options table
	register_setting( 'fp5_settings_commercial', 'fp5_settings_commercial', 'fp5_settings_sanitize' );

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
 * Settings Taxes Description Callback
 *
 * Renders the taxes section description.
 *
 * @since 1.3.3
 * @return void
 */
function fp5_settings_commercial_description_callback() {
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