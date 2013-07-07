/**
 * Display Settings
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 
 * @since    1.0.0
 */

// Add Splash Image
jQuery(document).ready(function($){

    var fp5_splash_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-splash-image', function(e){

        e.preventDefault();

        if ( fp5_splash_frame ) {
            fp5_splash_frame.open();
            return;
        }

        fp5_splash_frame = wp.media.frames.fp5_splash_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: splash_image.title,
            library: {
                type: 'image'
            },
            button: {
                text: splash_image.button
            }
        });

        fp5_splash_frame.on('select', function(){

            var media_attachment = fp5_splash_frame.state().get('selection').first().toJSON();

            $('#fp5-splash-image').val(media_attachment.url);
        });

        fp5_splash_frame.open();
    });
});

// Add mp4 video
jQuery(document).ready(function($){

    var fp5_mp4_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-mp4', function(e){

        e.preventDefault();

        if ( fp5_mp4_frame ) {
            fp5_mp4_frame.open();
            return;
        }

        fp5_mp4_frame = wp.media.frames.fp5_mp4_frame = wp.media({
            className: 'media-frame tgm-media-frame',
            frame: 'select',
            multiple: false,
            title: mp4_video.title,
            library: {
                type: 'video/mp4'
            },
            button: {
                text:  mp4_video.button
            }
        });

        fp5_mp4_frame.on('select', function(){
            var media_attachment = fp5_mp4_frame.state().get('selection').first().toJSON();
            $('#fp5-mp4-video').val(media_attachment.url);
        });
        fp5_mp4_frame.open();
    });
});

// Add webm video
jQuery(document).ready(function($){
    var fp5_webm_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-webm', function(e){
        e.preventDefault();

        if ( fp5_webm_frame ) {
            fp5_webm_frame.open();
            return;
        }

        fp5_webm_frame = wp.media.frames.fp5_webm_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: webm_video.title,
            library: {
                type: 'video/webm'
            },
            button: {
                text:  webm_video.button
            }
        });

        fp5_webm_frame.on('select', function(){
            var media_attachment = fp5_webm_frame.state().get('selection').first().toJSON();

            $('#fp5-webm-video').val(media_attachment.url);
        });

        fp5_webm_frame.open();
    });
});

// Add ogg video
jQuery(document).ready(function($){
    var fp5_ogg_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-ogg', function(e){
        e.preventDefault();

        if ( fp5_ogg_frame ) {
            fp5_ogg_frame.open();
            return;
        }

        fp5_ogg_frame = wp.media.frames.fp5_ogg_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: ogg_video.title,
            library: {
                type: 'video/ogg'
            },
            button: {
                text:  ogg_video.button
            }
        });

        fp5_ogg_frame.on('select', function(){
            var media_attachment = fp5_ogg_frame.state().get('selection').first().toJSON();

            $('#fp5-ogg-video').val(media_attachment.url);
        });

        fp5_ogg_frame.open();
    });
});

// Add vtt subtitles
jQuery(document).ready(function($){
    var fp5_webvtt_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-vtt', function(e){
        e.preventDefault();

        if ( fp5_webvtt_frame ) {
            fp5_webvtt_frame.open();
            return;
        }

        fp5_webvtt_frame = wp.media.frames.fp5_webvtt_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: webvtt.title,
            library: {
                type: 'text/vtt'
            },
            button: {
                text:  webvtt.button
            }
        });

        fp5_webvtt_frame.on('select', function(){
            var media_attachment = fp5_webvtt_frame.state().get('selection').first().toJSON();

            $('#fp5-vtt-subtitles').val(media_attachment.url);
        });

        fp5_webvtt_frame.open();
    });
});

jQuery(document).ready(function ($) {
    $('#video video').remove();
    $(".media-url").blur(function () {
        $('#video video').remove();
        $('#video').append('<video controls>' +
            '<source type="video/mp4" src="' + $('#fp5-mp4-video').val() + '"/>' +
            '<source type="video/webm" src="' + $('#fp5-webm-video').val() + '"/>' +
            '<source type="video/webm" src="' + $('#fp5-ogg-video').val() + '"/>' +
            '<track kind="subtitles" srclang="en" label="English" src="' + $('#fp5-vtt').val() + '"/>' +
            '</video>');
    });
});

// Settings Page
// Add Logo
jQuery(document).ready(function($){
    var fp5_logo_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5_settings_upload_button', function(e){
        e.preventDefault();

        if ( fp5_logo_frame ) {
            fp5_logo_frame.open();
            return;
        }

        fp5_logo_frame = wp.media.frames.fp5_logo_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: logo.title,
            library: {
                type: 'image'
            },
            button: {
                text: logo.button
            }
        });

        fp5_logo_frame.on('select', function(){
            var media_attachment = fp5_logo_frame.state().get('selection').first().toJSON();

            $('#fp5_settings_general[logo]').val(media_attachment.url);
        });

        fp5_logo_frame.open();
    });
});

jQuery(document).ready(function ($) {
    // Settings Upload field JS
    if( typeof wp == "undefined" || edd_vars.new_media_ui != '1' ){
		//Old Thickbox uploader
		if ( $( '.edd_settings_upload_button' ).length > 0 ) {
			window.formfield = '';

			$('body').on('click', '.edd_settings_upload_button', function(e) {
				e.preventDefault();
				window.formfield = $(this).parent().prev();
				window.tbframe_interval = setInterval(function() {
					jQuery('#TB_iframeContent').contents().find('.savesend .button').val(edd_vars.use_this_file).end().find('#insert-gallery, .wp-post-thumbnail').hide();
				}, 2000);
				tb_show(edd_vars.add_new_download, 'media-upload.php?TB_iframe=true');
			});

			window.edd_send_to_editor = window.send_to_editor;
			window.send_to_editor = function (html) {
				if (window.formfield) {
					imgurl = $('a', '<div>' + html + '</div>').attr('href');
					window.formfield.val(imgurl);
					window.clearInterval(window.tbframe_interval);
					tb_remove();
				} else {
					window.edd_send_to_editor(html);
				}
				window.send_to_editor = window.edd_send_to_editor;
				window.formfield = '';
				window.imagefield = false;
			}
		}
	} else {
		// WP 3.5+ uploader
		var file_frame;
		window.formfield = '';

		$('body').on('click', '.edd_settings_upload_button', function(e) {

			e.preventDefault();

			var button = $(this);

			window.formfield = $(this).parent().prev();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				title: button.data( 'uploader_title' ),
				button: {
					text: button.data( 'uploader_button_text' ),
				},
				multiple: false
			});

			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};

		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');

		        // Initialize the views in our view object.
		        view.set(views);
		    });

			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {

				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					window.formfield.val(attachment.url);
				});
			});

			// Finally, open the modal
			file_frame.open();
		});


		// WP 3.5+ uploader
		var file_frame;
		window.formfield = '';
	}
});