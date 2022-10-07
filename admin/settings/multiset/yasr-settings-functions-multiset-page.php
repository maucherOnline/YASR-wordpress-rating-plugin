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
                        esc_html_e('A Multi Set allows you to insert a rating for each aspect of your review (up to nine rows).',
                        'yet-another-stars-rating');
                    ?>
                    <br />
                    <?php
                        esc_html_e('It is possible to create up to 99 different Multi Set. Once you\'ve saved it, you can insert 
                        the rates while typing your article in the box below the editor, as you can see in this image (click to see it larger)',
                        'yet-another-stars-rating');
                    ?>
                </div>
            </th>

            <td>
                <div>
                    <div class="yasr-new-multi-set">
                        <?php yasr_display_multi_set_form(); ?>
                    </div>
                </div>

                <?php /*
                <div class="yasr-multi-set-right">
                    <div id="yasr-multi-set-doc-box" >
                        <?php esc_html_e(
                            "Multi Set allows you to insert a rate for each aspect about the product / local business / 
                            whetever you're reviewing, example in the image below.",
                            'yet-another-stars-rating'
                        );

                        echo "<br /><br /><img src=" . YASR_IMG_DIR . "/yasr-multi-set.png alt='multiset'> <br /> <br />";

                        esc_html_e(
                            "You can create up to 99 different Multi Set and each one can contain up to 9 different fields. 
                        Once you've saved it, you can insert the rates while typing your article in the box below the editor, 
                        as you can see in this image (click to see it larger)",
                            'yet-another-stars-rating'
                        );

                        echo "<br /><br /><a href=\"" . YASR_IMG_DIR . "yasr-multi-set-insert-rate.jpg\"><img src=" . YASR_IMG_DIR . "/yasr-multi-set-insert-rate-small.jpg></a> <br /> <br />";

                        esc_html_e(
                            'In order to insert your Multi Sets into a post or page, you can either past the short code that will 
                        appear at the bottom of the box or just click on the star in the graphic editor and select "Insert Multi Set".',
                            'yet-another-stars-rating'
                        );

                        ?>
                        <br/> <br/>
                        <a href="#" id="yasr-multi-set-doc-link-hide">
                            <?php esc_html_e("Close this message", 'yet-another-stars-rating') ?>
                        </a>
                    </div>
                </div>
                */
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
