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
            <div style="width: 49%;">
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

            <?php $this->formManageMultiset(); ?>
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
        <div class="yasr-manage-multiset" style="width: 49%;">
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
            <?php
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
     * Show the description for "Show average" row in multi criteria setting page
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


    /****************************** METHODS THAT RUN ON $_POST FROM HERE *******************************/


    /**
     * Save a new multi set
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
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

        $multi_set_name = $this->validateMandatoryFields();

        if($multi_set_name === false) {
            return;
        }

        $fields_name = array();
        $elements_filled = 0;

        //@todo increase number of element that can be stored
        for ($i = 1; $i <= 9; $i ++) {
            if (isset($_POST["multi-set-name-element-$i"]) && $_POST["multi-set-name-element-$i"] !== '') {
                $fields_name[$i] = $_POST["multi-set-name-element-$i"];

                $length_ok = $this->checkStringLength($fields_name[$i], $i);

                if($length_ok === 'ok') {
                    $elements_filled ++;
                } else {
                    YasrSettings::printNoticeError($length_ok);
                }
            }
        }

        $this->insertMultiset($multi_set_name, $elements_filled, $fields_name);
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return false|string
     */
    private function validateMandatoryFields() {
        //If these fields are not empty go ahead
        if ($_POST['multi-set-name'] === ''
            || $_POST['multi-set-name-element-1'] === ''
            || $_POST['multi-set-name-element-2'] === '') {
            YasrSettings::printNoticeError(
                __('Multi Set\'s name and first 2 elements can\'t be empty',
                    'yet-another-stars-rating')
            );
            return false;
        }

        $multi_set_name        = ucfirst(strtolower($_POST['multi-set-name']));
        $multi_set_name_exists = $this->multisetNameExists($multi_set_name);

        if($multi_set_name_exists !== false) {
            YasrSettings::printNoticeError($multi_set_name_exists);
            return false;
        }

        //If multi set name is shorter than 3 chars return error
        if (mb_strlen($multi_set_name) < 3) {
            YasrSettings::printNoticeError(__('Multi Set name must be longer than 3 chars', 'yet-another-stars-rating'));
            return false;
        }

        if (mb_strlen($multi_set_name) > 40) {
            YasrSettings::printNoticeError(__('Multi Set name must be shorter than 40 chars', 'yet-another-stars-rating'));
            return false;
        }

        return $multi_set_name;
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
     * @since  refactor 3.1.7
     * @return void
     */
    private function insertMultiset($multi_set_name, $elements_filled, $fields) {
        $error_message = __('Something goes wrong trying insert set field name. Please report it',
            'yet-another-stars-rating');

        $insert_multi_name_success = $this->saveMultisetName($multi_set_name);

        //If multi set name has been inserted, now we're going to insert elements
        if ($insert_multi_name_success !== false) {
            $insert_set_value = $this->saveMultisetFields($elements_filled, $fields);

            //Everything is ok
            if ($insert_set_value) {
                YasrSettings::printNoticeSuccess(__('Settings Saved', 'yet-another-stars-rating'));
            }
            //If there was an error saving the fields, delete the set name and print error
            else {
                $this->deleteMultisetName($multi_set_name);
                YasrSettings::printNoticeError($error_message);
            }
        }  else {
            YasrSettings::printNoticeError($error_message);
        }
    }

    /**
     * Save Multiset name and return query result
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $set_name
     *
     * @since
     * @return bool|int|\mysqli_result|resource|null
     */
    private function saveMultisetName ($set_name) {
        global $wpdb;

        return $wpdb->replace(
            YASR_MULTI_SET_NAME_TABLE,
            array(
                'set_name' => $set_name
            ),
            array('%s')
        );
    }

    /**
     * Call this when a new multiset is being created
     *
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

        //Here, I'm sure that the last id of YASR_MULTI_SET_NAME_TABLE is the set I'm saving now
        $parent_set_id = $wpdb->get_results(
            "SELECT MAX(set_id) as id
                   FROM " . YASR_MULTI_SET_NAME_TABLE,
            ARRAY_A);

        $insert_set_value   = false; //avoid undefined

        for ($i = 1; $i <= $elements_filled; $i ++) {
            $insert_set_value = $this->saveField($parent_set_id[0]['id'], $fields[$i], $i);
        }

        return $insert_set_value;
    }

    /**
     * Save the single set field
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $set_id
     * @param $field_name
     * @param $field_id
     * @param bool|int $id   This is only needed to support table created with YASR before of 2.0.9
     *
     * @since  3.1.7
     * @return bool|int|\mysqli_result|resource|null
     */
    private function saveField($set_id, $field_name, $field_id, $id=false) {
        global $wpdb;

        //default where, without id because is auto increment
        $where_array = array(
            'parent_set_id' => $set_id,
            'field_name'    => $field_name,
            'field_id'      => $field_id
        );
        $format_array =  array('%d', '%s', '%d');

        //This is to keep compatibility with versions INSTALLED before 2.0.9
        if($id !== false && is_int($id)) {
            $where_array['id'] = $id;
            $format_array[] = '%d';
        }

        //do the replacement
        return $wpdb->replace(
            YASR_MULTI_SET_FIELDS_TABLE,
            $where_array,
            $format_array
        );
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
            if ($multi_set_name === $set_name->set_name) {
                return __('You already have a set with this name.', 'yet-another-stars-rating');
            }
        }

        return false;
    }


    /**
     * Called in yasr-settings-multiset, is run when $_POST['yasr_edit_multi_set_form'] isset
     *
     * @author Dario Curvino <@dudo>
     * @since  refactor 3.1.7
     * @return void
     */
    public function editMultiset() {
        if (!isset($_POST['yasr_edit_multi_set_form'])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die('You are not allowed to be on this page.');
        }

        // Check nonce field
        check_admin_referer('edit-multi-set', 'add-nonce-edit-multi-set');

        $set_id                    = (int)$_POST['yasr_edit_multi_set_form'];
        $number_of_stored_elements = (int)$_POST['yasr-edit-form-number-elements'];

        //If is checked to remove all the set, delete set and return
        if($this->editMultisetRemoveSetChecked($set_id) === 'error') {
            return;
        }

        for ($i = 0; $i <= 9; $i ++) {
            //find if exists some fields to delete, WITHOUT RETURN if true
            if($this->editMultisetRemoveFieldChecked($i, $set_id) === 'error') {
                return;
            }

            if($this->editMultisetFieldUpdated($i, $number_of_stored_elements, $set_id) === 'error') {
                return;
            }

            if($this->editMultisetNewFieldAdded($i, $number_of_stored_elements, $set_id) === 'error') {
                return;
            }
        } //End for

        YasrSettings::printNoticeSuccess(__('Settings Saved'));

    } //End function

    /**
     * Find if the checkbox yasr-remove-multi-set is checked
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $set_id
     *
     * @since  3.1.7
     * @return bool
     */
    private function editMultisetRemoveSetChecked($set_id) {
        //Check if user want to delete entire set
        if (isset($_POST["yasr-remove-multi-set"])) {
            $remove_set = $this->deleteAllMultisetData($set_id);
            if ($remove_set === false) {
                YasrSettings::printNoticeError(
                    __('Something goes wrong trying to delete a Multi Set . Please report it',
                        'yet-another-stars-rating'));
            }
            return 'error';
        }

        return false;
    }


    /**
     * Find if a checkbox with prefix remove-element- is checked
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $i
     * @param $set_id
     *
     * @since 3.1.7
     * @return bool|string|void
     */
    private function editMultisetRemoveFieldChecked($i, $set_id) {
        $i = (int)$i;
        $element = 'remove-element-'.$i;

        //If checkbox is not checked, return
        if(!isset($_POST[$element])) {
            return;
        }

        //Then, check if the user want to remove some field
        $field_to_remove = $_POST[$element];
        $field_removed   = $this->deleteMultisetField($set_id, $field_to_remove);

        if ($field_removed === false) {
            YasrSettings::printNoticeError(__("Something goes wrong trying to delete a Multi Set's element. Please report it",
                'yet-another-stars-rating'));

            return 'error';
        }

        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     *
     * @param $i
     * @param $number_of_stored_elements
     * @param $set_id
     *
     * @since  3.1.7
     * @return string|void|false
     */
    private function editMultisetFieldUpdated ($i, $number_of_stored_elements, $set_id) {
        global $wpdb;

        if(!isset($_POST["edit-multi-set-element-$i"]) || $i > $number_of_stored_elements) {
            return;
        }

        //update the stored elements with the new ones
        $field_name = $_POST["edit-multi-set-element-$i"];
        $field_id   = $_POST["db-id-for-element-$i"];

        $length_ok = $this->checkStringLength($field_name, $i);

        if($length_ok !== 'ok') {
            YasrSettings::printNoticeError($length_ok);
            return;
        }

        //Check if field name is changed
        $field_name_in_db = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT field_name FROM "
                . YASR_MULTI_SET_FIELDS_TABLE .
                " WHERE field_id=%d AND parent_set_id=%d",
                $field_id, $set_id));

        $field_name_in_database = null; //avoid undefined
        foreach ($field_name_in_db as $field_in_db) {
            $field_name_in_database = $field_in_db->field_name;
        }

        //if field name in db is different from field name in form update it
        if ($field_name_in_database !== $field_name) {
            $field_updated = $this->updateMultisetField($field_name, $set_id, $field_id);

            if ($field_updated === false) {
                YasrSettings::printNoticeError(__("Something goes wrong trying to update a Multi Set's element. Please report it",
                    'yet-another-stars-rating'));
                return 'error';
            }
        }

        return false;

    }

    /**
     * @author Dario Curvino <@dudo>
     *
     * @param $i
     * @param $number_of_stored_elements
     * @param $set_id
     *
     * @since
     * @return false|void
     */
    private function editMultisetNewFieldAdded($i, $number_of_stored_elements, $set_id) {
        if(!isset($_POST["edit-multi-set-element-$i"]) || $i <= $number_of_stored_elements) {
            return;
        }

        //If $i > number of stored elements, user is adding new elements, so we're going to insert the new ones
        $field_name   = $_POST["edit-multi-set-element-$i"];

        global $wpdb;

        //if elements name is shorter than 3 chars return error.
        //I don't want return error if a user add an empty field here.
        //An empty field will be just ignored
        $length_ok = $this->checkStringLength($field_name, $i, true);

        if($length_ok !== 'ok') {
            YasrSettings::printNoticeError($length_ok);
            return;
        }

        if ($field_name !== '') {
            //get the new field id
            $highest_field_id = $wpdb->get_results(
                "SELECT field_id FROM " . YASR_MULTI_SET_FIELDS_TABLE . " 
                                ORDER BY field_id 
                                DESC LIMIT 1",
                ARRAY_A);

            //since version 2.0.9 id is auto_increment by default, still doing this to compatibility for
            //existing installs where auto_increment didn't work because set_id=1 already exists
            $existing_id = $wpdb->get_results("SELECT MAX(id) as id FROM " . YASR_MULTI_SET_FIELDS_TABLE, ARRAY_A);

            $new_field_id =  $highest_field_id[0]['field_id']+1;
            $new_id       =  $existing_id[0]['id']+1;

            $insert_set_value = $this->saveField(
                $set_id,
                $field_name,
                $new_field_id,
                $new_id
            );

            if ($insert_set_value === false) {
                YasrSettings::printNoticeError(__('Something goes wrong trying to insert set field name in edit form. Please report it',
                    'yet-another-stars-rating'));

                return 'error';
            }

        } //end if $field_name != ''

        return false;
    }

    /**
     * Here is safe to use set_name, instead of id, because a set name is saved only if doesn't exist another with the
     * same name
     *
     * @author Dario Curvino <@dudo>
     *
     * @param bool $set_name
     * @param bool $set_id
     *
     * @since  3.1.7
     * @return int|false|void
     */
    private function deleteMultisetName($set_name=false, $set_id=false) {
        global $wpdb;

        if($set_name) {
            return $wpdb->delete(
                YASR_MULTI_SET_NAME_TABLE,
                array(
                    'set_name' => $set_name
                ),
                array('%s')
            );
        }

        if($set_id) {
            return $wpdb->delete(
                YASR_MULTI_SET_NAME_TABLE,
                array(
                    'set_id' => $set_id,
                ),
                array('%d')
            );
        }

    }

    /**
     * Remove a specific field from a multiset along with the data
     *
     * @author Dario Curvino <@dudo>
     * @since
     * @return int|false
     */
    private function deleteMultisetField($set_id, $field_to_remove) {
        global $wpdb;

        //remove field
        $field_removed = $wpdb->delete(
            YASR_MULTI_SET_FIELDS_TABLE,
            array(
                'parent_set_id' => $set_id,
                'field_id'      => $field_to_remove
            ),
            array('%d', '%d')
        );

        //if field is removed, delete all the data
        if($field_removed !== false) {
            $wpdb->delete(
                YASR_LOG_MULTI_SET,
                array(
                    'set_type' => $set_id,
                    'field_id' => $field_to_remove
                ),
                array('%d', '%d')
            );
        }

        return $field_removed;

    }

    /**
     * Remove *ALL* multiset data
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $set_id
     *
     * @since  3.1.7
     * @return false|int|null
     */
    private function deleteAllMultisetData($set_id) {
        global $wpdb;

        $remove_set_name = $this->deleteMultisetName(false, $set_id);

        //if the set name has been removed, delete all the data
        if($remove_set_name !== false) {
            $wpdb->delete(
                YASR_MULTI_SET_FIELDS_TABLE, array(
                    'parent_set_id' => $set_id,
                ), array('%d')
            );

            $wpdb->delete(
                YASR_LOG_MULTI_SET, array(
                    'set_type' => $set_id,
                ), array('%d')
            );
        }

        return $remove_set_name;
    }

    /**
     * Update a field
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $field_name
     * @param $set_id
     * @param $field_id
     *
     * @since
     * @return bool|int|\mysqli_result|resource|null
     */
    private function updateMultisetField($field_name, $set_id, $field_id) {
        global $wpdb;

        return $wpdb->update(
            YASR_MULTI_SET_FIELDS_TABLE,

            //value to update
            array(
                'field_name' => $field_name,
            ),
            //where
            array(
                'parent_set_id' => $set_id,
                'field_id'      => $field_id
            ),

            array('%s'),
            array('%d', '%d')

        );
    }


    /**
     * Return 'ok' if string is of the correct length, or an error otherwise
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $string
     * @param $i
     * @param bool $empty_allowed
     *
     * @since  3.1.7
     * @return string
     */
    private function checkStringLength($string, $i, $empty_allowed=false) {
        $i = (int)$i;
        $length = mb_strlen($string);

        if($empty_allowed === true) {
            if ($length>1 && $length < 3) {
                return sprintf(
                    __('Field # %d must be at least 3 chars', 'yet-another-stars-rating'),
                    $i
                );
            }
        }

        if ($length < 3) {
            return sprintf(
                __('Field # %d must be at least 3 chars', 'yet-another-stars-rating'),
                $i
            );
        }

        if ($length > 40) {
            return sprintf(
                __('Field # %d must be shorter than 40 chars', 'yet-another-stars-rating'),
                $i
            );
        }

        return 'ok';
    }

}