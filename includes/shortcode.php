<?php

// changes made by Big Cloud Media ... still in progress.
// [] shortcode update to accomodate meta data
// example shortcode [flowplayer id='39']

if (!defined('ABSPATH'))
    exit;

// Add Shortcode
function add_fp5_shortcode($atts) {

$version = '1.0.0-beta';
$plugin_slug = 'flowplayer5';
$player_version = '5.4.1';
global $post;

	//post_id
	$id = $atts['id'];

	// get the meta from the post type
	$loop      = get_post_meta( $id, 'loop', true );
	$autoplay  = get_post_meta( $id, 'autoplay', true );
	$subtitles = get_post_meta( $id, 'webvtt', true );
	$skin      = get_post_meta( $id, 'fp5-select-skin', true );
	$splash    = get_post_meta( $id, 'splash-image', true );
	$mp4       = get_post_meta( $id, 'mp4-video', true );
	$webm      = get_post_meta( $id, 'webm-video', true );
	$ogg       = get_post_meta( $id, 'ogg-video', true) ;
	$width     = get_post_meta( $id, 'max-width', true );
	$height    = get_post_meta( $id, 'max-height', true );
	$ratio     = get_post_meta( $id, 'aspect-ratio', true );
	$fixed     = get_post_meta( $id, 'fixed-width', true );

	// set the options for the shortcode - pulled from the display-settings.php
	$options       = get_option('fp5_settings_general');
	$key           = $options['key'];
	$logo          = $options['logo'];
	$ga_account_id = $options['ga_account_id'];
	$logo_origin   = $options['logo_origin'];
	$cdn           = $options['cdn_option'];

	// Checks and displays the retrieved value
	if( isset( $id ) ) {
		echo $id;
	}
	if( isset( $loop ) ) {
		echo $loop;
	}
	if( isset( $autoplay ) ) {
		echo $autoplay;
	}
	if( isset( $subtitles ) ) {
		echo $subtitles;
	}
	if( isset( $mp4 ) ) {
		echo $mp4;
	}
	if( isset( $webm ) ) {
		echo $webm;
	}
	if( isset( $ogg ) ) {
		echo $ogg;
	}
	if( isset( $width ) ) {
		echo $width;
	}
	if( isset( $height ) ) {
		echo $height;
	}
	if( isset( $ratio ) ) {
		echo $ratio;
	}
	if( isset( $fixed ) ) {
		echo $fixed;
	}
	if( isset( $key ) ) {
		echo $key;
	}
	if( isset( $logo ) ) {
		echo $logo;
	}
	if( isset( $ga_account_id ) ) {
		echo $ga_account_id;
	}
	if( isset( $logo_origin ) ) {
		echo 'logo_origin';
	}
	if( isset( $cdn ) ) {
		echo 'cdn';
	}

	// Register ahortcode stylesheets and JavaScript
	if ($cdn == 'true') {
		wp_enqueue_style( $plugin_slug .'-skins' , 'http://releases.flowplayer.org/' . $player_version . '/skin/' . $skin . '.css' );
		wp_enqueue_script( $plugin_slug . '-script', 'http://releases.flowplayer.org/' . $player_version . '/'.($key != '' ? 'commercial/' : '') . 'flowplayer.min.js', array( 'jquery' ), $player_version, false );
	} else {
		wp_enqueue_style( $plugin_slug .'-skins', plugins_url( '/assets/flowplayer/skin/' . $skin . '.css', dirname(__FILE__) ), $player_version );
		wp_enqueue_script( $plugin_slug . '-script', plugins_url( '/assets/flowplayer/'.($key != '' ? "commercial/" : "").'flowplayer.min.js', dirname(__FILE__) ), array( 'jquery' ), $version, false );
	}

	if ($logo_origin == 'true') {
		wp_enqueue_style( $plugin_slug .'-logo-origin', plugins_url( '/assets/css/public.css', dirname(__FILE__) ), $player_version );
	}

	//shortcode processing
	$ratio          = ( $width != '' && $height != '' ? intval($height) / intval($width) : '' );
	$fixed_style    = ( $fixed == 'true' && $width != '' && $height != '' ? 'width:' . $width . 'px; height:' . $height . 'px; ' : 'max-width:' . $width . 'px; ' );
	$splash_style   = 'background:#777 url(' . $splash . ') no-repeat;';
	$class          = 'flowplayer ' . $skin . ( $splash != "" ? " is-splash" : "" );
	$data_key       = ( $key != '' ? $key : '');
	$data_logo      = ( $key != '' && $logo != '' ?  $logo : '' );
	$data_analytics = ( $ga_account_id != '' ?  $ga_account_id  : '' );
	$data_ratio     = ( $ratio != 0 ? $ratio : '' );
	$attributes     = ( $autoplay == 'true' ) ? 'autoplay' : '' . ( $loop == 'true' ) ? 'loop' : '';
	//( ( $preload == 'true' ) ? 'preload' : '' );


	// shortCode output
	$return = '';
	$return.= '<div style="' . $fixed_style . $splash_style . '" class="' . $class . '" data-key="' . $data_key . '" data-logo="' . $data_logo . '" data-analytics="' . $data_analytics . '" data-ratio="' . $data_ratio . '">';
	$return.= '<video' . $attributes . '>';
		$mp4       != '' ? $return.='<source type="video/mp4" src="' . $mp4 . '"/>' : '';
		$webm      != '' ? $return.='<source type="video/webm" src="' . $webm . '"/>' : '';
		$ogg       != '' ? $return.='<source type="video/ogg" src="' . $ogg . '"/>' : '';
		$subtitles != '' ? $return.='<track  type="text/vtt" src="' . $subtitles . '"/>' : '';
	$return.= '</video>';
	$return.= '</div>';

	$return.= '<script> </script>';

	return $return;
	}

// register shortcode
add_shortcode('flowplayer', 'add_fp5_shortcode');


// this needs to be completed... now standalone used to be included in the register shortcode script
function convert_video_shortcode($shortcode_array){/*  run the conversion script on the post on the fly.

    [flowplayer splash="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.jpg"
    webm="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.webm"
   mp4="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.mp4"
ogg="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.ogv"
width="1920" height="1080" skin="minimalist"]
    
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
            ), $atts);
            
        
       

        // get the id of the current post
        $the_post_id =  get_the_id();
        

        // create custom video post, return id
        $vid_post_id = create_fp5video_post($old_shortcode_atts);
        
        // add meta to the new video post
        $addpostmeta = add_meta_to_fp5video($vid_post_id, $old_shortcode_atts);
        
        

    }



/***
 * 
 * Shortcode processing helper functions
 * 
 */



// create the new "video" post
function create_fp5video_post($array){

    // create video name and slug
    $namearray = create_slug_and_title($array);
        
    $title = $namearray['title'];
    $slug = $namearray['slug'];

    // create new video post
    $product = array(
        'post_name' => $slug,
        'post_status' => 'publish',
        'post_title' => $title,
        'post_type' => 'video'
    );
    
    $video_post_id = wp_insert_post($product);

    return $video_post_id;
}

// create the slug and title for the new video post
function create_slug_and_title($stuff){
    extract($stuff);
    
    if($mp4):
        $title = basename($mp4, ".mp4");
        $slug = sanitize_title_with_dashes( $title, $unused='', $context = 'display' );
    elseif($webm):
        $title = basename($webm, ".webm");
        $slug = sanitize_title_with_dashes( $title, $unused='', $context = 'display' );
    elseif($ogg):
        $title = basename($ogg, ".ogg");
        $slug = sanitize_title_with_dashes( $title, $unused='', $context = 'display' );
    endif;

    $namearray=array('title'=>$title,'slug'=>$slug);

return $namearray;
}

// add meta to the new video post
function add_meta_to_fp5_video($vid_post_id, $old_shortcode_atts){

    // @todo account for video not being hosted on the site.
    // @todo account for the featured image not being hosting on site.

    $unique = true;

    // run through shortcode and add meta
    foreach($old_shortcode_atts as $key=>$value){
        
        if($key!='mp4' && $key!='ogg' && $key!='webm' && $key!='splash'){
            add_post_meta($vid_post_id, $key, $value, $unique);
        }
    
    // add the splash image as the featured image for post
        if($key = 'splash'){


            // get file data
            $wp_filetype = wp_check_filetype(basename($value), null );
        
            $wp_upload_dir = wp_upload_dir();

            $attachment = array(
                'guid' => $wp_upload_dir['url'] . '/' . basename( $value ), 
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($value)),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $value, 37 );
            // you must first include the image.php file
            // for the function wp_generate_attachment_metadata() to work
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $value );
            wp_update_attachment_metadata( $attach_id, $attach_data );
        
        }
    
    }  // end foreach $old_shortcode
    
    //check for missing post meta
    $fp5_ratio = get_post_meta($vid_post_id, 'fp5[ratio]', true);
    if(!isset($fp5_ratio)){
        add_post_meta($vid_post_id, 'fp5[ratio]', '', $unique);
    }
    
   // $ratio = ($width != '' && $height != '' ? intval($height) / intval($width) : '');
    
    
}

// generate the shortcode to replace old shortcode
function generate_new_shortcode($vid_post_id){
    $shortcode = '[flowplayer id="'.$vid_post_id.'"]';
    return $shortcode;
}

