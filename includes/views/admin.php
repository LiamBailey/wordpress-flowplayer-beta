<p><?php echo __( 'Use the dropdown below to chose a video.', 'flowplayer5' ); ?></p>
<div>
	<select name="flowplayer5_videos" id="flowplayer5_videos">
		<?php
		global $post;
		$args = array( 
			'posts_per_page' => -1,
			'post_type'      => 'flowplayer5'
		);
		$posts = get_posts( $args );
		foreach( $posts as $post ) : setup_postdata( $post ); ?>
		<option value="<? echo $post->ID; ?>"><?php the_title(); ?></option>
		<?php endforeach; ?>
	</select>
</div>