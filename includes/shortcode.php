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

	// set the options for the shortcode - pulled from the display-settings.php
	$options       = get_option('fp5_settings_general');
	$key           = $options['key'];
	$logo          = $options['logo'];
	$ga_account_id = $options['ga_account_id'];
	$logo_origin   = isset( $options['logo_origin'] );
	$cdn           = isset( $options['cdn_option'] );

	// Register ahortcode stylesheets and JavaScript
	if( isset( $cdn ) ) {
		wp_enqueue_style( $plugin_slug .'-skins' , 'http://releases.flowplayer.org/' . $player_version . '/skin/all-skins.css' );
		wp_enqueue_script( $plugin_slug . '-script', 'http://releases.flowplayer.org/' . $player_version . '/'. ( $key != '' ? 'commercial/' : '' ) . 'flowplayer.min.js', array( 'jquery' ), $player_version, false );
	} else {
		wp_enqueue_style( $plugin_slug .'-skins', plugins_url( '/assets/flowplayer/skin/all-skins.css', dirname(__FILE__) ), $player_version );
		wp_enqueue_script( $plugin_slug . '-script', plugins_url( '/assets/flowplayer/' . ( $key != '' ? "commercial/" : "" ) . 'flowplayer.min.js', dirname( __FILE__ ) ), array( 'jquery' ), $player_version, false );
	}

	if( isset ( $logo_origin ) ) {
		wp_enqueue_style( $plugin_slug .'-logo-origin', plugins_url( '/assets/css/public.css', dirname(__FILE__) ), $player_version );
	}

	/* <!-- global options -->
	<script>
	flowplayer.conf = {
		engine: "flash",
		swf: "/media/swf/flowplayer.swf",
		analytics: 'UA-27182341-1'
		embed: {
			library: "//mydomain.com/js/flowplayer.min.js",
			script: "//mydomain.com/js/embed.min.js",
			skin: "//mydomain.com/css/minimalist.css",
			swf: "//mydomain.com/swf/flowplayer.swf"
		}
	};
	</script> */

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
	$return  = '';
	$return .= '<div style="' . $fixed_style . $splash_style . ' background-size: contain;" class="' . $class . '" data-key="' . $data_key . '" data-logo="' . $data_logo . '" data-analytics="' . $data_analytics . '" data-ratio="' . $data_ratio . '">';
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