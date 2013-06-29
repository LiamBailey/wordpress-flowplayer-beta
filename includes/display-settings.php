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
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php settings_errors( 'edd-notices' ); ?>
	<form method="post" action="options.php">
		<?php
		// This prints out all hidden setting fields
		settings_fields( 'fp5_settings_commercial' );
		settings_fields( 'fp5_settings_video_tracking' );
		settings_fields( 'fp5_settings_cdn' );
		do_settings_sections( 'flowplayer5' );
		submit_button();
		?>
	</form>

</div>