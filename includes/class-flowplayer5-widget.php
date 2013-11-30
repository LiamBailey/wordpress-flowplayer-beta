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

		/**
		 * Default widget option values.
		 */
		$this->defaults = array(
			'id' => '3'
		);

		$widget_ops = array(
			'classname'   => 'flowplayer5-video-widget',
			'description' => __( 'Display your Flowplayer Videos in a Widget.', $this->plugin_slug )
		);

		parent::__construct(
				'flowplayer5-video-widget',
				__( 'Flowplayer Video Widget', $this->plugin_slug ),
				$widget_ops
		);

	}

	/**
	 * Display widget frontend.
	 *
	 * @since    1.4.0
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		extract( $args, EXTR_SKIP );

		$id    = $instance['id'];
		$title = get_the_title( $id );
		echo $before_widget;
		echo '<h3 class="widget-title">' . $title . '</h3>';
		echo $id;
		echo Flowplayer5_Shortcode::create_fp5_video_output( $id );
		echo $after_widget;
	}

	/**
	 * Update widget settings.
	 *
	 * @since    1.4.0
	 */
	public function update( $new_instance, $old_instance ) {

		echo $new_instance['id'];
		$instance['id'] = esc_attr( $new_instance['id'] );

		return $instance;

	}

	/**
	 * Admin side Widget form.
	 *
	 * @since    1.4.0
	 */
	public function form( $instance ) {

		// Merge with defaults
		$instance = wp_parse_args(
			( array ) $instance,
			$this->defaults
		);

		$id = isset( $instance['id']) ? esc_attr( $instance['id'] ) : '';

		// WP_Query arguments
		$args = array (
			'post_type'              => 'flowplayer5',
			'post_status'            => 'publish',
		);

		// The Query
		$query = new WP_Query( $args );
		$posts = $query->posts;

		$html = '<p><label for="' . $this->get_field_id( 'id' ) . '">' . __( 'Chose a video from the dropdown.', $this->plugin_slug ) . '</label>';
		$html .= '<select class="widefat" name="' . $this->get_field_id( 'id' ) . '" id="' . $this->get_field_name( 'id' ) . '">';
		foreach ( $posts as $post ) {
			$html .= '<option value="' . $post->ID . '"' . selected( $post->ID, $id ) . '>' . $post->post_title . '</option>';
		}
		$html .= '</select></p>';

		echo $html;

	}

}

/**
 * Register the Flowplayer5 widgets on startup.
 *
 * Calls 'widgets_init' action after all of the WordPress widgets have been
 * registered.
 *
 * @since 1.4.0
 */
function fp5_widgets_init() {

	register_widget( 'Flowplayer5_Widget' );

}
add_action( 'widgets_init', 'fp5_widgets_init' );