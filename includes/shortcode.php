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

	// Code
	if ( isset( $id ) ) {
		return '<a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a>';
	}

}
add_shortcode( 'flowplayer', 'add_fp5_shortcode' );