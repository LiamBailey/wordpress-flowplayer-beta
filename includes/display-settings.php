<div id="fp5_global" class="wrap">
	<div id="flowplayer5-icon" style="background: url('http://flowplayer.org/favicon.ico') no-repeat;" class="icon32"></br></div>
	<h2><?php _e('Flowplayer5 options') ?></h2>
		<form method="post" action="options.php"> 
			<?php @settings_fields('flowplayer5-group'); ?>
			<?php @do_settings_fields('flowplayer5-group'); ?>

			<?php do_settings_sections('flowplayer5'); ?>

			<?php @submit_button(); ?>
		</form>
</div>