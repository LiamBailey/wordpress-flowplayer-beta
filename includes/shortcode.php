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
function add_fp5_shortcode( $atts ) {

	global $post;

	// get post id
	$id = $atts['id'];
	if ( $id ) {
	// get the meta from the post type
	$loop           = get_post_meta( $id, 'fp5-loop', true );
	$autoplay       = get_post_meta( $id, 'fp5-autoplay', true );
	$preload        = get_post_meta( $id, 'fp5-preload', true );
	$poster         = '';
	$fixed_controls = get_post_meta( $id, 'fp5-fixed-contols', true );
	$coloring       = get_post_meta( $id, 'fp5-coloring', true );
	$skin           = get_post_meta( $id, 'fp5-select-skin', true );
	$splash         = get_post_meta( $id, 'fp5-splash-image', true );
	$mp4            = get_post_meta( $id, 'fp5-mp4-video', true );
	$webm           = get_post_meta( $id, 'fp5-webm-video', true );
	$ogg            = get_post_meta( $id, 'fp5-ogg-video', true) ;
	$subtitles      = get_post_meta( $id, 'fp5-vtt', true );
	$max_width      = get_post_meta( $id, 'fp5-max-width', true );
	$width          = get_post_meta( $id, 'fp5-width', true );
	$height         = get_post_meta( $id, 'fp5-height', true );
	$ratio          = get_post_meta( $id, 'fp5-aspect-ratio', true );
	$fixed          = get_post_meta( $id, 'fp5-fixed-width', true );

	// set the options for the shortcode - pulled from the register-settings.php
	$options       = get_option('fp5_settings_general');
	$key           = $options['key'];
	$logo          = $options['logo'];
	$ga_account_id = $options['ga_account_id'];

	// Shortcode processing
	$ratio            = ( ( $width != 0 && $height != 0 ) ? intval( $height ) / intval( $width ) : '' );
	$size             = ( $fixed == 'true' && $width != '' && $height != '' ? 'width:' . $width . 'px; height:' . $height . 'px; ' : ( ( $max_width != 0 ) ?  'max-width:' . $max_width . 'px; ' : '' ) );
	$splash_style     = 'background: #777 url(' . $splash . ') no-repeat;';
	$class            = 'flowplayer ' . $skin . ' ' . ( ! empty ( $splash ) ? 'is-splash ' : '' );
	$data_key         = ( 0 < strlen ( $key ) ? 'data-key="' . $key . '"' : '');
	$data_logo        = ( 0 < strlen  ( $key ) && 0 < strlen  ( $logo ) ? 'data-logo="' . $logo . '" ' : '' );
	$data_analytics   = ( 0 < strlen  ( $ga_account_id ) ? 'data-analytics="' . $ga_account_id . '" ' : '' );
	$data_ratio       = ( $ratio != 0 ? 'data-ratio="' . $ratio . '"' : '' );
	$attributes       = ( ( $autoplay == 'true' ) ? 'autoplay ' : '' ) . ( ( $loop == 'true' ) ? 'loop ' : '' ) . ( isset ( $preload ) ? 'preload="' . $preload . '" ' : '' ) . ( ( $poster == 'true' ) ? 'poster' : '' );
	$modifier_classes = ( isset ( $fixed_controls ) ? 'fixed-controls ' : '' ) . ( $coloring != 'default' ? $coloring : '' );

	// Shortcode output
	$return = '<div style="' . $size . $splash_style . ' background-size: contain;" class="' . $class . $modifier_classes . '" ' . $data_key . $data_logo . $data_analytics . $data_ratio . '>';
		$return .= '<video ' . $attributes . '>';
			$webm      != '' ? $return .= '<source type="video/webm" src="' . $webm . '"/>' : '';
			$mp4       != '' ? $return .= '<source type="video/mp4" src="' . $mp4 . '"/>' : '';
			$ogg       != '' ? $return .= '<source type="video/ogg" src="' . $ogg . '"/>' : '';
			$subtitles != '' ? $return .= '<track  type="text/vtt" src="' . $subtitles . '"/>' : '';
		$return .= '</video>';
	$return .= '</div>';

	// Extra options
	$return .= '<script>';
		$width == 0 && $height == 0 ? $return .= 'flowplayer.conf.adaptiveRatio = true;' : '';
	$return .= '</script>';

	
		// Check if a video has been added before output
		if ( $webm || $mp4 || $ogg ) {
			return $return;
		}
		} else {

		//[flowplayer splash="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.jpg" webm="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.webm" mp4="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.mp4" ogg="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.ogv" width="1920" height="1080" skin="functional" autoplay="true" loop="true" fixed="true" subtitles="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.vtt"]

				// get values from shortcode
				$old_shortcode_atts = shortcode_atts(
					array(
						'mp4'       => '',
						'webm'      => '',
						'ogg'       => '',
						'skin'      => 'minimalist',
						'splash'    => '',
						'autoplay'  => 'false',
						'loop'      => 'false',
						'subtitles' => '',
						'width'     => '',
						'height'    => '',
						'fixed'     => 'false'
					),
					$atts
				);

				// get the id of the current post
				$the_post_id =  get_the_id();

				// create custom video post, return id
				$video_post_id = fp5_create_video_post( $old_shortcode_atts );

				// add meta to the new video post
				$addpostmeta = add_meta_to_fp5_video( $video_post_id, $old_shortcode_atts );

				generate_new_shortcode( $video_post_id );

	}

}

// Register shortcode
add_shortcode( 'flowplayer', 'add_fp5_shortcode' );

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
				'post_type'   => 'flowplayer5',
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