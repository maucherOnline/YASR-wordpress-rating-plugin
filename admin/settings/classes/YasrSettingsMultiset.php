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

        $this->addSettingsSections();
        $this->addSettingsFields($option_multiset);

    }

    /**
     * Run add_setting_section for the page
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     */
    public function addSettingsSections() {

        //Add Section for new multiset
        add_settings_section(
            'yasr_new_multiset_form_section_id',
            '',
            '',
            'yasr_new_multiset_form'
        );

        //Add section for edit multiset
        add_settings_section(
            'yasr_edit_multiset_form_section_id',
            '',
            '',
            'yasr_edit_multiset_form'
        );

        //add section for show/hide average
        add_settings_section(
            'yasr_multiset_options_section_id',
            '',
            '',
            'yasr_multiset_tab'
        );
    }

    /**
     * Run addSettingsField for the page
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     */
    public function addSettingsFields ($option_multiset) {
        add_settings_field(
            'add_multi_set',
            yasr_multiset_description(),
            array($this, 'formCreateMultiset'),
            'yasr_new_multiset_form',
            'yasr_new_multiset_form_section_id'
        );

        add_settings_field(
            'manage_multi_set',
            yasr_manage_multiset_description(),
            array($this, 'formManageMultiset'),
            'yasr_edit_multiset_form',
            'yasr_edit_multiset_form_section_id'
        );

        add_settings_field(
            'yasr_multiset_hide_average_id',
            yasr_show_average_multiset_description(),
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

    /**
     * Shows a form to edit the Multi Set
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     */
    public function formManageMultiset() {
        ?>
        <div>
            <?php yasr_edit_multi_form(); ?>
            <div id="yasr-multi-set-response" style="display:none">
            </div>
        </div>
        <?php
    }

    /**
     * Show option to show/hide average
     *
     * @author Dario Curvino <@dudo>
     * @since 3.1.3
     * @param $option_multiset
     */
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

        <?php

    }


}