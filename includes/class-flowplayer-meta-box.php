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

class fp5_metabox {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1.0
	 *
	 * @var     string
	 */
	private $version;

	/**
	 * Unique identifier for the plugin. This value is also used as the text domain
	 * when internationalizing strings of text.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	private $plugin_slug;

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     0.1.0
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
	 * @version    0.1.0
	 * @since      0.1.0
	 */
	public function add_fp5_video_meta_box() {

		add_meta_box(
			'fp5_video_details',
			__( 'Video Details', $this->plugin_slug ),
			array( $this, 'display_fp5_video_meta_box' ),
			'FlowPlayer5Video',
			'normal',
			'default'
		 );

	}

	/**
	 * Displays the meta box for displaying the 'Post Short URL' or a default
	 * message if one does not exist.
	 *
	 * @version    0.1.0
	 * @since      0.1.0
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
				<option id="fp5-minimalist" value="minimalist"<?php selected( $fp5_stored_meta['fp5-select-skin'][0], 'minimalist' ); ?>>Minimalist</option>
				<option id="fp5-functional" value="functional"<?php selected( $fp5_stored_meta['fp5-select-skin'][0], 'functional' ); ?>>Functional</option>
				<option id="fp5-playful" value="playful"<?php selected( $fp5_stored_meta['fp5-select-skin'][0], 'playful' ); ?>>Playful</option>
			</select>
		</p>

		<p>
			<span class="example-row-title"><?php _e('Video attributes')?></span>
			<div class="example-row-content">
				<label for="fp5-autoplay">
					<input type="checkbox" name="fp5-autoplay" id="fp5-autoplay" value="yes" <?php checked( $fp5_stored_meta['autoplay'][0], 'yes' ); ?> />
					<?php _e( 'Autoplay?' )?>
				</label>
				<label for="fp5-loop">
					<input type="checkbox" name="fp5-loop" id="fp5-loop" value="yes" <?php checked( $fp5_stored_meta['loop'][0], 'yes' ); ?> />
					<?php _e( 'Loop?' )?>
				</label>
			</div>
		</p>

		<p>
			<label for="splash-image"><?php _e( 'Splash Image', $this->plugin_slug )?></label>
			<input class="mediaUrl" type="text" id="splash-image" size="70" value="<?php if ( isset ( $fp5_stored_meta['splash-image'] ) ) echo $fp5_stored_meta['splash-image'][0]?>" name="splash-image" />
			<a href="#" class="fp5-add-splash-image button button-primary" title="<?php _e( 'Add splash image', $this->plugin_slug )?>"><?php _e( 'Add splash image', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="mp4-video"><?php _e( 'mp4 video', $this->plugin_slug )?></label>
			<input class="mediaUrl" type="text" id="mp4-video" size="70" value="<?php echo $fp5_stored_meta['mp4-video'][0]; ?>" name="mp4-video" />
			<a href="#" class="fp5-add-mp4 button button-primary" title="<?php _e( 'Add mp4 video', $this->plugin_slug )?>"><?php _e( 'Add mp4 video', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="webm-video"><?php _e( 'webm video', $this->plugin_slug )?></label>
			<input class="mediaUrl" type="text" id="webm-video" size="70" value="<?php echo $fp5_stored_meta['webm-video'][0]; ?>" name="webm-video" />
			<a href="#" class="fp5-add-webm button button-primary" title="<?php _e( 'Add webm video', $this->plugin_slug )?>"><?php _e( 'Add webm video', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="ogg-video"><?php _e( 'ogg video', $this->plugin_slug )?></label>
			<input class="mediaUrl" type="text" id="ogg-video" size="70" value="<?php echo $fp5_stored_meta['ogg-video'][0]; ?>" name="ogg-video" />
			<a href="#" class="fp5-add-ogg button button-primary" title="<?php _e( 'Add ogg video', $this->plugin_slug )?>"><?php _e( 'Add ogg video', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="webvtt"><?php _e( 'webvtt file', $this->plugin_slug )?></label>
			<input class="mediaUrl" type="text" id="webvtt" size="70" value="<?php echo $fp5_stored_meta['webvtt'][0]; ?>" name="webvtt" />
			<a href="#" class="fp5-add-webvtt button button-primary" title="<?php _e( 'Add webvtt file', $this->plugin_slug )?>"><?php _e( 'Add webvtt file', $this->plugin_slug )?></a>
		</p>

		<p>
			<div id="fp5-preview" class="preview"><?php _e( 'Preview' ) ?>
				<div id="video"></div>
			</div>
		</p>

		<p>
			<label for="max-width" class="example-row-title"><?php _e('Max width')?></label>
			<input type="text" name="max-width" id="max-width" value="<?php echo $fp5_stored_meta['max-width'][0]; ?>" />
			<label for="aspect-ratio">
				<input type="checkbox" name="aspect-ratio" id="aspect-ratio" value="yes" <?php checked( $fp5_stored_meta['aspect-ratio'][0], 'yes' ); ?> />
				<?php _e('Use video\'s aspect ratio')?>
			</label>
			<label for="max-height" class="example-row-title"><?php _e('Max height')?></label>
			<input type="text" name="max-height" id="max-height" value="<?php echo $fp5_stored_meta['max-height'][0]; ?>" />
			<label for="fixed-width">
				<input type="checkbox" name="fixed-width" id="fixed-width" value="yes" <?php checked( $fp5_stored_meta['fixed-width'][0], 'yes' ); ?> />
				<?php _e('Use fixed player size') ?>
			</label>
		</p>

	<?php
	}

	/**
	 * When the post is saved or updated, generates a short URL to the existing post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @version  0.1.0
	 * @since    0.1.0
	 */
	public function save_fp5_video_details( $post_id ) {

		if ( $this->user_can_save( $post_id, 'fp5-nonce' ) ) {

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-select-skin' ] ) ) {
				update_post_meta( $post_id, 'fp5-select-skin', $_POST[ 'fp5-select-skin' ] );
			};

			// Checks for input and saves
			if( isset( $_POST[ 'autoplay' ] ) ) {
				update_post_meta( $post_id, 'autoplay', 'yes' );
			} else {
				update_post_meta( $post_id, 'autoplay', '' );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'loop' ] ) ) {
				update_post_meta( $post_id, 'loop', 'yes' );
			} else {
				update_post_meta( $post_id, 'loop', '' );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'splash-image' ] ) ) {
				update_post_meta( $post_id, 'splash-image', $_POST[ 'splash-image' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'mp4-video' ] ) ) {
				update_post_meta( $post_id, 'mp4-video', $_POST[ 'mp4-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'webm-video' ] ) ) {
				update_post_meta( $post_id, 'webm-video', $_POST[ 'webm-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'ogg-video' ] ) ) {
				update_post_meta( $post_id, 'ogg-video', $_POST[ 'ogg-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'webvtt' ] ) ) {
				update_post_meta( $post_id, 'webvtt', $_POST[ 'webvtt' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'max-width' ] ) ) {
				update_post_meta( $post_id, 'max-width', $_POST[ 'max-width' ] );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'aspect-ratio' ] ) ) {
				update_post_meta( $post_id, 'aspect-ratio', 'yes' );
			} else {
				update_post_meta( $post_id, 'aspect-ratio', '' );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'max-height' ] ) ) {
				update_post_meta( $post_id, 'max-height', $_POST[ 'max-height' ] );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fixed-width' ] ) ) {
				update_post_meta( $post_id, 'fixed-width', 'yes' );
			} else {
				update_post_meta( $post_id, 'fixed-width', '' );
			}

		}

	}


	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @param    string  $nonce      The nonce identifier associated with the value being saved
	 * @return   bool                Whether or not the user has the ability to save this post.
	 * @version  0.1.0
	 * @since    0.1.0
	 */
	private function user_can_save( $post_id, $nonce ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], plugin_basename( __FILE__ ) ) ) ? true : false;

		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision) && $is_valid_nonce;

	}

}