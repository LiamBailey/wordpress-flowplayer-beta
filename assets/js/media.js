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

(function($) {
   $(function() {
      $.fn.wptuts = function(options) {
         var selector = $(this).selector; // Get the selector
         // Set default options
         var defaults = {
            'preview' : '.fp5_settings_upload_preview',
            'text'    : '.fp5_upload_field',
            'button'  : '.fp5_settings_upload_button',
         };
         var options  = $.extend(defaults, options);

         var _custom_media = true;
         var _orig_send_attachment = wp.media.editor.send.attachment;

          // When the Button is clicked...
         $(options.button).click(function() {
            // Get the Text element.
            var button = $(this);
            var text = $(this).siblings(options.text);
            var send_attachment_bkp = wp.media.editor.send.attachment;

            _custom_media = true;

            wp.media.editor.send.attachment = function(props, attachment) {
               if(_custom_media) {
                  // Get the URL of the new image
                  text.val(attachment.url).trigger('change');
               } else {
                  return _orig_send_attachment.apply(this, [props, attachment]);
               };
            }

            wp.media.editor.open(button);

            return false;
         });

         $('.add_media').on('click', function() {
           _custom_media = false;
         });

         $(options.text).bind('change', function() {
            // Get the value of current object
            var url = this.value;
            // Determine the Preview field
            var preview = $(this).siblings(options.preview);
            // Bind the value to Preview field
            $(preview).attr('src', url);
         });
      }

      // Usage
      $('.upload').wptuts(); // Use as default option.
   });
}(jQuery));

// Update skin image according to selection
jQuery(document).ready(function ($) {

	$('#fp5-select-skin option').each(function () {
		if ($(this).is(':selected')) {
			$("." + $(this).val()).show();
		}
	});
	$("select#fp5-select-skin").change(function () {
		$("img").hide();
		$("." + $(this).val()).show();
	});


});