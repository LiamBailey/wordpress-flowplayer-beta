/**
 * Main jQuery media file for the plugin.
 *
 * @since 1.0.0
 *
 * @package TGM New Media Plugin
 * @author  Thomas Griffin
 */
jQuery(document).ready(function($){
    // Prepare the variable that holds our custom media manager.
    var fp5_media_frame;
    
    // Bind to our click event in order to open up the new media experience.
    $(document.body).on('click.tgmOpenMediaManager', '.fp5-open-media', function(e){
        // Prevent the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if ( fp5_media_frame ) {
            fp5_media_frame.open();
            return;
        }

        /**
         * The media frame doesn't exist let, so let's create it with some options.
         *
         * This options list is not exhaustive, so I encourage you to view the
         * wp-includes/js/media-views.js file to see some of the other default
         * options that can be utilized when creating your own custom media workflow.
         */
        fp5_media_frame = wp.media.frames.fp5_media_frame = wp.media({
            /**
             * We can pass in a custom class name to our frame, so we do
             * it here to provide some extra context for styling our
             * media workflow. This helps us to prevent overwriting styles
             * for other media workflows.
             */
            className: 'media-frame tgm-media-frame',

            /**
             * When creating a new media workflow, we are given two types
             * of frame workflows to chose from: 'select' or 'post'.
             *
             * The 'select' workflow is the default workflow, mainly beneficial
             * for uses outside of a post or post type experience where a post ID
             * is crucial.
             *
             * The 'post' workflow is tailored to screens where utilizing the
             * current post ID is critical.
             *
             * Since we only want to upload an image, let's go with the 'select'
             * frame option.
             */
            frame: 'select',

            /**
             * We can determine whether or not we want to allow users to be able
             * to upload multiple files at one time by setting this parameter to
             * true or false. It defaults to true, but we only want the user to
             * upload one file, so let's set it to false.
             */
            multiple: false,

            /**
             * We can set a custom title for our media workflow. I've localized
             * the script with the object 'tgm_nmp_media' that holds our
             * localized stuff and such. Let's populate the title with our custom
             * text.
             */
            title: tgm_nmp_media.title,

            /**
             * We can force what type of media to show when the user views his/her
             * library. Since we are uploading an image, let's limit the view to
             * images only.
             */
            library: {
                type: 'video/mp4'
            },

            /**
             * Let's customize the button text. It defaults to 'Select', but we
             * can customize it here to give us better context.
             *
             * We can also determine whether or not the modal requires a selection
             * before the button is enabled. It requires a selection by default,
             * and since this is the experience desired, let's keep it that way.
             *
             * By default, the toolbar generated by this frame fires a generic
             * 'select' event when the button is clicked. We could declare our
             * own events here, but the default event will work just fine.
             */
            button: {
                text:  tgm_nmp_media.button
            }
        });

        /**
         * We are now attaching to the default 'select' event and grabbing our
         * selection data. Since the button requires a selection, we know that a
         * selection will be available when the event is fired.
         *
         * All we are doing is grabbing the current state of the frame (which will
         * be 'library' since that's the only area where we can make a selection),
         * getting the selection, calling the 'first' method to pluck the first
         * object from the string and then forcing a faux JSON representation of
         * the model.
         *
         * When all is said and done, we are given absolutely everything we need to
         * insert the data into our custom input field. Specifically, our
         * media_attachment object will hold a key titled 'url' that we want to use.
         */
        fp5_media_frame.on('select', function(){
            // Grab our attachment selection and construct a JSON representation of the model.
            var media_attachment = fp5_media_frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom input field via jQuery.
            $('#tgm-new-media-image').val(media_attachment.url);
        });

        // Now that everything has been set, let's open up the frame.
        fp5_media_frame.open();
    });
});

// webm add
jQuery(document).ready(function($){
    // Prepare the variable that holds our custom media manager.
    var fp5_webm_frame;
    
    // Bind to our click event in order to open up the new media experience.
    $(document.body).on('click.tgmOpenMediaManager', '.fp5-add-webm', function(e){
        // Prevent the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if ( fp5_webm_frame ) {
            fp5_webm_frame.open();
            return;
        }

        fp5_webm_frame = wp.media.frames.fp5_webm_frame = wp.media({

            className: 'media-frame fp5-media-frame',

            frame: 'select',

            multiple: false,

            title: tgm_nmp_media.title,

            library: {
                type: 'video/webm'
            },

            button: {
                text:  tgm_nmp_media.button
            }
        });

        fp5_webm_frame.on('select', function(){
            // Grab our attachment selection and construct a JSON representation of the model.
            var media_attachment = fp5_webm_frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom input field via jQuery.
            $('#webm-video').val(media_attachment.url);
        });

        // Now that everything has been set, let's open up the frame.
        fp5_webm_frame.open();
    });
});

// ogg video add
jQuery(document).ready(function($){
    // Prepare the variable that holds our custom media manager.
    var fp5_ogg_frame;
    
    // Bind to our click event in order to open up the new media experience.
    $(document.body).on('click.tgmOpenMediaManager', '.fp5-open-media', function(e){
        // Prevent the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if ( fp5_ogg_frame ) {
            fp5_ogg_frame.open();
            return;
        }

        fp5_ogg_frame = wp.media.frames.fp5_ogg_frame = wp.media({

            className: 'media-frame fp5-media-frame',

            frame: 'select',

            multiple: false,

            title: tgm_nmp_media.title,

            library: {
                type: 'video/ogg'
            },

            button: {
                text:  tgm_nmp_media.button
            }
        });

        fp5_ogg_frame.on('select', function(){
            // Grab our attachment selection and construct a JSON representation of the model.
            var media_attachment = fp5_ogg_frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom input field via jQuery.
            $('#ogg-video').val(media_attachment.url);
        });

        // Now that everything has been set, let's open up the frame.
        fp5_ogg_frame.open();
    });
});