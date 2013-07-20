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

// example shortcode [flowplayer id='39']

// Add Shortcode
function add_fp5_shortcode($atts) {

$version        = '1.0.0-beta';
$plugin_slug    = 'flowplayer5';
$player_version = '5.4.3';
global $post;

	//post_id
	$id = $atts['id'];

	// get the meta from the post type
	$loop      = get_post_meta( $id, 'fp5-loop', true );
	$autoplay  = get_post_meta( $id, 'fp5-autoplay', true );
	$preload   = get_post_meta( $id, 'fp5-preload', true );
	$poster    = '';
	$skin      = get_post_meta( $id, 'fp5-select-skin', true );
	$splash    = get_post_meta( $id, 'fp5-splash-image', true );
	$mp4       = get_post_meta( $id, 'fp5-mp4-video', true );
	$webm      = get_post_meta( $id, 'fp5-webm-video', true );
	$ogg       = get_post_meta( $id, 'fp5-ogg-video', true) ;
	$subtitles = get_post_meta( $id, 'fp5-vtt', true );
	$width     = get_post_meta( $id, 'fp5-max-width', true );
	$height    = get_post_meta( $id, 'fp5-max-height', true );
	$ratio     = get_post_meta( $id, 'fp5-aspect-ratio', true );
	$fixed     = get_post_meta( $id, 'fp5-fixed-width', true );

	// set the options for the shortcode - pulled from the register-settings.php
	$options       = get_option('fp5_settings_general');
	$key           = $options['key'];
	$logo          = $options['logo'];
	$ga_account_id = $options['ga_account_id'];

	// Shortcode processing
	$ratio          = ( isset ( $width ) && isset( $height ) ? intval($height) / intval($width) : '' );
	$fixed_style    = ( $fixed == 'true' && $width != '' && $height != '' ? 'width:' . $width . 'px; height:' . $height . 'px; ' : 'max-width:' . $width . 'px; ' );
	$splash_style   = 'background: #777 url(' . $splash . ') no-repeat;';
	$class          = 'flowplayer ' . $skin . ( ! empty ( $splash ) ? ' is-splash' : '' );
	$data_key       = ( isset ( $key ) ? $key : '');
	$data_logo      = ( isset ( $key ) && isset ( $logo ) ? $logo : '' );
	$data_analytics = ( isset ( $ga_account_id ) ? $ga_account_id  : '' );
	$data_ratio     = ( $ratio != 0 ? $ratio : '' );
	$attributes     = ( ( $autoplay == 'true' ) ? 'autoplay ' : '' ) . ( ( $loop == 'true' ) ? 'loop ' : '' ) . ( isset ( $preload ) ? 'preload="' . $preload . '" ' : '' ) . ( ( $poster == 'true' ) ? 'poster' : '' ); 

	// Shortcode output
	$return = '<div style="' . $fixed_style . $splash_style . ' background-size: contain;" class="' . $class . '" data-key="' . $data_key . '" data-logo="' . $data_logo . '" data-analytics="' . $data_analytics . '" data-ratio="' . $data_ratio . '">';
	$return .= '<video ' . $attributes . '>';
		$mp4       != '' ? $return.='<source type="video/mp4" src="' . $mp4 . '"/>' : '';
		$webm      != '' ? $return.='<source type="video/webm" src="' . $webm . '"/>' : '';
		$ogg       != '' ? $return.='<source type="video/ogg" src="' . $ogg . '"/>' : '';
		$subtitles != '' ? $return.='<track  type="text/vtt" src="' . $subtitles . '"/>' : '';
	$return .= '</video>';
	$return .= '</div>';

	$return .= '<script> </script>';

	return $return;
	}

// Register shortcode
add_shortcode( 'flowplayer', 'add_fp5_shortcode' );