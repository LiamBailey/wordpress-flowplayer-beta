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


/*add meta box actions to setup meta box */
add_action('add_meta_boxes','fp5_add_video_details');
add_action('save_post','fp5_save_video_details');

/* cta box references */
function fp5_add_video_details() {
	add_meta_box(
		 'FP5_Video_Details',
		__( 'Video Details', 'flowplayer5' ),
		'fp5_Video_details',
		'FlowPlayer5Video',
		'normal',
		'default'
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



	wp_nonce_field('fp5_Video_N','fp5_Video_nonce');

	function fp5_meta_box_options() {
		global $fp5_options;

		ob_start();
		?>
		<div class="wrap">
			<div id="tab_container">
				<form method="post" action="options.php">
					<?php
						do_settings_sections( 'fp5_settings_meta_box' );
					submit_button();
					?>
				</form>
			</div><!-- #tab_container-->
		</div><!-- .wrap -->
		<?php
		echo ob_get_clean();
	}

}

/* saves the cta custom meta content */
function fp5_save_video_details($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!isset($_POST['fp5_Video_nonce'])) return;
	if (!wp_verify_nonce($_POST['fp5_Video_nonce'],'fp5_Video_N')) return;

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
