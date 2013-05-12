<?php
function fp5_postOptionsForm() {
    ?>
    <div class="options" xmlns="http://www.w3.org/1999/html">
        <div class="optgroup">
            <label for="fp5_selectSkin">
                <?php _e('Select skin')?>
            </label>
            <select id="fp5_selectSkin" class="option">
                <option class="fp5[skin]" id="fp5_minimalistSel" value="minimalist" selected="selected"><?php _e('Minimalist' ) ?></option>
                <option class="fp5[skin]" id="fp5_functionalSel" value="functional"><?php _e('Functional' ) ?></option>
                <option class="fp5[skin]" id="fp5_playfulSel" value="playful"><?php _e('Playful' ) ?></option>
            </select>
            <div class="option">
                <img id="fp5_minimalist" src="<?php print(FP5_PLUGIN_URL.'assets/img/minimalist.png')  ?>" />
                <img id="fp5_functional" src="<?php print(FP5_PLUGIN_URL.'assets/img/functional.png')  ?>" />
                <img id="fp5_playful" src="<?php print(FP5_PLUGIN_URL.'assets/img/playful.png')  ?>" />
            </div>
        </div>
        <div class="optgroup separated">
            <label for="fp5_videoAttributes">
                <?php _e('Video attributes')?> <a href="http://flowplayer.org/docs/index.html#video-attributes" target="_blank"><?php _e('(Info)')?></a>
            </label>
            <div class="wide"></div>
            <div id="fp5_videoAttributes" class="option">
                <label for="fp5_autoplay"><?php _e('Autoplay?')?></label>
                <input type="checkbox" name="fp5[autoplay]" id="fp5_autoplay" value="true" />
            </div>
            <div class="option">
                <label for="fp5_loop"><?php _e('Loop?')?></label>
                <input type="checkbox" name="fp5[loop]" id="fp5_loop" value="true" />
            </div>
        </div>

        <div class="optgroup">
            <div class="option wide">
                <label for="fp5_splash">
                    <a href="http://flowplayer.org/docs/index.html#splash" target="_blank"><?php _e('Splash image')?></a><br/><?php _e('(optional)')?>
                </label>
                <input class="mediaUrl" type="text" name="fp5[splash]" id="fp5_splash" />
                <input id="fp5_chooseSplash" type="button" value="<?php _e('Media Library'); ?>" />
            </div>
        </div>

        <div class="optgroup separated">
            <div class="head" for="fp5_videos">
                <?php _e('URLs for videos, at least one is needed. You need a video format supported by your web browser, otherwise the preview below does not work.')?>
                <a href="http://flowplayer.org/docs/#video-formats" target="_blank"><?php _e('About video formats')?></a>.
            </div>
            <div class="option wide">
                <label for="fp5_mp4"><?php _e('mp4')?></label>
                <input class="mediaUrl" type="text" name="fp5[mp4]" id="fp5_mp4" />
            </div>
            <div id="fp5_videos" class="option wide">
                <label for="fp5_webm"><?php _e('webm')?></label>
                <input class="mediaUrl" type="text" name="fp5[webm]" id="fp5_webm" />
            </div>
            <div class="option wide">
                <label for="fp5_ogg"><?php _e('ogg')?></label>
                <input class="mediaUrl" type="text" name="fp5[ogg]" id="fp5_ogg" />
            </div>
                <input id="fp5_chooseMedia" type="button" value="<?php _e('Media Library'); ?>" />
        </div>

        <div class="optgroup">
            <div class="option">
                <div id="preview" class="preview"><?php _e( 'Preview' ) ?>
                    <div class="flowplayer">
                        <video id="fp5_videoPreview" width="320" height="240" controls="controls">
                        </video>
                    </div>
                </div>
            </div>
            <div class="details separated">
                <label for="fp5_width"><?php _e('Maximum dimensions for the player are determined from the provided video files. You can change this size below. Fixing the player size disables scaling for different screen sizes.')?></label>
                <div class="wide"></div>
                <div class="option">
                    <label for="fp5_width"><?php _e('Max width')?></label>
                    <input class="small" type="text" id="fp5_width" name="fp5[width]" />
                </div>
                <div class="option">
                    <label class="checkbox" for="fp5_ratio"><?php _e('Use video\'s aspect ratio')?></label>
                    <input class="checkbox" type="checkbox" id="fp5_ratio" name="fp5[ratio]" value="true" checked="checked"/>
                </div>
                <div class="option">
                    <label for="fp5_height"><?php _e('Max height')?></label>
                    <input class="small" type="text" id="fp5_height" name="fp5[height]" readonly="true"/>
                </div>
                <div class="option">
                    <label class="checkbox" for="fp5_fixed"><?php _e('Use fixed player size') ?></label>
                    <input class="checkbox" type="checkbox" id="fp5_fixed" name="fp5[fixed]" value="true" />
                </div>
            </div>
        </div>

        <div class="optgroup separated">
            <label class="head" for="fp5_subtitles">
                <?php _e('You can include subtitles by supplying an URL to a WEBVTT file')?>
                <a href="http://flowplayer.org/docs/subtitles.html" target="_blank"> <?php _e( 'Visit the subtitles documentation' ) ?></a>.
            </label>
            <div class="option wide">
                <label class="head" for="fp5_subtitles">
                    <?php _e('WEBVTT URL')?>
                </label>
                <input class="mediaUrl" type="text" name="fp5[subtitles]" id="fp5_subtitles" />
            </div>
        </div>

        <div class="option wide">
            <input class="button-primary" id="fp5_sendToEditor" type="button" value="<?php _e('Send to Editor &raquo;'); ?>" />
        </div>
    </div>
<?php
}

function fp5_handleAdminMenu() {
    // meta boxes are shown when writing a post. We provide one for easy entry of params to be used in the Flowplayer shortcode.
    add_meta_box( 'flowplayer5', 'Add Flowplayer', 'fp5_metabox', 'video', 'normal' );
}

