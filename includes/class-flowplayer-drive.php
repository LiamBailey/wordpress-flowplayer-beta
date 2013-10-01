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

class Flowplayer_Drive {

	/**
	 * Instance of this class.
	 *
	 * @var      Flowplayer_Drive
	 */
	private static $instance;

	/**
	 * Initializes the plugin so that the Twitter information is appended to the end of a single post.
	 * Note that this constructor relies on the Singleton Pattern
	 *
	 * @since      1.3.0
	 */
	private function __construct() {
		add_action( 'admin_footer', array( $this, 'fp5_drive_content' ) );
	} // end constructor

	/**
	 * Creates an instance of this class
	 *
	 * @since      1.3.0
	 */
	public function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	} // end get_instance

	/**
	 * Attempts to request the specified user's JSON feed from Twitter
	 *
	 * @since      1.3.0
	 */
	private function make_auth_request() {

		// get the login info
		$options   = get_option('fp5_settings_general');
		$user_name = ( isset( $options['user_name'] ) ) ? $options['user_name'] : '';
		$password  = ( isset( $options['password'] ) ) ? $options['password'] : '';

		$auth_api_url = esc_url_raw( 'http://account.api.dev.flowplayer.org/auth' );

		$response_auth = wp_remote_get( $auth_api_url );

		$body_auth = wp_remote_retrieve_body( $response_auth );

		$json = json_decode( $body_auth );
		return $json;
 
		/*$seed = $json->result;

		$parameters = '?callback=?&username=' . $user_name . '&hash=' . sha1( $user_name . $seed . sha1( $password ) ) . '&seed=' . $seed;

		$url = esc_url_raw( 'http://account.api.dev.flowplayer.org/auth' . $parameters );

		$response = wp_remote_get( $auth_api_url );

		$body = wp_remote_retrieve_body( $response );

		$auth = json_decode( $body );

		return $auth->authcode;*/

	} // end make_twitter_request

	/**
	 * Attempts to request the specified user's JSON feed from Twitter
	 *
	 * @since      1.3.0
	 */
	private function make_video_request() {

		$authcode = $this->make_auth_request;
		$video_api_url = esc_url_raw( 'http://videos.api.dev.flowplayer.org/account?videos=true&authcode=' . $authcode );

		$response = wp_remote_get( $video_api_url );

		$body = wp_remote_retrieve_body( $response );

		$json = json_decode( $body );
 
		return $json;

	} // end make_twitter_request

	/**
	 * Fetches all videos from Flowplayer Drive
	 *
	 * @since      1.3.0
	 */
	public function listVideos(){
		$res = $this->make_video_request();
		$ret = array();
		foreach($res->body as $row) {
			$ret[] = new Video($row);
		}
		return $ret;
	}


	/**
	 * Fetches all videos from Flowplayer Drive
	 *
	 * @since      1.3.0
	 */
	public function get_videos() {

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
	public function fp5_drive_content() {

		$screen = get_current_screen();

		// Only run in post/page creation and edit screens
		if ( $screen->base == 'post' && $screen->post_type == 'flowplayer5' ) {
			?>
			<div style="display: block;">
				<div class="media-frame-router">
					<div class="media-router"><a href="#" class="media-menu-item">Upload Videos</a><a href="#" class="media-menu-item active">Flowplayer Drive</a></div>
				</div>
				<div id="flowplayer-drive">
					<?php //$this->make_auth_request(); ?>
					<?php $this->make_auth_request(); ?>
				</div>
			</div>
			<?php
		}
	}

} // end class

// Trigger the plugin
Flowplayer_Drive::get_instance();