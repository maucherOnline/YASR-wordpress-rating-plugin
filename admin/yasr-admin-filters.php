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

add_filter('yasr_filter_style_options', 'yasr_filter_style_options_callback');
function yasr_filter_style_options_callback($style_options) {

    if (!array_key_exists('stars_set_free', $style_options)) {
        $style_options['stars_set_free'] = 'rater-yasr'; //..default value if not exists
    }

    return $style_options;
}

global $yasr_fs;

/**
 * https://freemius.com/help/documentation/selling-with-freemius/free-trials/
 *
 * With this hook I change the default freemius behavior to show trial message after 1 week instead of 1 day
 */
$yasr_fs->add_filter( 'show_first_trial_after_n_sec', static function ($day_in_sec) {
    return WEEK_IN_SECONDS;
} );

/**
 * https://freemius.com/help/documentation/selling-with-freemius/free-trials/
 *
 * With this hook I change the default freemius behavior to show trial every 60 days instead of 30
 */
$yasr_fs->add_filter( 'reshow_trial_after_every_n_sec', static function ($thirty_days_in_sec) {
    return 2 * MONTH_IN_SECONDS;
} );
