<?php

class YasrSettingsMultiset {
    public function init () {
        add_action('admin_init', array($this, 'manageMultiset')); //This is for general options
    }

    public function manageMultiset () {
        register_setting(
            'yasr_multiset_options_group', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
            'yasr_multiset_options', //The name of an option to sanitize and save.
            'yasr_sanitize_multiset_options'
        );

        $option_multiset = get_option('yasr_multiset_options');

        if ($option_multiset === false) {
            $option_multiset = array(
                'show_average' => 'no'
            );
        }

        if (!isset($option_multiset['show_average'])) {
            $option_multiset['show_average'] = 'yes';
        }

        add_settings_section(
            'yasr_multiset_options_section_id',
            '',
            '',
            'yasr_multiset_tab'
        );

        add_settings_field(
            'add_multi_set',
            yasr_multiset_description(),
            array($this, 'formCreateMultiset'),
            'yasr_multiset_tab',
            'yasr_multiset_options_section_id'
        );

        add_settings_field(
            'manage_multi_set',
            yasr_manage_multiset_description(),
            array($this, 'formManageMultiset'),
            'yasr_multiset_tab',
            'yasr_multiset_options_section_id'
        );
        add_settings_field(
            'yasr_multiset_hide_average_id',
            __('Show average?', 'yet-another-stars-rating'),
            array($this, 'hideAverage'),
            'yasr_multiset_tab',
            'yasr_multiset_options_section_id',
            $option_multiset
        );
    }

    public function formCreateMultiset () {
        ?>
        <div class="yasr-new-multi-set">
            <?php yasr_display_multi_set_form(); ?>
        </div>
        <?php
    }

    public function formManageMultiset() {
        ?>
        <div>
            <?php yasr_edit_multi_form(); ?>
            <div id="yasr-multi-set-response" style="display:none">
            </div>
        </div>
        <?php
    }

    public function hideAverage($option_multiset) {

        ?>

        <div class="yasr-onoffswitch-big">
            <input type="checkbox" name="yasr_multiset_options[show_average]" class="yasr-onoffswitch-checkbox"
                   id="yasr-multiset-options-show-average-switch" <?php if ($option_multiset['show_average'] === 'yes') {
                echo " checked='checked' ";
            } ?> >
            <label class="yasr-onoffswitch-label" for="yasr-multiset-options-show-average-switch">
                <span class="yasr-onoffswitch-inner"></span>
                <span class="yasr-onoffswitch-switch"></span>
            </label>
        </div>

        <br/>

        <br/>

        <?php

        esc_html_e('If you select no, the "Average" row will not be displayed. 
        You can override this in the single multi set by using the parameter "show_average"',
            'yet-another-stars-rating');

    }


}