<?php
/**
 * Flowplayer Drive
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fetches all videos from Flowplayer Drive
 *
 * @since      1.3.0
 */
function get_videos() {

	// get the login info
	$options   = get_option('fp5_settings_general');
	$user_name = ( isset( $options['user_name'] ) ) ? $options['user_name'] : '';
	$password  = ( isset( $options['password'] ) ) ? $options['password'] : '';

	$authCode  = Flowplayer\Auth::authenticate( $user_name, $password );

	$client = new Flowplayer\Library\Client( $authCode );

	foreach ( $client->listVideos() as $video ) {

		foreach ( $video->get( 'encodings' ) as $encoding ) {
			if ( $encoding[ 'status' ] === 'done' & $encoding[ 'format' ] === 'webm' ) {
				$webm = $encoding['url'];
			}
			if ( $encoding[ 'status' ] === 'done' & $encoding[ 'format' ] === 'mp4' ) {
				$mp4 = $encoding['url'];
			}
			if ( $encoding[ 'status' ] === 'done' & $encoding[ 'format' ] === 'mp4' ) {
				$duration = gmdate( "H:i:s", $encoding['duration'] );
			} elseif ( $encoding[ 'status' ] === 'done' & $encoding[ 'format' ] === 'webm' ) {
				$duration = gmdate( "H:i:s", $encoding['duration'] );
			}
		}

		$return = '<div class="video">';
			$return .= '<a href="#" class="choose-video" data-webm="' . $webm .'" data-mp4="' . $mp4 .'" data-img="' . $video->get( 'snapshotUrl' ) . '">';
				$return .= '<h2 class="video-title">' . $video->get( 'title' ) . '</h2>';
				$return .= '<div class="thumb" style="background-image: url(' . $video->get( 'thumbnailUrl' ) . ');">';
					$return .= '<em class="duration">' . $duration . '</em>';
				$return .= '</div>';
			$return .= '</a>';
		$return .= '</div>';

		echo $return;
	}

}

/**
 * Content for flowplayer drive colorbox modal
 *
 * @since      1.3.0
 */
function fp5_drive_content() {

	$screen = get_current_screen();

	// Only run in post/page creation and edit screens
	if ( $screen->base == 'post' && $screen->post_type == 'flowplayer5' ) {
		?>
		<div style="display: none;">
			<div class="media-frame-router">
				<div class="media-router"><a href="#" class="media-menu-item">Upload Videos</a><a href="#" class="media-menu-item active">Flowplayer Drive</a></div>
			</div>
			<div id="flowplayer-drive">
				<?php get_videos(); ?>
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_footer', 'fp5_drive_content' );