<?php

class YasrSettingsMultiset {
    public function init () {
        add_action('admin_init', array($this, 'manageMultiset')); //This is for general options
    }

    public function manageMultiset () {
        register_setting(
            'yasr_multiset_options_group', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
            'yasr_multiset_options', //The name of an option to sanitize and save.
            array($this, 'sanitize')
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

    /**
     * Output the form to create a new multiset
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     */
    public function formCreateMultiset () {
        ?>
        <div class="yasr-new-multi-set">
            <div style="width: 70%;">
                <div class="yasr-multi-set-form-headers">
                    <?php esc_html_e('Add new Multi Set', 'yet-another-stars-rating'); ?>
                </div>
                <div style="margin-bottom: 10px;">
                    <span style="color: #FEB209; font-size: x-large"> | </span> = required
                </div>

                <div>
                    <?php
                        wp_nonce_field('add-multi-set', 'add-nonce-new-multi-set'); //Must be inside the form
                        $multiset_name_info = esc_html__('Unique name to identify your set.', 'yet-another-stars-rating');
                    ?>
                    <div>
                        <div>
                            <br />
                            <div id="yasr-multiset-page-new-set-criteria-name" class="criteria-row">
                                <label for="new-multi-set-name">
                                </label>
                                <input type="text"
                                       name="multi-set-name"
                                       id="new-multi-set-name"
                                       class="input-text-multi-set"
                                       placeholder="Name"
                                       required
                                >
                                <span class="dashicons dashicons-info yasr-multiset-info-delete"
                                      title="<?php echo esc_attr($multiset_name_info) ?>"></span>
                            </div>
                        </div>

                        <?php $this->newMultiCriteria(); ?>

                        <div>
                            <button class="button-secondary" id="new-criteria-button">
                                <span class="dashicons dashicons-insert" style="line-height: 1.4"></span>
                                <?php esc_html_e('Add new Criteria', 'yet-another-stars-rating'); ?>
                            </button>
                        </div>
                    </div>
                    <br />
                    <div>
                        <p>
                            <input type="submit"
                                   value="<?php esc_attr_e('Create New Set', 'yet-another-stars-rating') ?>"
                                   class="button-primary"
                            />
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Output the multicriteria form
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     */
    public function newMultiCriteria () {
        ?>
            <div id="new-set-criteria-container">
                <?php
                for ($i = 1; $i <= 4; $i ++) {
                    $element_n =  esc_html__('Element ', 'yet-another-stars-rating') . '#'.$i;
                    $name         = 'multi-set-name-element-'.$i;
                    $id           = 'multi-set-name-element-'.$i;
                    $id_container = 'criteria-row-container-'.$i;

                    $required  = '';

                    if($i === 1) {
                        $placeholder = 'Story';
                        $required    = 'required';
                    }
                    elseif($i === 2) {
                        $placeholder = 'Gameplay';
                        $required    = 'required';
                    }
                    elseif($i === 3) {
                        $placeholder = 'Graphics';
                    }
                    elseif($i === 4) {
                        $placeholder = 'Sound';
                    }
                    else {
                        $placeholder = $element_n;
                    }

                    $this->outputCriteria($id_container, $i, $id, $name, $placeholder, $required);
                } //End foreach
                ?>
            </div>
        <?php
    }

    /**
     * Output the single criteria row
     *
     * @author Dario Curvino <@dudo>
     * @since 3.1.3
     * @param $id_container
     * @param $i
     * @param $id
     * @param $name
     * @param $placeholder
     * @param $required
     */
    public function outputCriteria ($id_container, $i, $id, $name, $placeholder, $required) {
        ?>
        <div class="criteria-row removable-criteria"
             id="<?php echo esc_attr($id_container) ?>"
             value="<?php echo esc_attr($i) ?>">
            <label for="<?php echo esc_attr($id); ?>">
            </label>
            <input type="text"
                   name="<?php echo esc_attr($name); ?>"
                   id="<?php echo esc_attr($id); ?>"
                   class="input-text-multi-set"
                   placeholder="<?php echo esc_attr($placeholder); ?>"
                   <?php echo esc_attr($required) ?>
            >

            <?php
                if($required !== 'required') {
                    echo '<span class="dashicons dashicons-remove yasr-multiset-info-delete criteria-delete" 
                                id="remove-criteria-'.esc_attr($i).'"
                                data-id-criteria="'.esc_attr($id_container).'">
                          </span>';
                }
            ?>
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

    /**
     * Sanitize
     *
     * @author Dario Curvino <@dudo>
     * @since 3.1.3
     * @param $option_multiset
     *
     * @return mixed
     */
    public function sanitize($option_multiset) {

        if (is_array($option_multiset)) {
            if( ! array_key_exists('show_average', $option_multiset)) {
                $option_multiset['show_average'] = 'no';
            } else {
                $option_multiset['show_average'] = 'yes';
            }
        } else {
            $option_multiset['show_average'] = 'no';
        }

        return $option_multiset;

    }

}