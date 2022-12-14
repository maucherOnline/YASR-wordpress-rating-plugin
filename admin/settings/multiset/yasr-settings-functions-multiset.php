<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@gmail.com)

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

function yasr_process_edit_multi_set_form() {

    $error = false;

    if (isset($_POST['yasr_edit_multi_set_form'])) {

        $set_id = $_POST['yasr_edit_multi_set_form'];
        $number_of_stored_elements = $_POST['yasr-edit-form-number-elements'];

        global $wpdb;

        $array_errors = array();

        if (!current_user_can('manage_options')) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die('You are not allowed to be on this page.');
        }

        // Check nonce field
        check_admin_referer('edit-multi-set', 'add-nonce-edit-multi-set');

        //Check if user want to delete entire set
        if (isset($_POST["yasr-remove-multi-set"])) {

            $remove_set = $wpdb->delete(
                YASR_MULTI_SET_NAME_TABLE,
                array(
                    'set_id' => $set_id,
                ),
                array('%d')
            );

            $remove_set_values = $wpdb->delete(
                YASR_MULTI_SET_FIELDS_TABLE,
                array(
                    'parent_set_id' => $set_id,
                ),
                array('%d')
            );

            $remove_set_votes = $wpdb->delete(
                YASR_LOG_MULTI_SET,
                array(
                    'set_type' => $set_id,
                ),
                array('%d')
            );

            if ($remove_set == false) {
                $error          = true;
                $array_errors[] .= __("Something goes wrong trying to delete a Multi Set . Please report it", 'yet-another-stars-rating');
            }

        }

        for ($i = 0; $i <= 9; $i ++) {

            //Than, check if the user want to remove some field
            if (isset($_POST["remove-element-$i"]) && !isset($_POST["yasr-remove-multi-set"])) {

                $field_to_remove = $_POST["remove-element-$i"];

                $remove_field = $wpdb->delete(
                    YASR_MULTI_SET_FIELDS_TABLE,
                    array(
                        'parent_set_id' => $set_id,
                        'field_id'      => $field_to_remove
                    ),
                    array('%d', '%d')
                );

                $remove_values = $wpdb->delete(
                    YASR_LOG_MULTI_SET,
                    array(
                        'set_type' => $set_id,
                        'field_id' => $field_to_remove
                    ),
                    array('%d', '%d')
                );

                if ($remove_field == false) {
                    $error          = true;
                    $array_errors[] = __("Something goes wrong trying to delete a Multi Set's element. Please report it", 'yet-another-stars-rating');
                }


            }  //End if isset $_POST['remove-element-$i']


            //update the stored elements with the new ones
            if (isset($_POST["edit-multi-set-element-$i"]) && !isset($_POST["yasr-remove-multi-set"])
                && !isset($_POST["remove-element-$i"]) && $i <= $number_of_stored_elements) {

                $field_name = $_POST["edit-multi-set-element-$i"];
                $field_id = $_POST["db-id-for-element-$i"];

                //if elements name is shorter than 3 chars
                if (mb_strlen($field_name) < 3) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be at least 3 characters', 'yet-another-stars-rating'),
                        $i);
                    $error = true;
                }

                if (mb_strlen($field_name) > 40) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be shorter than 40 characters', 'yet-another-stars-rating'),
                        $i);
                    $error = true;
                } else {

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
                    if ($field_name_in_database != $field_name) {

                        $insert_field_name = $wpdb->update(
                            YASR_MULTI_SET_FIELDS_TABLE,

                            array(
                                'field_name' => $field_name,
                            ),
                            array(
                                'parent_set_id' => $set_id,
                                'field_id'      => $field_id
                            ),

                            array('%s'),
                            array('%d', '%d')

                        );

                        if ($insert_field_name == false) {
                            $error          = true;
                            $array_errors[] = __("Something goes wrong trying to update a Multi Set's element. Please report it", 'yet-another-stars-rating');
                        }

                    } //End if ($field_name_in_database != $field_name) {

                }

            } //End if (isset($_POST["edit-multi-set-element-$i"]) && !isset($_POST["remove-element-$i"]) && $i<=$number_of_stored_elements )


            //If $i > number of stored elements, user is adding new elements, so we're going to insert the new ones
            if (isset($_POST["edit-multi-set-element-$i"]) && !isset($_POST["yasr-remove-multi-set"]) && !isset($_POST["remove-element-$i"]) && $i > $number_of_stored_elements) {

                $field_name = $_POST["edit-multi-set-element-$i"];

                //if elements name is shorter than 3 chars return error. I use mb_strlen($field_name) > 1
                //because I don't wont return error if an user add an empty field. An empty field will be
                //just ignored
                if (mb_strlen($field_name) > 1 && mb_strlen($field_name) < 3) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be at least 3 characters', 'yet-another-stars-rating'),
                        $i);
                    $error = true;
                }

                if (mb_strlen($field_name) > 40) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be shorter than 40 characters', 'yet-another-stars-rating'),
                        $i);
                    $error          = true;
                } //if field is not empty
                elseif ($field_name != '') {

                    //from version 2.0.9 id is auto_increment by default, still doing this to compatibility for
                    //existing installs where auto_increment didn't work because set_id=1 alredy exists

                    $field_table_new_id = false; //avoid undefined
                    $new_field_id       = false; //avoid undefined

                    $highest_id = $wpdb->get_results("SELECT id FROM " . YASR_MULTI_SET_FIELDS_TABLE . " ORDER BY id DESC LIMIT 1 ");

                    $highest_field_id = $wpdb->get_results("SELECT field_id FROM " . YASR_MULTI_SET_FIELDS_TABLE . " ORDER BY field_id DESC LIMIT 1 ");

                    foreach ($highest_id as $id) {
                        $field_table_new_id = $id->id + 1;
                    }

                    foreach ($highest_field_id as $id) {
                        $new_field_id = $id->field_id + 1;
                    }

                    $insert_set_value = $wpdb->replace(
                        YASR_MULTI_SET_FIELDS_TABLE,
                        array(
                            'id'            => $field_table_new_id,
                            'parent_set_id' => $set_id,
                            'field_name'    => $field_name,
                            'field_id'      => $new_field_id
                        ),
                        array('%d', '%d', '%s', '%d')
                    );

                    if ($insert_set_value === false) {
                        $error          = true;
                        $array_errors[] = __("Something goes wrong trying to insert set field name in edit form. Please report it", 'yet-another-stars-rating');
                    }

                } //end else
            }

        } //End for

        if ($error) {
            return $array_errors;
        }

        echo "<div class=\"updated\"><p><strong>";
        esc_html_e("Settings Saved", 'yet-another-stars-rating');
        echo "</strong></p></div> ";


    } //End if isset( $_POST['yasr_edit_multi_set_form']


} //End yasr_process_edit_multi_set_form() function


?>