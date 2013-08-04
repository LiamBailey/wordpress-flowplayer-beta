<?php
/**
 * Update Flowplayer5 to 2.0.0
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

// Get entire array
$plugin_options = get_option( 'fp5_options' );

$new_options = array();

// Update keys
if( isset( $plugin_options['ga_accountId'] ) )
	$new_options['ga_account_id'] = $plugin_options['ga_accountId'];
if( isset( $plugin_options['key'] ) )
	$new_options['key'] = $plugin_options['key'];
if( isset( $plugin_options['logo'] ) )
	$new_options['logo'] = $plugin_options['logo'];
if( isset( $plugin_options['logoInOrigin'] ) )
	$new_options['logo_origin'] = $plugin_options['logoInOrigin'];

// Update entire array
update_option( 'fp5_settings_general', $new_options );
// Delte old array
delete_option( 'fp5_options' );

add_action( 'init', 'convert_video_shortcode' );

// this needs to be completed... now standalone used to be included in the register shortcode script
function convert_video_shortcode( $shortcode_array ){/* run the conversion script on the post on the fly.

	[flowplayer splash="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.jpg" webm="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.webm" mp4="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.mp4" ogg="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.ogv" width="1920" height="1080" skin="functional" autoplay="true" loop="true" fixed="true" subtitles="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.vtt"]

	This may need to be turned into a class and activated on the settings page via a "run conversion" button.

	Conversion steps:

	1) Gather video data from shortcode
	2) Create custom fp5video post and add video data to it (linking videos to that post, etc.)
		- add appropriate video data to meta-fields on new video post
		- add specified splash image as the video post featured image
	3) replace existing shortcode with new shortcode that references the newly create fp5video post.

	*/

	// get values from shortcode
	$old_shortcode_atts = shortcode_atts(
		array(
			'mp4' => '',
			'webm' => '',
			'ogg' => '',
			'skin' => 'minimalist',
			'splash' => '',
			'autoplay' => 'false',
			'loop' => 'false',
			'subtitles' => '',
			'width' => '',
			'height' => '',
			'fixed' => 'false'
		),
		$atts
	);

	// get the id of the current post
	$the_post_id =  get_the_id();

	// create custom video post, return id
	$vid_post_id = create_fp5video_post( $old_shortcode_atts );

	// add meta to the new video post
	$addpostmeta = add_meta_to_fp5video( $vid_post_id, $old_shortcode_atts );

}

/***
 * 
 * Shortcode processing helper functions
 * 
 */

// create the new "video" post
function fp5_create_video_post( $array ){

	// create video name and slug
	$namearray = fp5_create_title( $array );

	$title = $namearray['title'];

	// create new video post
	$video = array(
		'post_type'   => 'flowplayer5'
		'post_title'  => $title,
		'post_status' => 'publish',
	);
	
	$video_post_id = wp_insert_post( $video );

	return $video_post_id;
}

// create the slug and title for the new video post
function fp5_create_title( $stuff ){
	extract( $stuff );

	if( $mp4 ):
		$title = basename( $mp4, ".mp4" );
		$slug = sanitize_title_with_dashes( $title, $unused='', $context = 'display' );
	elseif( $webm ):
		$title = basename( $webm, ".webm" );
		$slug = sanitize_title_with_dashes( $title, $unused='', $context = 'display' );
	elseif( $ogg ):
		$title = basename( $ogg, ".ogg" );
		$slug = sanitize_title_with_dashes( $title, $unused='', $context = 'display' );
	endif;

	$namearray = array( 'title'=>$title );

return $namearray;

}

// add meta to the new video post
function add_meta_to_fp5_video( $video_post_id, $old_shortcode_atts ){

	$unique = true;

	if( $old_shortcode_atts['mp4'] ){
		add_post_meta( $video_post_id, 'fp5-mp4-video', $old_shortcode_atts['mp4'], $unique );
	}

	if( $old_shortcode_atts['webm']){
		add_post_meta( $video_post_id, 'fp5-webm-video', $old_shortcode_atts['webm'], $unique );
	}

	if( $old_shortcode_atts['ogg'] ){
		add_post_meta( $video_post_id, 'fp5-ogg-video', $old_shortcode_atts['ogg'], $unique );
	}

	if( $old_shortcode_atts['skin'] ){
		add_post_meta( $video_post_id, 'fp5-select-skin', $old_shortcode_atts['skin'], $unique );
	}

	if( $old_shortcode_atts['splash'] ){
		add_post_meta( $video_post_id, 'fp5-splash-image', $old_shortcode_atts['splash'], $unique );
	}

	if( $old_shortcode_atts['autoplay'] ){
		add_post_meta( $video_post_id, 'fp5-autoplay', $old_shortcode_atts['autoplay'], $unique  );
	}

	if( $old_shortcode_atts['loop'] ){
		add_post_meta( $video_post_id, 'fp5-loop', $old_shortcode_atts['loop'], $unique  );
	}

	if( $old_shortcode_atts['subtitles'] ){
		add_post_meta( $video_post_id, 'fp5-vtt-subtitles', $old_shortcode_atts['subtitles'], $unique );
	}

	if( $old_shortcode_atts['width'] ){
		add_post_meta( $video_post_id, 'fp5-width', $old_shortcode_atts['width'], $unique );
	}

	if( $old_shortcode_atts['height'] ){
		add_post_meta( $video_post_id, 'fp5-height', $old_shortcode_atts['height'], $unique );
	}

	if( $old_shortcode_atts['fixed'] ){
		add_post_meta( $video_post_id, 'fp5-fixed-width', $old_shortcode_atts['fixed'], $unique  );
	}

}

// generate the shortcode to replace old shortcode
function generate_new_shortcode( $video_post_id ) {
	$shortcode = '[flowplayer id="' . $video_post_id . '"]';
	return $shortcode;
}