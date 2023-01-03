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


function yasr_color_scheme_multiset_callback($style_options) {

    $array_options = array (
        'light' => __('Light', 'yet-another-stars-rating'),
        'dark'  => __('Dark', 'yet-another-stars-rating')
    );
    $default = $style_options['scheme_color_multiset'];
    $name    = 'yasr_style_options[scheme_color_multiset]';
    $class   = 'yasr-general-options-scheme-color';
    $id      = 'yasr-style-options-color-scheme';

    echo yasr_kses(YasrPhpFieldsHelper::radio('', $class, $array_options, $name, $default, $id));
    ?>

    <br/>

    <a href="#" id="yasr-color-scheme-preview-link">
        <?php esc_html_e("Preview", 'yet-another-stars-rating') ?>
    </a>

    <div id="yasr-color-scheme-preview" style="display:none">
        <?php
            esc_html_e("Light theme", 'yet-another-stars-rating');
            echo '<br /><br /><img src="' . esc_url(YASR_IMG_DIR . 'yasr-multi-set.png').'" alt="light-multiset">';

            echo "<br /> <br />";

            esc_html_e("Dark theme", 'yet-another-stars-rating');
            echo '<br /><br /><img src="' . esc_url(YASR_IMG_DIR . 'dark-multi-set.png').'" alt="dark-multiset">';
            ?>
    </div>

    <p>

    <?php
}

function yasr_style_options_textarea_callback($style_options) {
    esc_html_e('Please use text area below to write your own CSS styles to override the default ones.',
    'yet-another-stars-rating');
    echo "<br /><strong>";
    esc_html_e('Leave it blank if you don\'t know what you\'re doing.', 'yet-another-stars-rating');
    echo "</strong><p>";
    ?>

    <label for='yasr_style_options_textarea'></label><textarea
    rows='17'
    cols='40'
    name='yasr_style_options[textarea]'
    id='yasr_style_options_textarea'><?php echo esc_textarea($style_options['textarea']); ?></textarea>

    <?php

}

//sanitize
function yasr_style_options_sanitize($style_options) {
    $style_options = apply_filters('yasr_sanitize_style_options', $style_options);
    $output = array();

    foreach ($style_options as $key => $value) {
        $output[$key] = sanitize_textarea_field($style_options[$key]);
    }

    return $output;
}

?>