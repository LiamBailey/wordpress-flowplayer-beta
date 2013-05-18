<?php
/**
 * Flowplayer 5 for Wordpress
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Flowplayer Ltd
 */

// changes made by Big Cloud Media ... still in progress.
// [] shortcode update to accomodate meta data
// example shortcode [flowplayer id='39']

if (!defined('ABSPATH'))
	exit;

class fp5_shortcode {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0-beta';

	// Add Shortcode
	public function add_fp5_shortcode($atts) {

		//post_id
		$id = $atts['id'];

		// get the meta from the post type
		$autoplay = get_post_meta($id, 'fp5[autoplay]', true);
		$loop = get_post_meta($id, 'fp5[loop]', true);
		$autoplay = get_post_meta($id, 'fp5[autoplay]', true);
		$width = get_post_meta($id, 'fp5[width]', true);
		$height = get_post_meta($id, 'fp5[height]', true);
		$fp5_ratio = get_post_meta($id, 'fp5[ratio]', true);
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
			wp_enqueue_style( $this->plugin_slug .'-skins' , 'http://releases.flowplayer.org/' . $this->player_version . '/skin/' . $skin . '.css' );
			wp_enqueue_script( $this->plugin_slug . '-script', 'http://releases.flowplayer.org/' . $this->player_version . '/'.($key != '' ? 'commercial/' : '') . 'flowplayer.min.js', array( 'jquery' ), $this->player_version, false );
		} else {
			wp_enqueue_style( $this->plugin_slug .'-skins', plugins_url( '/assets/skin/' . $skin . '.css', __FILE__ ), $this->player_version );
			wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( '/assets/flowplayer/'.($key != '' ? "commercial/" : "").'flowplayer.min.js', __FILE__ ), array( 'jquery' ), $this->version, false );
		}

		//get the splash image or featured image
		$splash = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full');

		// get the video attachments
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
	}
}

add_shortcode( 'flowplayer', array( 'fp5_shortcode', 'add_fp5_shortcode' ) );
