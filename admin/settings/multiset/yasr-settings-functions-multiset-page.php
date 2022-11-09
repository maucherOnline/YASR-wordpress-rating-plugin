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

    <div>
        <form method="post">
            <?php
                //There is no setting to save here, I'm using these functions for better UIX
                do_settings_sections('yasr_new_multiset_form');
            ?>
        </form>

        <!-- Must be into another form -->
        <form method="post">
            <?php
                //There is no setting to save here, I'm using these functions for better UIX
                do_settings_sections('yasr_edit_multiset_form');
            ?>
        </form>


        <!--This allows to choose if show average or no, must be inside the form-->
        <form action="options.php" method="post" id="yasr_multiset_form">
            <?php
            settings_fields('yasr_multiset_options_group');
            do_settings_sections('yasr_multiset_tab');
            submit_button(esc_html__('Save', 'yet-another-stars-rating'));
            ?>
        </form>
    </div>

</div>
