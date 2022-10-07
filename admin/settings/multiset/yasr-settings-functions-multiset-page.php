<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

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

$error_new_multi_set  = yasr_process_new_multi_set_form(); //defined in yasr-settings-functions
$error_edit_multi_set = yasr_process_edit_multi_set_form(); //defined in yasr-settings-functions

if ($error_new_multi_set) {
    echo "<div class='error'> <p> <strong>";

    foreach ($error_new_multi_set as $error) {
        echo wp_kses_post ($error);
        echo "<br />";
    }

    echo "</strong></p></div>";
}

if ($error_edit_multi_set) {
    echo "<div class='error'> <p> <strong>";

    foreach ($error_edit_multi_set as $error) {
        echo wp_kses_post ($error);
        echo "<br />";
    }

    echo "</strong></p></div>";
}

global $wpdb;

$multi_set = YasrMultiSetData::returnMultiSetNames();
$n_multi_set = $wpdb->num_rows; //wpdb->num_rows always store the last of the last query

?>
<input type="hidden" value="<?php echo esc_attr($n_multi_set); ?>" id="n-multiset">

<div class="yasr-settings-div">

    <h3> <?php esc_html_e('Manage Multi Set', 'yet-another-stars-rating'); ?></h3>

    <table class="form-table" role="presentation">
        <tbody>
        <tr>
            <th scope="row">
                <div class="yasr-settings-description">
                    <?php
                    $description = sprintf(
                        esc_html__('A Multi Set allows you to insert a rating for each aspect of your review (up to nine rows), %s
                        %s example %s . %s
                        It is possible to create up to 99 different Multi Set. %s Once you\'ve saved it, you can insert 
                        the rates while typing your article in the %sbox below the editor.%s',
                            'yet-another-stars-rating'
                        ),
                        '<br />',
                                 '<a href='.esc_url(YASR_IMG_DIR . 'yasr-multi-set.png') .'>',
                                 '</a>',
                                 '<br />',
                                 '<br />',
                                 '<a href='.esc_url(YASR_IMG_DIR . 'yasr-multi-set-insert-rating.png') .'>',
                                 '</a>'
                    );

                    echo $description;

                    ?>
                </div>
            </th>

            <td>
                <div>
                    <div class="yasr-new-multi-set">
                        <?php yasr_display_multi_set_form(); ?>
                    </div>
                </div>
                ?>
            </td>
        </tr>

        <tr>
            <th scope="row">
                TEXT
            </th>
            <td>
                <div>
                    <?php yasr_edit_multi_form(); ?>
                    <div id="yasr-multi-set-response" style="display:none">
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row">
                TEXT
            </th>
            <td>
                <div class="yasr-multi-set-choose-theme">
                <!--This allow to choose if show average or no-->
                <form action="options.php" method="post" id="yasr_multiset_form">
                    <?php
                    settings_fields('yasr_multiset_options_group');
                    do_settings_sections('yasr_multiset_tab');
                    submit_button(esc_html__('Save', 'yet-another-stars-rating'));
                    ?>
                </form>
            </div>
            </td>
        </tr>
        </tbody>
    </table>

</div>
