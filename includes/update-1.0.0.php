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
//delete_option( 'plugin_options' );



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

