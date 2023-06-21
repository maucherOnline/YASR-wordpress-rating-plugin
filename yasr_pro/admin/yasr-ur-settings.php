<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/****** YASR PRO GENERAL SETTINGS ******/
add_action('admin_init', 'yasr_pro_ur_general_options_init');

function yasr_pro_ur_general_options_init() {
    register_setting(
        'yasr_ur_general_options_group', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
        'yasr_ur_general_options', //The name of an option to sanitize and save.
        'yasr_pro_ur_general_options_sanitize'
    );

    $general_options = get_option('yasr_ur_general_options');

    $yasr_settings_descriptions = new YasrSettingsDescriptions();

    //general options are not found
    if (!$general_options) {
        $general_options['comment_stars_auto_insert'] = 'no';
        $general_options['comment_stars_size']        = 'medium';
        $general_options['comment_allow_anonymous']   = 'no';
        $general_options['comment_rich_snippet']      = 'no';
        $general_options['comment_rating_mandatory']  = 'no';
        $general_options['text_after_stars']          = '';
        $general_options['text_after_stars_archive']  = '';
    }

    add_settings_section(
        'yasr_ur_general_options_section_id',
        __('Yasr User Reviews Options', 'yasr-pro'),
        'yasr_pro_ur_general_options_callback',
        'yasr_ur_general_options_tab'
    );

    add_settings_field('yasr_ur_comments_review',
        __('Reviews In Comments', 'yasr-pro'),
        'yasr_pro_ur_comments_review',
        'yasr_ur_general_options_tab',
        'yasr_ur_general_options_section_id',
        $general_options
    );
    add_settings_field('yasr_ur_custom_text',
        yasr_pro_ur_customize_string_description($yasr_settings_descriptions),
        'yasr_pro_custom_text_average_comments_ratings',
        'yasr_ur_general_options_tab',
        'yasr_ur_general_options_section_id',
        $general_options)
    ;

}

function yasr_pro_ur_general_options_callback() {
    //
}

function yasr_pro_ur_comments_review($general_options) {
    ?>
    <div class="yasr-settings-row-35">
        <div>
            <strong><?php esc_html_e('Allow anonymous?', 'yasr-pro'); ?></strong>
            <br/>

            <div class="yasr-onoffswitch-big">
                <input type="checkbox" name="yasr_ur_general_options[comment_allow_anonymous]"
                       class="yasr-onoffswitch-checkbox" value="yes"
                       id="yasr-ur-comment-allow-anonymous-switch" <?php if ($general_options['comment_allow_anonymous'] === 'yes') {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-ur-comment-allow-anonymous-switch">
                    <span class="yasr-onoffswitch-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>
        </div>

        <div>

            <strong>
                <?php esc_html_e('Size?', 'yasr-pro'); ?>
            </strong>
            <br/>

            <div class="yasr-indented-answer">
                <?php
                    $name  = 'yasr_ur_general_options[comment_stars_size]';
                    $class = 'yasr-pro-comments-review-class';
                    $id    = 'yasr-pro-comment-reviews-options-size-';
                    echo yasr_kses(
                        YasrSettings::radioSelectSize($name, $class, $general_options['comment_stars_size'], $id)
                    );
                ?>
            </div>
        </div>
    </div>

    <p>&nbsp;</p>

    <strong>
        <?php esc_html_e('Enable on every post and page?', 'yasr-pro'); ?>
    </strong>
    <br/>

    <div class="yasr-settings-row-35">
        <div>
            <div class="yasr-onoffswitch-big">
                <input type="checkbox" name="yasr_ur_general_options[comment_stars_auto_insert]"
                       class="yasr-onoffswitch-checkbox" value="yes"
                       id="yasr-ur-comment-stars-auto-insert" <?php if ($general_options['comment_stars_auto_insert'] === 'yes') {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-ur-comment-stars-auto-insert">
                    <span class="yasr-onoffswitch-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <div>
            <div id="yasr-pro-review-in-comment-auto-insert-explained">
                <?php esc_html_e("By enabling this, in every comment form YASR will add the fields in order to enable your visitors to add their own reviews.", 'yasr-pro'); ?>
                <br/>
                <?php esc_html_e("If you choose \"Yes\" but want to exclude a specific post or page, just open the editor page and disable it. ", 'yasr-pro'); ?>
                <br/>
                <?php esc_html_e("Vice versa if you choose \"No\". ", 'yasr-pro'); ?>
            </div>
        </div>
    </div>

    <div class="yasr-settings-row-35">
        <div>
            <strong>
                <?php esc_html_e('Should rating and title review be mandatory?', 'yasr-pro'); ?>
            </strong>
            <div class="yasr-onoffswitch-big">
                <input type="checkbox" name="yasr_ur_general_options[comment_rating_mandatory]"
                       class="yasr-onoffswitch-checkbox" value="yes"
                       id="yasr-ur-comment-rating-mandatory" <?php if ($general_options['comment_rating_mandatory'] === 'yes') {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-ur-comment-rating-mandatory">
                    <span class="yasr-onoffswitch-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <div>
            <strong>
                <?php esc_html_e('Create Rich Snippet for comments?', 'yasr-pro'); ?>
            </strong>
            <div class="yasr-onoffswitch-big">
                <input type="checkbox" name="yasr_ur_general_options[comment_rich_snippet]"
                       class="yasr-onoffswitch-checkbox" value="yes"
                       id="yasr-ur-comment-rich-snippet" <?php if ($general_options['comment_rich_snippet'] === 'yes') {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-ur-comment-rich-snippet">
                    <span class="yasr-onoffswitch-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>
        </div>
    </div>

    <br /><br/>
    <hr />

    <?php

}

function yasr_pro_custom_text_average_comments_ratings ($general_options) {
    ?>
    <div>
        <?php
        $custom_text = array(
            'text_after_stars' => array (
                'name'        => 'text_after_stars',
                'description' => __('Custom text to display in single post or page', 'yasr-pro'),
                'placeholder' => '%total_count% votes, average: %average%',
                'id'          => 'yasr-pro-custom-text-comments-ratings',
                'class'       => 'yasr-pro-custom-text-comments-ratings'
            ),
            'text_after_stars_archive'       => array (
                'name'        => 'text_after_stars_archive',
                'description' => __('Custom text to display in archive pages', 'yet-another-stars-rating'),
                'placeholder' => '(%total_count%)',
                'id'          => 'yasr-pro-custom-text-comments-ratings-archive',
                'class'       => 'yasr-pro-custom-text-comments-ratings'
            ),
        );
        ?>

        <div class="yasr-settings-row-45">
            <div id="yasr-pro-ur-custom-text">
                <?php
                    YasrSettings::echoSettingFields($custom_text, $general_options, 'yasr_ur_general_options');
                ?>
                <input type="button"
                       id="yasr-pro-ur-default-custom-texts"
                       class="button"
                       value="<?php esc_attr_e('Restore defaults', 'yet-another-stars-rating') ?>">
            </div>

            <div id="yasr-pro-ur-custom-text-div">
                <div class="yasr-help-box-settings" style="display: block">
                    <?php
                    $string_custom_visitor =
                        sprintf(__('You can use %s pattern to show the total count, and %s pattern to show the average',
                            'yet-another-stars-rating'),
                        '<strong>%total_count%</strong>', '<strong>%average%</strong>');

                    esc_html_e('Leave a field empty to disable it.', 'yet-another-stars-rating');
                    echo '<br /><br/>';
                    echo wp_kses_post($string_custom_visitor);
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php

}

/**
 * Show description for "customize strings"
 *
 * @author Dario Curvino <@dudo>
 *
 * @since 3.4.1
 *
 * @param $yasr_settings_descriptions
 *
 * @return mixed
 */
function yasr_pro_ur_customize_string_description($yasr_settings_descriptions) {
    $name = esc_html__('Customize strings', 'yet-another-stars-rating');

    $description = sprintf(
        esc_html__(
            'Insert custom text to show after yasr_pro_average_comments_ratings shortcode', 'yasr-pro'
        ), '<br />'
    );

    return $yasr_settings_descriptions->settingsFieldDescription($name, $description);
}

function yasr_pro_ur_general_options_sanitize($general_options) {
    foreach ($general_options as $key => $value) {
        // Check to see if the current option has a value. If so, process it.
        if (isset($value)) {
            //Tags are not allowed for any fields
            $allowed_tags = '';

            $general_options[$key] = strip_tags(stripslashes($value), $allowed_tags);
        }
    }

    $general_options['comment_allow_anonymous']   = YasrSettings::whitelistSettings($general_options, 'comment_allow_anonymous', 'no', 'yes');
    $general_options['comment_stars_auto_insert'] = YasrSettings::whitelistSettings($general_options, 'comment_stars_auto_insert', 'no', 'yes');
    $general_options['comment_rating_mandatory']  = YasrSettings::whitelistSettings($general_options, 'comment_rating_mandatory', 'no', 'yes');
    $general_options['comment_rich_snippet']      = YasrSettings::whitelistSettings($general_options, 'comment_rich_snippet', 'no', 'yes');

    return $general_options;

}

?>
