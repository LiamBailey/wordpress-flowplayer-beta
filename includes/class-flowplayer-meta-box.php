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
			<label for="tgm-new-media-image"><?php _e( 'mp4 video', $this->plugin_slug )?></label>
			<input class="mediaUrl" type="text" id="tgm-new-media-image" size="70" value="<?php echo $fp5_stored_meta['tgm-new-media-image'][0]; ?>" name="tgm-new-media-image" />
			<a href="#" class="tgm-open-media button button-primary" title="<?php _e( 'Add mp4 video', $this->plugin_slug )?>"><?php _e( 'Add mp4 video', $this->plugin_slug )?></a>
		</p>

		<p>
			<label for="webm-video"><?php _e( 'webm video', $this->plugin_slug )?></label>
			<input class="mediaUrl" type="text" id="webm-video" size="70" value="<?php echo $fp5_stored_meta['webm-video'][0]; ?>" name="webm-video" />
			<a href="#" class="fp5-open-media button button-primary" title="<?php _e( 'Add webm video', $this->plugin_slug )?>"><?php _e( 'Add webm video', $this->plugin_slug )?></a>
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
			if( isset( $_POST[ 'tgm-new-media-image' ] ) ) {
				update_post_meta( $post_id, 'tgm-new-media-image', $_POST[ 'tgm-new-media-image' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'webm-video' ] ) ) {
				update_post_meta( $post_id, 'webm-video', $_POST[ 'webm-video' ] );
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