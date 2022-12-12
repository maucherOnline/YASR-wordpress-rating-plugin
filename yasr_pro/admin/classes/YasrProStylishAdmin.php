<?php

/*
Copyright 2015 Dario Curvino (email : d.curvino@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProStylishAdmin {

    public function init() {
        //Remove YASR in style tab Free Action
        remove_action('yasr_style_options_add_settings_field', 'yasr_style_options_add_settings_field_callback');

        //Add 2 new settings fields in style tab
        add_action('yasr_style_options_add_settings_field', array ($this, 'addSettingsFields'));

        add_filter('yasr_sanitize_style_options', array($this, 'sanitizeOptions'));
    }


    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     *
     * @param $style_options
     */
    public function addSettingsFields($style_options) {

        add_settings_field(
            'yasr_st_upload_stars',
            sprintf(__('Custom Star Set %s', 'yasr-pro'), '<span class="dashicons dashicons-unlock"></span>'),
            array ($this, 'formUploadStars'),
            'yasr_style_tab',
            'yasr_style_options_section_id',
            $style_options);

        add_settings_field(
            'yasr_st_choose_stars_radio',
            sprintf(__('Choose Stars Set %s', 'yasr-pro'), '<span class="dashicons dashicons-unlock"></span>'),
            array ($this, 'chooseStarsRadio'),
            'yasr_style_tab',
            'yasr_style_options_section_id',
            $style_options);

    }

    /**
     * Print 2 input fields with button to upload stars
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     *
     * @param $style_options
     */
    public function formUploadStars($style_options) {

        if (!isset($style_options['custom_image_inactive'])) {
            $style_options['custom_image_inactive'] = null;
        }

        if (!isset($style_options['custom_image_active'])) {
            $style_options['custom_image_active'] = null;
        }

        ?>

        <div class="yasr-custom-stars-uploaders" style="width: 100%">
            <div style="font-size: 14px; color: #23282d; font-weight: 600">
                <?php esc_html_e('Upload Custom Icons', 'yasr-pro'); ?>
            </div>
            <br/>
            <div>
                <label for="yasr-custom-image-inactive" class="yasr-pro-text-upload-image">
                    <?php esc_html_e('"Off" image', 'yasr-pro') ?>
                </label>
                <input class="yasr-pro-input-text-upload-image"
                       type="text"
                       name="yasr_style_options[custom_image_inactive]"
                       id="yasr-custom-image-inactive"
                       value="<?php echo esc_attr($style_options['custom_image_inactive']); ?>"
                >

                <!-- Print preview -->
                <?php if ($style_options['custom_image_inactive']) { ?>
                    <span class="yasr_uploaded_stars_preview" id='yasr_pro_star_inactive_preview'>
                    <img src="<?php echo esc_url($style_options['custom_image_inactive']) ?>"
                         width="32" height="32" alt="inactive"
                    >
                </span>
                <?php } ?>

                <button class="button-primary yasr-pro-upload-image">
                    <?php esc_html_e('Upload', 'yasr-pro'); ?>
                </button>
            </div>
            <div>
                <label for="yasr-custom-image-active" class="yasr-pro-text-upload-image">
                    <?php esc_html_e('"Active" image', 'yasr-pro') ?>
                </label>
                <input class="yasr-pro-input-text-upload-image"
                       type="text"
                       name="yasr_style_options[custom_image_active]"
                       id="yasr-custom-image-active"
                       value="<?php echo esc_attr($style_options['custom_image_active']); ?>"
                >

                <!-- Print preview -->
                <?php if ($style_options['custom_image_active']) { ?>
                    <span class="yasr_uploaded_stars_preview" id='yasr_pro_star_active_preview'>
                <img src='<?php echo esc_url($style_options['custom_image_active']); ?>'
                     alt="active" width="32" height="32"></span>
                <?php } ?>

                <button class="button-primary yasr-pro-upload-image">
                    <?php esc_html_e('Upload', 'yasr-pro'); ?>
                </button>
            </div>
        </div>

        <div class="yasr-indented-answer" style="margin: 25px;">
            <?php
            $text = sprintf(
                __('%s (You need a plugin like %s to upload it). %s Aspect ratio must be 1:1 and width x height at least 32x32', 'yasr-pro'),
                '<strong>Svg Only.</strong>',
                '<a href="https://wordpress.org/plugins/safe-svg/">Safe Svg</a>',
                '<br />');

            echo $text;
            ?>
        </div>

        <?php

    }

    /**
     *
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     *
     * @param $style_options
     */
    public function chooseStarsRadio($style_options) {

        $folder_img = YASR_PRO_ABSOLUTE_PATH_INCLUDES . '/img/stars/thumb/'; //must use absolute path, not plugin_url
        $filetype   = '*.png';

        //create an array with the  folder content
        $array_file = glob($folder_img . $filetype);

        //Sorting array in "natural order"
        natsort($array_file);

        if (!isset($style_options['stars_set']) || $style_options['stars_set'] === false) {
            $style_options['stars_set'] = '0yasr';
        }

        echo '<div class="yasr-select-img-container">';
        foreach ($array_file as $single_file) {
            $filename_ext = basename($single_file); //File name with extension
            $img_url      = YASR_PRO_ST_IMG_DIR . 'thumb/' . $filename_ext; //File name absolute path
            $filename     = pathinfo($filename_ext, PATHINFO_FILENAME); //Filename without ext
            ?>
            <div>
                <input type='radio'
                       name='yasr_style_options[stars_set]'
                       value='<?php echo $filename; ?>'
                       id='yasr_pro_choosen_stars_<?php echo $filename ?>'
                    <?php if ($style_options['stars_set'] === $filename) {
                        echo " checked='checked' ";
                    } ?>
                />
                <label for='yasr_pro_choosen_stars_<?php echo $filename ?>'>
                    <span>
                        <img src='<?php echo $img_url; ?> '
                             width="32"
                             height="64"
                             alt='yasr_pro_choosen_stars_<?php echo $filename ?>'
                        >
                    </span>
                </label>
            </div>

            <?php

        }

        echo '</div>';

        ?>

        <p>&nbsp;</p>

        <button class="button-secondary" id="yasr-st-reset-stars">Reset</button>

        <?php submit_button(__('Save Settings'), 'primary', 'submit', false); ?>

        <p>&nbsp;</p>

        <hr>

        <?php

    } //End function yasr_pro_choose_stars_radio_callback

    /**
     * Sanitize the stylish options
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     *
     * @param $style_options
     *
     * @return mixed
     */
    public function sanitizeOptions($style_options) {
        foreach ($style_options as $key => $value) {
            //if key is custom_image_inactive or custom_image_active
            if ($key === 'custom_image_inactive' || $key === 'custom_image_active') {
                //if is set (empty is ok)
                if ($value !== '') {
                    $is_svg_and_url = yasr_check_svg_image($value);

                    if ($is_svg_and_url !== true) {
                        wp_die('Custom Icon is not a valid svg image');
                    }
                }
            }
        }
        return $style_options;
    }
}
