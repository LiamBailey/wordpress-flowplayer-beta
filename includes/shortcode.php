<?php

// changes made by Big Cloud Media ... still in progress.
// [] shortcode update to accomodate meta data
// example shortcode [flowplayer id='39']

if (!defined('ABSPATH'))
    exit;

// Add Shortcode
function add_fp5_shortcode($atts) {

//post_id
	$id = $atts['id'];

	// get the meta from the post type
	$autoplay = get_post_meta($id, 'fp5[autoplay]', true);
	$loop = get_post_meta($id, 'fp5[loop]', true);
	$autoplay = get_post_meta($id, 'fp5[autoplay]', true);
	$width = get_post_meta($id, 'fp5[width]', true);
	$height = get_post_meta($id, 'fp5[height]', true);
	$fixed = get_post_meta($id, 'fp5[fixed]', true);
	$subtitles = get_post_meta($id, 'fp5[subtitles]', true);

	// set the options for the shortcode - pulled from the display-settings.php
	$options = get_option('fp5_options');
	$key = $options['key'];
	$logo = $options['logo'];
	$analytics = $options['ga_accountId'];
	$logoInOrigin = $options['logoInOrigin'];
	$cdn = $options['cdn'];

	// Register ahortcode stylesheets and JavaScript
	if ($cdn == 'true') {
		wp_enqueue_style( $plugin_slug .'-skins' , 'http://releases.flowplayer.org/' . $player_version . '/skin/' . $skin . '.css' );
		wp_enqueue_script( $plugin_slug . '-script', 'http://releases.flowplayer.org/' . $player_version . '/'.($key != '' ? 'commercial/' : '') . 'flowplayer.min.js', array( 'jquery' ), $player_version, false );
	} else {
		wp_enqueue_style( $plugin_slug .'-skins', plugins_url( '/assets/skin/' . $skin . '.css', __FILE__ ), $player_version );
		wp_enqueue_script( $plugin_slug . '-script', plugins_url( '/assets/flowplayer/'.($key != '' ? "commercial/" : "").'flowplayer.min.js', __FILE__ ), array( 'jquery' ), $version, false );
	}

    if(isset($atts['id'])){

    //video_custompost_id
    $id = $atts['id'];
    
    //get the splash image or featured image
    $splash = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full');

    // find and assign the video attachments
    $args = array(
        'post_type' => 'attachment',
        'post_parent' => $id
    );
    $attachments = new WP_Query($args);
    if ($attachments) {
        foreach ($attachments as $attachment) {

            $filelink = the_attachment_link($attachment->ID, false);
            $filetype = wp_check_filetype($filelink);
            switch ($filetype) {
                case 'mp4':
                    $mp4 = $filelink;
                    break;
                case 'ogg':
                    $ogg = $filelink;
                    break;
                case 'webm':
                    $webm = $filelink;
                    break;
            }
        }
    }

    // Code
    if (isset($id)) {
        '<script>';
        if ($key != '' && $logoInOrigin) {
            $out .= 'jQuery("head").append(jQuery(\'<style>.flowplayer .fp-logo { display: block; opacity: 1; }</style>\'));';
        }
        '</script>';
        $ratio = ($width != '' && $height != '' ? intval($height) / intval($width) : '');
        $fixed_style = ( $fixed == 'true' && $width != '' && $height != '' ? '"width:' . $width . 'px;height:' . $height . 'px;" ' : '"max-width:' . $width . 'px"');
        $splash_style = 'background:#777 url(' . $splash . ') no-repeat;';
        $class = '"flowplayer ' . $skin . ( $splash != "" ? " is-splash" : "" ) . '"';
        $data_key = ( $key != '' ? ' "' . $key . '"' : '');
        $data_logo = ( $key != '' && $logo != '' ? ' "' . $logo . '"' : '' );
        $data_analytics = ( $analytics != '' ? ' "' . $analytics . '"' : '' );
        $data_ratio = ( $ratio != 0 ? '"' . $ratio . '"' : '' );
        $attributes = ( ( $autoplay == 'true' ) ? $autoplay : '' );
        ( ( $loop == 'true' ) ? $loop : '' );
        ( ( $preload == 'true' ) ? $preload : '' );
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

    } else{   /*  run the conversion script on the post on the fly.  
    
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
}

// register shortcode
add_shortcode('flowplayer', 'add_fp5_shortcode');


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
    $fp5_ratio = get_post_meta($vid_post_id, 'fp5[ratio]', true
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


