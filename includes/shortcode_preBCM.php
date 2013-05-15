<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Add Shortcode
function add_fp5_shortcode( $atts ) {

	// Register ahortcode stylesheets and JavaScript
	if( $fp5_cdn == 'true' ) {
		wp_enqueue_style( 'fp5_skins' , 'http://releases.flowplayer.org/' . FP5_FLOWPLAYER_VERSION . '/skin/' . $skin . '.css' );
		wp_enqueue_script( 'fp5_embedder', 'http://releases.flowplayer.org/' . FP5_FLOWPLAYER_VERSION . '/'.($key != '' ? 'commercial/' : '') . 'flowplayer.min.js', array('jquery'), null, false);
	} else {
		wp_enqueue_style( 'fp5_skins' , plugins_url( '/assets/skin/' . $skin . '.css', dirname(__FILE__) ) );
		wp_enqueue_script('fp5_embedder', plugins_url( '/assets/flowplayer/'.($key != '' ? "commercial/" : "").'flowplayer.min.js', dirname(__FILE__) ), array('jquery'), null, false);
	}
	// Attributes
	extract( shortcode_atts(
		array(
			'id' => '',
		), $atts )
	);

	$options = get_option('fp5_options');
	$key = $options['key'];
	$logo = $options['logo'];
	$analytics = $options['ga_accountId'];
	$logoInOrigin = $options['logoInOrigin'];
	$flowplayer_cdn = $options['flowplayer_cdn'];

	// Code
	if ( isset( $id ) ) {
	'<script>';
		if ($key != '' && $logoInOrigin) {
			$out .= 'jQuery("head").append(jQuery(\'<style>.flowplayer .fp-logo { display: block; opacity: 1; }</style>\'));';
		}
	'</script>';
	$ratio = ($width != '' && $height != '' ? intval($height) / intval($width) : '');
	$fixed_style = ( $fixed == 'true' && $width != '' && $height != '' ? '"width:'.$width.'px;height:'.$height.'px;" ' : '"max-width:'.$width.'px"');
	$splash_style = 'background:#777 url(' . $splash . ') no-repeat;';
	$class = '"flowplayer ' . $skin . ( $splash != "" ? " is-splash" : "" ) . '"';
	$data_key = ( $key != '' ? ' "'.$key.'"' : '');
	$data_logo = ( $key != '' && $logo != '' ? ' "'.$logo.'"' : '' );
	$data_analytics = ( $analytics != '' ? ' "'.$analytics.'"' : '' );
	$data_ratio = ( $ratio != 0 ? '"'.$ratio.'"' : '' );
	$attributes = ( ( $autoplay == 'true' ) ? $autoplay : '' ); ( ( $loop == 'true' ) ? $loop : '' ); ( ( $preload  == 'true' ) ? $preload : '' );
		'<div style=' . $fixedStyle . $splash_style . ' class=' . $class . ' data-key=' . $data_key . ' data-logo=' . $data_logo . ' data-analytics=' . $data_analytics . ' data-ratio=' . $data_ratio . '>';
			'<video' . $attributes . '>';
				$mp4 != '' ? '<source type="video/mp4" src="' . $mp4 . '"/>' : '';
				$webm != '' ? '<source type="video/webm" src="' . $webm . '"/>' : '';
				$ogg != '' ? '<source type="video/ogg" src="' . $ogg . '"/>' : '';
				$subtitles != '' ? '<track src="' . $subtitles . '"/>' : '';
			'</video>';
		'</div>';

	'<script>

	</script>';
	}

}
add_shortcode( 'flowplayer', 'add_fp5_shortcode' );