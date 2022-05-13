<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@tiscali.it)

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
 *
 * Originally taken form load_plugin_textdomain and load_textdomain from wp-includes/l10n.php

 * Load a .mo file with the text domain 'yasr-pro'
 *
 * On success, the .mo file will be placed in the $l10n global by $domain
 * and will be a MO object.
 *
 * @since 2.4.1
 *
 * @global MO[] $l10n          An array of all currently loaded text domains.
 * @global MO[] $l10n_unloaded An array of all text domains that have been unloaded again.
 *
 * @return bool True on success, false on failure.
 *
 **/
function yasr_pro_translate() {

    global $l10n, $l10n_unloaded;

    $l10n_unloaded = (array) $l10n_unloaded;

    $domain = 'yasr-pro';
    $locale = determine_locale();

    $mofile = YASR_PRO_LANG_DIR . $domain . '-' . $locale . '.mo';

    if (!is_readable($mofile)) {
        return false;
    }

    $mo = new MO();
    if (!$mo->import_from_file($mofile)) {
        return false;
    }

    if (isset($l10n[$domain])) {
        $mo->merge_with( $l10n[ $domain ] );
    }

    unset($l10n_unloaded[$domain]);

    $l10n[$domain] = &$mo;

    return true;

}


/**
 * Check if image is SVG
 *
 * @author Dario Curvino <@dudo>
 * @since  2.6.8
 *
 * @param $url
 *
 * @return bool
 */
function yasr_check_svg_image($url) {
    if ($url !== '') {

        //check if url is valid
        if (yasr_check_valid_url($url) === true) {

            //ig url is valid, check if is an svg image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $type  = $finfo->buffer(file_get_contents($url));

            if ($type === 'image/svg+xml') {
                return true;
            }
            return false;
        }
        return false;
    }
    return false;

}

/**
 * @author Dario Curvino <@dudo>
 * @since  2.9.5
 * @return int
 */
function yasr_pro_comment_star_size () {
    if(YASR_PRO_UR_COMMENT_STARS_SIZE === 'small') {
        return 16;
    }

    if(YASR_PRO_UR_COMMENT_STARS_SIZE === 'large') {
        return 32;
    }

    return 24;
}

/**
 * Return the int of enabled set id, or false if no set was enabled
 *
 * @author Dario Curvino <@dudo>
 * @since  2.9.7
 *
 * @param int $post_id
 *
 * @return false|int
 */
function yasr_pro_multiset_reviews_enabled ($post_id) {
    $set_id = get_post_meta($post_id, 'yasr_pro_review_setid', true);
    //if post meta exists, return the multiset
    if($set_id !== '') {
        return (int)$set_id;
    }
    return false;
}