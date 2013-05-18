<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   PluginName
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Calls the class on the post edit screen
 *
 * FlowPlayer5Video post type
 */

if ( is_admin() ):
	add_action( 'load-post.php', 'call_fp5_meta_box' );
endif;

/*  add meta box actions to setup meta box */
add_action('add_meta_boxes','fp5_add_video_details');
add_action('save_post','fp5_save_video_details');

/* cta box references */
function fp5_add_video_details() {
    add_meta_box(
        'FP5_Video_Details',
        __('Video Details','flowplayer5'),
        'fp5_Video_details',
        'FlowPlayer5Video'
    );
}


/* Prints the cta box content */
function fp5_Video_details($post) {

    $fp5_selectSkin = get_post_meta($post->ID,'fp5_selectSkin',true);
    $fp5_autoplay = get_post_meta($post->ID,'fp5_autoplay',true);
    $fp5_loop = get_post_meta($post->ID,'fp5_loop',true);
    $fp5_splash = get_post_meta($post->ID,'fp5_splash',true);
    $fp5_mp4 = get_post_meta($post->ID,'fp5_mp4',true);
    $fp5_webm = get_post_meta($post->ID,'fp5_webm',true);
    $fp5_ogg = get_post_meta($post->ID,'fp5_ogg',true);
    $fp5_width = get_post_meta($post->ID,'fp5_width',true);
    $fp5_height = get_post_meta($post->ID,'fp5_height',true);
    $fp5_ratio = get_post_meta($post->ID,'fp5_ratio',true);
    $fp5_fixed = get_post_meta($post->ID,'fp5_fixed',true);




    wp_nonce_field('HansenWP_jobs','HansenWP_jobs_nonce'); ?>

    <div class="options" xmlns="http://www.w3.org/1999/html">
			<div class="optgroup">
				<label for="fp5_selectSkin">
					<?php _e('Select skin')?>
				</label>
				<select id="fp5_selectSkin" name ='fp5_selectSkin' class="option">
                    <option class="fp5[skin]" id="fp5_minimalistSel" value="minimalist" selected="selected"><?php _e('Minimalist' ) ?></option>
                    <option class="fp5[skin]" id="fp5_functionalSel" value="functional"><?php _e('Functional' ) ?></option>
                    <option class="fp5[skin]" id="fp5_playfulSel" value="playful"><?php _e('Playful' ) ?></option>
                </select>
				<div class="option">
                    <img id="fp5_minimalist" src="<?php print(FP5_PLUGIN_URL.'assets/img/minimalist.png')  ?>" />
                    <img id="fp5_functional" src="<?php print(FP5_PLUGIN_URL.'assets/img/functional.png')  ?>" />
                    <img id="fp5_playful" src="<?php print(FP5_PLUGIN_URL.'assets/img/playful.png')  ?>" />
                </div>
			</div>
			<div class="optgroup separated">
                <label for="fp5_videoAttributes">
                    <?php _e('Video attributes')?> <a href="http://flowplayer.org/docs/index.html#video-attributes" target="_blank"><?php _e('(Info)')?></a>
                </label>
                <div class="wide"></div>
                <div id="fp5_videoAttributes" class="option">
                    <label for="fp5_autoplay"><?php _e('Autoplay?')?></label>
                    <input type="checkbox" name="fp5_autoplay" id="fp5_autoplay" value="true" />
                </div>
                <div class="option">
                    <label for="fp5_loop"><?php _e('Loop?')?></label>
                    <input type="checkbox" name="fp5_loop" id="fp5_loop" value="true" />
                </div>
            </div>

			<div class="optgroup">
                <div class="option wide">
                    <label for="fp5_splash">
                        <a href="http://flowplayer.org/docs/index.html#splash" target="_blank"><?php _e('Splash image')?></a><br/><?php _e('(optional)')?>
                    </label>
                    <input class="mediaUrl" type="text" name="fp5_splash" id="fp5_splash" />
                    <input id="fp5_chooseSplash" type="button" value="<?php _e('Media Library'); ?>" />
                </div>
            </div>

			<div class="optgroup separated">
                <div class="head" for="fp5_videos">
                    <?php _e('URLs for videos, at least one is needed. You need a video format supported by your web browser, otherwise the preview below does not work.')?>
                    <a href="http://flowplayer.org/docs/#video-formats" target="_blank"><?php _e('About video formats')?></a>.
                </div>
                <div class="option wide">
                    <label for="fp5_mp4"><?php _e('mp4')?></label>
                    <input class="mediaUrl" type="text" name="fp5_mp4" id="fp5_mp4" />
                </div>
                <div id="fp5_videos" class="option wide">
                    <label for="fp5_webm"><?php _e('webm')?></label>
                    <input class="mediaUrl" type="text" name="fp5_webm" id="fp5_webm" />
                </div>
                <div class="option wide">
                    <label for="fp5_ogg"><?php _e('ogg')?></label>
                    <input class="mediaUrl" type="text" name="fp5_ogg" id="fp5_ogg" />
                </div>
                <input id="fp5_chooseMedia" type="button" value="<?php _e('Media Library'); ?>" />
            </div>

			<div class="optgroup">
                <div class="option">
                    <div id="preview" class="preview"><?php _e( 'Preview' ) ?>
                        <div class="flowplayer">
                            <video id="fp5_videoPreview" width="320" height="240" controls="controls">
                            </video>
                        </div>
                    </div>
                </div>
                <div class="details separated">
                    <label for="fp5_width"><?php _e('Maximum dimensions for the player are determined from the provided video files. You can change this size below. Fixing the player size disables scaling for different screen sizes.')?></label>
                    <div class="wide"></div>
                    <div class="option">
                        <label for="fp5_width"><?php _e('Max width')?></label>
                        <input class="small" type="text" id="fp5_width" name="fp5_width" />
                    </div>
                    <div class="option">
                        <label class="checkbox" for="fp5_ratio"><?php _e('Use video\'s aspect ratio')?></label>
                        <input class="checkbox" type="checkbox" id="fp5_ratio" name="fp5_ratio" value="true" checked="checked"/>
                    </div>
                    <div class="option">
                        <label for="fp5_height"><?php _e('Max height')?></label>
                        <input class="small" type="text" id="fp5_height" name="fp5_height" readonly="true"/>
                    </div>
                    <div class="option">
                        <label class="checkbox" for="fp5_fixed"><?php _e('Use fixed player size') ?></label>
                        <input class="checkbox" type="checkbox" id="fp5_fixed" name="fp5_fixed" value="true" />
                    </div>
                </div>
            </div>

			<div class="optgroup separated">
                <label class="head" for="fp5_subtitles">
                    <?php _e('You can include subtitles by supplying an URL to a WEBVTT file')?>
                    <a href="http://flowplayer.org/docs/subtitles.html" target="_blank"> <?php _e( 'Visit the subtitles documentation' ) ?></a>.
                </label>
                <div class="option wide">
                    <label class="head" for="fp5_subtitles">
                        <?php _e('WEBVTT URL')?>
                    </label>
                    <input class="mediaUrl" type="text" name="fp5[subtitles]" id="fp5_subtitles" />
                </div>
            </div>

			<div class="option wide">
                <input class="button-primary" id="fp5_sendToEditor" type="button" value="<?php _e('Send to Editor &raquo;'); ?>" />
            </div>
		</div> <?php

}

/* saves the cta custom meta content */
function fp5_save_video_details($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['HansenWP_jobs_nonce'])) return;
    if (!wp_verify_nonce($_POST['HansenWP_jobs_nonce'],'HansenWP_jobs')) return;

    if (!current_user_can('edit_post',$post_id)) return;

    // OK, we're authenticated: we need to find and save the data

    $fp5_selectSkin = (!empty($_POST['fp5_selectSkin'])) ? $_POST['fp5_selectSkin'] : '';
    update_post_meta($post_id,'fp5_selectSkin',$fp5_selectSkin);

    $fp5_autoplay = (!empty($_POST['fp5_autoplay'])) ? $_POST['fp5_autoplay'] : '';
    update_post_meta($post_id,'fp5_autoplay',$fp5_autoplay);

    $fp5_loop = (!empty($_POST['fp5_loop'])) ? $_POST['fp5_loop'] : '';
    update_post_meta($post_id,'fp5_loop',$fp5_loop);

    $fp5_splash = (!empty($_POST['fp5_splash'])) ? $_POST['fp5_splash'] : '';
    update_post_meta($post_id,'fp5_splash',$fp5_splash);

    $fp5_mp4 = (!empty($_POST['fp5_mp4'])) ? $_POST['fp5_mp4'] : '';
    update_post_meta($post_id,'fp5_mp4',$fp5_mp4);

    $fp5_webm = (!empty($_POST['fp5_webm'])) ? $_POST['fp5_webm'] : '';
    update_post_meta($post_id,'fp5_webm',$fp5_webm);

    $fp5_ogg = (!empty($_POST['fp5_ogg'])) ? $_POST['fp5_ogg'] : '';
    update_post_meta($post_id,'fp5_ogg',$fp5_ogg);

    $fp5_ogg = (!empty($_POST['fp5_ogg'])) ? $_POST['fp5_ogg'] : '';
    update_post_meta($post_id,'fp5_ogg',$fp5_ogg);

    $fp5_width = (!empty($_POST['fp5_width'])) ? $_POST['fp5_width'] : '';
    update_post_meta($post_id,'fp5_width',$fp5_width);

    $fp5_height = (!empty($_POST['fp5_height'])) ? $_POST['fp5_height'] : '';
    update_post_meta($post_id,'fp5_height',$fp5_height);

    $fp5_ratio = (!empty($_POST['fp5_ratio'])) ? $_POST['fp5_ratio'] : '';
    update_post_meta($post_id,'fp5_ratio',$fp5_ratio);

    $fp5_fixed = (!empty($_POST['fp5_fixed'])) ? $_POST['fp5_fixed'] : '';
    update_post_meta($post_id,'fp5_fixed',$fp5_fixed);


/*  end CTA options custom fields */

}