<?php

/**
 * Class YasrSettingsStyle
 *
 * @author Dario Curvino <@dudo>
 * @since 3.1.9
 */
class YasrSettingsStyle {
    public function init() {
        //Add setting field to choose the image for the free version
        add_action('yasr_style_options_add_settings_field', array($this, 'settingsFieldFreeChooseImage'));

        //hook into options
        add_filter('yasr_filter_style_options', array($this, 'defaultStarSet'));
    }

    /**
     * Add setting field for free version
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $style_options
     *
     * @return void
     */
    function settingsFieldFreeChooseImage($style_options) {
        add_settings_field(
            'yasr_style_options_choose_stars_lite',
            __('Choose Stars Set', 'yet-another-stars-rating'),
            array($this, 'settingsFieldFreeChooseImageHTML'),
            'yasr_style_tab',
            'yasr_style_options_section_id',
            $style_options
        );
    }

    /**
     * Print the html with the radios to choose the image to use
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $style_options
     *
     * @return void
     */
    function settingsFieldFreeChooseImageHTML($style_options) {
        ?>
        <div class='yasr-select-img-container' id='yasr_pro_custom_set_choosen_stars'>
            <div>
                <input type='radio'
                       name='yasr_style_options[stars_set_free]'
                       value='rater'
                       id="radio-img-rater"
                       class='yasr-general-options-scheme-color'
                    <?php if ($style_options['stars_set_free'] === 'rater') {
                        echo 'checked="checked"';
                    } ?> />
                <label for="radio-img-rater">
                <span class='yasr_pro_stars_set'>
                    <?php
                    echo '<img src="' . esc_url(YASR_IMG_DIR . 'stars_rater.png').'">';
                    ?>
                </span>
                </label>
            </div>
            <div>
                <input type='radio' name='yasr_style_options[stars_set_free]' value='rater-yasr' id="radio-img-yasr"
                       class='yasr-general-options-scheme-color' <?php if ($style_options['stars_set_free'] === 'rater-yasr') {
                    echo 'checked="checked"';
                } ?> />
                <label for="radio-img-yasr">
                <span class='yasr_pro_stars_set'>
                    <?php
                    echo '<img src="' . esc_url(YASR_IMG_DIR . 'stars_rater_yasr.png').'">';
                    ?>
                </span>
                </label>
            </div>
            <div>
                <input type='radio' name='yasr_style_options[stars_set_free]' value='rater-oxy' id="radio-img-oxy"
                       class='yasr-general-options-scheme-color' <?php if ($style_options['stars_set_free'] === 'rater-oxy') {
                    echo 'checked="checked"';
                } ?> />
                <label for="radio-img-oxy">
                <span class='yasr_pro_stars_set'>
                    <?php
                    echo '<img src="' . esc_url(YASR_IMG_DIR . 'stars_rater_oxy.png').'">';
                    ?>
                </span>
                </label>
            </div>
        </div>

        <hr />

        <div id="yasr-settings-stylish-stars" style="margin-top: 30px">
            <div id="yasr-settings-stylish-image-container">
                <?php
                echo '<img id="yasr-settings-stylish-image" src=' . esc_url(YASR_IMG_DIR . 'yasr-pro-stars.png').'>';
                ?>
            </div>
        </div>

        <div id='yasr-settings-stylish-text'>
            <?php
            $text = __('Looking for more?', 'yet-another-stars-rating');
            $text .= '<br />';
            $text .= sprintf(__('Upgrade to %s', 'yet-another-stars-rating'), '<a href="?page=yasr_settings_page-pricing">Yasr Pro!</a>');

            echo wp_kses_post($text);
            ?>
        </div>

        <script type="text/javascript">
            jQuery('#yasr-settings-stylish-stars').mouseover(function () {
                jQuery('#yasr-settings-stylish-text').css("visibility", "visible");
                jQuery('#yasr-settings-stylish-image').css("opacity", 0.4);
            });
        </script>

        <?php
        submit_button(__('Save Settings', 'yet-another-stars-rating'));
    }

    /**
     * @author Dario Curvino <@dudo>
     *
     * Filter the $style_options and, if a default value doesn't exist,
     * set 'rater-yasr' as default
     * 
     * @param $style_options
     *
     * @return mixed
     */
    public static function defaultStarSet($style_options) {
        if (!array_key_exists('stars_set_free', $style_options)) {
            $style_options['stars_set_free'] = 'rater-yasr'; //..default value if not exists
        }
        return $style_options;
    }

}