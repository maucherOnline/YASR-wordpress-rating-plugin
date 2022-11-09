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

/**
 * Callback function for the spl_autoload_register above.
 * Load classes from yasr_pro/admin/classess and yasr_pro/admin/editor
 *
 * @param $class
 */

class YasrProLoadIncludes {

    public $yasr_stylish_includes;
    public $yasr_cr_includes;
    public $yasr_ur_includes;

    //Use these proprieties to hook
    //https://wordpress.stackexchange.com/questions/386498/remove-action-how-to-access-to-a-method-in-an-child-class

    public function init() {
        /****** Translating YASR Pro ******/
        add_action('init', 'yasr_pro_translate');

        $this->yasr_stylish_includes = new YasrProStylishIncludes();
        $this->yasr_stylish_includes->init();

        $this->yasr_cr_includes = new YasrProCRIncludes();
        $this->yasr_cr_includes->init();

        $this->yasr_ur_includes      = new YasrProURIncludes();
        $this->yasr_ur_includes->init();

        $this->addShortcodes();
    }

    /**
     * Here will be all the shortcodes that doesn't exists in Stylish or CR or UR
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     */
    public function addShortcodes() {
        /**
        These shortcodes accept this parameters:
         *
         * post_id
         * set_id
         * size
         *
         **/
        add_shortcode('yasr_pro_average_multiset',         array($this, 'averageMultisetShortcodeCallback'));
        add_shortcode('yasr_pro_average_visitor_multiset', array($this, 'averageMultisetShortcodeCallback'));
    }

    /**
     * This function is hooked on both yasr_pro_average_multiset and yasr_pro_average_visitor_multiset
     *
     * @param $atts
     * @param $content
     * @param $tag //the shorcode name
     *
     * @return string
     */
    public static function averageMultisetShortcodeCallback($atts, $content, $tag) {
        YasrScriptsLoader::loadOVMultiJs();

        $shortcode_name    = $tag;
        $multiset_average = new YasrProAverageMultiset($atts, $shortcode_name);

        return $multiset_average->printAverageMultiset();
    }
}
