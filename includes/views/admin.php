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
<?php

$args = array(
	'depth'        => 0,
	'show_date'    => '',
	'date_format'  => get_option( 'date_format' ),
	'child_of'     => 0,
	'exclude'      => '',
	'include'      => '',
	'title_li'     => __( 'Pages' ),
	'echo'         => 1,
	'authors'      => '',
	'sort_column'  => 'menu_order, post_title',
	'link_before'  => '',
	'link_after'   => '',
	'walker'       => '',
	'post_type'    => 'page',
	'post_status'  => 'publish'
);
wp_list_pages( $args );
?>