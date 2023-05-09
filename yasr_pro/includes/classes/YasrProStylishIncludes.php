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

class YasrProStylishIncludes {

    public function init() {
        global $yasr_load_script;

        remove_action('yasr_add_front_script_css', array($yasr_load_script, 'loadInlineCss'));
        remove_action('yasr_add_admin_scripts_end', array($yasr_load_script, 'loadInlineCss'));

        add_action('yasr_add_front_script_css', array($this, 'replaceCss'));
        add_action('yasr_add_admin_scripts_end', array($this, 'replaceCss'));

    }

    /**
     * Add inline CSS
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     */
    public function replaceCss() {
        $style_options = json_decode(YASR_STYLE_OPTIONS, true);

        if (!isset($style_options['stars_set'])) {
            $style_options['stars_set'] = false;
        }

        $array_icons = array();

        if (isset($style_options['custom_image_inactive']) && isset($style_options['custom_image_active'])) {
            if (yasr_check_svg_image($style_options['custom_image_active']) === true && yasr_check_svg_image($style_options['custom_image_inactive']) === true) {
                $array_icons['icon_0'] = $style_options['custom_image_inactive'];
                $array_icons['icon_1'] = $style_options['custom_image_active'];
            } else {
                $array_icons = $this->useExistingStarsSet($style_options['stars_set']);
            }
        } else {
            $array_icons = $this->useExistingStarsSet($style_options['stars_set']);
        }


        $yasr_st_css = ".yasr-star-rating {
            background-image: url(\"$array_icons[icon_0]\");
        }
        .yasr-star-rating .yasr-star-value {
            background: url(\"$array_icons[icon_1]\") ;
        }";

        wp_add_inline_style('yasrcss', $yasr_st_css);

    }

    /**
     * Return an array with the name of the images to use choosen between the ones provided by YASR
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     * @param $selected_set
     *
     * @return array
     */
    public function useExistingStarsSet($selected_set) {

        $array_icons = array();

        if ($selected_set) {
            //if the text _second is found, remove it and use _2.svg as extension
            if (strpos($selected_set, '_second')) {
                $file_name = str_replace('_second', '', $selected_set);
                $extension = '_2.svg';
            }  else {
                $file_name = $selected_set;
                $extension = '_1.svg';
            }

            //set file name
            $array_icons['icon_0'] = YASR_PRO_ST_IMG_DIR . $file_name . '_0.svg';
            $array_icons['icon_1'] = YASR_PRO_ST_IMG_DIR . $file_name . $extension;

        } //default stars if not set
        else {
            $array_icons['icon_0'] = YASR_PRO_ST_IMG_DIR . '0yasr_0.svg';
            $array_icons['icon_1'] = YASR_PRO_ST_IMG_DIR . '0yasr_1.svg';
        }

        return $array_icons;

    }


}
