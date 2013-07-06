<?php
/**
 * Represents the the meta box in the custom post type.
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

class video_meta_box {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	private $version;

	/**
	 * Unique identifier for the plugin. This value is also used as the text domain
	 * when internationalizing strings of text.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $plugin_slug;

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$this->version = '0.1.0';
		$this->plugin_slug = 'fp5';

		// Setup the meta box responsible for displaying the short URL
		add_action( 'add_meta_boxes', array( $this, 'add_fp5_video_meta_box' ) );

		// Setup the function responsible for generating and saving the short URL
		add_action( 'save_post', array( $this, 'save_fp5_video_details' ) );

	}

	/**
	 * Registers the meta box for displaying the 'Post Short URL' in the post editor.
	 *
	 * @version    1.0.0
	 * @since      1.0.0
	 */
	public function add_fp5_video_meta_box() {

		add_meta_box(
			'fp5_video_details',
			__( 'Video Details', $this->plugin_slug ),
			array( $this, 'display_fp5_video_meta_box' ),
			'flowplayer',
			'normal',
			'default'
		 );

	}

	/**
	 * Displays the meta box for displaying the 'Post Short URL' or a default
	 * message if one does not exist.
	 *
	 * @version    1.0.0
	 * @since      1.0.0
	 */
	public function display_fp5_video_meta_box( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'fp5-nonce' );
		$fp5_stored_meta = get_post_meta( $post->ID );
		$fp5_select_skin = get_post_meta($post->ID,'_fp5_select_skin',true);
		?>

		<p>
			<label for="fp5-select-skin">
				<?php _e( 'Select skin', $this->plugin_slug ); ?>
			</label>

			<select id="fp5-select-skin" name="fp5-select-skin">
				<option id="fp5-minimalist" value="minimalist"<?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'minimalist' ); ?>>Minimalist</option>
				<option id="fp5-functional" value="functional"<?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'functional' ); ?>>Functional</option>
				<option id="fp5-playful" value="playful"<?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'playful' ); ?>>Playful</option>
			</select>
		</p>

		<p>
			<span class="fp5-row-title"><?php _e('Video attributes')?></span>
			<div class="fp5-row-content">
				<label for="fp5-autoplay">
					<input type="checkbox" name="fp5-autoplay" id="fp5-autoplay" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-autoplay'] ) ) checked( $fp5_stored_meta['fp5-autoplay'][0], 'true' ); ?> />
					<?php _e( 'Autoplay?' )?>
				</label>
				<label for="fp5-loop">
					<input type="checkbox" name="fp5-loop" id="fp5-loop" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-loop'] ) ) checked( $fp5_stored_meta['fp5-loop'][0], 'true' ); ?> />
					<?php _e( 'Loop?' )?>
				</label>
				<label for="fp5-preload" class="fp5-row-title"><?php _e( 'Preload?' )?></label>
				<select name="fp5-preload" id="fp5-preload">
					<option value="auto" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'auto' ); ?>>auto</option>';
					<option value="metadata" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'metadata' ); ?>>metadata</option>';
					<option value="none" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'none' ); ?>>none</option>';
				</select>
			</div>
		</p>

		<p>
			<label for="fp5-splash-image"><?php _e( 'Splash Image', $this->plugin_slug )?></label>
			<input class="media-url" type="text" name="fp5-splash-image" id="fp5-splash-image" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-splash-image'] ) ) echo $fp5_stored_meta['fp5-splash-image'][0]?>" />
			<a href="#" class="fp5-add-splash-image button button-primary" title="<?php _e( 'Add splash image', $this->plugin_slug )?>"><?php _e( 'Add splash image', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="fp5-mp4-video"><?php _e( 'mp4 video', $this->plugin_slug )?></label>
			<input class="media-url" type="text" name="fp5-mp4-video" id="fp5-mp4-video" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-mp4-video'] ) ) echo $fp5_stored_meta['fp5-mp4-video'][0]; ?>" />
			<a href="#" class="fp5-add-mp4 button button-primary" title="<?php _e( 'Add mp4 video', $this->plugin_slug )?>"><?php _e( 'Add mp4 video', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="fp5-webm-video"><?php _e( 'webm video', $this->plugin_slug )?></label>
			<input class="media-url" type="text" name="fp5-webm-video" id="fp5-webm-video" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-webm-video'] ) ) echo $fp5_stored_meta['fp5-webm-video'][0]; ?>" />
			<a href="#" class="fp5-add-webm button button-primary" title="<?php _e( 'Add webm video', $this->plugin_slug )?>"><?php _e( 'Add webm video', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="fp5-ogg-video"><?php _e( 'ogg video', $this->plugin_slug )?></label>
			<input class="media-url" type="text" name="fp5-ogg-video" id="fp5-ogg-video" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-ogg-video'] ) ) echo $fp5_stored_meta['fp5-ogg-video'][0]; ?>" />
			<a href="#" class="fp5-add-ogg button button-primary" title="<?php _e( 'Add ogg video', $this->plugin_slug )?>"><?php _e( 'Add ogg video', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="fp5-vtt-subtitles"><?php _e( 'vtt file (Subtitles)', $this->plugin_slug )?></label>
			<input class="media-url" type="text" name="fp5-vtt" id="fp5-vtt" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-vtt-subtitles'] ) ) echo $fp5_stored_meta['fp5-vtt-subtitles'][0]; ?>" />
			<a href="#" class="fp5-add-vtt button button-primary" title="<?php _e( 'Add vtt file', $this->plugin_slug )?>"><?php _e( 'Add vtt file', $this->plugin_slug )?></a>
		</p>

		<p>
			<div id="fp5-preview" class="preview"><?php _e( 'Preview' ) ?>
				<div id="video"></div>
			</div>
		</p>

		<p>
			<label for="fp5-max-width" class="fp5-row-title"><?php _e('Max width')?></label>
			<input type="text" name="fp5-max-width" id="fp5-max-width" value="<?php if ( isset ( $fp5_stored_meta['fp5-max-width'] ) ) echo $fp5_stored_meta['fp5-max-width'][0]; ?>" />
			<label for="fp5-aspect-ratio">
				<input type="checkbox" name="fp5-aspect-ratio" id="fp5-aspect-ratio" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-aspect-ratio'] ) ) checked( $fp5_stored_meta['aspect-ratio'][0], 'true' ); ?> />
				<?php _e('Use video\'s aspect ratio')?>
			</label>
			<label for="fp5-max-height" class="fp5-row-title"><?php _e('Max height')?></label>
			<input type="text" name="fp5-max-height" id="fp5-max-height" value="<?php if ( isset ( $fp5_stored_meta['fp5-max-height'] ) ) echo $fp5_stored_meta['fp5-max-height'][0]; ?>" />
			<label for="fp5-fixed-width">
				<input type="checkbox" name="fp5-fixed-width" id="fp5-fixed-width" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-fixed-width'] ) ) checked( $fp5_stored_meta['fp5-fixed-width'][0], 'true' ); ?> />
				<?php _e('Use fixed player size') ?>
			</label>
		</p>

	<?php
	}

	/**
	 * When the post is saved or updated, generates a short URL to the existing post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @version  1.0.0
	 * @since    1.0.0
	 */
	public function save_fp5_video_details( $post_id ) {

		if ( $this->user_can_save( $post_id, 'fp5-nonce' ) ) {

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-select-skin' ] ) ) {
				update_post_meta( $post_id, 'fp5-select-skin', $_POST[ 'fp5-select-skin' ] );
			};

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-autoplay' ] ) ) {
				update_post_meta( $post_id, 'fp5-autoplay', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-autoplay', '' );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-loop' ] ) ) {
				update_post_meta( $post_id, 'fp5-loop', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-loop', '' );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-preload' ] ) ) {
				update_post_meta( $post_id, 'fp5-preload', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-preload', '' );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-splash-image' ] ) ) {
				update_post_meta( $post_id, 'fp5-splash-image', $_POST[ 'fp5-splash-image' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-mp4-video' ] ) ) {
				update_post_meta( $post_id, 'fp5-mp4-video', $_POST[ 'fp5-mp4-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-webm-video' ] ) ) {
				update_post_meta( $post_id, 'fp5-webm-video', $_POST[ 'fp5-webm-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-ogg-video' ] ) ) {
				update_post_meta( $post_id, 'fp5-ogg-video', $_POST[ 'fp5-ogg-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-vtt' ] ) ) {
				update_post_meta( $post_id, 'fp5-vtt', $_POST[ 'fp5-vtt' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-max-width' ] ) ) {
				update_post_meta( $post_id, 'fp5-max-width', $_POST[ 'fp5-max-width' ] );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-aspect-ratio' ] ) ) {
				update_post_meta( $post_id, 'fp5-aspect-ratio', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-aspect-ratio', '' );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-max-height' ] ) ) {
				update_post_meta( $post_id, 'fp5-max-height', $_POST[ 'fp5-max-height' ] );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-fixed-width' ] ) ) {
				update_post_meta( $post_id, 'fp5-fixed-width', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-fixed-width', '' );
			}

		}

	}


	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @param    string  $nonce      The nonce identifier associated with the value being saved
	 * @return   bool                Whether or not the user has the ability to save this post.
	 * @version  1.0.0
	 * @since    1.0.0
	 */
	private function user_can_save( $post_id, $nonce ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], plugin_basename( __FILE__ ) ) ) ? true : false;

		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision) && $is_valid_nonce;

	}

}