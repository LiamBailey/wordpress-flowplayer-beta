<p><?php echo __( 'Chose a video from the dropdown.', 'flowplayer5' ); ?></p>
<?php
// WP_Query arguments
$args = array (
	'post_type'              => 'flowplayer5',
	'post_status'            => 'publish',
);

// The Query
$query = new WP_Query( $args );
$posts = $query->posts;
//print_r( $posts);
echo '<select name="flowplayer5_video_id" id="flowplayer5_video_id">';
foreach ( $posts as $post ) {
	echo '<option value="' . $post->ID . '" id="' . $post->ID . '"', $select == $option ? ' selected="selected"' : '', '>' . $post->post_title . '</option>';
}
echo '</select>';
?>