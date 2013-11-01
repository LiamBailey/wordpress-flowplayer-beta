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
	private function get_auth_seed() {

		$response_account = wp_remote_get( esc_url_raw( $this->account_api_url ) );

		if ( wp_remote_retrieve_response_code( $response_account ) == 200 ) {

			$body_account = wp_remote_retrieve_body( $response_account );

			$json = json_decode( $body_account );

			return $json->result;

		} else {

			$error_msg = __( 'Unable to contact to Auth Seed API service.', 'flowplayer5' );

		}

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
		$seed      = $this->get_auth_seed();

		$auth_api_url = esc_url_raw( add_query_arg(
			array(
				'callback' => '?',
				'username' => $user_name,
				'hash'     => sha1( $user_name . $seed . sha1( $password ) ),
				'seed'     => $seed
			),
			esc_url_raw( $this->account_api_url )
		) );

		$response = wp_remote_get( $auth_api_url );

		if ( wp_remote_retrieve_response_code( $response ) == 200 ) {

			$body = wp_remote_retrieve_body( $response );

			$auth = json_decode( $body );

			return $auth->result->authcode;

		} else {

			$error_msg = __( 'Unable to contact to Auth API service.', 'flowplayer5' );

		}

	}

	/**
	 * Attempts to request videos
	 *
	 * @since    1.2.0
	 */
	private function make_video_request() {

		$authcode = $this->make_auth_request();

		$verified_video_api_url = esc_url_raw( add_query_arg(
			array(
				'videos'   => 'true',
				'authcode' => $authcode
			),
			esc_url_raw( $this->video_api_url )
		) );

		$response_videos = wp_remote_get( $verified_video_api_url );

		if ( wp_remote_retrieve_response_code( $response_videos ) == 200 ) {

			$body = wp_remote_retrieve_body( $response_videos );

			$json = json_decode( $body );

			return $json->videos;

		} else {

			$error_msg = __( 'Unable to contact to Video API service.', 'flowplayer5' );

		}

	}

	/**
	 * Fetches all videos from Flowplayer Drive
	 *
	 * @since    1.2.0
	 */
	public function get_videos() {

		$json_videos = $this->make_video_request();

		if ( !isset( $error_msg ) ) {

			foreach ( $json_videos as $video ) {

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

		} else {

		return $error_msg;

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