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

/**
 * @author Dario Curvino <@dudo>
 * @since 3.0.8
 * Class YasrProScriptsLoader
 */
class YasrProScriptsLoader {

    /**
     * Load reviewsInComments.js file
     *
     * @author Dario Curvino <@dudo>
     * @since  3.0.8
     */
    public static function loadReviewsInComments () {
        YasrScriptsLoader::loadRequiredJs();

        wp_enqueue_script('reviewsInComments',
            YASR_PRO_JS_DIR . 'reviewsInComments.js',
            array('jquery', 'yasr-global-functions', 'yasr-window-var'),
            YASR_VERSION_NUM,
            true
        );

        wp_enqueue_style(
            'reviewsInCommentsCss',
            YASR_PRO_CSS_DIR . 'reviewsInComments.css',
            false,
            YASR_VERSION_NUM
        );
    }
}