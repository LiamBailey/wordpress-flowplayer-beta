<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5
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
 * Flowplayer5 Widget Class
 *
 * @package Flowplayer5
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.4.0
 */
class Flowplayer5_Widget extends WP_Widget {

	/**
	 * Initialize the flowplayer5 widget.
	 *
	 * @since    1.3.0
	 */
	public function __construct() {

		$plugin = Flowplayer5::get_instance();
		// Call $plugin_slug from public plugin class.
		$this->plugin_slug = $plugin->get_plugin_slug();

		parent::__construct(
				'flowplayer5-video-widget',
				__( 'Flowplayer Video Widget', $this->plugin_slug ),
				array(
						'classname'   => 'flowplayer5-video-widget',
						'description' => __( 'Display your Flowplayer Videos in a Widget.', $this->plugin_slug )
				)
		);

	}

	/**
	 * Admin side Widget form.
	 *
	 * @since    1.4.0
	 */
 	public function form( $instance ) {
		// outputs the options form on admin
		$instance = wp_parse_args(
			( array ) $instance
		);
		
		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . '/views/admin.php' );
	}

	/**
	 * Update widget settings.
	 *
	 * @since    1.4.0
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;

		$instance['flowplayer5_video_id']    = esc_attr( $new_instance['flowplayer5_video_id'] );

		return $instance;

	}

	/**
	 * Display widget frontend.
	 *
	 * @since    1.4.0
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		extract( $args, EXTR_SKIP );
		
		$id    = $instance['flowplayer5_video_id'];
		$title = get_the_title( $id );
		echo $before_widget;
		Flowplayer5_Shortcode::create_fp5_video_output( $id );
		echo $after_widget;
	}

}

add_action( 'widgets_init', create_function( '', 'register_widget("Flowplayer5_Widget");' ) );