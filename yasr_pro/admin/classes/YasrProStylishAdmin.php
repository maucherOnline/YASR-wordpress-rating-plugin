<?php

/*
Copyright 2015 Dario Curvino (email : d.curvino@gmail.com)

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

class YasrProStylishAdmin {

    public function init() {
        add_filter('yasr_sanitize_style_options', array($this, 'sanitizeOptions'));
    }

    /**
     * Sanitize the stylish options
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     *
     * @param $style_options
     *
     * @return mixed
     */
    public function sanitizeOptions($style_options) {
        foreach ($style_options as $key => $value) {
            //if key is custom_image_inactive or custom_image_active
            if ($key === 'custom_image_inactive' || $key === 'custom_image_active') {
                //if is set (empty is ok)
                if ($value !== '') {
                    $is_svg_and_url = yasr_check_svg_image($value);

                    if ($is_svg_and_url !== true) {
                        wp_die($is_svg_and_url);
                    }
                }
            }
        }
        return $style_options;
    }
}
