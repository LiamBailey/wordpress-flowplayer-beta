<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * Use PHPDoc tags if you wish to be able to document the code using a documentation
 * generator.
 *
 * @package PluginName
 * @author  Your Name <email@example.com>
 * @license GPL-2.0+
 * @link    TODO
 * @version 1.0.0
 */

/**
 * If this file is attempted to be accessed directly, we'll exit.
 *
 * The following check provides a level of security from other files
 * that request data directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Calls the class on the post edit screen
 */
function call_fp5_meta_box() {
	return new fp5_meta_box();
}
if ( is_admin() )
	add_action( 'load-post.php', 'call_fp5_meta_box' );

/** 
 * The Class
 */
class fp5_meta_box {

	const LANG = 'some_textdomain';

	public function __construct()
	{
		add_action( 'add_meta_boxes', array( &$this, 'add_fp5_meta_box' ) );
	}

	/**
	 * Adds the meta box container
	 */
	public function add_fp5_meta_box() {
		add_meta_box( 
			 'flowplayer5'
			,__( 'Add Flowplayer', self::LANG )
			,array( &$this, 'render_meta_box_content' )
			,'video' 
			,'advanced'
			,'default'
		);
	}


	/**
	 * Render Meta Box content
	 */
	public function render_meta_box_content() {
		echo '<h1>TEST OUTPUT - this gets rendered inside the meta box.</h1>';
	}
}