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

function yasr_save_option_imported_plugin($plugin) {

    //get actual data
    $plugin_imported = get_option('yasr_plugin_imported');
    //Add plugin just imported as a key
    $plugin_imported[$plugin] = array('date' => date('Y-m-d H:i:s'));
    //update option
    update_option('yasr_plugin_imported', $plugin_imported, false);
}

function yasr_import_plugin_alert_box($plugin, $number_of_queries) {

    echo '<div class="yasr-alert-box">';
        echo wp_kses_post(sprintf(__(
            'To import %s seems like %s %d %s INSERT queries are necessary. %s
                There is nothing wrong with that, but some hosting provider can have a query limit/hour. %s
                I strongly suggest to contact your hosting and ask about your plan limit',
            'yet-another-stars-rating'
        ),$plugin, '<strong>', $number_of_queries, '</strong>', '<br />','<br />'));
    echo '</div>';

}

