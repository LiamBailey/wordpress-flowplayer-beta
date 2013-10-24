<?php
/**
 * Flowplayer
 *
 * @package   Flowplayer_Drive
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

/**
 * Flowplayer Drive Class
 *
 * @package Flowplayer_Drive
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
class Flowplayer_Drive {

	/**
	 * Flowplayer Account API URL
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	private $account_api_url = 'http://account.api.flowplayer.org/auth?_format=json';

	/**
	 * Flowplayer Video API URL
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	private $video_api_url = 'http://videos.api.flowplayer.org/account';

	/**
	 * Instance of this class.
	 *
	 * @since    1.2.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since    1.2.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = Flowplayer5::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add content to footer bottom
		add_action( 'admin_footer', array( $this, 'fp5_drive_content' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since    1.2.0
	 *
	 * @return   object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Flowplayer Drive API authentication
	 *
	 * @since    1.2.0
	 */
	private function make_auth_request() {

		// get the login info
		$options   = get_option('fp5_settings_general');
		$user_name = ( isset( $options['user_name'] ) ) ? $options['user_name'] : '';
		$password  = ( isset( $options['password'] ) ) ? $options['password'] : '';

		$response_account = wp_remote_get( $this->account_api_url );

		if( is_wp_error( $response_account ) )
			return;

			$body_account = wp_remote_retrieve_body( $response_account );

		if( is_wp_error( $body_account ) )
			return;

			if ( $response_account['response']['code'] == 200 ) {

				$json = json_decode( $body_account );

				$seed = $json->result;

				$auth_api_url = esc_url_raw( add_query_arg(
					array(
						'callback' => '?',
						'username' => $user_name,
						'hash'     => sha1( $user_name . $seed . sha1( $password ) ),
						'seed'     => $seed
					),
					$this->account_api_url
				) );

				$response = wp_remote_get( $auth_api_url );

				$body = wp_remote_retrieve_body( $response );

				$auth = json_decode( $body );

				return $auth->result->authcode;

			} else {

				echo __( 'Unable to contact API service.', 'flowplayer5' );

			}

	}

	/**
	 * Attempts to request videos
	 *
	 * @since    1.2.0
	 */
	private function make_video_request() {

		$authcode = $this->make_auth_request();

		$videos_api_url = esc_url_raw( add_query_arg(
			array(
				'videos' => 'true',
				'authcode' => $authcode
			),
			$this->video_api_url
		) );

		$response = wp_remote_get( $videos_api_url );

		$body = wp_remote_retrieve_body( $response );

		$json = json_decode( $body );
 
		return $json->videos;

	}

	/**
	 * Fetches all videos from Flowplayer Drive
	 *
	 * @since    1.2.0
	 */
	public function get_videos() {

		if( is_wp_error( $this->account_api_url ) || is_wp_error( $this->video_api_url ) ) {
			return;
		} else {
			foreach ( $this->make_video_request() as $video ) {

				foreach ( $video->encodings as $encoding ) {
					if ( $encoding->status === 'done' & $encoding->format === 'webm' ) {
						$webm = $encoding->url;
					}
					if ( $encoding->status === 'done' & $encoding->format === 'mp4' ) {
						$mp4 = $encoding->url;
					}
					if ( $encoding->status === 'done' & $encoding->format=== 'mp4' ) {
						$duration = gmdate( "H:i:s", $encoding->duration );
					} elseif ( $encoding->status === 'done' & $encoding->format === 'webm' ) {
						$duration = gmdate( "H:i:s", $encoding->duration );
					}
				}

				$return = '<div class="video">';
					$return .= '<a href="#" class="choose-video" data-webm="' . $webm .'" data-mp4="' . $mp4 .'" data-img="' . $video->snapshotUrl . '">';
						$return .= '<h2 class="video-title">' . $video->title . '</h2>';
						$return .= '<div class="thumb" style="background-image: url(' . $video->thumbnailUrl . ');">';
							$return .= '<em class="duration">' . $duration . '</em>';
						$return .= '</div>';
					$return .= '</a>';
				$return .= '</div>';

				echo $return;
			}
		}

	}

	/**
	 * Content for flowplayer drive colorbox modal
	 *
	 * @since    1.2.0
	 */
	public function fp5_drive_content() {

		$screen = get_current_screen();

		// Only run in post/page creation and edit screens
		if ( $screen->base == 'post' && $screen->post_type == 'flowplayer5' ) {
			?>
			<div style="display: none;">
				<div class="media-frame-router">
					<div class="media-router"><a href="#" class="media-menu-item"><?php __( 'Upload Videos' ) ?></a><a href="#" class="media-menu-item active"><?php __( 'Flowplayer Drive' ) ?></a></div>
				</div>
				<div id="flowplayer-drive">
					<?php $this->get_videos(); ?>
				</div>
			</div>
			<?php
		}
	}

}