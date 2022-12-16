<?php

class YasrSettingsMultiset {

    public function init () {
        //This is for general options
        add_action('admin_init', array($this, 'manageMultiset'));

        //Add Ajax Endpoint to manage more multi set
        add_action('wp_ajax_yasr_get_multi_set', array($this, 'editFormAjax'));
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
            'add_multi_set', $this->descriptionMultiset(),
            array($this, 'formCreateMultiset'),
            'yasr_new_multiset_form',
            'yasr_new_multiset_form_section_id'
        );

        add_settings_field(
            'manage_multi_set', $this->descriptionManageMultiset(),
            array($this, 'formManageMultiset'),
            'yasr_edit_multiset_form',
            'yasr_edit_multiset_form_section_id'
        );

        add_settings_field(
            'yasr_multiset_hide_average_id', $this->descriptionShowAverage(),
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
        <div class="yasr-manage-multiset">
            <div class="yasr-multi-set-form-headers">
                <?php esc_html_e('Manage Multi Set', 'yet-another-stars-rating'); ?>
            </div>

            <?php
                global $wpdb;
                $multi_set   = YasrDB::returnMultiSetNames();
                $n_multi_set = $wpdb->num_rows; //wpdb->num_rows always store the last of the last query

                if($n_multi_set < 1 ) {
                    esc_html_e('No Multi Set were found', 'yet-another-stars-rating');
                    return;
                }

                if ($n_multi_set === 1) {
                    $set_id     = $multi_set[0]->set_id;
                    $this->formEditMultiset($set_id);
                } //End if n_multi_set >1

                //n_multiset > 1 here
                else {
                    $this->manageManyMultiset($multi_set);
                }
            ?>
            <input type="hidden" value="<?php echo esc_attr($n_multi_set); ?>" id="n-multiset">

            <div id="yasr-multi-set-response" style="display:none">
            </div>
        </div>
        <?php
    }



    /**
     * Print the form to edit the multi set
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $set_id
     *
     * @since  3.1.7
     * @return void
     */
    public function formEditMultiset($set_id) {
        $set_fields = YasrDB::multisetFieldsAndID((int)$set_id);
        ?>
        <form action=" <?php echo esc_url(admin_url('options-general.php?page=yasr_settings_page&tab=manage_multi')); ?>"
              id="form_edit_multi_set" method="post">

            <input type="hidden" name="yasr_edit_multi_set_form" value="<?php echo esc_attr($set_id); ?>"/>

            <table id="yasr-table-form-edit-multi-set">
                <?php
                    $this->formEditMultisetPrintHeaders();
                    $i = $this->formEditMultisetPrintRow($set_fields);
                    $this->formEditMultisetPrintRemoveRow($i, $set_id);
                ?>
            </table>
            <?
                wp_nonce_field('edit-multi-set', 'add-nonce-edit-multi-set')
            ?>

            <div id="yasr-element-limit" style="display:none; color:red">
                <?php esc_html_e("You can use up to 9 elements", 'yet-another-stars-rating') ?>
            </div>

            <?php $this->editFormPrintButtons(); ?>
        </form>
        <?php
    }

    /**
     * Print the select
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $multi_set
     *
     * @since  3.1.7
     * @return void
     */
    private function manageManyMultiset($multi_set) {
        $title = __('Which set do you want to edit or remove?', 'yet-another-stars-rating');
        $id    = 'yasr_select_edit_set';
        YasrPhpFieldsHelper::printSelectMultiset($multi_set, $title, $id);
        ?>

        <button href="#" class="button-delete" id="yasr-button-select-set-edit-form">
            <?php esc_html_e('Select'); ?>
        </button>

        <?php
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    private function formEditMultisetPrintHeaders() {
        ?>
        <tr>
            <th id="yasr-table-form-edit-multi-set-header">
                <?php esc_html_e('Field name', 'yet-another-stars-rating') ?>
            </th>

            <th id="yasr-table-form-edit-multi-set-remove">
                <?php esc_html_e('Remove', 'yet-another-stars-rating') ?>
            </th>
        </tr>
        <?php
    }

    /**
     * Print the single row for edit form and return the number of set fields
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $set_fields
     *
     * @since  refactor in 3.1.7
     * @return int
     */
    private function formEditMultisetPrintRow($set_fields) {
        $i = 1;
        foreach ($set_fields as $field) {
            $input_name    = 'edit-multi-set-element-'.$i;
            $hidden_name   = 'db-id-for-element-'.$i;
            $checkbox_name = 'remove-element-'.$i;
            ?>
            <tr>
                <td style="width: 80%">
                    Element #<?php echo esc_html($i) ?>
                    <label>
                        <input type="text"
                               value="<?php echo esc_attr($field['name']);?>"
                               name="<?php  echo esc_attr($input_name) ?>"
                        />
                    </label>
                    <input type="hidden"
                           value="<?php echo esc_attr($field['id']) ?>"
                           name="<?php  echo esc_attr($hidden_name) ?>"
                    />
                </td>

                <td style="width:20%; text-align:center">
                    <label>
                        <input type="checkbox"
                               value="<?php echo esc_attr($field['id']) ?>"
                               name="<?php echo esc_attr($checkbox_name) ?>"
                        >
                    </label>
                </td>
            </tr>
            <?php
            $i ++;
        }

        //return the number of the rows
        return $i-1;
    }

    /**
     * Return the row with the checkbox to remove the entire set
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $i
     * @param $set_id
     *
     * @since 3.1.7
     * @return void
     */
    private function formEditMultisetPrintRemoveRow($i, $set_id) {
        ?>
        <input type="hidden"
               name="yasr-edit-form-number-elements"
               id="yasr-edit-form-number-elements"
               value="<?php echo esc_attr($i)?>"
        >
        <tr class="yasr-edit-form-remove-entire-set" id="yasr-edit-form-remove-entire-set">
            <td style="width: 80%;">
                <?php echo esc_html__('Remove whole set?', 'yet-another-stars-rating')?>
            </td>

            <td style="text-align:center; width: 20%;">
                <label>
                    <input type="checkbox"
                           name="yasr-remove-multi-set"
                           value="<?php echo esc_attr($set_id)?>"
                </label>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <?php
                    esc_html_e("If you remove something you will remove all the votes for that set or field. This operation CAN'T BE undone.",
                'yet-another-stars-rating'); ?>
                <p>&nbsp;</p>
            </td>
        </tr>
        <?php
    }

    /**
     * Print edit forms buttons
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    private function editFormPrintButtons () {
        ?>
        <div>
            <input type="button"
                   class="button-delete"
                   id="yasr-add-field-edit-multiset"
                   value="<?php esc_attr_e('Add element', 'yet-another-stars-rating'); ?>"
            >

            <input type="submit"
                   value="<?php esc_attr_e('Save changes', 'yet-another-stars-rating') ?>"
                   class="button-primary">
        </div>
        <?php
    }

    /**
     * Ajax Callback to print the edit form
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function editFormAjax() {
        if(!current_user_can('manage_options')) {
            die('Not Allowed');
        }
        $set_id = (int)$_POST['set_id'];
        $this->formEditMultiset($set_id);
        die();
    } //End function

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

    /**
     * Describe what is a Multiset in the setting page
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     * @return string
     */
    public function descriptionMultiset() {
        $title = esc_html__('Multi-criteria based rating system.', 'yet-another-stars-rating');

        $div = '<div class="yasr-settings-description">';

        $description = sprintf(
            esc_html__(
                'A Multi-criteria set allows you to insert a rating for each aspect of your review (up to nine rows).
                    %s Once you\'ve saved it, you can insert 
                    the rates while typing your article in the %s box below the editor.%s %s
                    See it in action %s here%s .', 'yet-another-stars-rating'
            ), '<br />', '<a href=' . esc_url(YASR_IMG_DIR . 'yasr-multi-set-insert-rating.png') . ' target="_blank">',
            '</a>', '<br />', '<a href=' . esc_url(
                "https://yetanotherstarsrating.com/yasr-shortcodes/?utm_source=wp-plugin&utm_medium=settings_resources&utm_campaign=yasr_settings&utm_content=yasr_newmultiset_desc#yasr-multiset-shortcodes"
            ) . '  target="_blank">', '</a>'
        );

        return $title . $div . $description . '</div>';

    }

    /**
     * Description for setting field "edit multiset"
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     * @return string
     */
    public function descriptionManageMultiset() {
        $title = esc_html__('Manage Multi Set', 'yet-another-stars-rating');

        $div = '<div class="yasr-settings-description">';

        $description = esc_html__('Add or remove an element, or the entire set.');

        return $title . $div . $description . '</div>';
    }

    /**
     * Show the description for "Show average" row in multi set setting page
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.3
     * @return string
     */
    public function descriptionShowAverage() {
        $title = esc_html__('Show average?', 'yet-another-stars-rating');

        $div = '<div class="yasr-settings-description">';

        $description = esc_html__(
            'If you select no, the "Average" row will not be displayed. 
        You can override this in the single multi set by using the parameter "show_average".',
            'yet-another-stars-rating'
        );

        return $title . $div . $description . '</div>';
    }

    /**
     * Save a new multi set
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return array|void
     */
    public function saveNewMultiSet() {
        if (!isset($_POST['multi-set-name'])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die('You are not allowed to be on this page.');
        }

        // Check nonce field
        check_admin_referer('add-multi-set', 'add-nonce-new-multi-set');

        //IF these fields are not empty go ahead
        if ($_POST['multi-set-name'] === ''
            || $_POST['multi-set-name-element-1'] === ''
            || $_POST['multi-set-name-element-2'] === '') {

            return array(__('Multi Set\'s name and first 2 elements can\'t be empty', 'yet-another-stars-rating'));
        }

        $multi_set_name        = ucfirst(strtolower($_POST['multi-set-name']));
        $multi_set_name_exists = $this->multisetNameExists($multi_set_name);

        if($multi_set_name_exists !== false) {
            return array($multi_set_name_exists);
        }

        //If multi set name is shorter than 3 chars return error
        if (mb_strlen($multi_set_name) < 3) {
            return array(__('Multi Set name must be longer than 3 chars', 'yet-another-stars-rating'));
        }

        if (mb_strlen($multi_set_name) > 40) {
            return array(__('Multi Set name must be shorter than 40 chars', 'yet-another-stars-rating'));
        }

        $array_error = array();
        $fields_name = array();
        $elements_filled = 0;

        //@todo increase number of element that can be stored
        for ($i = 1; $i <= 9; $i ++) {
            if (isset($_POST["multi-set-name-element-$i"]) && $_POST["multi-set-name-element-$i"] != '') {
                $fields_name[$i] = $_POST["multi-set-name-element-$i"];

                $length_ok = $this->checkStringLength($fields_name[$i], $i);

                if($length_ok === 'ok') {
                    $elements_filled ++;
                } else {
                    $array_error[] = $length_ok;
                }
            }
        }

        if(!empty($array_error)) {
            return $array_error;
        }

        $this->insertMultiset($multi_set_name, $elements_filled, $fields_name);
    }


    /**
     * Save Multi Set data
     *
     * @author Dario Curvino <@dudo>
     *
     * @param string $multi_set_name
     * @param int    $elements_filled
     * @param array  $fields
     *
     * @since 3.1.7
     * @return void
     */
    private function insertMultiset($multi_set_name, $elements_filled, $fields) {
        $insert_multi_name_success = $this->saveMultisetName($multi_set_name);

        //If multi set name has been inserted, now we're going to insert elements
        if ($insert_multi_name_success !== false) {
            $insert_set_value = $this->saveMultisetFields($elements_filled, $fields);

            if ($insert_set_value) {
                echo '<div class="updated"><p><strong>';
                           esc_html_e('Settings Saved', 'yet-another-stars-rating');
                echo '</strong></p></div>';
            } else {
                esc_html_e('Something goes wrong trying insert set field name. Please report it',
                    'yet-another-stars-rating');
            }
        }  else {
            esc_html_e('Something goes wrong trying insert Multi Set name. Please report it',
                'yet-another-stars-rating');
        }
    }

    /**
     * Save Multiset name and return query result
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $multi_set_name
     *
     * @since
     * @return bool|int|\mysqli_result|resource|null
     */
    private function saveMultisetName ($multi_set_name) {
        global $wpdb;

        return $wpdb->replace(
            YASR_MULTI_SET_NAME_TABLE,
            array(
                'set_name' => $multi_set_name
            ),
            array('%s')
        );
    }

    /**
     * @author Dario Curvino <@dudo>
     *
     * @param int   $elements_filled
     * @param array $fields
     *
     * @since
     * @return bool|int|\mysqli_result|resource|null
     */
    private function saveMultisetFields ($elements_filled, $fields) {
        global $wpdb;

        //get the highest id in table
        $parent_set_id = $wpdb->get_results(
            "SELECT MAX(set_id) as id
                      FROM " . YASR_MULTI_SET_NAME_TABLE, ARRAY_A);

        $insert_set_value   = false; //avoid undefined

        for ($i = 1; $i <= $elements_filled; $i ++) {
            $insert_set_value = $wpdb->replace(
                YASR_MULTI_SET_FIELDS_TABLE,
                array(
                    'parent_set_id' => $parent_set_id[0]['id'],
                    'field_name'    => $fields[$i],
                    'field_id'      => $i
                ),
                array('%d', '%s', '%d')
            );
        } //End for

        return $insert_set_value;
    }

    /**
     * Return error if multiset with give name already exists
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $multi_set_name
     *
     * @since  3.1.7
     * @return false|string
     */
    private function multisetNameExists($multi_set_name) {
        //Get all multiset names
        $check_name_exists = YasrDB::returnMultiSetNames();

        foreach ($check_name_exists as $set_name) {
            if ($multi_set_name == $set_name->set_name) {
                return __('You already have a set with this name.', 'yet-another-stars-rating');
            }
        }

        return false;
    }

    /**
     * Return 'ok' if string is of the correct length, or an error otherwise
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $string
     * @param $i
     *
     * @since  3.1.7
     * @return string
     */
    private function checkStringLength($string, $i) {
        $i = (int)$i;
        $length = mb_strlen($string);

        if ($length < 3) {
            return sprintf(
                __('Field # %d must be at least 3 characters', 'yet-another-stars-rating'),
                $i
            );
        }

        if ($length > 40) {
            return sprintf(
                __('Field # %d must be shorter than 40 characters', 'yet-another-stars-rating'),
                $i
            );
        }

        return 'ok';
    }

}